<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IDGeneratorController extends Controller
{
    public function generatePengguna(){
        $pengguna = "US";
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $uuid = $pengguna . substr($charid, 0, 6);
        return $uuid;
    }

    public function generateTempat(){
        $tempat = "KM";
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $uuid = $tempat . substr($charid, 0, 6);
        return $uuid;
    }

    public function generateInfo(){
        $tempat = "IF";
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $uuid = $tempat . substr($charid, 0, 6);
        return $uuid;
    }

    public function generateRating(){
        $tempat = "RT";
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $uuid = $tempat . substr($charid, 0, 6);
        return $uuid;
    }

    public function generateChat(){
        $tempat = "CH";
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $uuid = $tempat . substr($charid, 0, 6);
        return $uuid;
        
    }

    public function generatePertanyaan(){
        $tempat = "PT";
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $uuid = $tempat . substr($charid, 0, 6);
        return $uuid;
    }
}
