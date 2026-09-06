---
title: Resize + WebP gambar tema — 2026-09-07
kartu: P-3
kerja: Angela (worker-angela-p3-gambar)
sumber: desain/AUDIT-PERFORMA-0907.md, tabel (c) — 31 gambar oversize hasil audit Kevin (kartu P-2)
metode: Pillow, resize proporsional ke lebar-tampil-terbesar×2 (dibulatkan ke atas ke kelipatan 100), simpan WebP kualitas 82 DI SAMPING JPEG asli (JPEG tidak ditimpa). Tiap berkas dibuka ulang sesudah disimpan (verify() + load() + baca 3 piksel + cek header RIFF/WEBP) untuk memastikan tidak ada unduhan/tulis terpotong seperti kasus U-16.
status: SELESAI, sudah di-commit ke branch agent/worker-angela-p3-gambar.
---

# Resize + WebP — thejournalingroom.id (kartu P-3)

Cakupan berkas yang boleh disentuh: `wordpress/theme-v5/assets/img/**`, `patterns/**`, `templates/**`. Dari 31 baris di tabel (c) audit Kevin, **18 file JPEG ada di `assets/img/`** dan dikerjakan di sini (+1 variasi tambahan untuk foto hero yang butuh dua ukuran). Sisanya (11 logo klien `.webp`, `embracing-growth-menulis-jurnal-hd.jpg`) hidup di Media Library WP (`wp-content/uploads/`), bukan di folder tema — di luar cakupan berkas kartu ini. `tjr-mark.png` juga dilewati: markup-nya tercetak dari `functions.php` (situs logo cadangan), berkas yang eksplisit dilarang disentuh kartu ini.

## Tabel hemat

| File asli | Ukuran lama (px) | Ukuran lama | File baru | Ukuran baru (px) | Ukuran baru | Hemat |
|---|---|---|---|---|---|---|
| artotel-16.jpg | 900×1600 | 365.5 KB | artotel-16.webp | 300×533 | 46.0 KB | 319.5 KB |
| sundayreads-08.jpg | 1196×1600 | 365.0 KB | sundayreads-08.webp | 400×535 | 52.9 KB | 312.1 KB |
| pasar-jakal-02.jpg | 1600×896 | 384.7 KB | pasar-jakal-02.webp | 300×168 | 19.7 KB | 365.0 KB |
| sundayreads-27.jpg | 1196×1600 | 360.2 KB | sundayreads-27.webp | 300×401 | 27.1 KB | 333.1 KB |
| kolondjono-20.jpg | 896×1600 | 367.4 KB | kolondjono-20.webp | 300×536 | 45.5 KB | 321.9 KB |
| amco-naoki-03.jpg | 896×1600 | 355.0 KB | amco-naoki-03.webp | 300×536 | 45.6 KB | 309.3 KB |
| wardah-09.jpg | 896×1600 | 350.1 KB | wardah-09.webp | 400×714 | 71.4 KB | 278.7 KB |
| snapobox-08.jpg | 896×1600 | 385.2 KB | snapobox-08.webp | 300×536 | 40.7 KB | 344.4 KB |
| artotel-13.jpg | 1600×896 | 380.1 KB | artotel-13.webp | 600×336 | 48.1 KB | 332.0 KB |
| artotel-19.jpg | 896×1600 | 350.2 KB | artotel-19.webp | 300×536 | 35.6 KB | 314.6 KB |
| pasar-jakal-06.jpg | 896×1600 | 353.5 KB | pasar-jakal-06.webp | 700×1250 | 159.7 KB | 193.9 KB |
| radian-30.jpg | 1196×1600 | 334.2 KB | radian-30.webp | 300×401 | 23.4 KB | 310.8 KB |
| radian-11.jpg | 1196×1600 | 342.7 KB | radian-11.webp | 700×936 | 88.8 KB | 253.9 KB |
| kupiku-04.jpg | 1600×896 | 289.7 KB | kupiku-04.webp | 300×168 | 10.2 KB | 279.5 KB |
| sundayreads-12.jpg | 1196×1600 | 306.2 KB | sundayreads-12.webp | 300×401 | 16.5 KB | 289.7 KB |
| wardah-04.jpg | 1196×1600 | 286.6 KB | wardah-04.webp | 300×401 | 19.7 KB | 267.0 KB |
| radian-24.jpg | 1196×1600 | 273.4 KB | radian-24.webp | 300×401 | 18.3 KB | 255.2 KB |
| artotel-08.jpg (varian ponsel, ≤640px) | 1600×900 | 359.5 KB | artotel-08-700.webp | 700×394 | 60.0 KB | 299.5 KB |
| artotel-08.jpg (varian desktop, srcset 1600w) | 1600×900 | 359.5 KB | artotel-08-1600.webp | 1600×900 | 240.3 KB | 119.2 KB |

