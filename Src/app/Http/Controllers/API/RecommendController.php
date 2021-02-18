<?php

namespace App\Http\Controllers\API;


use App\Http\Controllers\Controller;
use App\Plan;
use App\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RecommendController extends Controller
{
    public function getPlanList(Request $request)
    {
        $user_id = $request->input('user_id');
        $offset = $request->input('offset');

        $plan_list = array();

        try{
            if (is_null($user_id) || empty($user_id))
                return response()->json([
                    'status' => 'failed'
                ], 404);

            $user_id = (int)$user_id;

            $user_info = User::where('id', $user_id)->first();
            if (is_null($user_info))
                return response()->json([
                    'status' => 'failed'
                ], 404);

            $major_id_list = explode(',', $user_info->major_id_list);

            $query = Plan::leftjoin('tbl_batch', 'tbl_plan.batch_id', '=', 'tbl_batch.id')
                ->leftjoin('tbl_college', 'tbl_plan.college_id', '=', 'tbl_college.id')
                ->leftjoin('tbl_province', 'tbl_province.id', '=', 'tbl_college.addr_province')
                ->leftjoin('tbl_department', 'tbl_plan.department_id', '=', 'tbl_department.id')
                ->leftjoin('tbl_major', 'tbl_plan.major_id', '=', 'tbl_major.id')
                ->leftjoin('tbl_subject', 'tbl_plan.subject_id', '=', 'tbl_subject.id')
                ->leftjoin('tbl_subject as tbl_re_subject', 'tbl_plan.re_subject_id', '=', 'tbl_re_subject.id')
                ->leftjoin('tbl_follow', function($join) use($user_id)
                {
                    $join->on('tbl_plan.id', '=', 'tbl_follow.plan_id');
                    $join->on('tbl_follow.user_id', '=', $user_id);
                })
                ->leftjoin(DB::raw('(SELECT plan_id, count( plan_id ) AS following_count FROM tbl_follow GROUP BY plan_id) tbl_following'),function($join)
                {
                    $join->on('tbl_plan.id', '=', 'tbl_following.plan_id');
                })
                ->where('tbl_plan.status', ACTIVE)
                ->select('tbl_plan.*', 'tbl_batch.title as batch_title', 'tbl_batch.code as batch_code',
                    'tbl_college.name as college_name', 'tbl_college.code as college_code', 'tbl_college.site_url as college_url', 'tbl_college.type as college_type', 'tbl_college.property as college_property',
                    'tbl_province.name as addr_province', 'tbl_department.name as department_name', 'tbl_department.code as department_code',
                    'tbl_major.name as major_name', 'tbl_major.code as major_code', 'tbl_subject.name as subject_name', 'tbl_re_subject.name as re_subject_name',
                    'tbl_follow.id as follow_id', DB::raw('NVL(tbl_following.following_count, 0) as following_count'))
                ->orderBy('following_count', 'DESC')
                ->orderBy('batch_code', 'ASC')
                ->offset($offset*10)->limit(($offset+1)*10);

            if (!is_null($major_id_list) && count($major_id_list) >= 1)
                $query->where(function($q) use ($major_id_list){
                    $q->where('tbl_major.id', $major_id_list[0]);
                    if (count($major_id_list) > 1)
                        for ($i = 1; $i < count($major_id_list); $i++)
                            $q->orWhere('tbl_major.id', $major_id_list[$i]);
                });

            $plan_list = $query->get()->toArray();

        } catch (QueryException $e) {
            $error = $e->getMessage();
            return response()->json([
                'message' => $error,
                'status' => 'failed'
            ], 405);
        }

        $type_list = config('constants.college_type');
        $property_list = config('constants.college_property');

        for ($i = 0; $i < count($plan_list); $i++) {
            $plan_list[$i]['college_type'] = $type_list[$plan_list[$i]['college_type']-1]['type'];
            $plan_list[$i]['college_property'] = $property_list[$plan_list[$i]['college_property']-1]['property'];

            if (is_null($plan_list[$i]['follow_id']) || empty($plan_list[$i]['follow_id']))
                $plan_list[$i]['is_follow'] = false;
            else
                $plan_list[$i]['is_follow'] = true;

            if (is_null($plan_list[$i]['re_subject_id']))
                $plan_list[$i]['re_subject_id'] = 0;

            if (is_null($plan_list[$i]['re_subject_name']))
                $plan_list[$i]['re_subject_name'] = '';
        }

        $data['plan_list'] = $plan_list;

        return response()->json([
            'data' => $data,
            'status' => 'success'
        ], 200);
    }
}