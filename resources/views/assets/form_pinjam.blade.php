<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Pinjam Asset</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Responsive styles */
        @media (max-width: 768px) {
            .container {
                padding-left: 15px;
                padding-right: 15px;
            }
            
            h2 {
                font-size: 1.5rem;
            }
            
            .img-fluid {
                max-height: 200px !important;
            }
            
            .card-body {
                padding: 1rem;
            }
            
            .btn {
                width: 100%;
                margin-bottom: 0.5rem;
            }
        }
    </style>
</head>

<body>
    @include('layouts.header')

    <div class="container py-4 py-md-5">
        <h2 class="mb-3 mb-md-4">Form Peminjaman Asset</h2>

        <div class="card">
            <div class="card-body">

                <!-- Foto Asset -->
                <div class="text-center mb-3 mb-md-4">
                    @if ($item->photo)
                        <img src="{{ asset('storage/' . $item->photo) }}" alt="Foto Asset" class="img-fluid rounded"
                            style="max-height: 250px; width: auto;">
                    @else
                        <div class="img-placeholder border rounded p-5 text-center text-muted">
                            <i class="fas fa-image fa-3x mb-2"></i>
                            <p class="mb-0">Tidak ada foto</p>
                        </div>
                    @endif
                </div>

                <form method="POST" action="{{ route('assets.form_pinjam', $item->id) }}" enctype="multipart/form-data" id="borrowForm">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Nama Asset</label>
                        <input type="text" class="form-control" value="{{ $item->item_name }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Stok Tersedia</label>
                        <input type="text" class="form-control" value="{{ $item->good_qty }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Ruangan</label>
                        <input type="text" class="form-control" value="{{ $item->room->name }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jumlah yang Mau Dipinjam</label>
                        <input type="number" name="jumlah" class="form-control" min="1"
                            max="{{ $item->good_qty }}" required>
                        @error('jumlah')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Foto Asset (Bukti Peminjaman)</label>
                        <input type="file" name="photo" class="form-control" accept="image/*" required>
                        <small class="text-muted">Silakan ambil foto asset yang akan dipinjam sebagai bukti peminjaman</small>
                        @error('photo')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid gap-2 d-md-flex">
                        <button type="submit" class="btn btn-success" id="submitBtn">Pinjam Sekarang</button>
                        <a href="{{ route('assets.index') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
                <script>
                    document.querySelector('form').addEventListener('keydown', function(e) {
                        if (e.key === 'Enter') {
                            e.preventDefault();
                        }
                    });

                    document.getElementById('borrowForm').addEventListener('submit', function(e) {
                        const submitBtn = document.getElementById('submitBtn');
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = `<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Memproses...`;
                    });
                </script>
            </div>
        </div>

    </div>

    @include('layouts.footer')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
