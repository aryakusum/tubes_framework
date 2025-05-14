<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayaran';

    protected $fillable = [
        'penjualan_id',
        'tgl_bayar',
        'jenis_pembayaran',
        'transaction_time',
        'gross_amount',
        'order_id',
        'payment_type',
        'status_code',
        'transaction_id',
        'settlement_time',
        'status_message',
        'merchant_id'
    ];

    protected $casts = [
        'tgl_bayar' => 'date',
        'transaction_time' => 'datetime',
        'settlement_time' => 'datetime',
        'gross_amount' => 'decimal:2'
    ];

    public $timestamps = true;

    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class, 'penjualan_id');
    }
}
