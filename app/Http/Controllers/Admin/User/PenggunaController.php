<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Models\User;

class PenggunaController extends Controller
{
    public function index()
    {
        $user = User::with('dataDiri')
            ->where('role', 'user')
            ->orderBy('id')
            ->get();

        // dd($user);

        return view('admin.user.view', compact('user'));
    }
}
