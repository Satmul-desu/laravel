<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;
    protected $fillable = ['kode_transaksi', 'tanggal', 'pelanggan_id', 'total_harga'];

    protected $casts = [
        'tanggal' => 'datetime',
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }

    public function detailTransaksis()
    {
        return $this->hasMany(DetailTransaksi::class);
    }

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class);
    }
    protected static function boot()
{
    parent::boot();

    static::creating(function ($model) {
        $model->kode_transaksi = 'TRX-' . strtoupper(uniqid());
        $model->tanggal = now();

    });
}

}


