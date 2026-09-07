# Laporan T-19: kirim dua berkas PHP dari Jim

Muatan: commit `abe8d20`, `inc/seo.php` dan `functions.php`.

**Kartu ini beda dari T-16 dan diperlakukan begitu.** Kedua berkas ini belum
pernah diuji sama sekali. Templat blok bisa dititipkan ke database untuk diukur
sebelum kirim; PHP tidak bisa. Jim eksplisit tidak menyatakan keduanya
terverifikasi, dan itu jujur. Jadi yang di bawah ini bukan konfirmasi ulang,
ini uji pertama dan satu satunya atas kode tersebut.

Karena itu saya membaca dulu apa yang akan saya kirim, bukan langsung
menjalankan gerbang.

## Membaca muatan sebelum mengirim

**Filter `gettext` adalah bagian paling berisiko**, karena ia berjalan di setiap
string terjemahan di setiap pemuatan halaman. Dibaca dan ternyata sudah
dipersempit dengan benar di tiga lapis: keluar lebih awal kalau domainnya bukan
`default`, mencocokkan pada teks **asli** bukan hasil terjemahan, dan petanya
cuma dua kunci dengan pencocokan sama persis. Nol wildcard, nol pencocokan
sebagian. Secara konstruksi ia tidak bisa menyentuh string ketiga.

**Urutan cabang di `seo.php`** juga diperiksa, karena cabang baru yang
diletakkan salah bisa mencuri halaman milik cabang lama:

```
170 is_front_page   177 is_post_type_archive(acara)   184 is_singular(acara)
193 is_page         212 is_home                       225 is_singular
234 is_tax/is_category/is_tag
250 is_date   <- baru      274 is_author  <- baru
296 is_search  299 is_404
```

Keduanya diletakkan **sesudah** cabang taksonomi dan `is_home`, jadi arsip
kategori dan `/cerita/` tidak mungkin tercuri. Itu prediksi dari kode, dan
diuji lagi di butir 3 dan 5 sesudah kirim.

## Keadaan SEBELUM

Direkam lebih dulu supaya perbandingannya punya patokan.

| Halaman | byte | title | canonical |
|---|---|---|---|
| `/cerita/2026/09/` | 80867 | "The Journaling Room" (generik) | **tidak ada** |
| `/cerita/author/the-journaling-room/` | 81227 | "The Journaling Room" (generik) | **tidak ada** |
| `/cerita/kategori/panduan-journaling/` | 82360 | "Panduan journaling \| ..." | ada |
| `/jadwal/` | 74124 | "Jadwal Workshop Journaling Jogja \| ..." | ada |
| `/cerita/` | 83079 | "Cerita dari The Journaling Room \| Jogja" | ada |
| `/format/journaling-workshop/` | 82132 | "Journaling Workshop \| ..." | ada |
| `/kota/yogyakarta/` | 81999 | "Yogyakarta \| ..." | ada |
| artikel | 90309 | judul artikel | ada |
| `/` | 97518 | "Workshop Journaling Jogja \| ..." | ada |

Label menu di kesembilannya: `Open menu`, `Close menu`.

Diagnosis Jim terbukti: dua arsip itu memang judulnya generik dan nol canonical.

## Gerbang dan kirim

| Gerbang | Hasil |
|---|---|
| Disk lawan git | 89 tracked, 89 disk, nol selisih dua arah |
| `bin/periksa-php.sh` | 11 berkas, 0 gagal |
| Pohon kerja | bersih |
| `--coba` | kirim 2, sama 86, hapus 0, keduanya berkas yang benar |

`bin/dorong-tema.sh` lalu `python3 bin/kirim-tema-ftp.py`: kirim 2, hapus 0.

**Lapis 1**, potret server nama demi nama: 88 tetap 88, nol hilang, nol baru,
yang **ukurannya berubah cuma `functions.php` dan `inc/seo.php`**.

**Lapis 2**, `--coba --teliti`: 88 dari 88 identik.

**Lapis nol yang khusus untuk kiriman PHP:** situs masih hidup. Satu fatal PHP
akan jadi layar putih, dan `periksa-php.sh` cuma menangkap sintaks. Beranda
menjawab `200` dengan 97522 byte, bukan halaman kosong.

