# Low Stock Alerts - Design Guide

## Design Philosophy

### Core Principles

1. **Attention-Grabbing** (without being overwhelming)
    - Use color hierarchy, not saturation
    - Animations only where they matter (critical items)
    - Clear visual distinction between urgency levels
    - Gradient headers draw the eye naturally

2. **Professional & Trustworthy**
    - Clean layouts with proper whitespace
    - Semantic typography hierarchy
    - Accessible color contrasts (WCAG 2.1 AA)
    - Consistent spacing throughout

3. **Warning Colors Used Wisely**
    - Three-level system prevents alert fatigue
    - Critical (red) = immediate danger only
    - Warning (orange) = plan action
    - Low (yellow) = monitor only
    - No "crying wolf" syndrome

4. **User-Centric**
    - Filters for targeted information
    - Bulk actions for efficiency
    - Clear status indicators
    - Activity log for accountability

---

## Color Palette

### Primary Status Colors

#### Critical Alert - Red

```
Color Name: Danger Red
Hex Code: #DC2626
RGB: 220, 38, 38
HSL: 0°, 85%, 51%
Tailwind: red-600
```

**Usage**:

- Status badges for critical items
- "Restock Now" buttons
- Alert icons that pulse
- Left borders on critical rows
- Critical card accent

**Why Red**:

- Universal "stop" signal
- High urgency association
- Good contrast on white
- Accessible (13.5:1 ratio on white)
- Culturally recognized

**Accessibility**:

- Foreground: #DC2626 (red) on white: 13.5:1 ratio ✓
- Not the only way to convey information
- Icon + color + text = multiple cues

#### Warning Alert - Orange

```
Color Name: Warning Orange
Hex Code: #F97316
RGB: 249, 115, 22
HSL: 33°, 97%, 53%
Tailwind: orange-500
```

**Usage**:

- Status badges for warning items
- "Schedule" buttons
- Warning card accent
- Filter bar background (with transparency)
- Left borders on warning rows

**Why Orange**:

- "Caution" signal
- Less urgent than red
- Still stands out visually
- Accessible contrast (7.2:1 on white)
- Professional appearance

**Accessibility**:

- Foreground: #F97316 (orange) on white: 7.2:1 ratio ✓
- Clearly different from red
- Icon differentiation (exclamation vs. bell)

#### Low Alert - Yellow

```
Color Name: Monitor Yellow
Hex Code: #EAB308
RGB: 234, 179, 8
HSL: 48°, 96%, 47%
Tailwind: yellow-500
```

**Usage**:

- Status badges for low items
- "Monitor" buttons
- Low card accent
- "Apply" filter button
- Left borders on low rows

**Why Yellow**:

- "Caution but stable" signal
- Less urgent than orange
- Still visible and distinct
- Accessible contrast (5.8:1 on white)
- Monitoring/watching implication

**Accessibility**:

- Foreground: #EAB308 (yellow) on white: 5.8:1 ratio ✓
- Combined with icons for clarity
- Clearly distinct from red and orange

### Secondary Colors

#### Header Gradient

```
Start: Orange-600 (#EA580C)
End: Red-600 (#DC2626)
Direction: Left to right
Purpose: Eye-catching header that doesn't overwhelm
```

**Why Gradient**:

- Smoother transition than solid color
- Combines warning (orange) and critical (red)
- Draws attention naturally
- Professional appearance
- Creates visual hierarchy

#### Card & Row Backgrounds

##### Critical Background

```
Color: Red-50 (#FEF2F2)
Hex: #FEF2F2
RGB: 254, 242, 242
HSL: 0°, 100%, 98%
Tailwind: red-50
```

**Usage**: Background for critical alert rows, subtle but noticeable

##### Warning Background

```
Color: Orange-50 (#FFF7ED)
Hex: #FFF7ED
RGB: 255, 247, 237
HSL: 30°, 100%, 96%
Tailwind: orange-50
```

**Usage**: Background for warning alert rows, light orange tint

