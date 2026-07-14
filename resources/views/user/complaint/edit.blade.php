@extends('layouts.app')
@section('content')
    <div class="app-content">
        <div class="container-fluid">
            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">{{ $title }}</h3>
                </div>

                <div class="card-body">
                    <form action="{{ route('user.complaint.update', $complaint->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="category_id" class="form-label">Kategori</label>
                                <select class="form-control @error('category_id') is-invalid @enderror" id="category_id"
                                    name="category_id" required>
                                    <option value="">Pilih Kategori</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ old('category_id') == $category->id || $complaint->category_id == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error name="category_id" />
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="title" class="form-label">Judul</label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror"
                                    id="title" name="title" value="{{ old('title') ?? $complaint->title }}" required>
                                <x-input-error name="title" />
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Deskripsi</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                    rows="3" required>{{ old('description') ?? $complaint->description }}</textarea>
                                <x-input-error name="description" />
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="location" class="form-label">Lokasi</label>
                                <input type="text" class="form-control @error('location') is-invalid @enderror"
                                    id="location" name="location" value="{{ old('location') ?? $complaint->location }}" required>
                                <x-input-error name="location" />
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="photo_path" class="form-label">Foto</label>
                                <input type="file" class="form-control @error('photo_path') is-invalid @enderror"
                                    id="photo_path" name="photo_path" accept="image/*">
                                <x-input-error name="photo_path" />
                                @if ($complaint->photo_path)
                                    <img class="img-thumbnail mt-2" src="{{ asset('storage/' . $complaint->photo_path) }}"
                                        alt="Foto Complaint {{ $complaint->title }}" width="200">
                                @endif
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
    <!--end::App Content-->
@endsection
