<?php

namespace App\Http\Controllers\Users\Admin;


use App\Http\Controllers\Controller;
use App\ScoreHistory;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ScoreController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $batch_list = $this->getBatchListFromID();

        $data['batch_list'] = $batch_list;
        return view('admin.score', $data);
    }

    public function getScoreList(Request $request)
    {
        $batch_id = $request->input('batch_id');
        $year = $request->input('year');
        $user_code = $request->input('user_code');
        $college_name = $request->input('college_name');
        $college_code = $request->input('college_code');
        $college_national_code = $request->input('college_national_code');
        $major_name = $request->input('major_name');
        $major_code = $request->input('major_code');
        $major_national_code = $request->input('major_national_code');
        $score = $request->input('score');
        $score_compare = $request->input('score_compare');
        $min = $request->input('min');
        $min_compare = $request->input('min_compare');
        $max = $request->input('max');
        $max_compare = $request->input('max_compare');
        $average = $request->input('average');
        $average_compare = $request->input('average_compare');

        $score_list = array();

        try{
            $query = ScoreHistory::leftjoin('tbl_batch', 'tbl_score_history.batch_id', '=', 'tbl_batch.id')
                ->leftjoin('tbl_college', 'tbl_score_history.college_id', '=', 'tbl_college.id')
                ->leftjoin('tbl_major', 'tbl_score_history.major_id', '=', 'tbl_major.id')
                ->select('tbl_score_history.*', 'tbl_batch.title as batch_title', 'tbl_batch.code as batch_code',
                    'tbl_college.name as college_name', 'tbl_college.code as college_code', 'tbl_college.national_code as college_national_code',
                    'tbl_major.name as major_name', 'tbl_major.code as major_code', 'tbl_major.national_code as major_national_code');

            if (!empty($batch_id))
                $query->where('tbl_score_history.batch_id', $batch_id);
            if (!empty($year))
                $query->where('tbl_score_history.year', $year);
            if (!empty($user_code))
                $query->where('tbl_score_history.user_code', 'like', '%'.$user_code.'%');
            if (!empty($college_name))
                $query->where('tbl_college.name', 'like', '%'.$college_name.'%');
            if (!empty($college_code))
                $query->where('tbl_college.code', 'like', '%'.$college_code.'%');
            if (!empty($college_national_code))
                $query->where('tbl_college.national_code', 'like', '%'.$college_national_code.'%');
            if (!empty($major_name))
                $query->where('tbl_major.name', 'like', '%'.$major_name.'%');
            if (!empty($major_code))
                $query->where('tbl_major.code', 'like', '%'.$major_code.'%');
            if (!empty($major_national_code))
                $query->where('tbl_major.national_code', 'like', '%'.$major_national_code.'%');
            if (!empty($score))
                if ($score_compare == MORE)
                    $query->where('tbl_score_history.score', '>=', $score);
                else
                    $query->where('tbl_score_history.score', '<=', $score);
            if (!empty($min))
                if ($min_compare == MORE)
                    $query->where('tbl_score_history.min', '>=', $min);
                else
                    $query->where('tbl_score_history.min', '<=', $min);
            if (!empty($max))
                if ($max_compare == MORE)
                    $query->where('tbl_score_history.max', '>=', $max);
                else
                    $query->where('tbl_score_history.max', '<=', $max);
            if (!empty($average))
                if ($average_compare == MORE)
                    $query->where('tbl_score_history.average', '>=', $average);
                else
                    $query->where('tbl_score_history.average', '<=', $average);


            $score_list = $query->get()->toArray();

        } catch (QueryException $e) {
            echo json_encode($score_list);
            exit;
        }

        echo json_encode($score_list);
    }

    public function add()
    {
        $batch_list = $this->getBatchListFromID();
        $college_list = $this->getCollegeListFromID();
        $department_list = $this->getDepartmentListFromID();
        $major_list = $this->getMajorListFromID();

        $data['batch_list'] = $batch_list;
        $data['college_list'] = $college_list;
        $data['department_list'] = $department_list;
        $data['major_list'] = $major_list;
        return view('admin.score_add', $data);
    }

    public function addSubmit(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'batch_title' => 'required|exists:tbl_batch,id',
            'college_name' => 'required|exists:tbl_college,id',
            'department_name' => 'required|exists:tbl_department,id',
            'major_name' => 'required|exists:tbl_major,id',
            'year' => 'required',
            'user_code' => 'required|max:12',
            'score' => 'required|numeric',
            'ranking' => 'required|numeric',
            'min' => 'required|numeric',
            'max' => 'required|numeric',
            'average' => 'required|numeric',
        ], [
        ], [
            'batch_title' => trans('admin_score.batch_title'),
            'college_name' => trans('admin_score.college_name'),
            'department_name' => trans('admin_score.department_name'),
            'major_name' => trans('admin_score.major_name'),
            'year' => trans('admin_score.year'),
            'user_code' => trans('admin_score.user_code'),
            'score' => trans('admin_score.score'),
            'ranking' => trans('admin_score.ranking'),
            'min' => trans('admin_score.min'),
            'max' => trans('admin_score.max'),
            'average' => trans('admin_score.average'),
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return redirect()->back()->withInput()->withErrors($errors);
        }

        try {
            $res = ScoreHistory::insert([
                'batch_id' => $data['batch_title'],
                'college_id' => $data['college_name'],
                'department_id' => $data['department_name'],
                'major_id' => $data['major_name'],
                'year' => $data['year'],
                'user_code' => $data['user_code'],
                'score' => $data['score'],
                'ranking' => $data['ranking'],
                'min' => $data['min'],
                'max' => $data['max'],
                'average' => $data['average'],
            ]);

            if ($res)
                return redirect()->route('admin.score')->with('success', trans('admin_score.add_success'));;

        } catch (QueryException $e) {
            return redirect()->back()->withInput()->withErrors(['failed' => trans('admin_score.add_failed')]);
        }

        return redirect()->route('admin.score')->with('success', trans('admin_score.add_success'));;
    }

    public function edit($id)
    {
        if (is_null($id) || empty($id))
            return redirect()->back()->withErrors(['failed' => trans('admin_score.no_info')]);

        try {
            $score_info = ScoreHistory::where('id', $id)->first();
            if (is_null($score_info))
                return redirect()->back()->withErrors(['failed' => trans('admin_score.no_info')]);

            $score_info = $score_info->toArray();

        } catch (QueryException $e) {
            return redirect()->back()->withErrors(['failed' => trans('admin_score.no_info')]);
        }

        $batch_list = $this->getBatchListFromID();
        $college_list = $this->getCollegeListFromID();
        $department_list = $this->getDepartmentListFromID();
        $major_list = $this->getMajorListFromID();

        $data['batch_list'] = $batch_list;
        $data['college_list'] = $college_list;
        $data['department_list'] = $department_list;
        $data['major_list'] = $major_list;
        $data['score_info'] = $score_info;
        return view('admin.score_edit', $data);
    }

    public function editSubmit(Request $request)
    {
        $data = $request->all();

        if (empty($data['id']))
            return redirect()->back()->withInput()->withErrors(['failed' => trans('admin_score.edit_failed')]);

        $validator = Validator::make($data, [
            'batch_title' => 'required|exists:tbl_batch,id',
            'college_name' => 'required|exists:tbl_college,id',
            'department_name' => 'required|exists:tbl_department,id',
            'major_name' => 'required|exists:tbl_major,id',
            'year' => 'required',
            'user_code' => 'required|max:12',
            'score' => 'required|numeric',
            'ranking' => 'required|numeric',
            'min' => 'required|numeric',
            'max' => 'required|numeric',
            'average' => 'required|numeric',
        ], [
        ], [
            'batch_title' => trans('admin_score.batch_title'),
            'college_name' => trans('admin_score.college_name'),
            'department_name' => trans('admin_score.department_name'),
            'major_name' => trans('admin_score.major_name'),
            'year' => trans('admin_score.year'),
            'user_code' => trans('admin_score.user_code'),
            'score' => trans('admin_score.score'),
            'ranking' => trans('admin_score.ranking'),
            'min' => trans('admin_score.min'),
            'max' => trans('admin_score.max'),
            'average' => trans('admin_score.average'),
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return redirect()->back()->withInput()->withErrors($errors);
        }

        try {
            $score_info = ScoreHistory::where('id', $data['id'])->first();

            if (is_null($score_info))
                return redirect()->back()->withInput()->withErrors(['failed' => trans('admin_score.edit_failed')]);

            $score_info->batch_id = $data['batch_title'];
            $score_info->college_id = $data['college_name'];
            $score_info->department_id = $data['department_name'];
            $score_info->major_id = $data['major_name'];
            $score_info->year = $data['year'];
            $score_info->user_code = $data['user_code'];
            $score_info->score = $data['score'];
            $score_info->ranking = $data['ranking'];
            $score_info->min = $data['min'];
            $score_info->max = $data['max'];
            $score_info->average = $data['average'];

            $res = $score_info->save();

            if ($res)
                return redirect()->route('admin.score')->with('success', trans('admin_score.edit_success'));;

        } catch (QueryException $e) {
            return redirect()->back()->withInput()->withErrors(['failed' => trans('admin_score.edit_failed')]);
        }

        return redirect()->route('admin.score')->with('success', trans('admin_score.edit_success'));;
    }

    public function delete(Request $request)
    {
        $id = $request->input('id');

        if (is_null($id) || empty($id))
            echo json_encode(false);

        try {
            $res = ScoreHistory::where('id', $id)->delete();

            if (!$res)
                echo json_encode(false);

        } catch (QueryException $e) {
            echo json_encode(false);
        }

        echo json_encode(true);
    }
}