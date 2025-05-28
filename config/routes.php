<?php
use App\Middleware\AuthMiddleware;

use App\Controllers\AuthController;
use App\Controllers\HomeController;
use App\Controllers\DashboardController;
use App\Controllers\NoteController;

return [
    // Auth routes
    ['GET', '/login', [AuthController::class, 'showLogin']],
    ['POST', '/login', [AuthController::class, 'login']],
    ['GET', '/register', [AuthController::class, 'showRegister']],
    ['POST', '/register', [AuthController::class, 'register']],
    ['GET', '/logout', [AuthController::class, 'logout']],

    ['GET', '/dashboard', 
        [DashboardController::class, 'index'],
        [AuthMiddleware::class],
    ],
    // Notes routes
    ['GET', '/notes',
        [NoteController::class, 'list'],
        [AuthMiddleware::class],
    ],
    ['GET', '/notes/edit/{id}',
        [NoteController::class, 'edit'],
        [AuthMiddleware::class],
    ],
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