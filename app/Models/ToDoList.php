<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ToDoList extends Model
{
    use HasFactory;
    protected $fillable =  ['title', 'user_id', 'is_group', 'is_complete', 'description', 'timer', 'total_seconds', 'timer_started', 'reminder_id', 'date', 'group_id', 'day'];

    public function reminder(): HasOne
    {
        return $this->hasOne(Reminder::class, 'to_do_list_id', 'id');
    }
    public function toDoLists(): BelongsTo
    {
        return $this->belongsTo(Group::class, 'group_id', 'id');
    }
    public function tasks(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'categorizes', 'to_do_list_id', 'category_id');
    }
}
