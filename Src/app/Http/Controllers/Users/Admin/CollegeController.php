<?php
namespace App\Http\Controllers\Users\Admin;


use App\College;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CollegeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $province_list = $this->getProvinceList();
        $city_list = $this->getCityList();

        $type_list = config('constants.college_type');
        $property_list = config('constants.college_property');

        $data['province_list'] = $province_list;
        $data['city_list'] = $city_list;
        $data['type_list'] = $type_list;
        $data['property_list'] = $property_list;
        return view('admin.college', $data);
    }

    public function getCollegeList(Request $request)
    {
        $name = $request->input('name');
        $code = $request->input('code');
        $national_code = $request->input('national_code');
        $addr_province = $request->input('addr_province');
        $addr_city = $request->input('addr_city');
        $type = $request->input('type');
        $property = $request->input('property');
        $description = $request->input('description');
        $status = $request->input('status');

        $college_list = array();

        try{
            $query = College::leftjoin('tbl_province', 'tbl_college.addr_province', '=', 'tbl_province.id')
                ->leftjoin('tbl_city', 'tbl_college.addr_city', '=', 'tbl_city.id')
                ->select('tbl_college.*', 'tbl_province.name as province_name', 'tbl_city.name as city_name');

            if (!empty($name))
                $query->where('tbl_college.name', 'like', '%'.$name.'%');
            if (!empty($code))
                $query->where('tbl_college.code', 'like', '%'.$code.'%');
            if (!empty($national_code))
                $query->where('tbl_college.national_code', 'like', '%'.$national_code.'%');
            if (!empty($addr_province))
                $query->where('tbl_college.addr_province', $addr_province);
            if (!empty($addr_city))
                $query->where('tbl_college.addr_city', $addr_city);
            if (!empty($type))
                $query->where('tbl_college.type', $type);
            if (!empty($property))
                $query->where('tbl_college.property', $property);
            if (!empty($description))
                $query->where('tbl_college.description', 'like', '%'.$description.'%');
            if (!empty($status))
                $query->where('tbl_college.status', $status);

            $college_list = $query->get()->toArray();

        } catch (QueryException $e) {
            echo json_encode($college_list);
            exit;
        }

        $type_list = config('constants.college_type');
        $property_list = config('constants.college_property');
        for ($i = 0; $i < count($college_list); $i++) {
            $college_list[$i]['type'] = $type_list[$college_list[$i]['type']-1]['type'];
            $college_list[$i]['property'] = $property_list[$college_list[$i]['property']-1]['property'];
        }

        echo json_encode($college_list);
    }

    public function add()
    {
        $province_list = $this->getProvinceList();
        $city_list = $this->getCityList();

        $type_list = config('constants.college_type');
        $property_list = config('constants.college_property');

        $data['province_list'] = $province_list;
        $data['city_list'] = $city_list;
        $data['type_list'] = $type_list;
        $data['property_list'] = $property_list;
        return view('admin.college_add', $data);
    }

    public function addSubmit(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'name' => 'required|max:128',
            'code' => 'required|unique:tbl_college,code|max:12',
            'national_code' => 'required|unique:tbl_college,national_code|max:12',
            'addr_province' => 'required',
            'addr_city' => 'required',
            'addr_detail' => 'nullable|string|max:256',
            'type' => 'required',
            'property' => 'required',
            'site_url' => 'nullable|string|max:256',
            'description' => 'nullable|string|max:1024',
            'status' => 'required',
            ], [
        ], [
            'name' => trans('admin_college.name'),
            'code' => trans('admin_college.code'),
            'national_code' => trans('admin_college.national_code'),
            'addr_province' => trans('admin_college.addr_province'),
            'addr_city' => trans('admin_college.addr_city'),
            'addr_detail' => trans('admin_college.addr_detail'),
            'type' => trans('admin_college.type'),
            'property' => trans('admin_college.property'),
            'site_url' => trans('admin_college.site_url'),
            'description' => trans('admin_college.description'),
            'status' => trans('admin_college.status'),
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return redirect()->back()->withInput()->withErrors($errors);
        }

        try {
            $res = College::insert([
                'name' => (string)$data['name'],
                'code' => (string)$data['code'],
                'national_code' => (string)$data['national_code'],
                'addr_province' => $data['addr_province'],
                'addr_city' => $data['addr_city'],
                'addr_detail' => (string)$data['addr_detail'],
                'type' => $data['type'],
                'property' => $data['property'],
                'site_url' => (string)$data['site_url'],
                'description' => (string)$data['description'],
                'status' => $data['status'],
            ]);

            if ($res)
                return redirect()->route('admin.college')->with('success', trans('admin_college.add_success'));;

        } catch (QueryException $e) {
            return redirect()->back()->withInput()->withErrors(['failed' => trans('admin_college.add_failed')]);
        }

        return redirect()->route('admin.college')->with('success', trans('admin_college.add_success'));;
    }

    public function edit($id)
    {
        if (is_null($id) || empty($id))
            return redirect()->back()->withErrors(['failed' => trans('admin_college.no_info')]);

        try {
            $college_info = College::where('id', $id)->first();
            if (is_null($college_info))
                return redirect()->back()->withErrors(['failed' => trans('admin_college.no_info')]);

            $college_info = $college_info->toArray();

        } catch (QueryException $e) {
            return redirect()->back()->withErrors(['failed' => trans('admin_college.no_info')]);
        }

        $province_list = $this->getProvinceList();
        $city_list = $this->getCityList();

        $type_list = config('constants.college_type');
        $property_list = config('constants.college_property');

        $data['province_list'] = $province_list;
        $data['city_list'] = $city_list;
        $data['type_list'] = $type_list;
        $data['property_list'] = $property_list;
        $data['college_info'] = $college_info;
        return view('admin.college_edit', $data);
    }

    public function editSubmit(Request $request)
    {
        $data = $request->all();

        if (empty($data['id']))
            return redirect()->back()->withInput()->withErrors(['failed' => trans('admin_college.edit_failed')]);

        $validator = Validator::make($data, [
            'name' => 'required|max:128',
            'code' => 'required|max:12',
            'national_code' => 'required|max:12',
            'addr_province' => 'required',
            'addr_city' => 'required',
            'addr_detail' => 'nullable|string|max:256',
            'type' => 'required',
            'property' => 'required',
            'site_url' => 'nullable|string|max:256',
            'description' => 'nullable|string|max:1024',
            'status' => 'required',
        ], [
        ], [
            'name' => trans('admin_college.name'),
            'code' => trans('admin_college.code'),
            'national_code' => trans('admin_college.national_code'),
            'addr_province' => trans('admin_college.addr_province'),
            'addr_city' => trans('admin_college.addr_city'),
            'addr_detail' => trans('admin_college.addr_detail'),
            'type' => trans('admin_college.type'),
            'property' => trans('admin_college.property'),
            'site_url' => trans('admin_college.site_url'),
            'description' => trans('admin_college.description'),
            'status' => trans('admin_college.status'),
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return redirect()->back()->withInput()->withErrors($errors);
        }

        try {
            $college_info = College::where('id', $data['id'])->first();

            if (is_null($college_info))
                return redirect()->back()->withInput()->withErrors(['failed' => trans('admin_college.edit_failed')]);

            $college_info->name = $data['name'];
            $college_info->code = $data['code'];
            $college_info->national_code = $data['national_code'];
            $college_info->addr_province = $data['addr_province'];
            $college_info->addr_city = $data['addr_city'];
            $college_info->addr_detail = $data['addr_detail'];
            $college_info->type = $data['type'];
            $college_info->property = $data['property'];
            $college_info->site_url = $data['site_url'];
            $college_info->description = $data['description'];
            $college_info->status = $data['status'];

            $res = $college_info->save();

        } catch (QueryException $e) {
            return redirect()->back()->withInput()->withErrors(['failed' => trans('admin_college.edit_failed')]);
        }

        return redirect()->route('admin.college')->with('success', trans('admin_college.edit_success'));;
    }

    public function delete(Request $request)
    {
        $id = $request->input('id');

        if (is_null($id) || empty($id))
            echo json_encode(false);

        try {
            $res = College::where('id', $id)->delete();

            if (!$res)
                echo json_encode(false);

        } catch (QueryException $e) {
            echo json_encode(false);
        }

        echo json_encode(true);
    }
}