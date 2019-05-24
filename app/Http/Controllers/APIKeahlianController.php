<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Keahlian;
use App\RatingKeahlian;
use App\InfoKeahlian;
use App\TempatKeahlian;
use App\Berkeahlian;

class APIKeahlianController extends Controller
{
    public function getAllKeahlian(){
        $response = array();
        $data = array();
        $query = Keahlian::select();

        if($query->count() > 0){
            foreach($query->get() as $item){
                array_push($data, array(
                    "id" => $item->id,
                    "keahlian" => $item->keahlian
                ));
            }
            $response['error'] = FALSE;
            $response['message'] = "All Data";
            $response['data'] = $data;
        }else{
            $response['error'] = TRUE;
            $response['message'] = "Data Not Found";
        }

        return json_encode($response);
    }

    public function getRatingKeahlian($idRating){
        $response = array();
        $data = array();

        $queryKeahlian = Keahlian::get();

        $queryRating = RatingKeahlian::where('idRating', $idRating)->get();

        foreach($queryKeahlian as $iKeahlian){
            $id = $iKeahlian->id;
            $selected = FALSE;
            foreach($queryRating as $iRating){
                $idKeahlian = $iRating->idKeahlian;
                if($id === $idKeahlian){
                    $selected = TRUE;
                }
            }
            array_push($data, array(
                'id' => $id,
                'keahlian' => $iKeahlian->keahlian,
                'selected' => $selected
            ));
        }

        if($queryKeahlian && $queryRating){

            $response['error'] = FALSE;
            $response['message'] = "All Data";
            $response['data'] = $data;

        }else{

            $response['error'] = TRUE;
            $response['message'] = "Data Not Found";

        }

        return json_encode($response);

    }

    public function getInfoKeahlian($idInfo){
        $response = array();
        $data = array();

        $queryKeahlian = Keahlian::get();

        $queryInfo = InfoKeahlian::where('idInfo', $idInfo)->get();

        foreach($queryKeahlian as $iKeahlian){
            $id = $iKeahlian->id;
            $selected = FALSE;
            foreach($queryInfo as $iInfo){
                $idKeahlian = $iInfo->idKeahlian;
                if($id === $idKeahlian){
                    $selected = TRUE;
                }
            }
            array_push($data, array(
                'id' => $id,
                'keahlian' => $iKeahlian->keahlian,
                'selected' => $selected
            ));
        }

        if($queryKeahlian && $queryInfo){

            $response['error'] = FALSE;
            $response['message'] = "All Data";
            $response['data'] = $data;

        }else{

            $response['error'] = TRUE;
            $response['message'] = "Data Not Found";

        }

        return json_encode($response);
    }

    public function getTempatKeahlian($idTempat){
        $response = array();
        $data = array();

        $queryKeahlian = Keahlian::get();

        $queryTempat = TempatKeahlian::where('idtempat', $idTempat)->get();

        foreach($queryKeahlian as $iKeahlian){
            $id = $iKeahlian->id;
            $selected = FALSE;
            foreach($queryTempat as $iTempat){
                $idKeahlian = $iTempat->idKeahlian;
                if($id === $idKeahlian){
                    $selected = TRUE;
                }
            }
            array_push($data, array(
                'id' => $id,
                'keahlian' => $iKeahlian->keahlian,
                'selected' => $selected
            ));
        }

        if($queryKeahlian && $queryTempat){

            $response['error'] = FALSE;
            $response['message'] = "All Data";
            $response['data'] = $data;

        }else{

            $response['error'] = TRUE;
            $response['message'] = "Data Not Found";

        }

        return json_encode($response);
    }

    public function getPenggunaKeahlian($idUser){

        $response = array();
        $data = array();

        $queryKeahlian = Keahlian::get();

        $queryPengguna = Berkeahlian::where('idUser', $idUser)->get();

        foreach($queryKeahlian as $iKeahlian){
            $id = $iKeahlian->id;
            $selected = FALSE;
            foreach($queryPengguna as $iPengguna){
                $idKeahlian = $iPengguna->idKeahlian;
                if($id === $idKeahlian){
                    $selected = TRUE;
                }
            }
            array_push($data, array(
                'id' => $id,
                'keahlian' => $iKeahlian->keahlian,
                'selected' => $selected
            ));
        }

        if($queryKeahlian && $queryPengguna){

            $response['error'] = FALSE;
            $response['message'] = "All Data";
            $response['data'] = $data;

        }else{

            $response['error'] = TRUE;
            $response['message'] = "Data Not Found";

        }

        return json_encode($response);
    }
}
