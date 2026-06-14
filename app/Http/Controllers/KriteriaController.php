<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use Illuminate\Http\Request;

class KriteriaController extends Controller
{
    // Menampilkan daftar kriteria
    public function index()
    {
        $kriterias = Kriteria::all();
        return view('kriteria.index', compact('kriterias'));
    }

    // Menampilkan form tambah kriteria
    public function create()
    {
        return view('kriteria.create');
    }

    // Menyimpan kriteria baru
    public function store(Request $request)
    {
        $request->validate([
            'kode_kriteria' => 'required|unique:kriteria,kode_kriteria|max:5',
            'nama_kriteria' => 'required|string|max:255',
            'bobot' => 'required|numeric',
            'jenis' => 'required|in:benefit,cost'
        ]);

        Kriteria::create($request->all());

        return redirect()->route('kriteria.index')->with('success', 'Kriteria berhasil ditambahkan!');
    }

    // --- FUNGSI BARU UNTUK EDIT & UPDATE ---

    // Menampilkan form edit kriteria
    public function edit($id)
    {
        $kriteria = Kriteria::findOrFail($id);
        return view('kriteria.edit', compact('kriteria'));
    }

    // Menyimpan perubahan data kriteria ke database
    public function update(Request $request, $id)
    {
        $request->validate([
            'kode_kriteria' => 'required|max:5|unique:kriteria,kode_kriteria,' . $id,
            'nama_kriteria' => 'required|string|max:255',
            'bobot' => 'required|numeric',
            'jenis' => 'required|in:benefit,cost'
        ]);

        $kriteria = Kriteria::findOrFail($id);
        $kriteria->update($request->all());

        return redirect()->route('kriteria.index')->with('success', 'Data kriteria berhasil diperbarui!');
    }

    // Menghapus kriteria
    public function destroy($id)
    {
        $kriteria = Kriteria::findOrFail($id);
        $kriteria->delete();

        return redirect()->route('kriteria.index')->with('success', 'Kriteria berhasil dihapus!');
    }
    /**
 * Memperbarui bobot seluruh kriteria secara massal (batch)
 */
    public function updateBatch(Request $request)
    {
    $request->validate([
        'bobot' => 'required|array',
        'bobot.*' => 'required|numeric|min:0|max:100',
    ]);

    // Lakukan perulangan untuk menyimpan masing-masing bobot kriteria
    foreach ($request->bobot as $id => $nilaiBobot) {
        $kriteria = Kriteria::find($id);
        if ($kriteria) {
            $kriteria->update(['bobot' => $nilaiBobot]);
        }
    }

    return redirect()->route('kriteria.index')->with('success', 'Bobot seluruh kriteria berhasil diperbarui!');
    }
}