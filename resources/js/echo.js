import Echo from 'laravel-echo';

window.Pusher = require('pusher-js');

/**
 * Laravel Echo Configuration
 * Connects to Reverb WebSocket server for real-time notifications
 */
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

/**
 * Admin Notification System
 * Listens for OrderPlaced events on admin-channel (private)
 */
if (document.querySelector('[data-admin-notifications]')) {
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
}

/**
 * Toast Notification Display Function
 * Shows real-time notification without page reload
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
        <div id="${toastId}" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header bg-success text-white">
                <i class="fas fa-box mr-2"></i>
                <strong class="me-auto">New Order Received!</strong>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                <div class="mb-2">
                    <strong>Order #${orderData.orderId}</strong>
                    <span class="badge bg-info">${orderData.paymentMethod}</span>
                </div>
                <p class="mb-1">
                    <i class="fas fa-user"></i> 
                    <strong>${orderData.customerName}</strong>
                </p>
                <p class="mb-1">
                    <i class="fas fa-envelope"></i> 
                    ${orderData.customerEmail}
                </p>
                <p class="mb-1">
                    <i class="fas fa-box"></i> 
                    <strong>${orderData.itemsCount}</strong> item(s)
                </p>
                <p class="mb-0">
                    <i class="fas fa-dollar-sign"></i> 
                    <strong class="text-success">${formattedAmount}</strong>
                </p>
                <small class="text-muted d-block mt-2">
                    ${new Date(orderData.timestamp).toLocaleString()}
                </small>
                <div class="mt-3">
                    <a href="/admin/orders/${orderData.orderId}" class="btn btn-sm btn-primary">
                        View Order →
                    </a>
                </div>
            </div>
        </div>
    `;

    toastContainer.innerHTML += toastHTML;

    // Initialize and show toast
    const toastElement = document.getElementById(toastId);
    const bsToast = new bootstrap.Toast(toastElement);
    bsToast.show();

    // Auto-remove after 10 seconds
    toastElement.addEventListener('hidden.bs.toast', () => {
        toastElement.remove();
    });

    // Play notification sound (optional)
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
