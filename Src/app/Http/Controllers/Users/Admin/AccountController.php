<?php

namespace App\Http\Controllers\Users\Admin;


use App\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        return view('admin.account');
    }

    public function getAccountList(Request $request)
    {
        $admin_code = $request->input('admin_code');
        $name = $request->input('name');
        $id_code = $request->input('id_code');
        $email = $request->input('email');
        $phone = $request->input('phone');

        $account_list = array();

        try{
            $query = Admin::where('id', '!=', Auth::user()->id)
                ->orderby('id', 'desc');

            if (!empty($admin_code))
                $query->where('admin_code', 'like', '%'.$admin_code.'%');
            if (!empty($name))
                $query->where('name', 'like', '%'.$name.'%');
            if (!empty($id_code))
                $query->where('id_code', 'like', '%'.$id_code.'%');
            if (!empty($email))
                $query->where('email', 'like', '%'.$email.'%');
            if (!empty($phone))
                $query->where('phone', 'like', '%'.$phone.'%');

            $account_list = $query->get()->toArray();

        } catch (QueryException $e) {
            echo json_encode($account_list);
            exit;
        }

        echo json_encode($account_list);
    }

    public function add()
    {
        return view('admin.account_add');
    }

    public function addSubmit(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'admin_code' => 'required|unique:tbl_admin,admin_code|max:12',
            'name' => 'required|max:12',
            'id_code' => 'required|string|max:32',
            'email' => 'required|email|string|max:64',
            'phone' => 'required|string|max:24',
            'role' => 'required',
            'password' => 'required|string|min:8|max:64|confirmed'
        ], [
        ], [
            'admin_code' => trans('admin_account.admin_code'),
            'name' => trans('admin_account.name'),
            'id_code' => trans('admin_account.id_code'),
            'email' => trans('admin_account.email'),
            'phone' => trans('admin_account.phone'),
            'role' => trans('admin_account.role'),
            'password' => trans('admin_account.password'),
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return redirect()->back()->withInput()->withErrors($errors);
        }

        try {
            $res = Admin::insert([
                'admin_code' => $data['admin_code'],
                'name' => $data['name'],
                'id_code' => $data['id_code'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'password' => Hash::make($data['password']),
                'role' => $data['role']
            ]);

            if ($res)
                return redirect()->route('admin.account')->with('success', trans('admin_account.add_success'));;

        } catch (QueryException $e) {
            return redirect()->back()->withInput()->withErrors(['failed' => trans('admin_account.add_failed')]);
        }

        return redirect()->route('admin.account')->with('success', trans('admin_account.add_success'));;
    }

    public function edit($id)
    {
        if (is_null($id) || empty($id))
            return redirect()->back()->withErrors(['failed' => trans('admin_account.no_info')]);

        try {
            $account_info = Admin::where('id', $id)->first();
            if (is_null($account_info))
                return redirect()->back()->withErrors(['failed' => trans('admin_account.no_info')]);

            $account_info = $account_info->toArray();

        } catch (QueryException $e) {
            return redirect()->back()->withErrors(['failed' => trans('admin_account.no_info')]);
        }

        $data['account_info'] = $account_info;
        return view('admin.account_edit', $data);
    }

    public function editSubmit(Request $request)
    {
        $data = $request->all();

        if (empty($data['id']))
            return redirect()->back()->withInput()->withErrors(['failed' => trans('admin_account.edit_failed')]);

        $validator = Validator::make($data, [
            'admin_code' => 'required|max:12',
            'name' => 'required|max:12',
            'id_code' => 'required|string|max:32',
            'email' => 'required|email|string|max:64',
            'phone' => 'required|string|max:24',
            'role' => 'required',
        ], [
        ], [
            'admin_code' => trans('admin_account.admin_code'),
            'name' => trans('admin_account.name'),
            'id_code' => trans('admin_account.id_code'),
            'email' => trans('admin_account.email'),
            'phone' => trans('admin_account.phone'),
            'role' => trans('admin_account.role'),
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return redirect()->back()->withInput()->withErrors($errors);
        }

        try {
            $account_info = Admin::where('id', $data['id'])->first();

            if (is_null($account_info))
                return redirect()->back()->withInput()->withErrors(['failed' => trans('admin_account.edit_failed')]);

            $account_info->admin_code = $data['admin_code'];
            $account_info->name = $data['name'];
            $account_info->id_code = $data['id_code'];
            $account_info->email = $data['email'];
            $account_info->phone = $data['phone'];
            $account_info->role = $data['role'];

            $res = $account_info->save();

            if (!$res)
                return redirect()->back()->withInput()->withErrors(['failed' => trans('admin_account.edit_failed')]);

        } catch (QueryException $e) {
            return redirect()->back()->withInput()->withErrors(['failed' => trans('admin_account.edit_failed')]);
        }

        return redirect()->route('admin.account')->with('success', trans('admin_account.edit_success'));;
    }

    public function delete(Request $request)
    {
        $id = $request->input('id');

        if (is_null($id) || empty($id))
            echo json_encode(false);

        try {
            $res = Admin::where('id', $id)->delete();

            if (!$res)
                echo json_encode(false);

        } catch (QueryException $e) {
            echo json_encode(false);
        }

        echo json_encode(true);
    }

}