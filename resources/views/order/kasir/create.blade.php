@extends('layouts.template')

@section('content')
    <form action="{{ route('order.store')}}" class="card p-4 mt-5" method="POST">
        @csrf
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif
        <div class="mb-3 d-flex align-items-center">
            <label for="name_customer" class="form-label" style="width: 16%">Penanggung Jawab :</label>
            <p style="width: 95%; margin-top: 10px;"><b>{{ Auth::user()->name }}</b></p>
        </div>
        <div class="mb-3 d-flex align-items-center">
            <label for="name_customer" class="form-label" style="width: 12%">Nama Pembeli :</label>
            <div class="col-sm-10">
            <input type="text" name="name_customer" id="name_customer" class="form-control" style="width: 88%; margin-left: 1.5rem;">
            </div>
        </div>
        <div class="mb-3">
            <div class="d-flex align-items-center">
            <label for="medicines" class="form-label" style="width: 12%; ">Obat :</label>
            <div class="col-sm-10">

                <select name="medicines[]" id="medicines" class="form-control" style="width: 88%; margin-left: 1.5rem">
                    {{-- name dengan [] biasanya dipakai buat colum yang tipe datanya json/array, dan biasanya digunakan apabila input dengan tujuan data yang sama ada banyak (dan dari banyak input yang datanya sama tsb) --}}
                    <option selected hidden disabled>Pesanan 1</option>
                    @foreach ($medicines as $medicine)
                    <option value="{{ $medicine['id'] }}">{{ $medicine['name'] }}</option>
                    @endforeach
                    {{-- karena akan ada js yang menampilkan select ketika di click, maka sediakan temapat penyimpanan element yang akan dihasilkan dari js tersebut --}}
                </select>
            </div>
            </div>
            <div id="wrap-select"></div>
            <p class="text-primary" style="margin-left: 9.5rem; cursor: pointer;"onclick="addSelect()">+ tambah pesanan</p>
        </div>
        <button type="submit" class="btn block btn btn-primary">Konfirmasi Pembelian</button>
    </form>
@endsection

@push('script')
    <script>
        //
        let no = 2;
        function addSelect() {
            //mengandung content di enter, memamnggil variable
            let el = `<br><div class="d-flex align-items-center">
            <label for="medicines" class="form-label" style="width: 12%"></label>
            <div class="col-sm-10">
                <select name="medicines[]" id="medicines" class="form-control" style="width: 88%; margin-left: 1.5rem;">
                    <option selected hidden disabled>Pesanan ${no}</option>
                    @foreach ($medicines as $medicine)
                    <option value="{{ $medicine['id'] }}">{{ $medicine['name'] }}</option>
                    @endforeach                 
                </select>
            </div>`;
            //gunakan Jquery memanggil html tempat el baru akan ditambahkan 
            //append : menambahkan el html dibgaian bawah sebelum penutup tag terkait
            // # karena id 
            // Html : untuk mengubah 
            $("#wrap-select").append(el);
        //agar no pesanan bertambah sesuai jumlah select
        no++;
        }
    </script>
@endpush