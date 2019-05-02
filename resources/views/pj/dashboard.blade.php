@extends('layouts.master')
@extends('pj.nav')
@section('title', 'Dashboard')

@section('contents')
        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-6">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <span class="label label-success pull-right">All</span>
                            <h5>Katalog</h5>
                        </div>
                        <div class="ibox-content">
                            <h1 class="no-margins">40 886,200</h1>
                            <small>Total Katalog</small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <span class="label label-info pull-right">All</span>
                            <h5>Pengguna</h5>
                        </div>
                        <div class="ibox-content">
                            <h1 class="no-margins">275,800</h1>
                            <small>Total Pengguna</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection
        
