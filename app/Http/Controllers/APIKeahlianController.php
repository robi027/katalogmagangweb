<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Keahlian;

class APIKeahlianController extends Controller
{
    public function getAllKeahlian(){
        $response = array();
        $data = array();
        $query = Keahlian::get();
        $queryCount = Keahlian::count();

        if($queryCount > 0){
            foreach($query as $item){
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
}
