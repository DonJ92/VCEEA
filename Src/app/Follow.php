<?php

namespace App;


use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    protected $table = 'tbl_follow';

    protected $fillable = [
        'user_id', 'plan_id'
    ];
}