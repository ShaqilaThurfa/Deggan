<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class SuperAdmin extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('isSuperAdmin');
    }

    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }
}
