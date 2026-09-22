# Component Library — Backend Admin UMKM KUTIM

Dokumen ini berisi struktur component library yang disesuaikan dengan desain yang sudah ada, namun ditujukan khusus untuk halaman backend/admin dashboard. Tujuannya agar UI backend tetap konsisten, formal, elegant, dan mudah dikembangkan.

## 1. Prinsip desain backend

Backend halaman harus memiliki karakter:
- bersih dan formal
- mudah dibaca saat bekerja dengan data
- fokus pada data, filter, action, dan status
- tidak terlalu dekoratif seperti landing page
- tetap mengikuti brand utama: hijau institusi + sentuhan gold

## 2. Design tokens

### Warna dasar
- primary-900: #022c22
- primary-800: #064e3b
- primary-700: #166534
- primary-600: #16a34a
- primary-50: #f0fdf4
- accent-500: #d97706
- accent-400: #f59e0b
- neutral-50: #f8fafc
- neutral-100: #f1f5f9
- neutral-200: #e2e8f0
- neutral-300: #cbd5e1
- neutral-600: #475569
- neutral-800: #1e293b
- white: #ffffff
- danger-500: #ef4444
- danger-50: #fef2f2
- warning-500: #f59e0b
- success-500: #22c55e
- info-500: #3b82f6

### Radius
- sm: 8px
- md: 12px
- lg: 16px
- xl: 20px
- 2xl: 24px

### Shadow
- soft: 0 8px 24px rgba(15, 23, 42, 0.08)
- medium: 0 12px 30px rgba(2, 44, 34, 0.12)
- subtle: 0 2px 10px rgba(15, 23, 42, 0.05)

### Border
- 1px solid rgba(22, 163, 74, 0.12)
- 1px solid rgba(148, 163, 184, 0.18)

### Typography
- heading 1: 28-32px, font-weight 800
- heading 2: 22-24px, font-weight 700
- heading 3: 18-20px, font-weight 700
- body: 14-16px, font-weight 500
- caption: 12-13px, font-weight 500

## 3. Layout system backend

### A. Page shell
Setiap halaman admin/pelaku biasanya terdiri dari:
- topbar
- sidebar
- content area
- footer atau minimal page footer

### B. Struktur umum
```html
<div class="min-h-screen bg-slate-50">
  <aside class="fixed ...">Sidebar</aside>

  <main class="ml-72">
    <header class="topbar"> ... </header>
    <section class="p-6">
      <div class="page-header"> ... </div>
      <div class="content-card"> ... </div>
    </section>
  </main>
</div>
```

## 4. Component list

### 4.1 Button

#### Button variants
- primary
- secondary
- ghost
- danger
- success
- warning
- icon-button

#### Style
- Primary
  - background: emerald 700/800
  - text: white
  - hover: emerald 800/900
  - padding: 10px 16px
  - radius: md
  - shadow: subtle

- Secondary
  - background: white
  - border: 1px solid green border
  - text: dark green

- Danger
  - background: red 500
  - white text

- Ghost
  - transparent background
  - text grey/green

#### Usage
- Tambah data
- Simpan data
- Hapus / hapus permanen
- Approve / Reject
- Filter / reset

#### Recommended markup
```html
<button class="inline-flex items-center gap-2 rounded-xl bg-emerald-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-emerald-800 transition">
  <i class="fa-solid fa-plus"></i>
  Tambah Data
</button>
```

### 4.2 Card

#### Card variants
- default-card
- metric-card
- info-card
- panel-card

#### Style
- white background
- border 1px solid neutral 200
- radius 16px
- padding 20px
- shadow soft

#### Usage
- ringkasan dashboard
- detail item
- form wrapper
- panel list data

#### Recommended markup
```html
<div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
  <div class="flex items-center justify-between">
    <h3 class="text-lg font-bold text-slate-800">Judul</h3>
  </div>
</div>
```

### 4.3 Badge / Status

#### Status variants
- success
- warning
- danger
- neutral
- info

#### Example
- active => green
- pending => amber
- rejected => red
- draft => slate
- approved => green

#### Recommended markup
```html
<span class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700">
  Approved
</span>
```

### 4.4 Input Field

#### Types
- text
- email
- password
- number
- textarea
- select
- date
- file upload

#### Style
- white background
- border neutral 200
- radius md
- padding 10-12px
- focus border green 500
- label above field

