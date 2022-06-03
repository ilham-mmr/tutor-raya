<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'about' => $this->about,
            'education' => $this->education,
            'picture' => $this->picture,
            'is_tutor' => $this->is_tutor,
            'phone_number' =>$this->phone_number,
            'created_at' => $this->created_at,
            'subject' => new SubjectResource($this->whenLoaded('subject'))
        ];
    }
}
