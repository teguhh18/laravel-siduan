@extends('layouts.app')
@section('content')
    <!--begin::App Content-->
    <div class="app-content">
        <!--begin::Container-->
        <div class="container-fluid">
            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">{{ $title }}</h3>
                </div>

                @if (session()->has('message'))
                    <div class="alert alert-{{ session('type-alert') }} alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>

                        <div>{{ session('message') }}</div>
                    </div>
                @endif

                <div class="card-body">
                    <table class="table table-bordered" id="datatables">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>User</th>
                                <th>Kategori</th>
                                <th>Judul</th>
                                <th>Deskripsi</th>
                                <th>Lokasi</th>
                                <th>Latitude</th>
                                <th>Longitude</th>
                                <th>Foto</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($complaints as $complaint)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $complaint->user->name ?? 'User tidak tersedia' }}</td>
                                    <td>{{ $complaint->category->name ?? 'Kategori tidak tersedia' }}</td>
                                    <td>{{ $complaint->title }}</td>
                                    <td>{{ $complaint->deskripsi }}</td>
                                    <td>{{ $complaint->location }}</td>
                                    <td>{{ $complaint->latitude }}</td>
                                    <td>{{ $complaint->longitude }}</td>
                                    <td>
                                        @if ($item->photo_path)
                                            <img src="{{ asset('storage/' . $complaint->foto) }}" alt="Foto Pengaduan"
                                                width="100">
                                        @else
                                            <p>Foto tidak tersedia</p>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="javascript:;"
                                            data-url="{{ route('admin.complaint.edit', $complaint->id) }}"
                                            class="btn btn-sm btn-warning btn-edit"><i class="bi bi-pencil-square"></i></a>

                                        <a href="{{ route('admin.complaint.show', $complaint->id) }}"
                                            class="btn btn-info btn-sm">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        {{-- <a href="javascript:;"
                                            data-url="{{ route('admin.complaint.show', $complaint->id) }}"
                                            class="btn btn-danger btn-sm btn-delete">
                                            <i class="bi bi-trash-fill"></i>
                                        </a> --}}
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

            $(document).on("click", ".btn-edit", function() {
                var url = $(this).attr("data-url");
                console.log(url);
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
