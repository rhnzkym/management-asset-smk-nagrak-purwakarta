@extends('layouts.app')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">List Room Loans</h1>
        <div class="d-flex" style="gap: 0.5rem;">
            <a href="/borrow" class="btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-box fa-sm text-white-50"></i> List Item Loans
            </a>

            <a href="/borrow-room/export-pdf" class="btn btn-sm btn-danger shadow-sm">
                <i class="fas fa-file-pdf fa-sm text-white-50"></i> Export PDF
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
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label fw-bold">Status</label>
                            <select class="form-select" id="status" name="status" onchange="this.form.submit()">
                                <option value="" {{ request('status') == '' ? 'selected' : '' }}>Semua Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="pinjam" {{ request('status') == 'pinjam' ? 'selected' : '' }}>Pinjam</option>
                                <option value="kembali" {{ request('status') == 'kembali' ? 'selected' : '' }}>Kembali</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label fw-bold">Urutkan</label>
                            <select class="form-select" id="sort" name="sort" onchange="this.form.submit()">
                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Terlama</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label fw-bold">Dari Tanggal</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" 
                                value="{{ request('start_date') }}" onchange="this.form.submit()">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
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
            <h6 class="m-0 font-weight-bold text-primary">Daftar Peminjaman Ruangan</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered text-center text-dark">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>User</th>
                            <th>Room</th>
                            <th>Status</th>
                            <th>Borrow Date</th>
                            <th>Return Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($room_loans as $index => $loan)
                            <tr>
                                <td>{{ $room_loans->firstItem() + $index }}</td>
                                <td>{{ $loan->user->nama ?? '-' }}</td>
                                <td>{{ $loan->room->name ?? '-' }}</td>
                                <td>
                                    @if ($loan->status == 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @elseif ($loan->status == 'pinjam')
                                        <span class="badge bg-info">Pinjam</span>
                                    @elseif ($loan->status == 'kembali')
                                        <span class="badge bg-success">Kembali</span>
                                    @elseif ($loan->status == 'hilang')
                                        <span class="badge bg-danger">Hilang</span>
                                    @endif
                                </td>
                                <td>{{ $loan->tanggal_pinjam }}</td>
                                <td>{{ $loan->tanggal_kembali ?? '-' }}</td>
                                <td>
                                    <div class="d-flex justify-content-center">
                                        <a href="{{ url('/borrow-room/' . $loan->id) }}"
                                            class="btn btn-sm btn-info mx-1">
                                            <i class="fas fa-eye"></i> Detail
                                        </a>
                                        @if ($loan->status == 'pending')
                                            <button type="button" class="btn btn-sm btn-success mx-1"
                                                data-bs-toggle="modal">
                                                <i class="fas fa-check"></i> Setujui
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-3">Tidak ada data peminjaman ruangan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{-- Pagination --}}
                <div class="mt-3 d-flex justify-content-center">
                    {{ $room_loans->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
@endsection
