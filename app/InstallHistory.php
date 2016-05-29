<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InstallHistory extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'install_histories';

    protected $fillable = [
        'devices_id', 'applications_id', 'country', 'install_from', 'registration_id', 'install_flag', 'sdk_version', 'country_code', 'android_version'
    ];
}
