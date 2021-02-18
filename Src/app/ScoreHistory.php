<?php

namespace App;


use Illuminate\Database\Eloquent\Model;

class ScoreHistory extends Model
{
    protected $table = 'tbl_score_history';

    protected $fillable = [
        'batch_id', 'college_id', 'department_id', 'major_id', 'user_code', 'score', 'ranking', 'min', 'max', 'average', 'year'
    ];
}