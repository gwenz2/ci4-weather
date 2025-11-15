<?php
header('Content-Type: application/json; charset=utf-8');

// =============================
// 🔧 SETTINGS
// =============================
$city = 'Isulan, Sultan Kudarat, Philippines';
$lat = 6.6297;
$lon = 124.7472;
$cacheFile = __DIR__ . '/weather_cache.json';
$cacheDuration = 3600; // 1 hour cache (in seconds)

// =============================
// 🕐 Check cache
// =============================
// Cache is disabled for now

// =============================
// 🌎 Detect location by IP
// =============================
// Location is set in settings above

// =============================
// ☁️ Fetch new weather data
// =============================
$apiUrl = "https://api.open-meteo.com/v1/forecast?latitude=$lat&longitude=$lon&hourly=temperature_2m,precipitation,weathercode&timezone=auto";
$response = @file_get_contents($apiUrl);

if (!$response) {
    // ❌ Fallback to cache
    if (isset($cacheData)) {
        $cacheData['note'] = 'Offline mode - showing last saved data.';
        $cacheData['source'] = 'cache';
        $cacheData['last_update'] = date('Y-m-d H:i:s', $cacheData['timestamp']);
        echo json_encode($cacheData, JSON_PRETTY_PRINT);
        exit;
    }
    echo json_encode(['error' => 'Failed to connect to Open-Meteo and no cache available']);
    exit;
}

$data = json_decode($response, true);

// =============================
// 📅 Extract forecast (3 days)
// =============================
$forecast = [];
if (isset($data['hourly']['time'])) {
    $now = new DateTime('now', new DateTimeZone($data['timezone'] ?? 'Asia/Manila'));
    $today = $now->format('Y-m-d');
    $tomorrow = $now->modify('+1 day')->format('Y-m-d');
    $now->modify('-1 day'); // reset

    $weatherCodes = [
        0 => 'Clear',
        1 => 'Mostly clear',
        2 => 'Partly cloudy',
        3 => 'Cloudy',
        45 => 'Foggy',
        48 => 'Foggy',
        51 => 'Light rain',
        53 => 'Light rain',
        55 => 'Light rain',
        56 => 'Freezing rain',
        57 => 'Freezing rain',
        61 => 'Light rain',
        63 => 'Rain',
        65 => 'Heavy rain',
        66 => 'Freezing rain',
        67 => 'Heavy freezing rain',
        71 => 'Light snow',
        73 => 'Snow',
        75 => 'Heavy snow',
        77 => 'Snow',
        80 => 'Light rain',
        81 => 'Rain showers',
        82 => 'Heavy rain',
        85 => 'Light snow',
        86 => 'Heavy snow',
        95 => 'Thunderstorm',
        96 => 'Thunderstorm',
        99 => 'Thunderstorm'
    ];

    foreach ($data['hourly']['time'] as $i => $isoTime) {
        $dt = new DateTime($isoTime);
        $date = $dt->format('Y-m-d');
        if ($date === $today || $date === $tomorrow) {
            $monthDayYear = $dt->format('M j, Y'); // e.g. Oct 29, 2025
            $hour = $dt->format('g A'); // e.g. 3 PM
            $temp = isset($data['hourly']['temperature_2m'][$i]) ? round($data['hourly']['temperature_2m'][$i]) : null;
            $precip = isset($data['hourly']['precipitation'][$i]) ? $data['hourly']['precipitation'][$i] : null;
            $code = isset($data['hourly']['weathercode'][$i]) ? $data['hourly']['weathercode'][$i] : null;
            $condition = $weatherCodes[$code] ?? 'Unknown';
            $desc = $condition;
            if ($precip > 0) {
                $desc .= ' with rain';
            } elseif ($temp !== null && $temp >= 32) {
                $desc .= ' and hot';
            }
            $forecast[] = [
                'date' => $monthDayYear,
                'hour' => $hour,
                'temperature' => $temp,
                'precipitation' => $precip,
                'condition' => $desc
            ];
        }
    }
}

// =============================
// 💾 Save cache
// =============================
$newData = [
    'location' => $city,
    'latitude' => $lat,
    'longitude' => $lon,
    'forecast' => $forecast,
    'timestamp' => time(),
    'source' => 'live',
    'last_update' => date('Y-m-d H:i:s')
];

file_put_contents($cacheFile, json_encode($newData, JSON_PRETTY_PRINT));

// =============================
// ✅ Return result
// =============================
echo json_encode($newData, JSON_PRETTY_PRINT);
