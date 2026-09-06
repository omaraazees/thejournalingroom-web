---
Kartu: S-8
Sumber daftar: desain/TOKENS-AUDIT.md, "Penyimpangan yang ditemukan" #3
Berkas yang disentuh: wordpress/theme-v5/style.css
---

# Log penarikan literal jadi token — `wordpress/theme-v5/style.css`

Aturan kerja yang dipakai: sebuah literal **hanya** diganti dengan `var(...)`
kalau nilai token itu **sama persis** dengan literal lama (byte-identik,
termasuk apakah token itu di-fluid-kan WordPress atau tidak). Kalau token
terdekat nilainya beda meski sedikit, literal **dibiarkan** dan ditandai
"TIDAK DIGANTI" di sini — bukan diselundupkan sebagai refactor.

Baris yang disebut adalah nomor baris di `style.css` **setelah** perubahan
kartu ini (satu baris berubah: 15px → var, jumlah baris total tetap sama).

## Ringkasan

| # | Literal lama | Lokasi (selector) | Token baru | Nilai token | Status |
| - | --- | --- | --- | --- | --- |
| 1 | `font-size:11px` | `.kaki-kartu .mini` (baris 821) | — | — (label=10px, mikro=12px) | TIDAK DIGANTI — tidak identik |
| 2 | `font-size:11px` | `.kaki ul a` (baris 1214) | — | — (label=10px, mikro=12px) | TIDAK DIGANTI — tidak identik |
| 3 | `font-size:14px` | `.fakta .dd,.fakta dd` (baris 873) | — | — (kecil=13px, teks=15px) | TIDAK DIGANTI — tidak identik |
| 4 | `font-size:14px` | `.fakta .wp-block-post-date,.fakta .wp-block-post-terms` (baris 1501) | — | — (kecil=13px, teks=15px) | TIDAK DIGANTI — tidak identik |
| 5 | `font-size:15px` | `.bento figcaption` dkk (baris 941) | `var(--wp--preset--font-size--teks)` | `15px` (statis, tidak di-fluid-kan) | **DIGANTI — identik** |
| 6 | `font-size:17px` | `.kartu-sesi h2,.kartu-sesi-judul` (baris 544) | — | `sedang`=17px TAPI di-fluid-kan (`clamp(15px…17px)` di lebar <1600px) | TIDAK DIGANTI — token terdekat berbeda perilaku (fluid vs statis) |
| 7 | `font-size:40px` | `.bar …is-menu-open a` di `@media(max-width:900px) and (max-height:520px)` (baris 427) | — | — (display-md=29px, display-lg=42px, keduanya fluid) | TIDAK DIGANTI — tidak identik (lihat catatan) |
| 8–12 | `font-size:clamp(...)` × 5, satu-kali | baris 354, 513, 696, 772, 1089 | — | — (sudah dicatat di TOKENS-AUDIT.md sebagai "tidak memetakan ke token manapun") | TIDAK DIGANTI — sesuai audit, memang tidak ada token yang cocok |
| 13 | `border-radius:2px` | `.kartu-sesi::before,::after` (baris 609) | — | — (radius terkecil di token: `cetakan`=4px) | TIDAK DIGANTI — tidak identik |
| 14 | `border-radius:2px` | `.slot` (baris 891) | — | — (radius terkecil di token: `cetakan`=4px) | TIDAK DIGANTI — tidak identik |
| 15 | `border-radius:2px` | `.slot i` (baris 893) | — | — (radius terkecil di token: `cetakan`=4px) | TIDAK DIGANTI — tidak identik |
| 16 | `box-shadow:0 12px 30px rgb(36 28 20 / .12)` | `.kartu-sesi` di `@media(max-width:760px)` (baris 584) | — | preset terdekat `mengambang` = `0 12px 30px rgb(36 28 20 / .3)` — offset & warna sama, **alpha beda** (.12 vs .3) | TIDAK DIGANTI — tidak identik |
| 17 | `box-shadow:0 0 70px rgb(28 19 9 / .34)` | `.sampul` (baris 1310) | — | tidak ada preset dengan offset `0 0 70px` | TIDAK DIGANTI — tidak identik |
| 18 | `color:#fff` | `.wp-block-button.is-style-pil-isi …:hover,:focus-visible` (baris 204) | — | warna terdekat `kertas`=`#FBF7F0` (bukan putih murni) | TIDAK DIGANTI — tidak identik |
| 19 | `color:#5A4C3D` | `.ajakan-kiri .lead` (baris 1184) | — | warna terdekat `tinta-lembut`=`#6D5D4C` | TIDAK DIGANTI — tidak identik, lihat catatan khusus |
| 20 | `background:#20160B` | `.sampul::after` (baris 1322) | — | warna terdekat `tinta`=`#241C14` | TIDAK DIGANTI — tidak identik |
| — | Gradient `#E9DAC1,#F4ECDD,#EADCC4,#DCC9A8` | `.sampul` (baris 1309) | — | — | **DIKECUALIKAN** sesuai instruksi kartu, tidak disentuh |

