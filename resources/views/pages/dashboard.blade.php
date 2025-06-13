@extends('layouts.app')

@section('content')
    <style>
        .card-custom {
            border-radius: 0 !important;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card-custom:hover {
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            transform: translateY(-4px);
        }

        .card-custom:active {
            transform: translateY(-2px) scale(0.98);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        thead th.border-bottom-0 {
            border-bottom: none !important;
        }

        table.table-hover-custom tbody tr:hover {
            background-color: #f8f9fa;
            cursor: pointer;
        }
        
        .chart-container {
            position: relative;
            margin: auto;
            height: 250px;
            width: 100%;
        }
    </style>
    
    <!-- Load Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
        <div class="d-flex" style="gap: 0.5rem;">
            {{-- <button class="btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-download fa-sm text-white-50"></i> Export Report
            </button>
            <button class="btn btn-sm btn-success shadow-sm">
                <i class="fas fa-plus fa-sm text-white-50"></i> Quick Add
            </button> --}}
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="row mb-4">

        <div class="col-6 col-md-3 mb-3">
            <a href="/locations" style="text-decoration: none; color: inherit;">
                <div class="card shadow-sm border-0 card-custom">
                    <div class="card-body text-center">
                        <h6 class="text-secondary mb-2">Total Lokasi</h6>
                        <h3 class="mb-0">{{ $totalBuildings }}</h3>
                        <small class="text-muted"><i class="fas fa-door-open me-1"></i>{{ $totalRooms }} Total
                            Ruangan</small>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-6 col-md-3 mb-3">
            <a href="/item" style="text-decoration: none; color: inherit;">
                <div class="card shadow-sm border-0 card-custom">
                    <div class="card-body text-center">
                        <h6 class="text-secondary mb-2">Total Item</h6>
                        <h3 class="mb-0">{{ $totalItems }}</h3>
                        <small class="text-muted"><i class="fas fa-boxes me-1"></i>Semua item aset</small>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-6 col-md-3 mb-3">
            <div class="card shadow-sm border-0 card-custom">
                <div class="card-body text-center">
                    <h6 class="text-secondary mb-2">Kondisi Baik</h6>
                    <h3 class="mb-0">{{ $goodCondition }}</h3>
                    @php
                        $percentGood = $totalItems > 0 ? round(($goodCondition / $totalItems) * 100) : 0;
                    @endphp
                    <small class="text-muted"><i class="fas fa-check-circle me-1"></i>{{ $percentGood }}% dari total</small>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3 mb-3">
            <div class="card shadow-sm border-0 card-custom">
                <div class="card-body text-center">
                    <h6 class="text-secondary mb-2">Aset Rusak</h6>
                    <h3 class="mb-0">{{ $damagedAssets }}</h3>
                    @php
                        $percentDamaged = $totalItems > 0 ? round(($damagedAssets / $totalItems) * 100) : 0;
                    @endphp
                    <small class="text-muted"><i class="fas fa-exclamation-triangle me-1"></i>{{ $percentDamaged }}% dari
                        total</small>
                </div>
            </div>
        </div>
    </div>

    {{-- Charts Section --}}
    <div class="row mb-4">
        <!-- Item Condition Chart -->
        <div class="col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Grafik Kondisi asset</h6>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="itemConditionChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Loan Status Chart -->
        <div class="col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Grafik Distribusi Status Peminjaman</h6>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="loanStatusChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mb-4">
        <!-- Monthly Trends Chart -->
        <div class="col-md-8 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Tren Peminjaman Bulanan</h6>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="monthlyTrendChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Top Categories -->
        <div class="col-md-4 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Kategori Teratas berdasarkan Item</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Kategori</th>
                                    <th>Total Item</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($topCategories as $category)
                                    <tr>
                                        <td>{{ $category->cat_name }}</td>
                                        <td class="text-center">{{ $category->total_items }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center">Tidak ada kategori ditemukan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Activities --}}
    <div class="row">
        <div class="col">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Aktivitas Terbaru</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <p class="mb-0">Aktivitas peminjaman item terbaru</p>
                        <a href="/borrow" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered text-center mb-0 table-hover-custom">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-bottom-0">Pengguna</th>
                                    <th class="border-bottom-0">Item</th>
                                    <th class="border-bottom-0">Jumlah</th>
                                    <th class="border-bottom-0">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($recentActivities as $activity)
                                    <tr>
                                        <td>{{ $activity->user->nama ?? 'N/A' }}</td>
                                        <td>{{ $activity->item->item_name ?? 'N/A' }}</td>
                                        <td>{{ $activity->jumlah }}</td>
                                        <td>
                                            @php
                                                $statusClass = match ($activity->status) {
                                                    'kembali' => 'bg-success text-white',
                                                    'pinjam' => 'bg-danger text-white',
                                                    'pending' => 'bg-warning text-dark',
                                                    default => 'bg-secondary text-white',
                                                };
                                            @endphp
                                            <span
                                                class="badge {{ $statusClass }}">{{ ucfirst($activity->status) }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4">Tidak ada aktivitas terbaru ditemukan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Item Condition Chart
    const itemConditionCtx = document.getElementById('itemConditionChart').getContext('2d');
    const itemConditionChart = new Chart(itemConditionCtx, {
        type: 'doughnut',
        data: {
            labels: ['Baik', 'Rusak', 'Hilang'],
            datasets: [{
                data: [{{ $itemConditionData['good'] }}, {{ $itemConditionData['broken'] }}, {{ $itemConditionData['lost'] }}],
                backgroundColor: ['#36A2EB', '#FF6384', '#FFCE56'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.label || '';
                            if (label) {
                                label += ': ';
                            }
                            if (context.parsed !== undefined) {
                                label += context.parsed + ' items';
                            }
                            return label;
                        }
                    }
                }
            }
        }
    });

    // Loan Status Chart
    const loanStatusCtx = document.getElementById('loanStatusChart').getContext('2d');
    const loanStatusChart = new Chart(loanStatusCtx, {
        type: 'pie',
        data: {
            labels: ['Pending', 'Dipinjam', 'Dikembalikan'],
            datasets: [{
                data: [{{ $loanStatusData['pending'] }}, {{ $loanStatusData['pinjam'] }}, {{ $loanStatusData['kembali'] }}],
                backgroundColor: ['#FFCE56', '#FF6384', '#4BC0C0'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.label || '';
                            if (label) {
                                label += ': ';
                            }
                            if (context.parsed !== undefined) {
                                label += context.parsed + ' loans';
                            }
                            return label;
                        }
                    }
                }
            }
        }
    });

    // Monthly Trend Chart
    const monthlyTrendCtx = document.getElementById('monthlyTrendChart').getContext('2d');
    const monthlyTrendChart = new Chart(monthlyTrendCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($monthlyLoanData['labels']) !!},
            datasets: [{
                label: 'Jumlah Peminjaman',
                data: {!! json_encode($monthlyLoanData['data']) !!},
                backgroundColor: 'rgba(78, 115, 223, 0.05)',
                borderColor: 'rgba(78, 115, 223, 1)',
                pointBackgroundColor: 'rgba(78, 115, 223, 1)',
                pointBorderColor: '#fff',
                pointHoverBackgroundColor: '#fff',
                pointHoverBorderColor: 'rgba(78, 115, 223, 1)',
                borderWidth: 2,
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgb(255, 255, 255)',
                    bodyColor: '#858796',
                    titleMarginBottom: 10,
                    titleColor: '#6e707e',
                    titleFontSize: 14,
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    padding: 15,
                    displayColors: false
                }
            }
        }
    });
</script>
@endpush
