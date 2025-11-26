<?php
namespace Models;

use Illuminate\Database\Eloquent\Model;

class Followup extends Model
{
    protected $table = 'tb_followup';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;

    protected $fillable = [
        'kode_user',
        'kelurahan',
        'paket',
        'expiration',
        'jumday',
        'status',
        'tanggal_jthtempo',
        'janji_bayar',
        'keterangan2',
        'keterangan',
        'kd_layanan',
        'status_log',
        'status_followup',
        'status_postwo',
        'tanggal_terakhir',
        'tanggal_status',
        'pic',
        'status_reminder',
        'expected_date',
        'cw_conv'
    ];

    protected $casts = [
        'expiration'        => 'date',
        'tanggal_jthtempo'  => 'date',
        'janji_bayar'       => 'date',
        'tanggal_terakhir'  => 'date',
        'tanggal_status'    => 'date',
        'expected_date'     => 'date',
        'jumday'            => 'integer'
    ];

    /**
     * Relasi: Followup punya banyak RequestExtend
     */
    public function requestExtends()
    {
        return $this->hasMany('\Models\RequestExtend', 'kode_user', 'kode_user');
    }

    /**
     * Scope untuk filter berdasarkan status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope untuk data aktif
     */
    public function scopeActive($query)
    {
        return $query->where('status', '!=', 'deleted');
    }
}