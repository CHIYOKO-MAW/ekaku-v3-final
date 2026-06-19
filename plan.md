# plan.md — Kondisi Pemakaian Skills & MCP Tools

> **Dibaca oleh AI sebelum memulai setiap task.**
> File ini menentukan KAPAN dan BAGAIMANA menggunakan skills dan MCP tools
> berdasarkan situasi yang dihadapi.

---

## Aturan Utama

1. **SELALU baca file ini sebelum memulai task apa pun.**
2. **Skills bersifat WAJIB** — jika situasi cocok, skills HARUS dipakai.
3. **MCP tools bersifat OPSIONAL** — gunakan jika membantu, skip jika tidak perlu.
4. **Jangan tebak** — gunakan tools untuk VERIFIKASI, bukan asumsi.
5. **Catat di AGENTS.md** setiap kondisi baru yang ditemukan.

---

## 1. KONDISI: Ada Bug / Error / Issue

### Skill WAJIB: `systematic-debugging`

**Kapan dipakai:**
- Error 500, 404, exception, test failure
- Behavioral tidak sesuai harapan
- Bug yang sudah dicoba fix tapi gagal
- Performa tiba-tiba turun

**Alur wajib (4 Phase):**
1. **Phase 1 — Root Cause Investigation**
   - Baca error message dengan teliti (jangan skip)
   - Reproduce bug secara konsisten
   - Cek recent changes (git diff)
   - Gather evidence di setiap layer

2. **Phase 2 — Pattern Analysis**
   - Cari working example di codebase
   - Bandingkan dengan reference implementation
   - Identifikasi perbedaan

3. **Phase 3 — Hypothesis & Testing**
   - Buat 1 hipotesa saja
   - Test dengan perubahan minimal
   - Verifikasi sebelum lanjut

4. **Phase 4 — Implementation**
   - Buat failing test case dulu
   - Fix 1 perubahan saja
   - Verifikasi fix berhasil

**RED FLAGS — JANGAN tebak:**
- "Quick fix for now"
- "Just try changing X"
- "I don't fully understand but this might work"
- Sudah gagal 3x → STOP, tanyakan arsitektur

### MCP Tools untuk Debugging:

