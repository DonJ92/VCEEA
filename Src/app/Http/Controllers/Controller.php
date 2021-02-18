<?php

namespace App\Http\Controllers;

use App\Batch;
use App\City;
use App\College;
use App\Department;
use App\Log;
use App\Major;
use App\Province;
use App\Subject;
use App\User;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function getProvinceList($id=null)
    {
        $province_list = array();
        try {
            $province_list = Province::orderby('id', 'asc');

            if (isset($id) && !empty($id))
                $province_list = $province_list->where('id', $id);

            $province_list = $province_list->get()->toArray();
        } catch (QueryException $e) {
            return $province_list;
        }

        return $province_list;
    }

    protected function getCityList($id=null, $province_id=null)
    {
        $city_list = array();
        try {
            $city_list = City::orderby('id', 'asc');

            if (isset($id) && !empty($id))
                $city_list = $city_list->where('id', $id);

            if (isset($province_id) && !empty($province_id))
                $city_list = $city_list->where('province_id', $province_id);

            $city_list = $city_list->get()->toArray();
        } catch (QueryException $e) {
            return $city_list;
        }

        return $city_list;
    }

    protected function getBatchListFromID($id=null)
    {
        $batch_list = array();
        try {
            $query = Batch::where('status', ACTIVE)->orderby('id', 'asc');

            if (isset($id) && !empty($id))
                $query = $query->where('id', $id);

            $batch_list = $query->get()->toArray();

        } catch (QueryException $e) {
            return $batch_list;
        }

        return $batch_list;
    }

    protected function getCollegeListFromID($id=null)
    {
        $college_list = array();
        try {
            $query = College::where('status', ACTIVE)->orderby('id', 'asc');

            if (isset($id) && !empty($id))
                $query = $query->where('id', $id);

            $college_list = $query->get()->toArray();

        } catch (QueryException $e) {
            return $college_list;
        }

        return $college_list;
    }

    protected function getDepartmentListFromID($id=null)
    {
        $department_list = array();
        try {
            $query = Department::where('status', ACTIVE)->orderby('id', 'asc');

            if (isset($id) && !empty($id))
                $query = $query->where('id', $id);

            $department_list = $query->get()->toArray();

        } catch (QueryException $e) {
            return $department_list;
        }

        return $department_list;
    }

    protected function getMajorListFromID($id=null)
    {
        $major_list = array();
        try {
            $query = Major::where('status', ACTIVE)->orderby('id', 'asc');

            if (isset($id) && !empty($id))
                $query = $query->where('id', $id);

            $major_list = $query->get()->toArray();

        } catch (QueryException $e) {
            return $major_list;
        }

        return $major_list;
    }

    protected function getSubjectListFromID($id=null)
    {
        $subject_list = array();
        try {
            $query = Subject::where('status', ACTIVE)->orderby('id', 'asc');

            if (isset($id) && !empty($id))
                $query = $query->where('id', $id);

            $subject_list = $query->get()->toArray();

        } catch (QueryException $e) {
            return $subject_list;
        }

        return $subject_list;
    }

    protected function registerLog($type, $user_id=null)
    {
        if (is_null($type) || empty($type))
            return false;

        if (is_null($user_id))
            $id = Auth::user()->id;
        else
            $id = $user_id;

        try {
            $res = Log::insert([
                'user_id' => $id,
                'log_type' => $type
            ]);

            if (!$res)
                return false;

        } catch (QueryException $e) {
            return false;
        }

        return true;
    }

    protected function getUserInfo($user_code)
    {
        $user = array();
        try{
            $user_info = User::where('user_code', $user_code)->first();
            if (is_null($user_info))
                return $user;

            $user = $user_info->toArray();
        } catch (QueryException $e) {
            return $user;
        }

        return $user;
    }
}
