<?php

namespace App\Http\Controllers\Users\Admin;

use App\City;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        return view('admin.home');
    }

    public function getCity(Request $request)
    {
        $province_id = $request->input('province_id');

        $city_list = array();
        try{
            $city_list = $this->getCityList(null, $province_id);
        } catch (QueryException $e) {
            echo json_encode($city_list);
            exit;
        }

        echo json_encode($city_list);
    }
}
