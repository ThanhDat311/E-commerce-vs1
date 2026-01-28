<!-- htmlhint style-disabled-in-attr: false, script-disabled-in-attr: false -->
@extends('admin.layout.admin')

@section('title', 'AI Price Suggestions')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-4xl font-bold text-gray-900">AI Price Suggestions</h1>
            <p class="text-gray-600 text-sm mt-1">Review and approve AI-generated pricing recommendations</p>
        </div>
        <div class="flex items-center gap-3 px-4 py-3 bg-blue-50 border border-blue-200 rounded-lg">
            <div class="text-blue-600 text-lg">
                <i class="fas fa-brain"></i>
            </div>
            <div>
                <p class="text-sm font-semibold text-blue-900">Smart Assistant</p>
                <p class="text-xs text-blue-700">{{ $suggestions->total() }} pending suggestions</p>
            </div>
        </div>
    </div>

    <!-- Alerts -->
    @if(session('success'))
    <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg">
        <div class="flex gap-3">
            <div class="flex-shrink-0 text-green-600">
                <i class="fas fa-check-circle text-lg"></i>
            </div>
            <div>
                <p class="text-green-800 font-medium">{{ session('success') }}</p>
            </div>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-auto text-green-600 hover:text-green-800">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
        <div class="flex gap-3">
            <div class="flex-shrink-0 text-red-600">
                <i class="fas fa-exclamation-circle text-lg"></i>
            </div>
            <div>
                <p class="text-red-800 font-medium">{{ session('error') }}</p>
            </div>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-auto text-red-600 hover:text-red-800">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    @endif

    <!-- Suggestions List -->
    @if($suggestions->count() > 0)
    <div class="space-y-4">
        @foreach($suggestions as $suggestion)
        @php
        $difference = $suggestion->getPriceDifference();
        $percent = $suggestion->getPriceDifferencePercent();
        $isIncrease = $difference >= 0;
        $confidencePercent = $suggestion->getConfidencePercentage();
        $confidenceLabel = $suggestion->getConfidenceLabel();
        @endphp

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 p-6">
                <!-- Product Info -->
                <div class="lg:col-span-3">
                    <div class="flex gap-4">
                        <div class="flex-shrink-0">
                            @if($suggestion->product->image)
                            <img src="{{ asset('storage/' . $suggestion->product->image) }}" alt="{{ $suggestion->product->name }}"
                                class="w-16 h-16 rounded-lg object-cover border border-gray-200">
                            @else
                            <div class="w-16 h-16 rounded-lg bg-gray-100 flex items-center justify-center border border-gray-200">
                                <i class="fas fa-box text-gray-400 text-xl"></i>
                            </div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-gray-900 text-sm line-clamp-2">{{ $suggestion->product->name }}</p>
                            <p class="text-xs text-gray-500 mt-1">SKU: {{ $suggestion->product->sku }}</p>
                            <p class="text-xs text-gray-500 mt-0.5">ID: #{{ $suggestion->product->id }}</p>
                        </div>
                    </div>
                </div>

                <!-- Price Comparison -->
                <div class="lg:col-span-3 space-y-3">
                    <div>
                        <div class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Current Price</div>
                        <div class="text-2xl font-bold text-gray-900">${{ number_format($suggestion->old_price, 2) }}</div>
                    </div>
                    <div>
                        <div class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Suggested Price</div>
                        <div class="text-2xl font-bold text-admin-primary">${{ number_format($suggestion->new_price, 2) }}</div>
                    </div>
                </div>

                <!-- Price Difference Highlight -->
                <div class="lg:col-span-2">
                    <div class="bg-gradient-to-br {{ $isIncrease ? 'from-green-50 to-green-100' : 'from-blue-50 to-blue-100' }} rounded-lg p-4 border {{ $isIncrease ? 'border-green-200' : 'border-blue-200' }}">
                        <div class="text-center">
                            <div class="text-xs font-semibold {{ $isIncrease ? 'text-green-700' : 'text-blue-700' }} uppercase tracking-wide mb-2">
                                {{ $isIncrease ? 'Price Increase' : 'Price Decrease' }}
                            </div>
                            <div class="text-3xl font-bold {{ $isIncrease ? 'text-green-600' : 'text-blue-600' }}">
                                {{ $isIncrease ? '+' : '' }}${{ number_format($difference, 2) }}
                            </div>
                            <div class="text-sm font-semibold {{ $isIncrease ? 'text-green-600' : 'text-blue-600' }} mt-1">
                                {{ $isIncrease ? '+' : '' }}{{ number_format($percent, 1) }}%
                            </div>
                        </div>
                    </div>
                </div>

                <!-- AI Confidence -->
                <div class="lg:col-span-2">
                    <div class="space-y-3">
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <div class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                                    <i class="fas fa-lightbulb text-yellow-500"></i> AI Confidence
                                </div>
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold
                                            @if($confidencePercent >= 80) bg-green-100 text-green-700
                                            @elseif($confidencePercent >= 60) bg-blue-100 text-blue-700
                                            @elseif($confidencePercent >= 40) bg-amber-100 text-amber-700
                                            @else bg-red-100 text-red-700
                                            @endif">
                                    {{ $confidencePercent }}%
                                </span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="h-2 rounded-full transition-all
                                            @if($confidencePercent >= 80) bg-green-500
                                            @elseif($confidencePercent >= 60) bg-blue-500
                                            @elseif($confidencePercent >= 40) bg-amber-500
                                            @else bg-red-500
                                            @endif"
                                    style="width: {{ $confidencePercent }}%">
                                </div>
                            </div>
                        </div>
                        <div class="pt-2">
                            <p class="text-xs text-gray-600">
                                <span class="font-semibold">{{ $confidenceLabel }} Confidence</span> • Based on market analysis
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="lg:col-span-2 flex items-center justify-end gap-2">
                    <form action="{{ route('admin.price-suggestions.approve', $suggestion) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" onclick="return confirm('Approve this price suggestion?')"
                            class="flex items-center gap-2 px-5 py-2.5 bg-admin-primary hover:bg-blue-700 text-white rounded-lg font-semibold transition-colors shadow-sm">
                            <i class="fas fa-check"></i>
                            <span>Approve</span>
                        </button>
                    </form>
                    <form action="{{ route('admin.price-suggestions.reject', $suggestion) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" onclick="return confirm('Reject this suggestion?')"
                            class="flex items-center gap-2 px-5 py-2.5 border border-gray-300 hover:bg-gray-50 text-gray-700 rounded-lg font-semibold transition-colors">
                            <i class="fas fa-times"></i>
                            <span>Reject</span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Reason (if available) -->
            @if($suggestion->reason)
            <div class="px-6 py-3 bg-gray-50 border-t border-gray-200">
                <p class="text-xs text-gray-600">
                    <span class="font-semibold text-gray-700">AI Reasoning:</span> {{ $suggestion->reason }}
                </p>
            </div>
            @endif

            <!-- Metadata -->
            <div class="px-6 py-2 bg-gray-50 border-t border-gray-200 flex items-center justify-between text-xs text-gray-500">
                <span>Suggested {{ $suggestion->created_at->diffForHumans() }}</span>
                <span>#{{ $suggestion->id }}</span>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="bg-white rounded-lg border border-gray-200 px-6 py-4">
        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-600">
                Showing <span class="font-semibold">{{ $suggestions->firstItem() }}</span> to
                <span class="font-semibold">{{ $suggestions->lastItem() }}</span> of
                <span class="font-semibold">{{ $suggestions->total() }}</span> suggestions
            </div>
            <div class="flex gap-2">
                @if($suggestions->onFirstPage())
                <button class="px-3 py-2 border border-gray-300 rounded-lg text-gray-400 cursor-not-allowed">
                    <i class="fas fa-chevron-left"></i>
                </button>
                @else
                <a href="{{ $suggestions->previousPageUrl() }}" class="px-3 py-2 border border-gray-300 hover:bg-gray-100 rounded-lg text-gray-600 transition-colors">
                    <i class="fas fa-chevron-left"></i>
                </a>
                @endif

                @foreach($suggestions->getUrlRange(1, $suggestions->lastPage()) as $page => $url)
                @if($page == $suggestions->currentPage())
                <button class="px-3 py-2 bg-admin-primary text-white rounded-lg font-medium">{{ $page }}</button>
                @else
                <a href="{{ $url }}" class="px-3 py-2 border border-gray-300 hover:bg-gray-100 rounded-lg text-gray-600 transition-colors">{{ $page }}</a>
                @endif
                @endforeach

                @if($suggestions->hasMorePages())
                <a href="{{ $suggestions->nextPageUrl() }}" class="px-3 py-2 border border-gray-300 hover:bg-gray-100 rounded-lg text-gray-600 transition-colors">
                    <i class="fas fa-chevron-right"></i>
                </a>
                @else
                <button class="px-3 py-2 border border-gray-300 rounded-lg text-gray-400 cursor-not-allowed">
                    <i class="fas fa-chevron-right"></i>
                </button>
                @endif
            </div>
        </div>
    </div>
    @else
    <!-- Empty State -->
    <div class="bg-white rounded-lg border border-gray-200 py-16 text-center">
        <div class="text-gray-300 text-6xl mb-4">
            <i class="fas fa-check-circle"></i>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-2">All Caught Up!</h3>
        <p class="text-gray-600 mb-6">No pending price suggestions at the moment. The AI will generate new suggestions as market conditions change.</p>
        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-admin-primary hover:bg-blue-700 text-white rounded-lg font-medium transition-colors">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>
    @endif
</div>

@endsection