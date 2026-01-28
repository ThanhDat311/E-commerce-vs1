# Top Selling Products - Design Guide

## Design Philosophy

The Top Selling Products dashboard is built on 4 core design principles:

1. **Insight-Driven**: Highlight actionable data for marketing decisions
2. **Ranking-Focused**: Clear product hierarchy and ranking
3. **Marketing-Oriented**: Support promotional and strategic planning
4. **Performance-Obsessed**: Emphasize metrics that drive business

---

## 📐 Color Palette

### Primary Neutral Colors

| Name      | Hex       | RGB              | Use                               |
| --------- | --------- | ---------------- | --------------------------------- |
| Slate-900 | `#0f172a` | rgb(15,23,42)    | Header backgrounds, serious tone  |
| Slate-800 | `#1e293b` | rgb(30,41,59)    | Secondary dark backgrounds        |
| Slate-700 | `#334155` | rgb(51,65,85)    | Tertiary elements                 |
| Gray-900  | `#111827` | rgb(17,24,39)    | Primary text, dark content        |
| Gray-600  | `#4b5563` | rgb(75,85,99)    | Secondary text, labels            |
| Gray-50   | `#f9fafb` | rgb(249,250,251) | Page background                   |
| White     | `#ffffff` | rgb(255,255,255) | Cards, modals, content containers |

### Data Visualization Colors

| Name       | Hex       | Use                                 |
| ---------- | --------- | ----------------------------------- |
| Blue-600   | `#2563eb` | Electronics (primary), bar charts   |
| Blue-400   | `#60a5fa` | Blue gradients, accents             |
| Purple-700 | `#a855f7` | Fashion, secondary category         |
| Purple-300 | `#d8b4fe` | Purple gradients, accents           |
| Green-600  | `#16a34a` | Growth indicators, positive trends  |
| Green-300  | `#86efac` | Green gradients, success highlights |
| Orange-600 | `#ea580c` | Home & Garden, accent               |
| Orange-200 | `#fed7aa` | Orange gradients, highlights        |
| Red-600    | `#dc2626` | Decline indicators, warnings        |
| Red-300    | `#fca5a5` | Red gradients, negative indicators  |

### Chart Gradient Colors (10 Products)

```
Product 1 (Headphones):    Blue:      #2563eb → #60a5fa
Product 2 (Watch):         Purple:    #a855f7 → #d8b4fe
Product 3 (T-Shirt):       Green:     #16a34a → #86efac
Product 4 (Yoga Mat):      Orange:    #ea580c → #fed7aa
Product 5 (Lamp):          Red:       #dc2626 → #fca5a5
Product 6 (Shoes):         Pink:      #ec4899 → #fbcfe8
Product 7 (Jacket):        Indigo:    #4f46e5 → #e0e7ff
Product 8 (Speaker):       Cyan:      #0891b2 → #cffafe
Product 9 (Plant Pot):     Lime:      #65a30d → #dcfce7
Product 10 (Protector):    Amber:     #d97706 → #fef3c7
```

### Status Colors

| Status                   | Color      | Use                               |
| ------------------------ | ---------- | --------------------------------- |
| Growth (Positive Trend)  | Green-600  | ↑ indicators, positive changes    |
| Decline (Negative Trend) | Red-600    | ↓ indicators, negative changes    |
| Warning                  | Orange-600 | Alert highlights, watch out items |
| Information              | Blue-600   | Info messages, neutral highlights |

---

## 🔤 Typography System

### Font Stack

```css
font-family:
    -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen, Ubuntu,
    Cantarell, sans-serif;
```

### Type Scale

#### Display Sizes

- **4xl** (44px): Page title (Slate-900 text on gradient background)
- **3xl** (30px): Summary card values (Gray-900 text)
- **xl** (20px): Section headers (Gray-900 text)
- **lg** (18px): Subsection headers (Gray-900 text)

#### Body Sizes

- **sm** (14px): Body text, table data, descriptions
- **xs** (12px): Labels, category tags, fine print

### Type Weights

| Weight | Name     | Use                               |
| ------ | -------- | --------------------------------- |
| 700    | Bold     | Titles, headers, important data   |
| 600    | Semibold | Labels, card titles, ranked items |
| 500    | Medium   | Emphasis within paragraphs        |
| 400    | Normal   | Body text, table content          |

### Line Heights

| Value | Use                       |
| ----- | ------------------------- |
| 1.25  | Headings (tight)          |
| 1.5   | Body text (readable)      |
| 1     | Metrics/numbers (compact) |

