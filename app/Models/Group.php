<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Group extends Model
{
    use HasFactory;
    protected $fillable = ['group_name', 'description'];

    public function group(): HasMany
    {
        return $this->hasMany(Grouping::class, 'group_id', 'id');
    }
}
