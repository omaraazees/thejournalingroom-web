---
title: Audit Performa thejournalingroom.id — 2026-09-07
kartu: P-2
kerja: Kevin (worker-kevin-perf-audit)
metode: Playwright MCP (browser_network_requests + performance.getEntriesByType('resource') via browser_evaluate), lebar 1440px & 390px, tiap halaman di-scroll penuh dulu supaya semua img[loading=lazy] ikut termuat sebelum diukur.
status: PENGUKURAN MURNI — NOL PERUBAHAN KODE. Semua rekomendasi butuh persetujuan god sebelum dieksekusi.
---

# Audit Performa — thejournalingroom.id

Diukur 2026-09-07. Situs belum pernah diaudit performa sebelumnya. Tiga halaman: Beranda (`/`), `/jadwal/`, `/acara/embracing-growth/`, masing-masing di lebar 1440px dan 390px.

## Keterbatasan alat (baca sebelum pakai angka di bawah)

- **`transferSize` tidak bisa dipakai lintas-halaman** karena di dalam satu sesi Playwright, aset yang sama (logo, font, CSS, JS, dan beberapa foto tema yang dipakai ulang) sudah ke-cache begitu halaman kedua dst. dibuka, jadi `transferSize`-nya jatuh ke 0. Untuk itu semua angka "berat" di laporan ini pakai `encodedBodySize` (ukuran file asli di server, apa adanya) — itu tetap valid meski di-cache, dan lebih representatif untuk kunjungan pertama seorang pengunjung baru.
- Situs punya anti-bot: request Playwright pertama ke `/` sempat dijawab 403 ("Checking your browser..."), lolos di percobaan kedua. Setelah itu tidak ada 403 lagi sepanjang audit (kemungkinan tantangan JS sekali per sesi). Tidak ada indikasi kena rate-limit selama audit ini.
- Belum sempat mengukur **Core Web Vitals field/lab** (LCP/CLS/INP terukur, bukan proxy) — Playwright MCP yang tersedia tidak punya integrasi Lighthouse/CrUX di sesi ini. Tabel oversize-image di bawah adalah proxy kuat untuk potensi LCP/CLS tapi bukan angka CWV resmi.
- `font-display` diambil dari `document.fonts` (FontFace API), bukan dari isi CSS langsung — cukup akurat untuk kasus ini karena semua status konsisten.
- Potensi hemat gambar dihitung dari rasio luas piksel (`naturalWidth×naturalHeight` vs `renderWidth×renderHeight×devicePixelRatio`) dikali ukuran file — estimasi proporsional, bukan hasil kompresi ulang aktual. `devicePixelRatio` browser Playwright = 1 di kedua lebar yang diuji.

---

## (a) Ringkasan berat & jumlah request per halaman per lebar

| Halaman | Lebar | Requests | Berat total (encoded) | gambar | font | css | js/lainnya |
|---|---|---|---|---|---|---|---|
| Beranda (/) | 1440px | 45 | **6.01 MB** (6150.5 KB) | 6107.7 KB (34 req) | 0.0 KB* (4 req 404) | 0.9 KB | 42.0 KB |
| Beranda (/) | 390px | 47 | **6.15 MB** (6295.1 KB) | 6190.3 KB (34 req) | 62.0 KB (6 req) | 0.9 KB | 42.0 KB |
| /jadwal/ | 1440px | 19 | **1.11 MB** (1133.8 KB) | 1028.0 KB (6 req) | 62.0 KB (6 req) | 0.9 KB | 43.0 KB |
| /jadwal/ | 390px | 19 | **1.11 MB** (1133.8 KB) | 1028.0 KB (6 req) | 62.0 KB (6 req) | 0.9 KB | 43.0 KB |
| /acara/embracing-growth/ | 1440px | 19 | **1.32 MB** (1350.1 KB) | 1244.3 KB (7 req) | 62.0 KB (5 req) | 0.9 KB | 43.0 KB |
| /acara/embracing-growth/ | 390px | 20 | **1.40 MB** (1434.3 KB) | 1328.5 KB (8 req) | 62.0 KB (5 req) | 0.9 KB | 43.0 KB |

\* Font 0.0 KB di baris Beranda@1440 karena 4 request font lokal itu 404 (0 byte body) — lihat bagian Font & Console Error.

**Gambar mendominasi 91–98% berat setiap halaman di kedua lebar.** Berat tidak banyak berubah antar lebar (390 vs 1440) karena situs **tidak memakai `srcset`/`sizes` responsif** untuk foto-foto tema (`wp-content/themes/tjr-v5/assets/img/*.jpg`) — file yang sama, ukuran penuh, dikirim baik di layar 390px maupun 1440px.

---

## (b) 10 aset terberat (unik per URL, berdasarkan ukuran file asli)

