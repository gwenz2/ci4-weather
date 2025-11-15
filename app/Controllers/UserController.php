<?php
namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\CityModel;
use App\Models\UserFavoriteModel;
use App\Libraries\WeatherService;

class UserController extends Controller
{
    // Show user dashboard with favorite cities weather
    public function dashboard()
    {
        // Check if user is logged in
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }
        
        $userId = session()->get('user_id');
        $favoriteModel = new UserFavoriteModel();
        $favorites = $favoriteModel->getUserFavorites($userId);
        
        // Get today and tomorrow dates
        $todayDate = date('M j, Y');
        $tomorrowDate = date('M j, Y', strtotime('+1 day'));
        
        // Get weather data for each favorite city
        $weatherService = new WeatherService();
        foreach ($favorites as &$city) {
            if (isset($city['latitude']) && isset($city['longitude'])) {
                $weather = $weatherService->getWeather($city['latitude'], $city['longitude']);
                $city['weather'] = $weather;
            } else {
                $city['weather'] = null;
            }
        }
        
        return view('user/user_dashboard', [
            'favorites' => $favorites,
            'today_date' => $todayDate,
            'tomorrow_date' => $tomorrowDate
        ]);
    }

    // List all cities (for user to view and add to favorites)
    public function cities()
    {
        // Check if user is logged in
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }
        
        $userId = session()->get('user_id');
        $cityModel = new CityModel();
        $favoriteModel = new UserFavoriteModel();
        
        $cities = $cityModel->findAll();
        
        // Add favorite status to each city
        foreach ($cities as &$city) {
            $city['is_favorite'] = $favoriteModel->isFavorite($userId, $city['id']);
        }
        
        return view('user/cities_list', ['cities' => $cities]);
    }

    // Add city to favorites
    public function addFavorite($cityId)
    {
        // Check if user is logged in
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }
        
        $userId = session()->get('user_id');
        $favoriteModel = new UserFavoriteModel();
        
        // Check if user already has 3 favorites
        $currentFavorites = $favoriteModel->getUserFavorites($userId);
        if (count($currentFavorites) >= 2) {
            session()->setFlashdata('error', 'You can only have a maximum of 2 favorite cities!');
            return redirect()->to('/user/cities');
        }
        
        if ($favoriteModel->addFavorite($userId, $cityId)) {
            session()->setFlashdata('success', 'City added to favorites!');
        } else {
            session()->setFlashdata('error', 'City is already in favorites!');
        }
        
        return redirect()->to('/user/cities');
    }

    // Remove city from favorites
    public function removeFavorite($cityId)
    {
        // Check if user is logged in
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }
        
        $userId = session()->get('user_id');
        $favoriteModel = new UserFavoriteModel();
        
        if ($favoriteModel->removeFavorite($userId, $cityId)) {
            session()->setFlashdata('success', 'City removed from favorites!');
        }
        
        return redirect()->to('/user/cities');
    }
}
