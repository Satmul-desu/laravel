<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;
   protected $fillable = [
    'transaksi_id',
    'jumlah_bayar',
    'metode',
    'tanggal_bayar',
    'kembalian',
];

    protected $casts = [
        'tanggal_bayar' => 'datetime',
    ];


    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->tanggal_bayar = now();
        });
    }
}

