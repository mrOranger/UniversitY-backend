<?php

namespace App\Http\Resources;

use App\Http\Resources\Collections\TeacherCollection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'sector' => $this->sector,
            'starting_date' => $this->starting_date,
            'ending_date' => $this->ending_date,
            'cfu' => $this->cfu,
            'professors' => new TeacherCollection($this->professors)
        ];
    }
}
