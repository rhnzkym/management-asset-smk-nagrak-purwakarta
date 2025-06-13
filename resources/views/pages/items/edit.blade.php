@extends('layouts.app')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">EDIT ASSET</h1>
    </div>

    {{-- Form Add Asset --}}
    <div class="row">
        <div class="col">
            <form action="/item/{{ $item->id }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card">
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label for="category">Category</label>
                            <input type="text" class="form-control" id="categorySearch" placeholder="Search category..." value="{{ $categories->where('id', $item->cat_id)->first() ? $categories->where('id', $item->cat_id)->first()->cat_name : '' }}">
                            <select name="cat_id" id="category"
                                class="form-control @error('cat_id')
                                is-invalid
                            @enderror"
                                style="display: none;">
                                <option selected disabled>-- Select Category --</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" @selected(old('cat_id', $item->cat_id) == $category->id)>
                                        {{ $category->cat_name }}</option>
                                @endforeach
                            </select>
                            @error('cat_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="item_name">Name</label>
                            <input type="text" name="item_name" id="item_name"
                                class="form-control @error('item_name') is-invalid @enderror"
                                value="{{ old('item_name', $item->item_name) }}">
                            @error('item_name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="qty">Total Quantity</label>
                            <input type="number" name="qty" id="qty" min="0"
                                class="form-control @error('qty') is-invalid @enderror"
                                value="{{ old('qty', $item->qty) }}" required>
                            @error('qty')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="good_qty">Jumlah Barang Baik</label>
                            <input type="number" name="good_qty" id="good_qty" min="0"
                                class="form-control @error('good_qty') is-invalid @enderror"
                                value="{{ old('good_qty', $item->good_qty) }}" required>
                            @error('good_qty')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="broken_qty">Jumlah Barang Rusak</label>
                            <input type="number" name="broken_qty" id="broken_qty" min="0"
                                class="form-control @error('broken_qty') is-invalid @enderror"
                                value="{{ old('broken_qty', $item->broken_qty) }}" required>
                            @error('broken_qty')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="lost_qty">Jumlah Barang Hilang</label>
                            <input type="number" name="lost_qty" id="lost_qty" min="0"
                                class="form-control @error('lost_qty') is-invalid @enderror"
                                value="{{ old('lost_qty', $item->lost_qty) }}" required>
                            @error('lost_qty')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="is_borrowable" name="is_borrowable" value="1" {{ old('is_borrowable', $item->is_borrowable) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_borrowable">Item dapat dipinjam</label>
                            </div>
                            <small class="form-text text-muted">Centang jika item ini dapat dipinjam oleh user</small>
                        </div>
                        <div class="form-group mb-3">
                            <label for="rooms">Ruangan</label>
                            <input type="text" class="form-control" id="roomSearch" placeholder="Search room..." value="{{ $rooms->where('id', $item->room_id)->first() ? $rooms->where('id', $item->room_id)->first()->name : '' }}">
                            <select name="room_id" id="rooms"
                                class="form-control @error('room_id') is-invalid @enderror"
                                style="display: none;">
                                <option selected disabled>-- Select Room --</option>
                                @foreach ($rooms as $room)
                                    <option value="{{ $room->id }}" @selected(old('rooms', $item->room_id) == $room->id)>
                                        {{ $room->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('room_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-content-end" style="gap: 7px;">
                            <a href="/item" class="btn btn-outline-secondary">
                                Back
                            </a>
                            <button type="submit" class="btn btn-warning" onclick="disableSubmit(this)">
                                Save Change
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            <script>
                document.querySelector('form').addEventListener('keydown', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                    }
                });

                function disableSubmit(btn) {
                    btn.disabled = true;
                    btn.innerHTML =
                        `<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Saving...`;
                    // Kirim form secara eksplisit
                    setTimeout(() => {
                        btn.closest('form').submit();
                    }, 100); // Tunggu sebentar sebelum form disubmit
                }

                // Validasi total quantity
                document.getElementById('qty').addEventListener('input', validateQuantities);
                document.getElementById('good_qty').addEventListener('input', validateQuantities);
                document.getElementById('broken_qty').addEventListener('input', validateQuantities);
                document.getElementById('lost_qty').addEventListener('input', validateQuantities);

                function validateQuantities() {
                    const totalQty = parseInt(document.getElementById('qty').value) || 0;
                    const goodQty = parseInt(document.getElementById('good_qty').value) || 0;
                    const brokenQty = parseInt(document.getElementById('broken_qty').value) || 0;
                    const lostQty = parseInt(document.getElementById('lost_qty').value) || 0;

                    const sum = goodQty + brokenQty + lostQty;

                    if (sum > totalQty) {
                        alert('Total kondisi barang tidak boleh melebihi total quantity!');
                        document.getElementById('good_qty').value = '';
                        document.getElementById('broken_qty').value = '';
                        document.getElementById('lost_qty').value = '';
                    }
                }
            </script>
        </div>
    </div>

    @push('styles')
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <style>
        .ui-autocomplete {
            max-height: 200px;
            overflow-y: auto;
            overflow-x: hidden;
            background-color: #fff;
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            z-index: 1000;
        }
        .ui-autocomplete .ui-menu-item {
            padding: 8px 12px;
            cursor: pointer;
            color: #495057;
        }
        .ui-autocomplete .ui-menu-item:hover {
            background-color: #4e73df;
            color: #fff;
        }
        .ui-helper-hidden-accessible {
            display: none;
        }
    </style>
    @endpush

    @push('scripts')
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <script>
        $(document).ready(function() {
            // Prepare category data
            var categoryData = [];
            $('#category option').each(function() {
                if ($(this).val()) {
                    categoryData.push({
                        label: $.trim($(this).text()),
                        value: $(this).val()
                    });
                }
            });

            // Initialize category autocomplete
            $('#categorySearch').autocomplete({
                source: categoryData,
                minLength: 1,
                select: function(event, ui) {
                    $('#category').val(ui.item.value);
                    $('#categorySearch').val(ui.item.label);
                    return false;
                }
            });

            // Prepare room data
            var roomData = [];
            $('#rooms option').each(function() {
                if ($(this).val()) {
                    roomData.push({
                        label: $.trim($(this).text()),
                        value: $(this).val()
                    });
                }
            });

            // Initialize room autocomplete
            $('#roomSearch').autocomplete({
                source: roomData,
                minLength: 1,
                select: function(event, ui) {
                    $('#rooms').val(ui.item.value);
                    $('#roomSearch').val(ui.item.label);
                    return false;
                }
            });
        });
    </script>
    @endpush
@endsection
