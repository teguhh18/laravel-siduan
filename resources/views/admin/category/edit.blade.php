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

                <div class="card-body">
                    <form action="{{ route('admin.category.update', $category->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3 @error('name') has-error @enderror">
                            <label for="name" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="name" name="name"
                                value="{{ old('name', $category->name) }}" required>
                            <x-input-error name="name" />

                        </div>
                        <div class="mb-3 @error('description') has-error @enderror">
                            <label for="description" class="form-label">Deskripsi</label>
                            <textarea id="description" name="description" rows="5" cols="40">{{ old('description', $category->description) }}</textarea>
                            <x-input-error name="description" />
                        </div>

                        <div class="col-md-6 mt-2  @error('is_active') has-error @enderror">
                            <label class="form-label">Is Active</label>
                            <input type="hidden" name="is_active" value="0">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" id="is_active"
                                    value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                                <x-input-error name="is_active" />
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
    <!--end::Container-->
    </div>
    <!--end::App Content-->
@endsection
