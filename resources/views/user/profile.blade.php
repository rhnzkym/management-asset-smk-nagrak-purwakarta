<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Pengguna</title>
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
    </style>
</head>

<body>
    @include('layouts.header')

    <div class="content-wrapper container">
        <div class="container py-4">
            <div class="row justify-content-center">
                <div class="col-md-8">

                    <div class="card shadow-sm">
                        <div
                            class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-user-circle"></i> Profile</span>
                            <a href="{{ route('profile.edit') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-edit"></i> Edit Profile
                            </a>
                        </div>
                        <div class="card-body">
                            <p><strong>Nama:</strong> {{ $user->nama }}</p>
                            <p><strong>Username:</strong> {{ $user->username }}</p>
                            <p>
                                <strong>Email:</strong>
                                @php
                                    $emailParts = explode('@', $user->email);
                                    $username = $emailParts[0];
                                    $domain = $emailParts[1] ?? '';
                                    $visibleChars = min(3, strlen($username));
                                    $hiddenChars = max(0, strlen($username) - $visibleChars);
                                    $maskedEmail = substr($username, 0, $visibleChars) . str_repeat('*', $hiddenChars) . '@' . $domain;
                                @endphp
                                {{ $maskedEmail }}
                            </p>
                            <p><strong>Jurusan:</strong> {{ $user->jurusan ?? '-' }}</p>
                            @if($user->role === 'user')
                            <p><strong>NIS:</strong> {{ $user->nis ?? '-' }}</p>
                            @elseif($user->role === 'resepsionis')
                            <p><strong>NIP:</strong> {{ $user->nip ?? '-' }}</p>
                            @endif
                            <p>
                                <strong>No. Telepon:</strong>
                                @php
                                    if (!$user->nomor_telpon) {
                                        $maskedPhone = '-';
                                    } else {
                                        $phoneLength = strlen($user->nomor_telpon);
                                        $visibleDigits = min(4, $phoneLength);
                                        $hiddenDigits = max(0, $phoneLength - $visibleDigits);
                                        $maskedPhone = str_repeat('*', $hiddenDigits) . substr($user->nomor_telpon, -$visibleDigits);
                                    }
                                @endphp
                                {{ $maskedPhone }}
                            </p>
                            <p class="text-muted mt-3">Terdaftar sejak {{ $user->created_at->format('d M Y') }}</p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    @include('layouts.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
