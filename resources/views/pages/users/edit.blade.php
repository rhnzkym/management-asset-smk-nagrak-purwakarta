@extends('layouts.app')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">EDIT USER</h1>
    </div>

    {{-- Form Edit User --}}
    <div class="row">
        <div class="col">
            <form action="/user/{{ $user->id }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card">
                    <div class="card-body">
                        <!-- Nama -->
                        <div class="form-group mb-3">
                            <label for="nama">Nama</label>
                            <input type="text" name="nama" id="nama"
                                class="form-control @error('nama') is-invalid @enderror"
                                value="{{ old('nama', $user->nama) }}" required>
                            @error('nama')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Username -->
                        <div class="form-group mb-3">
                            <label for="username">Username</label>
                            <input type="text" name="username" id="username"
                                class="form-control @error('username') is-invalid @enderror"
                                value="{{ old('username', $user->username) }}" required>
                            @error('username')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Role -->
                        <div class="form-group mb-3">
                            <label for="role">Role</label>
                            <select name="role" id="role" class="form-control @error('role') is-invalid @enderror">
                                <option selected disabled>-- Select Role --</option>
                                <option value="superadmin" @selected(old('role', $user->role) == 'superadmin')>Super Admin</option>
                                <option value="resepsionis" @selected(old('role', $user->role) == 'resepsionis')>Resepsionis</option>
                                <option value="user" @selected(old('role', $user->role) == 'user')>Siswa</option>
                            </select>
                            @error('role')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Jurusan (untuk siswa) -->
                        @if($user->role === 'user')
                        <div class="form-group mb-3">
                            <label for="jurusan">Jurusan</label>
                            <select name="jurusan" id="jurusan" class="form-control @error('jurusan') is-invalid @enderror">
                                <option selected disabled>-- Pilih Jurusan --</option>
                                <option value="Teknik Komputer Jaringan" @selected(old('jurusan', $user->jurusan) == 'Teknik Komputer Jaringan')>Teknik Komputer Jaringan</option>
                                <option value="Agribisnis Tanaman Pangan dan Hortikultura" @selected(old('jurusan', $user->jurusan) == 'Agribisnis Tanaman Pangan dan Hortikultura')>Agribisnis Tanaman Pangan dan Hortikultura</option>
                            </select>
                            @error('jurusan')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        @endif

                        <!-- NIS (untuk siswa) -->
                        @if($user->role === 'user')
                        <div class="form-group mb-3">
                            <label for="nis">NIS</label>
                            <input type="text" name="nis" id="nis"
                                class="form-control @error('nis') is-invalid @enderror"
                                value="{{ old('nis', $user->nis) }}">
                            @error('nis')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        @endif

                        <!-- NIP (untuk resepsionis dan super admin) -->
                        @if($user->role === 'resepsionis' || $user->role === 'superadmin')
                        <div class="form-group mb-3">
                            <label for="nip">NIP</label>
                            <input type="text" name="nip" id="nip"
                                class="form-control @error('nip') is-invalid @enderror"
                                value="{{ old('nip', $user->nip) }}">
                            @error('nip')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        @endif

                        <!-- Nomor Telpon -->
                        <div class="form-group mb-3">
                            <label for="nomor_telpon">Nomor Telpon</label>
                            <input type="number" name="nomor_telpon" id="nomor_telpon"
                                class="form-control @error('nomor_telpon') is-invalid @enderror"
                                value="{{ old('nomor_telpon', $user->nomor_telpon) }}">
                            @error('nomor_telpon')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="form-group mb-3">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email"
                                class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="form-group mb-3">
                            <label for="password">Password (Kosongkan jika tidak ingin mengubah)</label>
                            <input type="password" name="password" id="password"
                                class="form-control @error('password') is-invalid @enderror"
                                placeholder="Masukkan password">
                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="d-flex justify-content-end" style="gap: 7px;">
                            <a href="/user" class="btn btn-outline-secondary">
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
                    setTimeout(() => {
                        btn.closest('form').submit();
                    }, 100);
                }
            </script>
        </div>
    </div>
@endsection
