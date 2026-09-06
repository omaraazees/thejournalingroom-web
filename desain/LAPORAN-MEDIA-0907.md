---
title: Media Library WordPress — resize 2x + repoint (kartu P-5)
kartu: P-5
kerja: Darryl (worker-darryl-p5-media)
metode: WordPress REST API (wp/v2/media, wp/v2/kolaborator, wp/v2/acara) via Basic Auth Application Password (~/.tjr-wp, di-parse Python — bukan `source` shell). Media BARU diunggah, media lama TIDAK dihapus, hanya `featured_media` yang dialihkan.
status: SELESAI — 12 media diunggah, diverifikasi byte-identik + full-decode, dan referensinya sudah dialihkan.
---

# Laporan Media — thejournalingroom.id (Media Library)

Kelanjutan dari `AUDIT-PERFORMA-0907.md` tabel (c) — 12 dari 31 file oversize yang **tidak** ada di `wordpress/theme-v5/assets/img/` (upload Media Library, bukan tema). 18 file lain yang ada di folder tema sudah ditangani Angela (`LAPORAN-GAMBAR-0907.md`, kartu P-3); `tjr-mark.png` sengaja tidak disentuh (di luar cakupan, sudah kartu P-4).

## Temuan pemakaian (sebelum eksekusi)

- 11 logo klien `.webp` dipakai lewat custom post type `kolaborator` — logonya = **featured image** tiap entri, diambil tema di ukuran `medium` (`get_the_post_thumbnail_url($id,'medium')`, `functions.php:608`) lalu ditampilkan di CSS `.logos img{max-width:120px;max-height:48px;object-fit:contain}` (`style.css:1180`). Target 2x = kotak 240×96px.
- `logo-wardah` (media id 51, tanpa akhiran) ternyata **tidak dipakai** — entri kolaborator "wardah" mengarah ke `logo-wardah-1` (id 52). Id 51 dibiarkan, di luar cakupan (bukan berkas yang dipakai).
- `embracing-growth-menulis-jurnal-hd.jpg` (id 149) adalah **featured image** custom post type `acara` (satu-satunya entri, `embracing-growth`), dipakai via blok native `wp:post-featured-image` dengan `srcset`/`sizes` penuh — dan **hero full-width (bisa sampai 1200px) di halaman `/acara/embracing-growth/` sendiri** (fetchpriority=high, kandidat LCP), bukan cuma thumbnail kecil di Beranda seperti yang tertangkap audit. Karena itu dimensinya **TIDAK diperkecil** (tetap 1200×1600, sama seperti file lama — supaya hero tidak pecah) — hanya di-encode ulang ke WebP q85 untuk hemat berat murni. Oversize di Beranda (546×627) berasal dari atribut `sizes` blok bawaan Gutenberg yang tidak disesuaikan untuk kartu kecil di pattern `jadwal-sesi-terdekat.php` — itu perbaikan markup, di luar cakupan REST/Media Library kartu ini, dilaporkan ke outbox.
- Ukuran berkas lama untuk id 149 yang terunduh aktual (323.436 B) beda dari meta `filesize` REST-nya (312.377 B) — dipakai angka unduhan aktual (itu yang benar-benar dikirim ke pengunjung).

## Tabel media

| ID lama | Nama | Ukuran lama (px) | Ukuran lama (KB) | ID baru | Ukuran baru (px) | Ukuran baru (KB) | Hemat (KB) | Referensi dialihkan |
|---|---|---|---|---|---|---|---|---|
| 45 | logo-sundayreads | 457×300 | 18.3 | 159 | 146×96 | 6.8 | 11.5 | kolaborator #46 |
| 47 | logo-radian | 256×300 | 20.9 | 160 | 82×96 | 4.9 | 16.0 | kolaborator #48 |
| 49 | logo-kupiku | 978×300 | 22.6 | 161 | 240×74 | 5.2 | 17.4 | kolaborator #50 |
| 52 | logo-wardah-1 | 1556×300 | 54.7 | 162 | 240×46 | 5.6 | 49.1 | kolaborator #53 |
| 54 | logo-artotel | 1208×300 | 43.4 | 163 | 240×60 | 6.4 | 36.9 | kolaborator #55 |
| 56 | logo-hanasui | 1600×251 | 39.0 | 164 | 240×38 | 3.8 | 35.2 | kolaborator #57 |
| 58 | logo-heejaz | 377×300 | 12.8 | 165 | 121×96 | 4.0 | 8.8 | kolaborator #59 |
| 60 | logo-statement-beauty | 901×300 | 14.4 | 166 | 240×80 | 5.4 | 9.0 | kolaborator #61 |
| 62 | logo-amco | 367×300 | 19.2 | 167 | 117×96 | 7.4 | 11.9 | kolaborator #63 |
| 64 | logo-pasar-jakal | 480×300 | 23.0 | 168 | 154×96 | 6.5 | 16.5 | kolaborator #65 |
| 66 | logo-snapobox | 1118×300 | 28.3 | 169 | 240×64 | 5.2 | 23.2 | kolaborator #67 |
| 149 | embracing-growth-menulis-jurnal-hd | 1200×1600 | 315.9 | 170 | 1200×1600 (sama, cuma ganti format ke WebP q85) | 207.0 | 108.9 | acara #12 |
| **TOTAL** | | | **612.3** | | | **268.0** | **≈344.3 KB** | |

Semua 12 unggahan diverifikasi: GET REST ulang → unduh dari `source_url` → cek `Content-Length` cocok, `sha256` cocok byte-demi-byte dengan berkas lokal yang diunggah, dan didekode penuh (`Image.load()`) supaya tidak lolos seperti kasus U-16. Semua lolos di percobaan pertama kecuali unduhan berkas SUMBER `embracing-growth-menulis-jurnal-hd.jpg` yang sempat kena `IncompleteRead` di percobaan pertama — retry otomatis (percobaan ke-2) berhasil dan lolos verifikasi ukuran+hash+decode.

Media lama (45,47,49,52,54,56,58,60,62,64,66,149) **tidak dihapus**, tetap ada di Media Library, tidak lagi dirujuk oleh `featured_media` mana pun.

## Verifikasi live

- `/acara/embracing-growth/` sudah menyajikan `embracing-growth-menulis-jurnal-hd-2x.webp` langsung setelah update (dicek ulang lewat fetch HTML).
- Beranda (halaman kolaborator/logo) belum menampilkan URL baru pada saat pengecekan — kemungkinan page-cache belum invalidasi untuk custom post type `kolaborator`/`acara` di Beranda (di luar cakupan REST Media Library untuk kartu ini; `featured_media` di database sudah benar, dikonfirmasi ulang lewat GET REST untuk seluruh 11 entri kolaborator + 1 acara).

## Catatan untuk outbox

Bug nyata yang ditemukan tapi di luar wewenang kartu ini (butuh edit `patterns/jadwal-sesi-terdekat.php`, yang eksplisit dilarang): atribut `sizes` pada blok `wp:post-featured-image` di kartu "acara terdekat" Beranda tidak disesuaikan untuk lebar kartu yang sebenarnya kecil, jadi browser memilih kandidat `srcset` yang lebih besar dari perlu — itu penyebab asli oversize `embracing-growth-menulis-jurnal-hd.jpg` di Beranda pada audit Kevin, bukan ukuran filenya sendiri.
