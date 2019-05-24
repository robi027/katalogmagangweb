<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Bidang;

class BidangController extends Controller
{

    public function indexDaftar(){
        $login = Auth::user()->nama;
        return view('pj.bidang', ['login' => $login]);
    }

    public function getAllData(Request $request){
        $data = array();
        $columns = array(
            0 => 'id',
            1 => 'bidang',
            2 => 'idPS',
            3 => 'ps',
            4 => 'action'
        );
        $totalData = Bidang::count();
        
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value'))){
            $bidang = Bidang::select('bidang.id', 'bidang.bidang', 'bidang.idPS', 'ps.ps')
            ->offset($start)
            ->limit($limit)
            ->orderBy($order, $dir)
            ->leftJoin('ps', 'ps.id', '=', 'bidang.idPS')
            ->get();
        }else{
            $search = $request->input('search.value');

            $bidang = Bidang::select('bidang.id', 'bidang.bidang', 'bidang.idPS', 'ps.ps')
            ->offset($start)
            ->limit($limit)
            ->orderBy($order, $dir)
            ->leftJoin('ps', 'ps.id', '=', 'bidang.idPS')
            ->where('bidang.bidang', 'like', "%$search%")
            ->get();
            
            $totalFiltered = $bidang->count();
        }

        if(!empty($bidang)){
            foreach($bidang as $iBidang){
                array_push($data, array(
                    'id' => $iBidang->id,
                    'bidang' => $iBidang->bidang,
                    'idPS' => $iBidang->idPS,
                    'ps' => $iBidang->ps,
                    'action' => '
                    <button id="' . $iBidang->id . '" class="btn btn-xs btn-primary edit" type="button"><i class="fa fa-edit"></i> Edit</button>'
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

    public function addBidang(Request $request){
        $response = array();

        $idPS = $request->input('idPS');
        $bidang = $request->input('bidang');

        $id = app(IDGeneratorController::class)->generateBidang();

        $field = array('id' => $id, 'bidang' => $bidang, 'idPS' => $idPS);

        $query = Bidang::insert($field);

        if($query){
            $response['error'] = FALSE;
            $response['message'] = "Bidang published successfully";
        }else{
            $response['error'] = TRUE;
            $response['message'] = "Failed to publish bidang";
        }

        return json_encode($response);
    }

    public function editBidang(Request $request){
        $response = array();
        $id = $request->input('id');
        $idPS = $request->input('idPS');
        $bidang = $request->input('bidang');

        $field = array('idPS' => $idPS, 'bidang' => $bidang);

        $query = Bidang::where('id', $id)->update($field);

        if($query){
            $response['error'] = FALSE;
            $response['message'] = "Bidang updated successfully";
        }else{
            $response['error'] = TRUE;
            $response['message'] = "Failed to update bidang";
        }

        return json_encode($response);
    }
}
