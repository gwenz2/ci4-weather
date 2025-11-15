<?php
namespace App\Controllers;
use App\Models\CityModel;
use App\Models\UserModel;
use App\Models\UserFavoriteModel;
use CodeIgniter\Controller;

class AdminController extends Controller
{
    // Admin Dashboard
    public function dashboard()
    {
        $cityModel = new CityModel();
        $userModel = new UserModel();
        $favoriteModel = new UserFavoriteModel();
        
        $data = [
            'total_cities' => $cityModel->countAll(),
            'total_users' => $userModel->where('role', 'user')->countAllResults(),
            'total_admins' => $userModel->where('role', 'admin')->countAllResults(),
            'total_favorites' => $favoriteModel->countAll(),
        ];
        
        return view('admin/dashboard', $data);
    }

    // Cities CRUD
    public function cities()
    {
        $model = new CityModel();
        $cities = $model->findAll();
        return view('admin/cities_list', ['cities' => $cities]);
    }

    public function createCity()
    {
        return view('admin/city_create');
    }

    public function storeCity()
    {
        // Validate input
        $validation = \Config\Services::validation();
        $validation->setRules([
            'name' => 'required|min_length[2]|max_length[100]',
            'country' => 'required|min_length[2]|max_length[100]',
            'latitude' => 'required|decimal|greater_than_equal_to[-90]|less_than_equal_to[90]',
            'longitude' => 'required|decimal|greater_than_equal_to[-180]|less_than_equal_to[180]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $model = new CityModel();
        $data = [
            'name' => $this->request->getPost('name'),
            'country' => $this->request->getPost('country'),
            'latitude' => $this->request->getPost('latitude'),
            'longitude' => $this->request->getPost('longitude'),
        ];
        $model->insert($data);
        return redirect()->to('/admin/cities')->with('success', 'City added successfully!');
    }

    public function editCity($id)
    {
        $model = new CityModel();
        $city = $model->find($id);
        return view('admin/city_edit', ['city' => $city]);
    }

    public function updateCity($id)
    {
        // Validate input
        $validation = \Config\Services::validation();
        $validation->setRules([
            'name' => 'required|min_length[2]|max_length[100]',
            'country' => 'required|min_length[2]|max_length[100]',
            'latitude' => 'required|decimal|greater_than_equal_to[-90]|less_than_equal_to[90]',
            'longitude' => 'required|decimal|greater_than_equal_to[-180]|less_than_equal_to[180]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $model = new CityModel();
        $data = [
            'name' => $this->request->getPost('name'),
            'country' => $this->request->getPost('country'),
            'latitude' => $this->request->getPost('latitude'),
            'longitude' => $this->request->getPost('longitude'),
        ];
        $model->update($id, $data);
        return redirect()->to('/admin/cities')->with('success', 'City updated successfully!');
    }

    public function deleteCity($id)
    {
        $model = new CityModel();
        $model->delete($id);
        return redirect()->to('/admin/cities')->with('success', 'City deleted successfully!');
    }

    // Users CRUD (list only for now)
    public function users()
    {
        $model = new UserModel();
        $users = $model->findAll();
        return view('admin/users_list', ['users' => $users]);
    }

    public function editUser($id)
    {
        $model = new UserModel();
        $user = $model->find($id);
        
        if (!$user) {
            return redirect()->to('/admin/users')->with('error', 'User not found!');
        }
        
        return view('admin/user_edit', ['user' => $user]);
    }

    public function updateUser($id)
    {
        // Validate input
        $validation = \Config\Services::validation();
        $rules = [
            'username' => 'required|min_length[3]|max_length[50]',
            'email' => 'required|valid_email|max_length[100]',
            'role' => 'required|in_list[user,admin]'
        ];

        // Only validate password if provided
        if ($this->request->getPost('password')) {
            $rules['password'] = 'min_length[6]|max_length[255]';
        }

        $validation->setRules($rules);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $model = new UserModel();
        
        $data = [
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'role' => $this->request->getPost('role'),
        ];
        
        // Only update password if provided
        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $data['password'] = $model->hashPassword($password);
        }
        
        $model->update($id, $data);
        return redirect()->to('/admin/users')->with('success', 'User updated successfully!');
    }

    public function deleteUser($id)
    {
        $model = new UserModel();
        $favoriteModel = new UserFavoriteModel();
        
        // Prevent deleting yourself
        if ($id == session()->get('user_id')) {
            return redirect()->to('/admin/users')->with('error', 'You cannot delete your own account!');
        }
        
        // Delete user's favorites first
        $favoriteModel->where('user_id', $id)->delete();
        
        // Delete user
        $model->delete($id);
        return redirect()->to('/admin/users')->with('success', 'User deleted successfully!');
    }
}