| # | Ukuran | Jenis | URL |
|---|---|---|---|
| 1 | 448.8 KB | jpg | `wp-content/themes/tjr-v5/assets/img/sundayreads-08.jpg` |
| 2 | 447.3 KB | jpg | `wp-content/themes/tjr-v5/assets/img/artotel-16.jpg` |
| 3 | 446.4 KB | jpg | `wp-content/themes/tjr-v5/assets/img/pasar-jakal-02.jpg` |
| 4 | 402.0 KB | jpg | `wp-content/themes/tjr-v5/assets/img/sundayreads-27.jpg` |
| 5 | 371.1 KB | jpg | `wp-content/themes/tjr-v5/assets/img/kolondjono-20.jpg` |
| 6 | 347.2 KB | jpg | `wp-content/themes/tjr-v5/assets/img/wardah-09.jpg` |
| 7 | 346.9 KB | jpg | `wp-content/themes/tjr-v5/assets/img/amco-naoki-03.jpg` |
| 8 | 336.8 KB | jpg | `wp-content/themes/tjr-v5/assets/img/snapobox-08.jpg` |
| 9 | 317.7 KB | jpg | `wp-content/themes/tjr-v5/assets/img/artotel-13.jpg` |
| 10 | 309.5 KB | jpg | `wp-content/themes/tjr-v5/assets/img/artotel-19.jpg` |

Semua 10 aset terberat situs adalah foto JPEG di folder tema (bukan upload WP), semuanya tampil di grid klien/galeri Beranda, dan beberapa dipakai ulang di kartu `/jadwal/`. Konsisten dengan info awal: baru di-upscale lewat Magnific (kartu U-16) — dimensi asli 896–1600px padahal tampil sebagai thumbnail 114–320px.

---

## (c) Gambar yang dikirim jauh lebih besar dari ukuran tampilnya

31 file gambar unik terdeteksi over-sized (rasio piksel terkirim vs piksel tampil > 1.3×). Diurutkan dari potensi hemat terbesar (dedup per file — satu file dipakai di beberapa lebar/halaman, tapi perbaikannya cukup sekali):

| File | Natural (px) | Tampil (px) | Ukuran file | Rasio | Potensi hemat | loading |
|---|---|---|---|---|---|---|
| artotel-16.jpg | 900×1600 | 114×114 | 447.6 KB | 7.89× | **443.6 KB** | lazy |
| sundayreads-08.jpg | 1196×1600 | 156×156 | 448.8 KB | 7.67× | **443.1 KB** | lazy |
| pasar-jakal-02.jpg | 1600×896 | 140×140 | 446.4 KB | 11.43× | **440.3 KB** | lazy |
| sundayreads-27.jpg | 1196×1600 | 119×119 | 402.3 KB | 10.05× | **399.3 KB** | lazy |
| kolondjono-20.jpg | 896×1600 | 140×140 | 371.1 KB | 6.4× | **366.0 KB** | lazy |
| amco-naoki-03.jpg | 896×1600 | 144×144 | 347.2 KB | 6.22× | **342.2 KB** | lazy |
| wardah-09.jpg | 896×1600 | 156×156 | 347.2 KB | 5.74× | **341.3 KB** | lazy |
| snapobox-08.jpg | 896×1600 | 140×140 | 336.8 KB | 6.4× | **332.2 KB** | lazy |
| artotel-13.jpg | 1600×896 | 263×197 | 317.7 KB | 6.08× | **306.2 KB** | lazy |
| artotel-19.jpg | 896×1600 | 140×140 | 309.5 KB | 6.4× | **305.3 KB** | lazy |
| pasar-jakal-06.jpg | 896×1600 | 327×184 | 305.4 KB | 2.74× | **292.6 KB** | lazy |
| artotel-08.jpg | 1600×900 | 327×409 (@390px) | 281.6 KB | 4.89× | **255.4 KB** | **eager (None)** |
| radian-30.jpg | 1196×1600 | 149×149 | 244.4 KB | 8.03× | **241.6 KB** | lazy |
| radian-11.jpg | 1196×1600 | 327×245 | 243.1 KB | 3.66× | **232.9 KB** | lazy |
| kupiku-04.jpg | 1600×896 | 146×146 | 232.2 KB | 10.96× | **228.7 KB** | lazy |
| sundayreads-12.jpg | 1196×1600 | 140×140 | 211.6 KB | 8.54× | **209.4 KB** | lazy |
| wardah-04.jpg | 1196×1600 | 144×144 | 184.2 KB | 8.31× | **182.2 KB** | lazy |
| embracing-growth-menulis-jurnal-hd.jpg | 1200×1600 | 546×627 (Beranda) | 216.6 KB | 2.2× | **178.0 KB** | **eager (None)** |
| radian-24.jpg | 1196×1600 | 120×120 | 172.2 KB | 9.97× | **170.9 KB** | lazy |
| logo-radian.webp + 10 logo klien `.webp` lain | 256–300px | 41–120px | 3.9–21.2 KB masing² | 2.5–6.24× | **3.5–20.7 KB masing²** (total ~121 KB) | lazy |
| tjr-mark.png (logo header) | 110×56 | 77–124×40–64 | 11.8 KB | 1.43× | **5.9 KB** | eager (None) |

