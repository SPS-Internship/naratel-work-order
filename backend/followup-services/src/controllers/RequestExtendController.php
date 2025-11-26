<?php
namespace Controllers;

use Models\RequestExtend;
use Exception;
use Helpers\Response;

class RequestExtendController
{
    // 🔧 Helper untuk format data agar kode_user muncul setelah id
    private function formatData($item)
    {
        if (!$item) return null;

        return [
            'id'                => $item->id,
            'kode_user'         => $item->kode_user,
            'paket_inet'        => $item->paket_inet,
            'nominal_paket'     => $item->nominal_paket,
            'kode_mitra'        => $item->kode_mitra,
            'log_update'        => $item->log_update,
            'tgl_request'       => $item->tgl_request,
            'status_request'    => $item->status_request,
            'status_nominal'    => $item->status_nominal,
            'code_invoice'      => $item->code_invoice,
            'code_invoice_mitra'=> $item->code_invoice_mitra,
            'tgl_posting'       => $item->tgl_posting,
            'mitra_depart'      => $item->mitra_depart,
            'status_piutang'    => $item->status_piutang,
            'status_wa'         => $item->status_wa,
            'type_proses'       => $item->type_proses,
            'after_tgl'         => $item->after_tgl,
            'before_tgl'        => $item->before_tgl,
            'log_mitra'         => $item->log_mitra,
            'status_telegram'   => $item->status_telegram,
            'status_bayar'      => $item->status_bayar,
            'created_at'        => $item->created_at,
            'updated_at'        => $item->updated_at,
        ];
    }

    // 🔧 Helper untuk generate kode baru
    private function generateKodeUser()
    {
        $last = RequestExtend::orderBy('kode_user', 'desc')->first();
        $lastCode = $last ? intval(substr($last->kode_user, 3)) : 0;
        return 'REQ' . str_pad($lastCode + 1, 3, '0', STR_PAD_LEFT);
    }

    // ✅ Ambil semua data dengan urutan terbaru
    public function index()
    {
        try {
            $query = RequestExtend::orderBy('created_at', 'desc')->get();
            $data = $query->map(fn($item) => $this->formatData($item));
            Response::success($data, 'List request extend');
        } catch (Exception $e) {
            Response::error($e->getMessage());
        }
    }

    // ✅ Ambil detail berdasarkan kode_user
    public function show($kode_user)
    {
        try {
            $item = RequestExtend::where('kode_user', $kode_user)->first();
            if (!$item) {
                Response::error('Request Extend not found', 404);
                return;
            }
            Response::success($this->formatData($item), 'Detail request extend');
        } catch (Exception $e) {
            Response::error($e->getMessage());
        }
    }

    // ✅ Endpoint tambahan: Ambil kode_user berikutnya
    public function nextCode()
    {
        try {
            $newCode = $this->generateKodeUser();
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

            foreach ($items as $i => $item) {
                try {
                    // Auto kode_user
                    $item['kode_user'] = $this->generateKodeUser();

                    // Simpan ke DB
                    $newItem = RequestExtend::create($item);
                    $created[] = $this->formatData($newItem);
                } catch (Exception $e) {
                    $errors[] = "Item ke-$i: " . $e->getMessage();
                }
            }

            if (!empty($errors)) {
                Response::success([
                    'created' => $created,
                    'errors' => $errors
                ], 'Sebagian data gagal disimpan');
                return;
            }

            Response::success($created, 'Created', 201);
        } catch (Exception $e) {
            Response::error('Error creating request extend: ' . $e->getMessage());
        }
    }

    // ✅ Update data (kode_user tidak boleh diubah)
    public function update($kode_user)
    {
        try {
            $data = json_decode(file_get_contents("php://input"), true);
            if (!$data) {
                Response::error('Input tidak valid', 400);
                return;
            }

            $item = RequestExtend::where('kode_user', $kode_user)->first();
            if (!$item) {
                Response::error('Data tidak ditemukan', 404);
                return;
            }

            unset($data['kode_user']); // cegah ubah kode_user

            $item->update($data);
            Response::success($this->formatData($item), 'Updated');
        } catch (Exception $e) {
            Response::error('Gagal update: ' . $e->getMessage(), 500);
        }
    }

    // ✅ Hapus data
    public function destroy($kode_user)
    {
        try {
            $item = RequestExtend::where('kode_user', $kode_user)->first();
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
