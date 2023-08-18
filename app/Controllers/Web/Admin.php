<?php

namespace App\Controllers\Web;

use App\Controllers\BaseController;
use CodeIgniter\Session\Session;
use Myth\Auth\Config\Auth as AuthConfig;

class Admin extends BaseController
{
    protected $auth;
    
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
    }
    
    public function login() {
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
    

}
