<?php

namespace App\Controllers;

use CodeIgniter\Session\Session;
use Myth\Auth\Config\Auth as AuthConfig;
use Myth\Auth\Models\UserModel;

class Home extends BaseController
{
        protected $auth;
        protected $userModel;

        /**
         * @var AuthConfig
         */
        protected $config;

        /**
         * @var Session
         */
        protected $session;

    public function __construct()
    {
        $this->session = service('session');
        $this->config = config('Auth');
        $this->auth = service('authentication');
        $this->userModel = new UserModel();

    }

    public function index()
    {
        return view('welcome_message');
    }

    public function landingPage()
    {
        return view('landing_page');
    }

    public function error403()
    {
        return view('errors/html/error_403');
    }

    public function login()
    {
        $data = [
            'title' => 'Login',
            'config' => $this->config,
        ];
        return view('auth/login', $data);
    }

    public function register()
    {
        $data = [
            'title' => 'Register',
            'config' => $this->config,
        ];
        return view('auth/register', $data);
    }

    public function profile()
    {
        $data = [
            'title' => 'My Profile',
        ];
        
        return view('profile/manage_profile', $data);
    }

    public function update()
    {
        $data = [
            'title' => 'Update Profile',
        ];
        return view('profile/update_profile', $data);
    }

    public function save($id = null)
    {
        $data = [
            'title' => 'Save Profile',
        ];
        
        $request = $this->request->getPost();
        $requestData = [
            'id' => $id,
            'fullname' => $request['fullname'],
            'address' => $request['address'],
            'phone' => $request['phone']
        ];
        foreach ($requestData as $key => $value) {
            if (empty($value)) {
                unset($requestData[$key]);
            }
        }

        $updateProfil = $this->userModel->update_profil($id, $requestData);

        if ($updateProfil) {
            return redirect()->to(base_url('web/profile'));
        } else {
            return redirect()->back()->withInput();
        }

        return view('profile/manage_profile', $data);
    }

    public function changePassword()
    {
        $data = [
            'title' => 'Change Password',
        ];
        return view('profile/change_password', $data);
    }
}
