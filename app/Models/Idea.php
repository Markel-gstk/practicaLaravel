<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Idea extends Model
{
    protected $fillable = ['user_id', 'title', 'description', 'likes'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function usersIdeas(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}
