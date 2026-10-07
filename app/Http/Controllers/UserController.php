<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();

        return view('admin.users', compact('users'));
    }

    public function getUser($id)
    {
        $user = User::findOrFail($id);

        return response()->json([
            'success' => true,
            'user' => $user
        ]);
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'contact_number' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:500',
            'role' => 'required|string',
            'status' => 'required|string',
            'password' => 'nullable|string|min:8',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->contact_number = $validated['contact_number'] ?? null;
        $user->address = $validated['address'] ?? null;
        $user->role = $validated['role'];
        $user->status = $validated['status'];

        if ($user->role === 'ADMIN' && !empty($validated['password'])) {

            $user->password = Hash::make(
                $validated['password']
            );
        }

        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'User information updated successfully.',
            'user' => $user
        ]);
    }


    public function store(Request $request)
        {
            $validated = $request->validate([

                'name' => 'required|string|max:255',

                'email' => 'required|email|max:255|unique:users,email',

                'contact_number' => 'nullable|string|max:50',

                'address' => 'nullable|string|max:500',

                'role' => 'required|in:LOCAL,ADMIN',

                'status' => 'required|in:ACTIVE,SUSPENDED',

                'password' => 'required|string|min:8',

            ]);

            $user = User::create([

                'name' => $validated['name'],

                'email' => $validated['email'],

                'contact_number' =>
                    $validated['contact_number'] ?? null,

                'address' =>
                    $validated['address'] ?? null,

                'role' => $validated['role'],

                'status' => $validated['status'],

                'password' =>
                    Hash::make($validated['password']),

            ]);

            return response()->json([

                'success' => true,

                'message' =>
                    'User account created successfully.',

            ]);
        }
}
