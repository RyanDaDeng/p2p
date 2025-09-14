# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a **P2P Cryptocurrency Trading Platform** built with Laravel 12 and Vue 3, featuring a trust-first design system optimized for both desktop and mobile experiences.

## 🎨 Design System & Style Guide

### Core Design Philosophy
- **Trust-First Design**: Every design decision prioritizes building user trust and confidence
- **Security Visualization**: Visual elements emphasize platform safety and reliability
- **Professional Minimalism**: Clean fintech aesthetic without unnecessary decoration

### Brand Color System

#### Primary Colors
- **Emerald (翡翠绿)**: Primary action color for success, safety, and buy operations
  - Light: `emerald-600` / Dark: `emerald-500`
  - Use cases: Buy buttons, success states, security badges
  
- **Red**: Sell operations and warnings
  - Light: `red-600` / Dark: `red-400`
  
- **Blue**: Information and orders
  - Light: `blue-600` / Dark: `blue-400`
  
- **Amber**: Wallet and financial features
  - Light: `amber-600` / Dark: `amber-500`
  
- **Purple**: User profile related
  - Light: `purple-600` / Dark: `purple-400`

#### Background System
**Light Mode**:
- Primary: `white`
- Secondary: `gray-50`
- Borders: `gray-200`
- Text: `gray-700`, `gray-900`

**Dark Mode**:
- Primary: `slate-950`
- Secondary: `slate-900`
- Card backgrounds: `slate-900/60`
- Borders: `slate-800`
- Text: `slate-300`, `slate-100`

### Dark/Light Mode Implementation
**IMPORTANT**: Using Tailwind CSS v4 with CSS-based configuration
```css
/* In app.css */
@import "tailwindcss";
@variant dark (&:where(.dark, .dark *));
```

**Component Usage**:
```vue
<div class="bg-white dark:bg-slate-900 text-gray-700 dark:text-slate-300">
```

## 📱 Responsive Design Specifications

### Desktop Experience
- **Navigation**: Fixed top bar with full menu
- **Content**: Maximum width `max-w-7xl`
- **Trade Cards**: Horizontal expanded layout
- **Interactions**: Hover effects, dropdown menus

### Mobile Experience
- **Top Navigation**: Simplified - Logo + User Avatar/Login only
- **Bottom Navigation**: Fixed 5-item tab bar
  - 买币 (Buy) - Emerald
  - 卖币 (Sell) - Red
  - 订单 (Orders) - Blue  
  - 钱包 (Wallet) - Amber
  - 我的 (Profile) - Purple
- **Content**: Compact vertical cards
- **NO HAMBURGER MENU**: All functions accessible via bottom nav

## 🛠 Technology Stack

### Frontend
- **Vue 3** with Composition API
- **Inertia.js** for SPA-like experience
- **Tailwind CSS v4** with `@tailwindcss/vite`
- **VueUse** for composables

### Backend  
- **Laravel 12** with Jetstream
- **SQLite** (default database)
- **Sanctum** for API authentication

### Build Tools
- **Vite** for asset bundling
- **Composer** for PHP dependencies
- **NPM** for JavaScript packages

## 📁 Project Structure

```
resources/js/
├── Components/
│   └── UI/                    # Reusable UI components (P2P prefix)
│       ├── P2PButton.vue      # Unified button component
│       ├── P2PMobileDrawer.vue# Mobile drawer/sheet component
│       ├── P2PDropdown.vue    
│       ├── P2PSelect.vue      
│       ├── P2PPagination.vue  
│       └── P2PFooter.vue      
├── Layouts/
│   └── P2PAppLayout.vue       # Main app layout with mobile bottom nav
└── Pages/
    ├── Market/                # Market pages (formerly Lobby)
    │   └── Home.vue           
    └── Trade/                 # Trading pages
        └── Chat.vue           # Escrow chat interface
```

## 🎯 Component Development Guidelines

### Naming Convention
- All custom components use `P2P` prefix
- PascalCase for component names
- Descriptive names (e.g., `P2PTradeCard`, `P2PUserAvatar`)

### State Management
- Use Vue 3 Composition API
- Prefer `ref` and `computed`
- Avoid direct DOM manipulation

### Styling Rules
1. **Always support both themes**: Every color must have light/dark variants
2. **Mobile-first responsive**: Use `md:` prefix for desktop styles
3. **No inline styles**: Use Tailwind classes exclusively
4. **Consistent spacing**: Use Tailwind's spacing scale

### Code Style
- **No comments** unless absolutely necessary
- **Concise code** - prioritize readability
- **Chinese UI text** for user-facing content
- **English** for code, variables, and technical content
- **Cursor pointer** on ALL interactive elements (buttons, links, clickable areas)

## 🔐 Trust & Security Elements

### Visual Trust Indicators
- **Shield icons** with emerald color for security
- **Pulsing green dots** for online status  
- **Amber badges** for verification
- **5-star ratings** with trade counts
- **Progress bars** for completion rates

### Interactive Feedback
- **Loading states** with shield animation
- **Success messages** in emerald
- **Error messages** in red
- **Smooth transitions** for all interactions

## Development Commands

### Setup and Running
```bash
# Install dependencies
composer install
npm install

# Development server (runs all services)
composer dev

# Individual services
php artisan serve       # Laravel server
npm run dev            # Vite dev server
php artisan queue:listen
php artisan pail       # Log viewer
```

### Code Quality
```bash
# Format PHP code
./vendor/bin/pint

# Check without fixing
./vendor/bin/pint --test

# Run tests
php artisan test
```

