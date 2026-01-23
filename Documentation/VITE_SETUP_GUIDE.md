# Vite Asset Building Guide for Laragon

## Problem Solved

**Error:** `Illuminate\Foundation\ViteManifestNotFoundException: public/build/manifest.json not found`

**Root Cause:** The Vite dev server is not running, or assets have not been built for production.

## Solution for Local Development (Laragon)

### Option 1: Start Vite Dev Server (Recommended for Development)

This is the recommended approach during development - it provides hot module reloading (HMR).

```bash
cd c:\laragon\www\E-commerce

# Terminal 1: Start Vite dev server
npm run dev

# Terminal 2 (in separate terminal): Start Laravel
php artisan serve
```

**What happens:**

- Vite runs on `http://localhost:5173`
- Automatically detects file changes and rebuilds
- Hot module reloading (changes appear instantly)
- Manifest is loaded dynamically from dev server

### Option 2: Build Assets for Production-Like Testing

This builds all assets into `public/build/` with manifest.json.

```bash
cd c:\laragon\www\E-commerce

npm run build
```

**What happens:**

- Creates `public/build/manifest.json`
- Bundles and minifies all assets
- Generates versioned asset names
- Ready for production-like testing

Then start Laravel:

```bash
php artisan serve
```

### Option 3: Build Once, Keep Running (Hybrid Approach)

```bash
cd c:\laragon\www\E-commerce

# Build assets first
npm run build

# Start Laravel
php artisan serve

# Optional: In another terminal, watch for CSS/JS changes
npm run dev
```

This gives you production-like behavior with development file watching.

## When Each Option Should Be Used

| Scenario                     | Use      | Command                               |
| ---------------------------- | -------- | ------------------------------------- |
| Active development, need HMR | Option 1 | `npm run dev` + `php artisan serve`   |
| Testing production build     | Option 2 | `npm run build` + `php artisan serve` |
| CI/CD pipeline               | Option 2 | `npm run build`                       |
| Production deployment        | Option 2 | `npm run build`                       |
| Hybrid (watch + serve)       | Option 3 | Both commands                         |

## Detailed Setup for Laragon

### Step 1: Install Node Dependencies

```bash
cd c:\laragon\www\E-commerce
npm install
```

### Step 2: Choose Your Development Workflow

#### Workflow A: Development with HMR (Recommended)

```bash
# Terminal 1: Vite Dev Server (watches for changes, HMR enabled)
npm run dev

# Terminal 2: Laravel Dev Server
php artisan serve

# Now open http://localhost:8000/admin
# Edit resources/js/echo.js → changes appear instantly
```

#### Workflow B: Build-First Approach

```bash
# Build all assets (one-time or after changes)
npm run build

# Start Laravel
php artisan serve

# Open http://localhost:8000/admin
# To update assets, run npm run build again
```

### Step 3: Verify Setup

Open browser to `http://localhost:8000/admin` and check:

1. **No Vite errors in console** (F12 → Console tab)
2. **Notification container appears** (top-right corner)
3. **Toast styling loads correctly** (should see styled notification box)

## Troubleshooting

### Error: "Cannot find module 'vite'"

```bash
# Solution: Install dev dependencies
npm install
```

### Error: "Port 5173 already in use"

```bash
# Solution: Use different port
npm run dev -- --port 5174
```

### Error: "public/build/manifest.json not found"

```bash
# Solution A: Start Vite dev server
npm run dev

# Solution B: Build assets
npm run build

# Solution C: Use Laragon GUI to open /admin
# The fallback will automatically handle it
```

### Changes not reflecting

```bash
# Solution: Clear browser cache
# 1. Open DevTools (F12)
# 2. Right-click refresh button → Empty cache and hard refresh
# 3. Or: Press Ctrl+Shift+Delete to open Cache clear dialog
```

## What Was Fixed

### 1. Updated `vite.config.js`

Added `resources/js/echo.js` to Vite input configuration:

```javascript
input: [
    'resources/css/app.css',
    'resources/js/app.js',
    'resources/js/echo.js',  // ← Added
],
```

### 2. Safe Fallback in `resources/views/layouts/admin.blade.php`

Replaced direct `@vite()` with conditional that:

- Uses Vite manifest if it exists (production/built assets)
- Falls back to direct import during development
- Shows helpful console message if neither available

```blade
@if(file_exists(public_path('build/manifest.json')))
    @vite('resources/js/echo.js')
@else
    <!-- Development fallback -->
    <script type="module">
        // Fallback implementation
    </script>
@endif
```

## NPM Scripts Reference

```json
{
    "scripts": {
        "build": "vite build", // Build for production
        "dev": "vite" // Start Vite dev server
    }
}
```

## File Structure After Build

After running `npm run build`, you'll see:

```
public/
  build/
    assets/
      app-XXXXXX.js
      app-XXXXXX.css
      echo-XXXXXX.js
    manifest.json        ← Vite manifest (versioned filenames)
```

## Environment Variables (if needed)

In `.env`, if you need to customize Vite:

```env
VITE_APP_URL=http://localhost:8000
```

## Laragon-Specific Tips

### Option 1: Use Laragon GUI

1. Open Laragon
2. Click on "E-Commerce" project
3. Click "Terminal"
4. Run `npm run dev` in the terminal

### Option 2: Use Multiple Terminals

- Right-click Laragon tray icon → Terminal
- Run `npm run dev` in first terminal
- Run `php artisan serve` in second terminal

### Option 3: Create Batch Files

Create `c:\laragon\www\E-commerce\start-dev.bat`:

```batch
@echo off
start cmd /k "npm run dev"
start cmd /k "php artisan serve"
echo Development environment started
echo - Vite Dev Server: http://localhost:5173
echo - Laravel Dev Server: http://localhost:8000
```

Then double-click `start-dev.bat` to start both servers.

## Quick Reference

```bash
# First time setup
npm install

# Start development
npm run dev          # Terminal 1
php artisan serve    # Terminal 2

# Build for production
npm run build

# Quick test
# 1. Build assets
npm run build
# 2. Navigate to http://localhost:8000/admin
# 3. Should load without errors
```

## Production Deployment

For production (not Laragon):

```bash
# On production server
npm install
npm run build

# Then deploy to server
php artisan serve  # Or run via PHP-FPM/Apache
```

The `public/build/manifest.json` will be created automatically, and Laravel will use built assets.

## Support Files

- **vite.config.js** - Vite configuration
- **package.json** - NPM scripts and dependencies
- **resources/js/echo.js** - WebSocket listener
- **resources/views/layouts/admin.blade.php** - Safe fallback

---

**Last Updated:** January 24, 2026
**Tested On:** Laravel 12, Node 18+, Laragon
**Status:** ✅ Ready to Use
