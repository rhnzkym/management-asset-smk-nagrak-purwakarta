@extends('layouts.app')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Users</h1>
        {{-- Hide Add User Button
        <a href="{{ route('user.create') }}" class="btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Add User
        </a>
        --}}
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
                                    <th>Nama</th>
                                    <th>Username</th>
                                    <th>Jurusan</th>
                                    <th>NIS/NIP</th>
                                    <th>Email</th>
                                    <th>Nomor Telepon</th>
                                    <th>Role</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($users as $index => $user)
                                    <tr>
                                        <td>{{ $users->firstItem() + $index }}</td>
                                        <td>{{ $user->nama }}</td>
                                        <td>{{ $user->username }}</td>
                                        <td>{{ $user->jurusan ?? '-' }}</td>
                                        <td>
                                            @if($user->role === 'user')
                                                {{ $user->nis ?? '-' }}
                                            @elseif($user->role === 'resepsionis' || $user->role === 'super_admin')
                                                {{ $user->nip ?? '-' }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->nomor_telpon ?? '-' }}</td>
                                        <td>{{ $user->role === 'user' ? 'Siswa' : ucfirst(str_replace('_', ' ', $user->role)) }}</td>
                                        <td>
                                            <div class="d-flex justify-content-center">
                                                <a href="{{ route('user.edit', $user->id) }}"
                                                    class="btn btn-sm btn-warning mx-1">
                                                    <i class="fas fa-pen"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-danger mx-1"
                                                    data-bs-toggle="modal" data-bs-target="#confDelete-{{ $user->id }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                        @include('pages.users.conf-delete')
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9">Data Not Found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        {{-- Pagination --}}
                        <div class="mt-3 d-flex justify-content-center">
                            {{ $users->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
