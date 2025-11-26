<?php
// followup-services/database/connection.php

use Illuminate\Database\Capsule\Manager as Capsule;
use Dotenv\Dotenv;

require_once __DIR__ . '/../vendor/autoload.php';

// Load .env file
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->safeLoad();

// Setup Eloquent Capsule
$capsule = new Capsule();

$capsule->addConnection([
    'driver'    => getenv('DB_DRIVER') ?: ($_ENV['DB_DRIVER'] ?? 'pgsql'),
    'host'      => getenv('DB_HOST') ?: ($_ENV['DB_HOST'] ?? '127.0.0.1'),
    'port'      => getenv('DB_PORT') ?: ($_ENV['DB_PORT'] ?? '5432'),
    'database'  => getenv('DB_DATABASE') ?: ($_ENV['DB_DATABASE'] ?? 'followup_db'),
    'username'  => getenv('DB_USERNAME') ?: ($_ENV['DB_USERNAME'] ?? 'postgres'),
    'password'  => getenv('DB_PASSWORD') ?: ($_ENV['DB_PASSWORD'] ?? ''),
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
    'schema'    => getenv('DB_SCHEMA') ?: ($_ENV['DB_SCHEMA'] ?? 'public'),
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();

// Optional: Provide PDO globally for raw queries
try {
    $pdo = $capsule->getConnection()->getPdo();
    $GLOBALS['conn'] = $pdo; // For legacy/raw query usage
} catch (Exception $e) {
    http_response_code(500);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'success' => false,
        'message' => 'Database connection failed',
        'error'   => $e->getMessage()
    ]);
    exit;
}
