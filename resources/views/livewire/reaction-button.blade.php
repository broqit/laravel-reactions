<div class="reaction-buttons">
    @push('styles')
        <link rel="stylesheet" href="{{ asset('vendor/reactions/css/reactions.css') }}">
    @endpush

    @foreach($reactions as $reaction)
        <button class="reaction-button @if(in_array($reaction['type'], $currentReactions, true)) selected @endif" wire:click="react('{{ $reaction['type'] }}')">
            {!! $reaction['icon'] !!} {{ $reaction['name'] }}
            @if($reactionCounts[$reaction['type']])
            <span>{{ $reactionCounts[$reaction['type']]}}</span>
            @endif
        </button>
    @endforeach
</div>