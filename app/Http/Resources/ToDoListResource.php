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
            'title' => $this->title,
            'is_group' => $this->is_group,
            'is_complete' => $this->is_complete,
            'description' => optional($this->description),
            'timer' => optional($this->timer),
            'total_seconds' => optional($this->total_seconds),
            'elapsed' => optional($this->elapsed),
            'timer_started' => optional($this->timer_started),
            'user_id' => optional($this->user_id),
            'reminder_id' => $this->reminder_id,
            'grouping_id' => optional($this->grouping_id),
            'date' => $this->date
        ];
    }
}
