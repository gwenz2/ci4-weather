<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\UserModel;

class LoginController extends Controller
{
    public function index()
    {
        return view('login');
    }

    public function authenticate()
    {
        // Validate input
        $validation = \Config\Services::validation();
        $validation->setRules([
            'username' => 'required|min_length[3]|max_length[50]',
            'password' => 'required|min_length[6]|max_length[255]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $session = session();
        $model = new UserModel();

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        // Use checkUser method like in your previous project
        $user = $model->checkUser($username);

        // Check if user exists and verify password
        if ($user && password_verify($password, $user['password'])) {
            // Set session data with role
            $session->set([
                'user_id' => $user['id'],
                'username' => $user['username'],
                'role' => $user['role'],
                'logged_in' => true
            ]);
            // Redirect based on role
            if ($user['role'] === 'admin') {
                return redirect()->to('/admin/dashboard');
            } else {
                return redirect()->to('/user/dashboard');
            }
        } else {
            $session->setFlashdata('error', 'Invalid username or password');
            return redirect()->to('/login');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }

    public function signup()
    {
        return view('signup');
    }

    public function register()
    {
        // Validate input
        $validation = \Config\Services::validation();
        $validation->setRules([
            'username' => 'required|min_length[3]|max_length[50]|is_unique[users.username]',
            'email' => 'required|valid_email|max_length[100]',
            'password' => 'required|min_length[6]|max_length[255]',
            'confirm_password' => 'required|matches[password]',
            'role' => 'required|in_list[user,admin]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $session = session();
        $model = new UserModel();

        // Hash password before storing
        $hashedPassword = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);

        $data = [
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'password' => $hashedPassword,
            'role' => $this->request->getPost('role')
        ];

        if ($model->insert($data)) {
            $session->setFlashdata('success', 'Registration successful. Please login with your credentials.');
            return redirect()->to('/login');
        } else {
            $session->setFlashdata('error', 'Registration failed. Please try again.');
            return redirect()->to('/signup');
        }
    }
}
