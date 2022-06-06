<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Tutoring;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Http\Resources\TutoringResource;

class TutoringController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        // name, avatar,hourly price, subject, category, start_time, end_time
        // $result = User::whereHas('tutorings', function ($query) {
        //     $query->where('status', 'AVAILABLE');
        // })->get();

        // filter : category, date time, hourly price,

        // request mustbe utc
        // convert utc time to gmt+7 jakarta
        // dd(Carbon::parse('10:00:00')->addHours(3)->toTimeString());
        $result =  Tutoring::with(['tutor', 'subject.category',])
            ->where('status', 'AVAILABLE')
            ->when(request('category') ?? false, function ($query, $category) {
                return $query->whereHas('subject.category', function ($query) use ($category) {
                    $query->where('name', $category);
                });
            })
            ->when(request('minPrice') ?? false, function ($query, $minPrice) {
                return $query->where('hourly_price', '>=', $minPrice);
            })
            ->when(request('maxPrice') ?? false, function ($query, $maxPrice) {
                return $query->where('hourly_price', '<=', $maxPrice);
            })
            ->when(request('date') ?? false, function ($query, $date) {
                return $query->whereDate('start_time', $date);
            })
            ->paginate(10);

        return TutoringResource::collection($result);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        // date , time
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $result =  Tutoring::with(['tutor', 'subject.category',])
            ->where('id', $id)
            ->where('status', 'AVAILABLE')->firstOrFail();
        return
            TutoringResource::make($result);
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

    public function getLessons(Request $request)
    {
        if($request->user_id) {
            return response()->json(['message' => 'Not Found!'], 404);

        }

        $bookings = User::find($request->user_id)->bookings()->with(['tutoring.subject.category'])->get();
        $data['lessons'] = $bookings->map(function ($booking) {
            return [
                'id' => $booking->id,
                'total_price' => $booking->total_price,
                'start_time' => $booking->tutoring->start_time,
                'end_time' => $booking->tutoring->end_time,
                'title' => $booking->tutoring->title,
                'status' => $booking->status,
                'subject' => $booking->tutoring->subject->name,
                'category' => $booking->tutoring->subject->category->name,
                'meeting_link' => $booking->meeting_link,
                'tutor' =>$booking->tutoring->tutor->name,
                'picture' => $booking->tutoring->tutor->picture,
                'phone_number' =>$booking->tutoring->tutor->phone_number,
            ];
        });

        return $data;
    }
}
