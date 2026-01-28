# Revenue Analytics - Design Guide

## 🎨 Design Philosophy

### Core Principles

**1. Data Visualization First**

- Charts are primary UI elements
- Clean, uncluttered design
- Focus on metrics and trends
- Minimize decoration

**2. Business Intelligence**

- Professional, serious aesthetic
- Trust-building design
- Clear hierarchy
- Easy interpretation

**3. Clarity**

- Large, readable text
- Color-coded insights
- Clear data labels
- No ambiguity

**4. Accessibility**

- High contrast ratios
- Clear typography
- Keyboard navigation
- Mobile responsive

---

## 🎨 Color Palette

### Primary Colors

```
Slate-900   #0f172a  Headers, titles (dark serious tone)
Slate-800   #1e293b  Secondary dark elements
Slate-700   #374151  Tertiary text
Gray-50     #f9fafb  Page background
White       #ffffff  Card backgrounds
```

### Data Visualization

```
Blue        #2563eb  Primary charts, main data
Purple      #a855f7  Secondary data
Green       #16a34a  Growth, positive
Red         #dc2626  Decline, negative
Orange      #ea580c  Accent, tertiary
```

### UI Elements

```
Blue-100    #dbeafe  Light backgrounds
Blue-400    #60a5fa  Chart gradients (light)
Green-100   #dcfce7  Positive badge background
Green-700   #15803d  Positive badge text
Red-100     #fee2e2  Negative badge background
Red-700     #b91c1c  Negative badge text
Gray-100    #f3f4f6  Secondary backgrounds
Gray-200    #e5e7eb  Borders
```

---

## 📐 Layout System

### Grid Layout

**Summary Cards** (Top Row)

```
Desktop (1024+):  4 columns
Tablet (768-1023): 2 columns
Mobile (<768):    1 column
Gap:              1.5rem (24px)
```

**Charts Section** (Middle Row)

```
Desktop (1024+):  3 columns (2+1, 1+1)
Tablet (768-1023): 2 columns
Mobile (<768):    1 column
Gap:              1.5rem (24px)
```

### Spacing System

```
xs      4px      Compact padding
sm      8px      Small spacing
md      16px     Standard padding
lg      24px     Section spacing
xl      32px     Major gaps
```

---

## 🔤 Typography

### Font Stack

```
Primary: system-ui, -apple-system, sans-serif
Numbers: Monospace (optional for financial data)
```

### Type Scales

**Headers**

```
Page Title:    text-4xl (36px), bold, white
Card Title:    text-lg (18px), bold, gray-900
Label:         text-sm (14px), uppercase, bold, gray-600
```

**Body**

```
Metric Value:  text-3xl (30px), bold, gray-900
Secondary:     text-sm (14px), normal, gray-500
Description:   text-xs (12px), normal, gray-500
```

### Line Heights

```
Tight:    1.25  (Headers, labels)
Normal:   1.5   (Body text)
Relaxed:  1.625 (Descriptions)
```

---

## 🏗️ Component Styles

### Header Section

```
Background:  gradient-to-r from-slate-900 to-slate-800
Text Color:  White (#ffffff)
Padding:     py-8 (32px top/bottom)
Spacing:     px-6 (24px left/right)
Shadow:      shadow-lg
Elements:    Icon + Title + Subtitle + Button
```

**Icon Style**:

