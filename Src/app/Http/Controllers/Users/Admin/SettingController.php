<?php

namespace App\Http\Controllers\Users\Admin;


use App\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        return view('admin.setting');
    }

    public function updateProfile(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'admin_code' => 'required|string|max:12',
            'name' => 'required|string|max:12',
            'id_code' => 'required|string|max:32',
            'email' => 'required|email|string|max:64',
            'phone' => 'required|string|max:24',
        ], [
        ], [
            'admin_code' => trans('admin_setting.admin_code'),
            'name' => trans('admin_setting.name'),
            'id_code' => trans('admin_setting.id_code'),
            'email' => trans('admin_setting.email'),
            'phone' => trans('admin_setting.phone'),
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return redirect()->back()->withInput()->withErrors($errors);
        }

        try {
            $account_info = Admin::where('id', Auth::user()->id)->first();
            if (is_null($account_info))
                return redirect()->back()->withInput()->withErrors(['failed' => trans('admin_setting.edit_failed')]);

            $account_info->admin_code = $data['admin_code'];
            $account_info->name = $data['name'];
            $account_info->id_code = $data['id_code'];
            $account_info->email = $data['email'];
            $account_info->phone = $data['phone'];

            $res = $account_info->save();

            if (!$res)
                return redirect()->back()->withInput()->withErrors(['failed' => trans('admin_setting.edit_failed')]);

        } catch (QueryException $e) {
            return redirect()->back()->withInput()->withErrors(['failed' => trans('admin_setting.edit_failed')]);
        }

        return redirect()->route('admin.setting')->with('success', trans('admin_setting.edit_success'));;
    }

    public function updatePassword(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'old_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required'
        ], [
        ], [
            'old_password' => trans('admin_setting.old_password'),
            'password' => trans('admin_setting.new_password'),
            'password_confirmation' => trans('admin_setting.new_password_confirm'),
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return redirect()->back()->withInput()->withErrors($errors);
        }

        if (!Hash::check($data['old_password'], auth()->user()->password))
        {
            return redirect()->back()->withInput()->withErrors(['pwd_failed' => trans('admin_setting.old_password_wrong')]);
        }

        try{
            $account_info = Admin::where('id', Auth::user()->id)->first();
            if (is_null($account_info))
                return redirect()->back()->withInput()->withErrors(['failed' => trans('admin_setting.pwd_failed')]);

            $account_info->password = Hash::make($data['password']);

            $res = $account_info->save();

            if (!$res)
                return redirect()->back()->withInput()->withErrors(['failed' => trans('admin_setting.pwd_failed')]);

        } catch (QueryException $e) {
            return redirect()->back()->withInput()->withErrors(['pwd_failed' => trans('admin_setting.pwd_failed')]);
        }

        return redirect()->route('admin.setting')->with('pwd_success', trans('admin_setting.pwd_success'));;
    }
}