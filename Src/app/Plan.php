<?php

namespace App;


use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $table = 'tbl_plan';

    protected $fillable = [
        'batch_id', 'college_id', 'department_id', 'major_id', 'subject_id', 're_subject_id', 'academic_year_code', 'cost', 'recruitment_num', 'description', 'status'
    ];
}