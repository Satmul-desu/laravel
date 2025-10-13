<?php
namespace App\Http\Controllers;

use App\Models\Biodata;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class BiodataController extends Controller
{
    public function index()
    {
        $items = Biodata::latest()->paginate(10);
        return view('biodata.index', compact('items'));
    }

    public function create()
    {
        $agamaOptions = ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu', 'Lainnya'];
        return view('biodata.create', compact('agamaOptions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'         => 'required|string|max:255',
            'tgl_lahir'    => 'required|date',
            'jk'           => ['required', Rule::in(['L', 'P'])],
            'agama'        => 'required|string|max:50',
            'alamat'       => 'nullable|string',
            'tinggi_badan' => 'nullable|integer|min:0',
            'berat_badan'  => 'nullable|integer|min:0',
            'foto'         => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        // Handle foto upload
        if ($request->hasFile('foto')) {
            $file              = $request->file('foto');
            $filename          = Str::slug($request->nama) . '-' . time() . '.' . $file->getClientOriginalExtension();
            $path              = $file->storeAs('biodata', $filename, 'public'); // disimpan di storage/app/public/biodata
            $validated['foto'] = $path;
        }

        Biodata::create($validated);

        return redirect()->route('biodata.index')->with('success', 'Data biodata berhasil dibuat.');
    }

    public function show(Biodata $biodatum)
    {
        return view('biodata.show', ['item' => $biodatum]);
    }

    public function edit(Biodata $biodatum)
    {
        $agamaOptions = ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu', 'Lainnya'];
        return view('biodata.edit', ['item' => $biodatum, 'agamaOptions' => $agamaOptions]);
    }

    public function update(Request $request, Biodata $biodatum)
    {
        $validated = $request->validate([
            'nama'         => 'required|string|max:255',
            'tgl_lahir'    => 'required|date',
            'jk'           => ['required', Rule::in(['L', 'P'])],
            'agama'        => 'required|string|max:50',
            'alamat'       => 'nullable|string',
            'tinggi_badan' => 'nullable|integer|min:0',
            'berat_badan'  => 'nullable|integer|min:0',
            'foto'         => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            // hapus foto lama jika ada
            if ($biodatum->foto && Storage::disk('public')->exists($biodatum->foto)) {
                Storage::disk('public')->delete($biodatum->foto);
            }
            $file              = $request->file('foto');
            $filename          = Str::slug($request->nama) . '-' . time() . '.' . $file->getClientOriginalExtension();
            $path              = $file->storeAs('biodata', $filename, 'public');
            $validated['foto'] = $path;
        }

        $biodatum->update($validated);

        return redirect()->route('biodata.index')->with('success', 'Data biodata berhasil diperbarui.');
    }

    public function destroy(Biodata $biodatum)
    {
        // hapus file foto
        if ($biodatum->foto && Storage::disk('public')->exists($biodatum->foto)) {
            Storage::disk('public')->delete($biodatum->foto);
        }

        $biodatum->delete();

        return redirect()->route('biodata.index')->with('success', 'Data biodata berhasil dihapus.');
    }
}
