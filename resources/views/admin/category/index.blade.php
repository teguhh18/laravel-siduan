@extends('layouts.app')
@section('content')
    <!--begin::App Content-->
    <div class="app-content">
        <!--begin::Container-->
        <div class="container-fluid">
            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">Kategori</h3>
                </div>
                @if (session()->has('message'))
                    <div class="alert alert-{{ session('type-alert') }} alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>

                        <div>{{ session('message') }}</div>
                    </div>
                @endif
                <div class="card-body">
                    <div class="d-flex gap-2 mb-3">
                        <a href="{{ route('admin.category.create') }}" class="btn btn-primary">Tambah Kategori</a>
                    </div>
                    <table class="table table-bordered" id="datatables">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Deskripsi</th>
                                <th>Aktif</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categories as $category)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $category->name }}</td>
                                    <td>{{ $category->description }}</td>
                                    {{-- <td>{{ $category->is_active }}</td> --}}
                                    <td>
                                        @if ($category->is_active)
                                            <p>Aktif</p>
                                        @else
                                            <p>Tidak Aktif</p>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.category.edit', $category->id) }}"
                                            class="btn btn-sm btn-warning"><i class="bi bi-pencil-square"></i></a>

                                        <a href="javascript:;" data-url="{{ route('admin.category.show', $category->id) }}"
                                            class="btn btn-danger btn-sm btn-delete">
                                            <i class="bi bi-trash-fill"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
    <!--end::Container-->
    </div>
    <!--end::App Content-->
    <div id="tempat-modal"></div>
@endsection

@push('js')
    <script>
        setTimeout(function() {
            document.getElementById('respon').innerHTML = '';
        }, 3000);
        $(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $(document).on("click", ".btn-delete", function() {
                var url = $(this).attr("data-url");
                $.ajax({
                        method: "GET",
                        url: url,
                    })
                    .done(function(data) {
                        $('#tempat-modal').html(data.html);
                        $('#modal_show').modal('show');
                    })
            })
        });
    </script>
@endpush
