<?php

namespace Broqit\Laravel\Reactions\Traits;

use Broqit\Laravel\Reactions\Models\Reaction;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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
        $userId = Auth::check() ? Auth::id() : null;
        $guestId = !Auth::check() ? request()->ip() : null;
        $allowedUsers = config('reactions.allowed_users');
        $maxReactions = config('reactions.max_reactions_per_user', 1);
        $userModel = config('reactions.user_model', \App\Models\User::class);

        if (is_null($userModel)) {
            $userModel = \App\Models\User::class;
        }

        if (($allowedUsers === 'user' && !Auth::check()) || ($allowedUsers === 'guest' && Auth::check())) {
            return;
        }

        $existingReactions = $this->reactions()->when($userId, function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->when($guestId, function ($query) use ($guestId) {
            $query->where('guest_id', $guestId);
        })->count();

        if ($existingReactions >= $maxReactions) {
            return;
        }

        $this->reactions()->create([
            'user_id' => $userId,
            'guest_id' => $guestId,
            'type' => $type,
        ]);
    }

    public function removeReaction($type)
    {
        $userId = Auth::check() ? Auth::id() : null;
        $guestId = !Auth::check() ? request()->ip() : null;
        $removalWindowHours = config('reactions.removal_window_hours', null);

        $query = $this->reactions()->when($userId, function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->when($guestId, function ($query) use ($guestId) {
            $query->where('guest_id', $guestId);
        })->where('type', $type);

        if (!is_null($removalWindowHours)) {
            $query->where('created_at', '>=', Carbon::now()->subHours($removalWindowHours));
        }

        $query->delete();
    }
}
