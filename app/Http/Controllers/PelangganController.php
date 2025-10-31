<?php
namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    public function index()
    {
   $pelanggans = Pelanggan::all();
return view('pelanggan.index', compact('pelanggans'));


    }

    public function create()
    {
        return view('pelanggan.create');
    }

    public function store(Request $request)
{
    $request->validate([
        'nama' => 'required',
        'email' => 'nullable|email',
        'no_telp' => 'nullable',
        'alamat' => 'required'
    ]);

    Pelanggan::create($request->all());

    return redirect()->route('pelanggan.index')->with('success', 'Pelanggan berhasil ditambahkan.');
}


    public function edit($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        return view('pelanggan.edit', compact('pelanggan'));
    }

 
public function update(Request $request, $id)
{
    $validated = $request->validate([
    'nama'   => 'required',
    'alamat' => 'required',
    'no_telp'  => 'nullable',
    'email'  => 'nullable|email',
]);


    $pelanggan = Pelanggan::findOrFail($id);
    $pelanggan->update($request->all());

    return redirect()->route('pelanggan.index')->with('success', 'Pelanggan berhasil diperbarui.');
}
    public function destroy($id)
    {
        Pelanggan::findOrFail($id)->delete();
        return redirect()->route('pelanggan.index')->with('success', 'Pelanggan berhasil dihapus!');
    }

    public function show($id)
    {
        $pelanggan = Pelanggan::with('transaksis.detailTransaksis.produk', 'transaksis.pembayaran')->findOrFail($id);
        return view('pelanggan.show', compact('pelanggan'));
    }
}
