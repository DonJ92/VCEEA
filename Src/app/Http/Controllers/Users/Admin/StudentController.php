<?php

namespace App\Http\Controllers\Users\Admin;


use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index(Request $request)
    {
        return view('admin.student');
    }

    public function getStudentList(Request $request)
    {
        $user_code = $request->input('user_code');
        $name = $request->input('name');
        $gender = $request->input('gender');
        $birthday = $request->input('birthday');
        $email = $request->input('email');
        $phone = $request->input('phone');
        $id_code = $request->input('id_code');

        $student_list = array();

        try{
            $query = User::orderby('id', 'desc');

            if (!empty($user_code))
                $query->where('tbl_user.user_code', 'like', '%'.$user_code.'%');
            if (!empty($name))
                $query->where('tbl_user.name', 'like', '%'.$name.'%');
            if (!empty($gender))
                $query->where('tbl_user.gender', $gender);
            if (!empty($birthday))
                $query->where('tbl_user.birthday', $birthday);
            if (!empty($email))
                $query->where('tbl_user.email', 'like', '%'.$email.'%');
            if (!empty($phone))
                $query->where('tbl_user.phone', 'like', '%'.$phone.'%');
            if (!empty($id_code))
                $query->where('tbl_user.id_code', 'like', '%'.$id_code.'%');

            $student_list = $query->get()->toArray();

        } catch (QueryException $e) {
            echo json_encode($student_list);
            exit;
        }

        echo json_encode($student_list);
    }
}