#### Recommended markup
```html
<div class="space-y-2">
  <label class="text-sm font-semibold text-slate-700">Nama usaha</label>
  <input type="text" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-800 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100" placeholder="Masukkan nama usaha" />
</div>
```

### 4.5 Select / Dropdown

#### Style
Sama dengan input, dengan icon arrow di kanan.

#### Usage
- status filter
- kategori
- role
- tahun / bulan

### 4.6 Textarea

#### Style
- min-height 100-160px
- resize vertical
- border neutral

### 4.7 File Upload

#### Style
- dropzone area
- border dashed
- background neutral 50
- file name displayed after selected
- upload preview untuk foto/doc

#### Important rules
- upload file maksimal 2MB / 5MB
- hanya format PDF/JPG/PNG
- nama file akan ditampilkan setelah upload

### 4.8 Table

#### Table structure
- header row
- sorting icon optional
- status badge in cells
- action button group
- row hover background

#### Recommended markup
```html
<div class="overflow-hidden rounded-2xl border border-slate-200 bg-white">
  <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
    <thead class="bg-slate-50 text-slate-700">
      <tr>
        <th class="px-4 py-3 font-semibold">Nama</th>
        <th class="px-4 py-3 font-semibold">Kategori</th>
        <th class="px-4 py-3 font-semibold">Status</th>
        <th class="px-4 py-3 font-semibold text-right">Aksi</th>
      </tr>
    </thead>
    <tbody class="divide-y divide-slate-200">
      <tr class="hover:bg-slate-50">
        <td class="px-4 py-3">UMKM Maju Jaya</td>
        <td class="px-4 py-3">Kuliner</td>
        <td class="px-4 py-3"><span class="badge-success">Approved</span></td>
        <td class="px-4 py-3 text-right">
          <div class="flex justify-end gap-2">
            <button class="btn-secondary-small">Detail</button>
            <button class="btn-danger-small">Hapus</button>
          </div>
        </td>
      </tr>
    </tbody>
  </table>
</div>
```

### 4.9 Pagination

#### Style
- page number buttons rounded
- current page active with green background
- prev/next disabled styling
- compact but readable

#### Recommended markup
```html
<nav class="flex items-center justify-between gap-3">
  <div class="text-sm text-slate-600">Menampilkan 1-10 dari 120 data</div>
  <div class="flex items-center gap-2">
    <button class="rounded-lg border border-slate-200 px-3 py-2 text-sm">Prev</button>
    <button class="rounded-lg bg-emerald-700 px-3 py-2 text-sm text-white">1</button>
    <button class="rounded-lg border border-slate-200 px-3 py-2 text-sm">2</button>
    <button class="rounded-lg border border-slate-200 px-3 py-2 text-sm">Next</button>
  </div>
</nav>
```

### 4.10 Search Bar

#### Style
- icon left inside input
- rounded xl
- max width or full width
- beside filter button

#### Usage
- pencarian nama usaha, user, berita, event

#### Recommended markup
```html
<div class="flex items-center gap-3">
  <div class="relative flex-1">
    <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
    <input type="text" class="w-full rounded-xl border border-slate-200 bg-white py-2.5 pl-10 pr-3 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100" placeholder="Cari data..." />
  </div>
  <button class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700">Filter</button>
</div>
```

### 4.11 Filter Panel

#### Structure
- row of filters
- status select
- category select
- date input or month/year selector
- reset button

#### Usage
- untuk list UMKM, user, laporan, bazar, pelatihan

### 4.12 Sidebar

#### Required items
- Dashboard
- UMKM
- Verifikasi UMKM
- User
- Slider
- Berita
- Pelatihan
- Bazaar
- Resume
- Laporan
- Settings

#### Style
- fixed left column
- background dark green or white with green accents
- logo on top
- menu items with icon and label
- active item highlighted green

#### Recommended markup
```html
<aside class="h-screen w-72 border-r border-slate-200 bg-[#022c22] text-white">
  <div class="p-5 border-b border-white/10">
    <div class="flex items-center gap-3">
      <img src="/logo1.png" class="h-10 w-10" />
      <div>
        <p class="text-sm font-bold">UMKM KUTIM</p>
        <p class="text-xs text-emerald-200">Admin Panel</p>
      </div>
    </div>
  </div>

  <nav class="p-3 space-y-1">
    <a class="flex items-center gap-3 rounded-xl bg-emerald-700 px-3 py-2.5 text-sm font-semibold">Dashboard</a>
    <a class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm text-emerald-100 hover:bg-white/5">UMKM</a>
  </nav>
</aside>
```

