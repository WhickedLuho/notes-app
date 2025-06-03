<?php
namespace App\Services;

use App\Models\User;

class SessionService
{
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start([
                'cookie_lifetime' => 7200,      // 2 hours (in seconds)
                'cookie_secure'   => true,      // Only send over HTTPS
                'cookie_httponly' => true,      // Prevent JavaScript access
                'use_strict_mode' => true,      // Prevent session fixation
                'gc_maxlifetime'  => 7200       // Garbage collector cleans old sessions after 2h
            ]);
        }
    }

    public static function login(User $user): void
    {
        self::start();
        // Regenerate ID on login to prevent fixation
        session_regenerate_id(true);
        
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
        $_SESSION['ip_address'] = $_SERVER['REMOTE_ADDR'];
    }

    public static function logout(): void
    {
        self::start();
        session_unset();
        session_destroy();
    }

    public static function isLoggedIn(): bool
    {
        self::start();
        return !empty($_SESSION['user_id']);
    }
}