# AI Risk Control Center - Visual Design Guide

## 🎨 Color Palette

### Risk Level Colors

```
CRITICAL RISK (Red)
├─ Background: #fef2f2 (Red 50)
├─ Light Fill: #fee2e2 (Red 100)
├─ Badge: #fca5a5 (Red 300)
├─ Text: #b91c1c (Red 700)
├─ Icon: fa-skull-crossbones
└─ Use: Severe fraud indicators

HIGH RISK (Orange)
├─ Background: #fff7ed (Orange 50)
├─ Light Fill: #ffedd5 (Orange 100)
├─ Badge: #fdba74 (Orange 300)
├─ Text: #c2410c (Orange 700)
├─ Icon: fa-exclamation-triangle
└─ Use: Strong fraud signals

MEDIUM RISK (Amber)
├─ Background: #fffbeb (Amber 50)
├─ Light Fill: #fef3c7 (Amber 100)
├─ Badge: #fcd34d (Amber 300)
├─ Text: #b45309 (Amber 700)
├─ Icon: fa-exclamation-circle
└─ Use: Moderate risk factors

LOW RISK (Green)
├─ Background: #f0fdf4 (Green 50)
├─ Light Fill: #dcfce7 (Green 100)
├─ Badge: #86efac (Green 300)
├─ Text: #15803d (Green 700)
├─ Icon: fa-shield-alt
└─ Use: Low risk activities
```

### UI Element Colors

```
HEADER
├─ Title: #111827 (Gray 900)
├─ Subtitle: #4b5563 (Gray 600)
├─ Icon: Gradient (Red 500 → Pink 600)
└─ Background: White

STATS CARDS
├─ Blue: #eff6ff → #dbeafe (Blue 50-100)
├─ Green: #f0fdf4 → #dcfce7 (Green 50-100)
├─ Purple: #f3e8ff → #e9d5ff (Purple 50-100)
├─ Red: #fef2f2 → #fee2e2 (Red 50-100)
└─ Icons: Matching color 200

CARDS
├─ Background: White
├─ Border: #e5e7eb (Gray 200)
├─ Text: #111827 (Gray 900)
├─ Hover: #f3f4f6 (Gray 100)
└─ Shadow: Subtle gray shadow

BUTTONS
├─ Primary (Configure): Blue
├─ Success (Enable): Green
├─ Warning (Disable): Amber
├─ Import: Blue
├─ Export: Green
└─ Reset: Amber

PROGRESS BARS
├─ Track: #e5e7eb (Gray 200)
├─ Fill: #3b82f6 → #2563eb (Blue gradient)
└─ Animation: Smooth fill
```

---

## 📐 Layout Specifications

### Page Container

```
Width: Full screen
Max-width: 1920px
Padding: 32px (top/bottom) × 48px (left/right)
Background: #f9fafb (Gray 50)
Gap between sections: 32px
```

### Header Section

```
Type: Flexbox row with space-between
Height: 80px
Items:
  - Left: Icon + Title (flex-start)
  - Right: Buttons (flex-end)
Gap: 32px
Background: White (in content area)
Border-bottom: 1px gray-200
```

### Stats Grid

```
Type: CSS Grid
Columns: 4 (1 per stat type)
Gap: 16px
Responsive:
  - Mobile: 1 column
  - Tablet: 2 columns
  - Desktop: 4 columns
```

### Rule Cards

```
Type: Vertical stack
Gap: 16px
Card Interior:
  - Outer padding: 24px
  - Inner flex: space-between
  - Left section: 70%
  - Right section: 30%
  - Bottom section: Settings (optional)
```

### Stat Card

```
Width: 100%
Height: Auto (min 120px)
Padding: 24px
Direction: Flex row with space-between
Items:
  - Left: Text (flex-1)
  - Right: Icon (fixed)
Border: 1px + gradient background
Corners: 12px rounded
```

---

## 🔤 Typography System

### Headings

```
PAGE TITLE
├─ Font-size: 36px (4xl)
├─ Font-weight: 700 (Bold)
├─ Color: #111827 (Gray 900)
├─ Line-height: 1.1
└─ Margin-bottom: 8px

RULE NAME (Card)
├─ Font-size: 18px (lg)
├─ Font-weight: 700 (Bold)
├─ Color: #111827 (Gray 900)
└─ Line-height: 1.25

STAT LABEL
├─ Font-size: 14px (sm)
├─ Font-weight: 600 (Semibold)
├─ Color: Inherited (status color)
├─ Text-transform: Uppercase
├─ Letter-spacing: 0.05em
└─ Line-height: 1

STAT VALUE
├─ Font-size: 30px (3xl)
├─ Font-weight: 700 (Bold)
├─ Color: #111827 (Gray 900)
└─ Line-height: 1
```

