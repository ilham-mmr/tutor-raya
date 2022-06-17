<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index() {
        $user = Auth::user();
        return view('tutoring.profile', compact('user'));
    }

    public function store(Request $request) {

        $request->validate([
            'about' => 'required',
            'education' => 'required',
            'phone' => 'required|numeric',
            'degree' => 'required',
            'profile_picture' => 'max:2048|image|mimes:png,jpg,jpeg'
        ]);

        // dd($request->all());

        $user = Auth::user();

        // upload profile picture
        $updatedProfilePicture = $request->file('profile_picture');
        if ($updatedProfilePicture) {
            $currentProfilePicture = $user->picture;
            if ($currentProfilePicture) {
                Storage::disk('public')->delete($currentProfilePicture);
            }
            $path =  $updatedProfilePicture->store('images/profile', 'public');
            $user->picture = $path;
        }

        $user->about = $request->about;
        $user->education = $request->education;
        $user->about = $request->about;
        $user->degree = $request->degree;
        $user->phone_number = $request->phone;
        $user->active = $request->status == "on" ? true : false;
        $user->save();

        return redirect('/home/tutor/profile')->with('message', 'your profile has been successfully update.');
    }

}