**Hasil: 1 dari 20 literal diganti** (satu-satunya yang punya token dengan
nilai byte-identik). 19 sisanya dibiarkan literal karena token terdekat yang
ada di `theme.json` tidak sama persis nilainya — mengganti salah satu dari
19 ini akan mengubah tampilan, bukan sekadar refactor token.

## Catatan per kasus yang butuh penjelasan

**#5 — satu-satunya penggantian aman.** `teks` (15px) tidak punya entri
`fluid` di `theme.json` (beda dengan `sedang`), jadi presetnya benar-benar
statis `15px`, sama persis dengan literal lama. Aman diganti.

**#6 — `17px` sengaja tidak diganti ke `sedang`.** Nilai nominal `sedang`
memang `17px`, tapi `sedang` didefinisikan dengan `fluid: {min:15px,
max:17px}` di `theme.json`, sehingga `var(--wp--preset--font-size--sedang)`
di-generate WordPress sebagai `clamp(15px, …, 17px)`, bukan `17px` tetap.
Kalau literal statis ini diganti ke token itu, judul kartu sesi akan
mengecil jadi 15px di lebar layar sempit — itu perubahan visual, bukan
refactor murni. Dibiarkan literal.

**#7 — `40px` kebetulan sama dengan token spacing `jarak-4` (40px), tapi
sengaja tidak dipakai.** Saya pertimbangkan memakai
`var(--wp--preset--spacing--jarak-4)` untuk `font-size` ini karena nilainya
persis sama (`jarak-4` bukan token fluid, jadi hasilnya tetap `40px` statis).
Saya putuskan **tidak** memakainya: itu token kategori spacing, dipakai di
properti `font-size` — kalaupun nilainya kebetulan sama hari ini, itu
mengikat dua keputusan desain yang tidak berhubungan (kalau skala spacing
berubah, ukuran font menu mobile ikut berubah tanpa alasan). Ini bukan
"identik secara token", cuma kebetulan angka. Dibiarkan literal; kalau
memang perlu jadi token resmi, sebaiknya ditambahkan ukuran baru di skala
tipografi `theme.json` (di luar boundary kartu ini).

**#19 — `#5A4C3D` vs `tinta-lembut` (`#6D5D4C`).** Sesuai catatan khusus di
kartu ini: nilainya dekat tapi TIDAK sama (`#5A4C3D` = rgb(90,76,61) vs
`#6D5D4C` = rgb(109,93,76), beda di ketiga kanal). Kemungkinan besar ini
oversight/tidak sengaja waktu development, tapi saya tidak menyamakannya
diam-diam karena itu akan mengubah warna teks `.ajakan-kiri .lead` secara
kasat mata. **Rekomendasi:** ini keputusan desain untuk Umar/god — kalau
memang dimaksudkan sama dengan `tinta-lembut`, ganti manual di card
terpisah (perubahan visual, bukan token pull); kalau memang warna khusus,
sebaiknya didaftarkan sebagai token resmi baru di `theme.json`. Dibiarkan
literal di style.css untuk sekarang.

**#18 — `#fff` vs `kertas` (`#FBF7F0`).** Pola yang mirip di baris 196
(`.wp-block-button.is-style-pil-isi > .wp-block-button__link:hover` varian
lain) memakai `var(--wp--preset--color--kertas)` untuk teks di atas latar
gelap. Baris 204 memakai `#fff` mentah untuk kasus serupa (hover pil-isi).
Nilainya beda (`#FBF7F0` bukan putih murni), jadi bukan pengganti identik.
Kemungkinan ini memang sengaja dibuat putih murni untuk kontras maksimum di
atas burgundy saat hover — tidak saya ubah.

## Verifikasi

- `bash bin/periksa-php.sh` dijalankan sebelum commit — lihat commit log.
- Tidak menyentuh `theme.json`, pattern, template, PHP, atau berkas lain di
  luar `wordpress/theme-v5/style.css` dan berkas log ini.
