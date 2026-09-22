@php
    $name = $name ?? '';
    $class = $class ?? '';
    $name = str_replace('fa-', '', $name);
@endphp

{{-- Try to render an inline SVG for icons known to sometimes be unavailable in the FA subset. Otherwise render the FontAwesome <i> element. --}}
@switch($name)
    @case('bolt-lightning')
        <svg class="inline-block {{ $class }}" width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <path d="M13 2L3 14h7l-1 8 10-12h-7l1-8z" fill="currentColor"/>
        </svg>
        @break
    @case('arrow-trend-up')
        <svg class="inline-block {{ $class }}" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <path d="M3 17v4h4l11-11 3 3V6H14l-1 1-4 4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
        </svg>
        @break
    @case('person-digging')
        <svg class="inline-block {{ $class }}" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <path d="M13 2a2 2 0 11-2 2 2 2 0 012-2zM6 21l6-6 4 4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
            <path d="M20 12l-3 3" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
        </svg>
        @break
    @case('map')
        <svg class="inline-block {{ $class }}" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <path d="M20.5 3.5l-5.5 2-5.5-2-6 2v13l6-2 5.5 2 5.5-2v-13z" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
        </svg>
        @break
    @case('map-location-dot')
        <svg class="inline-block {{ $class }}" width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
            <circle cx="12" cy="9" r="2" fill="currentColor"/>
        </svg>
        @break
    @default
        <i class="fa-solid fa-{{ $name }} {{ $class }}" aria-hidden="true"></i>
@endswitch