### Body Text

```
DESCRIPTION
├─ Font-size: 14px (sm)
├─ Font-weight: 400 (Regular)
├─ Color: #4b5563 (Gray 600)
└─ Line-height: 1.5

LABEL
├─ Font-size: 12px (xs)
├─ Font-weight: 600 (Semibold)
├─ Color: #6b7280 (Gray 600)
├─ Text-transform: Uppercase
└─ Letter-spacing: 0.05em

METADATA
├─ Font-size: 12px (xs)
├─ Font-weight: 400 (Regular)
├─ Color: #9ca3af (Gray 400)
└─ Line-height: 1
```

### Button Text

```
BUTTON LABEL
├─ Font-size: 14px (sm)
├─ Font-weight: 600 (Semibold)
├─ Color: Inherited (button color)
├─ Line-height: 1.5
└─ Text-transform: Capitalize
```

---

## 🎯 Component Specifications

### Stat Card Component

```
┌─────────────────────────────────┐
│ 📋 Total Rules          [Icon]   │
│ 🔵 TOTAL RULES          🔵 📋   │
│ 24                               │
└─────────────────────────────────┘

Props:
- title: Label text (uppercase)
- value: Number to display
- icon: Font Awesome class
- color: Color variant (blue|green|purple|red)

Styling:
- Gradient background (color 50→100)
- Border: 1px (color 200)
- Icon background: color 200
```

### Rule Card Component

```
┌──────────────────────────────────────────┐
│ [Icon] Rule Name                [Badge]  │
│                                [Button1] │
│ Description text here...         [Button2]│
│                                          │
│ ⚖️ Risk Weight: [████████░░░] 75%       │
│ 🟢 Status: Active                       │
│                                          │
│ Settings:                                │
│ Key1: Value1  │  Key2: Value2           │
└──────────────────────────────────────────┘

Props:
- rule: RiskRule object
- color: Risk level color
- level: Risk level label

Styling:
- Hover: shadow-lg + border-gray-300
- Border: 1px gray-200
- Corners: 12px rounded-xl
- Padding: 24px
```

### Toggle Button

```
┌─────────────────────┐
│ [Icon] Disable      │ (Active state)
└─────────────────────┘
Background: #fef3c7 (Amber 100)
Border: 1px #fde68a (Amber 200)
Text: #b45309 (Amber 700)

┌─────────────────────┐
│ [Icon] Enable       │ (Inactive state)
└─────────────────────┘
Background: #f0fdf4 (Green 100)
Border: 1px #bbf7d0 (Green 200)
Text: #15803d (Green 700)
```

### Progress Bar

```
Label: Risk Weight
Full bar: 100% width, h-2, gray-200
Fill: 75%, blue gradient, rounded
Number: "75%", mono font, bold

╔═══════════════════════════════╗
║ Risk Weight: [████████░░░] 75% │
╚═══════════════════════════════╝
```

---

## 🎭 Interactive States

### Button States

```
NORMAL
├─ Background: Solid (color based)
├─ Border: 1px (color)
├─ Text: Matching color
└─ Cursor: pointer

HOVER
├─ Background: Lighter shade (+100)
├─ Border: Darker shade (-100)
├─ Text: Same
└─ Transition: 200ms ease

ACTIVE
├─ Background: Even lighter
├─ Border: Even darker
├─ Text: Same
└─ Duration: Instant

DISABLED
├─ Background: Gray 100
├─ Border: Gray 200
├─ Text: Gray 400
├─ Cursor: not-allowed
└─ Opacity: 50%
```

### Card States

```
NORMAL
├─ Shadow: sm
├─ Border: 1px gray-200
└─ Transition: ready

HOVER
├─ Shadow: lg
├─ Border: 1px gray-300
├─ Transition: 300ms ease
└─ Duration: smooth

ACTIVE
├─ Shadow: xl
├─ Border: 1px color
└─ Background: subtle highlight
```

---

## 📱 Responsive Design

### Mobile (< 768px)

```
Width: Full (no padding loss)
Header:
  - Vertical stack
  - Center aligned
  - Buttons wrap

Stats:
  - 1 column
  - Full width
  - 16px gap

Card:
  - Full width
  - Left/right: Single column
  - Buttons: Stack vertical
  - Font sizes: Slightly reduced

Touch targets:
  - Min 44×44px
  - Generous padding
```

### Tablet (768-1024px)

```
Header:
  - Horizontal with wrapping
  - Space between groups

Stats:
  - 2 columns
  - Even distribution

Card:
  - Full width
  - Two-column layout works
  - Buttons: Inline

Typography:
  - Slightly larger than mobile
```

