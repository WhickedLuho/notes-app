<?php
namespace App\Controllers;

use App\Services\AuthService;
use App\Services\SessionService;

class AuthController extends BaseController
{
    protected $authService;

    public function __construct()
    {
        parent::__construct();
        $this->authService = new AuthService($this->db);
        // var_dump(($_SESSION));
    }

    // Show login form
    public function showLogin()
    {
        $this->smarty->assign([
            'csrf_token' => $this->generateCsrfToken(),
            'activePage' => 'login'
        ]);
        $this->smarty->display('auth/login.tpl');
    }

    // Process login
    public function login()
    {
        $postData = $this->request->post();
        $this->verifyCsrfToken($postData['csrf_token'] ?? '');

        $user = $this->authService->attemptLogin(
            $postData['email'] ?? '',
            $postData['password'] ?? ''
        );

        if ($user) {
            SessionService::login($user);
            header('Location: /dashboard');
            exit;
        } else {
            $this->smarty->assign('error', 'Invalid credentials');
            $this->showLogin();
        }
    }

    // Show registration form
    public function showRegister()
    {
        $this->smarty->assign([
            'csrf_token' => $this->generateCsrfToken(),
            'activePage' => 'register'
        ]);

        $this->smarty->display('auth/register.tpl');
    }

    // Process registration
    public function register()
    {
        $postData = $this->request->post();
        $this->verifyCsrfToken($postData['csrf_token'] ?? '');
        // TODO if for some reason user doesnt successfully registered the token throws an error.

        try {
            $user = $this->authService->registerUser([
                'nickname' => $postData['nickname'] ?? '',
                'fullname' => $postData['fullname'] ?? '',
                'email' => $postData['email'] ?? '',
                'password' => $postData['password'] ?? ''
            ]);
            
            SessionService::login($user);
            header('Location: /dashboard');
            exit;
            
        } catch (\Exception $e) {
            $this->smarty->assign('error', $e->getMessage());
            $this->showRegister();
        }
    }

    // Logout
    public function logout()
    {
        SessionService::logout();
        header('Location: /');
    }
}