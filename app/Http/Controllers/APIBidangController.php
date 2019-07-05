<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Bidang;
use App\RatingBidang;
use App\InfoBidang;
use App\TempatBidang;
use App\Kemampuan;

class APIBidangController extends Controller
{
    public function getAllBidang(){
        $response = array();
        $data = array();

        $query = Bidang::orderBy('bidang', 'ASC')->get();
        $queryCount = Bidang::count();

        if($queryCount > 0){
            foreach($query as $item){
                array_push($data, array(
                    'id' => $item->id,
                    'bidang' => $item->bidang,
                    'idPS' => $item->idPS
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

    public function getRatingBidang($idRating){
        $response = array();
        $data = array();
        
        $queryBidang = Bidang::orderBy('bidang', 'ASC')->get();

        $queryRating = RatingBidang::select('idRating', 'idBidang')
        ->where('idRating', $idRating)
        ->get();

        
        foreach($queryBidang as $item){
            $id = $item->id;
            $selected = FALSE;
            foreach($queryRating as $items){
                $idBidang = $items->idBidang;
                if($id === $idBidang){
                    $selected = TRUE;
                }
            }
            array_push($data, array(
                'id' => $item->id,
                'bidang' => $item->bidang,
                'idPS' => $item->idPS,
                'selected' => $selected
            )); 
        }

        if($queryBidang && $queryRating){
            $response['error'] = FALSE;
            $response['message'] = "All Data";
            $response['data'] = $data;
        }else{
            $response['error'] = TRUE;
            $response['message'] = "Data Not Found";
        }
        
        return json_encode($response);

    }

    public function getInfoBidang($idInfo){
        $response = array();
        $data = array();

        $queryBidang = Bidang::orderBy('bidang', 'ASC')->get();

        $queryInfo = InfoBidang::where('idInfo', $idInfo)->get();

        foreach($queryBidang as $Ibidang){
            $id = $Ibidang->id;
            $selected = FALSE;
            foreach($queryInfo as $Iinfo){
                $idBidang = $Iinfo->idBidang;
                if($id === $idBidang){
                    $selected = TRUE;
                }
            }
            array_push($data, array(
                'id' => $id,
                'bidang' => $Ibidang->bidang,
                'selected' => $selected
            ));
        }

        if($queryBidang && $queryInfo){
            $response['error'] = FALSE;
            $response['message'] = "All Data";
            $response['data'] = $data;
        }else{
            $response['error'] = TRUE;
            $response['message'] = "Data Not Found";
        }
        
        return json_encode($response);
        
    }

    public function getTempatBidang($idTempat){
        $response = array();
        $data = array();

        $queryBidang = Bidang::orderBy('bidang', 'ASC')->get();

        $queryTempat = TempatBidang::where('idTempat', $idTempat)->get();

        foreach($queryBidang as $iBidang){
            $id = $iBidang->id;
            $selected = FALSE;
            foreach($queryTempat as $iTempat){
                $idBidang = $iTempat->idBidang;
                if($id === $idBidang){
                    $selected = TRUE;
                }
            }
            array_push($data, array(
                'id' => $id,
                'bidang' => $iBidang->bidang,
                'selected' => $selected
            ));
        }

        if($queryBidang && $queryTempat){
            $response['error'] = FALSE;
            $response['message'] = "All Data";
            $response['data'] = $data;
        }else{
            $response['error'] = TRUE;
            $response['message'] = "Data Not Found";
        }
        
        return json_encode($response);
    }

    public function getPenggunaBidang($idUser){
        $response = array();
        $data = array();

        $queryBidang = Bidang::orderBy('bidang', 'ASC')->get();

        $queryPengguna = Kemampuan::where('idUser', $idUser)->get();

        foreach($queryBidang as $iBidang){
            $id = $iBidang->id;
            $selected = FALSE;
            foreach($queryPengguna as $iPengguna){
                $idBidang = $iPengguna->idBidang;
                if($id === $idBidang){
                    $selected = TRUE;
                }
            }
            array_push($data, array(
                'id' => $id,
                'bidang' => $iBidang->bidang,
                'selected' => $selected
            ));
        }

        if($queryBidang && $queryPengguna){
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
