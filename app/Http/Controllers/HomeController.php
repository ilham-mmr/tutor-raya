<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;


class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $totalTutorings = count($user->tutorings()->whereDate('start_time', '>=', Carbon::today())->get());

        $tutorings = Auth::user()->tutorings()->select('id')->get();
        $tutorTutoringsId = $tutorings->toArray();
        $bookingsQuery = Booking::with(['user:id,name,phone_number', 'tutoring.subject'])->whereIn('tutoring_id', $tutorTutoringsId);

        $totalBookings = $bookingsQuery->count();

        $totalEarnings = $bookingsQuery->where('STATUS', 'FINISHED')->get()->sum(function ($item) {
            return $item->tutoring->hourly_price * $item->tutoring->hourly_duration;
        });
        return view('dashboard.index', compact('totalTutorings', 'totalBookings', 'totalEarnings'));
    }
}
