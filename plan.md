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

---

## 11. TOOL & MCP INVENTORY

> **WAJIB:** Baca section ini sebelum pakai tool apapun. Jangan tebak fungsi tool — gunakan tabel referensi di bawah.

### 11.1 MCP Servers

| Server | Lokasi Config | Fungsi | Kapan Pakai |
|--------|---------------|--------|-------------|
| **`context7`** | global | Dokumentasi library/framework real-time | Sebelum pakai API/library apapun |
| **`playwright`** | global | Browser automation (navigate, click, screenshot, evaluate) | E2E testing, reproduce bug di browser, verifikasi UI |
| **`serena`** | global | Semantic code analysis, jump to definition | Cari implementasi fungsi, trace call hierarchy |
| **`laravel-boost`** | project + global | Laravel artisan commands via MCP | Jalankan artisan command tanpa bash |
| **`laravel-telescope`** | project | Debug query, exception, request di production | Investigasi slow query, N+1, exception |
| **`lighthouse`** | project | Core Web Vitals audit (FCP, LCP, TBT, CLS) | Performance audit sebelum/sesudah optimasi |
| **`filesystem`** | project | File read/write/search operations | Operasi file di luar workspace |
| **`markitdown`** | project | Convert content ke markdown | Konversi dokumen ke format baca |
| **`mendeley`** | project | Research paper management | Cari/manage paper penelitian |
| **`mcp-mermaid`** | project | Generate Mermaid diagrams | Buat diagram flow/architecture |
| **`drawio`** | project | Generate Draw.io diagrams | Buat diagram visual |
| **`github`** | global | Git operations, PR, issues, search | Push code, buat PR, search repos |
| **`chrome-devtools`** | global | Inspect element, network, console | Debug frontend, network request analysis |
| **`next-devtools`** | global | Next.js dev server MCP | Jika project pakai Next.js |
| **`vercel`** | global | Vercel deployment | Deploy ke Vercel |
| **`ts-hover`** | global | TypeScript info on hover | Saat refactor TypeScript |
| **`zread`** | global | Read GitHub repo source code | Baca source code open source |
| **`nightwatch`** | global + system | Laravel production monitoring | Cari error/issue di production |
| **`railway`** | global + system | Railway deployment, logs, status | Deploy, cek logs, manage service |

### 11.2 Skills (Superpowers Plugin)

| Skill | Fungsi | Kapan Pakai |
|-------|--------|-------------|
| **`brainstorming`** | Eksplorasi intent, requirements, brainstorm solusi | Sebelum bikin fitur baru |
| **`systematic-debugging`** | 4-phase debugging: root cause → pattern → hypothesis → fix | Saat ada bug/error/issue |
| **`test-driven-development`** | TDD: failing test → implement → pass | Implement fitur/fix bug |
| **`laravel-refactor`** | Code quality, reduce complexity, optimize | Saat refactor/cleanup code |
| **`laravel-architect`** | Design decision, scalability, database design | Saat arsitektur concern |
| **`laravel-debugger`** | Advanced Laravel debugging | Debug issue complex |
| **`requesting-code-review`** | Request review dari AI | Sebelum merge/PR |
| **`receiving-code-review`** | Terima & implement feedback | Dapat review feedback |
| **`verification-before-completion`** | Verifikasi sebelum claim done | Sebelum commit/PR |
| **`writing-plans`** | Buat implementation plan | Sebelum implement multi-step |
| **`executing-plans`** | Jalankan plan dengan checkpoints | Saat eksekusi plan |
| **`subagent-driven-development`** | Parallel task execution | Task independent bisa paralel |
| **`dispatching-parallel-agents`** | Dispatch agents untuk task paralel | Multiple task independent |
| **`using-git-worktrees`** | Isolated workspace via git | Butuh workspace terpisah |
| **`finishing-a-development-branch`** | Guide merge/PR/cleanup | Selesai implementasi |
| **`visual-explainer`** | Generate visual HTML pages | Butuh diagram/visualisasi |

### 11.3 Built-in Tools

| Tool | Fungsi | Kapan Pakai |
|------|--------|-------------|
| **`bash`** | Shell commands | Jalankan artisan, composer, npm, git |
| **`read`** | Read file contents | Baca source code, config, logs |
| **`write`** | Write/create files | Buat file baru |
| **`edit`** | Edit existing files | Ubah code yang sudah ada |
| **`glob`** | Pattern matching files | Cari file berdasarkan pattern |
| **`grep`** | Content search di files | Cari kode berdasarkan regex |
| **`task`** | Dispatch subagent | Delegasi task ke agent lain |
| **`todowrite`** | Task tracking | Track progress multi-step |
| **`websearch`** | Web search | Cari info di web (fallback context7) |
| **`webfetch`** | Fetch URL content | Ambil konten dari URL |
| **`question`** | Ask user clarification | Tanya user saat ragu |

