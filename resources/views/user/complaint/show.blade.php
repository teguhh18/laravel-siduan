@extends('layouts.app')
@section('content')
    <div class="app-content">
        <div class="container-fluid">
            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">{{ $title }}</h3>
                </div>
                <div class="card-body">
                    <p><strong>Title:</strong> {{ $complaint->title }}</p>
                    <p><strong>Deskripsi:</strong> {{ $complaint->description }}</p>
                    <p><strong>Kategori:</strong>
                        {{ $complaint->category->name ?? 'Kategori tidak tersedia' }}</p>
                    <p><strong>Lokasi:</strong> {{ $complaint->location }}</p>
                    <p><strong>Status:</strong> {{ $complaint->status }}</p>
                    <p><strong>Foto:</strong></p>
                    @if ($complaint->photo_path)
                        <img src="{{ asset('storage/' . $complaint->photo_path) }}"
                            alt="Foto Complaint {{ $complaint->judul }}" width="200">
                    @else
                        <p>Foto tidak tersedia</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
