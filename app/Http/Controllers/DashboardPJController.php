<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Pengguna;
use App\Tempat;

class DashboardPJController extends Controller
{
    public function index(){
        $angkatan = array();
        $ps = array();

        $login = Auth::user()->nama;

        $pengguna = Pengguna::select('angkatan', DB::raw('COUNT(angkatan) AS total'))
        ->orderBy('angkatan', 'ASC')
        ->groupBy('angkatan')->get();

        $penggunaCount = Pengguna::count();

        $penggunaSection = Pengguna::select('ps.ps', DB::raw('COUNT(idPS) as total'))
        ->leftJoin('ps', 'ps.id', '=', 'pengguna.idPS')
        ->groupBy('idPS')->get();

        foreach($pengguna as $iPengguna){
            array_push($angkatan, (object) array(
                'angkatan' => $iPengguna->angkatan,
                'total' => $iPengguna->total
            ));
        }

        foreach($penggunaSection as $iSection){
            array_push($ps, (object) array(
                'value' => $iSection->total,
                'color' => "#a3e1d4",
                'highlight' => "#1ab394",
                'label' => $iSection->ps,
            ));
        }
        
        $tempatCount = Tempat::count();

        $kerjasamaCount = Tempat::leftJoin('pengguna', 'pengguna.id', '=', 'tempat.idUser')
        ->where('pengguna.level', '2')
        ->count();

        $nonActiveCount = Tempat::where('status', 0)->count();

        return view('pj.dashboard', ['login' => $login, 'angkatan' => $angkatan, 
        'totalPengguna' => $penggunaCount, 'totalKatalog' => $tempatCount, 
        'totalPS' => $ps, 'totalKerjasama' => $kerjasamaCount, 
        'nonActive' => $nonActiveCount]);
    }
}
