#!/usr/bin/env php
<?php
require __DIR__ . '/../vendor/autoload.php';

// Check if running in CLI
if (PHP_SAPI !== 'cli') {
    die('This script must be run from the command line');
}

// Load environment
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

try {
    // Initialize DB
    $db = new PDO(
        "mysql:host={$_ENV['DB_HOST']};dbname={$_ENV['DB_NAME']}",
        $_ENV['DB_USER'],
        $_ENV['DB_PASSWORD'],
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );

    // Get arguments
    $userId = $argv[1] ?? 1;
    $count = $argv[2] ?? 50;

    // Run seeder
    require __DIR__ . '/seeders/NotesTableSeeder.php';
    $seeder = new Database\Seeders\NotesTableSeeder($db);
    $seeder->run($userId, $count);

    echo "Successfully seeded $count notes for user $userId\n";
    exit(0);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}