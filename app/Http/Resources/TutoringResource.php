<?php

namespace App\Http\Resources;

use App\Models\Subject;
use App\Models\Category;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Resources\Json\JsonResource;

class TutoringResource extends JsonResource {
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request) {
        return [
            'id' => $this->id,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'hourly_price' => $this->hourly_price,
            'status' => $this->status,
            'tutor' => new TutorResource($this->whenLoaded('tutor')),
            'subject' => new SubjectResource($this->whenLoaded('subject')),
            'subjects_taught' => $this->when(Route::currentRouteName() == 'tutorings.show', [
                $this->tutor->subjects
            ])


        ];
    }
}
