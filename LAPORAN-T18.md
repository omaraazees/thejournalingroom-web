# LAPORAN T-18: perbaiki empat temuan T-17, privasi lebih dulu

**Agent:** Jim (jim-mtqt9z7n) · **Tanggal:** 7 Sep 2026 · **Kartu:** T-18
**Batasan:** commit saja, nol deploy FTP (Pam pengirimnya), nol em dash.

> Ditulis bertahap. Tiap temuan: angka sebelum, apa yang diubah, angka sesudah.

---

## Temuan 1 (PRIVASI): alamat email Umar di arsip penulis

### Keadaan sebelum, dicatat lengkap supaya bisa dibalik

Perubahan ini menyentuh **data pengguna di database**, bukan berkas tema, jadi tidak ikut
git dan tidak bisa dibalik lewat `git revert`. Nilai aslinya dicatat di sini apa adanya.

Pengguna `id 1`, peran `administrator`:

| field | nilai SEBELUM |
|---|---|
| `username` | `omar.aazees@gmail.com` (tidak bisa diubah WordPress, dan tidak publik) |
| `name` (display_name) | `omar.aazees@gmail.com` |
| `nickname` | `omar.aazees@gmail.com` |
| `slug` (user_nicename) | `omar-aazeesgmail-com` |
| `link` | `https://thejournalingroom.id/cerita/author/omar-aazeesgmail-com/` |
| `first_name`, `last_name`, `description` | kosong |
| `url` | `http://thejournalingroom.id` |

**Untuk membalik:** kembalikan `name`, `nickname`, dan `slug` ke tiga nilai di atas lewat
`POST /wp-json/wp/v2/users/1`.

Ada juga pengguna `id 2` bernama `thejournalingroom`, slug `thejournalingroom`, juga
administrator. Tidak saya sentuh.

### Kenapa tiga field, bukan satu

Kartu benar bahwa mengganti nama tampilan saja tidak cukup, dan saya tambahkan satu lagi.

- `name` menutup **judul** halaman arsip.
- `slug` menutup **URL**, dan ini yang sering terlewat: mengganti nama tampilan tidak
  mengubah `user_nicename` sama sekali, jadi emailnya tetap ada di alamat halaman.
- `nickname` saya tambahkan karena isinya **juga alamat email** dan nilai itu ikut jadi
  kandidat tampilan di beberapa tempat WordPress. Menutup dua sisi tapi meninggalkan yang
  ketiga tetap menyisakan email di database sebagai nilai yang bisa muncul.

### Yang diubah

`POST /wp-json/wp/v2/users/1`, tiga field sekaligus:

| field | sebelum | sesudah |
|---|---|---|
| `name` | `omar.aazees@gmail.com` | **The Journaling Room** |
| `nickname` | `omar.aazees@gmail.com` | **The Journaling Room** |
| `slug` | `omar-aazeesgmail-com` | **the-journaling-room** |

### Verifikasi dua sisi

| yang dicek | hasil |
|---|---|
| URL lama `/cerita/author/omar-aazeesgmail-com/` | **HTTP 404**, nol email, nol slug lama |
| URL baru `/cerita/author/the-journaling-room/` | HTTP 200, `h1` = **"The Journaling Room"**, nol email |

Kartu memperbolehkan 404 atau redirect asal emailnya hilang. Yang terjadi **404**, dan itu
memang wajar: slug lama tidak lagi menunjuk pengguna mana pun. Nol tautan internal yang
mengarah ke slug lama, jadi nol tautan rusak yang dibuat perubahan ini.

### Sapuan seluruh situs

Kartu minta menyapu sisa email di seluruh situs, bukan cuma halaman itu. Dikumpulkan dari
`wp-sitemap.xml` (12 URL) plus 8 permukaan yang tidak masuk sitemap: arsip penulis baru,
arsip tanggal, tiga feed, endpoint REST `users`, REST `posts?_embed`, dan sitemap sendiri.

