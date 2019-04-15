<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Bidang;

class APIBidangController extends Controller
{
    public function getAllBidang(){
        $response = array();
        $data = array();

        $query = Bidang::get();
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
}
