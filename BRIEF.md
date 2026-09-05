# BRIEF — The Journaling Room, situs WordPress

Dibaca oleh 4 agent. Kerjakan HANYA lane kamu. Jangan sentuh folder lane lain.

## Konteks

**The Journaling Room (TJR)** adalah penyelenggara workshop journaling di Yogyakarta.
Founder: Caca dan Dhanty. IG `@thejournalingroom`. WhatsApp `+62 857 2822 5369`.
Positioning: bukan kelas teknik menulis, tapi ruang aman untuk journaling bareng.
Berpindah venue tiap acara, dari kafe sampai hotel.

Situsnya akan dibangun di **WordPress** (block theme + Gutenberg, BUKAN Elementor),
di-hosting di **Hostinger**. Prioritas pemilik: SEO dan accessibility jangka panjang,
plus CMS yang bisa diurus Caca dan Dhanty sendiri tanpa nyentuh kode.

## Baca dulu sebelum mulai (read only, jangan diedit)

| File | Isi |
|---|---|
| `journaling-room-web/brand-brief.md` | Hasil scrape IG: positioning, 6 format acara, harga, partner, arah visual |
| `journaling-room-web/desain/prototipe/tokens.css` | Token final: warna, tipografi, spacing, bentuk. WAJIB dipakai |
| `journaling-room-web/desain/prototipe/final-beranda.html` | Beranda final yang sudah disetujui. Ini acuan pola markup dan gaya |
| `journaling-room-web/desain/prototipe/index.html` | Peta halaman, struktur CMS, peta ke WordPress |

## Aturan untuk semua lane

1. **Pakai token yang sudah ada.** Jangan bikin warna, font, atau skala spacing baru.
   Semua nilai ada di `tokens.css`. Kalau butuh nilai baru, tulis alasannya di catatan, jangan diam-diam.
2. **Bahasa Indonesia** untuk semua copy. Nama tema acara tetap Inggris.
3. **Dilarang em dash.** Dilarang frasa AI slop (dive into, seamless, unlock, elevate, robust, comprehensive, dll).
   Tulis seperti manusia, langsung, tidak berlebihan.
4. **Accessibility WCAG AA minimum.** Satu H1 per halaman, hierarki heading tidak lompat,
   landmark semantik, target sentuh minimal 44px, kontras teks minimal 4.5:1.
5. **Nol library JS.** Kalau butuh interaksi, pakai CSS atau paling banyak beberapa baris vanilla JS.
6. **Jangan pakai Figma MCP maupun Chrome MCP.** Orkestrator yang pegang keduanya.
7. **Konten asli, bukan lorem.** Ambil fakta dari `brand-brief.md`. Kalau datanya belum ada,
   tulis placeholder yang jelas ditandai, jangan mengarang angka yang kelihatan asli.
8. Selesai kerja, tulis ringkasan singkat di `journaling-room-web/LAPORAN-<lane>.md`:
   apa yang dibuat, keputusan yang diambil, dan apa yang masih nunggu orang.

---

## A1 · Halaman Dalam
**Tulis ke:** `journaling-room-web/desain/halaman/`

Bikin HTML halaman selain beranda, pakai `tokens.css` (link relatif ke `../wp-prototype/tokens.css`)
dan pola markup yang sama dengan `final-beranda.html`. Header dan footer disalin konsisten.

- `jadwal.html` — arsip semua acara, filter format dan kota, pemisah acara mendatang vs arsip
- `acara-detail.html` — satu sesi: hero foto, tanggal, venue plus link Maps, harga, isi kit,
  sisa slot, tombol WhatsApp, galeri sesi, acara lain yang mirip
- `tentang.html` — cerita Caca dan Dhanty, cara kerja sesi, foto, nilai brand
- `kolaborasi.html` — halaman B2B untuk brand: format brand activation, contoh kerja
  (Wardah, Artotel, Copenhagen), apa yang didapat brand, form brief
- `kontak.html` — WhatsApp, Instagram, area layanan, FAQ pendek
- `cerita.html` — indeks blog, kategori: Panduan journaling, Di balik sesi, Kolaborasi
- `cerita-detail.html` — satu artikel, lebar baca maksimal 65 karakter, daftar isi, artikel terkait

