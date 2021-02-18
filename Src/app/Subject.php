<?php

namespace App;


use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $table = 'tbl_subject';

    protected $fillable = [
        'name', 'code', 'description', 'status'
    ];
}