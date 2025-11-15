<?php

namespace App\Models;
use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table      = 'users';
    protected $primaryKey = 'id';

    protected $allowedFields = [
           'username',
           'email',
           'password',
           'role',
           'created_at',
           'updated_at'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $returnType    = 'array';
    protected $useSoftDeletes = false;

    // Check if user exists by username
    public function checkUser($username)
    {
        return $this->where('username', $username)->first();
    }

    // Hash password using bcrypt
    public function hashPassword($password)
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }
}
