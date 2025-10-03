<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentPageController extends Controller
{
    public function index(){
        $user = Auth::user();

        return view('mahasiswa.grades', compact(
            'user'
        ));
    }
}