### Building for Production
```bash
npm run build
php artisan optimize
```

## 🧩 Component Library

### P2PButton - 统一按钮组件
全局按钮组件，确保一致的交互体验。

**Props:**
- `variant`: 按钮变体
  - `primary` - 主要操作 (emerald-600)
  - `secondary` - 次要操作 (gray-600)
  - `danger` - 危险操作 (red-600)
  - `warning` - 警告操作 (amber-600)
  - `success` - 成功操作 (green-600)
  - `ghost` - 幽灵按钮
  - `outline` - 轮廓按钮
- `size`: `xs`, `sm`, `md`, `lg`, `xl`
- `fullWidth`: 占满宽度
- `disabled`: 禁用状态
- `loading`: 加载状态
- `iconOnly`: 纯图标按钮
- `rounded`: 圆角大小

**使用示例:**
```vue
<P2PButton variant="primary" size="md">
    <template #icon>
        <svg>...</svg>
    </template>
    确认支付
</P2PButton>
```

### P2PMobileDrawer - 移动端抽屉
移动端专用抽屉组件，支持底部和侧边弹出。

**Props:**
- `modelValue`: v-model绑定
- `title`: 标题
- `subtitle`: 副标题
- `position`: `bottom`, `left`, `right`
- `size`: `small`, `default`, `large`, `full`
- `persistent`: 点击遮罩不关闭
- `scrollable`: 内容可滚动

### P2PSelect - 增强选择器组件
通用下拉选择器，支持自定义渲染和主题适配。

**Props:**
- `modelValue`: 选中值
- `options`: 选项数组 `[{value, label, icon?, flag?}]`
- `placeholder`: 占位文本
- `disabled`: 禁用状态
- `size`: `sm`, `md`, `lg`
- `defaultValue`: 默认值

**特性:**
- 支持自定义slots渲染选项
- 自动关闭其他打开的下拉框
- 点击外部自动关闭
- 支持键盘ESC关闭
- 文本过长自动截断显示省略号
- Light/Dark模式滚动条适配

**Slots:**
- `selected`: 自定义选中项显示
- `option`: 自定义选项显示

**使用示例:**
```vue
<P2PSelect v-model="selected" :options="options">
  <template #selected="{ option }">
    <YourCustomIcon :type="option.value" />
    <span>{{ option.label }}</span>
  </template>
  <template #option="{ option }">
    <YourCustomIcon :type="option.value" />
    <span>{{ option.label }}</span>
  </template>
</P2PSelect>
```

### P2PPaymentMethodPicker - 支付方式选择器
专门的支付方式选择组件，支持分类浏览和搜索。

**Props:**
- `modelValue`: 选中的支付方式
- `placeholder`: 占位文本 (默认: "选择支付")
- `categories`: 支付方式分类数组

**特性:**
- 分类展示支付方式
- 支持搜索过滤
- 图标和颜色标识
- 弹窗式选择界面

### 其他核心组件
- **P2PDropdown**: 下拉菜单
- **P2PPagination**: 分页
- **P2PFooter**: 页脚

## 📊 Configuration System

### Backend Configuration
All configuration data is centralized in backend config files:
- `config/cryptocurrencies.php` - 加密货币配置
- `config/countries.php` - 国家列表配置
- `config/payment_methods.php` - 支付方式配置
- `config/fiat_currencies.php` - 法币配置

### Config API
统一配置API端点: `/web/api/config`
- 返回所有配置数据
- 支持缓存优化
- 英文响应格式

### Frontend Config Composable
使用 `useConfig` composable 管理配置:
```javascript
const {
  fetchConfig,           // 获取配置
  cryptocurrencies,      // 加密货币列表
  paymentMethods,       // 支付方式列表
  countries,            // 国家列表
  getCryptocurrencyByKey,
  getPaymentMethodDetails,
  // ... 其他helper方法
} = useConfig();
```

## ⚠️ Critical Notes

1. **Tailwind v4**: Configuration via CSS, NOT JavaScript config file
2. **Dark Mode**: Manual class management, not using VueUse's useDark
3. **Mobile Navigation**: Bottom tab bar only, no expandable menu
4. **Route Names**: Use `market` not `lobby` throughout
5. **Component Prefix**: All custom components must have `P2P` prefix
6. **Language**: UI in Chinese, code/comments in English
7. **Button Component**: Always use P2PButton for consistency
8. **API Routes**: ALL API routes MUST be in `routes/web.php` with `/web/api/` prefix, NOT in `routes/api.php`. This project uses session-based authentication
9. **Config Loading**: Load config once at parent level and pass via props to avoid multiple API calls
10. **Database Fields**: Use `currency_key` not `cryptocurrency`, use `network` not `chain`
11. **No Fallback Data**: NEVER display fallback, placeholder, or default content. Only show actual data from backend. Don't add your own text or values.

## 🚀 Best Practices

1. **Performance**
   - Lazy load large components
   - Use `v-show` for frequently toggled elements
   - Optimize images

2. **Accessibility**
   - Add `aria-label` to interactive elements
   - Ensure keyboard navigation
   - Maintain color contrast ratios

3. **Security**
   - Never expose sensitive data
   - Validate all inputs
   - Use CSRF protection

4. **Testing**
   - Write tests for critical paths
   - Test both themes
   - Test responsive breakpoints

---
*Project: P2P Cryptocurrency Trading Platform*
*Last Updated: 2024*
*Design System: Trust-First Professional*