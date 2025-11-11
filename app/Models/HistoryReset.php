<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoryReset extends Model
{
    protected $fillable = ['reset_at', 'table_name', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
