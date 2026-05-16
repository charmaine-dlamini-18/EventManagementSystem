<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleCMS extends Model
{
    protected $table = 'roles';

    protected $fillable = ['name', 'description'];
}
