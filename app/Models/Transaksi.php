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

    public function getKodeTransaksiWithPelangganAttribute()
    {
        $namaPelanggan = $this->pelanggan ? $this->pelanggan->nama : 'Pelanggan Tidak Diketahui';
        return $this->kode_transaksi . ' ── ' . $namaPelanggan . '';
    }
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->kode_transaksi = 'TRX-' . strtoupper(uniqid());
            $model->tanggal = now();
        });

        // Observer for history logging
        static::created(function ($transaksi) {
            \App\Models\HistoryTransaksi::create([
                'action' => 'create',
                'transaksi_id' => $transaksi->id,
                'new_data' => $transaksi->toArray(),
                'user_id' => auth()->id(),
            ]);
        });

        static::updating(function ($transaksi) {
            $original = $transaksi->getOriginal();
            \App\Models\HistoryTransaksi::create([
                'action' => 'update',
                'transaksi_id' => $transaksi->id,
                'old_data' => $original,
                'new_data' => $transaksi->toArray(),
                'user_id' => auth()->id(),
            ]);
        });

        static::deleting(function ($transaksi) {
            \App\Models\HistoryTransaksi::create([
                'action' => 'delete',
                'transaksi_id' => $transaksi->id,
                'old_data' => $transaksi->toArray(),
                'user_id' => auth()->id(),
            ]);
        });
    }

}


