<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SessionController extends Controller
{
    function distorySession(Request $request)
    {
        session()->forget('user');
        redirect('/login');
    }
}
