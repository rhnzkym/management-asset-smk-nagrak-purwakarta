@extends('layouts.app')

@section('content')
    <!-- Page Heading -->
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4">
        <h1 class="h3 mb-3 mb-md-0 text-gray-800">Daftar Peminjaman Item</h1>
        <div class="d-flex flex-wrap" style="gap: 0.5rem;">
            {{-- Hidden Daftar Peminjaman Ruangan button
            <a href="/borrow-room" class="btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-house fa-sm text-white-50"></i> Daftar Peminjaman Ruangan
            </a>
            --}}
            <a href="{{ route('borrow.export.pdf', request()->query()) }}" class="btn btn-sm btn-danger shadow-sm w-100 w-md-auto">
                <i class="fas fa-file-pdf fa-sm text-white-50"></i> Ekspor PDF
            </a>
            <a href="{{ route('borrow.export.excel', request()->query()) }}" class="btn btn-sm btn-success shadow-sm w-100 w-md-auto">
                <i class="fas fa-file-excel fa-sm text-white-50"></i> Ekspor Excel
            </a>
        </div>
    </div>

    {{-- Filter Section --}}
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter Data</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="">
                <div class="row g-3">
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="form-group mb-3">
                            <label class="form-label fw-bold">Status</label>
                            <select class="form-select" id="status" name="status" onchange="this.form.submit()">
                                <option value="" {{ request('status') == '' ? 'selected' : '' }}>Semua Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="pinjam" {{ request('status') == 'pinjam' ? 'selected' : '' }}>Pinjam</option>
                                <option value="kembali" {{ request('status') == 'kembali' ? 'selected' : '' }}>Kembali</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="form-group mb-3">
                            <label class="form-label fw-bold">Urutkan</label>
                            <select class="form-select" id="sort" name="sort" onchange="this.form.submit()">
                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Terlama</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="form-group mb-3">
                            <label class="form-label fw-bold">Dari Tanggal</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" 
                                value="{{ request('start_date') }}" onchange="this.form.submit()">
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="form-group mb-3">
                            <label class="form-label fw-bold">Sampai Tanggal</label>
                            <input type="date" class="form-control" id="end_date" name="end_date" 
                                value="{{ request('end_date') }}" onchange="this.form.submit()">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Table --}}
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Peminjaman Item</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive small">
                <table class="table table-bordered text-center text-dark table-responsive-stack" id="loanTable">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Pengguna</th>
                            <th>Item</th>
                            <th>Jumlah</th>
                            <th>Status</th>
                            <th>Dari</th>
                            <th>Tanggal Pinjam</th>
                            <th>Tanggal Kembali</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($loans as $index => $loan)
                            <tr>
                                <td>{{ $loans->firstItem() + $index }}</td>
                                <td>{{ $loan->user->nama ?? '-' }}</td>
                                <td>{{ $loan->item->item_name ?? '-' }}</td>
                                <td>{{ $loan->jumlah }}</td>
                                <td>
                                    @if ($loan->status == 'pending')
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @elseif ($loan->status == 'pinjam')
                                        <span class="badge bg-danger text-white">Pinjam</span>
                                    @elseif ($loan->status == 'kembali')
                                        <span class="badge bg-success text-white">Kembali</span>
                                    @elseif ($loan->status == 'hilang')
                                        <span class="badge bg-secondary text-white">Hilang</span>
                                    @elseif ($loan->status == 'ditolak')
                                        <span class="badge bg-secondary text-white">Ditolak</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($loan->room_loan_id)
                                        <span>{{ $loan->room->room->name }}</span>
                                    @else
                                        <span>Manual</span>
                                    @endif
                                </td>
                                <td>{{ $loan->tanggal_pinjam }}</td>
                                <td>{{ $loan->tanggal_kembali ?? '-' }}</td>
                                <td>
                                    <div class="d-flex justify-content-center">
                                        <a href="{{ route('borrow.show', $loan->id) }}" class="btn btn-sm btn-info mx-1">
                                            <i class="fas fa-eye"></i> Detail
                                        </a>
                                        @if ($loan->status == 'pinjam')
                                            <button type="button" class="btn btn-sm btn-info mx-1" data-bs-toggle="modal" data-bs-target="#confReturn-{{ $loan->id }}">
                                                <i class="fas fa-undo"></i> Kembalikan
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @if ($loan->status == 'pinjam')
                                <div class="modal fade" id="confReturn-{{ $loan->id }}" tabindex="-1" aria-labelledby="confReturnLabel-{{ $loan->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <form action="{{ route('item_loans.return', $loan->id) }}" method="POST" enctype="multipart/form-data" onsubmit="return validateReturnForm(this, {{ $loan->jumlah }});">
                                            @csrf
                                            @method('PATCH')
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="confReturnLabel-{{ $loan->id }}">Kembalikan Item</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">Jumlah Barang yang Dikembalikan dalam Kondisi Baik</label>
                                                        <input type="number" name="good" class="form-control" min="0" max="{{ $loan->jumlah }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Jumlah Barang yang Dikembalikan dalam Kondisi Rusak</label>
                                                        <input type="number" name="broken" class="form-control" min="0" max="{{ $loan->jumlah }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Jumlah Barang yang Hilang</label>
                                                        <input type="number" name="lost" class="form-control" min="0" max="{{ $loan->jumlah }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Foto Bukti Pengembalian</label>
                                                        <input type="file" name="photo" class="form-control" accept="image/*" required>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-primary">Konfirmasi Pengembalian</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-3">Tidak ada data peminjaman item</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{-- Pagination --}}
                <div class="mt-3 d-flex justify-content-center">
                    {{ $loans->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>

    <script>
    function validateReturnForm(form, expectedTotal) {
        var good = parseInt(form.good.value) || 0;
        var broken = parseInt(form.broken.value) || 0;
        var lost = parseInt(form.lost.value) || 0;
        var total = good + broken + lost;
        var errorMessage = document.getElementById('error-message');
        if (total !== expectedTotal) {
            errorMessage.textContent = 'Total jumlah kondisi barang harus sama dengan jumlah yang dipinjam (' + expectedTotal + ').';
            errorMessage.style.display = 'block';
            return false;
        }
        errorMessage.style.display = 'none';
        return true;
    }
    </script>
@endsection
