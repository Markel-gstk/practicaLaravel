<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Auth;


class Idea extends Model
{
    protected $fillable = ['user_id', 'title', 'description', 'likes'];

    protected $casts = ['created_at' => 'datetime'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function usersIdeas(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function scopeMyIdeas(Builder $query, $filter)
    {
        if (!empty($filter) && $filter == 'mis-ideas'){
            return $query->where('user_id', Auth::user()->id); 
        }
    }

    public function scopeTheBest(Builder $query, $filter)
    {
        if (!empty($filter) && $filter == 'las-mejores'){
            return $query->orderBy('likes', 'desc'); 
        }
    }
}
