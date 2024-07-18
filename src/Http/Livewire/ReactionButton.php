<?php

namespace Broqit\Laravel\Reactions\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ReactionButton extends Component
{
    public $model;
    public $reactions;
    public $currentReactions;
    public $allowedUsers;
    public $reactionCounts;
    public $removalWindowHours;
    public $maxReactions;

    public function mount($model)
    {
        $this->model = $model;
        $this->reactions = config('reactions.types');
        $this->allowedUsers = config('reactions.allowed_users');
        $this->removalWindowHours = config('reactions.removal_window_hours');
        $this->maxReactions = config('reactions.max_reactions_per_user', 1);
        $this->currentReactions = $this->model->reactions()
            ->when(Auth::check(), function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->when(!Auth::check(), function ($query) {
                $query->where('guest_id', request()->ip());
            })
            ->pluck('type')
            ->toArray();
        $this->updateReactionCounts();
    }

    public function react($type)
    {
        if (($this->allowedUsers === 'user' && !Auth::check()) || ($this->allowedUsers === 'guest' && Auth::check())) {
            return;
        }

        // Якщо користувач вже обрав цю реакцію, знімаємо її
        if (in_array($type, $this->currentReactions)) {
            $this->model->removeReaction($type);
            $this->currentReactions = array_diff($this->currentReactions, [$type]);
        } elseif (count($this->currentReactions) < $this->maxReactions) {
            $this->model->react($type);
            $this->currentReactions[] = $type;
        }

        $this->updateReactionCounts();
    }

    public function updateReactionCounts()
    {
        $this->reactionCounts = $this->model->reactions()
            ->select('type')
            ->selectRaw('count(*) as count')
            ->groupBy('type')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item['type'] => $item['count']];
            })
            ->toArray();
    }

    public function render()
    {
        return view('reactions::livewire.reaction-button', [
            'reactions' => $this->reactions,
            'currentReactions' => $this->currentReactions,
            'reactionCounts' => $this->reactionCounts
        ]);
    }
}
