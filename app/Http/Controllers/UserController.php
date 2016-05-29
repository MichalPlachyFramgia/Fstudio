<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Input;
use App\Http\Requests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Message;
use App\MessageHistory;
use App\Device;
use App\Application;
use App\InstallHistory;
use DB;
use Carbon\Carbon;

class UserController extends BaseController {

  public function getUsersApi()
  {
    $days = Input::get('days', 30);
    // Lấy bản ghi device trong vòng 30 ngày gần nhất
    $q1 = "SELECT DATE(date_add(created_at, INTERVAL 7 HOUR)) as daytime, id FROM devices WHERE date_add(created_at, INTERVAL 7 HOUR) > '".
      date('Y-m-d', time() - $days*24*3600)." 00:00:00'";

    // Đếm số device trong 30 ngày
    $q2 = "SELECT daytime, COUNT(*) as devicecount FROM (".$q1.") as q1 GROUP BY daytime ORDER BY daytime DESC";

    //Lấy lượt tải trừ đi các lượt tải lặp lại
    $q1newUser = "SELECT min(created_at) as created_at, devices_id, applications_id from install_histories GROUP BY devices_id, applications_id";
    //lọc ngày
    $q2newUser = "SELECT DATE(date_add(created_at, INTERVAL 7 HOUR)) as daytime, devices_id, applications_id from (".$q1newUser.") as q1 WHERE date(date_add(created_at, INTERVAL 7 HOUR)) > '". date('Y-m-d', time() - $days*24*3600)." 00:00:00'";


    //Đếm số lượt tải thực tế trong 30 ngày
    $q3 = "SELECT daytime, count(*) as users FROM (".$q2newUser.") as q2newUser GROUP BY daytime ORDER BY daytime DESC";

    //Đếm số lượt tải trong vòng 30 ngày gần nhất
    $result = DB::select(DB::raw("SELECT q3.daytime, devicecount, users FROM (".$q3.") as q3
        LEFT JOIN (".$q2.") as q2 
        ON q3.daytime = q2.daytime
      "));
    $usersChartData = json_encode($result); 
    return $usersChartData;
  }

  public function getApplicationChart()
  {
    $days = 30;
    $app_id = Input::get('app_id', 3);
    //Lấy lượt tải trừ đi các lượt tải lặp lại
    $q1newUser = "SELECT min(created_at) as created_at, devices_id from install_histories WHERE applications_id = ".$app_id." GROUP BY devices_id";
    //lọc ngày
    $q2newUser = "SELECT DATE(date_add(created_at, INTERVAL 7 HOUR)) as daytime, devices_id from (".$q1newUser.") as q1 WHERE date(date_add(created_at, INTERVAL 7 HOUR)) > '". date('Y-m-d', time() - $days*24*3600)." 00:00:00'";

    // đếm số devices mới mỗi ngày
    $q1 = "SELECT daytime, COUNT(*) as users_sum FROM (".$q2newUser.") as q2newUser GROUP BY daytime ORDER BY daytime ASC";

    $result = DB::select(DB::raw($q1));

    $usersChartData = [];
    foreach ($result as $key => $value) {
      $usersChartData[] = intval($value->users_sum);
    }
    return $usersChartData;
  }
}
