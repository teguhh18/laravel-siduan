<div class="modal fade" role="dialog" id="modal_show" tabindex="-1" aria-labelledby="modal_show_label" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="{{ route('admin.complaint.update', $complaint->id) }}" method="POST" novalidate>
                @csrf
                @method('PATCH')
                <div class="modal-header">
                    <h5 class="modal-title" id="modal_show_label">{{ $title }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="row justify-content-center">
                            <div class="col-4">
                                @if ($complaint->photo_path)
                                    <img class="img-thumbnail mt-2 mb-2"
                                        src="{{ asset('storage/' . $complaint->photo_path) }}"
                                        alt="Foto Complaint {{ $complaint->title }}" width="500">
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="kategori" class="form-label">Kategori</label>
                            <input type="text" class="form-control @error('kategori') is-invalid @enderror"
                                id="kategori" name="kategori"
                                value="{{ $complaint->category->name ?? 'Kategori tidak tersedia' }}"
                                readonly disabled>
                            <x-input-error name="kategori" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="judul" class="form-label">Judul</label>
                            <input type="text" class="form-control @error('judul') is-invalid @enderror"
                                id="judul" name="judul" value="{{ $complaint->title ?? 'Judul tidak tersedia' }}"
                                readonly disabled>
                            <x-input-error name="judul" />
                        </div>
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi" name="deskripsi" rows="3"
                                required disabled>{{ $complaint->description ?? 'Deskripsi tidak tersedia' }}</textarea>
                            <x-input-error name="deskripsi" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" id="status"
                                class="form-control @error('status') is-invalid @enderror" required>
                                <option value="">Pilih Status</option>
                                <option value="baru" {{ $complaint->status === 'baru' ? 'selected' : '' }}>Baru
                                </option>
                                <option value="diproses" {{ $complaint->status === 'diproses' ? 'selected' : '' }}>Diproses
                                </option>
                                <option value="selesai" {{ $complaint->status === 'selesai' ? 'selected' : '' }}>Selesai
                                </option>
                                <option value="ditolak" {{ $complaint->status === 'ditolak' ? 'selected' : '' }}>Ditolak
                                </option>
                            </select>
                            <x-input-error name="status" />
                        </div>

                        <div class="mb-3">
                            <label for="note" class="form-label">Catatan</label>
                            <textarea class="form-control @error('note') is-invalid @enderror" id="note"
                                name="note" rows="3" required placeholder="Ex: Laporan Sedang kami proses...">{{ old('note', $complaint->note) }}</textarea>
                            <x-input-error name="note" />
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-warning">Ubah</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </form>
        </div>
    </div>
</div>
