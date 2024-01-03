<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'color'];

    public function categorizing(): HasMany
    {
        return $this->hasMany(Categorize::class, 'category_id', 'id');
    }
}
