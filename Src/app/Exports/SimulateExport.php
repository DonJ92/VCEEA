<?php

namespace App\Exports;

use App\Plan;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SimulateExport implements FromQuery, WithHeadings, ShouldAutoSize
{
    use Exportable;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function query()
    {
        return Plan::leftjoin('tbl_simulate_detail', 'tbl_plan.id', '=', 'tbl_simulate_detail.plan_id')
            ->leftjoin('tbl_batch', 'tbl_plan.batch_id', '=', 'tbl_batch.id')
            ->leftjoin('tbl_college', 'tbl_plan.college_id', '=', 'tbl_college.id')
            ->leftjoin('tbl_department', 'tbl_plan.department_id', '=', 'tbl_department.id')
            ->leftjoin('tbl_major', 'tbl_plan.major_id', '=', 'tbl_major.id')
            ->where('tbl_simulate_detail.simulate_id', $this->id)
            ->select('tbl_batch.title as batch_title', 'tbl_batch.code as batch_code', 'tbl_college.name as college_name', 'tbl_college.code as college_code',
                'tbl_department.name as department_name', 'tbl_major.name as major_name', 'tbl_major.code as major_code')
            ->orderby('tbl_simulate_detail.sort', 'ASC');
    }

    public function headings(): array
    {
        return [
            trans('simulate.no'),
            trans('simulate.batch_title'),
            trans('simulate.batch_code'),
            trans('simulate.college_name'),
            trans('simulate.college_code'),
            trans('simulate.department_name'),
            trans('simulate.major_name'),
            trans('simulate.major_code'),
        ];
    }
}
