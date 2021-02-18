<?php

namespace App\Http\Controllers\Users;


use App\Http\Controllers\Controller;
use App\Plan;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RecommendController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $batch_list = $this->getBatchListFromID();
        $province_list = $this->getProvinceList();
        $subject_list = $this->getSubjectListFromID();
        $type_list = config('constants.college_type');
        $property_list = config('constants.college_property');

        $data['batch_list'] = $batch_list;
        $data['province_list'] = $province_list;
        $data['subject_list'] = $subject_list;
        $data['type_list'] = $type_list;
        $data['property_list'] = $property_list;

        $this->registerLog(LOG_AI_PAGE);

        return view('recommend', $data);
    }

    public function getPlanList(Request $request)
    {
        $batch_title = $request->input('batch_title');
        $batch_code = $request->input('batch_code');
        $college_name = $request->input('college_name');
        $college_type = $request->input('college_type');
        $college_property = $request->input('college_property');
        $addr_province = $request->input('addr_province');
        $department_name = $request->input('department_name');
        $major_name = $request->input('major_name');
        $subject_id = $request->input('subject_id');
        $re_subject_id = $request->input('re_subject_id');
        $cost_from = $request->input('cost_from');
        $cost_to = $request->input('cost_to');
        $recruitment_num = $request->input('recruitment_num');
        $recruitment_num_compare = $request->input('recruitment_num_compare');

        $major_id_list = explode(',', Auth::user()->major_id_list);

        $plan_list = array();

        try{
            $query = Plan::leftjoin('tbl_batch', 'tbl_plan.batch_id', '=', 'tbl_batch.id')
                ->leftjoin('tbl_college', 'tbl_plan.college_id', '=', 'tbl_college.id')
                ->leftjoin('tbl_province', 'tbl_province.id', '=', 'tbl_college.addr_province')
                ->leftjoin('tbl_department', 'tbl_plan.department_id', '=', 'tbl_department.id')
                ->leftjoin('tbl_major', 'tbl_plan.major_id', '=', 'tbl_major.id')
                ->leftjoin('tbl_subject', 'tbl_plan.subject_id', '=', 'tbl_subject.id')
                ->leftjoin('tbl_subject as tbl_re_subject', 'tbl_plan.re_subject_id', '=', 'tbl_re_subject.id')
                ->leftjoin('tbl_follow', function($join)
                {
                    $join->on('tbl_plan.id', '=', 'tbl_follow.plan_id');
                    $join->on('tbl_follow.user_id', '=', Auth::user()->id);
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
                ->orderBy('following_count', 'DESC');

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
            else
                if (!is_null($major_id_list) && count($major_id_list) >= 1)
                    $query->where(function($q) use ($major_id_list){
                        $q->where('tbl_major.id', $major_id_list[0]);
                        if (count($major_id_list) > 1)
                            for ($i = 1; $i < count($major_id_list); $i++)
                                $q->orWhere('tbl_major.id', $major_id_list[$i]);
                    });
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
            print_r($e->getMessage());
            echo json_encode($plan_list);
            exit;
        }

        $type_list = config('constants.college_type');
        $property_list = config('constants.college_property');

        for ($i = 0; $i < count($plan_list); $i++) {
            $plan_list[$i]['college_type'] = $type_list[$plan_list[$i]['college_type']-1]['type'];
            $plan_list[$i]['college_property'] = $property_list[$plan_list[$i]['college_property']-1]['property'];
        }

        echo json_encode($plan_list);
    }
}