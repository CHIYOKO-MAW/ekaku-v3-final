@props(['type' => 'text', 'class' => ''])

<div {{ $attributes->merge(['class' => "skeleton skeleton-{$type} {$class}"]) }}></div>
