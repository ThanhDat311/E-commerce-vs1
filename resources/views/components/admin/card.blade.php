@props(['variant' => 'white', 'border' => 'left', 'borderColor' => 'gray'])

@php
$variantClasses = [
'white' => 'bg-white',
'light' => 'bg-gray-50',
'red' => 'bg-red-50',
'orange' => 'bg-orange-50',
'green' => 'bg-green-50',
'blue' => 'bg-blue-50',
];

$borderClasses = [
'left' => 'border-l-4',
'top' => 'border-t-4',
'full' => 'border',
];

$borderColorClasses = [
'gray' => 'border-gray-200',
'red' => 'border-red-600',
'orange' => 'border-orange-500',
'green' => 'border-green-600',
'blue' => 'border-blue-500',
];

$cardClass = "rounded-lg shadow-md p-6 {$variantClasses[$variant]} {$borderClasses[$border]} {$borderColorClasses[$borderColor]}";
@endphp

<div class="{{ $cardClass }}">
    {{ $slot }}
</div>