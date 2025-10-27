<?php
namespace App\Http\Controllers;

use App\Models\Wali;
use Illuminate\Http\Request;

class WaliController extends Controller
{
    public function index()
    {
        $wali = Wali::latest()->get();
        return view('wali.index', compact('wali'));
    }

    public function create()
    {
        return view('wali.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'    => 'required|string|max:255',
            'no_telp' => 'required|string|max:15',
            'alamat'  => 'required|string|max:255',
        ]);

        Wali::create($validated);

        return redirect()->route('wali.index')->with('success', 'Data wali berhasil disimpan!');
    }

    public function show(string $id)
    {
        $wali = Wali::findOrFail($id);
        return view('wali.show', compact('wali'));
    }

    public function edit(string $id)
    {
        $wali = Wali::findOrFail($id);
        return view('wali.edit', compact('wali'));
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'nama'    => 'required|string|max:255',
            'no_telp' => 'required|string|max:15',
            'alamat'  => 'required|string|max:255',
        ]);

        $wali = Wali::findOrFail($id);
        $wali->update($validated);

        return redirect()->route('wali.index')->with('success', 'Data wali berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        $wali = Wali::findOrFail($id);
        $wali->delete();

        return redirect()->route('wali.index')->with('success', 'Data wali berhasil dihapus!');
    }
}