**Total potensi hemat unik (per file sumber, sekali perbaikan berlaku di semua halaman/lebar): ≈ 5.82 MB (5818.0 KB) dari 31 file.**
(Kalau dihitung per kemunculan di tiap lebar tanpa dedup file, angkanya 10.4 MB — tapi itu double count karena file sumbernya sama.)

Semua foto grid ini **sudah punya atribut `width`/`height` HTML yang benar** (aman dari CLS) — masalahnya murni di ukuran file, bukan reservasi layout. Pengecualian: **`tjr-mark.png`** (logo di header, dipakai 2×) hanya punya atribut `width` (isinya malah nilai tinggi — "52"/"64", bukan lebar asli ~100/124px) dan **tidak ada atribut `height` sama sekali** → berisiko CLS kecil di header/nav, dan atributnya sendiri salah nilai.

---

## (d) Font & font-display

- Font dimuat: **Manrope** (400/500/700), **Playfair Display italic** (400/500/600), **Pinyon Script** (400) — semua via `fonts.googleapis.com/css2?...&display=swap`, jadi `font-display: swap` **sudah aktif** dan bekerja (tidak ada FOIT).
- **Tapi ada 4 request font lokal yang selalu 404** di setiap halaman yang diuji: `wp-content/themes/tjr-v5/assets/fonts/{manrope-400,manrope-500,manrope-700,playfair-display-italic-400}.woff2`. Tema sepertinya punya deklarasi `@font-face` self-host ganda yang menunjuk ke file yang tidak ada, sementara di baris lain memuat family yang sama dari Google Fonts CDN (yang berhasil) — jadi situs tetap tampil benar, tapi tiap page-load membuang 4 request 404 percuma + dependensi ke Google Fonts CDN yang sebenarnya tidak perlu kalau font lokal-nya benar.

## (e) Console error non-pihak-ketiga

Sama persis di ketiga halaman (3–4 error tergantung font yang dipakai halaman itu), semuanya 404 pada file font lokal di atas. Tidak ada error JS lain yang berasal dari domain sendiri.

---

## Rekomendasi, diurutkan dari hemat terbesar (semua butuh gerbang god sebelum eksekusi)

1. **[≈5.82 MB]** Resize + kompres ulang 31 file JPEG/WebP di `wp-content/themes/tjr-v5/assets/img/` (daftar lengkap di tabel (c) — 10 terbesar: `sundayreads-08.jpg`, `artotel-16.jpg`, `pasar-jakal-02.jpg`, `sundayreads-27.jpg`, `kolondjono-20.jpg`, `wardah-09.jpg`, `amco-naoki-03.jpg`, `snapobox-08.jpg`, `artotel-13.jpg`, `artotel-19.jpg`) supaya dimensi file mendekati ukuran tampil terbesarnya (grid thumbnail ≈120–330px, hanya `artotel-08.jpg`/`artotel-13.jpg` yang tampil sampai ~650–1333px). Ini folder aset tema statis (bukan Media Library WP), jadi perlu diproses manual/build-step, bukan lewat WP image sizes otomatis. Sekalian convert ke WebP/AVIF (siblingnya, logo klien, sudah `.webp` — pola sama tinggal diikuti) untuk hemat tambahan di luar angka 5.82 MB di atas.
2. **[tanpa KB pasti, tapi menghapus 4 request 404 di *setiap* page-load situs]** Tema `tjr-v5` — cek CSS `@font-face` yang menunjuk `assets/fonts/manrope-400.woff2`, `manrope-500.woff2`, `manrope-700.woff2`, `playfair-display-italic-400.woff2`. Salah satu: (a) upload file `.woff2` yang hilang supaya self-host jalan lalu hapus link Google Fonts (lebih cepat, tanpa DNS lookup ke domain lain), atau (b) hapus saja deklarasi `@font-face` lokal yang mati kalau memang situs sudah mengandalkan Google Fonts CDN.
3. **[≈121 KB + minor CLS]** 11 logo klien `.webp` (`logo-radian.webp`, `logo-amco-300x245.webp`, dst., daftar di tabel (c)) di-generate 300px tapi tampil 41–120px — bisa dirapikan sekalian dengan item #1 karena satu batch proses yang sama.
4. **[≈6 KB, tapi bug markup]** `tjr-mark.png` (logo header, 2 kemunculan) — atribut `width` bernilai salah (kepasang nilai tinggi) dan atribut `height` tidak ada sama sekali. Perbaiki markup: set `width`/`height` sesuai ukuran render asli (≈100×52 dan 124×64) supaya reservasi layout benar dan tidak berisiko CLS di header.

**Total potensi hemat berat halaman terukur: ≈ 5.82 MB dari gambar oversized (item 1+3), di luar penghematan tambahan dari konversi format dan hilangnya 4 request 404 per halaman (item 2).**