### Desktop (> 1024px)

```
Header:
  - Full horizontal
  - Max spacing

Stats:
  - 4 columns
  - Perfect grid

Card:
  - Full width
  - Optimal spacing
  - All features visible

Typography:
  - Full size
  - Professional spacing
```

---

## 🎨 Icon Usage

### Risk Level Icons

| Level    | Icon                    | Unicode                                       | Size |
| -------- | ----------------------- | --------------------------------------------- | ---- |
| Critical | fa-skull-crossbones     | `<i class="fas fa-skull-crossbones"></i>`     | lg   |
| High     | fa-exclamation-triangle | `<i class="fas fa-exclamation-triangle"></i>` | lg   |
| Medium   | fa-exclamation-circle   | `<i class="fas fa-exclamation-circle"></i>`   | lg   |
| Low      | fa-shield-alt           | `<i class="fas fa-shield-alt"></i>`           | lg   |

### Action Icons

| Action           | Icon         | Size |
| ---------------- | ------------ | ---- |
| Configure        | fa-sliders-h | xs   |
| Enable           | fa-check     | xs   |
| Disable          | fa-power-off | xs   |
| Import           | fa-upload    | md   |
| Export           | fa-download  | md   |
| Reset            | fa-redo      | md   |
| Status: Active   | fa-circle    | xs   |
| Status: Inactive | fa-circle    | xs   |

---

## 🌐 Accessibility Features

### Color Contrast

```
Text on Color BG:
├─ Normal: 4.5:1 minimum
├─ Large: 3:1 minimum
└─ All exceed WCAG AA

Text on White:
├─ Dark gray: 7.3:1 ✅
├─ Medium gray: 5.1:1 ✅
└─ Light gray: 3.8:1 ✅
```

### Focus States

```
All interactive elements have:
├─ Visible focus outline
├─ 2px outline (color-based)
├─ 4px offset
└─ Accessible keyboard nav
```

### Touch Targets

```
Minimum sizes:
├─ Buttons: 44×44px
├─ Links: 44×44px
├─ Icons: 24×24px minimum
└─ Spacing: 8px between
```

---

## 📐 Spacing System

### Base Unit: 4px

```
Padding/Margin Scale:
├─ 2px: 0.5
├─ 4px: 1
├─ 8px: 2
├─ 12px: 3
├─ 16px: 4
├─ 20px: 5
├─ 24px: 6
├─ 28px: 7
├─ 32px: 8
├─ 40px: 10
└─ 48px: 12

Applied:
├─ Page padding: 8/12
├─ Section gaps: 8
├─ Card padding: 6
├─ Button padding: 2/3
└─ Component gaps: 2/4
```

---

## ⚡ Animation & Transitions

### Hover Transitions

```
Cards:
├─ Property: all
├─ Duration: 300ms
├─ Timing: ease
└─ Changes: shadow, border

Buttons:
├─ Property: background, border, shadow
├─ Duration: 200ms
├─ Timing: ease
└─ Changes: color shade

Progress Bar:
├─ Property: width
├─ Duration: 600ms
├─ Timing: ease-out
└─ Effect: animated fill
```

### Load Animations

```
Cards: Fade in on load
  ├─ Animation: opacity
  ├─ Duration: 300ms
  ├─ Stagger: 50ms delay per card
  └─ Starting opacity: 0.8

Stats: Slide in from top
  ├─ Animation: translateY
  ├─ Duration: 400ms
  ├─ Timing: cubic-bezier(0.4, 0, 0.2, 1)
  └─ Starting Y: -20px
```

---

## 📐 Border Radius System

```
Small Elements:
├─ Badge: 9999px (full round)
├─ Status pill: 9999px (full round)
├─ Icon badge: 8px
└─ Buttons: 8px

Medium Elements:
├─ Stat cards: 12px
├─ Rule cards: 12px
└─ Settings box: 8px

Large Elements:
├─ Header gradient: 16px
├─ Modal: 12px
└─ Alert: 8px
```

---

## 💾 File Locations

### CSS/Styling

- Tailwind utilities: Built-in
- Custom colors: Inline `<style>` block
- Location: Bottom of index.blade.php
- Size: ~200 lines for color definitions

### Icons

- Source: Font Awesome 6+
- CDN: Bootstrap CDN via admin layout
- Fallback: System fonts
- Size range: xs (12px) to 3xl (48px)

### Responsive

- Base: Mobile first
- Breakpoints: sm(640) md(768) lg(1024) xl(1280) 2xl(1536)
- Utilities: Tailwind responsive prefixes
- Media queries: Built into Tailwind

---

**Design Version**: 1.0.0
**Last Updated**: January 29, 2026
**Status**: Production Approved ✅
