<?php
/**
 * Created by PhpStorm.
 * User: Don Jung
 * Date: 1/26/2021
 * Time: 3:41 AM
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $table = 'tbl_city';

    protected $fillable = [
        'id', 'province_id', 'name',
    ];
}