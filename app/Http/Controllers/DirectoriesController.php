<?php

namespace App\Http\Controllers;

use App\Models\Directory;
use Illuminate\Http\Request;

class DirectoriesController extends Controller
{
    public function create(Request $request) {
        $data = $request->validate([
            'name' => 'required',
            'parent' => 'required|integer',
        ]);

        $directory = Directory::find($data['parent']);
        $res = Directory::create([
            ...$data,
           'parent_id' => $directory?->id,
        ]);

        return redirect()->route('passwords');
    }
}
