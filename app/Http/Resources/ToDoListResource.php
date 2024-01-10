<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ToDoListResource extends JsonResource
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
            'title' => $this->title,
            'is_group' => $this->is_group,
            'is_complete' => $this->is_complete,
            'description' => $this->description,
            'timer' => $this->timer,
            'total_seconds' => $this->total_seconds,
            'timer_started' => $this->timer_started,
            'user_id' => $this->user_id,
            'grouping_id' => $this->grouping_id,
            'date' => $this->date,
            'day' => $this->day
        ];
    }
}
