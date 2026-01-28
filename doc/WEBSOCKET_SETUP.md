# WebSocket Real-Time Notifications Setup Guide

## Overview

The E-commerce application uses Laravel Reverb for real-time WebSocket notifications. Currently, the configuration uses placeholder values which prevent real-time features from working.

## Errors You're Seeing

1. **"Cannot read properties of undefined (reading 'addEventListener')"**
    - The Echo socket object is undefined because Reverb isn't properly configured
    - This is now fixed with null checks

2. **CORS errors from sockjs.pusher.com**
    - When Reverb fails, it falls back to Pusher which requires API keys
    - The current setup doesn't have valid Pusher credentials

3. **WebSocket connection failures to localhost:8080**
    - The Reverb server isn't running on localhost:8080
    - Need to start it with `php artisan reverb:start`

## Current Status

The application has been updated with **graceful fallback handling**:

- ✅ If Reverb isn't configured, the app works without real-time features
- ✅ No JavaScript errors are thrown
- ✅ Notifications can be manually refreshed if needed

## Setting Up Reverb (For Production)

### Step 1: Generate Reverb Credentials

```bash
php artisan reverb:install
```

This command will:

- Generate secure `REVERB_APP_KEY` and `REVERB_APP_SECRET`
- Update your `.env` file with real credentials
- Create necessary configurations

### Step 2: Update .env File

After running the install command, verify these values in `.env`:

```env
BROADCAST_CONNECTION=reverb
REVERB_APP_KEY=your-generated-key
REVERB_APP_SECRET=your-generated-secret
REVERB_APP_ID=1
REVERB_HOST=your-domain.com      # For production, use your domain
REVERB_PORT=443                  # Use 443 for HTTPS
REVERB_SCHEME=https              # Use https for production
REVERB_CLUSTER=mt1

# Frontend configuration
VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"
VITE_REVERB_HOST="${REVERB_HOST}"
VITE_REVERB_PORT="${REVERB_PORT}"
VITE_REVERB_SCHEME="${REVERB_SCHEME}"
```

### Step 3: Start the Reverb Server (Development)

For local development:

```bash
php artisan reverb:start
```

This will start the WebSocket server on localhost:8080

### Step 4: Run Vite in Development Mode

In another terminal:

```bash
npm run dev
```

This rebuilds the JavaScript with the correct Reverb configuration.

### Step 5: Configure for Production

For production deployment using Laravel Herd or similar:

1. **Set proper environment variables** on your hosting provider:
    - Use your actual domain for `REVERB_HOST`
    - Use port 443 for `REVERB_PORT`
    - Use 'https' for `REVERB_SCHEME`

2. **Run the build command**:

    ```bash
    npm run build
    ```

3. **Ensure Reverb server is running**:
    - Use a process manager like Supervisor
    - Or use `php artisan reverb:start` in a background service

## How Real-Time Notifications Work

When properly configured:

1. **Order Placed Event** → Broadcast to 'admin-channel'
2. **Echo Listener** → Receives event in real-time
3. **Toast Notification** → Displays without page reload
4. **Sound Alert** → Optional audio feedback

## Fallback Behavior (Current)

With the recent fixes:

- ✅ No JavaScript errors even without Reverb
- ✅ Notifications still work if manually refreshed
- ✅ Admin panel is fully functional
- ⚠️ Real-time updates not available until Reverb is configured

## Testing the Setup

### Test 1: Verify Configuration

```php
// In tinker or a controller
php artisan tinker
>>> config('reverb.apps.0.key')
// Should return your actual key, not 'your-app-key'
```

### Test 2: Check Echo Connection

Open browser console and check:

```javascript
console.log(window.Echo);
console.log(window.Echo.connector);
console.log(window.Echo.connector.socket);
```

Should show proper objects, not undefined.

### Test 3: Listen for Events

In the admin dashboard:

1. Open browser DevTools
2. Check the Network tab for WebSocket connections
3. Should see `wss://` connections to your Reverb host

## Troubleshooting

### "Your-app-key" still appears in connections

- Run `php artisan config:cache` to clear config cache
- Rebuild JavaScript: `npm run build`
- Clear browser cache

### Reverb server won't start

```bash
# Check if port 8080 is in use
netstat -ano | findstr :8080  # Windows
lsof -i :8080                  # Mac/Linux

# Kill process or use different port
php artisan reverb:start --port=9000
```

### WebSocket shows as undefined in browser console

- Verify `.env` file has correct values
- Check if Reverb server is running
- Rebuild with `npm run build`
- Restart the application

## Environment Variables Reference

| Variable               | Development | Production       |
| ---------------------- | ----------- | ---------------- |
| `BROADCAST_CONNECTION` | `reverb`    | `reverb`         |
| `REVERB_HOST`          | `localhost` | `yourdomain.com` |
| `REVERB_PORT`          | `8080`      | `443`            |
| `REVERB_SCHEME`        | `http`      | `https`          |
| `VITE_REVERB_PORT`     | `8080`      | `443`            |
| `VITE_REVERB_SCHEME`   | `http`      | `https`          |

## Additional Resources

- [Laravel Reverb Documentation](https://laravel.com/docs/reverb)
- [Laravel Broadcasting](https://laravel.com/docs/broadcasting)
- [WebSocket Configuration](https://laravel.com/docs/reverb#installation)

---

**Status**: ✅ Application is stable with graceful fallback
**Last Updated**: January 29, 2026
