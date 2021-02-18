<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showRegistrationForm()
    {
        $major_list = $this->getMajorListFromID();

        $data['major_list'] = $major_list;

        return view('auth.register', $data);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'user_code' => ['required', 'unique:tbl_user,user_code','string', 'max:12'],
            'name' => ['required', 'string', 'max:12'],
            'birthday' => ['required', 'date', ],
            'gender' => ['required', 'numeric', 'max:2'],
            'id_code' => ['required', 'string', 'max:32'],
            'email' => ['required', 'string', 'email', 'max:64'],
            'phone' => ['required', 'string', 'max:24'],
            'major' => ['required'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            ], [],
            [
                'user_code' => trans('register.user_code'),
                'name' => trans('register.name'),
                'birthday' => trans('register.birthday'),
                'gender' => trans('register.gender'),
                'id_code' => trans('register.id_code'),
                'email' => trans('register.email'),
                'phone' => trans('register.phone'),
                'major' => trans('register.major'),
                'password' => trans('register.password'),
        ]);
    }

    public function username()
    {
        return 'user_code';
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $major_id_list = implode(",", $data['major']);
        return User::create([
            'user_code' => $data['user_code'],
            'name' => $data['name'],
            'birthday' => $data['birthday'],
            'gender' => $data['gender'],
            'id_code' => $data['id_code'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'major_id_list' => $major_id_list,
            'password' => Hash::make($data['password']),
        ]);
    }
}