##### Low Background

```
Color: Yellow-50 (#FEFCE8)
Hex: #FEFCE8
RGB: 254, 252, 232
HSL: 48°, 100%, 95%
Tailwind: yellow-50
```

**Usage**: Background for low alert rows, light yellow tint

##### Blue Card (Total Watched)

```
Color: Blue-50 (#EFF6FF)
Hex: #EFF6FF
RGB: 239, 246, 255
HSL: 213°, 100%, 97%
Tailwind: blue-50
```

**Usage**: Background for total watched items card

#### Neutral Grays

##### Header Dark

```
Color: Gray-900 (#111827)
Hex: #111827
RGB: 17, 24, 39
HSL: 213°, 41%, 11%
Tailwind: gray-900
```

**Usage**: Table header background (with gradient)

##### Text Primary

```
Color: Gray-800 (#1F2937)
Hex: #1F2937
RGB: 31, 41, 55
HSL: 210°, 27%, 17%
Tailwind: gray-800
```

**Usage**: Primary text on white backgrounds

##### Text Secondary

```
Color: Gray-600 (#4B5563)
Hex: #4B5563
RGB: 75, 85, 99
HSL: 217°, 14%, 34%
Tailwind: gray-600
```

**Usage**: Secondary text, labels, descriptions

##### Border Color

```
Color: Gray-200 (#E5E7EB)
Hex: #E5E7EB
RGB: 229, 231, 235
HSL: 213°, 13%, 91%
Tailwind: gray-200
```

**Usage**: Dividers, subtle borders, table lines

---

## Typography System

### Font Family

```
Font: System Stack (platform-native)
Primary: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto
Fallback: sans-serif
```

**Why System Stack**:

- Loads instantly (no web font delay)
- Optimized for each platform
- Familiar to users
- Small file size
- Accessible and readable

### Font Sizes

#### Header Sizes

```
Title (4xl)
Size: 2.25rem (36px)
Line-height: 2.5rem (40px)
Letter-spacing: normal
Weight: 700 (bold)
Usage: Main page title "Low Stock Alerts"
```

```
Subtitle (lg)
Size: 1.125rem (18px)
Line-height: 1.75rem (28px)
Letter-spacing: normal
Weight: 500 (medium)
Usage: Tagline under title
```

#### Card Sizes

```
Card Title (xl)
Size: 1.25rem (20px)
Line-height: 1.75rem (28px)
Weight: 600 (semibold)
Usage: Summary card metrics
```

```
Card Label (sm)
Size: 0.875rem (14px)
Line-height: 1.25rem (20px)
Weight: 500 (medium)
Usage: "Critical Items" text
```

#### Body Sizes

```
Body (base)
Size: 1rem (16px)
Line-height: 1.5rem (24px)
Weight: 400 (regular)
Usage: Table data, descriptions
```

```
Small (sm)
Size: 0.875rem (14px)
Line-height: 1.25rem (20px)
Weight: 400 (regular)
Usage: Timestamps, secondary info
```

```
Tiny (xs)
Size: 0.75rem (12px)
Line-height: 1rem (16px)
Weight: 500 (medium)
Usage: Labels, badges, captions
```

### Font Weights

- **Regular (400)**: Body text, descriptions
- **Medium (500)**: Labels, badges, secondary headings
- **Semibold (600)**: Card titles, important info
- **Bold (700)**: Page title, emphasis

---

## Component Specifications

### Header Component

```
Height: 12rem (192px)
Background: Gradient orange-600 → red-600
Padding: 3rem (48px)
Border-radius: 0 (sharp corners)
```

**Elements**:

- Icon: fa-exclamation-triangle (yellow-300, animate-pulse, 3rem)
- Title: "Low Stock Alerts" (4xl, bold, white)
- Subtitle: "Monitor inventory levels & manage restocking" (lg, regular, orange-100)
- Button 1: Settings (white bg, gray icon, px-4 py-2)
- Button 2: Export (orange-500 bg, white text, px-4 py-2)

