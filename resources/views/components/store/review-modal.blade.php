<div x-data="{
        isOpen: false,
        productId: null,
        productName: '',
        rating: 0,
        hovered: 0,
        comment: '',
        openModal(e) {
            this.productId = e.detail.product_id;
            this.productName = e.detail.product_name;
            this.isOpen = true;
            this.rating = 0;
            this.hovered = 0;
            this.comment = '';
        }
    }"
    @open-review-modal.window="openModal($event)"
    x-show="isOpen"
    class="relative z-50"
    style="display: none;"
>
    <!-- Backdrop -->
    <div x-show="isOpen" x-transition.opacity class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity"></div>

    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <!-- Modal panel -->
            <div x-show="isOpen"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 @click.outside="isOpen = false"
                 class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                 
                 <!-- Header -->
                 <div class="bg-indigo-600 px-6 py-4 flex items-center justify-between">
                     <h3 class="text-lg font-semibold text-white">Write a Review</h3>
                     <button @click="isOpen = false" class="text-indigo-200 hover:text-white transition-colors">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                     </button>
                 </div>

                 <!-- Content -->
                 <form :action="'/products/' + productId + '/reviews'" method="POST" class="p-6">
                    @csrf
                    
                    <p class="text-sm text-gray-500 mb-6">
                        You're reviewing: <span class="font-medium text-gray-900" x-text="productName"></span>
                    </p>

                    <!-- Star Rating -->
                    <div class="mb-5">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Rating <span class="text-red-500">*</span></label>
                        <div class="flex items-center gap-1">
                            <template x-for="i in 5" :key="i">
                                <button type="button"
                                    @click="rating = i"
                                    @mouseenter="hovered = i"
                                    @mouseleave="hovered = 0"
                                    class="text-gray-300 hover:text-yellow-400 focus:outline-none transition-colors"
                                    :class="(hovered >= i || (hovered === 0 && rating >= i)) ? 'text-yellow-400' : 'text-gray-300'"
                                >
                                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                </button>
                            </template>
                            <span class="ml-2 text-sm text-gray-500" x-text="rating > 0 ? rating + ' star' + (rating > 1 ? 's' : '') : 'Click to rate'"></span>
                        </div>
                        <input type="hidden" name="rating" :value="rating">
                    </div>

                    <!-- Comment -->
                    <div class="mb-6">
                        <label for="review-comment-modal" class="block text-sm font-medium text-gray-700 mb-1">Your Review</label>
                        <textarea id="review-comment-modal" name="comment" x-model="comment" rows="4"
                            placeholder="Share your experience with this product..."
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                        <button type="button" @click="isOpen = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                            Cancel
                        </button>
                        <button type="submit"
                            x-bind:disabled="rating === 0"
                            class="inline-flex items-center px-5 py-2 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors shadow-sm">
                            Submit Review
                        </button>
                    </div>
                 </form>
            </div>
        </div>
    </div>
</div>
