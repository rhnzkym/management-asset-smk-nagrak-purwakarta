<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil Pengguna</title>
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
            padding-top: 40px;
            padding-bottom: 40px;
        }

        .profile-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background-color: #dee2e6;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            color: #6c757d;
            margin: 0 auto 20px;
        }

        .card-header {
            font-size: 1.25rem;
            font-weight: bold;
            background-color: #f8f9fa;
        }

        .form-section {
            border-top: 1px solid #ddd;
            padding-top: 30px;
            margin-top: 30px;
        }

        footer {
            margin-top: auto;
        }
    </style>
</head>

<body>
    @include('layouts.header')

    <div class="content-wrapper container">
        <div class="container py-4">
            <div class="row justify-content-center">
                <div class="col-md-8">

                    <form action="{{ route('profile.update') }}" method="POST">

                        @csrf
                        @method('PUT')

                        <div class="card shadow-sm">
                            <div class="card-header bg-warning text-white">
                                <i class="fas fa-edit"></i> Edit Profil
                            </div>

                            <div class="card-body">
                                @if(session('error'))
                                <div class="alert alert-danger">
                                    {{ session('error') }}
                                </div>
                                @endif

                                <div class="mb-3">
                                    <label>Nama</label>
                                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                        value="{{ old('name', $user->nama) }}">
                                    @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label>Username</label>
                                    <input type="text" name="username" class="form-control @error('username') is-invalid @enderror"
                                        value="{{ old('username', $user->username) }}">
                                    @error('username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label>Email</label>
                                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                        value="{{ old('email', $user->email) }}">
                                    @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                @if($user->role === 'super_admin' || $user->role === 'admin' || $user->role === 'resepsionis')
                                <div class="mb-3">
                                    <label>NIP</label>
                                    <input type="text" name="nip" class="form-control @error('nip') is-invalid @enderror"
                                        value="{{ old('nip', $user->nip) }}">
                                    @error('nip')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                @else
                                <div class="mb-3">
                                    <label>Jurusan</label>
                                    <input type="text" name="jurusan" class="form-control @error('jurusan') is-invalid @enderror"
                                        value="{{ old('jurusan', $user->jurusan) }}">
                                    @error('jurusan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label>NIS</label>
                                    <input type="text" name="nis" class="form-control @error('nis') is-invalid @enderror"
                                        value="{{ old('nis', $user->nis) }}">
                                    @error('nis')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                @endif

                                <div class="mb-3">
                                    <label>No. Telepon</label>
                                    <input type="text" name="telepon" class="form-control @error('telepon') is-invalid @enderror"
                                        value="{{ old('telepon', $user->nomor_telpon) }}">
                                    @error('telepon')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <hr>
                                <h6 class="text-muted">Ganti Password (opsional)</h6>
                                <div class="mb-3">
                                    <label>Password Baru</label>
                                    <input type="password" name="password" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label>Konfirmasi Password</label>
                                    <input type="password" name="password_confirmation" class="form-control">
                                </div>

                                <button type="submit" class="btn btn-success mt-2">Simpan Perubahan</button>
                                <a href="{{ route('profile') }}" class="btn btn-secondary mt-2">Batal</a>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    @include('layouts.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
