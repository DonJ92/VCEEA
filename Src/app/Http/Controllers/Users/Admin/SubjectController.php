<?php

namespace App\Http\Controllers\Users\Admin;


use App\Http\Controllers\Controller;
use App\Subject;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        return view('admin.subject');
    }

    public function getSubjectList(Request $request)
    {
        $name = $request->input('name');
        $code = $request->input('code');
        $description = $request->input('description');
        $status = $request->input('status');

        $subject_list = array();

        try{
            $query = Subject::orderby('id', 'desc');

            if (!empty($name))
                $query->where('tbl_subject.name', 'like', '%'.$name.'%');
            if (!empty($code))
                $query->where('tbl_subject.code', 'like', '%'.$code.'%');
            if (!empty($description))
                $query->where('tbl_subject.description', 'like', '%'.$description.'%');
            if (!empty($status))
                $query->where('tbl_subject.status', $status);

            $subject_list = $query->get()->toArray();

        } catch (QueryException $e) {
            echo json_encode($subject_list);
            exit;
        }

        echo json_encode($subject_list);
    }

    public function add()
    {
        return view('admin.subject_add');
    }

    public function addSubmit(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'name' => 'required|max:64',
            'code' => 'required|unique:tbl_subject,code|max:12',
            'description' => 'nullable|string|max:1024',
            'status' => 'required',
        ], [
        ], [
            'name' => trans('admin_subject.name'),
            'code' => trans('admin_subject.code'),
            'description' => trans('admin_subject.description'),
            'status' => trans('admin_subject.status'),
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return redirect()->back()->withInput()->withErrors($errors);
        }

        try {
            $res = Subject::insert([
                'name' => (string)$data['name'],
                'code' => (string)$data['code'],
                'description' => (string)$data['description'],
                'status' => $data['status'],
            ]);

            if ($res)
                return redirect()->route('admin.subject')->with('success', trans('admin_subject.add_success'));;

        } catch (QueryException $e) {
            return redirect()->back()->withInput()->withErrors(['failed' => trans('admin_subject.add_failed')]);
        }

        return redirect()->route('admin.subject')->with('success', trans('admin_subject.add_success'));;
    }

    public function edit($id)
    {
        if (is_null($id) || empty($id))
            return redirect()->back()->withErrors(['failed' => trans('admin_subject.no_info')]);

        try {
            $subject_info = Subject::where('id', $id)->first();
            if (is_null($subject_info))
                return redirect()->back()->withErrors(['failed' => trans('admin_subject.no_info')]);

            $subject_info = $subject_info->toArray();

        } catch (QueryException $e) {
            return redirect()->back()->withErrors(['failed' => trans('admin_subject.no_info')]);
        }

        $data['subject_info'] = $subject_info;
        return view('admin.subject_edit', $data);
    }

    public function editSubmit(Request $request)
    {
        $data = $request->all();

        if (empty($data['id']))
            return redirect()->back()->withInput()->withErrors(['failed' => trans('admin_subject.edit_failed')]);

        $validator = Validator::make($data, [
            'name' => 'required|max:64',
            'code' => 'required|max:12',
            'description' => 'nullable|string|max:1024',
            'status' => 'required',
        ], [
        ], [
            'name' => trans('admin_subject.name'),
            'code' => trans('admin_subject.code'),
            'description' => trans('admin_subject.description'),
            'status' => trans('admin_subject.status'),
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return redirect()->back()->withInput()->withErrors($errors);
        }

        try {
            $subject_info = Subject::where('id', $data['id'])->first();

            if (is_null($subject_info))
                return redirect()->back()->withInput()->withErrors(['failed' => trans('admin_subject.edit_failed')]);

            $subject_info->name = $data['name'];
            $subject_info->code = $data['code'];
            $subject_info->description = $data['description'];
            $subject_info->status = $data['status'];

            $res = $subject_info->save();

            if (!$res)
                return redirect()->back()->withInput()->withErrors(['failed' => trans('admin_subject.edit_failed')]);

        } catch (QueryException $e) {
            return redirect()->back()->withInput()->withErrors(['failed' => trans('admin_subject.edit_failed')]);
        }

        return redirect()->route('admin.subject')->with('success', trans('admin_subject.edit_success'));;
    }

    public function delete(Request $request)
    {
        $id = $request->input('id');

        if (is_null($id) || empty($id))
            echo json_encode(false);

        try {
            $res = Subject::where('id', $id)->delete();

            if (!$res)
                echo json_encode(false);

        } catch (QueryException $e) {
            echo json_encode(false);
        }

        echo json_encode(true);
    }
}