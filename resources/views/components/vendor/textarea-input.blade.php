@props(['disabled' => false, 'error' => false])

@php
$classes = 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full transition ease-in-out duration-150 min-h-[100px]';
if ($error) {
    $classes .= ' border-red-500 focus:border-red-500 focus:ring-red-500';
}
@endphp

<textarea {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => $classes]) !!}>{{ $slot }}</textarea>
