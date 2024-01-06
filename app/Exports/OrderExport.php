<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
// untuk menggunakan function headers
use Maatwebsite\Excel\Concerns\WithHeadings;
// untuk menggunakan function map
use Maatwebsite\Excel\Concerns\WithMapping;

use App\Models\Order;
use Carbon\Carbon;





class OrderExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    // proses pengembalian data yang akan di export excel
    public function collection()
    {
        return Order::with('user')->get();
    }
    // menentukan nama-nama column di excel nya
    public function headings() : array
    {
        return [
            "Nama Pembeli", "Pesanan", "Total Harga (+PPN)", "Kasir", "Tanggal"
        ];
    }
    // data dari collection (pengambilan dari DB) yang akan dimunculkan ke excel 
    // untuk memanipulasi data
    public function map($item) : array
    {
        //  hasil dari column medicines di db yang tadinya array diubah ke format jadi :
            //  (vitamin c : qty 2 Rp. 10.000)
        $pesanan = "";
        foreach ($item['medicines'] as $medicine)
        {
            $pesanan .= "( " . $medicine ['name_medicine'] . " : qty " .
            $medicine['qty'] . " : Rp. " . number_format($medicine['price_after_qty'], 0, '.','.'). " ),";
        }

        // menghitung total harga ditambah PPN
        $totalAfterPPN = $item['total_price'] + ($item['total_price'] * 0.1);
        // urutannya harus sama dengan yang di headings
        return [
            $item['name_customer'], $pesanan, "Rp. " . number_format($totalAfterPPN,
            0,'.', '.'), $item['user']['name'] . "(" . $item['user']['email'] . ")", 
            Carbon::parse($item['created_at'])->format("d-m-y H:i:s") 
        ];
    }

    
}
