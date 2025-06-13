@extends('layouts.app')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">EDIT ROOM</h1>
    </div>

    {{-- Form Edit Room --}}
    <div class="row">
        <div class="col">
            <form action="{{ route('rooms.update', $room->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card">
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label for="name">Room Name</label>
                            <input type="text" name="name" id="name"
                                class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name', $room->name) }}">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="location_id">Location</label>
                            <select name="location_id" id="location_id"
                                class="form-control @error('location_id') is-invalid @enderror">
                                @foreach ($locations as $location)
                                    <option value="{{ $location->id }}"
                                        {{ $room->location_id == $location->id ? 'selected' : '' }}>
                                        {{ $location->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('location_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="length">Length (m) <small class="text-muted">(Optional)</small></label>
                                    <input type="number" step="0.01" name="length" id="length"
                                        class="form-control @error('length') is-invalid @enderror"
                                        value="{{ old('length', $room->length) }}" onchange="calculateArea()">
                                    @error('length')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="width">Width (m) <small class="text-muted">(Optional)</small></label>
                                    <input type="number" step="0.01" name="width" id="width"
                                        class="form-control @error('width') is-invalid @enderror"
                                        value="{{ old('width', $room->width) }}" onchange="calculateArea()">
                                    @error('width')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="area">Area (m²)</label>
                                    <input type="number" step="0.01" name="area" id="area"
                                        class="form-control @error('area') is-invalid @enderror"
                                        value="{{ old('area', $room->area) }}" onchange="updateDimensions()">
                                    @error('area')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="photo">Room Photo</label>
                            <input type="file" name="photo" class="form-control @error('photo') is-invalid @enderror">
                            @error('photo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="status">Status</label>
                            <select name="status" id="status"
                                class="form-control @error('status') is-invalid @enderror">
                                <option value="1" {{ $room->status == 1 ? 'selected' : '' }}>Available</option>
                                <option value="0" {{ $room->status == 0 ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="card-footer">
                            <div class="d-flex justify-content-end" style="gap: 7px;">
                                <a href="{{ route('rooms.index') }}" class="btn btn-outline-secondary">
                                    Back
                                </a>
                                <button type="submit" class="btn btn-primary" onclick="disableSubmit(this)">
                                    Save
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function calculateArea() {
            const length = parseFloat(document.getElementById('length').value) || 0;
            const width = parseFloat(document.getElementById('width').value) || 0;
            const area = length * width;
            document.getElementById('area').value = area.toFixed(2);
        }

        function updateDimensions() {
            const area = parseFloat(document.getElementById('area').value) || 0;
            const length = parseFloat(document.getElementById('length').value) || 0;
            const width = parseFloat(document.getElementById('width').value) || 0;
            
            // Only clear dimensions if both are empty and area is entered
            if (length === 0 && width === 0 && area > 0) {
                document.getElementById('length').value = '';
                document.getElementById('width').value = '';
            }
        }

        document.querySelector('form').addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
            }
        });

        function disableSubmit(btn) {
            btn.disabled = true;
            btn.innerHTML =
                `<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Saving...`;
            setTimeout(() => {
                btn.closest('form').submit();
            }, 100);
        }
    </script>
@endsection
