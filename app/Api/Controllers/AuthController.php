<?php
namespace App\Api\Controllers;

use Illuminate\Http\Request;
use Auth;

class AuthController extends BaseController {
    public function login(Request $request)
    {
        $data = $request->only('email', 'password');
        $user = Auth::attempt(['email' => $data['email'], 'password' => $data['password']]);
        if($user) {
            $user = Auth::user();
            $user->api_token = str_random(60);
            $user->save();
            return json_encode($user->api_token);
        } else {
            return json_encode([
                'error' => 'Invalid credentials'
            ]);
        }
    }
}