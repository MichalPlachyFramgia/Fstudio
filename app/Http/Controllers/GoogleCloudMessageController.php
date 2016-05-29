<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Session;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Message;
use App\MessageHistory;
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
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $messages = Message::paginate(10);
        $applications = Application::all();

        return view('messages.index')->withMessages($messages)->withApplications($applications);
    }

    public function create()
    {
        return view('messages.create');
    }

    public function store(Request $request)
    {
        $input = $request->all();

        $this->validate($request, [
            'title' => 'required',
            'content' => 'required',
            'icon' => 'required',
            'package_name' => 'required',
        ]);

        Message::create($input);

        Session::flash('flash_message', 'Message successfully added!');
        return redirect()->back();
    }

    public function show($id)
    {
        // $message = Message::findOrFail($id);

        // return view('messages.show')->withMessage($message);
    }

    public function edit($id)
    {
        $message = Message::findOrFail($id);

        return view('messages.edit')->withMessage($message);
    }

    public function update($id, Request $request)
    {
        $message = Message::findOrFail($id);

        $this->validate($request, [
            'title' => 'required',
            'content' => 'required',
            'icon' => 'required',
            'package_name' => 'required',
        ]);

        $input = $request->all();

        $message->fill($input)->save();

        Session::flash('flash_message', 'Message successfully edited!');

        return redirect()->back();
    }

    public function sendAll(Request $request)
    {
        $input = $request->all();
		if (!array_key_exists('applications', $input)){
			Session::flash('flash_message', 'Failed');
			return redirect()->action('GoogleCloudMessageController@index');
		}
        $message = Message::findOrFail($input['message_id']);
        $successs = 0;
        $faileds = 0;
        $num = 999;
        $offset = 0;
		if (isset($input['applications'][0]) && $input['applications'][0] == '0'){
			$installed = DB::table('install_histories')->lists('devices_id');
		}
		else{
			$installed = DB::table('install_histories')->whereIn('applications_id', $input['applications'])->lists('devices_id');
		}
        $counts = DB::table('devices')->whereIn('id', $installed)->count();
        while ($counts > 0){
            list ($success, $failed) = $this->sendFor($input['message_id'], $installed, $num, $offset);
            $successs += intval($success);
            $faileds += intval($failed);
            $offset = $num;
            $counts -= $num;
        }

        MessageHistory::create(array('message_id' => $input['message_id'], 'success' => $successs, 'failed' => $faileds));
        $message->success = $successs;
        $message->failed = $faileds;
        $message->send_at = date('Y/m/d h:i:s', time());
        $message->save();

        Session::flash('flash_message', 'Message successfully send!');
        return redirect()->action('GoogleCloudMessageController@index');
    }

    public function sendFor($message_id, $reg_ids, $num, $offset)
    {
        $registration_ids = DB::table('devices')->whereIn('id', $reg_ids)->skip($offset)->take($num)->lists('registration_id');
        $message = Message::findOrFail($message_id);
        if (empty($registration_ids))
        {
            Session::flash('flash_message', 'Dont have any devices!');
            return redirect()->action('GoogleCloudMessageController@index');
        }
        $data = array('message' => array(
            'title' => $message->title,
            'content' => $message->content,
            'icon' => $message->icon,
            'package_name' => $message->package_name,
            'type' => $message->type,
            'direct_url' => $message->direct_url,
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
        usleep(1000000);
        // Debug GCM response  
        $data = json_decode($result, true);
        return array($data['success'], $data['failure']);
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

    public function messageHistory($message_id)
    {
        $message_histories = MessageHistory::where('message_id', '=', $message_id)->orderBy('created_at', 'desc')->paginate(20);

        return view('message_history.index')->withMessageHistories($message_histories);
    }
}
