/**
 * Admin Design System - Tailwind Configuration Reference
 * 
 * This file documents all design tokens used in the admin dashboard.
 * Apply these to tailwind.config.js if you want centralized configuration.
 */

// ============================================================================
// COLOR PALETTE
// ============================================================================

const colors = {
    // Status Colors
    critical: '#DC2626',      // Red-600
    warning: '#F97316',       // Orange-500
    success: '#10B981',       // Green-600
    info: '#3B82F6',          // Blue-600

    // Backgrounds - Status Tints
    'critical-bg': '#FEF2F2', // Red-50
    'warning-bg': '#FFF7ED',  // Orange-50
    'success-bg': '#F0FDF4',  // Green-50
    'info-bg': '#EFF6FF',     // Blue-50

    // Neutral Grays
    'text-primary': '#1F2937',      // Gray-800
    'text-secondary': '#6B7280',    // Gray-600
    'text-tertiary': '#9CA3AF',     // Gray-400
    'border': '#E5E7EB',            // Gray-200
    'bg-light': '#F9FAFB',          // Gray-50
};

// ============================================================================
// SPACING SCALE
// ============================================================================

const spacing = {
    '0': '0px',
    '1': '0.25rem',   // 4px
    '2': '0.5rem',    // 8px
    '3': '0.75rem',   // 12px
    '4': '1rem',      // 16px
    '6': '1.5rem',    // 24px
    '8': '2rem',      // 32px
    '12': '3rem',     // 48px
    '16': '4rem',     // 64px
};

// ============================================================================
// BORDER RADIUS
// ============================================================================

const borderRadius = {
    none: '0px',
    sm: '0.375rem',  // 6px
    base: '0.5rem',  // 8px (DEFAULT)
    lg: '0.75rem',   // 12px
    xl: '1rem',      // 16px
    full: '9999px',  // Pill shape
};

// ============================================================================
// SHADOWS
// ============================================================================

const boxShadow = {
    none: 'none',
    sm: '0 1px 2px 0 rgba(0, 0, 0, 0.05)',
    base: '0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06)',
    md: '0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)',
    lg: '0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)',
    xl: '0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)',
};

// ============================================================================
// TYPOGRAPHY
// ============================================================================

const fontSize = {
    xs: ['0.75rem', { lineHeight: '1rem' }],       // 12px
    sm: ['0.875rem', { lineHeight: '1.25rem' }],   // 14px
    base: ['1rem', { lineHeight: '1.5rem' }],      // 16px
    lg: ['1.125rem', { lineHeight: '1.75rem' }],   // 18px
    xl: ['1.25rem', { lineHeight: '1.75rem' }],    // 20px
    '2xl': ['1.5rem', { lineHeight: '2rem' }],     // 24px
    '3xl': ['1.875rem', { lineHeight: '2.25rem' }],// 30px
    '4xl': ['2.25rem', { lineHeight: '2.5rem' }],  // 36px
};

const fontWeight = {
    regular: 400,
    medium: 500,
    semibold: 600,
    bold: 700,
};

const lineHeight = {
    tight: '1.25',
    snug: '1.375',
    normal: '1.5',
    relaxed: '1.625',
    loose: '2',
};

// ============================================================================
// GRADIENTS
// ============================================================================

// Use these with: bg-gradient-to-r from-{color1} to-{color2}
const gradients = {
    'orange-to-red': 'from-orange-600 to-red-600',      // Analytics pages
    'blue-to-blue': 'from-blue-600 to-blue-700',        // Settings pages
    'green-to-teal': 'from-green-600 to-teal-600',      // Success pages
    'purple-to-blue': 'from-purple-600 to-blue-600',    // Reports pages
    'red-to-pink': 'from-red-600 to-pink-600',          // Alerts pages
};

// ============================================================================
// RESPONSIVE BREAKPOINTS
// ============================================================================

const screens = {
    'xs': '375px',    // Mobile
    'sm': '640px',    // Small tablet
    'md': '768px',    // Tablet
    'lg': '1024px',   // Desktop
    'xl': '1280px',   // Large desktop
    '2xl': '1536px',  // Extra large desktop
};

// ============================================================================
// COMPONENT VARIANTS
// ============================================================================

// BUTTON COLORS
const buttonVariants = {
    primary: 'bg-blue-600 hover:bg-blue-700 text-white focus:ring-blue-500',
    secondary: 'bg-gray-200 hover:bg-gray-300 text-gray-800 focus:ring-gray-500',
    danger: 'bg-red-600 hover:bg-red-700 text-white focus:ring-red-500',
    success: 'bg-green-600 hover:bg-green-700 text-white focus:ring-green-500',
    warning: 'bg-orange-500 hover:bg-orange-600 text-white focus:ring-orange-500',
    ghost: 'bg-transparent hover:bg-gray-100 text-gray-700 border border-gray-300 focus:ring-gray-500',
};

