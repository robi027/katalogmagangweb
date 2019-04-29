<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tipe;

class APITipeController extends Controller
{
    public function getAllTipe(){
        $response = array();
        $data = array();

        $query = Tipe::select();

        if($query->count() > 0){
            foreach($query->get() as $item){
                array_push($data, array(
                    'id' => $item->id,
                    'tipe' => $item->tipe
                ));
            }

            $response['error'] = FALSE;
            $response['message'] = 'All Data Tipe';
            $response['data'] = $data;

        }else{
            $response['error'] = FALSE;
            $response['message'] = 'All Data Tipe';
        }

        return json_encode($response);
    }
}
