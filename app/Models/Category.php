<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'color', 'user_id'];

    public function customToDoLists(): BelongsToMany
    {
        return $this->belongsToMany(ToDoList::class, 'categorizes', 'category_id', 'to_do_list_id');
    }
}
