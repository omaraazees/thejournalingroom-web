# Laporan T-16: kirim perbaikan T-15

Muatan: commit `70519c6` milik Jim. Tiga berkas tema.
Pengirim tunggal. Prosedurnya mengikuti `bin/CARA-KIRIM.md`.

## Muatan, dan bukti `index.html` tidak ikut

| Berkas | Sifat |
|---|---|
| `templates/archive.html` | baru |
| `templates/single.html` | diubah |
| `style.css` | diubah |

`templates/index.html` **tidak boleh** ikut berubah, karena heading statis di
sana yang menjaga `/cerita/` tetap punya `<h1>` (WordPress mengosongkan
`query-title` untuk `is_home`). Dibuktikan tiga kali, bukan diasumsikan:

1. `git diff --stat 1d07606..HEAD -- templates/index.html` kosong.
2. Daftar dry run nol menyebut `index.html`.
3. Potret server sesudah kirim: `index.html` tidak ada di daftar "ukuran
   berubah".

## Keadaan SEBELUM, direkam lebih dulu

God memperingatkan arsip kategori masih punya dua `<h1>` dan itu keadaan
sebelum, bukan kiriman gagal. Direkam supaya perbandingannya punya patokan,
bukan ingatan:

| Halaman | byte | `<h1>` | isi |
|---|---|---|---|
| `/cerita/kategori/panduan-journaling/` | 83486 | **2** | "Category: Panduan journaling" + "Cerita dari The Journaling Room" |
| `/jadwal/` | 74124 | 1 | "Workshop journaling di Jogja" |
| `/cerita/` | 83079 | 1 | "Cerita dari The Journaling Room" |
| `/format/journaling-workshop/` | 82132 | 1 | "Format acara: Journaling Workshop", `lbl-taksonomi` 1 |
| `/kota/yogyakarta/` | 81999 | 1 | "Kota: Yogyakarta", `lbl-taksonomi` 1 |
| artikel | 88881 | 1 | `nav-tulisan` 0 |

Cocok persis dengan yang god sebut, termasuk angka 83486. Jadi pembersihan
templat uji Jim memang benar benar mengembalikan produksi ke keadaan semula.

**Koreksi alamat, supaya siapa pun yang mengulang tidak tersesat.** Tiga URL di
brief tidak ada di situs ini. Yang berlaku:

| Di brief | Yang sebenarnya |
|---|---|
| `/category/panduan-journaling/` | `/cerita/kategori/panduan-journaling/` |
| `/format/brand-activation/` | `/format/journaling-workshop/` |
| `/kota/jakarta/` | `/kota/yogyakarta/` |

Diambil dari `wp-sitemap-taxonomies-*.xml`, bukan ditebak.

## Gerbang sebelum kirim

| Gerbang | Hasil |
|---|---|
| Disk lawan git di folder tema | 89 tracked, 89 disk, nol selisih dua arah |
| `bin/periksa-php.sh` | 11 berkas, 0 gagal |
| Pohon kerja | bersih |
| `--coba` | kirim 3, sama 85, hapus 0, dan ketiganya berkas yang benar |

Daftar dry run persis tiga baris yang diharapkan, nol berkas asing, nol
penghapusan, dan nol sebutan `index.html`.

## Kirim

- `bin/dorong-tema.sh` ke `omaraazees/tjr-v5-theme`.
- `python3 bin/kirim-tema-ftp.py`: kirim 3, sama 85, hapus 0.

## Verifikasi

### Lapis 1: potret server, nama demi nama

| Ukuran | Hasil |
|---|---|
| Jumlah berkas server | 87 jadi 88 |
| BARU | `templates/archive.html`, dan cuma itu |
| UKURAN BERUBAH | `style.css`, `templates/single.html`, dan cuma itu |
| HILANG | NOL |

`templates/index.html` tidak muncul di baris mana pun. Itu bukti ketiga bahwa
ia tidak tersentuh.

### Lapis 2: isi asli di server

`--coba --teliti` mengunduh dan mem-hash isi server: **88 dari 88 identik**,
`kirim 0, hapus 0`.

### Butir 1: arsip kategori satu `<h1>` tanpa awalan "Category:"

| | Sebelum | Sesudah |
|---|---|---|
| `<h1>` | **2** | **1** |
| Judul | "Category: Panduan journaling" | **"Panduan journaling"** |
| byte | 83486 | 82360 |
| `<title>` tab | | "Panduan journaling \| The Journaling Room" |

Urutan headingnya juga diperiksa di DOM dan sehat: `H1 Panduan journaling`
lalu `H2` judul artikel. Label kicker `.lbl` "Cerita" tetap ada, daftar
tulisannya terisi satu pos, nol gulir mendatar.

### Butir 2: target sentuh tautan kategori, dan nol geser

