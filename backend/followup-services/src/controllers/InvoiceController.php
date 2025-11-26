<?php
namespace Controllers;

use Models\Invoice;
use Exception;
use Illuminate\Database\QueryException;

class InvoiceController
{
    // ✅ GET /api/invoice - Format response sama dengan frontend expectation
    public function index()
    {
        try {
            $invoices = Invoice::orderBy('tgl_invoice', 'desc')->get();
            
            // Format response persis sama dengan yang diharapkan frontend
            echo json_encode([
                'success' => true,
                'data' => $invoices,  // ✅ data langsung berupa array
                'count' => $invoices->count()
            ]);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Error retrieving invoices: ' . $e->getMessage(),
                'error_details' => [
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]
            ]);
        }
    }

    // ✅ GET /api/invoice/{code} - Ambil invoice berdasarkan code
    public function show($code_invoice)
    {
        try {
            $invoice = Invoice::find($code_invoice);
            
            if (!$invoice) {
                http_response_code(404);
                echo json_encode([
                    'success' => false,
                    'message' => 'Invoice not found'
                ]);
                return;
            }

            echo json_encode([
                'success' => true,
                'data' => $invoice
            ]);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Error retrieving invoice: ' . $e->getMessage()
            ]);
        }
    }

    // ✅ Generate next invoice code
    public function nextCode()
    {
        try {
            $lastInvoice = Invoice::orderBy('code_invoice', 'desc')->first();
            
            if (!$lastInvoice) {
                $newCode = 'INV-001';
            } else {
                // Extract number dari code (misal: INV-001 -> 001)
                $lastNumber = (int) substr($lastInvoice->code_invoice, 4);
                $newNumber = $lastNumber + 1;
                $newCode = 'INV-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
            }

            echo json_encode([
                'success' => true,
                'data' => ['next_code' => $newCode],
                'message' => 'Next invoice code generated'
            ]);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Error generating next code: ' . $e->getMessage()
            ]);
        }
    }

    // ✅ Cek struktur tabel invoice
    public function checkTable()
    {
        try {
            $columns = \DB::select("
                SELECT column_name, data_type, is_nullable, column_default
                FROM information_schema.columns 
                WHERE table_name = 'tbl_invoice' 
                  AND table_schema = 'public'
                ORDER BY ordinal_position
            ");
            
            echo json_encode([
                'success' => true,
                'data' => $columns,
                'message' => 'Invoice table structure'
            ]);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Failed to check table structure: ' . $e->getMessage()
            ]);
        }
    }

    // ✅ POST /api/invoice - Buat invoice baru
    public function store()
    {
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (!$input) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Invalid JSON input'
                ]);
                return;
            }

            // Auto-generate code_invoice jika tidak diberikan
            if (!isset($input['code_invoice']) || empty($input['code_invoice'])) {
                $lastInvoice = Invoice::orderBy('code_invoice', 'desc')->first();
                if (!$lastInvoice) {
                    $input['code_invoice'] = 'INV-001';
                } else {
                    $lastNumber = (int) substr($lastInvoice->code_invoice, 4);
                    $newNumber = $lastNumber + 1;
                    $input['code_invoice'] = 'INV-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
                }
            }

            // Validasi required fields
            if (!isset($input['amount'])) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Missing required field: amount'
                ]);
                return;
            }

            // Cek apakah code_invoice sudah ada
            $existingInvoice = Invoice::find($input['code_invoice']);
            if ($existingInvoice) {
                http_response_code(409);
                echo json_encode([
                    'success' => false,
                    'message' => 'Invoice code already exists'
                ]);
                return;
            }

            // Set default values & clean amount
            $data = [
                'code_invoice' => $input['code_invoice'],
                'amount' => (float) str_replace(['Rp', '.', ',', ' '], '', $input['amount']),
                'status' => $input['status'] ?? 'pending',
                'tgl_invoice' => $input['tgl_invoice'] ?? now()
            ];

            $invoice = Invoice::create($data);

            echo json_encode([
                'success' => true,
                'message' => 'Invoice created successfully',
                'data' => $invoice
            ]);

        } catch (QueryException $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Database error: ' . $e->getMessage()
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Error creating invoice: ' . $e->getMessage()
            ]);
        }
    }

    // ✅ PUT /api/invoice/{code} - Update invoice
    public function update($code_invoice)
    {
        try {
            $invoice = Invoice::find($code_invoice);
            
            if (!$invoice) {
                http_response_code(404);
                echo json_encode([
                    'success' => false,
                    'message' => 'Invoice not found'
                ]);
                return;
            }

            $input = json_decode(file_get_contents('php://input'), true);
            
            if (!$input) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Invalid JSON input'
                ]);
                return;
            }

            // Update hanya field yang dikirim (kecuali code_invoice)
            $updateableFields = ['amount', 'status', 'tgl_invoice'];
            $hasUpdates = false;

            foreach ($updateableFields as $field) {
                if (isset($input[$field])) {
                    if ($field === 'amount') {
                        // Clean amount dari format Rupiah
                        $invoice->$field = (float) str_replace(['Rp', '.', ',', ' '], '', $input[$field]);
                    } else {
                        $invoice->$field = $input[$field];
                    }
                    $hasUpdates = true;
                }
            }

            if (!$hasUpdates) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'No valid fields to update'
                ]);
                return;
            }

            $invoice->save();

            echo json_encode([
                'success' => true,
                'message' => 'Invoice updated successfully',
                'data' => $invoice
            ]);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Error updating invoice: ' . $e->getMessage()
            ]);
        }
    }

    // ✅ DELETE /api/invoice/{code} - Hapus invoice
    public function destroy($code_invoice)
    {
        try {
            $invoice = Invoice::find($code_invoice);
            
            if (!$invoice) {
                http_response_code(404);
                echo json_encode([
                    'success' => false,
                    'message' => 'Invoice not found'
                ]);
                return;
            }

            $invoice->delete();

            echo json_encode([
                'success' => true,
                'message' => 'Invoice deleted successfully'
            ]);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Error deleting invoice: ' . $e->getMessage()
            ]);
        }
    }

    // ✅ Statistik invoice
    public function stats()
    {
        try {
            $stats = [
                'total_invoices' => Invoice::count(),
                'total_amount' => Invoice::sum('amount'),
                'pending_count' => Invoice::where('status', 'pending')->count(),
                'paid_count' => Invoice::where('status', 'paid')->count(),
                'overdue_count' => Invoice::where('status', 'overdue')->count(),
                'average_amount' => Invoice::avg('amount')
            ];

            echo json_encode([
                'success' => true,
                'data' => $stats,
                'message' => 'Invoice statistics'
            ]);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Error getting invoice stats: ' . $e->getMessage()
            ]);
        }
    }
}