### 4.13 Topbar

#### Contents
- page title
- search bar optional
- user profile dropdown
- notification icon
- button add new

#### Style
- white background
- sticky top
- border bottom
- padding 16px 24px

### 4.14 Metric Cards

#### Purpose
Menampilkan ringkasan data dashboard.

#### Example metrics
- Total UMKM
- UMKM Tervalidasi
- UMKM Pending
- Total User
- Total Pendapatan
- Total Event

#### Structure
- title
- big number
- trend or subtitle
- small icon at corner

#### Recommended markup
```html
<div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
  <div class="flex items-center justify-between">
    <p class="text-sm text-slate-500">Total UMKM</p>
    <span class="rounded-lg bg-emerald-50 p-2 text-emerald-700"><i class="fa-solid fa-store"></i></span>
  </div>
  <p class="mt-4 text-3xl font-extrabold text-slate-900">1,248</p>
  <p class="mt-2 text-xs text-emerald-600">+12.5% dari bulan lalu</p>
</div>
```

### 4.15 Modal

#### Usage
- delete confirmation
- approve/reject verification
- add/edit form in overlay

#### Style
- dark translucent overlay
- centered white panel
- title, description, actions

### 4.16 Empty State

#### Usage
- no data found
- no search result
- no verification request

#### Style
- large icon
- message text
- optional CTA

### 4.17 Tabs / Segmented control

#### Usage
- overview / data / history
- aktif / pending / rejected

#### Style
- rounded segmented group
- active tab with green fill

### 4.18 Form Section

#### Structure
- section title
- grid with 2 columns on desktop
- fields grouped logically

#### Example groups
- Profile UMKM
- Legalitas dan Dokumen
- Media Sosial / Olshop
- Lokasi dan Kontak

### 4.19 Notification / Toast

#### Types
- success
- error
- warning
- info

#### Style
- top right
- green/red/amber border with icon
- concise message

## 5. Component usage rules

### A. Consistency rules
- semua form field harus menggunakan style yang sama
- semua status harus memakai badge yang sama
- semua table menggunakan row hover dan action styles yang sama
- semua CTA utama harus menggunakan primary green

### B. Responsive rules
- grid form menjadi 1 kolom pada mobile
- table bisa di-wrap dengan overflow horizontal
- sidebar menjadi menu top / drawer pada mobile

### C. Accessibility rules
- label harus selalu ada untuk input
- button harus mempunyai fungsi jelas
- warna status harus memiliki kontras yang cukup
- focus state harus terlihat jelas

## 6. Recommended backend page patterns

### Dashboard page
- metric cards row
- recent activity card
- table preview
- chart or summary panel

### List page
- page header with title + add button
- search + filter bar
- table with pagination

### Form page
- page header with breadcrumb
- section card for form fields
- action buttons bottom right

### Detail page
- summary card + metadata
- related data tables
- document preview / file links

## 7. Suggested component naming convention

Gunakan pendekatan yang rapi agar mudah diimplementasikan di Blade atau React:

- `ButtonPrimary`
- `ButtonSecondary`
- `BadgeStatus`
- `TableList`
- `SearchBar`
- `FilterPanel`
- `MetricCard`
- `SidebarNav`
- `TopBar`
- `ModalDialog`
- `FormSection`
- `EmptyState`
- `PaginationNav`

## 8. Final recommendation

Untuk backend halaman admin, komponen yang paling utama harus tersedia terlebih dahulu:
1. Button
2. Form input
3. Badge status
4. Table
5. Search + filter
6. Pagination
7. Metric card
8. Sidebar
9. Topbar
10. Modal
11. Empty state

Jika komponen-komponen ini sudah stabil, maka semua halaman backend seperti UMKM, user, bazar, pelatihan, verifikasi, dan laporan akan lebih mudah dibangun secara konsisten.

## 9. Satu kalimat ringkas

Backend UMKM KUTIM sebaiknya memakai sistem desain yang formal, data-first, hijau institusional, dengan komponen reusable untuk table, form, status, filter, pagination, dan dashboard metrics agar tetap konsisten di seluruh halaman admin.
