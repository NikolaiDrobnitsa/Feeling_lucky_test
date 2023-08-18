<?php

namespace App\Http\Controllers;

use App\Models\UniqueLink;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AdminPanelController extends Controller
{
    public function index()
    {
        $users = User::with('uniqueLinks')->get();
        return view('admin-panel.index', compact('users'));
    }

    public function create()
    {
        return view('admin-panel.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:255|unique:users',
        ]);

        $user = new User([
            'name' => $validatedData['name'],
            'phone_number' => $validatedData['phone_number'],
        ]);


        $user->save();


        $uniqueLink = new UniqueLink([
            'link' => Str::random(12),
        ]);

        $user->uniqueLinks()->save($uniqueLink);


        return response()->json(['message' => 'User created successfully']);
    }

    public function edit($id)
    {
        $user = User::with('uniqueLinks')->findOrFail($id);
        return response()->json(['user' => $user]);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
        ]);

        $user->name = $validatedData['name'];
        $user->phone_number = $validatedData['phone_number'];
        $user->save();

        return response()->json(['message' => 'User updated successfully']);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }
}
