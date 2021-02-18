<?php

namespace App\Http\Controllers\API;


use App\Http\Controllers\Controller;
use App\ScoreHistory;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class ScoreController extends Controller
{
    public function getScoreList(Request $request)
    {
        $batch_id = $request->input('batch_id');
        $year = $request->input('year');
        $college_id = $request->input('college_id');
        $department_id= $request->input('department_id');
        $major_id = $request->input('major_id');
        $score = $request->input('score');
        $score_compare = $request->input('score_compare');
        $min = $request->input('min');
        $min_compare = $request->input('min_compare');
        $max = $request->input('max');
        $max_compare = $request->input('max_compare');
        $average = $request->input('average');
        $average_compare = $request->input('average_compare');
        $offset = $request->input('offset');

        $score_list = array();

        try{
            $query = ScoreHistory::leftjoin('tbl_batch', 'tbl_score_history.batch_id', '=', 'tbl_batch.id')
                ->leftjoin('tbl_college', 'tbl_score_history.college_id', '=', 'tbl_college.id')
                ->leftjoin('tbl_department', 'tbl_score_history.department_id', '=', 'tbl_department.id')
                ->leftjoin('tbl_major', 'tbl_score_history.major_id', '=', 'tbl_major.id')
                ->select('tbl_score_history.*', 'tbl_batch.title as batch_title', 'tbl_batch.code as batch_code',
                    'tbl_college.name as college_name', 'tbl_college.site_url as college_url', 'tbl_department.name as department_name', 'tbl_major.name as major_name')
                ->skip($offset*10)->take(($offset+1)*10);

            if (!empty($batch_id))
                $query->where('tbl_score_history.batch_id', $batch_id);
            if (!empty($year))
                $query->where('tbl_score_history.year', $year);
            if (!empty($college_id))
                $query->where('tbl_score_history.college_id', $college_id);
            if (!empty($department_id))
                $query->where('tbl_score_history.department_id', $department_id);
            if (!empty($major_id))
                $query->where('tbl_score_history.major_id', $major_id);
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
            $error = $e->getMessage();
            return response()->json([
                'message' => $error,
                'status' => 'failed'
            ], 405);
        }

        $data['score_list'] = $score_list;
        return response()->json([
            'data' => $data,
            'status' => 'success'
        ], 200);
    }
}