### Letter Spacing

| Value   | Use                         |
| ------- | --------------------------- |
| 0.05em  | Uppercase labels (tracking) |
| 0       | Normal text                 |
| -0.02em | Headings (slightly tight)   |

---

## 📐 Layout System

### Grid System

#### Desktop (1024px+)

```
Header: Full width
Cards: 4-column grid (25% each)
Content: 2-column (66% + 33%)
  - Left: Product table
  - Right: Bar chart
Categories: 2-column (50% each)
Footer: Full width
```

#### Tablet (768px - 1023px)

```
Header: Full width
Cards: 2-column grid (50% each)
Content: 2-column (60% + 40%)
  - Left: Product table
  - Right: Bar chart
Categories: 1-column (100%)
Footer: Full width
```

#### Mobile (<768px)

```
Header: Full width (adjusted)
Cards: 1-column grid (100%)
Content: 1-column (100%)
  - Full width table
  - Full width chart
Categories: 1-column (100%)
Footer: Full width
```

### Spacing System

| Scale | Size | Use                          |
| ----- | ---- | ---------------------------- |
| xs    | 4px  | Small gaps, internal padding |
| sm    | 8px  | Normal gaps between elements |
| md    | 12px | Medium spacing               |
| lg    | 16px | Section spacing              |
| xl    | 24px | Card padding                 |
| 2xl   | 32px | Page sections                |
| 3xl   | 48px | Major sections               |

### Container Widths

```
max-w-7xl = 80rem (1280px)  - Main content container
Full width - Header background
Full width - Page background
```

---

## 🎯 Component Specifications

### 1. Header Component

**Structure**:

- Background: Gradient (Slate-900 to Slate-800)
- Padding: xl (24px) all sides
- Max width: max-w-7xl (centered)

**Title Section**:

- Icon: Font Awesome `fa-star` (yellow-400)
- Title: "Top Selling Products" (4xl, bold, white)
- Subtitle: "Product performance insights..." (sm, slate-300)

**Export Button**:

- Style: Solid blue (Blue-600)
- Hover: Blue-700
- Padding: px-4 py-2
- Rounded: lg
- Icon: `fa-download` (white)
- Text: "Export Report" (white, semibold)

**Filter Bar**:

- Background: Slate-800, 50% opacity, backdrop blur
- Rounded: lg
- Padding: lg (16px) all sides
- Grid: 1 col (mobile) → 2 cols (tablet) → 5 cols (desktop)
- Gap: md (12px)

---

### 2. Summary Cards (4 Total)

**Layout**:

- Grid: 1-4 columns responsive
- Gap: lg (16px)
- Max height: auto-expand

**Individual Card**:

- Background: White
- Shadow: md
- Rounded: lg
- Border: Left 4px (color-coded)
- Padding: xl (24px)

**Border Colors**:

- Card 1: Blue-500 (Units)
- Card 2: Purple-500 (Revenue)
- Card 3: Green-500 (Count)
- Card 4: Orange-500 (Average)

**Typography**:

- Label: "UNITS SOLD" (xs, uppercase, gray-600)
- Value: "1,245" (3xl, bold, gray-900)
- Trend: "↑ 12% vs previous" (sm, green-600)

**Icons**:

- Size: 2xl
- Opacity: 20% (subtle background icon)
- Position: Top right
- Colors: Match border color

---

### 3. Product Table

**Container**:

- Background: White
- Shadow: md
- Rounded: lg
- Overflow: auto (horizontal scroll on mobile)

**Header**:

- Background: Gradient (Slate-900 to Slate-800)
- Text: White
- Padding: py-3 px-4
- Font: xs, uppercase, bold, gray-700

**Header Columns**:

- # (narrow, centered)
- Product Name (wide, left-aligned)
- Units (medium, right-aligned)
- Revenue (medium, right-aligned)
- Trend (medium, centered)
- % of Total (medium, centered)

**Rows**:

- Padding: py-4 px-6
- Border: Bottom, gray-200
- Hover: bg-gray-50 transition
- Alternating: None (consistent white)

**Rank Badge**:

- Size: w-8 h-8
- Border radius: Full circle
- #1: Blue-100 background, Blue-700 text
- Others: Gray-100 background, Gray-700 text
- Font: Bold, sm, centered

**Trend Indicators**:

