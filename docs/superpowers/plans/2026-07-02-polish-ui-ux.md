# UI/UX Bug Fix & Polish Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Fix UI/UX bugs across all roles — mobile responsiveness, login animation, profile photo reactivity, admin notifications, bottom navigation, toast notifications.

**Architecture:** Targeted bug fixes approach. 18 files modified across 4 sections. Each task is self-contained with its own commit.

**Tech Stack:** Tailwind CSS v3, Alpine.js v3, Chart.js v4, Blade templates, Laravel 12

---

## Task 1: Toast Notification System

**Files:**
- Modify: `resources/css/app.css`
- Modify: `resources/js/app.js`
- Modify: `resources/views/layouts/app.blade.php`

- [ ] Add toast CSS styles to `resources/css/app.css`
- [ ] Add toast handler to `resources/js/app.js`
- [ ] Add toast container to `resources/views/layouts/app.blade.php`
- [ ] Test toast renders on login
- [ ] Commit: "feat: add toast notification system"

## Task 2: Mobile Bottom Navigation

**Files:**
- Modify: `resources/css/app.css`
- Modify: `resources/views/layouts/app.blade.php`
- Create: `resources/views/layouts/bottom-nav-user.blade.php`
- Create: `resources/views/layouts/bottom-nav-admin.blade.php`
- Create: `resources/views/layouts/bottom-nav-atasan.blade.php`

- [ ] Add bottom nav CSS to `resources/css/app.css`
- [ ] Add bottom nav container to `resources/views/layouts/app.blade.php`
- [ ] Create `bottom-nav-user.blade.php`
- [ ] Create `bottom-nav-admin.blade.php`
- [ ] Create `bottom-nav-atasan.blade.php`
- [ ] Test bottom nav on mobile for all roles
- [ ] Commit: "feat: add mobile bottom navigation"

## Task 3: Auth Pages — Logo Size + Gate Animation

**Files:**
- Modify: `resources/views/layouts/guest-auth.blade.php`
- Modify: `resources/views/auth/login.blade.php`
- Modify: `resources/css/app.css`

- [ ] Fix logo size in `guest-auth.blade.php`
- [ ] Add gate animation CSS to `app.css`
- [ ] Add gate animation to `login.blade.php`
- [ ] Test gate animation on login
- [ ] Commit: "feat: auth pages — logo size + gate animation"

## Task 4: Profile Photo Reactivity Bug Fix

**Files:**
- Modify: `resources/views/user/profile.blade.php`

- [ ] Add Alpine.js state for photoUrl
- [ ] Bind header photo to Alpine state
- [ ] Update photoUrl after successful upload
- [ ] Handle photo deletion
- [ ] Test photo reactivity
- [ ] Commit: "fix: profile photo reactivity"

## Task 5: Card Show Page Responsive Fix

**Files:**
- Modify: `resources/views/user/card-show.blade.php`

- [ ] Find hardcoded card width (640px)
- [ ] Replace with responsive width
- [ ] Add mobile scaling
- [ ] Test card on mobile
- [ ] Commit: "fix: card show responsive width"

## Task 6: Table Scroll Indicator

**Files:**
- Modify: `resources/css/app.css`
- Modify: `resources/js/app.js`
- Modify: 5 blade files (rekap-admin, rekap-admin-detail, laporan, users, verifikasi)

- [ ] Add table scroll wrapper CSS
- [ ] Wrap tables in rekap-admin.blade.php
- [ ] Wrap tables in rekap-admin-detail.blade.php
- [ ] Wrap tables in admin/laporan.blade.php
- [ ] Wrap tables in admin/users/index.blade.php
- [ ] Wrap tables in admin/verifikasi.blade.php
- [ ] Add scroll detection script
- [ ] Test scroll indicators
- [ ] Commit: "feat: table scroll indicators"

## Task 7: Admin Notification Bug Investigation

**Files:**
- Read: `app/Events/BiodataStatusChanged.php`
- Read: `app/Listeners/SendBiodataStatusNotification.php`
- Read: `app/Http/Controllers/BiodataController.php`
- Read: `app/Http/Controllers/VerifikasiController.php`
- Modify: (depends on findings)

- [ ] Investigate notification flow
- [ ] Fix based on findings
- [ ] Test notification flow
- [ ] Commit: "fix: admin notification bug"

## Task 8: Profile Tab Scroll Fix

**Files:**
- Modify: `resources/views/user/profile.blade.php`

- [ ] Add overflow-x-auto to tab container
- [ ] Test tab scroll on mobile
- [ ] Commit: "fix: profile tab scroll"

## Task 9: Profile Form Polish

**Files:**
- Modify: `resources/views/user/profile.blade.php`

- [ ] Add smooth collapse animation
- [ ] Consistent mobile padding
- [ ] Test collapsible sections
- [ ] Commit: "feat: smooth profile form animation"

## Task 10: Atasan Dashboard Fix

**Files:**
- Modify: `resources/views/atasan/dashboard.blade.php`
- Modify: `resources/views/atasan/rekap-admin.blade.php`
- Modify: `resources/views/atasan/rekap-admin-detail.blade.php`

- [ ] Fix chart container heights
- [ ] Fix stat cards grid
- [ ] Fix rekap-admin layout
- [ ] Fix rekap-admin-detail date filter
- [ ] Test all atasan pages
- [ ] Commit: "fix: atasan dashboard responsive"

## Task 11: Admin Dashboard Fix

**Files:**
- Modify: `resources/views/admin/dashboard.blade.php`

- [ ] Fix stat cards grid
- [ ] Fix chart height
- [ ] Add skeleton loading
- [ ] Test admin dashboard
- [ ] Commit: "fix: admin dashboard responsive"

## Task 12: Form Validation UX

**Files:**
- Modify: `resources/css/app.css`

- [ ] Add form validation styles
- [ ] Commit: "feat: form validation UX styles"

## Task 13: Run Tests & Verify

- [ ] Run `composer run test`
- [ ] Visual verification on mobile (375px)
- [ ] Visual verification on desktop (1280px)
- [ ] Final commit if needed
