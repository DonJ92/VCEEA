<?php

namespace App;


use Illuminate\Database\Eloquent\Model;

class Major extends Model
{
    protected $table = 'tbl_major';

    protected $fillable = [
        'name', 'code', 'national_code', 'description', 'status'
    ];
}