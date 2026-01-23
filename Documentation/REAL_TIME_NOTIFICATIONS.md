# Broadcasting Configuration for Real-time Admin Notifications

## Environment Variables (.env)

```env
BROADCAST_DRIVER=reverb

# Reverb Configuration
REVERB_APP_KEY=your-app-key
REVERB_APP_SECRET=your-app-secret
REVERB_APP_ID=1
REVERB_HOST=localhost
REVERB_PORT=8080
REVERB_SCHEME=http
REVERB_CLUSTER=mt1

# Frontend Configuration (VITE)
VITE_REVERB_HOST=localhost
VITE_REVERB_PORT=8080
VITE_REVERB_SCHEME=http
VITE_REVERB_APP_KEY=your-app-key
```

## Configuration Steps

### 1. Update .env file

- Set `BROADCAST_DRIVER=reverb`
- Generate app keys using `php artisan reverb:install`

### 2. Start Reverb Server

```bash
php artisan reverb:start
```

### 3. OrderPlaced Event

- Event broadcasts on `admin-channel` (private channel)
- Event name: `order-placed`
- Triggered immediately after successful order creation in OrderService

### 4. Frontend Integration

- Laravel Echo listens for `order-placed` events
- Toast notifications appear in top-right corner
- Real-time updates without page reload
- Direct link to view order details

## Broadcasting Flow

```
Order Checkout
    ↓
OrderService::processCheckout()
    ↓
Order Created (DB)
    ↓
event(new OrderPlaced($order)) ← Broadcast Event
    ↓
Reverb WebSocket Server
    ↓
Private Channel: admin-channel
    ↓
Admin Layout Echo Listener
    ↓
Toast Notification Display
    ↓
Auto-dismiss after 10 seconds
```

## Testing in Development

### Option 1: Using Reverb

```bash
# Terminal 1: Start Reverb Server
php artisan reverb:start

# Terminal 2: Serve Application
php artisan serve
```

### Option 2: Using Pusher (Production Alternative)

Replace Reverb with Pusher by:

1. Get Pusher credentials from https://pusher.com
2. Update .env:
    ```
    BROADCAST_DRIVER=pusher
    PUSHER_APP_ID=xxxxx
    PUSHER_APP_KEY=xxxxx
    PUSHER_APP_SECRET=xxxxx
    ```

## Notification Features

✅ Real-time order notifications
✅ Customer name, email, amount display
✅ Item count indicator
✅ Payment method badge
✅ Direct "View Order" link
✅ Auto-dismiss after 10 seconds
✅ Optional notification sound
✅ Slide-in animation
✅ Works without page reload
✅ Admin-only private channel

## Architecture

### Backend (Server-Side)

- `app/Events/OrderPlaced.php` - Broadcastable event
- `app/Services/OrderService.php` - Dispatches event
- `config/reverb.php` - WebSocket configuration
- `config/broadcasting.php` - Broadcasting driver setup

### Frontend (Client-Side)

- `resources/js/echo.js` - Echo configuration & listener
- `resources/views/layouts/admin.blade.php` - Layout with container
- Toast UI via Bootstrap 5

### Security

- Private channel restricts access to authenticated admins only
- CSRF token in meta tag
- Event broadcasts only essential order data
- No sensitive customer data in notifications

## Troubleshooting

### WebSocket not connecting

1. Ensure Reverb server is running: `php artisan reverb:start`
2. Check port 8080 is not blocked
3. Verify `VITE_REVERB_HOST` matches server URL

### Notifications not showing

1. Check browser console for errors
2. Verify notification container exists in admin layout
3. Check user is authenticated (private channel requirement)

### Production Deployment

For production, use Pusher or configure Reverb on your server:

1. Install Reverb on production server
2. Run in supervisor for persistence
3. Configure SSL certificates
4. Update environment variables

## References

- [Laravel Reverb Documentation](https://reverb.laravel.com)
- [Laravel Broadcasting](https://laravel.com/docs/11.x/broadcasting)
- [Laravel Echo](https://github.com/laravel/echo)
