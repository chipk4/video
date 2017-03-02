<?php
namespace App\Api\Controllers;

use App\Api\Transformers\Auth\LoginTransformer;
use Illuminate\Http\Request;
use Auth;

class AuthController extends BaseController {

    public function login(Request $request)
    {
        $data = $request->only('email', 'password');
        $user = Auth::attempt(['email' => $data['email'], 'password' => $data['password']]);
        if(!$user) {
            return $this->respondWithError('Invalid credentials');
        }
        $user = Auth::user();
        $user->api_token = str_random(60);
        $user->save();
        $this->setTransformer(new LoginTransformer());
        return $this->respondWithItem($user->api_token);
    }
}