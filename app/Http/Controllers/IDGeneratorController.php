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
        $info = "IF";
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $uuid = $info . substr($charid, 0, 6);
        return $uuid;
    }

    public function generateRating(){
        $rating = "RT";
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $uuid = $rating . substr($charid, 0, 6);
        return $uuid;
    }

    public function generateChat(){
        $chat = "CH";
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $uuid = $chat . substr($charid, 0, 6);
        return $uuid;
        
    }

    public function generatePertanyaan(){
        $pertanyaan = "PT";
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $uuid = $pertanyaan . substr($charid, 0, 6);
        return $uuid;
    }

    public function generateBidang(){
        $bidang = "BD";
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $uuid = $bidang . substr($charid, 0, 6);
        return $uuid;
    }

    public function generateKeahlian(){
        $keahlian = "KA";
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $uuid = $keahlian . substr($charid, 0, 6);
        return $uuid;
    }

    public function generateKerjasama(){
        $kerjasama = "KS";
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $uuid = $kerjasama . substr($charid, 0, 6);
        return $uuid;
    }
}