**Hasil: 0 dari 20 permukaan memuat alamat email.**

Termasuk yang paling gampang terlewat:

- `/feed/` dan `/cerita/feed/`: bersih. RSS WordPress bisa mencetak nama penulis.
- `/wp-json/wp/v2/users` tanpa login: bersih.
- `/wp-json/wp/v2/posts?_embed`: penulis ter-embed sekarang berbunyi
  `name: "The Journaling Room"`, `slug: "the-journaling-room"`. Bersih.

#### Satu koreksi terhadap sapuan saya sendiri

Sapuan pertama melaporkan dua permukaan "bersih" pada 36 byte dan 32 byte. Angka sekecil itu
mencurigakan, jadi saya periksa lagi. Ternyata **dua-duanya kegagalan permintaan di skrip
sapuan saya**, dan yang tercetak "bersih" itu sebenarnya string error, bukan halaman. Dua URL
itu **tidak pernah benar-benar diperiksa**.

Diulang dengan `curl`: `/kota/yogyakarta/` sebenarnya 81.999 byte (konsisten 3 kali) dan
`wp/v2/posts` 7.049 byte. Keduanya diperiksa ulang, **dua-duanya bersih**.

Ini bukan pemotongan Hostinger, jadi tidak ada berita buruk untuk dilaporkan di sisi itu.
Tapi layak dicatat sebagai jebakan: **hasil "bersih" dari permintaan yang gagal terlihat
persis sama dengan hasil "bersih" dari halaman yang benar benar aman.** Ukuran respons yang
janggal adalah satu-satunya yang membedakannya.

**Status temuan 1: SELESAI dan terverifikasi di live.** Ini satu-satunya temuan kartu ini
yang berlaku tanpa menunggu deploy, karena data pengguna hidup di database.

---

## Temuan 2 (SEO): arsip tanggal dan penulis nol judul khusus dan nol canonical

Sebabnya sudah saya tunjuk di T-17 dan terbukti benar: `wordpress/theme-v5/inc/seo.php`
mencabangkan arsip dengan `is_tax( array('format-acara','kota') ) || is_category() ||
is_tag()`. Arsip **tanggal** dan **penulis** tidak masuk cabang mana pun, jadi jatuh ke
judul generik dan nol canonical.

### Yang diubah

Dua cabang baru ditambahkan tepat sebelum cabang `is_search()`:

**`is_date()`** menangani tiga bentuk sekaligus, karena arsip tanggal punya tiga tingkat:

| bentuk | judul | canonical |
|---|---|---|
| Hari | "Tulisan 7 September 2026 \| ..." | `get_day_link()` |
| Bulan | "Tulisan September 2026 \| ..." | `get_month_link()` |
| Tahun | "Tulisan 2026 \| ..." | `get_year_link()` |

Nama bulan dicetak lewat `wp_date()`, bukan `date()`, supaya ikut lokal `id_ID` dan
berbunyi "September", bukan nama bulan berbahasa Inggris.

**`is_author()`** memakai `display_name` dan `get_author_posts_url()`. Satu penanganan
khusus: karena temuan 1 membuat nama tampilan penulis menjadi **sama persis** dengan nama
situs, judul "The Journaling Room | The Journaling Room" cuma mengulang diri sendiri. Jadi
kalau nama penulis sama dengan nama situs, judulnya jadi **"Semua tulisan | The Journaling
Room"**. Kalau berbeda, "Tulisan oleh <nama> | <situs>".

Deskripsi penulis dipakai kalau diisi, kalau kosong dibuatkan kalimat netral.

Verifikasi statis: `bin/periksa-php.sh` lulus **11 berkas, 0 gagal**.

### Yang BELUM bisa saya verifikasi, dan sebabnya

