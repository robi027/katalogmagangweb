<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardAdminController extends Controller
{

    public function index()
    {

        $login = Auth::user()->nama;
        $idLogin = Auth::user()->id;

        return view('admin.dashboard', ['login' => $login, 'idLogin' => $idLogin]);
    }

}
