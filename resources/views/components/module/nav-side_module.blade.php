@props(['active'])
@vite(['resources/css/global.css'])

@php
$classes = ($active ?? false)
            ? 'link'
            : 'link_none';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
