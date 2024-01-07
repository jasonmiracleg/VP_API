<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Group extends Model
{
    use HasFactory;
    protected $fillable = ['group_name', 'description'];


    public function group(): HasMany
    {
        return $this->hasMany(Grouping::class, 'group_id', 'id');
    }
    public function grouping(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'groupings', 'group_id', 'user_id');
    }
    public function toDoLists(): HasMany
    {
        return $this->hasMany(ToDoList::class, 'to_do_list_id', 'id');
    }
}
