<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Data Aset - Peminjaman Aset Sekolah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        html,
        body {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .content-wrapper {
            flex: 1 0 auto;
            padding-top: 20px;
            padding-bottom: 40px;
        }

        .search-bar {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
        }

        .category-buttons {
            margin-bottom: 30px;
        }

        .card-asset {
            transition: transform 0.2s;
        }

        .card-asset:hover {
            transform: scale(1.02);
        }

        .card-img-top {
            height: 180px;
            object-fit: cover;
            background-color: #f8f9fa;
        }

        .img-placeholder {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 180px;
            background-color: #e9ecef;
            color: #6c757d;
        }

        .img-placeholder i {
            font-size: 3rem;
        }
        
        /* Filter toggle styles */
        .filter-toggle {
            display: none;
            margin-bottom: 15px;
        }
        
        .filter-toggle-btn {
            width: 100%;
            text-align: left;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .filter-toggle-btn:after {
            content: '\f107';
            font-family: 'Font Awesome 5 Free';
            font-weight: 900;
            transition: transform 0.3s;
        }
        
        .filter-toggle-btn[aria-expanded="true"]:after {
            transform: rotate(180deg);
        }
        
        /* Responsive styles */
        @media (max-width: 768px) {
            .filter-toggle {
                display: block;
            }
            
            .search-form {
                margin-top: 15px;
            }
            
            .card-body {
                padding: 0.75rem;
            }
            
            .card-title {
                font-size: 1.1rem;
            }
            
            .card-text {
                font-size: 0.9rem;
            }
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    @include('layouts.header')

    <!-- Content Wrapper -->
    <div class="content-wrapper container">

        <!-- Search Bar -->
        <div class="search-bar shadow-sm">
            <!-- Mobile Filter Toggle Button -->
            <div class="filter-toggle">
                <button class="btn btn-light filter-toggle-btn" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse" aria-expanded="false" aria-controls="filterCollapse">
                    <span><i class="fas fa-filter me-2"></i> Filter Pencarian</span>
                </button>
            </div>
            
            <!-- Basic Search (Always Visible) -->
            <form class="row g-3 align-items-end" method="GET" action="{{ route('assets.index') }}" id="assetSearchForm">
                <div class="col-12">
                    <div class="input-group">
                        <input type="text" name="search" id="search" class="form-control"
                            placeholder="Cari Nama, Kategori, atau Ruangan" value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i><span class="d-none d-md-inline ms-1">Cari</span>
                        </button>
                        <a href="{{ route('assets.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i><span class="d-none d-md-inline ms-1">Reset</span>
                        </a>
                    </div>
                </div>
                
                <!-- Collapsible Advanced Filters -->
                <div class="col-12 collapse search-form" id="filterCollapse">
                    <div class="card card-body mt-2 mb-2 border-0 p-0">
                        <div class="row g-3">
                            {{-- Kolom Kategori --}}
                            <div class="col-md-6">
                                <label for="kategori_id" class="form-label">Kategori</label>
                                <select class="form-select" name="kategori_id" id="kategori_id">
                                    <option value="">Pilih Kategori</option>
                                    @foreach ($kategori as $k)
                                        <option value="{{ $k->id }}"
                                            {{ request('kategori_id') == $k->id ? 'selected' : '' }}>
                                            {{ $k->cat_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Kolom Ruangan --}}
                            <div class="col-md-6">
                                <label for="room_id" class="form-label">Ruangan</label>
                                <select class="form-select" name="room_id" id="room_id">
                                    <option value="">Pilih Ruangan</option>
                                    @foreach ($rooms as $room)
                                        <option value="{{ $room->id }}" {{ request('room_id') == $room->id ? 'selected' : '' }}>
                                            {{ $room->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-12 d-md-none mt-3">
                                <button type="submit" class="btn btn-primary w-100 mb-2">
                                    <i class="fas fa-search me-1"></i> Terapkan Filter
                                </button>
                                <a href="{{ route('assets.index') }}" class="btn btn-secondary w-100">
                                    <i class="fas fa-times me-1"></i> Reset Filter
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- List Asset -->
        <div class="row g-4">
            @forelse ($items as $item)
                <div class="col-md-4">
                    <div class="card card-asset shadow-sm h-100">
                        @if ($item->photo && file_exists(public_path('storage/' . $item->photo)))
                            <img src="{{ asset('storage/' . $item->photo) }}" class="card-img-top"
                                alt="{{ $item->item_name }}">
                        @else
                            <div class="img-placeholder">
                                <i class="fas fa-image"></i>
                            </div>
                        @endif
                        <div class="card-body p-2">
                            <h5 class="card-title mb-1">{{ $item->item_name }}</h5>
                            <p class="card-text mb-1"><strong>Total Barang:</strong> {{ $item->qty }}</p>
                            <p class="card-text mb-1"><strong>Stok Tersedia:</strong> {{ $item->available }}</p>
                            <p class="card-text mb-1"><strong>Rusak:</strong> {{ $item->broken }}</p>
                            <p class="card-text mb-1"><strong>Hilang:</strong> {{ $item->lost }}</p>
                            <p class="card-text mb-1"><strong>Ruangan:</strong> {{ $item->room->name ?? '-' }}</p>
                            @if (Auth::check() && Auth::user()->role === 'user')
                                @if($item->available > 0)
                                    <a href="{{ route('assets.form_pinjam.form', $item->id) }}"
                                        class="btn btn-primary btn-sm mt-2">Pinjam</a>
                                @else
                                    <button class="btn btn-secondary btn-sm mt-2" disabled>Tidak Tersedia</button>
                                @endif
                            @else
                                <button class="btn btn-secondary btn-sm mt-2" disabled>Pinjam</button>
                            @endif

                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-warning text-center">
                        Tidak ada asset ditemukan.
                    </div>
                </div>
            @endforelse
        </div>

        <div class="mt-4">
            {{ $items->links('pagination::bootstrap-5') }}
        </div>
    </div>

    <!-- Footer -->
    @include('layouts.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-expand filter on desktop
        if (window.innerWidth >= 768) {
            const filterCollapse = document.getElementById('filterCollapse');
            if (filterCollapse) {
                const bsCollapse = new bootstrap.Collapse(filterCollapse, {
                    toggle: true
                });
            }
        }
        
        // Check if any filter is active and expand the filter section
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('kategori_id') || urlParams.has('room_id')) {
            const filterCollapse = document.getElementById('filterCollapse');
            if (filterCollapse && window.innerWidth < 768) {
                const bsCollapse = new bootstrap.Collapse(filterCollapse, {
                    toggle: true
                });
                
                // Update button aria-expanded
                const toggleBtn = document.querySelector('.filter-toggle-btn');
                if (toggleBtn) {
                    toggleBtn.setAttribute('aria-expanded', 'true');
                }
            }
        }
    });
    </script>
</body>

</html>
