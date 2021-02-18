<?php

namespace App\Http\Controllers\Users;


use App\Exports\SimulateExport;
use App\Http\Controllers\Controller;
use App\Plan;
use App\Simulate;
use App\SimulateDetail;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SimulateController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        try {
            $simulate_list = Simulate::where('user_id', Auth::user()->id)
                ->orderby('id', 'DESC')
                ->get()->toArray();
        } catch (QueryException $e) {
        }
        $data['simulate_list'] = $simulate_list;

        $this->registerLog(LOG_SIMULATE_PAGE);

        return view('simulate', $data);
    }

    public function getSimulateList(Request $request)
    {
        $simulate_id = $request->input('simulate_id');

        $plan_list = array();
        try {
            $plan_list = Plan::leftjoin('tbl_simulate_detail', 'tbl_plan.id', '=', 'tbl_simulate_detail.plan_id')
                ->leftjoin('tbl_batch', 'tbl_plan.batch_id', '=', 'tbl_batch.id')
                ->leftjoin('tbl_college', 'tbl_plan.college_id', '=', 'tbl_college.id')
                ->leftjoin('tbl_department', 'tbl_plan.department_id', '=', 'tbl_department.id')
                ->leftjoin('tbl_major', 'tbl_plan.major_id', '=', 'tbl_major.id')
                ->where('tbl_simulate_detail.simulate_id', $simulate_id)
                ->select('tbl_batch.title as batch_title', 'tbl_batch.code as batch_code', 'tbl_college.name as college_name', 'tbl_college.code as college_code',
                    'tbl_department.name as department_name', 'tbl_major.name as major_name', 'tbl_major.code as major_code',
                    'tbl_simulate_detail.id as simulate_detail_id', 'tbl_simulate_detail.plan_id as plan_id', 'tbl_simulate_detail.sort as sort')
                ->orderby('tbl_simulate_detail.sort', 'ASC')
                ->get()->toArray();

        } catch (QueryException $e) {
            echo json_encode($plan_list);
            exit;
        }
        echo json_encode($plan_list);
    }

    public function moveUp(Request $request)
    {
        $simulate_detail_id = $request->get('simulate_detail_id');

        try {
            $simulate_detail_info = SimulateDetail::where('id', $simulate_detail_id)->first();
            if (is_null($simulate_detail_info)) {
                echo json_encode(false);
                exit;
            }

            if ($simulate_detail_info->sort < 2) {
                echo json_encode(true);
                exit;
            }

            $prev_sort = $simulate_detail_info->sort-1;
            $prev_simulate_detail_info = SimulateDetail::where('simulate_id', $simulate_detail_info->simulate_id)
                ->where('sort', $prev_sort)->first();
            if (is_null($prev_simulate_detail_info)) {
                echo json_encode(false);
                exit;
            }

            $prev_simulate_detail_info->sort = $simulate_detail_info->sort;
            $simulate_detail_info->sort = $prev_sort;

            $prev_simulate_detail_info->save();
            $simulate_detail_info->save();

        } catch (QueryException $e) {
            echo json_encode(false);
            exit;
        }

        echo json_encode(true);
    }

    public function moveDown(Request $request)
    {
        $simulate_detail_id = $request->get('simulate_detail_id');

        try {
            $simulate_detail_info = SimulateDetail::where('id', $simulate_detail_id)->first();
            if (is_null($simulate_detail_info)) {
                echo json_encode(false);
                exit;
            }

            $next_sort = $simulate_detail_info->sort+1;
            $next_simulate_detail_info = SimulateDetail::where('simulate_id', $simulate_detail_info->simulate_id)
                ->where('sort', $next_sort)->first();
            if (is_null($next_simulate_detail_info)) {
                echo json_encode(false);
                exit;
            }

            $next_simulate_detail_info->sort = $simulate_detail_info->sort;
            $simulate_detail_info->sort = $next_sort;

            $next_simulate_detail_info->save();
            $simulate_detail_info->save();

        } catch (QueryException $e) {
            echo json_encode(false);
            exit;
        }

        echo json_encode(true);
    }

    public function delete(Request $request)
    {
        $simulate_detail_id = $request->get('simulate_detail_id');

        if (is_null($simulate_detail_id) || empty($simulate_detail_id)) {
            echo json_encode(false);
            exit;
        }

        try {
            $res = SimulateDetail::where('id', $simulate_detail_id)->delete();

            if (!$res) {
                echo json_encode(false);
                exit;
            }

        } catch (QueryException $e) {
            echo json_encode(false);
            exit;
        }

        echo json_encode(true);
    }

    public function deleteSimulate(Request $request)
    {
        $id = $request->input('id');

        if (is_null($id) || empty($id))
            return redirect()->back()->withInput()->withErrors(['failed' => trans('simulate.delete_failed')]);

        try {
            $res = SimulateDetail::where('simulate_id', $id)->delete();

            if (!$res)
                return redirect()->back()->withInput()->withErrors(['failed' => trans('simulate.delete_failed')]);

            $res = Simulate::where('id', $id)->delete();

            if (!$res)
                return redirect()->back()->withInput()->withErrors(['failed' => trans('simulate.delete_failed')]);

        } catch (QueryException $e) {
            return redirect()->back()->withInput()->withErrors(['failed' => trans('simulate.delete_failed')]);
        }

        return redirect()->route('simulate')->with('success', trans('simulate.delete_success'));;
    }

    public function export(Request $request)
    {
        $id = $request->input('id');

        if (is_null($id) || empty($id))
            return redirect()->back()->withInput()->withErrors(['failed' => trans('simulate.export_failed')]);

        $simulate_data = Simulate::where('id', $id)->first();

        if(is_null($simulate_data))
            return redirect()->back()->withInput()->withErrors(['failed' => trans('simulate.export_failed')]);

        return (new SimulateExport($id))->download($simulate_data->name.'.xlsx');
    }
}