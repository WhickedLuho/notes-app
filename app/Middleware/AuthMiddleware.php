<?php
namespace App\Middleware;

class AuthMiddleware
{
    public function handle(): bool
    {
        session_start(); // Ensure session is active
        
        // echo"auth middlleware";
        if (empty($_SESSION['user_id'])) {

            header('Location: /login');
            return false; // Stop execution
        }
        
        return true; // Continue to next middleware/controller
    }
}