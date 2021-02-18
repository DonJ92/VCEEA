<?php

namespace App\Http\Controllers\API;


use App\Follow;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    public function getFollowList(Request $request)
    {
        $user_id = $request->input('user_id');
        $batch_title = $request->input('batch_title');
        $batch_code = $request->input('batch_code');
        $college_type = $request->input('college_type');
        $college_property = $request->input('college_property');
        $major_name = $request->input('major_name');
        $subject_id = $request->input('subject_id');
        $re_subject_id = $request->input('re_subject_id');
        $offset = $request->input('offset');

        $follow_list = array();

        try{
            if (is_null($user_id) || empty($user_id))
                return response()->json([
                    'status' => 'failed'
                ], 404);

            $user_id = (int)$user_id;

            $query = Follow::leftjoin('tbl_plan', 'tbl_follow.plan_id', '=', 'tbl_plan.id')
                ->leftjoin('tbl_batch', 'tbl_plan.batch_id', '=', 'tbl_batch.id')
                ->leftjoin('tbl_college', 'tbl_plan.college_id', '=', 'tbl_college.id')
                ->leftjoin('tbl_province', 'tbl_province.id', '=', 'tbl_college.addr_province')
                ->leftjoin('tbl_department', 'tbl_plan.department_id', '=', 'tbl_department.id')
                ->leftjoin('tbl_major', 'tbl_plan.major_id', '=', 'tbl_major.id')
                ->leftjoin('tbl_subject', 'tbl_plan.subject_id', '=', 'tbl_subject.id')
                ->leftjoin('tbl_subject as tbl_re_subject', 'tbl_plan.re_subject_id', '=', 'tbl_re_subject.id')
                ->where('tbl_follow.user_id', $user_id)
                ->select('tbl_follow.id as follow_id', 'tbl_plan.*', 'tbl_batch.title as batch_title', 'tbl_batch.code as batch_code',
                    'tbl_college.name as college_name', 'tbl_college.code as college_code', 'tbl_college.site_url as college_url', 'tbl_college.type as college_type', 'tbl_college.property as college_property',
                    'tbl_province.name as addr_province', 'tbl_department.name as department_name', 'tbl_department.code as department_code',
                    'tbl_major.name as major_name', 'tbl_major.code as major_code', 'tbl_subject.name as subject_name', 'tbl_re_subject.name as re_subject_name')
                ->orderBy('batch_code', 'ASC')
                ->skip($offset*10)->take(($offset+1)*10);

            if (!empty($batch_title))
                $query->where('tbl_plan.batch_id', $batch_title);
            if (!empty($batch_code))
                $query->where('tbl_plan.batch_id', $batch_code);
            if (!empty($college_type))
                $query->where('tbl_college.type', $college_type);
            if (!empty($college_property))
                $query->where('tbl_college.property', $college_property);
            if (!empty($major_name))
                $query->where('tbl_major.id', $major_name);
            if (!empty($subject_id))
                $query->where('tbl_plan.subject_id', $subject_id);
            if (!empty($re_subject_id))
                $query->where('tbl_plan.re_subject_id', $re_subject_id);

            $follow_list = $query->get()->toArray();

        } catch (QueryException $e) {
            $error = $e->getMessage();
            return response()->json([
                'message' => $error,
                'status' => 'failed'
            ], 405);
        }

        $type_list = config('constants.college_type');
        $property_list = config('constants.college_property');

        for ($i = 0; $i < count($follow_list); $i++) {
            $follow_list[$i]['college_type'] = $type_list[$follow_list[$i]['college_type']-1]['type'];
            $follow_list[$i]['college_property'] = $property_list[$follow_list[$i]['college_property']-1]['property'];
            if (is_null($follow_list[$i]['follow_id']) || empty($follow_list[$i]['follow_id']))
                $follow_list[$i]['is_follow'] = false;
            else
                $follow_list[$i]['is_follow'] = true;

            if (is_null($follow_list[$i]['re_subject_id']))
                $follow_list[$i]['re_subject_id'] = 0;

            if (is_null($follow_list[$i]['re_subject_name']))
                $follow_list[$i]['re_subject_name'] = '';
        }

        $data['plan_list'] = $follow_list;
        return response()->json([
            'data' => $data,
            'status' => 'success'
        ], 200);
    }

}