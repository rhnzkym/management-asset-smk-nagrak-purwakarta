<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Detail Peminjaman</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Responsive styles */
        @media (max-width: 768px) {
            .container {
                padding-left: 15px;
                padding-right: 15px;
            }
            
            .card-header h6 {
                font-size: 1rem;
            }
            
            .card {
                margin-bottom: 1.5rem;
            }
            
            .row.mb-3 {
                margin-bottom: 0.75rem !important;
            }
            
            /* Make image cards responsive */
            .img-fluid {
                max-width: 100%;
                height: auto;
            }
            
            /* Better button spacing */
            .btn-outline-secondary {
                padding: 0.375rem 0.75rem;
                font-size: 0.875rem;
            }
        }
    </style>
</head>

<body>
    @php
        use Illuminate\Support\Facades\Storage;
    @endphp

    <div class="wrapper d-flex flex-column min-vh-100">
        @include('layouts.header')

        <div class="container py-4 py-md-5 content flex-grow-1">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3 gap-md-0">
                <h2 class="mb-0 fs-4 fs-md-2">Detail Peminjaman</h2>
                <a href="{{ route('assets.borrow.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>

            <div class="row g-3">
                <div class="col-12 col-lg-8">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Informasi Peminjaman</h6>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-5 col-md-4">
                                    <strong>Status:</strong>
                                </div>
                                <div class="col-7 col-md-8">
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
                                    <div class="mb-1"><span class="badge bg-success">Baik:</span> {{ $loan->good ?? 0 }} item</div>
                                    <div class="mb-1"><span class="badge bg-warning">Rusak:</span> {{ $loan->broken ?? 0 }} item</div>
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

                <div class="col-12 col-lg-4">
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
                                    <strong>Total Item:</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $loan->item->qty }} item
                                </div>
                            </div>

                            <div class="card border-left-primary mb-3">
                                <div class="card-header py-2 bg-light">
                                    <h6 class="m-0 font-weight-bold text-primary">Kondisi Asset Saat Ini</h6>
                                </div>
                                <div class="card-body p-3">
                                    <div class="row mb-2 g-2">
                                        <div class="col-6">
                                            <span class="badge bg-success me-1">Baik:</span> {{ $loan->item->good_qty }} item
                                        </div>
                                        <div class="col-6">
                                            <span class="badge bg-warning me-1">Rusak:</span> {{ $loan->item->broken_qty }} item
                                        </div>
                                    </div>
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <span class="badge bg-danger me-1">Hilang:</span> {{ $loan->item->lost_qty }} item
                                        </div>
                                        <div class="col-6">
                                            <span class="badge bg-info me-1">Tersedia:</span> {{ $loan->item->good_qty }} item
                                        </div>
                                    </div>
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
                                <img src="/storage/{{ $loan->photo }}" alt="Bukti Peminjaman" class="img-fluid" style="max-width: 100%; height: auto;">
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
                                <img src="/storage/{{ $loan->return_photo }}" alt="Bukti Pengembalian" class="img-fluid" style="max-width: 100%; height: auto;">
                            @else
                                <p class="text-muted">Tidak ada foto bukti pengembalian</p>
                            @endif
                        </div>
                    </div>
                    @endif


                </div>
            </div>
        </div>

        @include('layouts.footer')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add any additional scripts if needed for mobile functionality
        });
    </script>
</body>

</html> 