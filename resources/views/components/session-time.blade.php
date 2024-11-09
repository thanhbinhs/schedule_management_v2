@php
    $sessions = [
        1 => '6h45-9h25',
        2 => '9h30-12h10',
        3 => '1h-3h40',
        4 => '3h45-6h25',
    ];

    $sessionNumber = isset($sessionNumber) && (is_int($sessionNumber) || is_string($sessionNumber)) ? $sessionNumber : null;
    $sessionTime = $sessionNumber !== null ? ($sessions[$sessionNumber] ?? 'N/A') : 'N/A';
@endphp

{{ $sessionNumber }} ({{ $sessionTime }})
