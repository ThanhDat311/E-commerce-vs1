<!-- Smart Search Bar Component - Header -->
<div class="w-full max-w-2xl mx-auto">
    <div x-data="advancedSearchBar()" class="relative">
        <!-- Search Input -->
        <div class="relative flex items-center bg-white border border-gray-300 rounded-lg shadow-sm hover:shadow-md transition focus-within:ring-2 focus-within:ring-blue-500">
            <svg class="w-5 h-5 text-gray-400 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>

            <input
                type="text"
                x-model="query"
                @input="debounceSearch()"
                @keydown.enter="performSearch()"
                @keydown.escape="isOpen = false"
                @keydown.arrow-down="focusSuggestion('down')"
                @keydown.arrow-up="focusSuggestion('up')"
                @keydown.enter="selectSuggestion(suggestions[selectedIndex])"
                @focus="isOpen = true"
                placeholder="Search products, categories..."
                aria-label="Search products"
                aria-autocomplete="list"
                aria-controls="search-suggestions"
                :aria-expanded="isOpen && (suggestions.length > 0 || loading)"
                class="w-full px-4 py-3 border-0 rounded-lg focus:outline-none text-gray-900 placeholder-gray-500 bg-transparent" />

            <!-- Loading Spinner -->
            <div x-show="loading" class="mr-3">
                <svg class="animate-spin w-5 h-5 text-blue-500" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>

            <!-- Search Button -->
            <button
                @click="performSearch()"
                type="button"
                aria-label="Search"
                class="px-4 py-3 bg-blue-600 text-white rounded-r-lg hover:bg-blue-700 transition font-medium">
                Search
            </button>
        </div>

        <!-- Suggestions Dropdown -->
        <div
            x-show="isOpen && (suggestions.length > 0 || loading)"
            @click.outside="isOpen = false"
            x-transition
            id="search-suggestions"
            role="listbox"
            class="absolute top-full left-0 right-0 mt-2 bg-white border border-gray-300 rounded-lg shadow-xl z-50 max-h-96 overflow-y-auto">
            <!-- Loading State -->
            <div x-show="loading" class="px-4 py-8 text-center">
                <div class="inline-flex items-center gap-2">
                    <svg class="animate-spin w-5 h-5 text-blue-500" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-sm text-gray-600">Loading suggestions...</span>
                </div>
            </div>

            <!-- Suggestions List -->
            <template x-for="(suggestion, index) in suggestions" :key="`${suggestion.id}-${index}`">
                <button
                    type="button"
                    role="option"
                    @click="selectSuggestion(suggestion)"
                    @mouseenter="selectedIndex = index"
                    :aria-selected="selectedIndex === index"
                    :class="{
                        'bg-blue-50 border-l-4 border-l-blue-500': selectedIndex === index,
                        'bg-white hover:bg-gray-50': selectedIndex !== index
                    }"
                    class="w-full px-4 py-3 text-left border-b border-gray-100 last:border-b-0 transition flex items-center gap-3">
                    <!-- Product Image -->
                    <div class="flex-shrink-0">
                        <template x-if="suggestion.image">
                            <img
                                :src="suggestion.image"
                                :alt="suggestion.name"
                                class="w-10 h-10 object-cover rounded" />
                        </template>
                        <template x-if="!suggestion.image">
                            <div class="w-10 h-10 bg-gray-200 rounded flex items-center justify-center">
                                <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"></path>
                                </svg>
                            </div>
                        </template>
                    </div>

                    <!-- Product Info -->
                    <div class="flex-1 min-w-0">
                        <h3 class="text-sm font-medium text-gray-900 truncate">
                            <span x-html="highlightQuery(suggestion.name)"></span>
                        </h3>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="text-xs text-gray-500" x-text="suggestion.category"></span>
                            <span class="text-xs font-semibold text-blue-600" x-text="'$' + parseFloat(suggestion.price).toFixed(2)"></span>
                        </div>
                    </div>

                    <!-- Arrow Icon -->
                    <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
            </template>

            <!-- No Results Message -->
            <div x-show="!loading && query.length >= 2 && suggestions.length === 0" class="px-4 py-8 text-center">
                <svg class="w-12 h-12 text-gray-300 mx-auto mb-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
                </svg>
                <p class="text-sm text-gray-600">No products found for "<span x-text="query" class="font-medium"></span>"</p>
            </div>

            <!-- Minimum Characters Message -->
            <div x-show="query.length > 0 && query.length < 2" class="px-4 py-4 text-center">
                <p class="text-sm text-gray-600">Type at least 2 characters to search</p>
            </div>
        </div>
    </div>
