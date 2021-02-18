<?php

namespace App;


use Illuminate\Database\Eloquent\Model;

class College extends Model
{
    protected $table = 'tbl_college';

    protected $fillable = [
        'name', 'code', 'national_code', 'type', 'addr_province', 'addr_city', 'addr_detail', 'property', 'site_url', 'description', 'status'
    ];

}