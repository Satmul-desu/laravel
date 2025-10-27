<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    protected $fillable = ['nama', 'nipd']; // ubah dari 'nama_dosen' ke 'nama'

    public function mahasiswas()
    {
        return $this->hasMany(Mahasiswa::class, 'dosen_id');
    }
}
