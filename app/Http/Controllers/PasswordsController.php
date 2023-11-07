<?php

namespace App\Http\Controllers;

use App\Models\Directory;
use App\Models\Password;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class PasswordsController extends Controller
{
    public function index(): \Inertia\Response {
        $passwords = Password::where('user_id', Auth::id())
            ->whereNull('directory_id')
            ->get();
        $directories = Directory::with(['passwords', 'contents'])
            ->whereNull('parent_id')
            ->get();

        return Inertia::render('Passwords/Index', compact('passwords', 'directories'));
    }

    public function create(Request $request) {
        $data = $request->validate([
            'project' => 'required',
            'login' => 'required',
            'password' => 'required|min:6',
            'directory' => 'required|integer'
        ]);

        $directory = Directory::find($data['directory']);
        Auth::user()?->passwords()->create([
            ...$data,
            'directory_id' => $directory?->id,
        ]);

        return redirect()->route('passwords');
    }
}
