<div>
    @foreach($reactions as $reaction)
        <button wire:click="react('{{ $reaction }}')">
            {{ $reaction }}
            @if($currentReaction && $currentReaction->type === $reaction)
                (Selected)
            @endif
            <span>{{ $reactionCounts[$reaction] ?? 0 }}</span>
        </button>
    @endforeach
</div>
