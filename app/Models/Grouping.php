<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Grouping extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'group_id', 'is_accepted'];
    public function members(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class, 'group_id', 'id');
    }
}
