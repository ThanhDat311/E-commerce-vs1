import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

const isReverbConfigured =
    import.meta.env.VITE_REVERB_APP_KEY &&
    import.meta.env.VITE_REVERB_APP_KEY !== 'your-app-key' &&
    import.meta.env.VITE_REVERB_HOST &&
    import.meta.env.VITE_REVERB_HOST !== 'localhost';

if (isReverbConfigured) {
    window.Echo = new Echo({
        broadcaster: 'reverb',
        key: import.meta.env.VITE_REVERB_APP_KEY,
        wsHost: import.meta.env.VITE_REVERB_HOST,
        wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
        wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
        forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
        encrypted: true,
        disableStats: true,
    });
} else {
    console.info('Reverb is not configured. Real-time features are disabled.');
}

/**
 * Admin Notification System
 * Listens for OrderPlaced events on admin-channel (private)
 * Only listen if the data attribute exists and Echo is properly initialized
 */
if (document.querySelector('[data-admin-notifications]') && window.Echo && isReverbConfigured) {
    try {
        window.Echo.private('admin-channel').listen('order-placed', (data) => {
            console.log('New Order Received:', data);

            // Create toast notification
            showOrderNotification({
                orderId: data.order_id,
                customerName: data.customer_name,
                customerEmail: data.customer_email,
                amount: data.amount,
                itemsCount: data.items_count,
                paymentMethod: data.payment_method,
                timestamp: data.timestamp,
            });
        });
    } catch (error) {
        console.debug('Could not initialize order listener:', error.message);
    }
}

/**
 * Toast Notification Display Function
 * Shows real-time notification without page reload
 * Uses browser-native approach (no Bootstrap dependency)
 */
function showOrderNotification(orderData) {
    const toastContainer = document.getElementById('notification-container');

    if (!toastContainer) {
        console.warn('Notification container not found');
        return;
    }

    const toastId = `toast-${Date.now()}`;
    const formattedAmount = new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
    }).format(orderData.amount);

    const toastHTML = `
        <div id="${toastId}" class="fixed top-6 right-6 bg-white border-l-4 border-green-500 rounded-lg shadow-lg p-4 max-w-sm z-50 animate-slide-in-right" role="alert">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0 text-green-600">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-sm font-semibold text-gray-900">New Order Received!</h3>
                    <div class="mt-2 text-sm text-gray-700 space-y-1">
                        <p><strong>Order #${orderData.orderId}</strong></p>
                        <p>👤 ${orderData.customerName}</p>
                        <p>📧 ${orderData.customerEmail}</p>
                        <p>📦 ${orderData.itemsCount} item(s)</p>
                        <p class="text-green-600 font-semibold">💰 ${formattedAmount}</p>
                        <p class="text-xs text-gray-500">${new Date(orderData.timestamp).toLocaleString()}</p>
                    </div>
                    <a href="/admin/orders/${orderData.orderId}" class="mt-3 inline-block px-3 py-1 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded transition-colors">
                        View Order →
                    </a>
                </div>
                <button class="flex-shrink-0 text-gray-400 hover:text-gray-600" onclick="document.getElementById('${toastId}').remove()">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </div>
    `;

    toastContainer.innerHTML += toastHTML;

    // Auto-remove after 8 seconds
    setTimeout(() => {
        const element = document.getElementById(toastId);
        if (element) {
            element.remove();
        }
    }, 8000);

    // Play notification sound
    playNotificationSound();
}

/**
 * Play notification sound
 * Optional audio feedback for new orders
 */
function playNotificationSound() {
    const audioContext = new (window.AudioContext || window.webkitAudioContext)();
    const oscillator = audioContext.createOscillator();
    const gainNode = audioContext.createGain();

    oscillator.connect(gainNode);
    gainNode.connect(audioContext.destination);

    oscillator.frequency.value = 800;
    oscillator.type = 'sine';

    gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
    gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.5);

    oscillator.start(audioContext.currentTime);
    oscillator.stop(audioContext.currentTime + 0.5);
}

export { showOrderNotification, playNotificationSound };
