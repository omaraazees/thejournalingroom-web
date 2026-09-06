# Laporan Font & Logo (kartu P-4) — 2026-09-07

Rujukan: `desain/AUDIT-PERFORMA-0907.md` bagian (d) Font & font-display dan item #2/#4 di daftar temuan.

## (A) 4 (sebenarnya 6) request font lokal 404

**Sumber sebenarnya:** bukan `style.css`, tapi `theme.json` — `settings.typography.fontFamilies[].fontFace[].src` mendeklarasikan `file:./assets/fonts/{playfair-display-italic-400,500,600, manrope-400,500,700}.woff2`. Folder `assets/fonts/` kosong (dikonfirmasi lewat `tjr_v5_font_lokal_ada()` yang sudah ada di `functions.php`), jadi WordPress tetap mencetak keenam `@font-face` itu ke stylesheet global, browser mencoba memuatnya, gagal, baru jatuh ke family yang sama dari Google Fonts CDN (`tjr_v5_enqueue()`) yang memang berhasil. Audit menangkap 4 dari 6 karena cuma itu yang ketriger teks di 3 halaman yang diuji; playfair-italic-500/600 sama-sama mati kalau ada halaman lain yang memakainya.

**Keputusan: BUANG**, bukan sediakan berkasnya. Tidak ada satu pun file `.woff2` di seluruh repo — self-host tidak pernah selesai — dan Google Fonts CDN sudah menjadi jalur yang benar-benar dipakai dan bekerja. Membuat 6 file woff2 sekarang untuk font pihak ketiga hanya menambah maintenance tanpa manfaat, dan sesuai instruksi (assets/fonts bukan aset visual worker lain) tidak menyalahi batas berkas.

**Cara buang:** `theme.json` ada di luar daftar berkas yang boleh kusentuh, jadi bukannya edit file itu, kutambahkan filter `wp_theme_json_data_theme` di `functions.php` (`tjr_v5_buang_font_face_mati()`) yang menyaring `fontFace` per-berkas dengan `file_exists()` sebelum WordPress mencetak stylesheet global. Kalau file lokal ditaruh di `assets/fonts/` nanti, deklarasinya otomatis ikut lagi tanpa perlu ubah filter ini.

## (B) Logo header `tjr-mark.png`

Bug ada di `tjr_v5_logo_cadangan()` (`functions.php`) — fungsi fallback logo yang jalan kalau situs belum punya Custom Logo di Media Library. Hanya mencetak atribut `width` dari attrs blok (`52` di `header.html`, `64` di `footer.html`), tidak pernah mencetak `height`.

Diukur pakai Pillow: `assets/img/tjr-mark.png` = **400×207px** (rasio 1.9324:1), `@2x` = 800×413px. Bukan menghardcode angka, `tinggi` dihitung runtime dari `getimagesize()` berkas asli × rasio `width` blok — jadi kalau asetnya diresize lagi nanti (folder itu milik worker lain), tinggi ikut menyesuaikan sendiri. Hasil: `width=52`→`height=27`, `width=64`→`height=33`.

## Bukti nol 404 + rasio benar (Playwright, situs live)

**Sebelum fix** (baseline, dicatat sebelum commit — 4 request 404 persis sesuai audit):
```
manrope-400.woff2 → 404
manrope-500.woff2 → 404
manrope-700.woff2 → 404
playfair-display-italic-400.woff2 → 404
logo width="52" height=null (rendered 89×46) | width="64" height=null (rendered 124×64)
```

**Sesudah deploy** (menyusul — deploy FTP ada di luar wewenangku, lihat pesan ke god): akan diverifikasi ulang dengan skrip Playwright yang sama begitu `functions.php` tayang, hasil ditempel di sini.

## Berkas
- Kode: `wordpress/theme-v5/functions.php`
- Laporan: `desain/LAPORAN-FONT-LOGO-0907.md`
