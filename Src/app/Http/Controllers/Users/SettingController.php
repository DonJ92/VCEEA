<?php
namespace App\Http\Controllers\Users;


use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $major_list = $this->getMajorListFromID();

        $data['major_id_list'] = explode(',', Auth::user()->major_id_list);
        $data['major_list'] = $major_list;

        $this->registerLog(LOG_SETTING_PAGE);

        return view('setting', $data);
    }

    public function updateProfile(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'user_code' => 'required|string|max:12',
            'name' => 'required|string|max:12',
            'birthday' => 'required|date',
            'gender' => 'required|numeric|max:2',
            'id_code' => 'required|string|max:32',
            'email' => 'required|string|max:64',
            'phone' => 'required|string|max:24',
            'major' => 'required',
        ], [
        ], [
            'user_code' => trans('setting.user_code'),
            'name' => trans('setting.name'),
            'birthday' => trans('setting.birthday'),
            'gender' => trans('setting.gender'),
            'id_code' => trans('setting.id_code'),
            'email' => trans('setting.email'),
            'phone' => trans('setting.phone'),
            'major' => trans('setting.major'),
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return redirect()->back()->withInput()->withErrors($errors);
        }

        $major_id_list = implode(",", $data['major']);

        try {
            $account_info = User::where('id', Auth::user()->id)->first();
            if (is_null($account_info))
                return redirect()->back()->withInput()->withErrors(['failed' => trans('setting.edit_failed')]);

            $account_info->user_code = $data['user_code'];
            $account_info->name = $data['name'];
            $account_info->birthday = $data['birthday'];
            $account_info->gender = $data['gender'];
            $account_info->id_code = $data['id_code'];
            $account_info->email = $data['email'];
            $account_info->phone = $data['phone'];
            $account_info->major_id_list = $major_id_list;

            $res = $account_info->save();

            if (!$res)
                return redirect()->back()->withInput()->withErrors(['failed' => trans('setting.edit_failed')]);

        } catch (QueryException $e) {
            return redirect()->back()->withInput()->withErrors(['failed' => trans('setting.edit_failed')]);
        }

        return redirect()->route('setting')->with('success', trans('setting.edit_success'));;

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
            'old_password' => trans('setting.old_password'),
            'password' => trans('setting.new_password'),
            'password_confirmation' => trans('setting.new_password_confirm'),
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return redirect()->back()->withInput()->withErrors($errors);
        }

        if (!Hash::check($data['old_password'], auth()->user()->password))
        {
            return redirect()->back()->withInput()->withErrors(['pwd_failed' => trans('setting.old_password_wrong')]);
        }

        try{
            $account_info = User::where('id', Auth::user()->id)->first();
            if (is_null($account_info))
                return redirect()->back()->withInput()->withErrors(['failed' => trans('setting.pwd_failed')]);

            $account_info->password = Hash::make($data['password']);

            $res = $account_info->save();

            if (!$res)
                return redirect()->back()->withInput()->withErrors(['failed' => trans('setting.pwd_failed')]);

        } catch (QueryException $e) {
            return redirect()->back()->withInput()->withErrors(['pwd_failed' => trans('setting.pwd_failed')]);
        }

        return redirect()->route('setting')->with('pwd_success', trans('setting.pwd_success'));;
    }
}