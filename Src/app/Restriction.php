<?php

namespace App;


use Illuminate\Database\Eloquent\Model;

class Restriction extends Model
{
    protected $table = 'tbl_restriction';

    protected $fillable = [
        'name', 'batch_id_list', 'description', 'status'
    ];

}