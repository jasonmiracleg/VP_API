<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Categorize extends Model
{ // pivot table
    use HasFactory;
    public function categorizing(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(ToDoList::class, 'to_do_list_id', 'id');
    }
}
