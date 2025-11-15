<?php
namespace App\Models;
use CodeIgniter\Model;

class UserFavoriteModel extends Model
{
    protected $table = 'user_favorites';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'city_id', 'created_at'];
    protected $useTimestamps = false;
    protected $returnType = 'array';

    // Get all favorite cities for a user with city details
    public function getUserFavorites($userId)
    {
        return $this->select('cities.*, user_favorites.id as favorite_id')
            ->join('cities', 'cities.id = user_favorites.city_id')
            ->where('user_favorites.user_id', $userId)
            ->findAll();
    }

    // Check if city is already favorited by user
    public function isFavorite($userId, $cityId)
    {
        return $this->where(['user_id' => $userId, 'city_id' => $cityId])->first() !== null;
    }

    // Add favorite
    public function addFavorite($userId, $cityId)
    {
        if (!$this->isFavorite($userId, $cityId)) {
            return $this->insert([
                'user_id' => $userId,
                'city_id' => $cityId,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }
        return false;
    }

    // Remove favorite
    public function removeFavorite($userId, $cityId)
    {
        return $this->where(['user_id' => $userId, 'city_id' => $cityId])->delete();
    }
}
