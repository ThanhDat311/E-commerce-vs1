@props(['title', 'striped' => true, 'hoverable' => true])

@php
$tableClass = 'w-full';
$theadClass = 'bg-gradient-to-r from-gray-900 to-gray-800 text-white';
$thClass = 'px-6 py-3 text-left text-sm font-semibold';

$rowClass = 'border-b border-gray-200';
if ($hoverable) $rowClass .= ' hover:bg-gray-50 transition';

$tdClass = 'px-6 py-4 text-sm text-gray-900';
@endphp

<div class="bg-white rounded-lg shadow-md overflow-hidden">
    @if($title)
    <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900">{{ $title }}</h3>
    </div>
    @endif
    <table class="{{ $tableClass }}">
        {{ $slot }}
    </table>
</div>