## Butir 1: arsip tanggal

| | Sebelum | Sesudah |
|---|---|---|
| title | "The Journaling Room" | **"Tulisan September 2026 \| The Journaling Room"** |
| canonical | tidak ada | **`https://thejournalingroom.id/cerita/2026/09/`** |
| description | tidak ada | "Tulisan The Journaling Room yang terbit September 2026." |
| byte | 80867 | 81982 |

Arsip tahun juga bekerja: `/cerita/2026/` jadi "Tulisan 2026 \| The Journaling
Room".

**Soal nama bulan berbahasa Indonesia, dan ini perlu dikatakan jujur.**
"September" dieja **sama persis** dalam bahasa Indonesia dan Inggris, jadi judul
yang keluar di atas **tidak membuktikan apa apa** soal lokal. Saya mencoba
membuktikannya lewat bulan lain dan tidak bisa: `/cerita/2026/08/`, `/01/`,
`/12/`, `/03/` keempatnya **404**, karena arsip tanggal kosong memang 404 dan
satu satunya tulisan situs ini terbit September.

Yang bisa saya buktikan, dan saya buktikan:

| Bukti | Hasil |
|---|---|
| Setelan bahasa WordPress | `language: id_ID` |
| Nama bulan di beranda | **"Agustus"**, nol nama bulan Inggris |
| Nama hari di beranda | "Minggu", dan nama hari Inggris yang ada semuanya di dalam JSON-LD (14 kemunculan, memang harus Inggris menurut schema.org) plus 5 kemunculan "Sunday" yang bagian dari nama merek "Sunday Reads" |
| Fungsi yang dipakai kode | `wp_date()`, yang menurut definisinya ikut lokal, bukan `date()` |

Jadi mekanismenya terbukti menghasilkan nama bulan Indonesia di instalasi ini,
dan kodenya memakai fungsi yang benar. **Yang belum bisa diamati langsung**
cuma judul arsip untuk bulan selain September, dan itu baru bisa diuji kalau ada
tulisan terbit di bulan lain.

## Butir 2: arsip penulis

| | Sebelum | Sesudah |
|---|---|---|
| title | "The Journaling Room" | **"Semua tulisan \| The Journaling Room"** |
| canonical | tidak ada | **`.../cerita/author/the-journaling-room/`** |
| description | tidak ada | "Kumpulan tulisan The Journaling Room." |
| byte | 81227 | 82299 |

Persis yang diminta: **bukan** nama diulang dua kali. Ini titik yang god sebut
paling mungkin salah, karena nama penulis sekarang sama dengan nama situs, dan
cabang `strcasecmp` di kode memang memilih label netral.

## Butir 3: arsip kategori tidak tercuri

| | Sebelum | Sesudah |
|---|---|---|
| title | "Panduan journaling \| The Journaling Room" | **sama** |
| canonical | `.../cerita/kategori/panduan-journaling/` | **sama** |
| description | "Panduan praktis buat yang baru mulai journaling." | **sama** |
| byte | 82360 | **82360** |

Byte per byte sama. Cabang baru nol mencuri yang lama.

## Butir 4: label menu, dan bukti nol string lain ikut berubah

Di kesembilan halaman terbit: `aria-label` jadi **"Buka menu"** dan
**"Tutup menu"**, dan sisa `Open menu`/`Close menu` di seluruh HTML: **0**.

**Bukti bahwa nol string lain ikut berubah**, dan kebetulan ini bukti yang
kebetulan sangat kuat. Kedua penggantiannya **panjangnya sama persis**:

```
"Open menu"  9 karakter  ->  "Buka menu"  9 karakter
"Close menu" 10 karakter ->  "Tutup menu" 10 karakter
```

Jadi kalau **cuma** dua string itu yang berubah, ukuran halaman harus tetap
sama persis. Dan memang begitu: tujuh rute yang labelnya berubah tetap **byte
per byte sama** (`/` 97518, `/cerita/` 83079, `/jadwal/` 74124, kategori 82360,
`/format/` 82132, `/kota/` 81999, artikel 90309). String lain apa pun yang ikut
tertimpa hampir pasti panjangnya beda dan akan menggeser angka itu.

