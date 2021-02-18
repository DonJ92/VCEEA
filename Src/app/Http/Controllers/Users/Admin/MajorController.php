<?php

namespace App\Http\Controllers\Users\Admin;


use App\Http\Controllers\Controller;
use App\Major;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MajorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        return view('admin.major');
    }

    public function getMajorList(Request $request)
    {
        $name = $request->input('name');
        $code = $request->input('code');
        $national_code = $request->input('national_code');
        $description = $request->input('description');
        $status = $request->input('status');

        $major_list = array();

        try{
            $query = Major::orderby('id', 'desc');

            if (!empty($name))
                $query->where('tbl_major.name', 'like', '%'.$name.'%');
            if (!empty($code))
                $query->where('tbl_major.code', 'like', '%'.$code.'%');
            if (!empty($national_code))
                $query->where('tbl_major.national_code', 'like', '%'.$national_code.'%');
            if (!empty($description))
                $query->where('tbl_major.description', 'like', '%'.$description.'%');
            if (!empty($status))
                $query->where('tbl_major.status', $status);

            $major_list = $query->get()->toArray();

        } catch (QueryException $e) {
            echo json_encode($major_list);
            exit;
        }

        echo json_encode($major_list);
    }

    public function add()
    {
        return view('admin.major_add');
    }

    public function addSubmit(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'name' => 'required|max:64',
            'code' => 'required|unique:tbl_major,code|max:12',
            'national_code' => 'required|unique:tbl_major,national_code|max:12',
            'description' => 'nullable|string|max:1024',
            'status' => 'required',
        ], [
        ], [
            'name' => trans('admin_major.name'),
            'code' => trans('admin_major.code'),
            'national_code' => trans('admin_major.national_code'),
            'description' => trans('admin_major.description'),
            'status' => trans('admin_major.status'),
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return redirect()->back()->withInput()->withErrors($errors);
        }

        try {
            $res = Major::insert([
                'name' => (string)$data['name'],
                'code' => (string)$data['code'],
                'national_code' => (string)$data['national_code'],
                'description' => (string)$data['description'],
                'status' => $data['status'],
            ]);

            if ($res)
                return redirect()->route('admin.major')->with('success', trans('admin_major.add_success'));;

        } catch (QueryException $e) {
            return redirect()->back()->withInput()->withErrors(['failed' => trans('admin_major.add_failed')]);
        }

        return redirect()->route('admin.major')->with('success', trans('admin_major.add_success'));;
    }

    public function edit($id)
    {
        if (is_null($id) || empty($id))
            return redirect()->back()->withErrors(['failed' => trans('admin_major.no_info')]);

        try {
            $major_info = Major::where('id', $id)->first();
            if (is_null($major_info))
                return redirect()->back()->withErrors(['failed' => trans('admin_major.no_info')]);

            $major_info = $major_info->toArray();

        } catch (QueryException $e) {
            return redirect()->back()->withErrors(['failed' => trans('admin_major.no_info')]);
        }

        $data['major_info'] = $major_info;
        return view('admin.major_edit', $data);
    }

    public function editSubmit(Request $request)
    {
        $data = $request->all();

        if (empty($data['id']))
            return redirect()->back()->withInput()->withErrors(['failed' => trans('admin_major.edit_failed')]);

        $validator = Validator::make($data, [
            'name' => 'required|max:64',
            'code' => 'required|max:12',
            'national_code' => 'required|max:12',
            'description' => 'nullable|string|max:1024',
            'status' => 'required',
        ], [
        ], [
            'name' => trans('admin_major.name'),
            'code' => trans('admin_major.code'),
            'national_code' => trans('admin_major.national_code'),
            'description' => trans('admin_major.description'),
            'status' => trans('admin_major.status'),
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return redirect()->back()->withInput()->withErrors($errors);
        }

        try {
            $major_info = Major::where('id', $data['id'])->first();

            if (is_null($major_info))
                return redirect()->back()->withInput()->withErrors(['failed' => trans('admin_major.edit_failed')]);

            $major_info->name = $data['name'];
            $major_info->code = $data['code'];
            $major_info->national_code = $data['national_code'];
            $major_info->description = $data['description'];
            $major_info->status = $data['status'];

            $res = $major_info->save();

            if (!$res)
                return redirect()->back()->withInput()->withErrors(['failed' => trans('admin_major.edit_failed')]);

        } catch (QueryException $e) {
            return redirect()->back()->withInput()->withErrors(['failed' => trans('admin_major.edit_failed')]);
        }

        return redirect()->route('admin.major')->with('success', trans('admin_major.edit_success'));;
    }

    public function delete(Request $request)
    {
        $id = $request->input('id');

        if (is_null($id) || empty($id))
            echo json_encode(false);

        try {
            $res = Major::where('id', $id)->delete();

            if (!$res)
                echo json_encode(false);

        } catch (QueryException $e) {
            echo json_encode(false);
        }

        echo json_encode(true);
    }
}