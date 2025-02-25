<?php

namespace Broqit\Laravel\Reactions\Http\Livewire;

use Illuminate\View\View;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ReactionButton extends Component
{
    public $style = 'default'; // default or tailwind
    public $model;
    public $reactions;
    public $currentReactions;
    public $allowedUsers;
    public $reactionCounts;
    public $removalWindowHours;
    public $maxReactions;

    public function mount($model, $style = 'default'): void
    {
        $this->style = $style;
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

    public function react($type): void
    {
        if (($this->allowedUsers === 'user' && !Auth::check()) || ($this->allowedUsers === 'guest' && Auth::check())) {
            return;
        }

        // If user has already selected this reaction, remove it
        if (in_array($type, $this->currentReactions, true)) {
            $this->model->removeReaction($type);
            $this->currentReactions = array_diff($this->currentReactions, [$type]);
        } elseif (count($this->currentReactions) < $this->maxReactions) {
            $this->model->react($type);
            $this->currentReactions[] = $type;
        }

        $this->updateReactionCounts();
    }

    public function updateReactionCounts(): void
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

    public function render(): View
    {
        $view = $this->style === 'tailwind' 
            ? 'reactions::livewire.reaction-button-tw'
            : 'reactions::livewire.reaction-button';

        return view($view, [
            'reactions' => $this->reactions,
            'currentReactions' => $this->currentReactions,
            'reactionCounts' => $this->reactionCounts
        ]);
    }
}