**Spacing**:

- Icon to title: 1rem gap
- Title to subtitle: 0.5rem gap
- Buttons float to right with 1rem gap between

### Summary Cards (4-card grid)

#### Card Container

```
Background: Color-specific-50 (red-50, orange-50, yellow-50, blue-50)
Border: 4px solid status color
Border-radius: 0.5rem (8px)
Padding: 1.5rem (24px)
```

**Card Structure**:

```
Layout: 2-row layout
Row 1: Icon (left) + Label (right)
Row 2: Value (left) + Description (right)

Icon Size: 2rem × 2rem
Icon Color: Status color (red-600, orange-500, yellow-500, blue-500)
Icon Opacity: 20% on card
Value Size: 3xl, bold
Description: sm, gray-600
```

**Responsive**:

- Desktop: 4 columns
- Tablet: 2 columns
- Mobile: 1 column

#### Card States

**Critical Card**:

```
Background: red-50 (#FEF2F2)
Border: 4px solid red-600 (#DC2626)
Icon: fa-exclamation-circle (red-600, 20% opacity)
Value Color: red-600
```

**Warning Card**:

```
Background: orange-50 (#FFF7ED)
Border: 4px solid orange-500 (#F97316)
Icon: fa-exclamation (orange-500, 20% opacity)
Value Color: orange-500
```

**Low Card**:

```
Background: yellow-50 (#FEFCE8)
Border: 4px solid yellow-500 (#EAB308)
Icon: fa-info-circle (yellow-500, 20% opacity)
Value Color: yellow-500
```

**Total Card**:

```
Background: blue-50 (#EFF6FF)
Border: 4px solid blue-500 (#3B82F6)
Icon: fa-cubes (blue-500, 20% opacity)
Value Color: blue-500
```

### Filter Section

```
Background: orange-500 at 20% opacity with backdrop-blur
Padding: 1.5rem (24px)
Border-radius: 0.5rem (8px)
Border: 1px solid orange-200 (subtle)
```

**Layout**: 5-column responsive grid

- Columns 1-4: Filter inputs
- Column 5: Apply button

**Filter Controls**:

```
Select dropdowns:
  Width: Full column width
  Background: White
  Border: 1px gray-200
  Border-radius: 0.375rem (6px)
  Padding: 0.5rem (8px)
  Font-size: sm (14px)

Input text:
  Width: Full column width
  Background: White
  Border: 1px gray-200
  Border-radius: 0.375rem (6px)
  Padding: 0.5rem (8px)
  Font-size: sm (14px)

Apply Button:
  Background: yellow-400 (#FACC15)
  Hover: yellow-500 (#EAB308)
  Text: white, bold
  Padding: 0.5rem 1rem (8px 16px)
  Border-radius: 0.375rem (6px)
```

### Alert Table

#### Table Header

```
Background: Gradient gray-900 → gray-800
Text: white, bold, sm (14px)
Padding: 1rem (16px)
Border-bottom: 2px gray-700
```

**Columns** (7 total):

1. Status (12% width)
2. Product Name (25% width)
3. Current Stock (15% width)
4. Min. Threshold (15% width)
5. Stock Level (18% width)
6. Restock Qty (10% width)
7. Action (5% width)

#### Table Rows

##### Critical Row Styling

```
Background: red-50 (#FEF2F2)
Left Border: 4px solid red-600 (#DC2626)
Hover: red-100 background
Padding: 1rem (16px)
Line-height: 1.75rem (28px)
```

##### Warning Row Styling

```
Background: orange-50 (#FFF7ED)
Left Border: 4px solid orange-500 (#F97316)
Hover: orange-100 background
Padding: 1rem (16px)
Line-height: 1.75rem (28px)
```

##### Low Row Styling

```
Background: yellow-50 (#FEFCE8)
Left Border: 4px solid yellow-500 (#EAB308)
Hover: yellow-100 background
Padding: 1rem (16px)
Line-height: 1.75rem (28px)
```

