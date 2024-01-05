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
            'title' => $request->title,
            'is_group' => $request->is_group,
            'is_complete' => $request->is_complete,
            'description' => optional($request->description),
            'timer' => optional($request->timer),
            'total_seconds' => optional($request->total_seconds),
            'elapsed' => optional($request->elapsed),
            'timer_started' => optional($request->timer_started),
            'user_id' => optional($request->user_id),
            'reminder_id' => $request->reminder_id,
            'grouping_id' => optional($request->grouping_id),
            'date' => $request->date
        ];
    }
}
