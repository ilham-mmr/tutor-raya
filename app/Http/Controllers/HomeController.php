<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Tutoring;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller {
    public function index() {

        return view('dashboard.index');
    }

    public function profile() {
        $user = Auth::user();
        return view('tutoring.profile', compact('user'));
    }


    public function addTutoring(Request $request) {
        $categoriesQuery = Category::query();
        $categories = $categoriesQuery->get();
        $selectedCategory = $request->category ?? 1;


        $categoriesQuery->with('subjects')->where('id', $selectedCategory);
        $subjects = $categoriesQuery->firstOrFail()->subjects;

        // dd($subjects);

        return view('tutoring.add-tutoring', compact(
            'categories',
            'subjects',
            'selectedCategory'
        ));
    }
    public function storeProfile(Request $request) {

        $request->validate([
            'about' => 'required',
            'education' => 'required',
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
        $user->active = $request->status == "on" ? true : false;
        $user->save();

        return redirect('/home/tutor/profile')->with('message', 'your profile has been successfully update.');
    }


    public function storeTutoring(Request $request) {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'subject' => 'required',
            'hourly_price' => 'required|numeric|min:1',
            'hours' => 'required|numeric|min:1',
            'teachingDateTime' => 'required|after_or_equal:' . now(),
        ]);


        $startTime = Carbon::parse($request->teachingDateTime)->setTimezone('Asia/Jakarta')->setSecond(0);
        $endTime = Carbon::parse($request->teachingDateTime)->setTimezone('Asia/Jakarta')->setSecond(0)->addHours($request->hours);


        $overlappedTutorings = Auth::user()->tutorings()->ofBetween($startTime->toDateTimeString())->count();

        if ($overlappedTutorings > 0) {
            return redirect()->back()->withErrors(['overlapped' => 'Oops! You already have a tutoring session on the given time']);
        }


        Tutoring::create([
            'tutor_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'subject_id' => $request->subject,
            'hourly_price' => $request->hourly_price,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'hourly_duration' => $request->hours,
            'label_color' => $this->getRandomizedColor()
        ]);
        return redirect()->route('web-tutoring.create')->with('message', 'the tutoring session has been successfully added.');
    }


    public function viewTutoring() {
        // get valid tutorings
        $tutorings = Auth::user()->tutorings()->whereDate('start_time', '>=', Carbon::today())->get();

        $events = $tutorings->map(function ($tutoring) {
            return [
                'title' => $tutoring->title,
                'start' => $tutoring->start_time,
                'end' => $tutoring->end_time,
                'backgroundColor' => $tutoring->label_color,
                'borderColor' => $tutoring->label_color,
            ];
        });


        return view('tutoring.view-tutoring', compact('tutorings', 'events'));
    }

    public function getRandomizedColor() {
        $availableColors = ["#6610f2", "#3c8dbc",  "#605ca8", "#f012be", "#d81b60", "#ff851b", "#01ff70", "#39cccc", "#3d9970"];
        return $availableColors[rand(0, count($availableColors) - 1)];
    }
}
