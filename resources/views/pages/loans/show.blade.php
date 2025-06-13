@extends('layouts.app')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detail Peminjaman</h1>
        <a href="{{ route('item_loans.index') }}" class="btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Peminjaman</h6>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Status:</strong>
                        </div>
                        <div class="col-md-8">
                            @if ($loan->status == 'pending')
                                <span class="badge bg-warning">Pending</span>
                            @elseif ($loan->status == 'pinjam')
                                <span class="badge bg-info">Pinjam</span>
                            @elseif ($loan->status == 'kembali')
                                <span class="badge bg-success">Kembali</span>
                            @elseif ($loan->status == 'ditolak')
                                <span class="badge bg-danger">Ditolak</span>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Tanggal Pinjam:</strong>
                        </div>
                        <div class="col-md-8">
                            {{ \Carbon\Carbon::parse($loan->tanggal_pinjam)->format('d M Y, H:i') }}
                        </div>
                    </div>

                    @if($loan->tanggal_kembali)
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Tanggal Kembali:</strong>
                        </div>
                        <div class="col-md-8">
                            {{ \Carbon\Carbon::parse($loan->tanggal_kembali)->format('d M Y, H:i') }}
                        </div>
                    </div>
                    @endif

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Jumlah Dipinjam:</strong>
                        </div>
                        <div class="col-md-8">
                            {{ $loan->jumlah }} item
                        </div>
                    </div>
                    
                    @if($loan->status == 'kembali')
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Kondisi Pengembalian:</strong>
                        </div>
                        <div class="col-md-8">
                            <div><span class="badge bg-success">Baik:</span> {{ $loan->good ?? 0 }} item</div>
                            <div><span class="badge bg-warning">Rusak:</span> {{ $loan->broken ?? 0 }} item</div>
                            <div><span class="badge bg-danger">Hilang:</span> {{ $loan->lost ?? 0 }} item</div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Peminjam</h6>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Nama:</strong>
                        </div>
                        <div class="col-md-8">
                            {{ $loan->user->nama }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Username:</strong>
                        </div>
                        <div class="col-md-8">
                            {{ $loan->user->username }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Jurusan:</strong>
                        </div>
                        <div class="col-md-8">
                            {{ $loan->user->jurusan ?? '-' }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Email:</strong>
                        </div>
                        <div class="col-md-8">
                            {{ $loan->user->email }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Nomor Telepon:</strong>
                        </div>
                        <div class="col-md-8">
                            {{ $loan->user->nomor_telpon ?? '-' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Asset</h6>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Nama Asset:</strong>
                        </div>
                        <div class="col-md-8">
                            {{ $loan->item->item_name }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Kategori:</strong>
                        </div>
                        <div class="col-md-8">
                            {{ $loan->item->category->cat_name }}
                        </div>
                    </div>



                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Ruangan:</strong>
                        </div>
                        <div class="col-md-8">
                            {{ $loan->item->room->name ?? '-' }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Foto Bukti Peminjaman</h6>
                </div>
                <div class="card-body text-center">
                    @if($loan->photo)
                        <img src="{{ asset('storage/' . $loan->photo) }}" alt="Bukti Peminjaman" class="img-fluid">
                    @else
                        <p class="text-muted">Tidak ada foto</p>
                    @endif
                </div>
            </div>
            
            @if($loan->status == 'kembali')
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Foto Bukti Pengembalian</h6>
                </div>
                <div class="card-body text-center">
                    @if($loan->return_photo)
                        <img src="{{ asset('storage/' . $loan->return_photo) }}" alt="Bukti Pengembalian" class="img-fluid">
                    @else
                        <p class="text-muted">Tidak ada foto bukti pengembalian</p>
                    @endif
                </div>
            </div>
            @endif



            @if($loan->status == 'pending')
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Aksi</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <form method="POST" action="{{ route('assets.loan.approve', $loan->id) }}">
                            @csrf
                            <button type="submit" class="btn btn-success btn-block" onclick="return confirm('Yakin ingin menyetujui peminjaman ini?')">
                                <i class="fas fa-check"></i> Setujui
                            </button>
                        </form>
                        <form method="POST" action="{{ route('assets.loan.reject', $loan->id) }}">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-block" onclick="return confirm('Yakin ingin menolak peminjaman ini?')">
                                <i class="fas fa-times"></i> Tolak
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
@endsection 