| Tool | Kapan Dipakai | Contoh |
|------|---------------|--------|
| **`context7`** | Cari dokumentasi Laravel/PHP tentang error | "Bagaimana cara handle @cache Blade directive" |
| **`websearch** | Cari solusi error spesifik di web | "Laravel Class not found error production" |
| **`playwright`** | Reproduce bug di browser, verifikasi fix | Navigate ke halaman, cek network request |
| **`lighthouse`** | Audit performa untuk cari bottleneck | `lighthouse_run_audit` dengan URL |
| **`laravel-telescope`** | Cek query, exception, request di production | Debug query N+1, slow queries |
| **`nightwatch`** | Cari error di production environment | List issues, get issue details |

---

## 2. KONDISI: Implement Fitur Baru

### Skill WAJIB: `brainstorming`

**Kapan dipakai:**
- User minta "tambahkan X"
- "Bikin fitur baru"
- "Modifikasi behavior"
- Task yang melibatkan kreativitas/creative work

**Alur:**
1. Eksplorasi user intent dan requirements
2. Clarify requirements dengan user
3. Brainstorm solusi
4. Pilih approach terbaik
5. Implement

### MCP Tools untuk Implementasi:

| Tool | Kapan Dipakai | Contoh |
|------|---------------|--------|
| **`context7`** | Cari API reference/library docs | Cari cara pakai Intervention Image |
| **`playwright`** | Verifikasi fitur baru di browser | Login, navigasi, submit form |
| **`railway`** | Deploy setelah implement | `railway_redeploy`, cek status |
| **`github`** | Buat PR, push code | `github_push_files`, `github_create_pull_request` |

---

## 3. KONDISI: Refactoring / Code Quality

### Skill WAJIB: `laravel-refactor`

**Kapan dipakai:**
- "Bersihkan code ini"
- "Optimize performance"
- "Reduce complexity"
- Code smell ditemukan

### MCP Tools untuk Refactoring:

| Tool | Kapan Dipakai | Contoh |
|------|---------------|--------|
| **`context7`** | Cari best practice Laravel | Cari cara optimize query |
| **`playwright`** | Verifikasi refactor tidak break UX | Cek semua halaman masih jalan |
| **`lighthouse`** | Audit performa setelah refactor | Bandingkan score sebelum/sesudah |

---

## 4. KONDISI: Performance Issue

### Skill WAJIB: `systematic-debugging` (Phase 1-3)

**Alur Performance Debugging:**
1. **Measure dulu** — jangan optimize tanpa data
2. **Identifikasi bottleneck** — server-side atau network?
3. **Fix bottleneck** — satu per satu
4. **Re-measure** — verifikasi improvement

### MCP Tools untuk Performance:

| Tool | Kapan Dipakai | Contoh |
|------|---------------|--------|
| **`lighthouse`** | Core Web Vitals audit | FCP, LCP, TBT, CLS |
| **`playwright`** | Network request analysis | `playwright_browser_network_requests` |
| **`laravel-telescope`** | Query performance, slow queries | Cek N+1, missing index |
| **`context7`** | Cari optimasi pattern | "Laravel eager loading optimization" |
| **`websearch`** | Cari performa tips spesifik | "Laravel Blade fragment caching" |

---

## 5. KONDISI: Deployment / DevOps

### MCP Tools untuk Deployment:

| Tool | Kapan Dipakai | Contoh |
|------|---------------|--------|
| **`railway`** | Deploy, cek logs, status | `railway_list-deployments`, `railway_get-logs` |
| **`github`** | Push code, buat branch | `github_push_files`, `github_create_branch` |
| **`playwright`** | Verifikasi production setelah deploy | Navigate ke production URL |

---

## 6. KONDISI: Arsitektur / Design Decision

### Skill WAJIB: `laravel-architect`

**Kapan dipakai:**
- "Gimana cara terbaik untuk..."
- "Arsitektur yang tepat untuk..."
- "Scalability concern"
- "Database design"

### MCP Tools untuk Arsitektur:

| Tool | Kapan Dipakai | Contoh |
|------|---------------|--------|
| **`context7`** | Cari pattern Laravel | "Repository pattern Laravel" |
| **`websearch`** | Cari arsitektur reference | "Laravel queue best practices" |
| **`laravel-boost`** | Inspect routes, config, tinker | `php.bat artisan route:list` |

---

## 7. KONDISI: Testing

### Skill WAJIB: `test-driven-development`

**Kapan dipakai:**
- Implement fitur baru
- Fix bug
- Refactoring

**Aturan:**
1. Tulis failing test DULU
2. Implement fix
3. Test pass → done

### MCP Tools untuk Testing:

| Tool | Kapan Dipakai | Contoh |
|------|---------------|--------|
| **`playwright`** | E2E testing di browser | Login, submit form, verifikasi |
| **`laravel-telescope`** | Cek request/response di test | Debug test failure |

---

## 8. KONDISI: Research / Investigasi

### MCP Tools untuk Research:

| Tool | Kapan Dipakai | Contoh |
|------|---------------|--------|
| **`context7`** | Dokumentasi library/framework | Cari API reference |
| **`websearch`** | Informasi umum, solusi error | "How to fix X in Laravel" |
| **`webfetch`** | Ambil konten dari URL | Fetch documentation page |
| **`zread`** | Baca source code GitHub repo | Cari implementasi di open source |

---

## 9. KONDISI: Browser Automation

### MCP Tools:

| Tool | Kapan Dipakai | Contoh |
|------|---------------|--------|
| **`playwright`** | Interaksi web otomatis | Login, navigasi, submit, screenshot |
| **`chrome-devtools`** | Inspect element, network, console | Cek network request, console error |

**Aturan Playwright:**
- Selalu `navigate` dulu sebelum interaksi
- Pakai `snapshot` untuk cari element
- Pakai `screenshot` untuk verifikasi visual
- Pakai `evaluate` untuk jalankan JavaScript

---

## 10. KONDISI: Code Review

### Skill WAJIB: `requesting-code-review` atau `receiving-code-review`

**Kapan dipakai:**
- Sebelum merge PR
- Setelah dapat feedback
- Verifikasi kualitas code

---

## Ringkasan Cepat

| Situasi | Skill | MCP Utama |
|---------|-------|-----------|
| **Bug/Error** | `systematic-debugging` | context7, playwright, telescope |
| **Fitur Baru** | `brainstorming` | context7, playwright, railway |
| **Refactor** | `laravel-refactor` | context7, lighthouse, playwright |
| **Performance** | `systematic-debugging` | lighthouse, playwright, telescope |
| **Deploy** | — | railway, github, playwright |
| **Arsitektur** | `laravel-architect` | context7, websearch, laravel-boost |
| **Testing** | `test-driven-development` | playwright, telescope |
| **Research** | — | context7, websearch, zread |
| **Browser** | — | playwright, chrome-devtools |
| **Code Review** | `requesting-code-review` | — |

---

## Catatan Penting

- **Playwright** adalah tool browser utama — gunakan untuk semua verifikasi web
- **Context7** adalah tool dokumentasi utama — gunakan untuk semua pertanyaan API/library
- **Railway** adalah tool deployment utama — gunakan untuk semua operasi production
- **Laravel Telescope** adalah tool debugging production — gunakan untuk query, exception, request
- **Lighthouse** adalah tool performa — gunakan untuk Core Web Vitals
- **Websearch** adalah fallback saat context7 tidak punya jawaban
