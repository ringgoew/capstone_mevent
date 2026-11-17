# DESIGN.md — M!VENT (Multi-Organizer Event Management System)

Dokumen ini adalah sumber kebenaran utama (*single source of truth*) untuk arah visual, token warna, tipografi, mikro-interaksi, dan aturan arsitektur frontend pada proyek Laravel **M!VENT**. Setiap perubahan UI/UX wajib mematuhi spesifikasi di bawah ini.

---

## 1. Filosofi Desain & Arah Estetika
- **Karakter Visual:** *Bright, Spirited, Optimistic* — bersih, segar, modern, dan profesional untuk mewadahi beragam kategori acara (Konser Musik, Seminar, Pameran, Festival) tanpa mendominasi warna poster acara.
- **Kualitas Interaktif (High-Craft Liveliness):** Terinspirasi dari presisi interaktif `antislop.ai.id` — setiap elemen interaktif harus memberikan umpan balik taktil yang jelas (*tactile press*, *smooth spring motion*, *live state feedback*), bebas dari tampilan statis maupun elemen dekoratif berlebihan (*zero AI slop*).
- **Aturan Anti-Slop:**
  1. **Dilarang menggunakan emoji melayang dekoratif** (seperti `🎉 Platform #1`) atau klaim pemasaran hiperbolis generik. Gunakan ikon vektor SVG (Lucide/Heroicons) yang tajam dan konsisten.
  2. **Tidak boleh ada gambar rusak (*broken image*):** Setiap elemen `<img>` poster event wajib memiliki mekanisme fallback otomatis (`onerror`) ke kartu gradien tipografis berinisial event agar tata letak tidak pernah terlihat rusak.
  3. **Hierarki Membaca Natural:** Logo berada di sisi kiri navbar, menu navigasi dan tombol aksi berada di sisi kanan.

---

## 2. Token Warna (Deterministic Color Palette)

Gunakan CSS Custom Properties berikut pada `:root` di seluruh file CSS (`style.css`, `eo.css`, `admin.css`):

```css
:root {
    /* Surface & Background (Off-White Bersih) */
    --bg-main: #F8FAFC;
    --bg-subtle: #F3F4F6;
    --bg-surface: #FFFFFF;
    --bg-glass: rgba(248, 250, 252, 0.85);

    /* Brand & Primary Action (Vivid Cyan) */
    --primary: #38BDF8;
    --primary-hover: #0EA5E9;
    --primary-active: #0284C7;
    --primary-soft: rgba(56, 189, 248, 0.14);
    --primary-ring: rgba(56, 189, 248, 0.30);

    /* Secondary & Neutral Typography (Slate / Charcoal) */
    --text-heading: #0F172A;     /* Slate 900 - Kontras tinggi untuk judul */
    --text-body: #334155;        /* Slate 700 - Nyaman dibaca */
    --text-muted: #64748B;       /* Slate 500 - Label & metadata */
    --border-subtle: #E2E8F0;    /* Slate 200 */
    --border-strong: #CBD5E1;    /* Slate 300 */

    /* Status Semantic Colors (Wajib Sesuai Activity Diagram) */
    /* Published / Verified / Success -> Soft Emerald/Mint */
    --status-success-bg: #DCFCE7;
    --status-success-text: #166534;
    --status-success-border: #86EFAC;

    /* Pending Review / Draft -> Warm Amber/Warning */
    --status-warning-bg: #FEF3C7;
    --status-warning-text: #92400E;
    --status-warning-border: #FCD34D;

    /* Rejected / Invalid / Error -> Soft Rose */
    --status-danger-bg: #FEE2E2;
    --status-danger-text: #991B1B;
    --status-danger-border: #FCA5A5;

    /* Elevation & Radius Scale */
    --radius-sm: 8px;
    --radius-md: 12px;
    --radius-lg: 16px;
    --radius-pill: 9999px;
    --shadow-card: 0 4px 20px -2px rgba(15, 23, 42, 0.05);
    --shadow-card-hover: 0 14px 30px -6px rgba(15, 23, 42, 0.10), 0 0 0 1px rgba(56, 189, 248, 0.45);
    --ease-spring: cubic-bezier(0.16, 1, 0.3, 1);
}
```

### Aturan Kontras & Aksesibilitas (WCAG AA)
- **Tombol Vivid Cyan (`#38BDF8`):** Wajib menggunakan teks gelap **`#0F172A` (Slate 900)** dengan `font-weight: 700` agar rasio kontras mencapai **> 8.5:1** (sangat tajam dan mudah terbaca dibandingkan teks putih di atas biru muda).

---

