<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;

class SetupController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function generate()
    {
        $key = str_random(32);
        $app_key = "base64:" . base64_encode($key);
        $authorization_key = Hash::make($key);

        $display = "
            <p>
                <h2>Environment Variables</h2>
                APP_KEY=<b>$app_key</b>
                <br/>
                AUTHORIZATION_KEY=<b>$authorization_key</b>
            </p>
            <p>
                <h2>Request Header</h2>
                <strong>Authorization</strong> &emsp;&emsp; <b>$key</b>
            </p>
        ";

        return response($display)->header('Content-type', 'text/html');
    }
}
