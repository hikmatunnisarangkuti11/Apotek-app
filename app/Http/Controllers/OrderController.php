<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF;
use App\Exports\OrderExport;
use Excel;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // with : mengambi function relasi PK ke FK atau FK ke PK dari model
        // isi di petik disamakan dengan nama function di modelnya
        $orders = Order::with('user')->simplePaginate(5);
        return view('order.kasir.index', compact('orders'));
    }

    public function data()
    {
        $orders = Order::with('user')->simplePaginate(5);
        return view('order.admin.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $medicines = Medicine::all();
        return view('order.kasir.create', compact('medicines'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "name_customer" => "required",
            "medicines" => "required",
        ]);
        // array_count_values : menghitung jumlah item sama di jalan array
        // hasilnya berbentuk : "itemnya" => "jumlah yang sama"
        // menentukan qty
        $medicines = array_count_values($request->medicines);
        
        $dataMedicines = [];
        foreach ($medicines as $key => $value) {
            $medicine = Medicine::where('id', $key)->first();
            $arrayAssoc = [
                "id" => $key,
                "name_medicine" => $medicine['name'],
                "price" => $medicine['price'],
                "qty" => $value,
                // (int) => memastikan dan mengubah tipe data menjadi integer
                "price_after_qty" => (int)$value * (int)$medicine['price'],
            ];
            //format assoc timasukkan ke array penampung sebelumnya
            array_push($dataMedicines, $arrayAssoc);
        }

        // var totalprice awalnya 0
        $totalPrice = 0;
        // loop data dari array penampung yang udah di format
        foreach ($dataMedicines as $formatArray) {
            // dia bakal menjumlahkan totalprice sebelumnya di tambah data harga dari price_after_qty
            $totalPrice += (int)$formatArray['price_after_qty'];
        }

        $prosesTambahData = Order::create([
            'name_customer' => $request->name_customer,
            'medicines' => $dataMedicines,
            'total_price' => $totalPrice,
            //user_id menyimpan data id dari orang yang login(kasir penanggung jawabe)
            'user_id' => Auth::user()->id,
        ]);
        // redirect ke halaman struk
        return redirect()->route('order.struk', $prosesTambahData['id']);

    }


    public function strukPembelian($id)
    {
        $order = Order::where('id', $id)->first();
        return view('order.kasir.struk', compact('order'));
    }

    public function downloadPDF($id)
    {
        // get data yang aka ditampilkan  di pdf
        // data yang dikirim ke pdf wajib bertipe data array
        $order = Order::where('id', $id)->first()->toArray();

        // ketika data dipanggil di blade PDF, akan dipanggil dengan $ apa
        view()->share('order', $order);

        // lokasi dan nama blade yang akan di download ke pdf serta data yang akan ditampikan
        $pdf = PDF::loadview('order.kasir.download', $order);

        // ketika di download nama file nya apa
        return $pdf->download('Bukti Pembelian.pdf');
    }

    public function search(Request $request)
{
    $searchDate = $request->input('search');

    $orders = Order::whereDate('created_at', $searchDate)->simplePaginate(5);

    return view('order.kasir.index', compact('orders'));
}
public function searchAdmin(Request $request)
{
    $searchDate = $request->input('search');

    $orders = Order::whereDate('created_at', $searchDate)->simplePaginate(5);

    return view('order.admin.index', compact('orders'));
}

    public function downloadExcel()
    {
        // nama file excel ketika di download
        $file_name = 'Data Seluruh Pembelian.xlsx';
        // panggil logic exports nya
        return Excel::download(new OrderExport, $file_name);
    }

    
    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
}
