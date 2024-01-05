<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Group extends Model
{
    use HasFactory;
    protected $fillable = ['group_name', 'description'];

    public function grouping(): HasMany
    {
        return $this->hasMany(Grouping::class, 'user_id', 'id');
    }
}
