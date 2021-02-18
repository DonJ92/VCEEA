<?php
namespace App;


use Illuminate\Database\Eloquent\Model;

class Simulate extends Model
{
    protected $table = 'tbl_simulate';

    protected $fillable = [
        'id', 'user_id', 'name',
    ];

}