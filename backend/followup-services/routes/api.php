<?php
use Bramus\Router\Router;

require_once __DIR__ . '/../vendor/autoload.php';

// Error reporting untuk debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);

// CORS + headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

$router = new Router();

// Helper function untuk handle errors
function handleControllerError($callback) {
    try {
        $callback();
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Internal Server Error: ' . $e->getMessage(),
            'error_details' => [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => substr($e->getTraceAsString(), 0, 1000)
            ]
        ]);
    }
}

// Instantiate controllers with error checking
try {
    $followupController = new Controllers\FollowupController();
    $mitraController = new Controllers\MitraController();
    $invoiceController = new Controllers\InvoiceController();
    $requestExtendController = new Controllers\RequestExtendController();
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Failed to initialize controllers: ' . $e->getMessage()
    ]);
    exit;
}

// ===== FOLLOWUP =====
$router->get('/api/followup', function() use ($followupController) { 
    handleControllerError(function() use ($followupController) {
        $followupController->index(); 
    });
});

$router->get('/api/followup/(\d+)', function($id) use ($followupController) { 
    handleControllerError(function() use ($followupController, $id) {
        $followupController->show($id); 
    });
});

$router->post('/api/followup', function() use ($followupController) { 
    handleControllerError(function() use ($followupController) {
        $followupController->store(); 
    });
});

$router->put('/api/followup/(\d+)', function($id) use ($followupController) { 
    handleControllerError(function() use ($followupController, $id) {
        $followupController->update($id); 
    });
});

$router->delete('/api/followup/(\d+)', function($id) use ($followupController) { 
    handleControllerError(function() use ($followupController, $id) {
        $followupController->destroy($id); 
    });
});

// ===== MITRA =====
$router->get('/api/mitra', function() use ($mitraController) { 
    handleControllerError(function() use ($mitraController) {
        $mitraController->index(); 
    });
});

$router->get('/api/mitra/([A-Za-z0-9\-_]+)', function($kode) use ($mitraController) { 
    handleControllerError(function() use ($mitraController, $kode) {
        $mitraController->show($kode); 
    });
});

$router->post('/api/mitra', function() use ($mitraController) { 
    handleControllerError(function() use ($mitraController) {
        $mitraController->store(); 
    });
});

$router->put('/api/mitra/([A-Za-z0-9\-_]+)', function($kode) use ($mitraController) { 
    handleControllerError(function() use ($mitraController, $kode) {
        $mitraController->update($kode); 
    });
});

$router->delete('/api/mitra/([A-Za-z0-9\-_]+)', function($kode) use ($mitraController) { 
    handleControllerError(function() use ($mitraController, $kode) {
        $mitraController->destroy($kode); 
    });
});

// ===== INVOICE =====
$router->get('/api/invoice', function() use ($invoiceController) { 
    handleControllerError(function() use ($invoiceController) {
        $invoiceController->index(); 
    });
});

$router->get('/api/invoice/([A-Za-z0-9\-_]+)', function($code) use ($invoiceController) { 
    handleControllerError(function() use ($invoiceController, $code) {
        $invoiceController->show($code); 
    });
});

$router->post('/api/invoice', function() use ($invoiceController) { 
    handleControllerError(function() use ($invoiceController) {
        $invoiceController->store(); 
    });
});

$router->put('/api/invoice/([A-Za-z0-9\-_]+)', function($code) use ($invoiceController) { 
    handleControllerError(function() use ($invoiceController, $code) {
        $invoiceController->update($code); 
    });
});

$router->delete('/api/invoice/([A-Za-z0-9\-_]+)', function($code) use ($invoiceController) { 
    handleControllerError(function() use ($invoiceController, $code) {
        $invoiceController->destroy($code); 
    });
});

// ===== REQUEST EXTEND =====

// Basic CRUD - menggunakan kode_user sebagai identifier utama
$router->get('/api/request-extend', function() use ($requestExtendController) { 
    handleControllerError(function() use ($requestExtendController) {
        $requestExtendController->index(); 
    });
});

// Get by kode_mitra - route spesifik harus diatas route generals
$router->get('/api/request-extend/mitra/([A-Za-z0-9\-_]+)', function($kode_mitra) use ($requestExtendController) { 
    handleControllerError(function() use ($requestExtendController, $kode_mitra) {
        $requestExtendController->getByMitra($kode_mitra); 
    });
});

