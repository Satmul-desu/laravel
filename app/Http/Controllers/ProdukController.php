<?php
namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    public function index(Request $request)
    {
        $query = Produk::query();

        // Search berdasarkan nama produk
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where('nama_produk', 'like', '%' . $search . '%');
        }

        $produks = $query->paginate(10);

        return view('produk.index', compact('produks'));
    }

    public function create()
    {
        return view('produk.create');
    }

    public function store(Request $request)
    {
        Produk::create($request->all());
        return redirect()->route('produk.index');
    }

    public function edit(Produk $produk)
    {
        return view('produk.edit', compact('produk'));
    }

    public function update(Request $request, Produk $produk)
    {
        $produk->update($request->all());
        return redirect()->route('produk.index');
    }

    public function destroy(Produk $produk)
    {
        $produk->delete();
        return redirect()->route('produk.index');
    }


    public function show($id)
    {
        $produk = Produk::with('detailTransaksis.transaksi.pelanggan', 'detailTransaksis.transaksi.pembayaran')->findOrFail($id);
        return view('produk.show', compact('produk'));
    }
}
