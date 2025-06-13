@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <h3 class="mb-4">Detail Aset</h3>

        <div class="row align-items-start">
            <!-- Bagian Gambar -->
            <div class="col-md-4 mb-4 d-flex justify-content-center align-items-center"
                style="height: 300px; background-color: #f8f9fa; border: 1px solid #dee2e6; border-radius: 0.25rem;">
                @if ($item->photo != null)
                    <img src="{{ asset('storage/' . $item->photo) }}" alt="{{ $item->item_name }}"
                        class="img-fluid rounded shadow-sm" style="max-height: 100%; object-fit: contain;">
                @else
                    <span class="text-muted">No Image Available</span>
                @endif
            </div>

            <!-- Bagian Informasi -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Informasi Aset</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Nama Aset:</strong> {{ $item->item_name }}</p>
                                <p><strong>Kategori:</strong> {{ $item->category->cat_name }}</p>
                                <p><strong>Ruangan:</strong> {{ $item->room->name }}</p>
                                <p><strong>Total Quantity:</strong> {{ $item->qty }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Jumlah Barang Baik:</strong> {{ $item->good_qty }}</p>
                                <p><strong>Jumlah Barang Rusak:</strong> {{ $item->broken_qty }}</p>
                                <p><strong>Jumlah Barang Hilang:</strong> {{ $item->lost_qty }}</p>
                                <p><strong>Status Peminjaman:</strong> 
                                    @if($item->is_borrowable)
                                        <span class="badge bg-success">Dapat Dipinjam</span>
                                    @else
                                        <span class="badge bg-danger">Tidak Dapat Dipinjam</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <a href="{{ url('/item') }}" class="btn btn-secondary mt-3">Kembali</a>
            </div>
        </div>
    </div>
@endsection
