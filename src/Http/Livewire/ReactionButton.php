<?php

namespace Broqit\Laravel\Reactions\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ReactionButton extends Component
{
    public $model;
    public $reactions;
    public $currentReaction;
    public $allowedUsers;
    public $reactionCounts;

    public function mount($model)
    {
        $this->model = $model;
        $this->reactions = config('reactions.types');
        $this->allowedUsers = config('reactions.allowed_users');
        $this->currentReaction = $this->model->reactions()->where('user_id', Auth::check() ? Auth::id() : request()->ip())->first();
        $this->reactionCounts = $this->model->reactions()
            ->select('type', \DB::raw('count(*) as count'))
            ->groupBy('type')
            ->pluck('count', 'type')
            ->toArray();
    }

    public function react($type)
    {
        if (($this->allowedUsers === 'user' && !Auth::check()) || ($this->allowedUsers === 'guest' && Auth::check())) {
            return;
        }

        $this->model->react($type);
        $this->currentReaction = $this->model->reactions()->where('user_id', Auth::check() ? Auth::id() : request()->ip())->first();
        $this->updateReactionCounts();
    }

    public function updateReactionCounts()
    {
        $this->reactionCounts = $this->model->reactions()
            ->select('type', \DB::raw('count(*) as count'))
            ->groupBy('type')
            ->pluck('count', 'type')
            ->toArray();
    }

    public function render()
    {
        return view('reactions::livewire.reaction-button');
    }
}