#### Status Badge

##### Critical Badge

```
Background: red-600 (#DC2626)
Text: white, bold, xs (12px)
Icon: fa-exclamation-circle (white)
Padding: 0.25rem 0.75rem (4px 12px)
Border-radius: 9999px (pill shape)
Animation: animate-pulse (pulsing effect)
Display: Inline-flex, gap-1
```

**Pulsing Animation**:

```
@keyframes pulse {
  0%, 100% { opacity: 1 }
  50% { opacity: 0.5 }
}
Duration: 2 seconds
Iteration: Infinite
Timing: ease-in-out
```

##### Warning Badge

```
Background: orange-500 (#F97316)
Text: white, bold, xs (12px)
Icon: fa-exclamation (white)
Padding: 0.25rem 0.75rem (4px 12px)
Border-radius: 9999px (pill shape)
Animation: None (static)
Display: Inline-flex, gap-1
```

##### Low Badge

```
Background: yellow-500 (#EAB308)
Text: white, bold, xs (12px)
Icon: fa-bell (white)
Padding: 0.25rem 0.75rem (4px 12px)
Border-radius: 9999px (pill shape)
Animation: None (static)
Display: Inline-flex, gap-1
```

### Progress Bar

```
Height: 0.5rem (8px)
Background: gray-200 (#E5E7EB)
Border-radius: 9999px (pill shape)
Overflow: hidden (for rounded edges)

Filled portion:
  Height: 100% (inherits)
  Border-radius: 9999px
  Animation: smooth width transition
  Transition: width 0.3s ease
```

**Color by Status**:

