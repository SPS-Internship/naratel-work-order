<?php
namespace Controllers;

use Models\Mitra;
use Exception;

class MitraController {

    private function respond($success, $data = null, $message = '', $status = 200){
        http_response_code($status);
        echo json_encode(['success'=>$success,'data'=>$data,'message'=>$message]);
        exit;
    }

    public function index() {
        try {
            $mitras = Mitra::all();
            $this->respond(true, $mitras, 'List mitras');
        } catch(Exception $e){
            $this->respond(false,null,'Error: '.$e->getMessage(),500);
        }
    }

    public function show($kode) {
        $mitra = Mitra::find($kode);
        if(!$mitra) $this->respond(false,null,'Mitra not found',404);
        $this->respond(true, $mitra, 'Detail mitra');
    }

    public function store() {
        $data = json_decode(file_get_contents('php://input'), true);
        if(!$data) $this->respond(false,null,'Input tidak valid',400);
        try {
            $mitra = Mitra::create($data);
            $this->respond(true, $mitra, 'Created',201);
        } catch(Exception $e){
            $this->respond(false,null,'Gagal menyimpan: '.$e->getMessage(),500);
        }
    }

    public function update($kode){
        $mitra = Mitra::find($kode);
        if(!$mitra) $this->respond(false,null,'Mitra tidak ditemukan',404);
        $data = json_decode(file_get_contents('php://input'), true);
        try{
            $mitra->update($data);
            $this->respond(true, $mitra, 'Updated');
        } catch(Exception $e){
            $this->respond(false,null,'Gagal update: '.$e->getMessage(),500);
        }
    }

    public function destroy($kode){
        $mitra = Mitra::find($kode);
        if(!$mitra) $this->respond(false,null,'Mitra tidak ditemukan',404);
        try{
            $mitra->delete();
            $this->respond(true,null,'Deleted');
        } catch(Exception $e){
            $this->respond(false,null,'Gagal hapus: '.$e->getMessage(),500);
        }
    }
}
