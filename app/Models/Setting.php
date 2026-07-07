<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable([
    'app_name',
    'copyright',
    'login_title',
    'keywords',
    'description',
    'logo',
    'company_address',
    'signatory_name',
    'signatory_position'
])]

class Setting extends Model
{
    //
}
