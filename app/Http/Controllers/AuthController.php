<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use GuzzleHttp\Exception\ClientException;

class AuthController extends Controller {
    public function index() {
        return view('auth.sign-in');
    }

    /**
     * Redirect the user to the Provider authentication page.
     *
     * @param $provider
     * @return JsonResponse
     */
    public function redirectToProvider($provider) {
        // dd($provider);

        $validated = $this->validateProvider($provider);
        if (!is_null($validated)) {
            return $validated;
        }

        return Socialite::driver($provider)->with(['state' => 'is_web=true'])->redirect();
    }

    /**
     * @param $provider
     * @return JsonResponse
     */
    protected function validateProvider($provider) {
        if (!in_array($provider, ['facebook', 'google'])) {
            return redirect()->route('sign-in')->with('message','Ooops, There is an error');
        }
    }


    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request) {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('sign-in')->with('message','You are logged out!');
    }
}
