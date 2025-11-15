<?php

namespace App\Libraries;

class WeatherService
{
    private $baseUrl = 'https://api.open-meteo.com/v1/forecast';
    private $cacheDir;
    private $cacheDuration = 3600; // 1 hour in seconds

    public function __construct()
    {
        // Set cache directory
        $this->cacheDir = WRITEPATH . 'cache/weather/';
        
        // Create cache directory if it doesn't exist
        if (!is_dir($this->cacheDir)) {
            mkdir($this->cacheDir, 0755, true);
        }
        
        // Set timezone to Philippines
        date_default_timezone_set('Asia/Manila');
    }

    /**
     * Get weather forecast for a city
     * @param float $latitude
     * @param float $longitude
     * @return array|null Weather data or null on error
     */
    public function getWeather($latitude, $longitude)
    {
        // Create cache key based on coordinates
        $cacheKey = md5($latitude . '_' . $longitude);
        $cacheFile = $this->cacheDir . $cacheKey . '.json';

        // Check if cache exists and is still valid
        if (file_exists($cacheFile)) {
            $cacheData = json_decode(file_get_contents($cacheFile), true);
            $cacheTime = $cacheData['cached_at'] ?? 0;
            
            // If cache is less than 1 hour old, return cached data
            if (time() - $cacheTime < $this->cacheDuration) {
                $weatherData = $cacheData['weather'];
                // Remove old cached_time if it exists
                unset($weatherData['cached_time']);
                // Add fresh info
                $weatherData['source'] = 'cache';
                $weatherData['cached_time'] = date('M j, Y g:i A', $cacheTime);
                return $weatherData;
            }
        }

        // Build API URL with parameters
        $params = [
            'latitude' => $latitude,
            'longitude' => $longitude,
            'hourly' => 'temperature_2m,precipitation,weathercode',
            'timezone' => 'auto',
            'forecast_days' => 2
        ];

        $url = $this->baseUrl . '?' . http_build_query($params);

        // Make API request
        $client = \Config\Services::curlrequest();
        
        try {
            $response = $client->get($url);
            $data = json_decode($response->getBody(), true);

            if (!$data || !isset($data['hourly'])) {
                return null;
            }

            // Process and return formatted weather data
            $weatherData = $this->formatWeatherData($data);
            
            // Add source info (timestamp will be added when reading cache)
            $weatherData['source'] = 'live';

            // Cache the weather data without timestamp
            $cacheData = [
                'weather' => $weatherData,
                'cached_at' => time()
            ];
            file_put_contents($cacheFile, json_encode($cacheData));

            // Add timestamp for display
            $weatherData['cached_time'] = date('M j, Y g:i A', time());

            return $weatherData;

        } catch (\Exception $e) {
            log_message('error', 'Weather API Error: ' . $e->getMessage());
            
            // Try to return cached data even if expired
            if (file_exists($cacheFile)) {
                $cacheData = json_decode(file_get_contents($cacheFile), true);
                $weatherData = $cacheData['weather'] ?? null;
                if ($weatherData) {
                    // Remove old cached_time if it exists
                    unset($weatherData['cached_time']);
                    // Add fresh info
                    $weatherData['source'] = 'offline-cache';
                    $weatherData['cached_time'] = date('M j, Y g:i A', $cacheData['cached_at']);
                }
                return $weatherData;
            }
            
            return null;
        }
    }

    /**
     * Format raw API data into user-friendly structure
     * @param array $data Raw API response
     * @return array Formatted weather data
     */
    private function formatWeatherData($data)
    {
        $hourly = $data['hourly'];
        $times = $hourly['time'];
        $temps = $hourly['temperature_2m'];
        $precipitation = $hourly['precipitation'];
        $weathercodes = $hourly['weathercode'];

        // Get timezone from API response (default to Asia/Manila for Philippines)
        $timezone = isset($data['timezone']) ? $data['timezone'] : 'Asia/Manila';

        // Get today and tomorrow dates in the city's timezone
        $now = new \DateTime('now', new \DateTimeZone($timezone));
        $today = $now->format('Y-m-d');
        $tomorrow = (clone $now)->modify('+1 day')->format('Y-m-d');
        $currentHour = (int)$now->format('H');

        // Separate data by day
        $todayData = [];
        $tomorrowData = [];

        foreach ($times as $index => $time) {
            $dt = new \DateTime($time, new \DateTimeZone($timezone));
            $date = $dt->format('Y-m-d');
            $hour = (int)$dt->format('H');
            
            // Format hour to 12-hour format (e.g., "3 PM", "12 AM")
            $hourFormatted = $dt->format('g A');

            $temp = round($temps[$index]);
            $precip = round($precipitation[$index], 2);
            $baseCondition = $this->getWeatherCondition($weathercodes[$index]);
            
            // Add "and hot" if temp >= 32 and no precipitation
            $condition = $baseCondition;
            if ($temp >= 32 && $precip == 0) {
                $condition .= ' and hot';
            }

            $weatherData = [
                'hour' => $hour,
                'time' => $hourFormatted,
                'temp' => $temp,
                'precipitation' => $precip,
                'condition' => $condition,
                'is_current' => ($date === $today && $hour === $currentHour)
            ];

            if ($date === $today) {
                $todayData[] = $weatherData;
            } elseif ($date === $tomorrow) {
                $tomorrowData[] = $weatherData;
            }
        }

        // Merge consecutive hours with same conditions
        return [
            'today' => $this->mergeConsecutiveHours($todayData),
            'tomorrow' => $this->mergeConsecutiveHours($tomorrowData)
        ];
    }

