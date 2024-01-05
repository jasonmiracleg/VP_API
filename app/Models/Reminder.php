<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Reminder extends Model
{
    use HasFactory;
    protected $fillable = ['time_hours', 'time_minutes'];
    public function reminder(): HasOne
    {
        return $this->hasOne(ToDoList::class, 'reminder_id', 'id');
    }
}
