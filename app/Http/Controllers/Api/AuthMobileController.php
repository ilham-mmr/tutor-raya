<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use GuzzleHttp\Exception\ClientException;

class AuthMobileController extends Controller {


    public function loginMobile(Request $request) {

        $data = $request->validate([
            'email' => 'required',
            'name' => 'required',
        ]);



        $email = $request->email;
        $name = $request->name;
        $picture = $request->picture;

        $userCreated = User::firstOrCreate(
            [
                'email' => $email
            ],
            [
                'email_verified_at' => now(),
                'name' => $name,
                'picture' => $picture
            ]
        );
        if($picture){
            $userCreated->picture = $picture;
            $userCreated->save();
        }
        $token = $userCreated->createToken('tutor-raya')->plainTextToken;

        return response()->json([$userCreated], 200, ['api_token' => $token]);
    }


    public function logout(Request $request) {
        // Auth::user()->tokens()->delete();
        // Revoke the token that was used to authenticate the current request...
        $request->user()->currentAccessToken()->delete();


        return [
            'message' => 'Logged out'
        ];
    }
}
