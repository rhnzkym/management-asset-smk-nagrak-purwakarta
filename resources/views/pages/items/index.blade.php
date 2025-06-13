@extends('layouts.app')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Assets</h1>
        <div class="d-flex" style="gap: 0.5rem;">
            <a href="/item/create" class="btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-plus fa-sm text-white-50"></i> Add Item
            </a>
            <a href="{{ route('items.export.pdf', request()->query()) }}" class="btn btn-sm btn-danger shadow-sm">
                <i class="fas fa-file-pdf fa-sm text-white-50"></i> Export PDF
            </a>
            <a href="{{ route('items.export.excel', request()->query()) }}" class="btn btn-sm btn-success shadow-sm">
                <i class="fas fa-file-excel fa-sm text-white-50"></i> Export Excel
            </a>
        </div>
    </div>

    {{-- Search and Filter --}}
    <div class="bg-white shadow-sm rounded-lg mb-4">
        <div class="p-3">
            <form action="{{ route('item') }}" method="GET">
                <div class="d-flex flex-wrap align-items-center gap-2">
                    {{-- Search Input --}}
                    <div class="search-input-container flex-grow-1" style="min-width: 220px; max-width: 400px;">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text border-end-0 bg-transparent">
                                <i class="fas fa-search text-muted"></i>
                            </span>
                            <input type="text" 
                                class="form-control form-control-sm border-start-0" 
                                id="search" 
                                name="search" 
                                value="{{ request('search') }}" 
                                placeholder="Cari item...">
                        </div>
                    </div>
                    
                    {{-- Filters Dropdown --}}
                    <div class="filter-dropdown-container" style="width: 180px;">
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle w-100 d-flex align-items-center justify-content-between" 
                                   type="button" 
                                   id="dropdownFilters" 
                                   data-bs-toggle="dropdown" 
                                   aria-expanded="false">
                                <span><i class="fas fa-filter me-2"></i> Filter</span>
                                <span class="ms-auto">
                                    @php $filterCount = 0; @endphp
                                    @if(request('category')) @php $filterCount++; @endphp @endif
                                    @if(request('location')) @php $filterCount++; @endphp @endif
                                    @if(request('room')) @php $filterCount++; @endphp @endif
                                    
                                    @if($filterCount > 0)
                                        <span class="badge rounded-pill bg-primary">{{ $filterCount }}</span>
                                    @endif
                                </span>
                            </button>
                            <div class="dropdown-menu p-3" aria-labelledby="dropdownFilters" style="width: 280px;">
                                {{-- Kategori Filter --}}
                                <div class="mb-3">
                                    <label for="category" class="form-label small fw-bold d-block mb-1">Kategori</label>
                                    <select class="form-select form-select-sm" id="category" name="category">
                                        <option value="">Semua Kategori</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                                {{ $category->cat_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                {{-- Lokasi Filter --}}
                                <div class="mb-3">
                                    <label for="location" class="form-label small fw-bold d-block mb-1">Lokasi</label>
                                    <select class="form-select form-select-sm" id="location" name="location">
                                        <option value="">Semua Lokasi</option>
                                        @foreach ($locations as $location)
                                            <option value="{{ $location->id }}" {{ request('location') == $location->id ? 'selected' : '' }}>
                                                {{ $location->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                {{-- Ruangan Filter --}}
                                <div class="mb-3">
                                    <label for="room" class="form-label small fw-bold d-block mb-1">Ruangan</label>
                                    <select class="form-select form-select-sm" id="room" name="room">
                                        <option value="">Semua Ruangan</option>
                                        @foreach ($rooms as $room)
                                            <option value="{{ $room->id }}" {{ request('room') == $room->id ? 'selected' : '' }}>
                                                {{ $room->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="d-flex gap-1">
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="fas fa-search"></i>
                        </button>
                        @if(request('search') || request('category') || request('location') || request('room'))
                            <a href="{{ route('item') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-times"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Table --}}
    <div class="row">
        <div class="col">
            <div class="card shadow">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered text-center">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Item</th>
                                    <th>Kategori</th>
                                    <th>Lokasi</th>
                                    <th>Ruangan</th>
                                    <th>Total Qty</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($items as $index => $item)
                                    <tr>
                                        <td>{{ $items->firstItem() + $index }}</td>
                                        <td>{{ $item->item_name }}</td>
                                        <td>{{ $item->category->cat_name }}</td>
                                        <td>{{ $item->room->location->name }}</td>
                                        <td>{{ $item->room->name }}</td>
                                        <td>{{ $item->qty }}</td>
                                        <td>
                                            <div class="d-flex justify-content-center">
                                                <a href="{{ route('item.show', $item->id) }}"
                                                    class="btn btn-sm btn-info mx-1">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('item.edit', $item->id) }}"
                                                    class="btn btn-sm btn-warning mx-1">
                                                    <i class="fas fa-pen"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-danger mx-1"
                                                    data-bs-toggle="modal" data-bs-target="#confDelete-{{ $item->id }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                        @include('pages.items.conf-delete')
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7">Data Not Found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        {{-- Pagination --}}
                        <div class="mt-3 d-flex justify-content-center">
                            {{ $items->appends(request()->query())->links('pagination::bootstrap-5') }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    /* Form Control Styles */
    .form-select, .form-control {
        border-color: #e2e8f0;
        border-radius: 0.25rem;
        font-size: 0.875rem;
        height: calc(1.5em + 0.75rem + 2px);
    }
    
    .form-select:focus, .form-control:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.15);
    }

    .input-group-text {
        background-color: #fff;
        border-color: #e2e8f0;
    }
    
    /* Filter Dropdown Styles */
    .filter-dropdown-container .dropdown-menu {
        width: 280px !important;
        padding: 1rem;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        border: none;
        border-radius: 0.5rem;
    }
    
    .dropdown-toggle {
        height: calc(1.5em + 0.75rem + 2px);
        background-color: #fff;
        font-size: 0.875rem;
    }
    
    .dropdown-toggle:hover {
        background-color: #f8fafc;
    }
    
    .form-label.small {
        font-size: 0.75rem;
        font-weight: 600;
        color: #64748b;
        margin-bottom: 0.25rem;
        display: block;
        width: 100%;
    }
    
    /* Hide numbering in select options */
    .form-select option {
        font-weight: normal;
    }
    
    /* Badge styling */
    .badge.bg-primary {
        background-color: #3b82f6 !important;
        font-weight: 500;
        font-size: 0.7rem;
    }
    
    /* Button styles */
    .btn-primary {
        background-color: #3b82f6;
        border-color: #3b82f6;
    }

    .btn-primary:hover {
        background-color: #2563eb;
        border-color: #2563eb;
    }
    
    .btn-outline-secondary {
        color: #64748b;
        border-color: #e2e8f0;
    }

    .btn-outline-secondary:hover {
        background-color: #f1f5f9;
        border-color: #cbd5e1;
        color: #475569;
    }
    
    /* Search input container */
    .search-input-container .input-group {
        border-radius: 0.25rem;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }
    
    /* Table styles */
    .table th {
        background-color: #f8fafc;
        font-weight: 600;
        color: #475569;
        font-size: 0.875rem;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .filter-dropdown-container .dropdown-menu {
            width: 100% !important;
            position: fixed;
            top: auto;
            left: 0;
            right: 0;
            transform: none !important;
            max-height: 60vh;
            overflow-y: auto;
        }
        
        .dropdown-toggle {
            width: 100%;
        }
    }
</style>
@endpush
