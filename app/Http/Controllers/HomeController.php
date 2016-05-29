<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Input;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use App\Message;
use App\MessageHistory;
use App\Device;
use App\Application;
use App\InstallHistory;
use DB;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $devicesCount = Device::count();
        $newDevice = DB::select(DB::raw(
            "SELECT COUNT(*) as count FROM devices WHERE date(date_add(created_at, INTERVAL 7 HOUR)) = '".date('Y-m-d', time())."'"));
        if (!empty($newDevice))
            $newDevice = $newDevice[0]->count;
        else
            $newDevice = "0";

        $q1newUser = "SELECT min(created_at) as created_at, devices_id, applications_id from install_histories GROUP BY devices_id, applications_id";
        $q2newUser = "SELECT count(*) as users from (".$q1newUser.") as q1 WHERE date(date_add(created_at, INTERVAL 7 HOUR)) = '".date('Y-m-d', time())."'";
        $newUser = DB::select(DB::raw($q2newUser));
        if (!empty($newUser))
            $newUser = $newUser[0]->users;
        else
            $newUser = "0";
        $page = Input::get('page', 1);

        //Lấy device_id và application_id trong lịch sử tải trong 1 ngày tính từ hiện tại
        //Lấy lượt tải trừ đi các lượt tải lặp lại
        $q1 = "SELECT min(created_at) as created_at, devices_id, applications_id from install_histories GROUP BY devices_id, applications_id";
        $q2 = "SELECT devices_id, applications_id from (".$q1.") as q1 WHERE date(date_add(created_at, INTERVAL 7 HOUR)) = '".date('Y-m-d', time())."'";
        //Đếm lượt tải của tung app
        $q3 = "SELECT count(*) as installed, applications_id from (".$q2.") as q1 group by applications_id";

        // Lấy id của các devices được tạo trong trong trước đó 1 ngày
        $q4 = "SELECT id from devices where date(date_add(created_at, INTERVAL 7 HOUR)) = '".date('Y-m-d', time())."'";

        // Lấy lọc duy nhất các devices và các app mới tạo trong ngày hiện tại
        $q5 = "SELECT devices_id, applications_id from (".$q2.") as q22 where devices_id IN (".$q4.")";
        //Đếm tổng device cài đặt tương ứng với từng app
        $q6 = "SELECT count(*) as total_devices, applications_id from (".$q5.") as q5 group by applications_id";
        $apps = DB::select(DB::raw(
            "SELECT a.id, a.name, a.icon, q6.total_devices, a.package_name, q3.installed FROM applications as a 
                left join (".$q3.") as q3 on a.id = q3.applications_id
                left join (".$q6.") as q6 on a.id = q6.applications_id
				 ORDER BY q3.installed desc
            LIMIT ".($page*20-20).",20"));

        if($page*20 >= Application::count())
            $pagi = false;
        else
            $pagi = new Paginator(array_values(Application::all()->toArray()), 20, intval($page));

        $appCount = Application::count();
        $messageCount = Message::count();
        $users = DB::select(DB::raw("SELECT count(id) as count FROM (SELECT id FROM install_histories GROUP BY applications_id, devices_id) as a"));

        return view('home', [
            'devicesCount' => $devicesCount,
            'newUser' => $newUser,
            'newDevice' => $newDevice,
            'users_count' => $users[0]->count,
            'appCount' => $appCount,
            'messageCount' => $messageCount,
            'apps' => $apps,
            'pagi' => $pagi,
        ]);
    }
}
