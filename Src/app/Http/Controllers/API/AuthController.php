<?php

namespace App\Http\Controllers\API;


use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only(['user_code', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return response()->json([
                'status' => 'failed'
            ], 401);
        }

        $user = auth()->user();
        $user->gender = (int)$user->gender;
        $user->birthday = date('Y-m-d', strtotime($user->birthday));
        return response()->json([
            'data' => ['user' => $user],
            'status' => 'success'
        ], 200);
    }

    public function register(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'user_code' => ['required', 'unique:tbl_user,user_code','string', 'max:12'],
            'name' => ['required', 'string', 'max:12'],
            'birthday' => ['required', 'date', ],
            'gender' => ['required', 'numeric', 'max:2'],
            'id_code' => ['required', 'string', 'max:32'],
            'email' => ['required', 'string', 'email', 'max:64'],
            'phone' => ['required', 'string', 'max:24'],
            'major' => ['required'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'message' => $errors,
                'status' => 'failed'
            ], 400);
        }

        try {
            $user = User::create([
                'user_code' => $data['user_code'],
                'name' => $data['name'],
                'birthday' => $data['birthday'],
                'gender' => $data['gender'],
                'id_code' => $data['id_code'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'major_id_list' => $data['major'],
                'password' => Hash::make($data['password']),
            ]);

            $user->gender = (int)$user->gender;
            $user->birthday = date('Y-m-d', strtotime($user->birthday));
        } catch (QueryException $e) {
            $error = $e->getMessage();
            return response()->json([
                'message' => $error,
                'status' => 'failed'
            ], 405);
        }

        return response()->json([
            'data' => ['user' => $user],
            'status' => 'success'
        ], 201);
    }
}