<?php
 // app/Models/Mahasiswa.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    protected $fillable = ['nama', 'nim', 'kelas', 'id_dosen'];

    public function wali()
    {
        return $this->hasOne(Wali::class, 'id_mahasiswa');
    }

    // app/Models/Mahasiswa.php
public function dosen()
{
    return $this->belongsTo(Dosen::class, 'id_dosen');
}

// app/Models/Mahasiswa.php
public function hobi()
{
    return $this->belongsToMany(Hobi::class, 'mahasiswa_hobi', 'id_mahasiswa', 'id_hobi');
}



}


