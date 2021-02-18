<?php

namespace App\Http\Controllers\API;


use App\Follow;
use App\Http\Controllers\Controller;
use App\Plan;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PlanController extends Controller
{
    public function getPlanList(Request $request)
    {
        $user_id = $request->input('user_id');
        $batch_title = $request->input('batch_title');
        $batch_code = $request->input('batch_code');
        $college_name = $request->input('college_name');
        $college_type = $request->input('college_type');
        $college_property = $request->input('college_property');
        $addr_province = $request->input('province');
        $department_name = $request->input('department_name');
        $major_name = $request->input('major_name');
        $subject_id = $request->input('subject_id');
        $re_subject_id = $request->input('re_subject_id');
        $cost_from = $request->input('cost_from');
        $cost_to = $request->input('cost_to');
        $recruitment_num = $request->input('recruitment_num');
        $recruitment_num_compare = $request->input('recruitment_num_compare');
        $offset = $request->input('offset');

        $plan_list = array();

        try{
            if (is_null($user_id) || empty($user_id))
                return response()->json([
                    'status' => 'failed'
                ], 404);

            $user_id = (int)$user_id;

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

            if (!empty($batch_title))
                $query->where('tbl_plan.batch_id', $batch_title);
            if (!empty($batch_code))
                $query->where('tbl_plan.batch_id', $batch_code);
            if (!empty($college_name))
                $query->where('tbl_college.name', 'like', '%'.$college_name.'%');
            if (!empty($college_type))
                $query->where('tbl_college.type', $college_type);
            if (!empty($college_property))
                $query->where('tbl_college.property', $college_property);
            if (!empty($addr_province))
                $query->where('tbl_college.addr_province', $addr_province);
            if (!empty($department_name))
                $query->where('tbl_department.name', 'like', '%'.$department_name.'%');
            if (!empty($major_name))
                $query->where('tbl_major.name', 'like', '%'.$major_name.'%');
            if (!empty($subject_id))
                $query->where('tbl_plan.subject_id', $subject_id);
            if (!empty($re_subject_id))
                $query->where('tbl_plan.re_subject_id', $re_subject_id);
            if (!empty($cost_from))
                $query->where('tbl_plan.cost', '>=', $cost_from);
            if (!empty($cost_to))
                $query->where('tbl_plan.cost', '<=', $cost_to);
            if (!empty($recruitment_num))
                if ($recruitment_num_compare == MORE)
                    $query->where('tbl_plan.recruitment_num', '>=', $recruitment_num);
                else
                    $query->where('tbl_plan.recruitment_num', '<=', $recruitment_num);

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

    public function follow(Request $request)
    {
        $user_id = $request->input('user_id');
        $plan_id = $request->input('plan_id');
        $following = $request->input('following');

        if (is_null($user_id) || empty($user_id) || is_null($plan_id) || empty($plan_id))
            return response()->json([
                'status' => 'failed'
            ], 404);

        try {
            if ($following)
                $res = Follow::insert([
                    'user_id' => $user_id,
                    'plan_id' => $plan_id
                ]);
            else
                $res = Follow::where('user_id', $user_id)
                    ->where('plan_id', $plan_id)
                    ->delete();

            if (!$res)
                return response()->json([
                    'status' => 'failed'
                ], 405);

        } catch (QueryException $e) {
            $error = $e->getMessage();
            return response()->json([
                'message' => $error,
                'status' => 'failed'
            ], 405);
        }

        return response()->json([
            'status' => 'success'
        ], 200);
    }
}