- Positive (Green): Text-green-600, ↑ arrow
- Negative (Red): Text-red-600, ↓ arrow
- Font: sm, bold

---

### 4. Bar Chart

**Container**:

- Background: White
- Shadow: md
- Rounded: lg
- Padding: lg (16px) all sides

**Header**:

- Background: Gradient (Slate-900 to Slate-800)
- Text: White
- Title: "Units Sold Chart" (xl, bold)
- Subtitle: "Top 10 products by units" (sm, slate-300)

**Individual Bar**:

- Height: h-6 (24px)
- Border radius: full (rounded pill shape)
- Gap from next: md (12px)
- Proportional width: % of max

**Bar Styling**:

- Gradient colors: From darker to lighter shade
- Label: Product name (left, xs, truncated)
- Value: Unit count (right, inline, white text)
- Padding: pr-2

**Footer Section**:

- Background: Blue-50
- Border: 1px Blue-200
- Rounded: md
- Padding: sm (8px)
- Text: sm, Blue-700
- Icon: `fa-info-circle` (info)

---

### 5. Category Breakdown

**Layout**:

- Grid: 2 columns (desktop) → 1 column (mobile)
- Gap: lg (16px)

**Section Header**:

- Background: Gradient (Slate-900 to Slate-800)
- Text: White
- Title: lg, bold
- Icon: `fa-folder` (section specific)

**Category Item**:

- Padding: md (12px) bottom
- Border: Bottom gray-200 (except last)
- Text: Gray-900 (category name), Gray-600 (details)

**Progress Bar**:

- Height: h-2 (8px)
- Background: Gray-200
- Foreground: Category color (Blue/Purple/Green/Orange)
- Border radius: full
- Margin: mt-2

**Percentage Text**:

- Font: xs, Gray-600
- Margin: mt-1

---

### 6. Key Insights Section

**Layout**:

- Column grid: 1 (mobile) → 1 (tablet) → 1 (desktop)
- Stack: Vertical

**Insight Item**:

- Display: Flex with gap
- Padding: pb-4
- Border: Bottom gray-200 (except last)

**Icon Container**:

- Size: h-10 w-10 (40x40px)
- Border radius: lg
- Display: Flex, center items
- Background: Category color (10% opacity)
- Icon color: Category color (600)

**Text**:

- Title: sm, bold, gray-900
- Description: sm, gray-600

**Icon Types**:

1. Crown (`fa-crown`) - Blue-100 background, Blue-600 icon
2. Growth (`fa-arrow-trend-up`) - Green-100 background, Green-600 icon
3. Pie (`fa-chart-pie`) - Orange-100 background, Orange-600 icon
4. Warning (`fa-warning`) - Red-100 background, Red-600 icon

---

## 🎨 Responsive Design

### Breakpoints

```css
/* Tailwind breakpoints */
md: 768px   /* Tablet */
lg: 1024px  /* Desktop */
```

### Mobile First Approach

```css
/* Default: Mobile */
display: block;
grid-cols: 1;

/* Tablet and up */
@media (min-width: 768px) {
    grid-cols: 2;
}

/* Desktop and up */
@media (min-width: 1024px) {
    grid-cols: 4;
}
```

### Specific Responsive Rules

**Summary Cards**:

- Mobile: 1 column, full width
- Tablet: 2 columns, 50% width
- Desktop: 4 columns, 25% width

**Product Table**:

- Mobile: Horizontal scroll
- Tablet: Scroll if needed
- Desktop: Full visibility

**Category Sections**:

- Mobile: 1 column, 100%
- Tablet: 1 column, 100%
- Desktop: 2 columns, 50%

**Filter Bar**:

- Mobile: 1 column inputs
- Tablet: 2 columns
- Desktop: 5 columns in header

---

## ✨ Interaction States

### Hover States

**Buttons**:

```
Background: opacity-90 or darker shade
Cursor: pointer
Transition: 150ms ease-in-out
```

**Table Rows**:

```
Background: bg-gray-50
Cursor: pointer (optional)
Transition: instant
```

**Links**:

```
Text: Underline on hover
Color: Blue-700 (darker)
Transition: 150ms
```

### Focus States

**Inputs/Selects**:

```
Border: Blue-500
Outline: Ring 2px Blue-500
Transition: 150ms
```

**Buttons**:

```
Outline: Ring 2px Blue-500
Transition: 150ms
```

### Active States

**Selected Options**:

```
Background: Blue-100
Text: Blue-900
Font-weight: 600
```

