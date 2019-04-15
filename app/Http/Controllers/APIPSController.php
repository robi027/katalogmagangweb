<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PS;

class APIPSController extends Controller
{
    public function getAllPS(){
        $response = array();
        $data = array();

        $query = PS::get();
        $queryCount = PS::count();

        if($queryCount > 0){
            foreach($query as $item){
                array_push($data, array(
                    'id' => $item->id,
                    'ps' => $item->ps
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
