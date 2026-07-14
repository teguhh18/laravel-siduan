<div class="modal fade" role="dialog" id="modal_show" tabindex="-1" aria-labelledby="modal_show_label" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="{{ route('admin.pengaduan.update', $pengaduan->id) }}" method="POST" novalidate>
                @csrf
                @method('PATCH')
                <div class="modal-header">
                    <h5 class="modal-title" id="modal_show_label">Edit Pengaduan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="row justify-content-center">
                            <div class="col-4">
                                @if ($pengaduan->foto)
                                    <img class="img-thumbnail mt-2 mb-2"
                                        src="{{ asset('storage/' . $pengaduan->foto) }}"
                                        alt="Foto Pengaduan {{ $pengaduan->judul }}" width="500">
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="kategori" class="form-label">Kategori</label>
                            <input type="text" class="form-control @error('kategori') is-invalid @enderror"
                                id="kategori" name="kategori"
                                value="{{ $pengaduan->kategoriPengaduan->nama_kategori ?? 'Kategori tidak tersedia' }}"
                                readonly disabled>
                            <x-input-error name="kategori" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="judul" class="form-label">Judul</label>
                            <input type="text" class="form-control @error('judul') is-invalid @enderror"
                                id="judul" name="judul" value="{{ $pengaduan->judul ?? 'Judul tidak tersedia' }}"
                                readonly disabled>
                            <x-input-error name="judul" />
                        </div>
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi" name="deskripsi" rows="3"
                                required disabled>{{ $pengaduan->deskripsi ?? 'Deskripsi tidak tersedia' }}</textarea>
                            <x-input-error name="deskripsi" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" id="status"
                                class="form-control @error('status') is-invalid @enderror" required>
                                <option value="">Pilih Status</option>
                                <option value="pending" {{ $pengaduan->status === 'pending' ? 'selected' : '' }}>Pending
                                </option>
                                <option value="proses" {{ $pengaduan->status === 'proses' ? 'selected' : '' }}>Proses
                                </option>
                                <option value="selesai" {{ $pengaduan->status === 'selesai' ? 'selected' : '' }}>Selesai
                                </option>
                            </select>
                            <x-input-error name="status" />
                        </div>

                        <div class="mb-3">
                            <label for="pesan_tanggapan" class="form-label">Pesan Tanggapan</label>
                            <textarea class="form-control @error('pesan_tanggapan') is-invalid @enderror" id="pesan_tanggapan"
                                name="pesan_tanggapan" rows="3" required placeholder="Ex: Laporan Sedang kami proses...">{{ old('pesan_tanggapan', $pengaduan->pesan_tanggapan) }}</textarea>
                            <x-input-error name="pesan_tanggapan" />
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
