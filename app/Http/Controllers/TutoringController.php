<?php

namespace App\Http\Controllers;



use App\Models\Category;
use App\Models\Tutoring;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
class TutoringController extends Controller
{
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

    public function editTutoring(Request $request, Tutoring $tutoring)
    {
        $categoriesQuery = Category::query();
        $categories = $categoriesQuery->get();
        $selectedCategory = $request->category ?? $tutoring->subject->category->id;


        $categoriesQuery->with('subjects')->where('id', $selectedCategory);
        $subjects = $categoriesQuery->firstOrFail()->subjects;

        return view('tutoring.edit-tutoring', compact('selectedCategory', 'categories', 'subjects', 'tutoring'));
    }


    public function updateTutoring(Request $request, Tutoring $tutoring)
    {
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

        // get overllaped tutorings which are not current tutoring
        $overlappedTutorings = Auth::user()->tutorings()->ofBetween($startTime->toDateTimeString())->where('id', '!=', $tutoring->id)->count();

        if ($overlappedTutorings > 0) {
            return redirect()->back()->withErrors(['overlapped' => 'Oops! You already have a tutoring session on the given time']);
        }

        $tutoring->update([
            'title' => $request->title,
            'description' => $request->description,
            'subject_id' => $request->subject,
            'hourly_price' => $request->hourly_price,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'hourly_duration' => $request->hours,
        ]);
        return redirect('/home/tutor/view-tutoring')->with('message', "the tutoring session has been updated");
    }

    public function getRandomizedColor()
    {
        $availableColors = ["#6610f2", "#3c8dbc",  "#605ca8", "#f012be", "#d81b60", "#ff851b", "#01ff70", "#39cccc", "#3d9970"];
        return $availableColors[rand(0, count($availableColors) - 1)];
    }



    public function deleteTutoring(Request $request, Tutoring $tutoring)
    {
        $message = "the tutoring session has been deleted";
        $tutoring->delete();

        if ($request->ajax()) {
            Session::flash('message', $message);
            return "/home/tutor/view-tutoring";
        }
        return redirect('/home/tutor/view-tutoring')->with('message', $message);
    }




    public function viewTutoring()
    {
        // get valid tutorings
        $tutorings = Auth::user()->tutorings()->with('subject.category')->whereDate('start_time', '>=', Carbon::today())->orderBy('start_time', 'ASC')->get();

        $events = $tutorings->map(function ($tutoring) {
            return [
                'id' => $tutoring->id,
                'title' => $tutoring->title,
                'start' => $tutoring->start_time,
                'end' => $tutoring->end_time,
                'subject' => $tutoring->subject->name,
                'category' => $tutoring->subject->category->name,
                'final_price' => $tutoring->hourly_price * $tutoring->hourly_duration,
                'backgroundColor' => $tutoring->label_color,
                'borderColor' => $tutoring->label_color,
            ];
        });


        return view('tutoring.view-tutoring', compact('tutorings', 'events'));
    }

}
