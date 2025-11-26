<?php
namespace Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $table = 'tbl_invoice';
    protected $primaryKey = 'code_invoice';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = true;

    protected $fillable = [
        'code_invoice',
        'amount',
        'status',
        'tgl_invoice'
    ];

    protected $casts = [
        'amount' => 'float',
        'tgl_invoice' => 'datetime'
    ];

    protected $dates = [
        'tgl_invoice',
        'created_at',
        'updated_at'
    ];

    // ✅ Relasi dengan RequestExtend 
    public function requestExtends()
    {
        return $this->hasMany('\Models\RequestExtend', 'code_invoice', 'code_invoice');
    }

    // ✅ Scope untuk status
    public function scopeActive($query)
    {
        return $query->where('status', '!=', 'deleted');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'overdue');
    }

    // ✅ Scope untuk filtering berdasarkan tanggal
    public function scopeByMonth($query, $month, $year = null)
    {
        $year = $year ?: date('Y');
        return $query->whereYear('tgl_invoice', $year)
                    ->whereMonth('tgl_invoice', $month);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('tgl_invoice', [$startDate, $endDate]);
    }

    // ✅ Accessor untuk format amount
    public function getFormattedAmountAttribute()
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }

    // ✅ Accessor untuk status badge
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => '<span class="badge badge-warning">Pending</span>',
            'paid' => '<span class="badge badge-success">Paid</span>',
            'overdue' => '<span class="badge badge-danger">Overdue</span>',
            'cancelled' => '<span class="badge badge-secondary">Cancelled</span>'
        ];

        return $badges[$this->status] ?? '<span class="badge badge-light">' . ucfirst($this->status) . '</span>';
    }

    // ✅ Mutator untuk amount (pastikan selalu numeric)
    public function setAmountAttribute($value)
    {
        $this->attributes['amount'] = (float) str_replace(['Rp', '.', ','], '', $value);
    }

    // ✅ Helper method untuk cek status
    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isPaid()
    {
        return $this->status === 'paid';
    }

    public function isOverdue()
    {
        return $this->status === 'overdue';
    }
}