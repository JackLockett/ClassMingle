<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $currentUserId = Auth::id();
        $students = User::where('role', 'user')->where('id', '!=', $currentUserId)->get();
        return view('view-students', ['students' => $students]);
    }    

    public function showProfile($id)
    {
        $student = User::findOrFail($id);
        return view('student', ['student' => $student]);
    }
}