- Size: 36px (3xl)
- Color: Blue-400 (#60a5fa)
- Icon: fa-chart-line

**Button Style**:

- Background: Blue-600 (#2563eb)
- Hover: Blue-700 (#1d4ed8)
- Text: White, semibold
- Padding: px-4 py-2

### Date Range Filter

**Container**:

```
Background: Slate-800 with opacity (50%)
Backdrop:   Blur effect
Border:     None (blends with header)
Padding:    p-4 (16px)
```

**Input Fields**:

```
Background: Slate-700 (#374151)
Border:     1px solid slate-600
Text:       White
Focus:      border-blue-500 + ring-blue-500
Padding:    px-3 py-2
```

**Buttons**:

```
Apply:   Blue-600 bg, white text, semibold
Reset:   Slate-700 bg, white text, semibold
Width:   flex-1 (equal width)
Gap:     8px spacing
```

### Summary Cards

**Card Structure**:

```
Background: White (#ffffff)
Border:     1px solid gray-200
Shadow:     shadow-sm (light)
Hover:      shadow-md (enhanced on hover)
Padding:    p-6 (24px)
Border-radius: rounded-lg (8px)
Transition: smooth (all properties)
```

**Card Content**:

```
Icon Area:
  - Size: 24px text-2xl
  - Color: Blue (#2563eb) with 20% opacity
  - Position: Top-right

Title:
  - Size: text-sm uppercase bold
  - Color: Gray-600
  - Margin-bottom: mb-4

Value:
  - Size: text-3xl bold
  - Color: Gray-900
  - Margin-bottom: mb-2

Support Text:
  - Size: text-xs
  - Color: Gray-500
  - Margin: mt-1

Trend Indicator:
  - Flex row, gap-1
  - Icon: arrow (up/down)
  - Text: Font semibold, small
  - Color: Green-600 (up) or Red-600 (down)
```

### Charts

**Container**:

```
Background: White (#ffffff)
Border:     1px solid gray-200
Shadow:     shadow-sm
Padding:    p-6 (24px)
Border-radius: rounded-lg (8px)
```

**Chart Area**:

```
Height:     h-80 (320px)
Background: Gradient from blue-50 to gray-50
Border:     1px solid gray-100
Padding:    p-4 (16px)
Border-radius: rounded-lg (8px)
Flex:       Center content
```

**Chart Options**:

```
Button Group:
  - Size: text-xs
  - Active: bg-blue-100, text-blue-700
  - Inactive: bg-gray-100, text-gray-700
  - Padding: px-3 py-1
  - Border-radius: rounded
  - Gap: 8px
```

### Legend

```
Display:   Flex row, centered
Gap:       24px spacing
Items:     Flex with gap-2

Indicator:
  - Size: w-3 h-3
  - Shape: rounded-full (circle)
  - Current: Blue-600
  - Comparison: Gray-300

Text:
  - Size: text-sm
  - Weight: font-semibold
  - Color: Gray-700
```

### Comparison Table

**Container**:

```
Background: White
Border:     1px gray-200
Padding:    p-6
Border-radius: rounded-lg
```

**Row Item**:

```
Border-bottom: 1px solid gray-100 (except last)
Padding-bottom: pb-5
Space:         gap-3 between elements

Metric Name: text-sm font-semibold gray-900
Current Value: text-sm font-bold gray-900
Change Badge:
  - Green: bg-green-100, text-green-700
  - Red: bg-red-100, text-red-700
  - Padding: px-2 py-1
  - Border-radius: rounded
  - Font: text-xs semibold
Previous: text-xs gray-500
```

### Day of Week Bar Chart

**Container**:

```
Background: Gradient blue-50 to gray-50
Border:     1px gray-100
Height:     h-80 (320px)
Padding:    p-4
Border-radius: rounded-lg
```

**Bar Structure**:

```
Container:  flex items-end justify-around h-56 gap-2
Bar:        width-full with flex-1
Height:     Proportional (% based on revenue)
Gradient:   from-blue-600 to-blue-400
Border-radius: rounded-t (top only)

Labels Below:
  - Day: text-xs font-semibold gray-600
  - Amount: text-xs gray-500
  - Margin: mt-2 from bar
```

---

## 📱 Responsive Design

### Desktop (1024px+)

```
Cards:   4 columns, full width
Charts:  2+1 layout, space for details
Text:    Full size, no truncation
Icons:   Large, prominent
Details: All visible
```

### Tablet (768px-1023px)

```
Cards:   2 columns
Charts:  Reflow to 2 columns
Text:    Slightly reduced size
Icons:   Medium size
Details: Essential only
Filters: May stack vertically
```

### Mobile (<768px)

```
Cards:   1 column, full width
Charts:  1 column, scrollable
Text:    Smaller but readable
Icons:   Medium size
Details: Simplified
Filters: Stacked vertically
Spacing: Reduced but comfortable
```

---

## ♿ Accessibility

### WCAG 2.1 AA Compliance

**Color Contrast**:

```
Text on background: 4.5:1 minimum
Blue on white:      4.5:1 ✓
Gray on white:      4.5:1 ✓
```

**Focus Indicators**:

```
All interactive: 2px solid ring
Ring color:      Blue-500 (#3b82f6)
Outline offset:  2px
Visible:         Always visible
```

**Keyboard Navigation**:

```
Tab:       Move to next element
Shift+Tab: Move to previous
Enter:     Activate button
```

**Semantic HTML**:

```
<button>    for interactive elements
<header>    for page header
<section>   for logical sections
<input>     for form fields
<label>     for input labels
```

---

## 🎨 Animation & Interaction

### Hover States

```
Cards:     Enhanced shadow (shadow-md)
Buttons:   Slight background darken
Charts:    Tooltip on hover (optional)
Transition: 150-300ms smooth
```

### Focus States

```
Inputs:    Blue ring, darker border
Buttons:   Blue ring visible
Links:     Underline appears
Transition: Instant
```

### Loading States

```
Skeleton:  Gray placeholder bars
Fade-in:   Smooth appearance (300ms)
Spinner:   In chart area
Text:      "Loading..." message
```

---

## 🎯 Visual Hierarchy

### Level 1: Page Header

- Largest text
- Prominent position
- Gradient background
- Eye-catching

### Level 2: Summary Cards

- Large metric values (3xl)
- Colored icons
- Clear positioning
- Grid layout

### Level 3: Chart Areas

- Secondary headers
- Large chart space
- Clear axis labels
- Legends

### Level 4: Details

- Small text
- Gray color
- Supporting info
- Secondary importance

---

## 📊 Chart Specifications

### Line Chart

```
X-Axis:      Dates, rotated 45°
Y-Axis:      Currency values, formatted
Grid:        Light gray (optional)
Lines:       2-3px width
Points:      4px radius circles
Tooltip:     Shows date, revenue, value
Smooth:      Curved (cubic interpolation)
```

### Bar Chart

```
Bars:        6-8px width
Height:      Proportional to value
Colors:      Solid or gradient
Gap Between: 8px
Labels:      Below bars, rotated 0°
Values:      Above bars, formatted currency
Hover:       Slightly highlight
```

### Progress Bars (Category)

```
Container:   Full width, bg-gray-200
Fill:        Solid color (category-specific)
Height:      8px (h-2 in Tailwind)
Radius:      rounded-full (pill shape)
Animation:   Smooth width transition (300ms)
```

---

## 🎓 Design Checklist

- [x] Gradient header implemented
- [x] 4-column card grid
- [x] Date range picker styled
- [x] Summary cards designed
- [x] Line chart area prepared
- [x] Bar chart visualization
- [x] Category breakdown styled
- [x] Comparison table laid out
- [x] Color scheme applied
- [x] Typography system set
- [x] Spacing system consistent
- [x] Responsive design verified
- [x] WCAG 2.1 AA compliant
- [x] Hover/focus states
- [x] Mobile optimized

---

**Design Version**: 1.0.0
**Last Updated**: January 29, 2026
**Compliance**: WCAG 2.1 AA
