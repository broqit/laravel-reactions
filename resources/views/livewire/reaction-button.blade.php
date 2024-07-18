<div>
    @push('styles')
        <link rel="stylesheet" href="{{ asset('vendor/reactions/css/reactions.css') }}">
    @endpush

    @foreach($reactions as $reaction)
        <button class="reaction-button @if(in_array($reaction['type'], $currentReactions)) selected @endif" wire:click="react('{{ $reaction['type'] }}')">
            {!! $reaction['icon'] !!} {{ $reaction['name'] }}
            <span>{{ $reactionCounts[$reaction['type']] ?? 0 }}</span>
        </button>
    @endforeach
</div>