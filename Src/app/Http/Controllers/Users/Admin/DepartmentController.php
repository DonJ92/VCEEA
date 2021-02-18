<?php

namespace App\Http\Controllers\Users\Admin;


use App\Department;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DepartmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        return view('admin.department');
    }

    public function getDepartmentList(Request $request)
    {
        $name = $request->input('name');
        $code = $request->input('code');
        $description = $request->input('description');
        $status = $request->input('status');

        $department_list = array();

        try{
            $query = Department::orderby('id', 'desc');

            if (!empty($name))
                $query->where('tbl_department.name', 'like', '%'.$name.'%');
            if (!empty($code))
                $query->where('tbl_department.code', 'like', '%'.$code.'%');
            if (!empty($description))
                $query->where('tbl_department.description', 'like', '%'.$description.'%');
            if (!empty($status))
                $query->where('tbl_department.status', $status);

            $department_list = $query->get()->toArray();

        } catch (QueryException $e) {
            echo json_encode($department_list);
            exit;
        }

        echo json_encode($department_list);
    }

    public function add()
    {
        return view('admin.department_add');
    }

    public function addSubmit(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'name' => 'required|max:64',
            'code' => 'required|unique:tbl_department,code|max:12',
            'description' => 'nullable|string|max:1024',
            'status' => 'required',
        ], [
        ], [
            'name' => trans('admin_department.name'),
            'code' => trans('admin_department.code'),
            'description' => trans('admin_department.description'),
            'status' => trans('admin_department.status'),
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return redirect()->back()->withInput()->withErrors($errors);
        }

        try {
            $res = Department::insert([
                'name' => (string)$data['name'],
                'code' => (string)$data['code'],
                'description' => (string)$data['description'],
                'status' => $data['status'],
            ]);

            if ($res)
                return redirect()->route('admin.department')->with('success', trans('admin_department.add_success'));;

        } catch (QueryException $e) {
            return redirect()->back()->withInput()->withErrors(['failed' => trans('admin_department.add_failed')]);
        }

        return redirect()->route('admin.department')->with('success', trans('admin_department.add_success'));;
    }

    public function edit($id)
    {
        if (is_null($id) || empty($id))
            return redirect()->back()->withErrors(['failed' => trans('admin_department.no_info')]);

        try {
            $department_info = Department::where('id', $id)->first();
            if (is_null($department_info))
                return redirect()->back()->withErrors(['failed' => trans('admin_department.no_info')]);

            $department_info = $department_info->toArray();

        } catch (QueryException $e) {
            return redirect()->back()->withErrors(['failed' => trans('admin_department.no_info')]);
        }

        $data['department_info'] = $department_info;
        return view('admin.department_edit', $data);
    }

    public function editSubmit(Request $request)
    {
        $data = $request->all();

        if (empty($data['id']))
            return redirect()->back()->withInput()->withErrors(['failed' => trans('admin_department.edit_failed')]);

        $validator = Validator::make($data, [
            'name' => 'required|max:64',
            'code' => 'required|max:12',
            'description' => 'nullable|string|max:1024',
            'status' => 'required',
        ], [
        ], [
            'name' => trans('admin_department.name'),
            'code' => trans('admin_department.code'),
            'description' => trans('admin_department.description'),
            'status' => trans('admin_department.status'),
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return redirect()->back()->withInput()->withErrors($errors);
        }

        try {
            $department_info = Department::where('id', $data['id'])->first();

            if (is_null($department_info))
                return redirect()->back()->withInput()->withErrors(['failed' => trans('admin_department.edit_failed')]);

            $department_info->name = $data['name'];
            $department_info->code = $data['code'];
            $department_info->description = $data['description'];
            $department_info->status = $data['status'];

            $res = $department_info->save();

            if (!$res)
                return redirect()->back()->withInput()->withErrors(['failed' => trans('admin_department.edit_failed')]);

        } catch (QueryException $e) {
            return redirect()->back()->withInput()->withErrors(['failed' => trans('admin_department.edit_failed')]);
        }

        return redirect()->route('admin.department')->with('success', trans('admin_department.edit_success'));;
    }

    public function delete(Request $request)
    {
        $id = $request->input('id');

        if (is_null($id) || empty($id))
            echo json_encode(false);

        try {
            $res = Department::where('id', $id)->delete();

            if (!$res)
                echo json_encode(false);

        } catch (QueryException $e) {
            echo json_encode(false);
        }

        echo json_encode(true);
    }
}