Diukur di DOM halaman artikel live. Untuk membuktikan "tidak bergeser", aturan
barunya dimatikan sementara di halaman yang sama supaya keadaan sebelum bisa
diukur langsung, bukan dibandingkan dengan ingatan:

| Ukuran | Sebelum (aturan dimatikan) | Sesudah |
|---|---|---|
| Tinggi target sentuh tautan kategori | **13.5px** | **49px** |
| Posisi atas `<h1>` | 293 | **293** |
| Posisi atas kotak kategori | 252 | 252 |
| Tinggi kotak kategori | 17 | 17 |
| Tinggi dokumen | 3107 | **3107** |

**Geser 0px di ketiganya.** Caranya `padding:16px 0` dipasangkan dengan
`margin:-16px 0`, jadi area sentuh membesar tanpa menambah tinggi baris.
Ukuran huruf tetap 10px. Jarak kotak kategori ke `<h1>` tetap 24px.

### Butir 3: navigasi pos tidak menggantung

Situs baru punya satu artikel, jadi yang benar adalah bloknya **tidak** tampil.

| Ukuran | Hasil |
|---|---|
| `.nav-tulisan` `display` | **none** |
| Tinggi kotaknya | 0 |
| Jumlah `<a>` di dalamnya | 0 |
| Dukungan `:has()` di mesin uji | ya |

Isinya memang cuma satu `div` kosong `post-navigation-link-previous`, nol
tautan, jadi `:not(:has(a))` menangkapnya dengan benar. **Nol garis
menggantung**: karena `display:none`, `border-top`-nya ikut hilang, bukan
sekadar tak berisi. Di halaman arsip blok ini nol muncul sama sekali, memang
seharusnya begitu.

### Butir 4: sapuan regresi templat arsip

Templat arsip yang rakus merusak diam diam, jadi ini diperiksa dengan patokan
byte, bukan cuma "kelihatan benar":

| Halaman | Templat yang benar | byte sebelum | byte sesudah | Penanda |
|---|---|---|---|---|
| `/jadwal/` | `archive-acara.html` | 74124 | **74124** | judul "Workshop journaling di Jogja" utuh |
| `/cerita/` | `index.html` | 83079 | **83079** | heading statis tetap ada |
| `/format/journaling-workshop/` | `taxonomy.html` | 82132 | **82132** | `lbl-taksonomi` tetap 1 |
| `/kota/yogyakarta/` | `taxonomy.html` | 81999 | **81999** | `lbl-taksonomi` tetap 1 |

**Keempatnya byte per byte sama** sebelum dan sesudah. Itu bukti yang lebih
kuat daripada memeriksa penanda saja: kalau `archive.html` mencuri salah
satunya, ukurannya pasti berubah.

### Butir 5: kesembilan URL terbit

Persis satu `<h1>` di kesembilannya. Panel `<dl>` tetap ada di `/` dan
`/acara/embracing-growth/`. Halaman yang tidak punya persis satu `<h1>`: **0**.

## Soal CDN yang dinonaktifkan

**Nol pemotongan dari 21 permintaan halaman** (6 sebelum kirim, 6 sesudah, 9
sapuan), ditambah beberapa pemuatan penuh di Chrome. Kemarin, dengan CDN aktif,
satu dari sepuluh gagal dan satu permintaan `/kontak/` putus dengan
`IncompleteRead(17754 bytes read, 1996 more expected)`.

Jadi pengamatan diagnostik Umar berlaku juga di sisi saya: **dengan CDN mati,
pemotongan hilang**. Ini bukan bukti sebab tunggal, cuma satu sampel lagi yang
searah. Kalau pemotongan muncul lagi dalam keadaan ini, itu berita besar dan
perlu langsung dilaporkan.

## Ringkasan T-16

| Butir | Hasil |
|---|---|
| Muatan | 3 berkas, `index.html` terbukti tidak tersentuh (tiga cara) |
| Gerbang | disk 89/89, PHP 11/0, pohon bersih, dry run 3 berkas yang benar |
| Lapis 1 | server 87 jadi 88, cuma `archive.html` baru, cuma 2 berubah, nol hilang |
| Lapis 2 | 88 dari 88 identik |
| Butir 1 | arsip kategori 2 `<h1>` jadi 1, awalan "Category:" hilang |
| Butir 2 | target sentuh 13.5px jadi 49px, geser tata letak **0px** |
| Butir 3 | navigasi pos `display:none`, nol garis menggantung |
| Butir 4 | empat halaman regresi **byte per byte sama** |
| Butir 5 | 9 dari 9 halaman punya persis satu `<h1>` |
| Pemotongan | 0 dari 21 permintaan, searah dengan CDN dimatikan |

Nol temuan baru, nol yang perlu dibalik.