Permukaan lain yang memakai string inti juga disapu (`/?s=journaling`, arsip
kategori, `/cerita/`): nol `Previous`, `Next`, `Search`, `Read more`,
`Comments`, `Older posts`, `Newer posts` yang tampil.

## Butir 5: sapuan regresi byte

| Halaman | byte sebelum | byte sesudah |
|---|---|---|
| `/jadwal/` | 74124 | **74124** |
| `/cerita/` | 83079 | **83079** |
| `/format/journaling-workshop/` | 82132 | **82132** |
| `/kota/yogyakarta/` | 81999 | **81999** |
| `/cerita/kategori/panduan-journaling/` | 82360 | **82360** |
| artikel | 90309 | **90309** |
| `/` | 97518 | **97518** |

Tujuh rute, byte per byte sama. Yang berubah cuma dua arsip yang memang jadi
sasaran kartu ini.

## Butir 6: kesembilan URL terbit

Persis satu `<h1>` di kesembilannya. Halaman yang tidak memenuhi: **0**.

## Temuan baru [SUDAH DIPERBAIKI di T-21 Jim, dikirim di T-26]: skip-link masih berbahasa Inggris

Ditemukan saat menyapu string inti untuk butir 4, dan **bukan akibat kiriman
ini**: tautan lewati navigasi masih berbunyi **"Skip to content"** di setiap
halaman.

Buktinya ia sudah ada sebelum kiriman: halaman halaman itu byte per byte sama
sebelum dan sesudah, jadi mustahil string ini baru muncul sekarang. Dan filter
`gettext` yang saya kirim petanya cuma dua kunci, jadi ia tidak mungkin
menambah string apa pun.

Ini **kelas cacat yang sama persis** dengan yang baru saja Jim tambal: string
blok inti berdomain `default` yang nol punya terjemahan id_ID di pemasangan ini,
tampil ke pengguna pembaca layar berbahasa Indonesia di halaman berlabel
`lang="id-ID"`. Obatnya juga sama dan sudah tersedia: satu baris tambahan di
peta `tjr_v5_label_menu_id()`.

**Saya tidak menambalnya.** Di luar lingkup kartu ini, dan menambah string ke
peta terjemahan itu keputusan penulisan yang layak lewat kartu sendiri, bukan
diselipkan ke kiriman verifikasi.

## Soal CDN

**Nol pemotongan** dari sekitar 40 permintaan halaman di seluruh kartu ini.
CDN masih dinonaktifkan, dan pengamatannya tetap searah dengan kemarin.

## Ringkasan T-19

| Butir | Hasil |
|---|---|
| Baca muatan dulu | filter `gettext` aman secara konstruksi, urutan cabang `seo.php` aman |
| Gerbang | disk 89/89, PHP 11/0, pohon bersih, dry run 2 berkas yang benar |
| Lapis 1 dan 2 | cuma 2 berkas berubah, nol hilang, 88 dari 88 identik, situs hidup |
| 1 arsip tanggal | judul dan canonical ada. Nama bulan Indonesia: mekanisme terbukti, kasus non-September belum bisa diamati |
| 2 arsip penulis | "Semua tulisan \| The Journaling Room", bukan nama dua kali |
| 3 arsip kategori | byte per byte sama, nol tercuri |
| 4 label menu | "Buka menu" dan "Tutup menu", nol sisa Inggris, dan byte identik membuktikan nol string lain berubah |
| 5 regresi | tujuh rute byte per byte sama |
| 6 h1 | 9 dari 9 punya persis satu |
| Temuan baru | skip-link "Skip to content" masih Inggris, dilaporkan tidak ditambal |

---

**Catatan status, 7 Sep 2026.** Skip-link yang dilaporkan di atas sudah
ditambal Jim di kartu T-21 dan saya kirim di kartu T-26. Diukur di produksi:
`Lewati ke konten` di kesebelas URL terbit, `Skip to content` nol.
