@extends('layouts.template')

@section('content')
{{-- tempat alret berhasil --}}
<div id="msg-success"></div>
    <table class="table table-striped table-bordered table-hovered">
        <thead>
            <tr>
                <th>#</th>
                <th>Nama</th>
                <th>Stock</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach ($medicines as $item)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $item['name'] }}</td>
                    <td style="background: {{ $item['stock'] <= 3 ? 'red' : 'none' }}">{{ $item['stock'] }}</td>
                    <td>
                        <div class="btn btn-primary" onclick="edit({{ $item['id'] }})">Tambah Stock</div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{-- Pagination --}}
    <div class="d-flex justify-content-end">
        @if ($medicines->count())
            {{ $medicines->Links() }}
        @endif
   </div>
   <!-- Modal -->
    <div class="modal fade" id="tambah-stock" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Ubah Data Stock</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
        <form method="post" id="form-stock">
            <div class="modal-body">
                {{-- tempat alret error --}}
                <div id="msg"></div>
                {{-- input hidden tidak akan tertampil, biasanya digunakan untuk menyimpan data yang diperlukan di proses BE tapi tidak boeh di ketahui/diubah user --}}
                <input type="hidden" name="id" id="id">
                <div>
                    <label for="name">Nama Obat :</label>
                    <input type="text" name="name" id="name" class="form-control" disabled>
                </div>
                <div >
                    <label for="stock">Jumlah Stok :</label>
                    <input type="number" name="stock" id="stock" class="form-control">
                </div>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
        </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        // csrf token versi js
        $.ajaxSetup({
            headers:{
                'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr("content"),
            }
        })

        function edit(id) {
            //panggil route dari web.php yang akan menangani proses ambil satu data
            let url = "{{ route('medicine.show', 'id') }}";
            //ganti bagian 'id' di url nya jadi data dari pameter id di function nya
            url = url.replace('id', id);
            //pengambilan data dari FE ke BE dijembatani oleh jquery ajax
            $.ajax({
                // route nya pake method:: apa
                type: 'GET',
                // link route nya dari let url
                url: url,
                // data yang dihasilkan bentuk nya json
                contentType: 'json',
                // kalau proses ambil data berhasil, ambil data yang dikirim BE lewat parameter res
                success: function (res) {
                    //munculkan modal yang id nya tambah-stock
                    $('#tambah-stock').modal("show");
                    //isi value input dari hasil response BE
                    $("#name").val(res.name);
                    $("#stock").val(res.stock);
                    $("#id").val(res.id);
                }
            })
        }

        //ketika form dengan id="form-stock" button submit nya di klik
        $("#form-stock").submit(function(e) {
            // element form penanganan action nya akan di ambil alih (ditangani) oleh js
            e.preventDefault();
            // element form penanganan
            let id = $('#id').val()
            let url = "{{ route ('medicine.stock.update', 'id') }}";
            url = url.replace('id', id);

            let data = {
                stock: $("#stock").val(),
            }

            $.ajax({
                type: 'PATCH',
                url: url,
                data: data,
                cache: false,
                success: function (res) {
                    //jik aberhasil, modal di hide
                    $("#tambah-stock").modal("hide");
                    // buat session js bernama 'successUpdateStock'
                    sessionStorage.successUpdateStock = true;
                    window.location.reload();
                },
                error: function (err) {
                    //kalau terjadi error, pada element
                    $("#msg").attr("class", "alert alert-danger");
                    //
                    $("#msg").text(err.responseJSON.message);
                }
            })
        });
        //
        $(function() {
            if (sessionStorage.successUpdateStock) {
                $("#msg-success").attr("class", "alert alert-success");
                $("#msg-success").text("Berhasil mengubah data stock!");
                //hapus kembali data sesssion setelah alert succes
                sessionStorage.clear();
        }
    })
    </script>
@endPush