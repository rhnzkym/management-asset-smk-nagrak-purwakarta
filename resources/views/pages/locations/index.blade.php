@extends('layouts.app')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Locations</h1>
        <div class="d-flex" style="gap: 0.5rem;">
            <a href="/locations/create" class="btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-plus fa-sm text-white-50"></i> Add Location
            </a>
            <a href="{{ route('locations.export.pdf', request()->query()) }}" class="btn btn-sm btn-danger shadow-sm">
                <i class="fas fa-file-pdf fa-sm text-white-50"></i> Export PDF
            </a>
        </div>
    </div>

    {{-- Search Filter --}}
    <div class="bg-white shadow-sm rounded-lg mb-4">
        <div class="p-4">
            <form action="{{ route('locations.index') }}" method="GET">
                <div class="row g-3">
                    {{-- Search Input --}}
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="fas fa-search text-muted"></i>
                            </span>
                            <input type="text" 
                                class="form-control border-start-0" 
                                id="search" 
                                name="search" 
                                value="{{ request('search') }}" 
                                placeholder="Cari lokasi atau alamat...">
                        </div>
                    </div>

                    {{-- Submit Button --}}
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-filter me-1"></i> Filter
                        </button>
                    </div>

                    {{-- Reset Button --}}
                    <div class="col-md-3">
                        <a href="{{ route('locations.index') }}" class="btn btn-secondary w-100">
                            <i class="fas fa-sync-alt me-1"></i> Reset
                        </a>
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
                                    <th>Name</th>
                                    <th>Address</th>
                                    <th>Area(m²)</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($locations as $index => $location)
                                    <tr>
                                        <td>{{ $locations->firstItem() + $index }}</td>
                                        <td>{{ $location->name }}</td>
                                        <td>{{ $location->address }}</td>
                                        <td>{{ $location->area }}</td>
                                        <td>
                                            <div class="d-flex justify-content-center">
                                                <a href="{{ route('locations.show', $location->id) }}"
                                                    class="btn btn-sm btn-info mx-1">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('locations.edit', $location->id) }}"
                                                    class="btn btn-sm btn-warning mx-1">
                                                    <i class="fas fa-pen"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-danger mx-1"
                                                    data-bs-toggle="modal" data-bs-target="#confDelete-{{ $location->id }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                        @include('pages.locations.conf-delete')
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
                            {{ $locations->links('pagination::bootstrap-5') }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