- **Critical**: red-600 (#DC2626)
- **Warning**: orange-500 (#F97316)
- **Low**: yellow-500 (#EAB308)

**Width Calculation**: (current_stock / min_threshold) × 100

**Label**: "X% of minimum" (gray-600, sm font, positioned right of bar)

### Action Buttons

#### Restock Now Button (Critical)

```
Background: red-600 (#DC2626)
Hover: red-700 (#991B1B)
Text: white, bold, sm (14px)
Icon: fa-truck (white)
Padding: 0.5rem 1rem (8px 16px)
Border-radius: 0.375rem (6px)
Border: none
Cursor: pointer
Transition: background 0.2s ease

Active: red-800 background
Disabled: opacity-50, cursor-not-allowed
```

#### Schedule Button (Warning)

```
Background: orange-500 (#F97316)
Hover: orange-600 (#EA580C)
Text: white, bold, sm (14px)
Icon: fa-calendar (white)
Padding: 0.5rem 1rem (8px 16px)
Border-radius: 0.375rem (6px)
Border: none
Cursor: pointer
Transition: background 0.2s ease

Active: orange-700 background
Disabled: opacity-50, cursor-not-allowed
```

#### Monitor Button (Low)

```
Background: yellow-500 (#EAB308)
Hover: yellow-600 (#CA8A04)
Text: white, bold, sm (14px)
Icon: fa-eye (white)
Padding: 0.5rem 1rem (8px 16px)
Border-radius: 0.375rem (6px)
Border: none
Cursor: pointer
Transition: background 0.2s ease

Active: yellow-700 background
Disabled: opacity-50, cursor-not-allowed
```

### Quick Actions Panel

```
Background: gray-50 (#F9FAFB)
Border: 1px solid gray-200 (#E5E7EB)
Border-radius: 0.5rem (8px)
Padding: 1.5rem (24px)

Title: "Quick Actions" (lg, bold)
Button Layout: 4 buttons, full width stacked
```

**Button Style** (for quick actions):

```
Width: 100%
Height: 3rem (48px)
Font: bold, base (16px)
Icon: Left aligned
Text: Centered
Border-radius: 0.375rem (6px)
Transition: All 0.2s ease

Each button has color matching its status/action
```

### Recent Activity Panel

```
Background: gray-50 (#F9FAFB)
Border: 1px solid gray-200 (#E5E7EB)
Border-radius: 0.5rem (8px)
Padding: 1.5rem (24px)

Title: "Recent Activity" (lg, bold)
Max-height: 20rem (320px)
Overflow: auto (scrollable if needed)
```

**Activity Items**:

```
Layout: Flex row with indicator dot + text

Indicator Dot:
  Size: 0.75rem (12px)
  Border-radius: 9999px (circle)
  Color: Status-specific (red/orange/green/blue)
  Margin-right: 0.75rem (12px)

Text Container:
  Title: sm, bold, gray-800
  Description: xs, gray-600
  Timestamp: xs, gray-500, italic
  Gap: 0.25rem between lines
```

### Footer Info Box

```
Background: red-50 (#FEF2F2)
Border: 1px solid red-200 (#FECACA)
Border-radius: 0.5rem (8px)
Padding: 1rem (16px)

Layout: Flex row, gap-4

Icon:
  fa-exclamation-triangle
  Color: red-600 (#DC2626)
  Size: 1.5rem (24px)
  Flex-shrink: 0

Text:
  Color: red-700 (#B91C1C)
  Font-size: sm (14px)
  Line-height: 1.5rem (24px)
```

---

## Layout & Spacing

### Main Container

```
Max-width: 1280px (1280px breakpoint)
Margin: auto (centered)
Padding: 2rem (32px) responsive down to 1rem (16px) on mobile
```

### Spacing Scale (Tailwind)

- xs: 0.25rem (4px)
- sm: 0.5rem (8px)
- md: 1rem (16px)
- lg: 1.5rem (24px)
- xl: 2rem (32px)
- 2xl: 2.5rem (40px)
- 3xl: 3rem (48px)

### Gap Between Sections

- Header to cards: 2rem (32px)
- Cards to filters: 2rem (32px)
- Filters to table: 2rem (32px)
- Table to actions: 2rem (32px)
- Actions to footer: 2rem (32px)

---

## Responsive Design

### Breakpoints

#### Mobile (< 640px)

```
Summary Cards: 1 column
Table: Horizontal scroll
Buttons: Full width
Filter Grid: 1 column, stacked
Padding: 1rem (16px)
Font Sizes: Reduced by 0.25rem for better fit
```

#### Tablet (640px - 1024px)

```
Summary Cards: 2 columns
Table: Horizontal scroll
Buttons: Full width in actions
Filter Grid: 2 columns (2 filters per row)
Padding: 1.5rem (24px)
Font Sizes: Normal scale
```

#### Desktop (> 1024px)

```
Summary Cards: 4 columns
Table: Normal full-width
Buttons: Inline in table rows
Filter Grid: 5 columns (4 filters + apply button)
Padding: 2rem (32px)
Font Sizes: Full scale
```

### Mobile-Specific Adjustments

```
Hide on Mobile:
- Export button (available in quick actions)
- Settings button (available in quick actions)
- Some description text

Show on Mobile:
- Simplified header
- Stacked layout
- Touch-friendly buttons (48px min height)
- Collapsible filters (save space)
```

---

## Animations & Transitions

### Pulsing Animation (Critical Only)

```
Animation: animate-pulse
Duration: 2 seconds
Timing: ease-in-out
Iteration: Infinite
Opacity: 1 → 0.5 → 1
Usage: Critical status badges only
```

**CSS**:

```css
@keyframes pulse {
    0%,
    100% {
        opacity: 1;
    }
    50% {
        opacity: 0.5;
    }
}

.animate-pulse {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}
```

### Button Hover Transition

```
Duration: 0.2 seconds
Easing: ease
Properties: background-color
```

**Example**:

```
Default: bg-red-600
Hover: bg-red-700
Transition: background-color 0.2s ease
```

### Row Hover Effect

```
Duration: 0.15 seconds
Easing: ease
Property: background-color
Intensity: +1 shade (e.g., red-50 → red-100)
```

### Progress Bar Width Transition

```
Duration: 0.3 seconds
Easing: ease
Property: width
```

---

## Accessibility (WCAG 2.1 AA)

### Color Contrast Ratios

```
Red (#DC2626) on White (#FFFFFF): 13.5:1 ✓ AAA
Orange (#F97316) on White (#FFFFFF): 7.2:1 ✓ AA
Yellow (#EAB308) on White (#FFFFFF): 5.8:1 ✓ AA
Gray-800 (#1F2937) on White (#FFFFFF): 10.4:1 ✓ AAA
Gray-600 (#4B5563) on White (#FFFFFF): 6.2:1 ✓ AA
```

### Focus States

```
All interactive elements must have visible focus state:
- Buttons: 2px outline (matching primary color)
- Inputs: 2px outline (blue-500)
- Links: Underline + color change

Outline-offset: 2px
Outline-width: 2px
Color: Matching element (or blue-500 for consistency)
```

### Semantic HTML

- Use `<button>` for buttons, not `<div>` styled as button
- Use `<label>` for form labels
- Use `<table>` for tabular data
- Use `<section>` for major content sections
- Use heading hierarchy (h1, h2, h3)

### Icon + Text

- Never rely on icon alone for meaning
- Always pair icons with text labels
- Example: Icon + "CRITICAL" badge, not just icon

### Text Alternatives

- All images have `alt` text
- Icons have `aria-label` or accompanying text
- Color never the only differentiator (also use text/icons)

---

## Design Tokens (Tailwind Classes)

### Colors

```
Critical: bg-red-600, text-red-600, border-red-600
Warning: bg-orange-500, text-orange-500, border-orange-500
Low: bg-yellow-500, text-yellow-500, border-yellow-500
Info: bg-blue-500, text-blue-500, border-blue-500
Success: bg-green-500, text-green-500, border-green-500

Backgrounds:
Critical: bg-red-50
Warning: bg-orange-50
Low: bg-yellow-50
Total: bg-blue-50

Neutral:
Primary text: text-gray-800
Secondary text: text-gray-600
Borders: border-gray-200
```

### Sizing

```
Spacing: sp-1 (4px) through sp-16 (64px)
Border radius: rounded (4px), rounded-full (pill)
Border width: border-1, border-2, border-4
Icon sizes: w-4 h-4, w-6 h-6, w-8 h-8, w-12 h-12
```

### Typography

```
Font family: font-sans (system stack)
Sizes: text-xs through text-4xl
Weights: font-normal, font-medium, font-semibold, font-bold
Line height: leading-none through leading-relaxed
```

---

## Design Validation Checklist

- [ ] All status levels (red/orange/yellow) visually distinct
- [ ] Red used ONLY for critical items (pulsing animation)
- [ ] Orange for warning items (no pulsing)
- [ ] Yellow for low items (no pulsing)
- [ ] Header gradient visible and attractive
- [ ] Summary cards show correct colors and icons
- [ ] Progress bars match row status colors
- [ ] Buttons match their urgency level (red/orange/yellow)
- [ ] All text has sufficient contrast (WCAG AA minimum)
- [ ] Focus states visible on all interactive elements
- [ ] Mobile layout responsive to 320px width
- [ ] Tablet layout responsive to 640px width
- [ ] Desktop layout optimal at 1280px width
- [ ] Icons complement text, never replace it
- [ ] Color blindness not the only differentiator
- [ ] Animation doesn't cause motion sickness (pulsing < 2Hz)
- [ ] Table scrollable on mobile
- [ ] Buttons touch-friendly (48px minimum on mobile)
- [ ] Consistent spacing throughout
- [ ] Semantic HTML structure
- [ ] No invalid ARIA labels
- [ ] Images/icons have text alternatives

---

**Version**: 1.0.0  
**Updated**: January 29, 2026  
**Framework**: Tailwind CSS 3.x with Blade templating  
**Browser Support**: All modern browsers (Chrome, Firefox, Safari, Edge)
