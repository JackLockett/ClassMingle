<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index()
    {
        $email = auth()->user()->email;
        return view('profile', compact('email'));
    }
}
