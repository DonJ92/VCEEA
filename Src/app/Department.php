<?php
/**
 * Created by PhpStorm.
 * User: Don Jung
 * Date: 1/27/2021
 * Time: 3:02 AM
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $table = 'tbl_department';

    protected $fillable = [
        'name', 'code', 'description', 'status'
    ];
}