</div>

<script>
    function advancedSearchBar() {
        return {
            query: '',
            suggestions: [],
            isOpen: false,
            loading: false,
            selectedIndex: -1,
            debounceTimer: null,

            /**
             * Debounce search input (300ms)
             */
            debounceSearch() {
                clearTimeout(this.debounceTimer);
                this.selectedIndex = -1;

                if (this.query.length < 2) {
                    this.suggestions = [];
                    this.isOpen = false;
                    return;
                }

                this.debounceTimer = setTimeout(() => {
                    this.fetchSuggestions();
                }, 300);
            },

            /**
             * Fetch suggestions from API
             */
            async fetchSuggestions() {
                this.loading = true;
                this.isOpen = true;

                try {
                    const params = new URLSearchParams({
                        q: this.query,
                        limit: 8,
                    });

                    const response = await fetch(`/api/v1/search/suggestions?${params}`);

                    if (!response.ok) {
                        throw new Error(`HTTP ${response.status}`);
                    }

                    const data = await response.json();

                    if (data.status === 'success') {
                        this.suggestions = data.data;
                    } else {
                        this.suggestions = [];
                    }
                } catch (error) {
                    console.error('Search suggestions error:', error);
                    this.suggestions = [];
                } finally {
                    this.loading = false;
                }
            },

            /**
             * Highlight matching text in suggestions
             */
            highlightQuery(text) {
                if (!this.query) return text;

                const regex = new RegExp(`(${this.escapeRegex(this.query)})`, 'gi');
                return text.replace(regex, '<strong class="font-semibold text-blue-600">$1</strong>');
            },

            /**
             * Escape special regex characters
             */
            escapeRegex(str) {
                return str.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
            },

            /**
             * Navigate suggestions with arrow keys
             */
            focusSuggestion(direction) {
                if (this.suggestions.length === 0) return;

                if (direction === 'down') {
                    this.selectedIndex = Math.min(this.selectedIndex + 1, this.suggestions.length - 1);
                } else if (direction === 'up') {
                    this.selectedIndex = Math.max(this.selectedIndex - 1, -1);
                }
            },

            /**
             * Select a suggestion and navigate to product
             */
            selectSuggestion(suggestion) {
                if (!suggestion) return;

                // Navigate to product detail page
                window.location.href = `/products/${suggestion.id}`;
            },

            /**
             * Perform full search
             */
            performSearch() {
                if (!this.query.trim()) return;

                const params = new URLSearchParams({
                    q: this.query,
                    per_page: 12,
                });

                // Redirect to search results page
                window.location.href = `/search?${params}`;
            },
        };
    }
</script>

<style>
    /* Smooth animations */
    [x-cloak] {
        display: none !important;
    }

    @keyframes spin {
        from {
            transform: rotate(0deg);
        }

        to {
            transform: rotate(360deg);
        }
    }

    .animate-spin {
        animation: spin 1s linear infinite;
    }

    /* Scrollbar styling for suggestions dropdown */
    #search-suggestions::-webkit-scrollbar {
        width: 6px;
    }

    #search-suggestions::-webkit-scrollbar-track {
        background: #f1f5f9;
    }

    #search-suggestions::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 3px;
    }

    #search-suggestions::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
</style>