### 11.4 Prioritas Penggunaan

```
1. Skill (jika situasi cocok) → WAJIB pakai
2. Context7 (sebelum pakai library/tool) → WAJIB cek dulu
3. Built-in Tools (untuk operasi dasar)
4. MCP Servers (untuk operasi spesifik)
```

---

## 12. LIBRARIES & PACKAGES

### Packages yang Dipakai di Project Ini

| Package | Fungsi | Dokumentasi via Context7 |
|---------|--------|--------------------------|
| **`laravel/breeze`** | Auth scaffolding (login, register) | `/laravel/breeze` |
| **`laravel/sanctum`** | API token authentication | `/laravel/framework` |
| **`spatie/laravel-responsecache`** | HTTP response caching | `/spatie/laravel-responsecache` |
| **`livewire/blaze`** | Blade pre-compilation | `/livewire/blaze` |
| **`barryvdh/laravel-dompdf`** | PDF generation | `/barryvdh/laravel-dompdf` |
| **`intervention/image`** | Image manipulation | `/intervention/image` |
| **`pestphp/pest`** | Testing framework | `/pestphp/pest` |
| **`laravel/framework`** | Core Laravel | `/laravel/framework` |
| **`laravel/pint`** | Code style fixer | `/laravel/pint` |

### Aturan Pakai Library

1. **Jangan asumsi library tersedia** — cek `composer.json` dulu
2. **Jangan tebak API** — pakai `context7_resolve-library-id` → `context7_query-docs`
3. **Jangan copy-paste dari web** — verifikasi via context7 dulu

---

## 13. CONTEXT7 FIRST RULE

> **ATURAN WAJIB:** Sebelum pakai tool/library/framework apapun, **WAJIB cek context7 dulu.**

### Alur yang BENAR

```
1. User minta pakai Library X
2. AI panggil context7_resolve-library-id(libraryName: "Library X")
3. AI panggil context7_query-docs(libraryId, "cara pakai feature Y")
4. Baca dokumentasi hasil query
5. Implementasi dengan API yang sudah diverifikasi
6. Tidak ada tebakan — semua berbasis dokumentasi resmi
```

### Alur yang SALAH

```
1. User minta pakai Library X
2. AI langsung tulis code tanpa cek dokumentasi
3. Code salah karena API berubah / method tidak ada
4. Debug → buang waktu
```

### Kapan WAJIB Pakai Context7

| Situasi | Contoh Query |
|---------|--------------|
| Pakai library baru | "Cara resize image pakai Intervention Image" |
| Pakai method baru | "Laravel Cache remember method signature" |
| Konfigurasi package | "Cara setup spatie responsecache di Laravel 12" |
| Error "function not found" | "Barryvdh DomPDF method to generate PDF" |
| Ragu API tertentu | "Alpine.js x-show vs x-if kapan pakai" |
| Pakai MCP tool baru | "Playwright MCP browser_navigate parameters" |

### Contoh Penggunaan

```
# BENAR: Cek context7 dulu
context7_resolve-library-id(libraryName: "Intervention Image")
→ libraryId: "/intervention/image"

context7_query-docs(libraryId: "/intervention/image", query: "cara resize image")
→ Mendapatkan API docs lengkap dengan code examples

# SALAH: Tebak fungsi
$ image->resize(100, 100);  // Method ini mungkin tidak ada!
```

---

## 14. TOOL USAGE LOGGING

> **ATURAN WAJIB:** Setelah selesai task, AI **WAJIB** tampilkan ringkasan tools di chat DAN catat di AGENTS.md.

### 14.1 Tampilkan di Chat (Wajib)

Setelah selesai task, AI **wajib** tampilkan ringkasan ini di chat:

```
🔧 Tools Used:
- Skills: nama skill yang dipakai (atau —)
- MCP: nama MCP server yang dipakai (atau —)
- Built-in: tools yang dipakai (atau —)
- Catatan: ringkasan hasil
```

**Contoh:**
```
🔧 Tools Used:
- Skills: systematic-debugging
- MCP: —
- Built-in: read, grep, glob
- Catatan: Ditemukan 2 bug login redirect admin/atasan
```

### 14.2 Catat di AGENTS.md (Arsip)

Selain tampilkan di chat, AI juga wajib catat di AGENTS.md section "Tool Usage Log" sebagai arsip:

```markdown
| Tanggal | Task | Skills | MCP Servers | Built-in Tools | Catatan |
|---------|------|--------|-------------|----------------|---------|
| YYYY-MM-DD | Deskripsi task | Skill yang dipakai | MCP yang dipakai | Tools yang dipakai | Hasil/catatan |
```

### Kapan Log Wajib

- Setelah selesai fix bug
- Setelah selesai implement fitur
- Setelah selesai refactor
- Setelah selesai deploy
- Setelah selesai debug production issue
- Setelah konsultasi dokumentasi via context7
