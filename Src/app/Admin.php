<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use Notifiable;

    protected $table = 'tbl_admin';

    protected $guard = 'admin';

    protected $fillable = [
        'admin_code', 'name', 'id_code', 'email', 'phone', 'password', 'role'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];
}
