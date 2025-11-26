<?php
// followup-services/public/index.php
declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

// Boot DB connection
require_once __DIR__ . '/../database/connection.php';

error_reporting(E_ALL);
ini_set('display_errors', '1');

// CORS + headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Content-Type: application/json; charset=utf-8");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// echo "REQUEST_URI: " . $_SERVER['REQUEST_URI'] . "\n"; // Debug hanya untuk development


// Load routes
require_once __DIR__ . '/../routes/api.php';
