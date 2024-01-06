@extends('layouts.template')

@section('content')
<form action="{{ route('medicine.update', $medicine ['id']) }}" method="POST" class="card bg-light mt-5 p-5">
    @csrf
    @method('PATCH')
    @if ($errors->any())
    <ul class="alert alert-danger p-5">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
        
    @endif
            
        <div class="mb-3 row">
            <label for="name" class="col-sm-2 col-form-label">Nama Obat</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="name" name="name" value="{{ $medicine['name'] }}">
            </div>
        </div>
        <div class="mb-3 row">
            <label for="type" class="col-sm-2 col-form-label">Tipe Obat</label>
            <div class="col-sm-10">
                <select id="type" class="form-control" name="type">
                    <option disabled hidden selected>Pilih</option>
                    <option value="tablet" {{ $medicine['type'] == 'tablet' ? 'selected' : '' }}>Tablet</option>
                    <option value="sirup" {{ $medicine['type'] == 'sirup' ? 'selected' : '' }}>Sirup</option>
                    <option value="kapsul" {{ $medicine['type'] == 'kapsul' ? 'selected' : '' }}>Kapsul</option>
                </select>
            </div>
        </div>
        <div class="mb-3 row">
            <label for="price" class="col-sm-2 col-form-label">Harga Obat</label>
            <div class="col-sm-10">
                <input type="number" class="form-control" id="price" name="price" value="{{ $medicine['price'] }}">
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Ubah Data</button>
    </form>

@endsection