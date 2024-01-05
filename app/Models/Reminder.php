<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reminder extends Model
{
    use HasFactory;
    protected $fillable = ['time_hours', 'time_minutes', 'to_do_list_id'];
    public function reminder(): BelongsTo
    {
        return $this->belongsTo(ToDoList::class, 'to_do_list_id', 'id');
    }
}
