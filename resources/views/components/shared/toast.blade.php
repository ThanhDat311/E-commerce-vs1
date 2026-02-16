{{-- Global Toast Notification System --}}
<div
    x-data="toastNotification()"
    x-on:show-toast.window="addToast($event.detail)"
    class="fixed top-4 left-4 right-4 sm:left-auto sm:right-4 z-[9999] flex flex-col items-stretch sm:items-end gap-4 pointer-events-none w-auto sm:w-full sm:max-w-md"
>
    <template x-for="toast in toasts" :key="toast.id">
        <div
            x-show="toast.visible"
            x-transition:enter="transform ease-out duration-300"
            x-transition:enter-start="translate-x-full opacity-0"
            x-transition:enter-end="translate-x-0 opacity-100"
            x-transition:leave="transform ease-in duration-200"
            x-transition:leave-start="translate-x-0 opacity-100"
            x-transition:leave-end="translate-x-full opacity-0"
            class="pointer-events-auto w-full max-w-sm overflow-hidden rounded-xl shadow-2xl ring-1 ring-black/5"
            :class="{
                'bg-white': true
            }"
            role="alert"
        >
            <div class="relative">
                {{-- Progress bar --}}
                <div class="absolute bottom-0 left-0 h-1 rounded-b-xl transition-all duration-100 ease-linear"
                     :class="{
                        'bg-emerald-500': toast.type === 'success',
                        'bg-red-500': toast.type === 'error',
                        'bg-amber-500': toast.type === 'warning',
                        'bg-blue-500': toast.type === 'info'
                     }"
                     :style="`width: ${toast.progress}%`"
                ></div>

                <div class="p-4">
                    <div class="flex items-start">
                        {{-- Icon --}}
                        <div class="flex-shrink-0">
                            {{-- Success --}}
                            <template x-if="toast.type === 'success'">
                                <div class="flex items-center justify-center w-8 h-8 rounded-full bg-emerald-100">
                                    <svg class="w-5 h-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                            </template>
                            {{-- Error --}}
                            <template x-if="toast.type === 'error'">
                                <div class="flex items-center justify-center w-8 h-8 rounded-full bg-red-100">
                                    <svg class="w-5 h-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </div>
                            </template>
                            {{-- Warning --}}
                            <template x-if="toast.type === 'warning'">
                                <div class="flex items-center justify-center w-8 h-8 rounded-full bg-amber-100">
                                    <svg class="w-5 h-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                </div>
                            </template>
                            {{-- Info --}}
                            <template x-if="toast.type === 'info'">
                                <div class="flex items-center justify-center w-8 h-8 rounded-full bg-blue-100">
                                    <svg class="w-5 h-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                            </template>
                        </div>

                        {{-- Content --}}
                        <div class="ml-3 flex-1 min-w-0 pr-2">
                            <p class="text-sm font-semibold text-gray-900 truncate" x-text="toast.title"></p>
                            <p class="mt-0.5 text-sm text-gray-600 break-words" x-text="toast.message"></p>
                        </div>

                        {{-- Close button --}}
                        <div class="ml-4 flex flex-shrink-0">
                            <button
                                @click="removeToast(toast.id)"
                                class="inline-flex rounded-md bg-white text-gray-400 hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors"
                            >
                                <span class="sr-only">Close</span>
                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>

<script>
    const TOAST_TITLES = {
        success: 'Success',
        error: 'Error',
        warning: 'Warning',
        info: 'Information'
    };

    function toastNotification() {
        return {
            toasts: [],
            counter: 0,

            init() {
                // Expose global showToast function
                window.showToast = (message, type = 'success', duration = 4000) => {
                    this.addToast({ message, type, duration });
                };

                // Auto-read Laravel session flash messages
                @if(session('success'))
                    this.addToast({ message: '{{ session("success") }}', type: 'success' });
                @endif
                @if(session('error'))
                    this.addToast({ message: '{{ session("error") }}', type: 'error' });
                @endif
                @if(session('warning'))
                    this.addToast({ message: '{{ session("warning") }}', type: 'warning' });
                @endif
                @if(session('info'))
                    this.addToast({ message: '{{ session("info") }}', type: 'info' });
                @endif
            },

            addToast({ message, type = 'success', title = null, duration = 4000 }) {
                const id = ++this.counter;
                const toast = {
                    id,
                    message,
                    type,
                    title: title || TOAST_TITLES[type] || 'Notification',
                    visible: true,
                    progress: 100,
                    duration
                };

                this.toasts.push(toast);

                // Animate progress bar
                const interval = 50;
                const step = (100 / (duration / interval));
                const progressInterval = setInterval(() => {
                    const t = this.toasts.find(t => t.id === id);
                    if (!t) {
                        clearInterval(progressInterval);
                        return;
                    }
                    t.progress = Math.max(0, t.progress - step);
                }, interval);

                // Auto-dismiss
                setTimeout(() => {
                    clearInterval(progressInterval);
                    this.removeToast(id);
                }, duration);
            },

            removeToast(id) {
                const toast = this.toasts.find(t => t.id === id);
                if (toast) {
                    toast.visible = false;
                    setTimeout(() => {
                        this.toasts = this.toasts.filter(t => t.id !== id);
                    }, 300);
                }
            }
        };
    }
</script>
