<?php

namespace App\Http\Controllers\Users\Admin;


use App\Batch;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BatchController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        return view('admin.batch');
    }

    public function getBatchList(Request $request)
    {
        $title = $request->input('title');
        $code = $request->input('code');
        $description = $request->input('description');
        $status = $request->input('status');

        $batch_list = array();

        try{
            $query = Batch::orderby('id', 'desc');

            if (!empty($title))
                $query->where('tbl_batch.title', 'like', '%'.$title.'%');
            if (!empty($code))
                $query->where('tbl_batch.code', 'like', '%'.$code.'%');
            if (!empty($description))
                $query->where('tbl_batch.description', 'like', '%'.$description.'%');
            if (!empty($status))
                $query->where('tbl_batch.status', $status);

            $batch_list = $query->get()->toArray();

        } catch (QueryException $e) {
            echo json_encode($batch_list);
            exit;
        }

        echo json_encode($batch_list);
    }

    public function add()
    {
        return view('admin.batch_add');
    }

    public function addSubmit(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'title' => 'required|max:64',
            'code' => 'required|unique:tbl_batch,code|max:12',
            'description' => 'nullable|string|max:1024',
            'status' => 'required',
        ], [
        ], [
            'title' => trans('admin_batch.title'),
            'code' => trans('admin_batch.code'),
            'description' => trans('admin_batch.description'),
            'status' => trans('admin_batch.status'),
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return redirect()->back()->withInput()->withErrors($errors);
        }

        try {
            $res = Batch::insert([
                'title' => (string)$data['title'],
                'code' => (string)$data['code'],
                'description' => (string)$data['description'],
                'status' => $data['status'],
            ]);

            if ($res)
                return redirect()->route('admin.batch')->with('success', trans('admin_batch.add_success'));;

        } catch (QueryException $e) {
            return redirect()->back()->withInput()->withErrors(['failed' => trans('admin_batch.add_failed')]);
        }

        return redirect()->route('admin.batch')->with('success', trans('admin_batch.add_success'));;
    }

    public function edit($id)
    {
        if (is_null($id) || empty($id))
            return redirect()->back()->withErrors(['failed' => trans('admin_batch.no_info')]);

        try {
            $batch_info = Batch::where('id', $id)->first();
            if (is_null($batch_info))
                return redirect()->back()->withErrors(['failed' => trans('admin_batch.no_info')]);

            $batch_info = $batch_info->toArray();

        } catch (QueryException $e) {
            return redirect()->back()->withErrors(['failed' => trans('admin_batch.no_info')]);
        }

        $data['batch_info'] = $batch_info;
        return view('admin.batch_edit', $data);
    }

    public function editSubmit(Request $request)
    {
        $data = $request->all();

        if (empty($data['id']))
            return redirect()->back()->withInput()->withErrors(['failed' => trans('admin_batch.edit_failed')]);

        $validator = Validator::make($data, [
            'title' => 'required|max:64',
            'code' => 'required|max:12',
            'description' => 'nullable|string|max:1024',
            'status' => 'required',
        ], [
        ], [
            'title' => trans('admin_batch.title'),
            'code' => trans('admin_batch.code'),
            'description' => trans('admin_batch.description'),
            'status' => trans('admin_batch.status'),
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return redirect()->back()->withInput()->withErrors($errors);
        }

        try {
            $batch_info = Batch::where('id', $data['id'])->first();

            if (is_null($batch_info))
                return redirect()->back()->withInput()->withErrors(['failed' => trans('admin_batch.edit_failed')]);

            $batch_info->title = $data['title'];
            $batch_info->code = $data['code'];
            $batch_info->description = $data['description'];
            $batch_info->status = $data['status'];

            $res = $batch_info->save();

            if (!$res)
                return redirect()->back()->withInput()->withErrors(['failed' => trans('admin_batch.edit_failed')]);

        } catch (QueryException $e) {
            return redirect()->back()->withInput()->withErrors(['failed' => trans('admin_batch.edit_failed')]);
        }

        return redirect()->route('admin.batch')->with('success', trans('admin_batch.edit_success'));;
    }

    public function delete(Request $request)
    {
        $id = $request->input('id');

        if (is_null($id) || empty($id))
            echo json_encode(false);

        try {
            $res = Batch::where('id', $id)->delete();

            if (!$res)
                echo json_encode(false);

        } catch (QueryException $e) {
            echo json_encode(false);
        }

        echo json_encode(true);
    }
}