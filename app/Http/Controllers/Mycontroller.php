<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Mycontroller extends Controller
{
    public function hello()
    {
       $nama = "satmul";
    $umur = 17; 

    return view('hello',compact('nama','umur'));
   }

   public function siswa(){
    $data = [
        ['nama'=>'satmul','alamat'=>'bandung'],
         ['nama'=>'kin','alamat'=>'jakarta'],
          ['nama'=>'mul','alamat'=>'bateng'],
          ['nama' => 'sat', 'alamat' => 'jatim'],

    ];
    return view('siswa',compact('data'));
   }
}
