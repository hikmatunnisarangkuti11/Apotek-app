<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $medicines = Medicine::orderBy('name', 'ASC')->simplePaginate(5);
        return view('medicine.index', compact('medicines'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('medicine.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3',
            'type' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|numeric'
        ]);

        Medicine::create([
            'name' => $request->name,
            'type' => $request->type,
            'price' => $request->price,
            'stock' => $request->stock
        ]);

        return redirect()->back()->with('success', 'Berhasil Menambahkan Data Obat!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $medicine = Medicine::find($id);
        //mengembalikan bentuk json dikirim data yang di ambil dengan response status code 200
        // response status code api :
        // 200 -> succes/ok
        // 400 an -> error kode/validasi input user
        // 419 -> error token csrf
        // 500 an -> error server hosting
        return response()->json($medicine, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // sudah dipilih lalu di ubah tanpa kondisi
        $medicine = Medicine::find($id);

        return view('medicine.edit', compact('medicine'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|min:3',
            'type' => 'required',
            'price' => 'required|numeric',
        ]);
        //
        Medicine::where('id', $id)->update([
            'name' => $request->name,
            'type' => $request->type,
            'price' => $request->price,
        ]);

        return redirect()->route('medicine.data')->with('success', 'Berhasil Mengubah Data!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //cari dan hapus data
        Medicine::where('id', $id)->delete();
        return redirect()->back()->with('deleted', 'Berhasil menghapus data!');
    }

    public function stockData()
    {
        $medicines = Medicine::orderBy('stock', 'ASC')->simplePaginate(5);
        return view('medicine.stock', compact('medicines'));

    }

    public function updateStock(Request $request, $id)
    {
        //validasi input
        $request->validate([
            'stock' => 'required|numeric',
        ], [
            'stock.required' => "Input stock harus diisi",
    ]);
        // a,bil data sebelum update, untuk dibandingkan
        $medicineBefore = Medicine::where('id', $id)->first();
        if($request->stock <= $medicineBefore['stock']) {
            //
            return response()->json(['message' => 'Stock tidak boleh kurang/sama dengan stock sembelumnya'], 400);
        }
        $medicineBefore->update(['stock' => $request->stock]);
        return response()->json('Berhasil',200);
    }

}
