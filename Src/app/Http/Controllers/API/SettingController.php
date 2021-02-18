<?php
namespace App\Http\Controllers\API;


use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{
    public function changePwd(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'user_id' => 'required',
            'old_password' => 'required',
            'new_password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'message' => $errors,
                'status' => 'failed'
            ], 400);
        }

        try{
            $account_info = User::where('id', $data['user_id'])->first();
            if (is_null($account_info))
                return response()->json([
                    'status' => 'failed'
                ], 404);

            if (!Hash::check($data['old_password'], $account_info->password))
            {
                return response()->json([
                    'status' => 'failed'
                ], 401);
            }

            $account_info->password = Hash::make($data['new_password']);

            $res = $account_info->save();

            if (!$res)
                return response()->json([
                    'status' => 'failed'
                ], 405);

        } catch (QueryException $e) {
            $error = $e->getMessage();
            return response()->json([
                'message' => $error,
                'status' => 'failed'
            ], 405);
        }

        return response()->json([
            'status' => 'success'
        ], 202);
    }

    public function update(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'user_id' => 'required',
            'user_code' => 'required|string|max:12',
            'name' => 'required|string|max:12',
            'birthday' => 'required|date',
            'gender' => 'required|numeric|max:2',
            'id_code' => 'required|string|max:32',
            'email' => 'required|string|max:64',
            'phone' => 'required|string|max:24',
            'major' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'message' => $errors,
                'status' => 'failed'
            ], 400);
        }

        try {
            $account_info = User::where('id', $data['user_id'])->first();
            if (is_null($account_info))
                return response()->json([
                    'status' => 'failed'
                ], 404);

            $account_info->user_code = $data['user_code'];
            $account_info->name = $data['name'];
            $account_info->birthday = $data['birthday'];
            $account_info->gender = $data['gender'];
            $account_info->id_code = $data['id_code'];
            $account_info->email = $data['email'];
            $account_info->phone = $data['phone'];
            $account_info->major_id_list = $data['major'];

            $res = $account_info->save();

            if (!$res)
                return response()->json([
                    'status' => 'failed'
                ], 405);

        } catch (QueryException $e) {
            $error = $e->getMessage();
            return response()->json([
                'message' => $error,
                'status' => 'failed'
            ], 405);
        }

        return response()->json([
            'status' => 'success'
        ], 202);
    }

    public function initialize(Request $request)
    {
        $batch_list = $this->getBatchListFromID();
        $college_list = $this->getCollegeListFromID();
        $department_list = $this->getDepartmentListFromID();
        $major_list = $this->getMajorListFromID();
        $subject_list = $this->getSubjectListFromID();
        $province_list = $this->getProvinceList();
        $city_list = $this->getCityList();
        $college_type_list = config('constants.college_type');
        $college_property_list = config('constants.college_property');

        $data['batch_list'] = $batch_list;
        $data['college_list'] = $college_list;
        $data['department_list'] = $department_list;
        $data['major_list'] = $major_list;
        $data['subject_list'] = $subject_list;
        $data['province_list'] = $province_list;
        $data['city_list'] = $city_list;
        $data['college_type_list'] = $college_type_list;
        $data['college_property_list'] = $college_property_list;

        return response()->json([
            'data' => $data,
            'status' => 'success'
        ], 202);
    }
}