**TOTAL hemat (17 file konteks tunggal + varian ponsel foto hero, dedup per file sumber): ≈ 5.380,1 KB (5,25 MB).**

Catatan artotel-08.jpg: ini foto hero (`.panggung`, `fetchpriority="high"`), tampil 100% lebar section di kedua breakpoint tapi lebar sebenarnya jauh berbeda (4:5 di ponsel vs 16:9 hampir penuh di desktop) — sesuai instruksi (4), dipasang `srcset` dua ukuran, bukan satu file. Baris "varian ponsel" itu yang masuk TOTAL di atas (kasus dominan dari audit); baris "varian desktop" tetap hemat 119,2 KB dari kompresi WebP meski dimensinya sama dengan aslinya (tidak diperbesar), jadi tidak dihitung dobel di TOTAL karena browser cuma mengunduh satu varian per kunjungan.

Pengecualian yang sengaja TIDAK diresize: **artotel-16.jpg** juga dipakai penuh (~900px lebar, konteks `.pasangan` di `pengantar-kutipan.php`) selain sebagai polaroid 114px di `pita-kolaborator.php` — audit Kevin cuma menangkap pemakaian polaroidnya. Untuk konteks besar itu berkas 900×1600 aslinya TIDAK oversized (rasio ~1×) dan WebP q82 di resolusi itu malah 404,7 KB (lebih besar dari JPEG 365,5 KB) jadi dibuang, bukan dipakai — markup di `pengantar-kutipan.php` tetap merujuk JPEG asli untuk penggunaan itu. Detail dikirim ke god lewat outbox.

## Markup yang diubah

Semua di `wordpress/theme-v5/patterns/`, tidak menyentuh `functions.php`/`inc/**`/`style.css`:

- `pita-kolaborator.php` — polaroid Artotel → `artotel-16.webp`.
- `ajakan-whatsapp.php` — `artotel-13.webp`, `sundayreads-27.webp`, `radian-24.webp`.
- `hero-panggung.php` — `hero_cetakan` → `sundayreads-27.webp`; `hero_foto` → `srcset="artotel-08-700.webp 700w, artotel-08-1600.webp 1600w" sizes="100vw"`.
- `galeri-bento.php` — `bento_1..4` (radian-11, sundayreads-08, wardah-09, pasar-jakal-06) → WebP masing².
- `tumpukan-cetakan.php` — `cetakan_1..9` → WebP masing² (sundayreads-12, radian-30, wardah-04, artotel-19, kolondjono-20, amco-naoki-03, snapobox-08, pasar-jakal-02, kupiku-04).
- `pengantar-kutipan.php` — hanya `pengantar_cetakan` (radian-24) → WebP; `pengantar_foto` (artotel-16) sengaja dibiarkan JPEG (lihat catatan di atas).

Empat pattern yang fotonya diambil lewat `tjr_v5_foto()` (bisa diganti dari ACF/dasbor) pakai penjagaan inline: URL dialihkan ke `.webp` HANYA kalau padanannya memang ada di `assets/img/`; foto yang sudah diganti editor dari Media Library tetap tampil apa adanya, tidak pernah diarahkan ke berkas yang tidak ada.

## Width/height

Tidak ditulis tangan. Semua `<img>` di pattern-pattern ini sudah lewat `tjr_v5_sifat_gambar( $url )` (di `functions.php`, tidak disentuh) yang membaca dimensi asli file lewat `getimagesize()` berdasarkan URL yang sama dipakai di `src`. Karena `src` sekarang menunjuk file `.webp` yang baru, atribut `width`/`height` otomatis ikut jadi ukuran WebP yang sudah diperkecil — tidak ada risiko CLS baru.

## Verifikasi

Tiap 19 berkas WebP: dibuka ulang dengan Pillow (`Image.open().load()`), `Image.verify()` struktural, dibaca 3 piksel (pojok kiri-atas, pojok kanan-bawah, tengah) untuk memaksa decode penuh, plus cek 12 byte header `RIFF....WEBP`. Semua lolos, tidak ada yang terpotong.
