<?php

namespace App\Http\Resources;

use App\Http\Resources\Collections\CourseCollection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'bachelor_final_mark' => $this->bachelor_final_mark,
            'master_final_mark' => $this->master_final_mark,
            'phd_final_mark' => $this->phd_final_mark,
            'outside_prescribed_time' => $this->outside_prescribed_time,
            'degree' => new DegreeResource($this->degree),
            'user' => new UserResource($this->user),
            'courses' => new CourseCollection($this->courses)
        ];
    }
}
