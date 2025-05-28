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
        $this->verifyCsrfToken($_POST['csrf_token'] ?? '');

        $user = $this->authService->attemptLogin(
            $_POST['email'] ?? '',
            $_POST['password'] ?? ''
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
            'csrf_token', $this->generateCsrfToken(),
            'activePage' => 'register'
        ]);
        $this->smarty->display('auth/register.tpl');
    }

    // Process registration
    public function register()
    {
        $this->verifyCsrfToken($_POST['csrf_token'] ?? '');
        // TODO if for some reason user doesnt successfully registered the token throws an error.

        try {
            $user = $this->authService->registerUser([
                'nickname' => $_POST['nickname'] ?? '',
                'fullname' => $_POST['fullname'] ?? '',
                'email' => $_POST['email'] ?? '',
                'password' => $_POST['password'] ?? ''
            ]);
            
            SessionService::login($user);
            header('Location: /notes/list');
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