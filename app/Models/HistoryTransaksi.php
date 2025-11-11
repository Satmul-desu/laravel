<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoryTransaksi extends Model
{
    protected $fillable = ['action', 'transaksi_id', 'old_data', 'new_data', 'user_id'];

    protected $casts = [
        'old_data' => 'array',
        'new_data' => 'array',
    ];

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
