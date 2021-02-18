<?php

namespace App\Http\Controllers\Users\Admin;


use App\Http\Controllers\Controller;
use App\Log;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        date_default_timezone_set("Asia/Shanghai");

        $today = date('Y-m-d\TH:i');
        $from_date = date("Y-m-d\TH:i", strtotime( date( "Y-m-d", strtotime( date("Y-m-d") ) ) . "-1 month" ) );
        $type_list = config('constants.log_type');

        $data['from_date'] = $from_date;
        $data['to_date'] = $today;
        $data['type_list'] = $type_list;

        return view('admin.log', $data);
    }

    public function getLogList(Request $request)
    {
        $user_code = $request->input('user_code');
        $log_type = $request->input('log_type');
        $date_from = $request->input('date_from');
        $date_to = $request->input('date_to');

        if(isset($date_from))
            $date_from = date('Y-m-d H:i', strtotime($date_from));

        if(isset($date_to))
            $date_to = date('Y-m-d H:i', strtotime($date_to));

        $log_list = array();

        try{
            $query = Log::leftjoin('tbl_user', 'tbl_log.user_id', '=', 'tbl_user.id' )
                ->select('tbl_log.*', 'tbl_user.user_code')
                ->orderby('tbl_log.created_at', 'desc');

            if (!empty($user_code))
                $query->where('tbl_user.user_code', 'like', '%'.$user_code.'%');
            if (!empty($log_type))
                $query->where('tbl_log.log_type', $log_type);
            if (!empty($date_from))
                $query->where('tbl_log.created_at', '>=', $date_from);
            if (!empty($date_to))
                $query->where('tbl_log.created_at', '<=', $date_to);

            $log_list = $query->get()->toArray();

        } catch (QueryException $e) {
            echo json_encode($log_list);
            exit;
        }

        $type_list = config('constants.log_type');
        for ($i = 0; $i < count($log_list); $i++) {
            $log_list[$i]['log_type'] = $type_list[$log_list[$i]['log_type'] - 1]['type'];
            $log_list[$i]['created_at'] = date('Y-m-d H:i:s', strtotime($log_list[$i]['created_at']));
        }

        echo json_encode($log_list);
    }

}