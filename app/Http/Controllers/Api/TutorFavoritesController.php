<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\TutoringResource;

class TutorFavoritesController extends Controller
{
    /**
     * fetch and set
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $favoriteTutors = $user->favoriteTutors()->get();

        if ($request->with_details) {
            $map = $favoriteTutors->map(function ($tutor) {
                if ($tutor->pivot->is_favorite) {
                    return [
                        'id' => $tutor->id,
                        'name' => $tutor->name,
                        'picture' => $tutor->picture,
                        'categories' => collect($tutor->tutorings->map(function ($tutoring) {
                            return [
                                $tutoring->subject->category->name
                            ];
                        }))->unique()->flatten()->toArray(),
                        'minimum_price' => $tutor->tutorings->min('hourly_price'),
                        'maximum_price' => $tutor->tutorings->max('hourly_price'),
                    ];
                }


            })
            // remove all entries that are equivalent to false
            ->filter()->values();

            // dd($map->toArray());
            // return $map->all();

            return response()->json(['data' => $map]);
        }
        $data = [];


        foreach ($favoriteTutors as $tutor) {
            $arr[$tutor->pivot->tutor_id] = ['is_favorite' => $tutor->pivot->is_favorite];
            $data = $arr;
        }

        return $data;
    }

    public function getFavoriteUsers(Request $request)
    {
        $user = Auth::user();
        $exists =  $user->favoriteTutors()->where('tutor_id', $request->tutor_id)->first();
        // $map = $tutors->map(function ($tutor) {
        //     return [
        //         'id' => $tutor->id,
        //         'name' => $tutor->name,
        //         'picture' => $tutor->picture,
        //         'categories' => collect($tutor->tutorings->map(function ($tutoring) {
        //             return [
        //                 $tutoring->subject->category->name
        //             ];
        //         }))->unique()->flatten()->toArray(),
        //         'minimum_price' => $tutor->tutorings->min('hourly_price'),
        //         'maximum_price' => $tutor->tutorings->max('hourly_price'),
        //     ];
        // });
        // return response()->json($map);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function toggleFavorite(Request $request)
    {

        $request->validate([
            'tutor_id' => 'required',
            'is_favorite' => 'required'
        ]);

        $user = Auth::user();
        $exists =  $user->favoriteTutors()->where('tutor_id', $request->tutor_id)->first();
        if ($exists == null) {
            $user->favoriteTutors()->attach($request->tutor_id, ['is_favorite' => $request->is_favorite]);
            return ['message' => 'tutors have been saved'];
        }
        $user->favoriteTutors()->updateExistingPivot($request->tutor_id, ['is_favorite' => $request->is_favorite]);
        return ['message' => 'tutors have been updated'];
    }


    // /**
    //  * Update the specified resource in storage.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    // public function update(Request $request) {
    //     $user = Auth::user();
    //     $user->favoriteTutors()->sync($request->tutors);
    //     return ['message' => 'favorite tutors have been updated'];
    // }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $user->favoriteTutors()->detach($id);
        return ['message' => 'tutor deleted'];
    }
}