// Filter by status - routes spesifik untuk status
$router->get('/api/request-extend/status/pending', function() { 
    handleControllerError(function() {
        try {
            $requestExtends = Models\RequestExtend::pending()->get();
            echo json_encode([
                'success' => true,
                'data' => $requestExtends,
                'count' => $requestExtends->count()
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Error retrieving pending requests: ' . $e->getMessage()
            ]);
        }
    });
});

$router->get('/api/request-extend/status/approved', function() { 
    handleControllerError(function() {
        try {
            $requestExtends = Models\RequestExtend::approved()->get();
            echo json_encode([
                'success' => true,
                'data' => $requestExtends,
                'count' => $requestExtends->count()
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Error retrieving approved requests: ' . $e->getMessage()
            ]);
        }
    });
});

$router->get('/api/request-extend/status/rejected', function() { 
    handleControllerError(function() {
        try {
            $requestExtends = Models\RequestExtend::rejected()->get();
            echo json_encode([
                'success' => true,
                'data' => $requestExtends,
                'count' => $requestExtends->count()
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Error retrieving rejected requests: ' . $e->getMessage()
            ]);
        }
    });
});

// POST - Create new request extend
$router->post('/api/request-extend', function() use ($requestExtendController) { 
    handleControllerError(function() use ($requestExtendController) {
        $requestExtendController->store(); 
    });
});

// PATCH - Update status by kode_user
$router->patch('/api/request-extend/([A-Za-z0-9\-_]+)/status', function($kode_user) use ($requestExtendController) { 
    handleControllerError(function() use ($requestExtendController, $kode_user) {
        $requestExtendController->updateStatus($kode_user); 
    });
});

// GET - Get by kode_user (route general di bawah route spesifik)
$router->get('/api/request-extend/([A-Za-z0-9\-_]+)', function($kode_user) use ($requestExtendController) { 
    handleControllerError(function() use ($requestExtendController, $kode_user) {
        $requestExtendController->show($kode_user); 
    });
});

// PUT - Update by kode_user
$router->put('/api/request-extend/([A-Za-z0-9\-_]+)', function($kode_user) use ($requestExtendController) { 
    handleControllerError(function() use ($requestExtendController, $kode_user) {
        $requestExtendController->update($kode_user); 
    });
});

// DELETE - Delete by kode_user
$router->delete('/api/request-extend/([A-Za-z0-9\-_]+)', function($kode_user) use ($requestExtendController) { 
    handleControllerError(function() use ($requestExtendController, $kode_user) {
        $requestExtendController->destroy($kode_user); 
    });
});

// ===== ROOT =====
$router->get('/', function() {
    echo json_encode([
        'success' => true, 
        'message' => 'Followup service running',
        'version' => '1.0.0',
        'timestamp' => date('Y-m-d H:i:s'),
        'endpoints' => [
            'followup' => '/api/followup',
            'mitra' => '/api/mitra',
            'invoice' => '/api/invoice',
            'request_extend' => '/api/request-extend'
        ]
    ]);
});

// Error handling untuk route tidak ditemukan
$router->set404(function() {
    http_response_code(404);
    echo json_encode([
        'success' => false,
        'message' => 'Endpoint not found',
        'available_endpoints' => [
            'GET /' => 'Service status',
            'GET /api/followup' => 'Get all followups',
            'GET /api/mitra' => 'Get all mitra',
            'GET /api/invoice' => 'Get all invoices',
            'GET /api/request-extend' => 'Get all request extends',
            'GET /api/request-extend/{kode_user}' => 'Get request extend by kode_user',
            'GET /api/request-extend/mitra/{kode_mitra}' => 'Get request extend by kode_mitra',
            'GET /api/request-extend/status/{status}' => 'Get request extend by status (pending/approved/rejected)',
            'POST /api/request-extend' => 'Create new request extend',
            'PUT /api/request-extend/{kode_user}' => 'Update request extend by kode_user',
            'PATCH /api/request-extend/{kode_user}/status' => 'Update status request extend',
            'DELETE /api/request-extend/{kode_user}' => 'Delete request extend by kode_user'
        ]
    ]);
});

$router->run();