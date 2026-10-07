<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LocalRegisterController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|max:150',
            'contact_number' => 'required|max:30',
            'email' => 'nullable|email|unique:users,email',
            'address' => 'required|max:255',
            'password' => 'required|confirmed|min:6',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email ?: null,
            'contact_number' => $request->contact_number,
            'address' => $request->address,
            'password' => Hash::make($request->password),
            'role' => 'LOCAL',
            'status' => 'ACTIVE',
        ]);

        return redirect('/auth/login')
            ->with('success', 'Account created successfully. You can now login.');
        
            // dd($request->all());

        }
}