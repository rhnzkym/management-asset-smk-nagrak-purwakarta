@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h3 class="mb-4">Konfirmasi Pengembalian Barang</h3>

    <form action="{{ route('assets.borrow.return.confirm.submit', $loan->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="good_qty" class="form-label">Jumlah Barang Baik</label>
            <input type="number" class="form-control @error('good_qty') is-invalid @enderror" id="good_qty" name="good_qty" value="{{ old('good_qty') }}" min="0" required>
            @error('good_qty')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="broken_qty" class="form-label">Jumlah Barang Rusak</label>
            <input type="number" class="form-control @error('broken_qty') is-invalid @enderror" id="broken_qty" name="broken_qty" value="{{ old('broken_qty') }}" min="0" required>
            @error('broken_qty')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="lost_qty" class="form-label">Jumlah Barang Hilang</label>
            <input type="number" class="form-control @error('lost_qty') is-invalid @enderror" id="lost_qty" name="lost_qty" value="{{ old('lost_qty') }}" min="0" required>
            @error('lost_qty')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="return_photo" class="form-label">Upload Foto Bukti Pengembalian (Opsional)</label>
            <input type="file" class="form-control @error('return_photo') is-invalid @enderror" id="return_photo" name="return_photo" accept="image/*">
            <small class="text-muted">Foto bukti pengembalian tidak wajib diisi</small>
            @error('return_photo')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Konfirmasi Pengembalian</button>
        <a href="{{ route('assets.borrow.show', $loan->id) }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection
