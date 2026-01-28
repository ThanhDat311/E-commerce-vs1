<!-- Category Tree Item Component -->
@php
$category = $item['category'];
$children = $item['children'] ?? [];
$hasChildren = count($children) > 0;
@endphp

<div x-show="!searchQuery || '{{ strtolower($category->name) }}'.includes(searchQuery.toLowerCase())" class="group">
    <div class="flex items-center gap-2 px-3 py-2.5 rounded-lg hover:bg-gray-100 transition-colors cursor-pointer" onclick="window.location.href='{{ route('admin.categories.edit', $category->id) }}'">
        @if($hasChildren)
        <button onclick="event.stopPropagation(); this.parentElement.parentElement.querySelector('[data-children]').classList.toggle('hidden')" class="flex-shrink-0">
            <svg class="w-4 h-4 text-gray-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </button>
        @else
        <div class="flex-shrink-0 w-4"></div>
        @endif

        <div class="flex-1 min-w-0">
            <p class="text-sm font-medium text-gray-900 truncate">{{ $category->name }}</p>
        </div>

        <span class="flex-shrink-0 inline-flex items-center gap-1 px-2 py-1 bg-indigo-100 text-indigo-700 rounded text-xs font-semibold">
            {{ $category->products_count ?? 0 }}
        </span>

        <div class="flex-shrink-0 opacity-0 group-hover:opacity-100 transition-opacity">
            <a href="{{ route('admin.categories.edit', $category->id) }}" onclick="event.stopPropagation()" class="p-1 hover:bg-gray-200 rounded transition-colors" title="Edit">
                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
            </a>
        </div>
    </div>

    @if($hasChildren)
    <div data-children class="ml-4 border-l border-gray-200 space-y-0">
        @foreach($children as $childId => $childNode)
        @include('admin.categories.partials.category-tree-item', ['item' => $childNode, 'level' => $level + 1])
        @endforeach
    </div>
    @endif
</div>