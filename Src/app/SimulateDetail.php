<?php

namespace App;


use Illuminate\Database\Eloquent\Model;

class SimulateDetail extends Model
{
    protected $table = 'tbl_simulate_detail';

    protected $fillable = [
        'id', 'simulate_id', 'plan_id',
    ];

}