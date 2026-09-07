# T-36 (judul acara): "Embracing Growth" jadi "This is My First Time Too!"

**Selesai. Satu field di basis data, nol berkas tema, nol kiriman FTP.**

Catatan penomoran: kartu ini bernomor T-36, sama dengan kartu saringan skrip
kirim yang sudah ditutup sebelumnya. Laporannya dipisah supaya nol tertukar.

## Sumbernya SATU, tapi kandidatnya DUA, dan itu perlu dipastikan dulu

Sekilas ada dua tempat yang menyimpan judul, dan menulis keduanya akan salah:

| Kandidat | Isi sebelum |
|---|---|
| `post_title` | `Embracing Growth: A Full-day Journaling Activity` |
| `acf.judul_acara` | sama persis |

Yang benar **cuma `post_title`**. `judul_acara` bukan penyimpan, dia pintu masuk:

- `tjr_v5_muat_judul_acara()` di `isi-beranda.php:1327` mengisi kolom itu dari
  `get_the_title()` **tiap kali layar edit dibuka**, jadi nilai yang muncul di
  REST itu hasil hitung ulang, bukan data tersimpan.
- `tjr_v5_simpan_judul_acara()` di `:1354` justru **menghapus** meta `judul_acara`
  lalu menulis `post_title`.
- Dibuktikan: `meta` post cuma berisi `_acf_changed` dan `footnotes`. Nol
  `judul_acara` tersimpan di sana.

Jadi yang saya tulis satu field. `acf.judul_acara` ikut berubah **sendiri**
sesudahnya, dan itu justru bukti analisisnya benar.

## Yang diperiksa dan yang berubah

**Diperiksa 19 permukaan. Yang memuat judul: 8. Kemunculan: 14.**

| Permukaan | Peran | Jumlah |
|---|---|---|
| `/acara/embracing-growth/` | `<title>`, `og:title`, `twitter:title`, JSON-LD `name`, `<h1>` | 5 |
| `/` | kartu Sesi terdekat, `alt` gambar unggulan, judul di daftar | 3 |
| `/wp-json/wp/v2/acara` | `title.rendered` dan `acf.judul_acara` | 2 |
| `/jadwal/`, `/format/journaling-workshop/`, `/kota/yogyakarta/`, `/?s=` | judul kartu | 1 masing-masing |

Sesudah: **judul lama nol di kesembilan belas permukaan**, judul baru muncul 14
kali di delapan permukaan yang sama persis. Sebelas permukaan lain memang nol
memuatnya, sebelum maupun sesudah.

## Slug, syarat yang paling merusak kalau meleset

`embracing-growth` **sebelum dan sesudah**, dan
`https://thejournalingroom.id/acara/embracing-growth/` menjawab **HTTP 200**.

Ini aman secara mekanis, bukan karena beruntung. REST `POST` yang cuma mengirim
`title` nol menyentuh `post_name`. Jalur ACF pun aman: `:1377` cuma menulis slug
kalau slug lama kosong atau seluruhnya angka.

Satu hal yang berubah dan **bukan** slug: `generated_slug`. Itu cuma *saran*
WordPress kalau slug dibuat ulang dari judul, nol dipakai apa pun. Disebut supaya
tidak dikira slug bergeser.

## Tanda kutip: melengkung semua, dan itu pilihan, bukan kebetulan

Kartu memberi pilihan lurus semua atau melengkung semua. **Saya pilih melengkung**,
disimpan langsung sebagai U+201C dan U+201D, bukan lurus yang dibiarkan
di-`wptexturize`.

Alasannya: judul lurus akan tampil **berbeda-beda di permukaan berbeda**. `<h1>`
lewat texturize jadi melengkung, `og:title` lewat `esc_attr` jadi `&quot;` yang
lurus, dan JSON-LD jadi `\"` yang juga lurus. Itu persis gaya campur yang kartu
minta dihindari. Kutip melengkung nol butuh escape di konteks mana pun, jadi
kelima permukaan jadi identik.

Diperiksa dengan **kode karakter dan hasil dekode**, bukan pencocokan pola:

| Permukaan | Kode kutip | Kutip lurus |
|---|---|---|
| `<title>` | `0x201c`, `0x201d` | 0 |
| `og:title` | `0x201c`, `0x201d` | 0 |
| `twitter:title` | `0x201c`, `0x201d` | 0 |
| `<h1>` | `0x201c`, `0x201d` | 0 |
| JSON-LD `name` | `0x201c`, `0x201d` | 0 |

Dan lebih baik daripada yang diminta: **server mengirim karakter UTF-8 aslinya,
bukan entitas sama sekali**. Bukan `&quot;`, bukan `&#8220;`. Sapuan
pengkodean ganda di kesembilan belas permukaan juga **nol**.

## Satu akibat yang perlu dicatat, dan ini BUKAN cacat

**Tag `<title>`, `og:title`, dan `twitter:title` kehilangan akhiran ` | TJR Jogja`.**

`tjr_v5_seo_judul_acara()` di `seo.php:453` memasang akhiran itu hanya kalau
judul plus akhiran tidak lebih dari 60, dan kalau lewat, akhirannya yang dibuang
supaya judulnya tidak terpotong di tengah kata.

| Judul | Byte | Plus akhiran | Hasil |
|---|---|---|---|
| lama | 48 | **60** | akhiran dipakai |
| baru, kutip lurus | 49 | 61 | akhiran dibuang |
| baru, kutip melengkung | 53 | 65 | akhiran dibuang |

Judul lama duduk **tepat di batas 60**. Judul baru lebih panjang, jadi akhirannya
hilang, dan itu terjadi **dengan gaya kutip mana pun**. Pilihan melengkung nol
menyebabkannya, cuma menambah jarak dari batas.

`strlen()` PHP menghitung **byte**, dan kutip melengkung 3 byte per buah di UTF-8.
Itu sebabnya varian melengkung 53 byte padahal 49 karakter.

Kalau akhiran itu diinginkan kembali, jalannya bukan memendekkan judul: field
`judul_seo_pendek` sudah didukung `seo.php:454` tapi belum didaftarkan di situs
ini. Itu keputusan Umar, dan saya tidak mengerjakannya.

## Batas kartu

Harga, tanggal, dan daftar aktivitas **nol disentuh**. Diff post menunjukkan yang
berubah cuma `title.raw`, `title.rendered`, `acf.judul_acara` yang merupakan
cermin, `generated_slug`, dan dua penanda waktu.
