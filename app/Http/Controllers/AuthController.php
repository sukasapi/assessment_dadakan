<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Peserta;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } else {
                // Redirect peserta to their dashboard
                $peserta = Peserta::where('user_id', $user->id)->first();
                if ($peserta) {
                    return redirect()->route('peserta.dashboard');
                }
            }
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->withInput($request->only('email'));
    }

    /**
     * Handle logout
     */
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    /**
     * Show participant login with PIN
     */
    public function showParticipantLogin()
    {
        return view('auth.participant-login');
    }

    /**
     * Handle participant login with PIN
     */
    public function participantLogin(Request $request)
    {
        $request->validate([
            'pin' => 'required|string|min:6|max:10|regex:/^[A-Za-z0-9]{6,10}$/',
        ]);

        $peserta = Peserta::where('pin', $request->pin)
                         ->where('aktif', true)
                         ->first();

        if (!$peserta) {
            return back()->withErrors([
                'pin' => 'PIN tidak valid atau peserta tidak aktif.',
            ]);
        }

        if (!$peserta->user) {
            return back()->withErrors([
                'pin' => 'Data user tidak ditemukan untuk peserta ini.',
            ]);
        }

        // Login user dan set session
        Auth::login($peserta->user);
        
        // Set session tambahan untuk peserta
        session(['peserta_id' => $peserta->id]);
        session(['peserta_name' => $peserta->nama_lengkap]);

        return redirect()->route('peserta.dashboard');
    }
}
