<?php

namespace App\Models\API;

use App\Models\Admin\Admins as AdminAdmins;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Libraries\FileSystem;

class Admins extends AdminAdmins
{
	protected $hidden = ['token', 'password'];

	/**
    * Get resize images
    *
    * @return array
    */
    public function getImageAttribute($value)
    {
        return $value ? FileSystem::getAllSizeImages($value) : null;
    }
}