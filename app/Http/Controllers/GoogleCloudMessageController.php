<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Session;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Message;
use App\MessageHistory;
use App\InstallHistory;
use App\Device;
use App\Application;
use DB;
class GoogleCloudMessageController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => array('search', 'sendAll')]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $messages = Message::all();
        $applications = Application::all();
        $devices_count = Device::count();
        return view('messages.index')->withMessages($messages)->withApplications($applications)->withDevicesCount($devices_count);
    }

    public function search(Request $request)
    {
        $input = $request->all();
        $search_text = trim($input["search_data"]);
        if (empty($search_text))
            return "";

        $html = "";
        if ($input["application"] != "false")
        {
            $applications = Application::where('name', 'LIKE', '%'.$search_text.'%')->get();
            $applicationsBoxs = "<div class='row' style='overflow:scroll;height:280px;'>";
            foreach ($applications as $application) {
                $applicationsBoxs .= $this->drawApplicationBox($application->id, $application->name, $application->icon);
            }
            $applicationsBoxs .= "</div>";

            $html .= $applicationsBoxs;
        }

        if ($input["country"] != "false")
        {
            $install_countries = DB::select(DB::raw("SELECT country_code FROM install_histories where country_code is not null group by country_code"));
            $coutriesAll = array_flip(config('countries'));
            $install_country = array_map(function($install_country){
                $coutriesAll = array_flip(config('countries'));
                return array($install_country->country_code => $coutriesAll[strtoupper($install_country->country_code)]);
            }, $install_countries);

            $countries = array_filter($install_country, function($el) use ($search_text) {
                $value = array_values($el);
                return ( strpos(strtolower($value[0]), strtolower($search_text)) !== false );
            });

            $countriesBoxs = "<div class='row' style='overflow:scroll;height:280px;'>";
            foreach ($countries as $id => $country) {
                foreach ($country as $country_code => $country_name) {
                    $countriesBoxs .= $this->drawCountryBox($country_code, $country_name);
                }
            }
            $countriesBoxs .= "</div>";

            $html .= $countriesBoxs;
        }

        $reg_country = '';
        if (isset($input['countries_list']) && $input['countries_list'] != [])
            $reg_country = 'where country_code IN ("' . implode($input['countries_list'], '","') . '")';

        $reg_app = '';
        if (isset($input['app_list']) && $input['app_list'] != [])
            $reg_app = 'applications_id IN ("' . implode($input['app_list'], '","') . '")';

        if ($reg_country != '' && $reg_app != ''){
            $reg_ids = DB::select(DB::raw("SELECT count(*) as c from devices where id in (SELECT devices_id from install_histories ".$reg_country." AND ".$reg_app.")"));
        }
        else{
            if ($reg_app != '')
                $reg_app = " where ". $reg_app;
            $reg_ids = DB::select(DB::raw("SELECT count(*) as c from devices where id in (SELECT devices_id from install_histories ".$reg_country.$reg_app.")"));
        }


        return json_encode(array($html,$reg_ids[0]->c));
    }

    public function drawCountryBox($code, $name){
        return "
            <div id='country_$code' class='col-md-3' style='width:150px;height:80px;'>
            <input type='checkbox' name='country' id='checkbox_$code' value='$code' style=''/>
            <script>
            $('#country_$code').click(function() {
                setTimeout(function() {
                    ajax_call(true);
                  }, 10);
                
                if ($('#checkbox_$code').is(':checked')){
                    $('#checkbox_$code').prop('checked', false);
                }
                else{
                    $('#checkbox_$code').prop('checked', true);
                }
            });
            </script>
            <div>$name</div>
            </div>
        ";
    }

    public function drawApplicationBox($id, $name, $link){
        return "
            <div id='application_$id' class='col-md-3' style='width:150px;height:180px;'>
            <input type='checkbox' name='application' id='checkbox_$id' value='$id' style=''/>
            <script>
            $('#application_$id').click(function() {
                ajax_call(true);
                if ($('#checkbox_$id').is(':checked')){
                    $('#checkbox_$id').prop('checked', false);
                }
                else{
                    $('#checkbox_$id').prop('checked', true);
                }
            });
            </script>
            <div><img src='$link' style='width:90px;height:90px;'/></div>
            <div>$name</div>
            </div>
        ";
    }

    public function sendAll(Request $request)
    {
        $input = $request->all();
        $reg_country = '';
        if (isset($input['country']) && $input['country'] != [])
            $reg_country = 'where country IN ("' . implode($input['country'], '","') . '")';

        $reg_app = '';
        if (isset($input['application']) && $input['application'] != [])
            $reg_app = 'applications_id IN ("' . implode($input['application'], '","') . '")';
        $connect = '';
        if ($reg_country != '' && $reg_app != ''){
            $connect = $reg_country." AND ".$reg_app;
        }
        else{
            if ($reg_app != '')
                $reg_app = " where ". $reg_app;
            $connect = $reg_country.$reg_app;
        }
        
        if ($input['type'])
        {
            $app = Application::where('package_name', '=', $input['url'])->first();
        }else
        {
            $app = Application::where('direct_url', '=', $input['url'])->first();
        }
        if ($app){
            if ($connect != ''){
                $connect .= "and devices_id not in (select devices_id from install_histories where applications_id = ".$app->id.")";
            }
            else{
                $connect .= "where devices_id not in (select devices_id from install_histories where applications_id = ".$app->id.")";
            }
        }
        $reg_ids = "SELECT registration_id from devices where id in (SELECT devices_id from install_histories ".$connect.")";
        if (($input['end'] - $input['start']) >= 1000 ){
            $reg_ids = DB::select(DB::raw($reg_ids. " limit ".$input['start'].",999"));
        }
        else{
            $end = 0;
            $reg_ids = DB::select(DB::raw($reg_ids. " limit ".$input['start'].",".$input['end']));
        }

        $registration_ids = [];
        foreach ($reg_ids as $key => $value) {
            $registration_ids[] = $value->registration_id;
        }
        $end = 1;
        if ($input['message_history_id'] == 0)
        {
            $message_history = new MessageHistory();
        }
        else
        {
            $message_history = MessageHistory::find($input['message_history_id']);
        }

        if ($input['message_id'] == 0)
        {
            $message = new Message();
            $message->icon = $input['icon'];
            $message->title = $input['title'];
            $message->content = $input['content'];
            $message->type = $input['type'];
            if ($input['type'])
            {
                $message->package_name = $input['url'];
            }else
            {
                $message->direct_url = $input['url'];
            }
        }else{
            $message = Message::find($input['message_id']);
            $message->icon = $input['icon'];
            $message->title = $input['title'];
            $message->content = $input['content'];
            $message->type = $input['type'];
            if ($input['type'])
            {
                $message->package_name = $input['url'];
            }else
            {
                $message->direct_url = $input['url'];
            }
        }
        $message->save();
        $message_history->icon = $input['icon'];
        $message_history->title = $input['title'];
        $message_history->content = $input['content'];
        $message_history->type = $input['type'];
        $message_history->url = $input['url'];

        
        if (empty($registration_ids))
        {
            Session::flash('flash_message', 'Dont have any devices!');
            return redirect()->action('GoogleCloudMessageController@index');
        }
        $data = array('message' => array(
            'title' => $input['title'],
            'content' => $input['content'],
            'icon' => $input['icon'],
            'package_name' => $input['url'],
            'type' => $input['type'],
            'direct_url' => $input['url'],
        ));

        $applicationId = "AIzaSyCXbGkRY9_Mx4v7iU3UMGCnhR6NGcIQRoY";
        $url = 'https://gcm-http.googleapis.com/gcm/send';

        $postData = array(
                    'registration_ids' => $registration_ids,
                    'data'             => $data,
                    );

        $headers = array(
                        'Authorization: key=' . $applicationId,
                        'Content-Type: application/json;charset=UTF-8;',
                    );

        // Initialize curl handle       
        $ch = curl_init();

        // Set URL to GCM endpoint      
        curl_setopt( $ch, CURLOPT_URL, $url );

        // Set request method to POST       
        curl_setopt( $ch, CURLOPT_POST, true );

        // Set our custom headers       
        curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers );

        // Get the response back as string instead of printing it       
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

        // Set JSON post data
        curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $postData ) );

        // Actually send the push   
        $result = curl_exec( $ch );

        // Error handling
        if ( curl_errno( $ch ) )
        {
            echo 'GCM error: ' . curl_error( $ch );
        }

        // Close curl handle
        curl_close( $ch );
        // Debug GCM response  
        $data = json_decode($result, true);
        $message_history->success += $data['success'];
        $message_history->failed += $data['failure'];
        $message_history->save();

        return array($end, $data['success'], $data['failure'], $message->id, $message_history->id);
    }

    public function destroy($id)
    {
        $message = Message::findOrFail($id);

        $message->delete();

        Session::flash('flash_message', 'Task successfully deleted!');

        return redirect()->action('GoogleCloudMessageController@index');
    }

    public function destroyAll()
    {
        return view('list_google_message');
    }

    public function showHistories()
    {
        // $message_histories = MessageHistory::where('message_id', '=', $message_id)->orderBy('created_at', 'desc')->paginate(20);

        return view('message_history.index')->withMessageHistories($message_histories);
    }
}
