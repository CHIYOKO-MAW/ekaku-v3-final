# Remove BladeFlow + Fix Chart & Active Navigation

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Remove all BladeFlow SPA directives and gate animation to restore full page reload navigation, fix Chart.js rendering, and fix active navigation indicator.

**Architecture:** Remove `@bladeflow`, `@bladeflowContent`, `@bladeflowEngine` directives from all Blade templates. Remove gate animation script and CSS. Every navigation becomes a full page reload so server re-renders nav with correct active state and Chart.js init scripts re-execute.

**Tech Stack:** Laravel 12 Blade, Tailwind CSS, Alpine.js v3, Chart.js, Pest v4

---

## File Map

| File | Change |
|------|--------|
| `resources/views/layouts/app.blade.php` | Remove `@bladeflowContent` (line 35), `@bladeflowEngine` (line 84) |
| `resources/views/layouts/guest-auth.blade.php` | Remove `data-bladeflow-content` (line 20), gate animation script (lines 64-163), `@bladeflowEngine` (line 164) |
| `resources/css/app.css` | Remove gate animation CSS (lines 269-324) |
| `resources/views/layouts/navigation-user.blade.php` | Remove `@bladeflow` from 13 links |
| `resources/views/layouts/navigation-admin.blade.php` | Remove `@bladeflow` from 15 links |
| `resources/views/layouts/navigation-atasan.blade.php` | Remove `@bladeflow` from 9 links |
| `resources/views/auth/login.blade.php` | Remove `@bladeflow` from 2 links |
| `resources/views/auth/register.blade.php` | Remove `@bladeflow` from 1 link |
| `resources/views/auth/forgot-password.blade.php` | Remove `@bladeflow` from 1 link |

---

### Task 1: Remove BladeFlow from app.blade.php

**Files:** `resources/views/layouts/app.blade.php:35,84`

- [ ] **Step 1: Remove `@bladeflowContent` from `<main>` tag**

Line 35, change:
```blade
<main @bladeflowContent class="min-h-screen">
```
to:
```blade
<main class="min-h-screen">
```

- [ ] **Step 2: Remove `@bladeflowEngine` before `</body>`**

Delete line 84:
```blade
    @bladeflowEngine
```

- [ ] **Step 3: Verify no BladeFlow references remain**

Run: `grep -r "@bladeflow" resources/views/layouts/app.blade.php`
Expected: no matches

---

### Task 2: Remove gate animation from guest-auth.blade.php

**Files:** `resources/views/layouts/guest-auth.blade.php:20,64-164`

- [ ] **Step 1: Remove `data-bladeflow-content` from wrapper**

Line 20, change:
```blade
<div class="login-gate-wrapper" data-bladeflow-content>
```
to:
```blade
<div class="login-gate-wrapper">
```

- [ ] **Step 2: Remove entire gate animation script block**

Delete lines 64-163 (the entire `<script>...</script>` block that does document.write swap, fetch interceptor, error handling).

- [ ] **Step 3: Remove `@bladeflowEngine`**

Delete line 164:
```blade
    @bladeflowEngine
```

- [ ] **Step 4: Verify file ends cleanly**

File should end with:
```blade
    </div>
</body>
</html>
```

---

### Task 3: Remove gate animation CSS from app.css

**Files:** `resources/css/app.css:269-324`

- [ ] **Step 1: Delete gate animation CSS**

Delete lines 269-324. Everything from `/* Gate cinematic animation */` to end of file. This includes:
- `.login-gate-open .login-brand-section` transforms
- `.login-gate-open .login-form-section` transforms
- `.login-gate-open::after` overlay
- `@keyframes gate-cinematic-overlay`
- Mobile responsive gate animations

Replace with:
```css
/* Login split layout */
.login-gate-wrapper {
  display: flex;
  min-height: 100vh;
}
```

- [ ] **Step 2: Verify file ends cleanly**

File should end with the `.login-gate-wrapper` block only.

---

### Task 4: Remove @bladeflow from navigation-user.blade.php

**Files:** `resources/views/layouts/navigation-user.blade.php`

- [ ] **Step 1: Remove `@bladeflow` from all 13 `<a>` tags**

Use replaceAll to change ` @bladeflow ` to ` ` in all `<a>` tags. Or change each individually:

```bash
# Quick approach: sed to remove @bladeflow from anchor tags
sed -i 's/ @bladeflow / /g' resources/views/layouts/navigation-user.blade.php
```

