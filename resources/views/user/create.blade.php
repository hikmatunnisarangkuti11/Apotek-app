@extends('layouts.template')

@section('content')
<form action="{{ route('users.store') }}" method="post" class="card bg-light mt-5 p-5">
    @csrf
    @if ($errors->any())
    <ul class="alert alert-danger p-5">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
        
    @endif
        @if (Session::get('success'))
            <div class="alert alert-success">{{ Session::get('success') }}</div>
        @endif
            
        <div class="mb-3 row">
            <label for="name" class="col-sm-2 col-form-label">Nama</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="name" name="name">
            </div>
        </div>
        <div class="mb-3 row">
            <label for="name" class="col-sm-2 col-form-label">Email</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="email" name="email">
            </div>
        </div>
        <div class="mb-3 row">
            <label for="name" class="col-sm-2 col-form-label">Role</label>
            <div class="col-sm-10">
                <select name="role" id="role" class="form-control">
                    <option selected disabled hidden>Pilih</option>
                    <option value="admin">Admin</option>
                    <option value="cashier">Kasir</option>
                </select>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Simpan Data</button>
    </form>

@endsection