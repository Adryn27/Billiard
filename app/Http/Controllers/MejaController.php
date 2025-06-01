<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Meja;
use App\Models\Reservasi;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MejaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $meja=Meja::orderby('nomor_meja','asc')->get();
        $kategori=Kategori::orderby('updated_at','desc')->get();
        return view('backend.v_meja.index', [
            'judul'=>'Table',
            'index'=>$meja,
            'create'=>$kategori
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'kategori_id' => 'required',
            'nomor_meja' => 'required|max:2|unique:meja'
        ];

        $validatedData = $request->validate($rules);
        Meja::create($validatedData);
        return redirect()->route('backend.table.index')->with('success', 'Data Berhasil Tersimpan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $meja = Meja::findOrFail($id);
        $rules = [
            'kategori_id' => 'required',
            'nomor_meja' => 'required|max:2|unique:meja,nomor_meja,' . $id,
        ];
        $validatedData = $request->validate($rules);
        $meja->update($validatedData);
        return redirect()->route('backend.table.index')->with('success', 'Data berhasil diperbaharui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $meja = Meja::findOrFail($id);
        $meja->delete();
        return redirect()->route('backend.table.index')->with('success', 'Data berhasil dihapus');
    }

}
