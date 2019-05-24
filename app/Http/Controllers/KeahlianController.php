<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Keahlian;

class KeahlianController extends Controller
{
    public function indexDaftar(){
        $login = Auth::user()->nama;
        return view('pj.keahlian', ['login' => $login]);
    }

    public function getAllData(Request $request){
        $data = array();
        $columns = array(
            0 => 'id',
            1 => 'keahlian',
            2 => 'action'
        );

        $totalData = Keahlian::count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value'))){
            $keahlian = Keahlian::select('*')
            ->offset($start)
            ->limit($limit)
            ->orderBy($order, $dir)
            ->get();
        }else{
            $search = $request->input('search.value');

            $keahlian = Keahlian::select('*')
            ->offset($start)
            ->limit($limit)
            ->orderBy($order, $dir)
            ->where('keahlian', 'like', "%$search%")
            ->get();

            $totalFiltered = $keahlian->count();
        }

        if(!empty($keahlian)){
            foreach($keahlian as $iKeahlian){
                array_push($data, array(
                    'id' => $iKeahlian->id,
                    'keahlian' => $iKeahlian->keahlian,
                    'action' => '
                    <button id="' . $iKeahlian->id . '" class="btn btn-xs btn-primary edit" type="button"><i class="fa fa-edit"></i> Edit</button>'
                ));
            }
        }

        $jsonData = array(
            'draw' => intval($request->input('draw')),
            'recordsTotal' => intval($totalData),
            'recordsFiltered' => intval($totalFiltered),
            'data' => $data
        );

        echo json_encode($jsonData);
    }

    public function addKeahlian(Request $request){
        $keahlian = $request->input('keahlian');
        $id = app(IDGeneratorController::class)->generateKeahlian();

        $field = array('id' => $id, 'keahlian' => $keahlian);
        $query = Keahlian::insert($field);

        if($query){
            $response['error'] = FALSE;
            $response['message'] = "Keahlian published successfully";
        }else{
            $response['error'] = TRUE;
            $response['message'] = "Failed to publish keahlian";
        }

        return json_encode($response);

    }

    public function editKeahlian(Request $request){
        $id = $request->input('id');
        $keahlian = $request->input('keahlian');

        $query = Keahlian::where('id', $id)->update(['keahlian' => $keahlian]);

        if($query){
            $response['error'] = FALSE;
            $response['message'] = "Keahlian updated successfully";
        }else{
            $response['error'] = TRUE;
            $response['message'] = "Failed to update keahlian";
        }

        return json_encode($response);

    }
}
