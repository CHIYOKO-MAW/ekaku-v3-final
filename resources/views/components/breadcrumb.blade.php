@props(['items' => []])

@if(count($items) > 0)
<nav class="mb-4 flex items-center text-sm text-gray-500" aria-label="Breadcrumb">
    <ol class="flex flex-wrap items-center gap-1">
        @foreach($items as $index => $item)
            @if($index > 0)
                <li class="text-gray-400">/</li>
            @endif
            <li>
                @if(isset($item['url']))
                    <a href="{{ $item['url'] }}" class="hover:text-disnaker-600 transition-colors">{{ $item['label'] }}</a>
                @else
                    <span class="font-medium text-gray-700">{{ $item['label'] }}</span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
@endif
