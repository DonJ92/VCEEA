<?php

namespace App;


use Illuminate\Database\Eloquent\Model;

class Province extends Model
{

    protected $table = 'tbl_province';

    protected $fillable = [
        'id', 'name',
    ];

}