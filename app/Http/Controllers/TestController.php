<?php

namespace App\Http\Controllers;

use App\Models\Directory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TestController extends Controller
{
    function test() {
        $dir = Directory::find(2);

        dd($dir->accesses);
    }
}
