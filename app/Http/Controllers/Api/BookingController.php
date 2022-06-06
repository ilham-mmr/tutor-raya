<?php

namespace App\Http\Controllers\Api;

use Midtrans\Config;
use App\Models\Booking;
use App\Models\Tutoring;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;


class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function bookDummy(Request $request)
    {

        $request->validate(
            [
                'tutoring_id' => 'required',
                'user_id' => 'required',
            ]
        );
        // if there are bookings that have tutoring id then it's invalid

        $booking = Booking::where('tutoring_id')->first();
        if ($booking !== null) {
            return response()->json(['message' => 'Failed'], 403);
        }

        $tutoring = Tutoring::find($request->tutoring_id);
        $booking = Booking::create([
            'tutoring_id' => $request->tutoring_id,
            'total_price' => $tutoring->hourly_price * $tutoring->hourly_duration,
            'status' => 'REQUESTED',
            'user_id' => $request->user_id,
        ]);

        // after being booked set the current tutoring status to UNAVAILABLE
        $tutoring->status = "UNAVAILABLE";
        $tutoring->save();
        return [
            'message' => 'Logged out'
        ];

        // return success
    }
}