Tiap section kasih penanda `<span class="wp">pattern: tjr/nama</span>` di dalam `.wp-host`,
sama persis polanya dengan beranda final.

---

## A2 · WordPress Theme
**Tulis ke:** `journaling-room-web/wordpress/theme/`

Bikin child theme block-based untuk GeneratePress. Semua file siap di-zip dan diupload.

- `style.css` — header child theme yang benar
- `theme.json` — versi 3. Terjemahkan `tokens.css` jadi settings resmi:
  `color.palette` (9 warna TJR), `typography.fontFamilies` (Cormorant Garamond, Inter,
  Petit Formal Script) plus `fontSizes` dari skala step, `spacing.spacingSizes` dari skala sp,
  `layout.contentSize` 720px dan `wideSize` 1160px. Matikan custom color picker biar
  Caca dan Dhanty nggak bisa keluar dari palet
- `functions.php` — daftarkan CPT `acara`, taksonomi `format-acara` dan `kota`,
  enqueue font, daftarkan block pattern, daftarkan pattern category `tjr`
- `patterns/*.php` — tiap section beranda jadi satu file pattern.
  Nama harus cocok dengan penanda `pattern: tjr/*` di `final-beranda.html`
- `assets/tjr.css` — CSS yang nggak bisa diwakili theme.json: tekstur kertas,
  animasi scroll-driven, selotip, rotasi foto

Catat di laporan: mana yang bisa diatur lewat editor dan mana yang terkunci di kode.

---

## A3 · Konten dan SEO
**Tulis ke:** `journaling-room-web/konten/`

- `keyword-research.md` — keyword yang realistis dikejar TJR di Jogja. Kelompokkan per intent:
  orang yang nyari kegiatan ("kegiatan akhir pekan jogja"), yang nyari kelas
  ("workshop journaling jogja"), yang nyari solusi ("cara mulai journaling").
  Kasih perkiraan tingkat kesulitan dan halaman mana yang menargetkannya.
  Tandai jelas mana angka perkiraanmu dan mana yang butuh diverifikasi di Search Console nanti
- `meta-per-halaman.md` — title tag (maks 60 karakter) dan meta description (maks 155)
  untuk 8 halaman, plus pola otomatis untuk CPT acara
- `copy-halaman.md` — copy final halaman Tentang, Kolaborasi, dan Kontak.
  Suara brand: hangat, tenang, personal. Lihat kutipan founder di `brand-brief.md`
- `outline-blog.md` — 8 artikel pertama, tiap satu: judul, target keyword, outline H2,
  panjang target, dan internal link ke halaman mana
- `struktur-url.md` — pola permalink untuk semua tipe konten, plus aturan slug

---

## A4 · CMS dan Data
**Tulis ke:** `journaling-room-web/wordpress/cms/`

Target: Caca dan Dhanty bisa nambah acara baru dalam 5 menit tanpa nanya siapa pun.

- `acf-fields.json` — field group ACF siap import untuk CPT `acara`:
  tanggal mulai, durasi, venue plus link Maps, harga, catatan harga, kapasitas,
  slot terisi, isi kit, galeri sesi. Pakai format export ACF yang benar
- `logika-status.md` — aturan status otomatis dari tanggal dan slot:
  Buka, Hampir penuh (sisa di bawah 25 persen), Penuh, Selesai.
  Tulis juga potongan PHP-nya
- `schema-event.md` — JSON-LD `Event` plus `Offer` yang dibangun dari field ACF,
  lengkap dengan potongan PHP untuk `wp_head`. Ini yang bikin sesi TJR bisa muncul
  di daftar acara Google, jadi kerjakan serius
- `whatsapp-link.md` — generator link WhatsApp otomatis berisi nama acara dan tanggal,
  plus potongan PHP-nya
- `data-acara-contoh.json` — 6 acara contoh dari data asli di `brand-brief.md`
- `panduan-caca-dhanty.md` — panduan langkah per langkah nambah acara baru,
  ditulis untuk orang yang belum pernah pakai WordPress. Tanpa jargon.
