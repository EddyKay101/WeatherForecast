<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Auth;
use GuzzleHttp\Client;

class MainController extends Controller
{
    function index() {
        return view('login');
    }

    function checkLogin(Request $request){
        $this->validate($request, [
            'email'   => 'required|email',
            'password' => 'required|alphaNum|min:3'
        ]);

        $user_data = array(
            'email' => $request->get('email'),
            'password' => $request->get('password')
        );

        if(Auth::attempt($user_data)){
            return redirect('main/weather');
        }
        else {
            return back()->with('error', 'Wrong Login Details');
        }
    }

    function weather() {
        $client = new Client();

        if(isset($_POST['location'])){
            $location = htmlentities($_POST['location']);
            $g_loc = str_replace(' ', '+', $location);
            $geocode_url = $client->post('https://maps.googleapis.com/maps/api/geocode/json?address='.$g_loc.'&key=AIzaSyDu20S1HqBqkvoU6kKXqTaVXycy5uETrP0');
            $geo_response =  $geocode_url->getBody()->getContents();
            $geo_data = json_decode($geo_response, true);
            $coord = $geo_data['results'][0]['geometry']['location'];
            $coords = $coord['lat'].','.$coord['lng'];
            $req = $client->get('https://api.darksky.net/forecast/6c314524bbb5e8ea74d27ea433a64a19/'.$coords);
            $response = $req->getBody()->getContents();
            $data = json_decode($response, true);
            $count = 0;
        }
        return view('weather', compact('data','count','location'));

    }

    public static function celcius($temp) {
        return ($temp-32)*.5556;
    }

    public static function get_icon($icon){
        if($icon =='clear-day'){
            $the_icon = '<i class="wi wi-day-sunny"></i>';
            return $the_icon;
        }
        elseif($icon =='clear-night'){
            $the_icon = '<i class="wi wi-clear-night"></i>';
            return $the_icon;
        }
        elseif($icon =='rain'){
            $the_icon = '<i class="wi wi-rain"></i>';
            return $the_icon;
        }
        elseif($icon =='snow'){
            $the_icon = '<i class="wi wi-snow"></i>';
            return $the_icon;
        }
        elseif($icon =='sleet'){
            $the_icon = '<i class="wi wi-sleet"></i>';
            return $the_icon;
        }
        elseif($icon =='wind'){
            $the_icon = '<i class="wi wi-strong-windy"></i>';
            return $the_icon;
        }
        elseif($icon =='fog'){
            $the_icon = '<i class="wi wi-fog"></i>';
            return $the_icon;
        }
        elseif($icon =='cloudy'){
            $the_icon = '<i class="wi wi-cloudy"></i>';
            return $the_icon;
        }
        elseif($icon =='partly-cloudy-day'){
            $the_icon = '<i class="wi wi-day-sunny-overcast"></i>';
            return $the_icon;
        }
        elseif($icon =='partly-cloudy-night'){
            $the_icon = '<i class="wi wi-night-alt-cloudy"></i>';
            return $the_icon;
        }
        else {
            $the_icon = '<i class="wi wi-thermometer"></i>';
            return $the_icon;
        }
    }

    function logout() {
        Auth::logout();
        return redirect('main');
    }


}
