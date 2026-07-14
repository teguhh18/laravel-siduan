@extends('layouts.app')
@section('content')
    <div class="app-content">
        <div class="container-fluid">
            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">Detail Pengaduan</h3>
                </div>
                <div class="card-body">
                    <p><strong>Judul:</strong> {{ $pengaduan->judul }}</p>
                    <p><strong>Deskripsi:</strong> {{ $pengaduan->deskripsi }}</p>
                    <p><strong>Kategori:</strong>
                        {{ $pengaduan->kategoriPengaduan->nama_kategori ?? 'Kategori tidak tersedia' }}</p>
                    <p><strong>Status:</strong> {{ $pengaduan->status }}</p>
                    <p><strong>Foto:</strong></p>
                    @if ($pengaduan->foto)
                        <img src="{{ asset('storage/' . $pengaduan->foto) }}" alt="Foto Pengaduan {{ $pengaduan->judul }}"
                            width="200">
                    @else
                        <p>Foto tidak tersedia</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
