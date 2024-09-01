<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LogController extends Controller
{
    //
    public function index()
    {
        $logs = DB::table('request_logs')->orderBy('created_at', 'desc')->get();

        return view('logs.index', compact('logs'));
    }
}