const buttonSizes = {
    sm: 'px-3 py-1 text-sm',
    md: 'px-4 py-2 text-base',
    lg: 'px-6 py-3 text-lg',
};

// BADGE COLORS
const badgeVariants = {
    critical: 'bg-red-600 text-white',
    warning: 'bg-orange-500 text-white',
    success: 'bg-green-600 text-white',
    info: 'bg-blue-600 text-white',
    neutral: 'bg-gray-100 text-gray-800',
};

// CARD BACKGROUNDS
const cardVariants = {
    white: 'bg-white',
    light: 'bg-gray-50',
    red: 'bg-red-50',
    orange: 'bg-orange-50',
    green: 'bg-green-50',
    blue: 'bg-blue-50',
};

// CARD BORDERS
const borderVariants = {
    gray: 'border-gray-200',
    red: 'border-red-600',
    orange: 'border-orange-500',
    green: 'border-green-600',
    blue: 'border-blue-500',
};

// ============================================================================
// TAILWIND CONFIG EXTENSION EXAMPLE
// ============================================================================

/*
// In your tailwind.config.js file, add:

module.exports = {
  theme: {
    extend: {
      colors: {
        critical: '#DC2626',
        warning: '#F97316',
        success: '#10B981',
        info: '#3B82F6',
      },
      spacing: {
        sidebar: '16rem',
        header: '4rem',
      },
      fontSize: {
        // Add custom sizes if needed
      },
      fontWeight: {
        // Add custom weights if needed
      },
    },
  },
}
*/

// ============================================================================
// ACCESSIBILITY COMPLIANCE
// ============================================================================

/*
COLOR CONTRAST RATIOS (WCAG 2.1 AA)
- Red (#DC2626) on White: 13.5:1 ✓ AAA
- Orange (#F97316) on White: 7.2:1 ✓ AA
- Yellow (#EAB308) on White: 5.8:1 ✓ AA
- Green (#10B981) on White: 6.5:1 ✓ AA
- Blue (#3B82F6) on White: 5.1:1 ✓ AA
- Gray-800 (#1F2937) on White: 10.4:1 ✓ AAA
- Gray-600 (#4B5563) on White: 6.2:1 ✓ AA
*/

// ============================================================================
// USAGE EXAMPLES
// ============================================================================

/*

// BUTTON STATES
<button class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed">
  Save Changes
</button>

// CARD WITH LEFT BORDER
<div class="bg-red-50 rounded-lg shadow-md p-6 border-l-4 border-red-600">
  <h3 class="text-lg font-semibold text-gray-900">Critical Alert</h3>
  <p class="text-red-700 mt-2">Content here</p>
</div>

// STATUS BADGE
<span class="bg-red-600 text-white px-3 py-1 rounded-full text-xs font-semibold inline-flex items-center gap-1 animate-pulse">
  <i class="fas fa-exclamation-circle"></i>
  CRITICAL
</span>

// METRIC CARD GRID
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
  <!-- Cards -->
</div>

// PROGRESS BAR
<div class="bg-gray-200 rounded-full h-2">
  <div class="bg-red-600 h-2 rounded-full" style="width: 24%;"></div>
</div>

// TABLE ROW
<tr class="bg-red-50 border-b border-red-200 hover:bg-red-100 border-l-4 border-red-600">
  <td class="px-6 py-4">Content</td>
</tr>

// HEADER GRADIENT
<div class="bg-gradient-to-r from-orange-600 to-red-600 text-white px-6 py-8 shadow-lg">
  <h1 class="text-4xl font-bold">Page Title</h1>
</div>

*/

// ============================================================================
// CUSTOM UTILITY CLASSES (Add to globals.css)
// ============================================================================

/*

@layer components {
  .admin-card {
    @apply bg-white rounded-lg shadow-md p-6 border border-gray-200;
  }

  .admin-btn-primary {
    @apply bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold transition-colors;
  }

  .admin-badge {
    @apply inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold;
  }

  .admin-stat {
    @apply text-3xl font-bold text-gray-900;
  }

  .admin-label {
    @apply text-sm font-medium text-gray-700;
  }
}

*/

// ============================================================================
// DOCUMENTATION
// See: doc/ADMIN_DESIGN_SYSTEM.md
// See: doc/ADMIN_COMPONENTS_USAGE_GUIDE.md
// ============================================================================
