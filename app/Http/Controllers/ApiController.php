<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Session;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Message;
use App\MessageHistory;
use App\Device;
use App\Application;
use App\InstallHistory;
use DB;

class ApiController extends Controller
{
    public function addRegId($reg_id, $device_id, $package_name, $app_id)
    {
        header('Content-Type: application/json;charset=UTF-8;');

        $device = Device::where('device_id', '=', $device_id)->first();

        if (!$device)
        {
            $device = new Device();
            $device->device_id = $device_id;
            $device->registration_id = $reg_id;
            $device->save();
        }

        $app = Application::where('package_name', '=', $package_name)->first();

        if ($app)
        {
            $installed = InstallHistory::where('applications_id', '=', $app->id)
                ->where('devices_id' , '=', $device->id)->first();
            if (!$installed)
            {
                $app->installed += 1;
                $app->save();
            }

            $install = new InstallHistory();
            $install->devices_id = $device->id;
            $install->applications_id = $app->id;
            $install->country = 'All';
            $install->install_flag = false;
            $install->registration_id = $reg_id;
            $install->install_from = 'All';
            $install->save();
        }
        else
            return json_encode(array('status'=>500));

        $device->registration_id = $reg_id;
        $device->save();

        if ($device)
            return json_encode(array('status'=>200, $reg_id, $device_id, $package_name, $app->id));
        else
            return json_encode(array('status'=>500));
    }

    public function updateVersion($packageName, $versionName, $versionCode)
    {
        header('Content-Type: application/json;charset=UTF-8;');
        $app = Application::where('package_name', '=', $packageName)->first();

        if ($app){
            if ($app->status == 1){
                if ($app->version_name != $versionName || $app->version_code != $versionCode){
                    return json_encode(array('status'=>200,
                        'title' => $app->update_title,
                        'message' => $app->update_message,
                        'update_type' => intval($app->update_type),
                        'package_name' => $app->update_package,
                        'direct_url' => $app->direct_url,
                        'force_update' => intval($app->force_update),
                    ));
				}
			}
		}
        return json_encode(array('status'=>500));
    }

    public function uninstall($device_id, $package_name, $registration_id)
    {
        $apps = Application::whereIn('package_name', explode("-", $package_name))->get();
        $device = Device::where('device_id', '=', $device_id)->first();
        $status = 404;
        foreach ($apps as $app) {
            if ($app && $device)
            {
                $install = InstallHistory::where('applications_id', '=', $app->id)
                    ->where('registration_id' , '=', $registration_id)
                    ->where('install_flag' , '=', false)
                    ->where('devices_id' , '=', $device->id)
                    ->orderBy('created_at', 'desc')->first();

                if ($install)
                {
                    $install->install_flag = true;
                    $install->save();

                    if ($device->registration_id == $registration_id)
                    {
                        $install = InstallHistory::where('install_flag' , '=', false)
                            ->where('devices_id' , '=', $device->id)
                            ->orderBy('created_at', 'desc')->first();

                        if ($install)
                        {
                            $device->registration_id = $install->registration_id;
                            $device->save();
                        }
                    }
                    $status = 200;
                }
                else if($status != 200){
                    $status = 500;
                }
            }
        }

        return json_encode(array('status'=>$status));
    }

    public function addRegIdV2()
    {
        header('Content-Type: application/json;charset=UTF-8;');

        if(Input::has('reg_id') &&
            Input::has('device_id') &&
            Input::has('package_name') &&
            Input::has('app_id') &&
            Input::has('sdk_version') &&
            Input::has('country_code') &&
            Input::has('android_version'))
        {
            $reg_id = Input::get('reg_id');
            $device_id = Input::get('device_id');
            $package_name = Input::get('package_name');
            $app_id = Input::get('app_id');
            $sdk_version = Input::get('sdk_version');
            $country_code = Input::get('country_code');
            $android_version = Input::get('android_version');

        }

        $device = Device::where('device_id', '=', $device_id)->first();
        if (!$device)
        {
            $device = new Device();
            $device->device_id = $device_id;
            $device->registration_id = $reg_id;
            $device->save();
        }

        $app = Application::where('package_name', '=', $package_name)->first();
        if ($app)
        {
            $installed = InstallHistory::where('applications_id', '=', $app->id)
                ->where('devices_id' , '=', $device->id)->first();
            if (!$installed)
            {
                $app->installed += 1;
                $app->save();
            }

            $install = new InstallHistory();
            $install->devices_id = $device->id;
            $install->applications_id = $app->id;
            $install->country = 'All';
            $install->install_flag = false;
            $install->registration_id = $reg_id;
            $install->install_from = 'All';
            $install->sdk_version = $sdk_version;
            $install->country_code = $country_code;
            $install->android_version = $android_version;
            $install->save();
        }
        else
            return json_encode(array('status'=>500));

        $device->registration_id = $reg_id;
        $device->save();

        if ($device)
            return json_encode(array('status'=>200, $reg_id, $device_id, $package_name, $app->id));
        else
            return json_encode(array('status'=>500));
    }
}
