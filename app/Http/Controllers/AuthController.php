<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function loginForm()
    {
        return view('login');
    }

    public function registerForm()
    {
        // Define jurusan options for dropdown
        $jurusanOptions = [
            'Agribisnis Tanaman Pangan dan Hortikultura',
            'Teknik Komputer Jaringan'
        ];
        return view('register', compact('jurusanOptions'));
    }

    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');
        
        // First check if credentials are valid
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            
            // Check if account is pending approval
            if ($user->status === 'pending') {
                Auth::logout();
                return back()->withErrors([
                    'status' => 'Akun Anda masih menunggu persetujuan dari admin.',
                ]);
            }
            
            // Check if account is rejected
            if ($user->status === 'rejected') {
                Auth::logout();
                return back()->withErrors([
                    'status' => 'Akun Anda telah ditolak oleh admin.',
                ]);
            }
            
            // User is active, proceed with login
            $request->session()->regenerate();
            return redirect()->intended('/');
        }
    
        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'username' => 'required|string|max:50|unique:users',
            'jurusan' => 'required|string|max:100',
            'nis' => 'required|string|max:20|unique:users',
            'nomor_telpon' => 'nullable|string|max:15',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|confirmed|min:6',
        ]);
    
        User::create([
            'nama' => $request->nama,
            'username' => $request->username,
            'jurusan' => $request->jurusan,
            'nis' => $request->nis,
            'nomor_telpon' => $request->nomor_telpon,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'user',
            'status' => 'pending', // Account starts as pending
        ]);
    
        return redirect('/login')->with('info', 'Registrasi berhasil. Akun Anda menunggu persetujuan admin.');
    }
    

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
