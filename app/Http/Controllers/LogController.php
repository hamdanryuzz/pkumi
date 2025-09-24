<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Log;

class LogController extends Controller
{
    public function index()
    {
        $logs = Log::latest()->paginate(10);
        return view('log.index', compact('logs'));
    }
}
