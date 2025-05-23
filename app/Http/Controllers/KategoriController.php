<?php

namespace App\Http\Controllers;

use App\Helpers\ImageHelper;
use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kategori=Kategori::orderby('updated_at','desc')->get();
        return view('backend.v_kategori.index', [
            'judul'=>'Category',
            'index'=>$kategori,
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
            'nama_kategori' => 'required|max:255|unique:kategori',
            'fasilitas' => 'required',
            'harga' => 'required',
            'foto' => 'required|image|mimes:jpeg,jpg,png,gif|file|max:1024',
        ];

        $validatedData = $request->validate($rules);

        if ($request->file('foto')) {
            $file = $request->file('foto');
            $extension = $file->getClientOriginalExtension();
            $originalFileName = date('YmdHis') . '_' . uniqid() . '.' . $extension;
            $directory = 'storage/img-kategori/';
            // Simpan gambar asli
            $fileName = ImageHelper::uploadAndResize($file, $directory, $originalFileName);
            $validatedData['foto'] = $fileName;
            // create thumbnail 1 (lg)
            $thumbnailLg = 'thumb_lg_' . $originalFileName;
            ImageHelper::uploadAndResize($file, $directory, $thumbnailLg, 800, null);
            // create thumbnail 2 (md)
            $thumbnailMd = 'thumb_md_' . $originalFileName;
            ImageHelper::uploadAndResize($file, $directory, $thumbnailMd, 500, 500);
            // create thumbnail 3 (sm)
            $thumbnailSm = 'thumb_sm_' . $originalFileName;
            ImageHelper::uploadAndResize($file, $directory, $thumbnailSm, 100, 100);
            // Simpan nama file asli di database
            $validatedData['foto'] = $originalFileName;
        }
        Kategori::create($validatedData);
        return redirect()->route('backend.category.index')->with('success', 'Data Berhasil Tersimpan');
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
        $kategori = Kategori::findOrFail($id);
        $rules = [
            'nama_kategori' => 'required|max:255|unique:kategori,nama_kategori,' . $id,
            'fasilitas' => 'required',
            'harga' => 'required',
            'foto' => 'image|mimes:jpeg,jpg,png,gif|file|max:1024',
        ];
        $validatedData = $request->validate($rules);

        if ($request->file('foto')) {
            //hapus gambar lama
            if ($kategori->foto) {
                $oldImagePath = public_path('storage/img-kategori/') . $kategori->foto;
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
                $oldThumbnailLg = public_path('storage/img-kategori/') . 'thumb_lg_' .
                    $kategori->foto;
                if (file_exists($oldThumbnailLg)) {
                    unlink($oldThumbnailLg);
                }
                $oldThumbnailMd = public_path('storage/img-kategori/') . 'thumb_md_' .
                    $kategori->foto;
                if (file_exists($oldThumbnailMd)) {
                    unlink($oldThumbnailMd);
                }
                $oldThumbnailSm = public_path('storage/img-kategori/') . 'thumb_sm_' .
                    $kategori->foto;
                if (file_exists($oldThumbnailSm)) {
                    unlink($oldThumbnailSm);
                }
            }
            $file = $request->file('foto');
            $extension = $file->getClientOriginalExtension();
            $originalFileName = date('YmdHis') . '_' . uniqid() . '.' . $extension;
            $directory = 'storage/img-kategori/';
            // Simpan gambar asli
            $fileName = ImageHelper::uploadAndResize($file, $directory, $originalFileName);
            $validatedData['foto'] = $fileName;
            // create thumbnail 1 (lg)
            $thumbnailLg = 'thumb_lg_' . $originalFileName;
            ImageHelper::uploadAndResize($file, $directory, $thumbnailLg, 800, null);
            // create thumbnail 2 (md)
            $thumbnailMd = 'thumb_md_' . $originalFileName;
            ImageHelper::uploadAndResize($file, $directory, $thumbnailMd, 500, 519);
            // create thumbnail 3 (sm)
            $thumbnailSm = 'thumb_sm_' . $originalFileName;
            ImageHelper::uploadAndResize($file, $directory, $thumbnailSm, 100, 110);
            // Simpan nama file asli di database
            $validatedData['foto'] = $originalFileName;
        }
        $kategori->update($validatedData);
        return redirect()->route('backend.category.index')->with('success', 'Data berhasil diperbaharui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $kategori = kategori::findOrFail($id);

        $directory = public_path('storage/img-kategori/');
        if ($kategori->foto) {
            // Hapus gambar asli
            $oldImagePath = $directory . $kategori->foto;
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }
            // Hapus thumbnail lg
            $thumbnailLg = $directory . 'thumb_lg_' . $kategori->foto;
            if (file_exists($thumbnailLg)) {
                unlink($thumbnailLg);
            }
            // Hapus thumbnail md
            $thumbnailMd = $directory . 'thumb_md_' . $kategori->foto;
            if (file_exists($thumbnailMd)) {
                unlink($thumbnailMd);
            }
            // Hapus thumbnail sm
            $thumbnailSm = $directory . 'thumb_sm_' . $kategori->foto;
            if (file_exists($thumbnailSm)) {
                unlink($thumbnailSm);
            }
        }
        $kategori->delete();
        return redirect()->route('backend.category.index')->with('success', 'Data berhasil dihapus');
    }
}
