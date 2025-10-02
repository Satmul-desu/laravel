<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class post extends Model
{
    //secara ontomatis model ini akan terhubung ke tabel posts
    protected $table = 'posts';
    public $fillable = ['title','content'];
    public $visible = ['title','content'];
    public $timestamps = true;
}
