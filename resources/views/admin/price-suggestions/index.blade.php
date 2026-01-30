@extends('admin.layout.admin')

@section('title', 'AI Price Suggestions')

@section('content')
<div class="space-y-8">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <div class="p-4 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl shadow-lg">
                <i class="fas fa-brain text-white text-3xl"></i>
            </div>
            <div>
                <h1 class="text-4xl font-bold text-gray-900">AI Price Suggestions</h1>
                <p class="text-gray-600 text-sm mt-1">Review and approve AI-generated pricing recommendations</p>
            </div>
        </div>
        <div class="flex gap-3">
            <div class="px-4 py-2.5 bg-blue-50 text-blue-700 border border-blue-200 rounded-lg font-bold">
                <i class="fas fa-robot mr-2"></i>{{ $suggestions->total() }} Suggestions Pending
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-6 rounded-xl border border-blue-200 shadow-sm">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm font-medium text-blue-700 uppercase tracking-wide">Total Suggestions</p>
                    <p class="text-3xl font-bold text-blue-900 mt-2">{{ $suggestions->total() }}</p>
                </div>
                <div class="p-3 bg-blue-200 rounded-lg text-blue-600">
                    <i class="fas fa-list-ol"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 p-6 rounded-xl border border-indigo-200 shadow-sm">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm font-medium text-indigo-700 uppercase tracking-wide">Avg. Confidence</p>
                    <p class="text-3xl font-bold text-indigo-900 mt-2">84%</p>
                </div>
                <div class="p-3 bg-indigo-200 rounded-lg text-indigo-600">
                    <i class="fas fa-bullseye"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 p-6 rounded-xl border border-emerald-200 shadow-sm">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm font-medium text-emerald-700 uppercase tracking-wide">High Impact</p>
                    <p class="text-3xl font-bold text-emerald-900 mt-2">12 Items</p>
                </div>
                <div class="p-3 bg-emerald-200 rounded-lg text-emerald-600">
                    <i class="fas fa-bolt"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="space-y-4">
        <h3 class="font-bold text-gray-900 text-lg flex items-center gap-2">
            <i class="fas fa-lightbulb text-amber-400"></i> AI Recommendations
        </h3>
        
        @forelse($suggestions as $suggestion)
        <div class="group bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-md hover:border-blue-300 transition-all p-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-5 flex-1">
                    <div class="w-20 h-20 bg-gray-50 rounded-xl border border-gray-100 flex items-center justify-center overflow-hidden">
                        <img src="{{ asset('storage/' . $suggestion->product->image) }}" 
                             onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($suggestion->product->name) }}&color=7F9CF5&background=EBF4FF'" 
                             class="object-cover w-full h-full">
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 group-hover:text-blue-600 transition-colors">{{ $suggestion->product->name }}</h3>
                        <div class="flex items-center gap-3 mt-1">
                            <span class="text-sm text-gray-500 line-through">${{ number_format($suggestion->current_price, 2) }}</span>
                            <span class="px-2 py-0.5 bg-gray-100 text-gray-600 rounded text-xs font-bold uppercase">Current Price</span>
                        </div>
                    </div>
                </div>

                <div class="flex-1 text-center">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Suggested Price</p>
                    <div class="flex items-center justify-center gap-2">
                        <span class="text-3xl font-black text-blue-600">${{ number_format($suggestion->suggested_price, 2) }}</span>
                        @php $diff = (($suggestion->suggested_price - $suggestion->current_price) / $suggestion->current_price) * 100; @endphp
                        <span class="text-sm font-bold {{ $diff > 0 ? 'text-green-500' : 'text-red-500' }}">
                            {{ $diff > 0 ? '↑' : '↓' }} {{ abs(round($diff, 1)) }}%
                        </span>
                    </div>
                </div>

                <div class="flex items-center gap-4 flex-1 justify-end">
                    <div class="text-right mr-4">
                        <p class="text-xs font-bold text-blue-500 uppercase">Confidence</p>
                        <div class="w-24 bg-gray-100 h-2 rounded-full mt-1">
                            <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $suggestion->confidence_score }}%"></div>
                        </div>
                        <p class="text-sm font-black text-gray-700 mt-1">{{ $suggestion->confidence_score }}%</p>
                    </div>
                    
                    <form action="{{ route('admin.price-suggestions.approve', $suggestion) }}" method="POST">
                        @csrf
                        <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-bold shadow-lg shadow-blue-100 transition-all">
                            Approve
                        </button>
                    </form>
                    <button class="p-2.5 text-gray-400 hover:text-red-500 transition-colors">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-2xl border-2 border-dashed border-gray-200 py-20 text-center">
            <div class="text-gray-200 text-7xl mb-4">
                <i class="fas fa-robot"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900">System Optimized</h3>
            <p class="text-gray-500">No new price suggestions at the moment.</p>
        </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $suggestions->links() }}
    </div>
</div>

<style>
    /* CSS hỗ trợ đồng bộ */
    .bg-gradient-to-br { background-image: linear-gradient(to bottom right, var(--tw-gradient-stops)); }
    .from-blue-500 { --tw-gradient-from: #3b82f6; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, rgba(59, 130, 246, 0)); }
    .to-indigo-600 { --tw-gradient-to: #4f46e5; }
</style>
@endsection