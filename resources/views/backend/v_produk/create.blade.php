@extends('backend.v_layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <form class="form-horizontal" action="{{ route('admin.produk.store') }}" method="post"
                    enctype="multipart/form-data">
                    @csrf

                    <div class="card-body">
                        <h4 class="card-title mb-4">{{ $judul ?? 'Tambah Produk' }}</h4>

                        <div class="row">

                            {{-- KOLOM KIRI: UPLOAD FOTO --}}
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label class="form-label">Foto Produk</label>
                                    <img class="img-preview img-fluid mb-3 col-sm-5 d-block"
                                        style="max-height: 200px; border-radius: 8px;">
                                    <input type="file" name="foto"
                                        class="form-control @error('foto') is-invalid @enderror" id="image"
                                        onchange="previewImage()">
                                    @error('foto')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- KOLOM KANAN: DATA PRODUK --}}
                            <div class="col-md-8">

                                {{-- Nama Produk --}}
                                <div class="form-group mb-3">
                                    <label class="form-label">Nama Produk</label>
                                    <input type="text" name="nama_produk" value="{{ old('nama_produk') }}"
                                        class="form-control @error('nama_produk') is-invalid @enderror"
                                        placeholder="Masukkan Nama Produk">
                                    @error('nama_produk')
                                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Deskripsi (DENGAN CKEDITOR) --}}
                                <div class="form-group mb-3">
                                    <label class="form-label">Deskripsi</label>
                                    <textarea name="deskripsi" id="ckeditor" class="form-control @error('deskripsi') is-invalid @enderror">{{ old('deskripsi') }}</textarea>
                                    @error('deskripsi')
                                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Harga --}}
                                <div class="form-group mb-3">
                                    <label class="form-label">Harga (Rp)</label>
                                    <input type="text" onkeypress="return hanyaAngka(event)" name="harga"
                                        value="{{ old('harga') }}"
                                        class="form-control @error('harga') is-invalid @enderror"
                                        placeholder="Contoh: 50000">
                                    @error('harga')
                                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Stok --}}
                                <div class="form-group mb-3">
                                    <label class="form-label">Stok</label>
                                    <input type="text" onkeypress="return hanyaAngka(event)" name="stok"
                                        value="{{ old('stok') }}" class="form-control @error('stok') is-invalid @enderror"
                                        placeholder="Contoh: 10">
                                    @error('stok')
                                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                    @enderror
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="card-footer border-top">
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-device-floppy"></i> Simpan
                        </button>
                        <a href="{{ route('admin.produk.index') }}" class="btn btn-secondary">
                            <i class="ti ti-arrow-left"></i> Kembali
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>

    {{-- SCRIPT JAVASCRIPT --}}
    <script src="{{ asset('ckeditor/ckeditor.js') }}"></script>

    <script>
        // 2. Aktifkan CKEditor pada element dengan ID "ckeditor"
        ClassicEditor
            .create(document.querySelector('#ckeditor'))
            .catch(error => {
                console.error(error);
            });

        // 3. Fungsi Preview Gambar
        function previewImage() {
            const image = document.querySelector('#image');
            const imgPreview = document.querySelector('.img-preview');
            imgPreview.style.display = 'block';
            const oFReader = new FileReader();
            oFReader.readAsDataURL(image.files[0]);
            oFReader.onload = function(oFREvent) {
                imgPreview.src = oFREvent.target.result;
            }
        }

        // 4. Fungsi Hanya Angka
        function hanyaAngka(evt) {
            var charCode = (evt.which) ? evt.which : event.keyCode
            if (charCode > 31 && (charCode < 48 || charCode > 57))
                return false;
            return true;
        }
    </script>
@endsection
