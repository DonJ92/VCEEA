<?php
namespace App\Http\Controllers\Users;


use App\Follow;
use App\Http\Controllers\Controller;
use App\Restriction;
use App\Simulate;
use App\SimulateDetail;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FollowController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $batch_list = $this->getBatchListFromID();
        $major_list = $this->getMajorListFromID();
        $type_list = config('constants.college_type');
        $property_list = config('constants.college_property');
        $subject_list = $this->getSubjectListFromID();

        $data['batch_list'] = $batch_list;
        $data['major_list'] = $major_list;
        $data['type_list'] = $type_list;
        $data['property_list'] = $property_list;
        $data['subject_list'] = $subject_list;

        $this->registerLog(LOG_FOLLOW_PAGE);

        return view('follow', $data);
    }

    public function getFollowList(Request $request)
    {
        $batch_title = $request->input('batch_title');
        $batch_code = $request->input('batch_code');
        $college_type = $request->input('college_type');
        $college_property = $request->input('college_property');
        $major_name = $request->input('major_name');
        $subject_id = $request->input('subject_id');
        $re_subject_id = $request->input('re_subject_id');

        $follow_list = array();

        try{
            $query = Follow::leftjoin('tbl_plan', 'tbl_follow.plan_id', '=', 'tbl_plan.id')
                ->leftjoin('tbl_batch', 'tbl_plan.batch_id', '=', 'tbl_batch.id')
                ->leftjoin('tbl_college', 'tbl_plan.college_id', '=', 'tbl_college.id')
                ->leftjoin('tbl_province', 'tbl_province.id', '=', 'tbl_college.addr_province')
                ->leftjoin('tbl_department', 'tbl_plan.department_id', '=', 'tbl_department.id')
                ->leftjoin('tbl_major', 'tbl_plan.major_id', '=', 'tbl_major.id')
                ->leftjoin('tbl_subject', 'tbl_plan.subject_id', '=', 'tbl_subject.id')
                ->leftjoin('tbl_subject as tbl_re_subject', 'tbl_plan.re_subject_id', '=', 'tbl_re_subject.id')
                ->where('tbl_follow.user_id', Auth::user()->id)
                ->select('tbl_plan.*', 'tbl_batch.id as batch_id', 'tbl_batch.title as batch_title', 'tbl_batch.code as batch_code',
                    'tbl_college.name as college_name', 'tbl_college.code as college_code', 'tbl_college.site_url as college_url', 'tbl_college.type as college_type', 'tbl_college.property as college_property',
                    'tbl_province.name as addr_province', 'tbl_department.name as department_name', 'tbl_department.code as department_code',
                    'tbl_major.name as major_name', 'tbl_major.code as major_code', 'tbl_subject.name as subject_name', 'tbl_re_subject.name as re_subject_name')
                ->orderBy('batch_code', 'ASC');

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
            echo json_encode($follow_list);
            exit;
        }

        $type_list = config('constants.college_type');
        $property_list = config('constants.college_property');

        for ($i = 0; $i < count($follow_list); $i++) {
            $follow_list[$i]['college_type'] = $type_list[$follow_list[$i]['college_type']-1]['type'];
            $follow_list[$i]['college_property'] = $property_list[$follow_list[$i]['college_property']-1]['property'];
        }

        echo json_encode($follow_list);
    }

    public function getRestriction(Request $request)
    {
        $batch_id = $request->input('batch_id');

        $restriction_id_list = array();

        if (is_null($batch_id) || empty($batch_id)) {
            echo json_encode($restriction_id_list);
            exit;
        }

        try {
            $restriction_list = Restriction::where('batch_id_list', 'LIKE', $batch_id.',%')
                ->orWhere('batch_id_list', 'LIKE', '%,'.$batch_id.',%')
                ->orWhere('batch_id_list', 'LIKE', '%,'.$batch_id)
                ->get()->toArray();

            foreach ($restriction_list as $restriction_info){
                $restriction_list = explode(',', $restriction_info['batch_id_list']);
                if (($key = array_search($batch_id, $restriction_list)) !== false) {
                    unset($restriction_list[$key]);
                }

                $restriction_id_list = array_merge($restriction_id_list, $restriction_list);
            }
        } catch (QueryException $e) {
            echo json_encode($restriction_id_list);
            exit;
        }

        echo json_encode($restriction_id_list);
    }

    public function simulate(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors(['failed' => trans('follow.warning')]);
        }

        try {
            $simulate_name = trans('common.simulate') . '_' . date('YmdHis');
            $simulate_info = Simulate::create([
                'user_id' => Auth::user()->id,
                'name' => $simulate_name
            ]);

            $id = $simulate_info->id;

            $insert_data = array();
            $sort = 1;
            foreach ($data['id'] as $plan_id)
                $insert_data[] = ['simulate_id' => $id, 'plan_id' => $plan_id, 'sort' => $sort++];

            $res = SimulateDetail::insert($insert_data);

        } catch (QueryException $e) {
            return redirect()->back()->withInput()->withErrors(['failed' => trans('follow.add_failed')]);
        }

        return redirect()->route('follow')->with('success', trans('follow.add_success'));;
    }
}