<?php
namespace Models;

use Illuminate\Database\Eloquent\Model;

class RequestExtend extends Model
{
    protected $table = 'tbl_request_extend';
    protected $primaryKey = 'kode_user';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = true;

    protected $fillable = [
        'kode_user',
        'paket_inet',
        'nominal_paket',
        'kode_mitra',
        'log_update',
        'tgl_request',
        'status_request',
        'status_nominal',
        'code_invoice',
        'code_invoice_mitra',
        'tgl_posting',
        'mitra_depart',
        'status_piutang',
        'status_wa',
        'type_proses',
        'after_tgl',
        'before_tgl',
        'log_mitra',
        'status_telegram',
        'status_bayar',
    ];

    protected $casts = [
        'tgl_request'   => 'datetime',
        'tgl_posting'   => 'datetime',
        'after_tgl'     => 'datetime',
        'before_tgl'    => 'datetime',
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime',
        'nominal_paket' => 'integer'
    ];
}
