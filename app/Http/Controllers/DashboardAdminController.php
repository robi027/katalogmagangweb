<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Pertanyaan;

class DashboardAdminController extends Controller
{

    public function index()
    {
        $login = Auth::user()->nama;
        $idLogin = Auth::user()->id;

        $query = Pertanyaan::select(DB::raw('FROM_UNIXTIME(tglRecord, "%d/%m/%Y") as tgl, COUNT(FROM_UNIXTIME(tglRecord, "%d/%m/%Y")) as total'))
        ->limit(21)
        ->orderBy('tglRecord', 'DESC')
        ->where('idPenerima', $idLogin)
        ->groupBy('tgl')->get();

        return view('admin.dashboard', ['login' => $login, 'idLogin' => $idLogin, 'jumlahPertanyaan' => $query]);
    }

}
