@props(['size' => 'md', 'showDomain' => false])

@php
    $sizeClasses = [
        'sm' => 'text-sm',
        'md' => 'text-base sm:text-lg',
        'lg' => 'text-lg sm:text-xl',
        'xl' => 'text-xl sm:text-2xl'
    ][$size];
@endphp

<div class="inline-flex items-baseline tracking-tight {{ $sizeClasses }}">
    {{-- P2P with special styling --}}
    <span class="font-bold text-gray-900 dark:text-white">
        P<span class="text-emerald-600 dark:text-emerald-500">2</span>P
    </span>

    {{-- CoinSwap as one word --}}
    <span class="font-bold text-gray-700 dark:text-gray-300 ml-0.5">
        CoinSwap
    </span>

    {{-- Domain suffix --}}
    @if($showDomain)
        <span class="text-gray-500 dark:text-gray-400 font-normal ml-0.5">
            .com
        </span>
    @endif
</div>