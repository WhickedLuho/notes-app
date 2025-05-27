<?php
namespace App\Controllers;

use Smarty;
use PDO;
use PDOException;
use Dotenv\Dotenv;
use App\Services\SessionService;

class BaseController
{
    protected $db;
    protected $smarty;

    public function __construct()
    {
        $this->loadEnv();
        $this->initDb();
        $this->initSmarty();
    }

    protected function loadEnv(): void
    {
        $dotenv = Dotenv::createImmutable(dirname(__DIR__, 2));
        // $dotenv->load();
        $dotenv->safeLoad(); // Changed to safeLoad() to prevent exceptions

        $dotenv->required([
            'DB_HOST',
            'DB_NAME',
            'DB_USER'
        ])->notEmpty();
        
        $dotenv->ifPresent('DB_PASSWORD')->notEmpty(); // Optional but validated if present
    }

    protected function initDb(): void
    {
        try {
            $this->db = new PDO(
                "mysql:host=" . $_ENV['DB_HOST'] . 
                ";dbname=" . $_ENV['DB_NAME'],
                $_ENV['DB_USER'],
                $_ENV['DB_PASSWORD'],
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );
            // var_export(getenv());
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    protected function initSmarty(): void
    {
        $this->smarty = new Smarty();
        
        // Absolute paths work best in Windows
        $viewsDir = dirname(__DIR__, 2) . '/app/Views';
        
        // Configure paths
        $this->smarty->setTemplateDir($viewsDir);
        $this->smarty->setCompileDir(dirname(__DIR__, 2) . '/storage/cache/smarty_compiled');
        $this->smarty->setCacheDir(dirname(__DIR__, 2) . '/storage/cache/smarty_cache');
        
        // Enable debugging (temporary)
        // $this->smarty->setDebugging(true);
        $this->smarty->error_reporting = E_ALL;
        
        // echo '<pre>Is logged in: ' . SessionService::isLoggedIn() . '</pre>';

        $this->smarty->assign('app_name', $_ENV['APP_NAME']);
    }

    protected function jsonResponse(array $data, int $status = 200): void
    {
        header('Content-Type: application/json');
        http_response_code($status);
        echo json_encode($data);
        exit;
    }

    protected function checkAuth(): void
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }
    }

    protected function generateCsrfToken(): string
    {
        SessionService::start();
        $token = bin2hex(random_bytes(32));
        $_SESSION['csrf_token'] = $token;
        return $token;
    }

    protected function verifyCsrfToken(string $token): void
    {
        SessionService::start();

        if (!isset($_SESSION['csrf_token']) || $_SESSION['csrf_token'] !== $token) {
            throw new \Exception("Invalid CSRF token");
        }
        // unset($_SESSION['csrf_token']); // token egyszervas
    }

}