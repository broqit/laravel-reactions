<div class="flex flex-wrap items-center justify-center mt-8">
    @foreach($reactions as $reaction)
        <button 
            class="inline-flex items-center px-3 py-2 m-1 text-sm border border-gray-300 rounded-md transition-colors duration-300 
            {{ in_array($reaction['type'], $currentReactions, true) 
                ? 'bg-gray-200 border-gray-400' 
                : 'bg-gray-50 hover:bg-gray-100' }}"
            wire:click="react('{{ $reaction['type'] }}')"
        >
            {!! $reaction['icon'] !!} 
            <span class="ml-1">{{ $reaction['name'] }}</span>
            @if(!empty($reactionCounts[$reaction['type']]))
                <span class="ml-2 font-bold">{{ $reactionCounts[$reaction['type']]}}</span>
            @endif
        </button>
    @endforeach
</div>