<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Carbon;


class TutorAPIController extends Controller {
    public function getTutors(Request $request) {
        $tutorsQuery = User::with(['tutorings.subject.category'])
            ->where('is_tutor', true)->whereHas('tutorings', function ($q) {
                $q->whereDate('start_time', '>=', Carbon::today())->orderBy('start_time', 'ASC');
            })->inRandomOrder();
        if ($request->limit) {
            $tutorsQuery->limit($request->limit);
        }
        $tutors = $tutorsQuery->get();
        $map = $tutors->map(function ($tutor) {
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
        });
        return response()->json($map);
        // return $map->toJson();
    }

    public function detailTutors(Request $request, $tutorId) {


        $tutor = User::with(['tutorings' => function ($q) {
            $q->whereDate('start_time', '>=', Carbon::today())->orderBy('start_time', 'ASC');
        }, 'tutorings.subject.category'])
            ->where('is_tutor', true)
            ->where('id', $tutorId)->firstOrFail();

        $data = [
            'id' => $tutor->id,
            'name' => $tutor->name,
            'picture' => $tutor->picture,
            'is_available' => $tutor->active,
            'about' => $tutor->about,
            'education' => $tutor->education,
            'degree' => $tutor->degree,
            'categories' => collect($tutor->tutorings->map(function ($tutoring) {
                return [
                    $tutoring->subject->category->name
                ];
            }))->unique()->flatten()->toArray(),
            'subjects' => collect($tutor->tutorings->map(function ($tutoring) {
                return [
                    $tutoring->subject->name
                ];
            }))->unique()->flatten()->toArray()
        ];
        if ($request->with_tutorings) {
            $tutorings = $tutor->tutorings->map(function ($tutoring) {
                return [
                    'tutoring_id' =>  $tutoring->id,
                    'title' => $tutoring->title,
                    'description' => $tutoring->description,
                    'start_time' => $tutoring->start_time,
                    'end_time' => $tutoring->end_time,
                    'duration' => $tutoring->hourly_duration,
                    'total_price' => $tutoring->hourly_price * $tutoring->hourly_duration,
                    'subject' => $tutoring->subject->name,
                    'category' => $tutoring->subject->category->name
                ];
            })->toArray();
            $data['tutorings']  = $tutorings;
        }

        return $data;
    }
}
