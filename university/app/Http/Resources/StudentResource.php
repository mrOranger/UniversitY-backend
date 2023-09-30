<?php

namespace App\Http\Resources;

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
            'degree' => [
                'id' => $this->degree->id,
                'name' => $this->degree->name,
                'code' => $this->degree->code,
                'course_type' => $this->degree->course_type
            ],
            'user' => [
                'first_name' => $this->user->first_name,
                'last_name' => $this->user->last_name,
                'email' => $this->user->email,
                'birth_date' => $this->user->birth_date
            ]
        ];
    }
}
