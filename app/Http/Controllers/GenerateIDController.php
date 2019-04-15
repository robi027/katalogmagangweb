<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GenerateIDController extends Controller
{
    public function generatePengguna(){
        $pengguna = "US";
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $uuid = $pengguna . substr($charid, 0, 6);
        return $uuid;
    }
}