`seo.php` adalah berkas PHP tema. Berbeda dari templat blok di T-15, PHP **tidak bisa
dititipkan ke database**, jadi tidak ada cara menguji cabang ini tanpa mengirimnya. Kartu
melarang deploy.

**Jadi temuan 2 belum terbukti di HTML terrender, dan saya tidak menyatakannya terverifikasi.**

Yang harus dicek sesudah Pam mengirim, angka sebelumnya sudah tercatat di T-17:

| URL | `<title>` sebelum | yang diharapkan sesudah | canonical sebelum |
|---|---|---|---|
| `/cerita/2026/09/` | "The Journaling Room" | "Tulisan September 2026 \| The Journaling Room" | tidak ada |
| `/cerita/author/the-journaling-room/` | "The Journaling Room" | "Semua tulisan \| The Journaling Room" | tidak ada |

---

## Temuan 3 (i18n): label menu mobile masih Inggris

Dipastikan dulu dari HTML mentah server, bukan cuma dari iframe: `aria-label="Open menu"`
dan `aria-label="Close menu"` masing masing muncul **1 kali**, padahal `<html lang="id-ID">`
dan `language` situs `id_ID`.

Keduanya **string blok inti** `core/navigation` domain `default`, bukan milik tema. Tidak ada
di `parts/header.html`, dan `wp:navigation` tidak menerima atribut untuk mengubahnya. Jadi
tidak bisa diperbaiki dari markup.

### Yang diubah

Filter `gettext` di `functions.php`, sengaja dipersempit:

- hanya domain `default`,
- hanya kalau teks aslinya **sama persis** "Open menu" atau "Close menu",
- perbandingan dilakukan pada teks **asli**, bukan pada hasil terjemahan.

Poin terakhir itu yang membuatnya aman jangka panjang: kalau suatu saat berkas terjemahan
id_ID untuk inti benar benar terpasang, filter ini tetap menghasilkan teks yang sama dan
tidak menimpa terjemahan lain yang kebetulan berbunyi mirip.

Hasil: "Buka menu" dan "Tutup menu". Sama seperti temuan 2, ini berkas tema, jadi **baru
berlaku setelah dikirim**.

---

## Temuan 4: POSITIF PALSU. Menu mobile ternyata sudah benar, dan saya tidak menambalnya

Kamu menyuruh menguji dulu dengan interaksi sungguhan sebelum menambal, karena saya sendiri
yang memberi label perlu konfirmasi. Ternyata benar, dan hasilnya membatalkan temuannya.

### Uji dengan interaksi sungguhan

Jendela dikecilkan sampai mode overlay aktif (`innerWidth` 606, tombol MENU 108x46px muncul),
lalu menu **diklik sungguhan**, di-Tab sungguhan, dan ditutup dengan Escape sungguhan.

| perilaku modal | hasil |
|---|---|
| Menu terbuka saat diklik | ya |
| Fokus pindah ke dalam menu | ya, mendarat di tautan "Tentang" |
| Fokus terkurung sesudah 9 Tab | **ya, masih di dalam menu**, menu masih terbuka |
| Cincin fokus terlihat di dalam menu | **ya**, `solid 2px rgb(95,29,29)` offset 3px |
| Gulir latar dikunci | ya, `html` `overflow:hidden` |
| Escape menutup menu | **ya** |
| Fokus kembali ke tombol pemicu | **ya**, ke tombol "Open menu" |
| Kunci gulir dilepas setelah tutup | ya, `overflow` kembali `visible` |

Jadi **nol jebakan fokus yang salah**, dan `outline:none` di panel menu memang aman: aturan
`:focus-visible` penggantinya bekerja persis seperti komentar di `style.css` menjanjikan.

### Kenapa saya melaporkan `aria-modal` null di T-17

**Saya memeriksa elemen yang salah.** Atribut modalnya tidak ada di
`.wp-block-navigation__responsive-container` yang saya periksa, melainkan di div anak
`.wp-block-navigation__responsive-dialog`. Terlihat jelas begitu markup mentahnya dibaca:

