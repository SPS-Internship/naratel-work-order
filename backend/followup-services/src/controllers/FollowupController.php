<?php
namespace Controllers;

use Models\Followup;
use Exception;
use Helpers\Response;

class FollowupController
{
    // ✅ Ambil semua data dengan urutan ID ASC
    public function index()
    {
        try {
            $query = Followup::with('requestExtends')->orderBy('id', 'asc');
            
            if (isset($_GET['status']) && $_GET['status'] !== '') {
                $query->where('status', $_GET['status']);
            }
            
            $data = $query->get();
            Response::success($data, 'List followups');
        } catch (Exception $e) {
            Response::error($e->getMessage());
        }
    }

    // ✅ Ambil detail berdasarkan ID
    public function show($id)
    {
        try {
            $item = Followup::with('requestExtends')->find($id);
            if (!$item) {
                Response::error('Followup not found', 404);
                return;
            }
            Response::success($item, 'Detail followup');
        } catch (Exception $e) {
            Response::error($e->getMessage());
        }
    }

    // ✅ Endpoint tambahan: Ambil kode_user berikutnya (untuk preview di frontend)
    public function nextCode()
    {
        try {
            $last = Followup::orderBy('id', 'desc')->first();
            $lastCode = $last ? intval(substr($last->kode_user, 3)) : 0;
            $newCode = 'USR' . str_pad($lastCode + 1, 3, '0', STR_PAD_LEFT);
            Response::success(['next_code' => $newCode], 'Next kode user');
        } catch (Exception $e) {
            Response::error($e->getMessage());
        }
    }

    // ✅ Simpan data baru dengan auto-generate kode_user
    public function store()
    {
        try {
            $data = json_decode(file_get_contents("php://input"), true);
            if (!$data) {
                Response::error('Input tidak valid', 400);
                return;
            }

            $items = isset($data[0]) && is_array($data[0]) ? $data : [$data];
            $created = [];
            $errors = [];

            $fillable = (new Followup())->getFillable();

            foreach ($items as $i => $item) {
                // Generate kode_user otomatis
                $last = Followup::orderBy('id', 'desc')->first();
                $lastCode = $last ? intval(substr($last->kode_user, 3)) : 0;
                $newCode = 'USR' . str_pad($lastCode + 1, 3, '0', STR_PAD_LEFT);
                $item['kode_user'] = $newCode; // override agar tidak bisa diisi manual

                // Validasi semua field wajib ada, kecuali kode_user karena sudah digenerate
                $missingFields = [];
                foreach ($fillable as $field) {
                    if ($field === 'kode_user') continue; // skip validasi karena kita isi sendiri
                    if (!array_key_exists($field, $item)) {
                        $missingFields[] = $field;
                    }
                }

                if (!empty($missingFields)) {
                    $errors[] = "Item ke-$i: Field berikut wajib diisi dan lengkap: " . implode(', ', $missingFields);
                    continue;
                }

                try {
                    $created[] = Followup::create($item);
                } catch (Exception $e) {
                    $errors[] = "Item ke-$i: " . $e->getMessage();
                }
            }

            if (!empty($errors)) {
                Response::error('Sebagian atau semua data gagal disimpan', 207, [
                    'created' => $created,
                    'errors' => $errors
                ]);
                return;
            }

            Response::success($created, 'Created', 201);
        } catch (Exception $e) {
            Response::error('Error creating followup: ' . $e->getMessage());
        }
    }

    // ✅ Update data berdasarkan ID (kode_user tidak boleh diubah)
    public function update($id)
    {
        try {
            $data = json_decode(file_get_contents("php://input"), true);
            if (!$data) {
                Response::error('Input tidak valid', 400);
                return;
            }

            $item = Followup::find($id);
            if (!$item) {
                Response::error('Data tidak ditemukan', 404);
                return;
            }

            // Hapus kode_user dari data agar tidak bisa diubah
            unset($data['kode_user']);

            $item->update($data);
            Response::success($item, 'Updated');
        } catch (Exception $e) {
            Response::error('Gagal update: ' . $e->getMessage(), 500);
        }
    }

    // ✅ Hapus data
    public function destroy($id)
    {
        try {
            $item = Followup::find($id);
            if (!$item) {
                Response::error('Data tidak ditemukan', 404);
                return;
            }

            $item->delete();
            Response::success(null, 'Deleted');
        } catch (Exception $e) {
            Response::error('Delete failed: ' . $e->getMessage(), 500);
        }
    }
}