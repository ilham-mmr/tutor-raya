<?php

namespace App\Http\Controllers;

use App\Http\Resources\TutoringResource;
use App\Models\Tutoring;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TutoringController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
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
    public function store(Request $request) {
        //
        // date , time
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $result =  Tutoring::with(['tutor', 'subject.category',])
            ->where('id', $id)
            ->where('status', 'AVAILABLE')->first();
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
    public function update(Request $request, $id) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        //
    }
}
