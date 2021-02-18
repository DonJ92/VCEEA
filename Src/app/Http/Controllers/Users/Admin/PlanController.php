<?php

namespace App\Http\Controllers\Users\Admin;


use App\Http\Controllers\Controller;
use App\Plan;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PlanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $batch_list = $this->getBatchListFromID();
        $subject_list = $this->getSubjectListFromID();
        $type_list = config('constants.college_type');
        $property_list = config('constants.college_property');

        $data['batch_list'] = $batch_list;
        $data['subject_list'] = $subject_list;
        $data['type_list'] = $type_list;
        $data['property_list'] = $property_list;
        return view('admin.plan', $data);
    }

    public function getPlanList(Request $request)
    {
        $batch_title = $request->input('batch_title');
        $batch_code = $request->input('batch_code');
        $department_name = $request->input('department_name');
        $department_code = $request->input('department_code');
        $college_name = $request->input('college_name');
        $college_code = $request->input('college_code');
        $major_name = $request->input('major_name');
        $major_code = $request->input('major_code');
        $subject_id = $request->input('subject_id');
        $college_type = $request->input('college_type');
        $college_property = $request->input('college_property');
        $academic_year = $request->input('academic_year');
        $cost_from = $request->input('cost_from');
        $cost_to = $request->input('cost_to');
        $recruitment_num = $request->input('recruitment_num');
        $recruitment_num_compare = $request->input('recruitment_num_compare');

        $plan_list = array();

        try{
            $query = Plan::leftjoin('tbl_batch', 'tbl_plan.batch_id', '=', 'tbl_batch.id')
                ->leftjoin('tbl_college', 'tbl_plan.college_id', '=', 'tbl_college.id')
                ->leftjoin('tbl_department', 'tbl_plan.department_id', '=', 'tbl_department.id')
                ->leftjoin('tbl_major', 'tbl_plan.major_id', '=', 'tbl_major.id')
                ->leftjoin('tbl_subject', 'tbl_plan.subject_id', '=', 'tbl_subject.id')
                ->select('tbl_plan.*', 'tbl_batch.title as batch_title', 'tbl_batch.code as batch_code',
                    'tbl_college.name as college_name', 'tbl_college.code as college_code', 'tbl_college.national_code as college_national_code',
                    'tbl_college.type as college_type', 'tbl_college.type as college_property',
                    'tbl_department.name as department_name', 'tbl_department.code as department_code',
                    'tbl_major.name as major_name', 'tbl_major.code as major_code', 'tbl_major.national_code as major_national_code', 'tbl_subject.name as subject_name');

            if (!empty($batch_title))
                $query->where('tbl_plan.batch_id', $batch_title);
            if (!empty($batch_code))
                $query->where('tbl_plan.batch_id', $batch_code);
            if (!empty($department_name))
                $query->where('tbl_department.name', 'like', '%'.$department_name.'%');
            if (!empty($department_code))
                $query->where('tbl_department.code', 'like', '%'.$department_code.'%');
            if (!empty($college_name))
                $query->where('tbl_college.name', 'like', '%'.$college_name.'%');
            if (!empty($college_code))
                $query->where('tbl_college.code', 'like', '%'.$college_code.'%');
            if (!empty($major_name))
                $query->where('tbl_major.name', 'like', '%'.$major_name.'%');
            if (!empty($major_code))
                $query->where('tbl_major.code', 'like', '%'.$major_code.'%');
            if (!empty($subject_id))
                $query->where('tbl_plan.subject_id', $subject_id);
            if (!empty($college_type))
                $query->where('tbl_college.type', $college_type);
            if (!empty($college_property))
                $query->where('tbl_college.property', $college_property);
            if (!empty($academic_year))
                $query->where('tbl_plan.academic_year_code', $academic_year);
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

    public function add()
    {
        $batch_list = $this->getBatchListFromID();
        $college_list = $this->getCollegeListFromID();
        $department_list = $this->getDepartmentListFromID();
        $major_list = $this->getMajorListFromID();
        $subject_list = $this->getSubjectListFromID();

        $data['batch_list'] = $batch_list;
        $data['college_list'] = $college_list;
        $data['department_list'] = $department_list;
        $data['major_list'] = $major_list;
        $data['subject_list'] = $subject_list;

        return view('admin.plan_add', $data);
    }

    public function addSubmit(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'batch_title' => 'required|exists:tbl_batch,id',
            'college_name' => 'required|exists:tbl_college,id',
            'department_name' => 'required|exists:tbl_department,id',
            'major_name' => 'required|exists:tbl_major,id',
            'subject' => 'required|exists:tbl_subject,id',
            're_subject' => 'nullable|exists:tbl_subject,id',
            'academic_year' => 'required|numeric',
            'cost' => 'nullable|numeric',
            'recruitment_num' => 'nullable|numeric',
            'description' => 'nullable|string|max:1024',
            'status' => 'required',
        ], [
        ], [
            'batch_title' => trans('admin_plan.batch_title'),
            'college_name' => trans('admin_plan.college_name'),
            'department_name' => trans('admin_plan.department_name'),
            'major_name' => trans('admin_plan.major_name'),
            'subject' => trans('admin_plan.subject'),
            're_subject' => trans('admin_plan.re_subject'),
            'academic_year' => trans('admin_plan.academic_year'),
            'cost' => trans('admin_plan.cost'),
            'recruitment_num' => trans('admin_plan.recruitment_num'),
            'description' => trans('admin_plan.description'),
            'status' => trans('admin_plan.status'),
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return redirect()->back()->withInput()->withErrors($errors);
        }

        try {
            $res = Plan::insert([
                'batch_id' => $data['batch_title'],
                'college_id' => $data['college_name'],
                'department_id' => $data['department_name'],
                'major_id' => $data['major_name'],
                'subject_id' => $data['subject'],
                're_subject_id' => $data['re_subject'],
                'academic_year_code' => $data['academic_year'],
                'cost' => $data['cost'],
                'recruitment_num' => $data['recruitment_num'],
                'description' => $data['description'],
                'status' => $data['status'],
            ]);

            if (!$res)
                return redirect()->back()->withInput()->withErrors(['failed' => trans('admin_plan.add_failed')]);

        } catch (QueryException $e) {
            return redirect()->back()->withInput()->withErrors(['failed' => trans('admin_plan.add_failed')]);
        }

        return redirect()->route('admin.plan')->with('success', trans('admin_plan.add_success'));;
    }

    public function edit($id)
    {
        if (is_null($id) || empty($id))
            return redirect()->back()->withErrors(['failed' => trans('admin_plan.no_info')]);

        try {
            $plan_info = Plan::where('id', $id)->first();
            if (is_null($plan_info))
                return redirect()->back()->withErrors(['failed' => trans('admin_plan.no_info')]);

            $plan_info = $plan_info->toArray();

        } catch (QueryException $e) {
            return redirect()->back()->withErrors(['failed' => trans('admin_plan.no_info')]);
        }

        $batch_list = $this->getBatchListFromID();
        $college_list = $this->getCollegeListFromID();
        $department_list = $this->getDepartmentListFromID();
        $major_list = $this->getMajorListFromID();
        $subject_list = $this->getSubjectListFromID();

        $data['batch_list'] = $batch_list;
        $data['college_list'] = $college_list;
        $data['department_list'] = $department_list;
        $data['major_list'] = $major_list;
        $data['subject_list'] = $subject_list;
        $data['plan_info'] = $plan_info;
        return view('admin.plan_edit', $data);
    }

    public function editSubmit(Request $request)
    {
        $data = $request->all();

        if (empty($data['id']))
            return redirect()->back()->withInput()->withErrors(['failed' => trans('admin_plan.edit_failed')]);

        $validator = Validator::make($data, [
            'batch_title' => 'required|exists:tbl_batch,id',
            'college_name' => 'required|exists:tbl_college,id',
            'department_name' => 'required|exists:tbl_department,id',
            'major_name' => 'required|exists:tbl_major,id',
            'subject' => 'required|exists:tbl_subject,id',
            're_subject' => 'nullable|exists:tbl_subject,id',
            'academic_year' => 'required|numeric',
            'cost' => 'nullable|numeric',
            'recruitment_num' => 'nullable|numeric',
            'description' => 'nullable|string|max:1024',
            'status' => 'required',
        ], [
        ], [
            'batch_title' => trans('admin_plan.batch_title'),
            'college_name' => trans('admin_plan.college_name'),
            'department_name' => trans('admin_plan.department_name'),
            'major_name' => trans('admin_plan.major_name'),
            'subject' => trans('admin_plan.subject'),
            're_subject' => trans('admin_plan.re_subject'),
            'academic_year' => trans('admin_plan.academic_year'),
            'cost' => trans('admin_plan.cost'),
            'recruitment_num' => trans('admin_plan.recruitment_num'),
            'description' => trans('admin_plan.description'),
            'status' => trans('admin_plan.status'),
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return redirect()->back()->withInput()->withErrors($errors);
        }

        try {
            $plan_info = Plan::where('id', $data['id'])->first();

            if (is_null($plan_info))
                return redirect()->back()->withInput()->withErrors(['failed' => trans('admin_plan.edit_failed')]);

            $plan_info->batch_id = $data['batch_title'];
            $plan_info->college_id = $data['college_name'];
            $plan_info->department_id = $data['department_name'];
            $plan_info->major_id = $data['major_name'];
            $plan_info->subject_id = $data['subject'];
            $plan_info->re_subject_id = $data['re_subject'];
            $plan_info->academic_year_code = $data['academic_year'];
            $plan_info->cost = $data['cost'];
            $plan_info->recruitment_num = $data['recruitment_num'];
            $plan_info->description = $data['description'];
            $plan_info->status = $data['status'];

            $res = $plan_info->save();

            if ($res)
                return redirect()->route('admin.plan')->with('success', trans('admin_plan.edit_success'));;

        } catch (QueryException $e) {
            return redirect()->back()->withInput()->withErrors(['failed' => trans('admin_plan.edit_failed')]);
        }

        return redirect()->route('admin.plan')->with('success', trans('admin_plan.edit_success'));;
    }

    public function delete(Request $request)
    {
        $id = $request->input('id');

        if (is_null($id) || empty($id))
            echo json_encode(false);

        try {
            $res = Plan::where('id', $id)->delete();

            if (!$res)
                echo json_encode(false);

        } catch (QueryException $e) {
            echo json_encode(false);
        }

        echo json_encode(true);
    }
}