---

## ♿ Accessibility (WCAG 2.1 AA)

### Color Contrast

| Element   | Foreground | Background | Ratio  | Standard |
| --------- | ---------- | ---------- | ------ | -------- |
| Body Text | Gray-900   | White      | 16.7:1 | AAA      |
| Labels    | Gray-600   | White      | 8.2:1  | AA       |
| Titles    | White      | Slate-900  | 12.8:1 | AAA      |
| Links     | Blue-600   | White      | 5.2:1  | AA       |
| Buttons   | White      | Blue-600   | 5.5:1  | AA       |

### Semantic HTML

```html
<header>
    Header section
    <main>
        Primary content
        <table>
            Structured data
            <form>
                Filter inputs
                <article>
                    Card content
                    <section>
                        Grouped content
                        <footer>Footer info</footer>
                    </section>
                </article>
            </form>
        </table>
    </main>
</header>
```

### Focus Indicators

- All interactive elements have visible focus rings
- Focus ring: 2px, Blue-500 color
- Never remove focus indicators
- Apply `focus:ring-2 focus:ring-blue-500`

### Alt Text

- Icons: No alt (decorative) or descriptive alt (informational)
- Charts: Descriptive alt or long description
- Images: Descriptive alt text

### Keyboard Navigation

- Tab order: Left-to-right, top-to-bottom
- All buttons: Keyboard accessible (Enter/Space)
- All inputs: Keyboard accessible
- Escape key: Close modals (not applicable here)

### Text Sizing

- Minimum font size: 12px (xs) for fine print
- Normal body text: 14px (sm)
- Labels: 12px (xs) uppercase
- Headings: Progressively larger scales

---

## 🎬 Animation Guidelines

### Transitions

**Duration**: 150ms standard (150ms = enough time to see, not annoying)
**Easing**: `ease-in-out` (smooth, natural feeling)
**Properties**:

- `background-color`
- `color`
- `border-color`
- `opacity`

```css
transition: background-color 150ms ease-in-out;
transition: color 150ms ease-in-out;
```

### Hover Effects

**Buttons**:

- Opacity: 90% or darker shade
- Example: `hover:bg-blue-700` (from blue-600)

**Cards**:

- Shadow: Increase by 1 level
- Example: `hover:shadow-lg` (from shadow-md)

**Links**:

- Underline: Add on hover
- Color: Darker shade

### No Auto-Animation

- Data tables: No slide/fade (instant visibility)
- Loading states: Spinner only if needed
- Transitions: Subtle, brief
- Goal: Fast, responsive feel

---

## 🎯 Design Checklist

### Visual Design

- [x] Color palette applied consistently
- [x] Typography scaled properly
- [x] Spacing consistent via Tailwind
- [x] Icons match design system
- [x] Borders and shadows applied
- [x] Responsive layouts work

### Interaction

- [x] Hover states visible
- [x] Focus states clear
- [x] Transitions smooth
- [x] Buttons clickable and obvious
- [x] Inputs clearly labeled
- [x] Feedback messages visible

### Accessibility

- [x] Color contrast sufficient
- [x] Text scalable to 200%
- [x] Keyboard navigation works
- [x] Focus indicators visible
- [x] Alt text where needed
- [x] Semantic HTML used

### Content

- [x] Headings hierarchical
- [x] Labels clear and associated
- [x] Instructions provided
- [x] Error messages helpful
- [x] Success feedback visible
- [x] Data clearly formatted

---

## 🚀 Implementation Notes

### Tailwind CSS Usage

The entire design uses Tailwind CSS utility classes:

- No custom CSS needed
- All colors use Tailwind color scale
- All spacing uses Tailwind spacing scale
- All typography uses Tailwind text utilities
- All responsive design uses Tailwind breakpoints

### Browser Support

- Chrome/Edge (latest)
- Firefox (latest)
- Safari (latest)
- Mobile browsers (all modern)

### Performance

- No heavy animations (instant, snappy)
- No CSS-in-JS (static Tailwind)
- No extra fonts (system fonts)
- No image optimization needed (icons only)

---

## 📞 Design Support

- **Color Questions**: Use color palette reference
- **Layout Questions**: Use grid system specs
- **Typography Questions**: Use type scale reference
- **Component Questions**: Use component specifications
- **Accessibility Questions**: Use WCAG checklist

---

**Version**: 1.0.0  
**Updated**: January 29, 2026  
**Status**: Design Approved