```
<div class="wp-block-navigation__responsive-dialog"
     data-wp-bind--aria-modal="state.ariaModal"
     data-wp-bind--aria-label="state.ariaLabel"
     data-wp-bind--role="state.roleAttribute">
```

Diperiksa saat menu benar benar terbuka, div itu berbunyi:

| atribut | nilai |
|---|---|
| `role` | **dialog** |
| `aria-modal` | **true** |
| `aria-label` | **Menu** |

Dan tombol pemicunya sendiri sudah punya `aria-haspopup="dialog"`.

**Kesimpulan: bukan cacat. Nol tambalan.** WordPress 7.1 memakai Interactivity API dan
mengurus semantik dialog dengan benar. Kalau saya menambal ini, saya akan menambahkan
`role` dan `aria-modal` kedua di elemen yang salah, dan itu justru merusak yang sekarang
sudah benar.

Ini positif palsu ketiga yang saya bantah sendiri dalam dua kartu terakhir, dan penyebabnya
sama seperti dua sebelumnya: **menyimpulkan dari pemeriksaan yang tidak menyentuh kondisi
sebenarnya.** Yang pertama fokus programatik, yang kedua hitungan kontras tanpa melihat,
yang ini query selector ke elemen induk yang salah.

---

## Ringkasan

| # | temuan | status | terverifikasi di live? |
|---|---|---|---|
| 1 | Email Umar di arsip penulis | **SELESAI** | **ya**, data database berlaku langsung |
| 2 | Arsip tanggal dan penulis nol judul dan canonical | diperbaiki di repo | belum, menunggu kiriman |
| 3 | Label menu mobile Inggris | diperbaiki di repo | belum, menunggu kiriman |
| 4 | Menu mobile nol `aria-modal` | **POSITIF PALSU, nol tambalan** | ya, sudah benar sejak awal |

## Berkas yang berubah

| berkas | perubahan |
|---|---|
| `wordpress/theme-v5/inc/seo.php` | cabang `is_date()` dan `is_author()` ditambahkan |
| `wordpress/theme-v5/functions.php` | filter `gettext` untuk dua label menu |
| `LAPORAN-T18.md` | laporan ini |

**Dua berkas tema untuk Pam.** `bin/periksa-php.sh` lulus 11 berkas, 0 gagal.

Perubahan yang TIDAK ikut git karena hidup di database: `name`, `nickname`, dan `slug`
pengguna `id 1`. Nilai lamanya tercatat lengkap di bagian temuan 1 supaya bisa dibalik.

## Yang perlu dicek sesudah pengiriman

1. `/cerita/2026/09/` judulnya jadi "Tulisan September 2026 | The Journaling Room" dan punya
   canonical.
2. `/cerita/author/the-journaling-room/` judulnya jadi "Semua tulisan | The Journaling Room"
   dan punya canonical.
3. Tombol menu di layar sempit berbunyi "Buka menu" dan "Tutup menu".

## Yang tidak saya kerjakan, dan kenapa

- **Arsip penulis masih boleh diindeks.** Sesudah temuan 1, isinya tidak lagi membocorkan
  apa pun, jadi ini bukan lagi soal privasi. Tapi arsip penulis di situs satu penulis
  isinya menduplikasi `/cerita/` persis. Menambahkan `noindex` masuk akal untuk SEO, dan
  presedennya ada (`f749450` sudah mengeluarkannya dari sitemap). **Di luar lingkup kartu
  ini**, jadi saya laporkan, bukan kerjakan.
- **Arsip tag** tetap belum bisa diuji, situs masih nol tag.
- Judul arsip tanggal belum diuji pada bentuk hari dan tahun, cuma bulan yang punya URL
  nyata sekarang. Kodenya menangani ketiganya, tapi yang bisa dibuktikan nanti cuma bulan.
