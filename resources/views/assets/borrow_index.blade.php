<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Riwayat Peminjaman</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Responsive styles */
        @media (max-width: 768px) {
            .container {
                padding-left: 15px;
                padding-right: 15px;
            }
            
            /* Responsive table for mobile */
            .table-responsive-mobile {
                width: 100%;
                margin-bottom: 1rem;
                overflow-x: auto;
            }
            
            .table-responsive-mobile table {
                width: 100%;
            }
            
            /* Stack table on mobile */
            .table-stack-mobile tr {
                display: flex;
                flex-direction: column;
                border: 1px solid #dee2e6;
                margin-bottom: 1rem;
                border-radius: 0.25rem;
            }
            
            .table-stack-mobile td {
                border: none;
                border-bottom: 1px solid #dee2e6;
                position: relative;
                padding-left: 40% !important;
                text-align: left;
                display: flex;
                align-items: center;
                min-height: 45px;
            }
            
            .table-stack-mobile td:last-child {
                border-bottom: none;
            }
            
            .table-stack-mobile td:before {
                content: attr(data-label);
                position: absolute;
                left: 0.75rem;
                width: 35%;
                font-weight: bold;
            }
            
            .table-stack-mobile thead {
                display: none;
            }
            
            /* Fix button on mobile */
            .btn-mobile-block {
                width: 100%;
                display: block;
            }
        }
    </style>
</head>

<body>
    <div class="wrapper d-flex flex-column min-vh-100">
        @include('layouts.header')

        <div class="container py-5 content flex-grow-1">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
                <h2 class="mb-3 mb-md-0">Riwayat Peminjaman Item</h2>
                {{-- Hidden Ruangan button
                <a href="/rooms/borrow" class="btn btn-outline-secondary">
                    <i class="fas fa-door-open me-1"></i> Ruangan
                </a>
                --}}
            </div>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="table-responsive-mobile">
                <table class="table table-bordered text-center table-stack-mobile">
                    <thead>
                        <tr>
                            <th>Nama Asset</th>
                            <th>Jumlah Dipinjam</th>
                            <th>Tanggal Pinjam</th>
                            <th>Tanggal Kembali</th>
                            <th>Status</th>
                            <th>Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse ($itemLoans as $loan)
                        <tr>
                            <td data-label="Nama Asset">{{ $loan->item->item_name ?? '-' }}</td>
                            <td data-label="Jumlah Dipinjam">{{ $loan->jumlah }}</td>
                            <td data-label="Tanggal Pinjam">{{ $loan->tanggal_pinjam }}</td>
                            <td data-label="Tanggal Kembali">
                                {{ $loan->tanggal_kembali ?? '-' }}
                            </td>
                            <td data-label="Status">
                                @if ($loan->status == 'pinjam')
                                    <span class="badge bg-primary">Pinjam</span>
                                @elseif($loan->status == 'pending')
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @elseif($loan->status == 'kembali')
                                    <span class="badge bg-success">Kembali</span>
                                @elseif($loan->status == 'ditolak')
                                    <span class="badge bg-danger">Ditolak</span>
                                @endif
                            </td>
                            <td data-label="Detail">
                                <a href="{{ route('assets.borrow.show', $loan->id) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada data peminjaman.</td>
                        </tr>
                    @endforelse
                </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center">
                {{ $itemLoans->links() }}
            </div>
        </div>

        @include('layouts.footer')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add script for responsive functionality if needed
        });
    </script>
</body>

</html>
