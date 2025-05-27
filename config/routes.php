<?php
use App\Middleware\AuthMiddleware;

use App\Controllers\AuthController;
use App\Controllers\HomeController;

return [
    // Auth routes
    ['GET', '/login', [AuthController::class, 'showLogin']],
    ['POST', '/login', [AuthController::class, 'login']],
    ['GET', '/register', [AuthController::class, 'showRegister']],
    ['POST', '/register', [AuthController::class, 'register']],
    ['GET', '/logout', [AuthController::class, 'logout']],


    // Simple closure route
    // ['GET', '/', function() {
    //     return "Hello from your notes app!";
    // }],

    // ['GET', '/test', function() {
    //     return "Hello from your notes app! TEST page";
    // }],
    ['GET', '/', 
        [HomeController::class, 'index'],
        [AuthMiddleware::class],
    ],
    
    // Controller example (we'll create this next)
    // ['GET', '/about', [PageController::class, 'about']]
];