@props([
    'status' => null,
    'title',
    'subtitle' => null,
    'badge' => null,
    'badgeColor' => 'gray',
    'expandable' => false,
])

@php
    $statusClass = $status ? "mobile-card-status-{$status}" : '';
    $badgeColors = [
        'green' => 'bg-green-100 text-green-700',
        'yellow' => 'bg-yellow-100 text-yellow-700',
        'red' => 'bg-red-100 text-red-700',
        'blue' => 'bg-blue-100 text-blue-700',
        'gray' => 'bg-gray-100 text-gray-700',
    ];
@endphp

<div {{ $attributes->merge(['class' => "mobile-card {$statusClass}"]) }}
     @if($expandable) x-data="expandableCard()" @endif>

    <div class="mobile-card-header">
        <div>
            <p class="mobile-card-title">{{ $title }}</p>
            @if($subtitle)
                <p class="mobile-card-subtitle">{{ $subtitle }}</p>
            @endif
        </div>
        @if($badge)
            <span class="mobile-card-badge {{ $badgeColors[$badgeColor] ?? $badgeColors['gray'] }}">
                {{ $badge }}
            </span>
        @endif
    </div>

    <div class="mobile-card-body">
        {{ $slot }}
    </div>

    @if(isset($actions))
        <div class="mobile-card-actions">
            {{ $actions }}
        </div>
    @endif

    @if($expandable && isset($expand))
        <button type="button" x-on:click="toggle()" class="mobile-card-expand-btn">
            <span x-text="expanded ? 'Tutup' : 'Lihat Selengkapnya'"></span>
            <i class="fas text-[10px]" :class="expanded ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
        </button>
        <div class="mobile-card-expand-content" :data-expanded="expanded.toString()">
            {{ $expand }}
        </div>
    @endif
</div>
