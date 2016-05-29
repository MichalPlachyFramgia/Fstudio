<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Application;
use Illuminate\Support\Facades\Input;
class ApplicationController extends Controller
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
        $apps = Application::paginate(20);

        return view('applications.index')->withApps($apps);
    }

    public function create()
    {
        return view('applications.create');
    }

    public function store(Request $request)
    {
        $input = $request->all();

        $this->validate($request, [
            'name' => 'required',
            'application_id' => 'required|unique:applications',
            'icon' => 'required',
            'package_name' => 'required|unique:applications',
        ]);

        $application = new Application;
        $application->name = $input['name'];
        $application->application_id = $input['application_id'];
        $application->package_name = $input['package_name'];

        if ($request->hasFile('icon')) {
            $file = Input::file('icon');
            list($width, $height) = getimagesize($file);
            if ($width == '144' && $height == '144')
            {
                $timestamp = str_replace([' ', ':'], '-', Carbon::now()->toDateTimeString());
                $name = $timestamp. '-' .$file->getClientOriginalName();
                $application->icon = $name;
                $file->move(public_path().'/application_icon/', $name);
            }
            else
            {
                Session::flash('flash_message', 'Image size not corect 144 x 144!');
                return redirect()->back();                
            }
        }
        $application->save();

        Session::flash('flash_message', 'App successfully added!');
        return redirect()->back();
    }

    public function show($id)
    {
        $app = Application::findOrFail($id);

        return view('applications.show')->withApp($app);
    }

    public function edit($id)
    {
        $app = Application::findOrFail($id);

        return view('applications.edit')->withApp($app);
    }

    public function update($id, Request $request)
    {
        $input = $request->all();

        $application = Application::findOrFail($id);

        $this->validate($request, [
            'name' => 'required',
            'application_id' => 'required|unique:applications,id,'.$application->id,
            'package_name' => 'required|unique:applications,id,'.$application->id,
        ]);

        $application->name = $input['name'];
        $application->application_id = $input['application_id'];
        $application->package_name = $input['package_name'];

        if ($request->hasFile('icon')) {
            $file = Input::file('icon');
            list($width, $height) = getimagesize($file);
            if ($width == '144' && $height == '144')
            {
                $timestamp = str_replace([' ', ':'], '-', Carbon::now()->toDateTimeString());
                $name = $timestamp. '-' .$file->getClientOriginalName();
                $file->move(public_path().'/application_icon/', $name);
                unlink(public_path().'/application_icon/'.$application->icon);
                $application->icon = $name;
            }
            else
            {
                Session::flash('flash_message', 'Image size not corect 144 x 144!');
                return redirect()->back();                
            }
        }
        $application->save();
        Session::flash('flash_message', 'App successfully added!');

        return redirect()->back();
    }

    public function destroy($id)
    {
        $app = Application::findOrFail($id);

        $app->delete();

        Session::flash('flash_message', 'App successfully deleted!');

        return redirect()->action('ApplicationController@index');
    }

    public function editVersion($id)
    {
        $app = Application::findOrFail($id);

        return view('applications.updateversion')->withApp($app);
    }

    public function updateVersion($id, Request $request)
    {
        $app = Application::findOrFail($id);

        $this->validate($request, [
            'version_code' => 'required',
            'version_name' => 'required',
            'update_title' => 'required',
            'update_message' => 'required',
        ]);

        $input = $request->all();

        $app->fill($input)->save();

        Session::flash('flash_message', 'App successfully updated!');

        return redirect()->back();
    }
}
