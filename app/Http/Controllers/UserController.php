<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('status', 'active')->paginate(10);
        return view('pages.users.index', compact('users'));
    }
    
    public function pendingUsers()
    {
        $users = User::where('status', 'pending')->paginate(10);
        return view('pages.users.pending', compact('users'));
    }

    public function create()
    {
        $jurusanOptions = [
            'Agribisnis Tanaman Pangan dan Hortikultura',
            'Teknik Komputer Jaringan'
        ];
        return view('pages.users.create', compact('jurusanOptions'));
    }
    
    public function createReceptionist()
    {
        return view('pages.users.create_receptionist');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'username' => 'required|string|max:50|unique:users',
            'jurusan' => 'required|string|max:100',
            'nis' => 'required|string|max:20|unique:users',
            'nomor_telpon' => 'nullable|string|max:15',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'role' => ['required', Rule::in(['super_admin', 'resepsionis', 'user'])],
        ]);

        $validated['password'] = Hash::make($request->password);
        $validated['status'] = 'active'; // Admin-created accounts are automatically active

        User::create($validated);

        return redirect('/user')->with('success', 'User has been added');
    }
    
    public function storeReceptionist(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'username' => 'required|string|max:50|unique:users',
            'nip' => 'required|string|max:20|unique:users',
            'nomor_telpon' => 'nullable|string|max:15',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed'
        ]);

        $validated['password'] = Hash::make($request->password);
        $validated['role'] = 'resepsionis';
        $validated['status'] = 'active';

        User::create($validated);

        return redirect('/user')->with('success', 'Receptionist has been added');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('pages.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'username' => 'required|string|max:50|unique:users,username,' . $id,
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => ['required', Rule::in(['super_admin', 'resepsionis', 'user'])],
            'jurusan' => 'nullable|string|max:100',
            'nis' => 'nullable|string|max:20|unique:users,nis,' . $id,
            'nip' => 'nullable|string|max:20|unique:users,nip,' . $id,
            'nomor_telpon' => 'nullable|string|max:15',
            'password' => 'nullable|min:6|confirmed',
        ]);

        // Hapus password dari validated jika tidak diisi
        if (!$request->filled('password')) {
            unset($validated['password']);
        } else {
            $validated['password'] = Hash::make($request->password);
        }

        $user->update($validated);

        return redirect('/user')->with('success', 'User berhasil diperbarui');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect('/user')->with('success', 'User has been deleted');
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('pages.users.show', compact('user'));
    }

    public function showProfile()
    {
        $user = Auth::user(); // ambil user yang sedang login
    
        return view('user.profile', compact('user'));
    }

    public function editProfile()
    {
        $user = Auth::user();
    return view('user.edit', compact('user'));
}

public function updateProfile(Request $request)
{
    $user = User::findOrFail(Auth::id());

    // Different validation rules based on role
    if ($user->role === 'super_admin' || $user->role === 'admin' || $user->role === 'resepsionis') {
        $rules = [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'nip' => 'required|string|max:20|unique:users,nip,' . $user->id,
            'telepon' => 'nullable|string|max:20',
        ];
    } else {
        $rules = [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'jurusan' => 'required|string|max:100',
            'nis' => 'required|string|max:20|unique:users,nis,' . $user->id,
            'telepon' => 'nullable|string|max:20',
        ];
    }

    if ($request->filled('password')) {
        $rules['password'] = 'required|string|min:6|confirmed';
    }

    $validated = $request->validate($rules);

    // Update user data
    $user->nama = $validated['name'];
    $user->username = $validated['username'];
    $user->email = $validated['email'];
    
    if ($user->role === 'super_admin' || $user->role === 'admin' || $user->role === 'resepsionis') {
        $user->nip = $validated['nip'];
    } else {
        $user->jurusan = $validated['jurusan'];
        $user->nis = $validated['nis'];
    }
    
    $user->nomor_telpon = $validated['telepon'] ?? null;

    if ($request->filled('password')) {
        $user->password = Hash::make($request->password);
    }

    $user->save();

    return redirect()->route('profile')->with('success', 'Profil berhasil diperbarui.');
}

    public function approveUser($id)
    {
        $user = User::findOrFail($id);
        $user->status = 'active';
        $user->save();
        
        return redirect()->back()->with('success', 'User has been approved');
    }
    
    public function rejectUser($id)
    {
        $user = User::findOrFail($id);
        $user->status = 'rejected';
        $user->save();
        
        return redirect()->back()->with('success', 'User has been rejected');
    }
}
