<?php

namespace App\Http\Controllers\Users\Admin;


use App\Http\Controllers\Controller;
use App\Restriction;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RestrictionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $batch_list = $this->getBatchListFromID();

        $data['batch_list'] = $batch_list;

        return view('admin.restriction', $data);
    }

    public function getRestrictionList(Request $request)
    {
        $name = $request->input('name');
        $batch_title = $request->input('batch_title');
        $batch_code = $request->input('batch_code');
        $description = $request->input('description');
        $status = $request->input('status');

        $restriction_list = array();

        try{
            $query = Restriction::orderby('id', 'desc');

            if (!empty($name))
                $query->where('name', 'like', '%'.$name.'%');
            if (!empty($batch_title))
                $query->where('batch_id_list', 'like', '%'.$batch_title.'%');
            if (!empty($batch_code))
                $query->where('batch_id_list', 'like', '%'.$batch_code.'%');
            if (!empty($description))
                $query->where('description', 'like', '%'.$description.'%');
            if (!empty($status))
                $query->where('status', $status);

            $restriction_list = $query->get()->toArray();

            $batch_list = $this->getBatchListFromID();

            for ($i = 0; $i < count($restriction_list); $i++) {
                $batch_id_list = explode(',', $restriction_list[$i]['batch_id_list']);

                $batch_title = array();
                $batch_code = array();
                foreach ($batch_list as $batch_info) {
                    if (in_array($batch_info['id'], $batch_id_list)) {
                        $batch_title[] = $batch_info['title'];
                        $batch_code[] = $batch_info['code'];
                    }
                }

                $restriction_list[$i]['batch_title'] = implode(',', $batch_title);
                $restriction_list[$i]['batch_code'] = implode(',', $batch_code);
            }

        } catch (QueryException $e) {
            echo json_encode($restriction_list);
            exit;
        }

        echo json_encode($restriction_list);
    }

    public function add()
    {
        $batch_list = $this->getBatchListFromID();

        $data['batch_list'] = $batch_list;

        return view('admin.restriction_add', $data);
    }

    public function addSubmit(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'name' => 'required|max:64',
            'batch' => 'required',
            'description' => 'nullable|string|max:1024',
            'status' => 'required',
        ], [
        ], [
            'name' => trans('admin_restriction.name'),
            'batch' => trans('admin_restriction.batch'),
            'description' => trans('admin_restriction.description'),
            'status' => trans('admin_restriction.status'),
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return redirect()->back()->withInput()->withErrors($errors);
        }

        $batch_id_list = implode(",", $data['batch']);

        try {
            $res = Restriction::insert([
                'name' => $data['name'],
                'batch_id_list' => (string)$batch_id_list,
                'description' => $data['description'],
                'status' => $data['status'],
            ]);

            if ($res)
                return redirect()->route('admin.restriction')->with('success', trans('admin_restriction.add_success'));;

        } catch (QueryException $e) {
            return redirect()->back()->withInput()->withErrors(['failed' => trans('admin_restriction.add_failed')]);
        }

        return redirect()->route('admin.restriction')->with('success', trans('admin_restriction.add_success'));;
    }

    public function edit($id)
    {
        if (is_null($id) || empty($id))
            return redirect()->back()->withErrors(['failed' => trans('admin_restriction.no_info')]);

        try {
            $restriction_info = Restriction::where('id', $id)->first();
            if (is_null($restriction_info))
                return redirect()->back()->withErrors(['failed' => trans('admin_restriction.no_info')]);

            $restriction_info = $restriction_info->toArray();
            $restriction_info['batch'] = explode(',', $restriction_info['batch_id_list']);

        } catch (QueryException $e) {
            return redirect()->back()->withErrors(['failed' => trans('admin_restriction.no_info')]);
        }

        $batch_list = $this->getBatchListFromID();

        $data['batch_list'] = $batch_list;
        $data['restriction_info'] = $restriction_info;
        return view('admin.restriction_edit', $data);
    }

    public function editSubmit(Request $request)
    {
        $data = $request->all();

        if (empty($data['id']))
            return redirect()->back()->withInput()->withErrors(['failed' => trans('admin_restriction.edit_failed')]);

        $validator = Validator::make($data, [
            'name' => 'required|max:64',
            'batch' => 'required',
            'description' => 'nullable|string|max:1024',
            'status' => 'required',
        ], [
        ], [
            'name' => trans('admin_restriction.name'),
            'batch' => trans('admin_restriction.batch'),
            'description' => trans('admin_restriction.description'),
            'status' => trans('admin_restriction.status'),
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return redirect()->back()->withInput()->withErrors($errors);
        }

        $batch_id_list = implode(",", $data['batch']);

        try {
            $restriction_info = Restriction::where('id', $data['id'])->first();

            if (is_null($restriction_info))
                return redirect()->back()->withInput()->withErrors(['failed' => trans('admin_restriction.edit_failed')]);

            $restriction_info->name = $data['name'];
            $restriction_info->batch_id_list = $batch_id_list;
            $restriction_info->description = $data['description'];
            $restriction_info->status = $data['status'];

            $res = $restriction_info->save();

            if (!$res)
                return redirect()->back()->withInput()->withErrors(['failed' => trans('admin_restriction.edit_failed')]);

        } catch (QueryException $e) {
            return redirect()->back()->withInput()->withErrors(['failed' => trans('admin_restriction.edit_failed')]);
        }

        return redirect()->route('admin.restriction')->with('success', trans('admin_restriction.edit_success'));;
    }

    public function delete(Request $request)
    {
        $id = $request->input('id');

        if (is_null($id) || empty($id))
            echo json_encode(false);

        try {
            $res = Restriction::where('id', $id)->delete();

            if (!$res)
                echo json_encode(false);

        } catch (QueryException $e) {
            echo json_encode(false);
        }

        echo json_encode(true);
    }
}