{{--memanggil file template--}}
@extends('layouts.template')

{{--isi bagian yield--}}
@section('content')
    @if(Session::get('success'))
        <div class="alert alert-success">{{ Session::get('success') }}</div>
    @endif
    @if (Session::get('deleted'))
        <div class="alert alert-warning">{{ Session::get('deleted') }}</div>
    @endif

   
    <a href="{{ Route('users.create') }}" class="btn btn-secondary" style="float: right; mt-5" >Tambah Pengguna</a>
   <table class="table mt-5 table-striped table-bordered table-hovered">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Role</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach ($user as $item)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $item['name'] }}</td>
                <td>{{ $item['email'] }}</td>
                <td>{{ $item['role'] }}</td>
                <td class="d-flex">
                    <a href="{{ route('users.edit', $item['id']) }}" class="btn btn-success">Edit</a>
                    {{-- method::delete tidak bisa digunakan pada a href, harus melalui form action --}}
                    <form action="{{ route('users.delete', $item['id']) }}" method="post" class="ms-3">
                        @csrf
                        {{-- menimpa/mengubah method="post" agar menjadi method="delete" sesuai dengan method route (::delete) --}}
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </form> 
                </td>
            </tr>
            @endforeach
        </tbody>
   </table>
   <div class="d-flex justify-content-end">
        @if ($user->count())
            {{ $user->Links() }}
        @endif
   </div>
@endsection