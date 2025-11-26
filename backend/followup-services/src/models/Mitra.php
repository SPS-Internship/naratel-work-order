<?php

namespace Models;

use Illuminate\Database\Eloquent\Model;

class Mitra extends Model
{
    protected $table = 'tbl_mitra';
    protected $primaryKey = 'kode_mitra';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'kode_mitra',
        'nama_mitra',
        'kontak',
        'alamat'
    ];

    public function requestExtends()
    {
        return $this->hasMany(RequestExtend::class, 'kode_mitra','nama_mitra','kontak','alamat');
    }
}
    