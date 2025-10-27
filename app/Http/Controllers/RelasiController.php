<?php
namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\Hobi;

class RelasiController extends Controller
{

      public function oneToOne()
    {
        $mahasiswas = Mahasiswa::with('wali')->get();
        return view('relasi.one_to_one', compact('mahasiswas'));
    }

     public function oneToMany()
    {
        // Ambil dosen beserta mahasiswa bimbingannya
        $dosens = Dosen::with('mahasiswas')->get();
        return view('relasi.one-to-many', compact('dosens'));
    }
    public function manyToMany()
    {
        $mahasiswas = Mahasiswa::with('hobis')->get();
        return view('relasi.many-to-many', compact('mahasiswas'));
    }

     public function eloquent()
    {
        // Ambil semua data mahasiswa lengkap dengan relasinya
        $mahasiswa = Mahasiswa::with('wali', 'dosen', 'hobi')->get();

        // Kirim data ke view
        return view('relasi.eloquent', compact('mahasiswa'));
    }

}
