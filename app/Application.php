<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'applications';

    protected $fillable = [
        'name', 'icon', 'package_name', 'application_id', 'installed', 'version_code', 'version_name', 'update_title', 'update_message', 'update_type', 'direct_url', 'force_update', 'status', 'update_package'
    ];
}