    /**
     * Merge consecutive hours with same weather condition
     * @param array $hourlyData
     * @return array Merged data
     */
    private function mergeConsecutiveHours($hourlyData)
    {
        if (empty($hourlyData)) {
            return [];
        }

        $result = [];
        $current = null;

        foreach ($hourlyData as $data) {
            // If this is the current hour, always make it its own row
            if ($data['is_current']) {
                // Save previous group if exists
                if ($current !== null) {
                    $result[] = $this->formatMergedGroup($current);
                    $current = null;
                }
                
                // Add current hour as standalone
                $result[] = [
                    'time_range' => $data['time'],
                    'condition' => $data['condition'],
                    'temp' => $data['temp'],
                    'precipitation' => $data['precipitation'],
                    'is_current' => true
                ];
                continue;
            }

            if ($current === null) {
                // First hour or start new group
                $current = [
                    'start_time' => $data['time'],
                    'end_time' => $data['time'],
                    'condition' => $data['condition'],
                    'temps' => [$data['temp']],
                    'precipitations' => [$data['precipitation']],
                    'is_current' => false
                ];
            } elseif ($current['condition'] === $data['condition']) {
                // Same condition, merge with current
                $current['end_time'] = $data['time'];
                $current['temps'][] = $data['temp'];
                $current['precipitations'][] = $data['precipitation'];
            } else {
                // Different condition, save current and start new
                $result[] = $this->formatMergedGroup($current);
                
                $current = [
                    'start_time' => $data['time'],
                    'end_time' => $data['time'],
                    'condition' => $data['condition'],
                    'temps' => [$data['temp']],
                    'precipitations' => [$data['precipitation']],
                    'is_current' => false
                ];
            }
        }

        // Add the last group
        if ($current !== null) {
            $result[] = $this->formatMergedGroup($current);
        }

        return $result;
    }

    /**
     * Format a merged group of hours
     */
    private function formatMergedGroup($group)
    {
        $timeRange = $group['start_time'];
        if ($group['start_time'] !== $group['end_time']) {
            $timeRange .= ' – ' . $group['end_time'];
        }

        $avgTemp = array_sum($group['temps']) / count($group['temps']);
        $avgPrecip = array_sum($group['precipitations']) / count($group['precipitations']);

        return [
            'time_range' => $timeRange,
            'condition' => $group['condition'],
            'temp' => number_format($avgTemp, 1),
            'precipitation' => number_format($avgPrecip, 2),
            'is_current' => false
        ];
    }

    /**
     * Convert WMO weather code to readable condition
     * @param int $code WMO weather code
     * @return string Weather condition
     */
    private function getWeatherCondition($code)
    {
        $conditions = [
            0 => 'Clear',
            1 => 'Mainly clear',
            2 => 'Partly cloudy',
            3 => 'Cloudy',
            45 => 'Foggy',
            48 => 'Foggy',
            51 => 'Light drizzle',
            53 => 'Drizzle',
            55 => 'Heavy drizzle',
            61 => 'Light rain',
            63 => 'Rain',
            65 => 'Heavy rain',
            71 => 'Light snow',
            73 => 'Snow',
            75 => 'Heavy snow',
            77 => 'Snow grains',
            80 => 'Light rain showers',
            81 => 'Rain showers',
            82 => 'Heavy rain showers',
            85 => 'Light snow showers',
            86 => 'Snow showers',
            95 => 'Thunderstorm',
            96 => 'Thunderstorm with hail',
            99 => 'Thunderstorm with hail'
        ];

        return $conditions[$code] ?? 'Unknown';
    }
}