## 3. Tipografi & Skala Layout
- **Font Utama:** `'Plus Jakarta Sans', 'Inter', system-ui, -apple-system, sans-serif`.
- **Angka Tabular:** Gunakan `font-variant-numeric: tabular-nums;` pada semua nominal harga tiket (`Rp ...`), jumlah kuota, statistik dashboard, dan timer.
- **Whitespace:** Berikan ruang napas yang lega (`padding` section minimal `48px 0` pada desktop, `gap: 24px` pada grid kartu event).

---

## 4. Sistem Interaktivitas & Mikro-Animasi (Liveliness Toolkit)

Agar website terasa hidup dan responsif seperti aplikasi web modern, terapkan komponen interaktif berikut:

1. **Sticky Glassmorphic Navbar:**
   - Saat halaman di-*scroll* (`> 20px`), navbar bertransisi halus menjadi `background: var(--bg-glass)`, `backdrop-filter: blur(12px)`, dengan garis bawah `1px solid var(--border-subtle)`.
2. **Tactile Button Feedback:**
   - Semua tombol CTA (`Buat Event`, `Checkout`, `Login`, `Cari`) memiliki transisi `transform 0.2s var(--ease-spring)`.
   - Hover: sedikit terangkat (`translateY(-1px)`) dengan warna `--primary-hover`.
   - Active/Click: efek tekan fisik (`transform: scale(0.97)`).
   - Ikon panah di dalam tombol bergeser ke kanan (`translateX(4px)`) saat tombol di-hover.
3. **Interactive Category Filter Pills (Halaman Home):**
   - Sediakan bar *pill tabs* interaktif di atas/bawah kotak pencarian (`Semua`, `Konser`, `Seminar`, `Pameran`, `Festival`).
   - Klik kategori akan langsung memfilter kartu event secara instan di layar dengan animasi transisi halus (tanpa merusak fitur pencarian `GET` dari form utama).
4. **Event Card Spotlight & Poster Fallback:**
   - Kartu event memiliki transisi `transform: translateY(-5px)` dan bayangan `--shadow-card-hover` saat di-hover, disertai efek zoom halus (`scale(1.04)`) pada gambar poster di dalam wrapper `overflow: hidden`.
   - Jika gambar poster gagal dimuat, tampilkan otomatis placeholder gradien cyan-slate dengan nama event dan kategori agar tidak pernah tampil ikon gambar pecah.
5. **Staggered Scroll Reveal:**
   - Gunakan `IntersectionObserver` ringan untuk memunculkan kartu event dan kartu statistik secara bertahap (`opacity: 0 -> 1`, `translateY(14px) -> 0`) saat memasuki *viewport*.
6. **Interactive Ticket Stepper & Live Calculator (Detail & Checkout):**
   - Gunakan tombol `-` dan `+` interaktif untuk memilih jumlah tiket dengan pembaruan **Total Harga** secara *real-time* di layar sebelum menekan tombol Checkout.
7. **Interactive Document Check & Status Feedback (Dashboard EO & Admin):**
   - Pada halaman verifikasi Admin, sediakan indikator visual interaktif (Hijau/Valid vs Merah/Invalid) saat Admin meninjau dokumen/event sebelum menekan tombol keputusan akhir.
   - Pada Dashboard EO, tombol tambah/hapus kategori tiket bekerja dinamis dengan animasi halus dan penghitung total kuota otomatis.

---

## 5. Penyelarasan dengan Activity Diagram & Guardrails Laravel

Perombakan UI/UX **DILARANG** mengubah logika backend dan alur status yang sudah terhubung dengan *Activity Diagram*:

1. **Halaman Publik (`layouts/main.blade.php`, `home.blade.php`, `detail.blade.php`, `checkout_tiket.blade.php`):**
   - Pertahankan alur: *Tampil Event `Published` -> Cari/Filter Keyword & Kategori -> Pilih Detail Event -> Pilih Tiket & Checkout*.
2. **Dashboard Event Organizer (`layouts/eo.blade.php`, `EO/dashboard.blade.php`, `EO/events/*`):**
   - Pertahankan struktur *sidebar* responsif (bisa dibuka-tutup pada layar mobile).
   - Pertahankan status lifecycle event: `Draft`, `Pending Review` (`pending`), `Published`, dan `Rejected` (menampilkan catatan alasan penolakan dari Admin).
   - Pertahankan alur form pembuatan/edit event & tiket beserta validasi Laravel (`@error`) dan token keamanan (`@csrf`, `@method`).
3. **Dashboard Administrator (`layouts/admin.blade.php`, `Admin/dashboard.blade.php`, `Admin/kyc/*`, `Admin/events/*`):**
   - Pertahankan navbar seragam di seluruh halaman Admin.
   - Pertahankan alur verifikasi KYC EO dan verifikasi kelayakan Event (Approve -> `Published` / Reject + catatan alasan -> `Rejected`).