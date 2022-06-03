<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Carbon;


class TutorAPIController extends Controller
{
    public function getTutors(Request $request)
    {
        $tutorsQuery = User::with(['tutorings.subject.category'])
            ->where('is_tutor', true)->whereHas('tutorings', function ($q) {
                $q->where('status', '=', 'AVAILABLE')->whereDate('start_time', '>=', Carbon::today())->orderBy('start_time', 'ASC');
            })->inRandomOrder();

        if ($request->keyword) {
            $keywords = explode(' ', $request->keyword);
            foreach ($keywords as $keyword) {
                $tutorsQuery->where(function ($query) use ($keyword) {
                    $query->whereHas('tutorings.subject.category', function ($query) use ($keyword) {
                        $query->where('name','like', '%' . $keyword . '%');
                    });
                    $query->orWhereHas('tutorings.subject', function ($query) use ($keyword) {
                        $query->where('name','like', '%' . $keyword . '%');
                    });
                        // ->orWhere('product.title', 'like', '%' . $keyword . '%')
                        // ->orWhere('product.quantity', 'like', '%' . $keyword . '%')
                        // ->orWhere('product.price', 'like', '%' . $keyword . '%');
                });
            }
        }

        // filters
        $tutorsQuery->when(request('category') ?? false, function ($query, $category) {
            return $query->whereHas('tutorings.subject.category', function ($query) use ($category) {
                $query->where('name', $category);
            });
        });

        $tutorsQuery->when(request('subject') ?? false, function ($query, $subject) {
            return $query->whereHas('tutorings.subject', function ($query) use ($subject) {
                $query->where('name', $subject);
            });
        });

        $tutorsQuery->when(request('minPrice') ?? false, function ($query, $minPrice) {
            return $query->whereHas('tutorings', function ($query) use ($minPrice) {
                $query->where('hourly_price', '>=', $minPrice);
            });
        });

        $tutorsQuery->when(request('maxPrice') ?? false, function ($query, $maxPrice) {
            return $query->whereHas('tutorings', function ($query) use ($maxPrice) {
                $query->where('hourly_price', '<=', $maxPrice);
            });
        });

        $tutorsQuery->when(request('date') ?? false, function ($query, $date) {
            return $query->whereHas('tutorings', function ($query) use ($date) {
                $query->where('start_time', '<=', $date);
            });
        });

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

    public function detailTutors(Request $request, $tutorId)
    {


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
