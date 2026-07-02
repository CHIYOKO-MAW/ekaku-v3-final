# Gate Animation Fix + BladeFlow SPA Navigation

**Goal:** Fix login gate animation smoothness (fetch paralel) + install BladeFlow untuk SPA navigasi.

---

## Part A: Fix Gate Animation (fetch() approach)

**Problem:** Animasi + server request sequential → ~1.25 detik jeda
**Fix:** `fetch()` biar animasi + server jalan paralel → ~600ms

- [x] **A1.** Edit `resources/views/layouts/guest-auth.blade.php`
  - Ganti script `form.submit()` → `fetch()` dengan redirect
  - Tambah loading spinner di tombol "Masuk" saat submit
  - Handle error login (balikin form, show error message)
- [x] **A2.** Tambah CSS spinner di `resources/css/app.css`

---

## Part B: BladeFlow SPA Navigation

- [x] **B1.** Install: `composer require thbappy7706/bladeflow`
- [x] **B2.** Publish assets: `php artisan vendor:publish --tag=bladeflow-assets`
- [x] **B3.** Edit `resources/views/layouts/app.blade.php`
  - Tambah `@bladeflowContent` di `<main>`
  - Tambah `@bladeflowEngine` sebelum `</body>`
- [x] **B4.** Edit `resources/views/layouts/navigation-admin.blade.php`
  - Tambah `@bladeflow` di semua `<a href>` nav links
- [x] **B5.** Edit `resources/views/layouts/navigation-user.blade.php`
  - Tambah `@bladeflow` di semua `<a href>` nav links
- [x] **B6.** Edit `resources/views/layouts/navigation-atasan.blade.php`
  - Tambah `@bladeflow` di semua `<a href>` nav links
- [x] **B7.** Edit `start-container.sh`
  - Tambah `php artisan view:clear` + `php artisan route:clear`
- [x] **B8.** Edit `.gitignore`
  - Tambah `/public/vendor`

---

## Part C: Deploy & Verify

- [ ] **C1.** Commit + push
- [ ] **C2.** `railway up` deploy
- [ ] **C3.** Verify login gate animation smooth (Playwright)
- [ ] **C4.** Verify SPA navigation tanpa full reload (Playwright)

---

## Status

| Task | Status |
|------|--------|
| A1: fetch() gate animation | pending |
| A2: CSS spinner | pending |
| B1: composer require | pending |
| B2: publish assets | pending |
| B3: app.blade.php | pending |
| B4: nav-admin | pending |
| B5: nav-user | pending |
| B6: nav-atasan | pending |
| B7: start-container.sh | pending |
| B8: .gitignore | pending |
| C1-C4: deploy & verify | pending |
