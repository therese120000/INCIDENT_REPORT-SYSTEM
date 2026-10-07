<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'contact_number' => 'required',
            'password' => 'required',
        ]);

        $credentials = [
            'contact_number' => $request->contact_number,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials)) {

            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->role === 'ADMIN') {
                return redirect('/admin/dashboard');
            }

            if ($user->role === 'LOCAL') {
                return redirect('/local/dashboard');
            }

            Auth::logout();

            return back()->withErrors([
                'contact_number' => 'Invalid user role.',
            ])->withInput();
        }

        return back()->withErrors([
            'contact_number' => 'Invalid phone number or password.',
        ])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/auth/login');
    }
}
