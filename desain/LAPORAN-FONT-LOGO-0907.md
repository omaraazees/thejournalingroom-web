# Laporan Font & Logo (kartu P-4) — 2026-09-07

Rujukan: `desain/AUDIT-PERFORMA-0907.md` bagian (d) Font & font-display dan item #2/#4 di daftar temuan.

## (A) 4 (sebenarnya 6) request font lokal 404

**Sumber sebenarnya:** bukan `style.css`, tapi `theme.json` — `settings.typography.fontFamilies[].fontFace[].src` mendeklarasikan `file:./assets/fonts/{playfair-display-italic-400,500,600, manrope-400,500,700}.woff2`. Folder `assets/fonts/` kosong (dikonfirmasi lewat `tjr_v5_font_lokal_ada()` yang sudah ada di `functions.php`), jadi WordPress tetap mencetak keenam `@font-face` itu ke stylesheet global, browser mencoba memuatnya, gagal, baru jatuh ke family yang sama dari Google Fonts CDN (`tjr_v5_enqueue()`) yang memang berhasil. Audit menangkap 4 dari 6 karena cuma itu yang ketriger teks di 3 halaman yang diuji; playfair-italic-500/600 sama-sama mati kalau ada halaman lain yang memakainya.

**Keputusan: BUANG**, bukan sediakan berkasnya. Tidak ada satu pun file `.woff2` di seluruh repo — self-host tidak pernah selesai — dan Google Fonts CDN sudah menjadi jalur yang benar-benar dipakai dan bekerja. Membuat 6 file woff2 sekarang untuk font pihak ketiga hanya menambah maintenance tanpa manfaat.

**Ronde 1 (commit `a1ab174`→`16bb8a0`):** `theme.json` awalnya di luar daftar berkas kartu ini, jadi kubuang `fontFace`-nya lewat filter `wp_theme_json_data_theme` di `functions.php`. Setelah god deploy dan aku verifikasi live, **logo langsung benar tapi font masih 404** — bukan karena kodenya salah, tapi karena `wp_get_global_stylesheet()` WordPress menyimpan stylesheet global (termasuk `@font-face`) di object cache (grup `theme_json`), dan cache itu cuma dibersihkan otomatis oleh `wp_clean_theme_json_cache()` yang di-hook ke `switch_theme`/`start_previewing_theme` — BUKAN ke perubahan berkas tema. Dikonfirmasi baca langsung source WordPress core (`wp-includes/global-styles-and-settings.php` dan `default-filters.php`).

**Ronde 2 (final, komit ini):** god mengizinkan sentuh `theme.json` langsung. Karena itu jadi solusi yang lebih bersih dari filter run-time, **keenam blok `fontFace` dihapus langsung dari `theme.json`** (nama family + fallback stack lokal tetap ada, cuma deklarasi `@font-face` self-host-nya yang hilang), dan filter `tjr_v5_buang_font_face_mati()` di `functions.php` (jadi redundan) DIHAPUS lagi. Ditambahkan `tjr_v5_bersihkan_cache_gaya_global()` — sekali jalan (dijaga `get_option`/`update_option`, ikut naik otomatis kalau `TJR_V5_VERSION` naik lagi) yang memanggil `wp_clean_theme_json_cache()` di hook `init`, supaya cache lama yang masih berisi 6 `@font-face` mati itu ikut dibuang begitu deploy ini tayang.

## (B) Logo header `tjr-mark.png`

Bug ada di `tjr_v5_logo_cadangan()` (`functions.php`) — fungsi fallback logo yang jalan kalau situs belum punya Custom Logo di Media Library. Hanya mencetak atribut `width` dari attrs blok (`52` di `header.html`, `64` di `footer.html`), tidak pernah mencetak `height`.

Diukur pakai Pillow: `assets/img/tjr-mark.png` = **400×207px** (rasio 1.9324:1), `@2x` = 800×413px. Bukan menghardcode angka, `tinggi` dihitung runtime dari `getimagesize()` berkas asli × rasio `width` blok — jadi kalau asetnya diresize lagi nanti (folder itu milik worker lain), tinggi ikut menyesuaikan sendiri. Hasil: `width=52`→`height=27`, `width=64`→`height=33`. **Sudah terbukti live** (lihat bukti di bawah) — beres di ronde 1, tidak disentuh lagi di ronde 2.

## Bukti (Playwright + curl cache-buster, situs live)

Metode cek "sudah deploy tapi kok belum kelihatan": HCDN Hostinger nge-cache tepi (`x-hcdn-cache-status: HIT`, `max-age=604800`). Query-string unik (`?x=<timestamp>`) memaksa `DYNAMIC` (lewat ke PHP origin asli, ada header `x-hcdn-upstream-rt`) — dipakai supaya tidak salah simpul "kode gagal" padahal cuma cache tepi yang lama.

**Sebelum fix (baseline)** — Playwright ke halaman beranda, nol cache-buster:
```
manrope-400.woff2 → 404
manrope-500.woff2 → 404
manrope-700.woff2 → 404
playfair-display-italic-400.woff2 → 404
logo width="52" height=null (rendered 89×46) | width="64" height=null (rendered 124×64)
```

**Sesudah ronde 1 (`16bb8a0`, curl cache-buster ke origin)** — logo BENAR, font MASIH 404 (object cache):
```
<img ... width="52" height="27">   <- logo BENAR
<img ... width="64" height="33">   <- logo BENAR
manrope-400/500/700.woff2, playfair-display-italic-400/500/600.woff2  <- MASIH ada di HTML (cache lama)
```

**Sesudah ronde 2 (komit ini):** menyusul — akan ditempel begitu god deploy ulang dan `init` sempat jalan sekali (memicu `wp_clean_theme_json_cache()`), diverifikasi ulang dengan Playwright + cache-buster yang sama, target nol rujukan `assets/fonts/*.woff2` di HTML live.

## Berkas
- Kode: `wordpress/theme-v5/theme.json`, `wordpress/theme-v5/functions.php`
- Laporan: `desain/LAPORAN-FONT-LOGO-0907.md`
