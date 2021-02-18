<?php

namespace App;


use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $table = 'tbl_log';

    protected $fillable = [
        'user_id', 'log_type'
    ];
}