Lines affected: 18, 21, 24, 27, 107, 108, 109, 110, 111, 122, 125, 128, 131

- [ ] **Step 2: Verify no @bladeflow remains**

Run: `grep -c "@bladeflow" resources/views/layouts/navigation-user.blade.php`
Expected: `0`

---

### Task 5: Remove @bladeflow from navigation-admin.blade.php

**Files:** `resources/views/layouts/navigation-admin.blade.php`

- [ ] **Step 1: Remove `@bladeflow` from all 15 `<a>` tags**

```bash
sed -i 's/ @bladeflow / /g' resources/views/layouts/navigation-admin.blade.php
```

Lines affected: 18, 19, 20, 21, 22, 92, 93, 94, 95, 96, 107, 110, 113, 116, 119

- [ ] **Step 2: Verify no @bladeflow remains**

Run: `grep -c "@bladeflow" resources/views/layouts/navigation-admin.blade.php`
Expected: `0`

---

### Task 6: Remove @bladeflow from navigation-atasan.blade.php

**Files:** `resources/views/layouts/navigation-atasan.blade.php`

- [ ] **Step 1: Remove `@bladeflow` from all `<a>` tags**

```bash
sed -i 's/ @bladeflow / /g' resources/views/layouts/navigation-atasan.blade.php
```

Lines affected: 18, 19, 89, 90, 101, 104, 107

- [ ] **Step 2: Verify no @bladeflow remains**

Run: `grep -c "@bladeflow" resources/views/layouts/navigation-atasan.blade.php`
Expected: `0`

---

### Task 7: Remove @bladeflow from auth pages

**Files:**
- `resources/views/auth/login.blade.php:42,49`
- `resources/views/auth/register.blade.php:55`
- `resources/views/auth/forgot-password.blade.php:29`

- [ ] **Step 1: login.blade.php - remove @bladeflow from 2 links**

Line 42, change:
```blade
<a class="text-sm text-disnaker-600 hover:text-disnaker-700" href="{{ route('password.request') }}" @bladeflow>
```
to:
```blade
<a class="text-sm text-disnaker-600 hover:text-disnaker-700" href="{{ route('password.request') }}">
```

Line 49, change:
```blade
<a class="text-sm text-gray-600 hover:text-gray-900" href="{{ route('register') }}" @bladeflow>Belum punya akun?</a>
```
to:
```blade
<a class="text-sm text-gray-600 hover:text-gray-900" href="{{ route('register') }}">Belum punya akun?</a>
```

- [ ] **Step 2: register.blade.php - remove @bladeflow from 1 link**

Line 55, change:
```blade
<a class="text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}" @bladeflow>Sudah punya akun?</a>
```
to:
```blade
<a class="text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">Sudah punya akun?</a>
```

- [ ] **Step 3: forgot-password.blade.php - remove @bladeflow from 1 link**

Line 29, change:
```blade
<a class="text-sm text-disnaker-600 hover:text-disnaker-700" href="{{ route('login') }}" @bladeflow>
```
to:
```blade
<a class="text-sm text-disnaker-600 hover:text-disnaker-700" href="{{ route('login') }}">
```

- [ ] **Step 4: Verify no @bladeflow remains in auth pages**

Run: `grep -rc "@bladeflow" resources/views/auth/`
Expected: all files show `0`

---

### Task 8: Build assets and verify

- [ ] **Step 1: Build CSS/JS**

Run: `npm run build`
Expected: build completes without errors

- [ ] **Step 2: Run tests**

Run: `composer run test`
Expected: all tests pass (or only pre-existing failures)

- [ ] **Step 3: Verify no BladeFlow directives remain anywhere**

Run: `grep -r "@bladeflow\|@bladeflowContent\|@bladeflowEngine\|data-bladeflow" resources/views/`
Expected: no matches

- [ ] **Step 4: Verify gate animation CSS is gone**

Run: `grep -c "login-gate-open" resources/css/app.css`
Expected: `0`

- [ ] **Step 5: Commit and push**

```bash
git add -A
git commit -m "Remove BladeFlow SPA, gate animation - fix chart & nav active indicator"
git push
```

- [ ] **Step 6: Deploy to Railway and verify**

Deploy, then Playwright test:
1. Login as user@ekaku.test - verify chart renders on dashboard
2. Click nav links - verify active indicator (garis bawah) pindah
3. Login as admin@ekaku.test - same checks
4. Login as atasan@ekaku.test - same checks
5. Mobile view - verify bottom nav active states work
