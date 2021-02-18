<?php

namespace App;


use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    protected $table = 'tbl_batch';

    protected $fillable = [
        'title', 'code', 'description', 'status'
    ];

}