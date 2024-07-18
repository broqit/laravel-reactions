<?php

namespace Broqit\Laravel\Reactions\Traits;

use Broqit\Laravel\Reactions\Models\Reaction;
use Illuminate\Support\Facades\Auth;

trait HasReactions
{
    public static function bootHasReactions()
    {
        static::deleting(function ($model) {
            $model->reactions()->delete();
        });
    }

    public function reactions()
    {
        return $this->morphMany(Reaction::class, 'reactable');
    }

    public function react($type)
    {
        $userId = Auth::check() ? Auth::id() : request()->ip();
        $allowedUsers = config('reactions.allowed_users');
        $maxReactions = config('reactions.max_reactions_per_user', 1);

        if (($allowedUsers === 'user' && !Auth::check()) || ($allowedUsers === 'guest' && Auth::check())) {
            return;
        }

        $existingReactions = $this->reactions()->where('user_id', $userId)->count();

        if ($existingReactions >= $maxReactions) {
            return;
        }

        $reaction = $this->reactions()->where('user_id', $userId)->first();

        if ($reaction) {
            $reaction->update(['type' => $type]);
        } else {
            $this->reactions()->create([
                'user_id' => $userId,
                'type' => $type,
            ]);
        }
    }

    public function removeReaction()
    {
        $userId = Auth::check() ? Auth::id() : request()->ip();
        $this->reactions()->where('user_id', $userId)->delete();
    }
}
