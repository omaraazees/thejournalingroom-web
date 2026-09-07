# LAPORAN T-21, T-20, T-5

**Agent:** Jim (jim-mtqt9z7n) · **Tanggal:** 7 Sep 2026
**Batasan:** T-21 dan T-20 berkas tema, commit saja nol deploy. T-5 lewat WP REST, langsung
tayang. Nol em dash.

> Ditulis bertahap. Dikerjakan sesuai urutan yang diminta: T-21, lalu T-20, lalu T-5.

---

## T-21 bagian 1: sapuan string blok inti yang belum diterjemahkan

Kartu minta daftarnya dilaporkan **sebelum** menambal semuanya, supaya ukurannya diketahui
dulu. Ini daftarnya.

Metode: 12 URL dari `wp-sitemap.xml` plus arsip penulis, arsip tanggal, dan halaman
pencarian. Dari tiap halaman ditarik semua `aria-label`, `screen-reader-text`, teks
`skip-link`, teks tombol, dan atribut `title`. Dua URL sempat gagal ambil dan **diulang**,
karena hasil kosong dari permintaan gagal terlihat sama dengan hasil bersih (pelajaran
T-18). Sesudah diulang, `/kontak/` 81.922 byte dan `/?s=journaling` 89.761 byte.

### Yang ditemukan

| # | string | tampil di | terlihat siapa | berat |
|---|---|---|---|---|
| 1 | `Skip to content` | **setiap halaman** | pembaca layar | **sedang** |
| 2 | `Search results for: "journaling"` | `/?s=` sebagai **`h1`** | **mata, langsung** | **sedang** |
| 3 | `... Feed`, `... Comments Feed`, `... Category Feed`, `Posts by ... Feed`, `... Kota Feed`, `... Format acara Feed` | atribut `title` pada `<link rel=alternate>` | pembaca feed | kecil |

Yang **bukan** temuan, dicatat supaya tidak dikejar orang lain:

- `aria-label="state.ariaLabel"` terjaring sapuan saya, tapi itu **positif palsu regex
  saya sendiri**: yang kena sebenarnya atribut `data-wp-bind--aria-label` milik Interactivity
  API, bukan `aria-label` sungguhan.
- `JSON`, `RSD`, `oEmbed (JSON)`, `oEmbed (XML)`: nama format, bukan kalimat, tidak
  diterjemahkan di bahasa mana pun.
- Semua `aria-label` buatan tema sudah berbahasa Indonesia dan benar: "Buka menu",
  "Tutup menu", "Cari", "Hubungi kami lewat WhatsApp", "8 kursi tersedia", dan lainnya.

### Jadi polanya seberapa besar

Kartu curiga dua kejadian dalam sehari bukan kebetulan. Benar, tapi ukurannya **kecil dan
tertutup**: **dua** string yang benar benar terlihat pengguna, plus satu kelompok judul feed
yang cuma dibaca mesin. Bukan kebocoran terjemahan yang meluas.

Sebabnya sama untuk ketiganya: string blok inti berdomain `default` yang **nol punya berkas
terjemahan `id_ID`** di pemasangan ini, padahal `language` situs sudah `id_ID`. Akar yang
sebenarnya ada di paket bahasa WordPress, bukan di tema.

### Yang saya tambal, dan yang sengaja tidak

**Ditambal: nomor 1 dan 2.** Keduanya terlihat pengguna.

**Tidak ditambal: nomor 3, judul feed.** Alasannya bukan malas. String itu **majemuk dan
berpola** (`%1$s %2$s Comments Feed` dan sejenisnya), jadi menambalnya lewat `gettext` butuh
menebak `msgid` persis beserta posisi placeholder-nya. Saya tidak bisa memverifikasi tebakan
itu tanpa deploy, dan menebak `msgid` lalu mengklaim selesai adalah persis kesalahan yang
sudah tiga kali saya hindari hari ini. Dampaknya juga cuma ke pembaca feed. **Dilaporkan,
tidak dikerjakan.**

---

## T-21 bagian 2: yang ditambal

### `Skip to content`

Satu baris ditambahkan ke peta yang sudah ada di `functions.php`:

```
'Skip to content' => 'Lewati ke konten',
```

Fungsinya sekaligus **diganti nama** dari `tjr_v5_label_menu_id()` menjadi
`tjr_v5_string_inti_id()`, karena isinya sekarang bukan cuma label menu. Nama yang menyesatkan
adalah biaya pemeliharaan, dan ini satu satunya tempat yang memanggilnya.

Docblock-nya ikut diperluas dengan catatan kenapa yang ini justru lebih penting daripada
label menu: tautan lewati-ke-konten **cuma dibaca pembaca layar**, jadi salah bahasa di sana
tidak terlihat mata siapa pun dan bisa bertahan lama tanpa ada yang melaporkannya.

### `Search results for:` di `h1` halaman pencarian

Sengaja **tidak** lewat `gettext`, dan alasannya perlu ditulis.

String ini majemuk, bentuknya `Search results for: %s` dengan placeholder. Menambalnya lewat
`gettext` berarti menebak `msgid` persis milik versi WordPress yang sedang jalan. Tebakan yang
salah **tidak akan bersuara**: filternya cuma diam tidak berefek, dan itu jenis kegagalan yang
paling mahal dilacak. Saya juga tidak bisa memverifikasi tebakan itu tanpa deploy.

Jadi yang dicocokkan adalah **keluaran yang sudah saya amati sendiri** di HTML terrender,
lewat `render_block_core/query-title`:

```
str_replace( 'Search results for:', 'Hasil pencarian untuk:', $isi )
```

Mencocokkan sesuatu yang sudah dilihat lebih jujur daripada menebak kunci terjemahan yang
belum pernah dilihat. Kata kunci pencariannya sendiri tetap utuh, yang diganti cuma awalannya.

Sesudah ditambal, halaman pencarian akan berbunyi label "Hasil pencarian" lalu `h1`
"Hasil pencarian untuk: "kata"", bukan lagi label Indonesia disusul judul Inggris yang
mengulang artinya.

**Belum terverifikasi**, karena berkas PHP tema tidak bisa diuji tanpa dikirim.

---

## T-20: arsip penulis tidak diindeks

Ditambahkan ke `inc/seo.php` lewat filter `wp_robots`:

```
if ( is_author() ) { $robots['noindex'] = true; $robots['follow'] = true; }
```

`follow` sengaja dibiarkan menyala: yang tidak diinginkan cuma halamannya masuk indeks,
sedangkan tautan di dalamnya tetap boleh ditelusuri supaya tulisan yang ditautkan dari sana
tidak ikut kehilangan jalur.

Sebelumnya `inc/seo.php` **nol menangani robots sama sekali**, jadi yang tercetak cuma
bawaan WordPress `max-image-preview:large`. Filter `wp_robots` adalah kait resmi untuk ini.

Komentarnya mencatat dua hal supaya tidak salah dibaca nanti: bahwa niat menyembunyikan
arsip ini sudah ada sejak `f749450` mengeluarkannya dari sitemap **tapi mencabut dari sitemap
bukan menutup halaman**, dan bahwa `noindex` ini murni SEO karena kebocoran emailnya sudah
ditutup terpisah di T-18.

**Belum terverifikasi**, alasan sama: berkas PHP tema.

Verifikasi statis untuk T-21 dan T-20: `bin/periksa-php.sh` lulus **11 berkas, 0 gagal**.

---

## T-5: DIHENTIKAN. Acaranya ada, tapi semuanya di TEMPAT SAMPAH

Saya tidak mengubah satu judul pun, dan alasannya bukan karena tidak ketemu.

### Apa yang sebenarnya ada

Kartu menyebut sembilan acara arsip berjudul placeholder "TJR x [Kolaborator]" yang tayang,
plus tiga yang sudah berjudul asli. Keadaan sebenarnya berbeda di dua hal.

`wp/v2/acara` hanya mengembalikan **satu** acara, yaitu `embracing-growth`. Sepuluh yang
dimaksud kartu memang ADA, tapi statusnya `trash`. Ditemukan dengan menanyakan tiap status
satu per satu, bukan dengan `status=any`, karena **`status=any` TIDAK termasuk `trash`**.

| status | jumlah |
|---|---|
| `publish` | **1** |
| `trash` | **15** |
| `auto-draft` | 4 |
| `draft`, `pending`, `private`, `future` | 0 |

Lima belas yang di tempat sampah:

| id | judul | dibuat |
|---|---|---|
| 98 | TJR x Sunday Reads Club | 6 Sep 15.42 |
| 99 | TJR x Radian | 6 Sep 15.42 |
| 100 | TJR x Kupiku Coffee | 6 Sep 15.42 |
| 101 | TJR x Wardah | 6 Sep 15.42 |
| 102 | TJR x Artotel | 6 Sep 15.42 |
| 103 | TJR x Statement Beauty | 6 Sep 15.42 |
| 104 | TJR x Kolondjono | 6 Sep 15.42 |
| 105 | About Myself | 6 Sep 15.42 |
| 106 | Write & Reflect | 6 Sep 15.42 |
| 107 | Writing Your Way Back to Yourself | 6 Sep 15.42 |
| 13 | Much Between the Lines | 5 Sep 08.41 |
| 14 | Journaling Playdate | 5 Sep 08.41 |
| 15 | A Moment Between Chapters | 5 Sep 08.41 |
| 16 | Wardah x The Journaling Room | 5 Sep 08.41 |
| 17 | Between the Pages | 5 Sep 08.41 |

### Dua koreksi terhadap kartu

1. **Placeholder "TJR x" ada TUJUH, bukan sembilan** (id 98 sampai 104). Tiga sisanya dari
   kelompok 6 Sep memang sudah berjudul asli (105, 106, 107), persis seperti kata kartu.
   Jadi kelompoknya sepuluh, komposisinya 7 plus 3, bukan 9 plus 3.
2. **Nol dari sepuluh itu tayang.** Semuanya di tempat sampah.

### Dibuang sekaligus, bukan satu per satu

Kelima belasnya punya `modified` yang **sama persis: 6 Sep 2026 pukul 19.00**, padahal
dibuatnya di dua waktu berbeda (5 Sep 08.41 dan 6 Sep 15.42). Waktu ubah yang seragam
seperti itu adalah tanda **satu tindakan massal**, bukan pembuangan satu per satu yang
kebetulan berdekatan. Seseorang memilih semuanya lalu membuangnya dalam satu langkah.

Dipastikan juga bahwa itu bukan sekadar penanda di REST: `/acara/tjr-x-radian/` dan
`/acara/about-myself/` dua-duanya **404** di live.

### Kenapa saya berhenti dan tidak mengerjakannya

Dua jalan yang tersedia, dua-duanya salah untuk saya ambil sendiri:

- **Mengganti judul selagi di tempat sampah** itu pekerjaan tanpa akibat. Halamannya 404,
  nol pengunjung melihatnya, dan hasilnya cuma terlihat benar di dasbor.
- **Memulihkannya dulu** adalah keputusan isi yang jauh lebih besar daripada yang diminta
  kartu. Kartu ini meminta mengganti judul, bukan menerbitkan ulang sepuluh acara ke situs
  hidup.

Ada satu alasan tambahan yang membuat pemulihan massal **berbahaya**, dan ini menyambung
temuan saya di T-4: lima acara kelompok 5 Sep (id 13 sampai 17) namanya persis sama dengan
acara contoh di `wordpress/cms/data-acara-contoh.json`. Berkas itu menandai sendiri bahwa
tanggal, kapasitas, dan sisa kursinya **karangan**, dan menulis "Jangan pakai angka bertanda
contoh di situs live". Memulihkan kelimanya berarti menayangkan tanggal dan sisa kursi
karangan di situs bisnis yang hidup.

**Jadi ini bukan pekerjaan yang macet, ini keputusan yang bukan milik saya.** Instruksi isi
dari Umar ("TJR nya dihapus saja isinya hanya kolaborator") jelas dan tidak saya
pertanyakan. Yang tidak jelas adalah apakah Umar tahu sepuluh acara itu sudah dibuang ke
tempat sampah semalam sebelumnya, karena instruksinya berbunyi seolah acara itu masih tayang.

Pertanyaan yang perlu dijawab sebelum saya melanjutkan ada di pesan `done` saya.

---

## Ringkasan

| kartu | status | terverifikasi di live? |
|---|---|---|
| T-21 `Skip to content` | diperbaiki di repo | belum, berkas tema |
| T-21 `Search results for:` | diperbaiki di repo | belum, berkas tema |
| T-21 judul feed | **dilaporkan, sengaja tidak ditambal** | tidak berlaku |
| T-20 `noindex` arsip penulis | diperbaiki di repo | belum, berkas tema |
| T-5 judul acara arsip | **DIHENTIKAN, menunggu keputusan** | nol perubahan dibuat |

## Berkas yang berubah

| berkas | perubahan |
|---|---|
| `wordpress/theme-v5/functions.php` | `Skip to content` masuk peta; fungsi diganti nama jadi `tjr_v5_string_inti_id()`; filter baru `tjr_v5_judul_pencarian_id()` |
| `wordpress/theme-v5/inc/seo.php` | filter `wp_robots`, `noindex` untuk arsip penulis |
| `LAPORAN-T21-T20-T5.md` | laporan ini |

**Dua berkas tema untuk Pam.** Nol perubahan lewat WP REST, jadi **nol yang sudah tayang**
dari kartu ini.

## Yang harus dicek sesudah pengiriman

1. Tab pertama di halaman mana pun memunculkan tautan berbunyi **"Lewati ke konten"**.
2. `/?s=journaling` `h1`-nya berbunyi **"Hasil pencarian untuk: "journaling""**, dan kata
   kuncinya masih utuh.
3. `/cerita/author/the-journaling-room/` punya `<meta name="robots">` yang memuat
   **`noindex`** dan `follow`.
4. Halaman lain **tidak** ikut kena `noindex`. Ini yang paling penting dicek dari ketiganya,
   karena `noindex` yang bocor ke halaman lain adalah kerusakan SEO yang senyap.

## Yang tidak saya uji

- Efek filter `gettext` terhadap string inti lain yang kebetulan berbunyi sama. Risikonya
  kecil karena dicocokkan pada teks **asli** dan sama persis, tapi belum saya buktikan
  dengan menyapu seluruh situs sesudah tambalan tayang.
- Judul feed, sengaja tidak disentuh.
- Halaman pencarian dengan kata kunci yang mengandung tanda kutip atau HTML.

---

## Tambahan T-5: menguji dugaan "dua kelompok itu saling duplikat"

Diminta god: verifikasi murah, TANPA memulihkan apa pun, tanpa mengubah status, tanpa
mengubah judul. Semua di bawah ini hasil baca saja (`GET` REST dan `GET` halaman publik).
Nol tulis.

### Jawabannya: dugaan itu TIDAK terbukti

`id 16` dan `id 101` bukan satu acara yang dibuat dua kali. Field-nya berjauhan:

| field | id 16 `wardah-x-tjr` | id 101 `tjr-x-wardah` |
|---|---|---|
| `tanggal_mulai` | 2026-11-08 10.00 | 2026-02-21 16.00 |
| `venue_nama` | Copenhagen | Wardah |
| `harga` | 125000 | kosong |
| `kapasitas` / `slot_terisi` | 30 / 30 | 0 / 0 |
| `durasi_jam` | 3 | 0 |

Selisih tanggalnya sembilan bulan, venue-nya beda, yang satu punya data komersial lengkap
dan yang satu nol. Ditambah lagi tanggal `id 16` itu sendiri tanggal karangan (`_sumber`
menandainya `contoh`), jadi dipakai sebagai bukti pun tidak bisa.

### Yang benar: dua kelompok itu dua JENIS data, bukan dua salinan

**id 13-17, dibuat 5 Sep 08.41.** Salinan persis fixture `data-acara-contoh.json`, field per
field: judul, `tanggal_mulai`, `venue_nama`, `harga`, `kapasitas`, `slot_terisi` semuanya
cocok. Tanggalnya semua DI MASA DEPAN (28 Sep sampai 8 Nov 2026).

**id 98-107, dibuat 6 Sep 15.42.** Satu acara per kolaborator, `venue_nama` = nama
kolaboratornya, tanggalnya semua SUDAH LEWAT (9 Nov 2025 sampai 30 Agu 2026), dan
`harga`/`kapasitas`/`slot_terisi` semuanya kosong. Persis bentuk "arsip kolaborasi yang
sudah selesai". Pemetaannya satu lawan satu ke post type `kolaborator`:

`98`→46 Sunday Reads Club, `99`→48 Radian, `100`→50 Kupiku Coffee, `101`→53 Wardah,
`102`→55 Artotel, `103`→61 Statement Beauty, `104`→136 Kolondjono (draft),
`105`→63 AMCO Bakehouse, `106`→65 Pasar Jakal, `107`→67 Snapobox.

Dua kolaborator tanpa acara arsip: Hanasui (57) dan Heejaz (59). Heejaz cuma muncul sebagai
venue fixture `id 17`.

Yang tumpang tindih itu SUBJEK-nya, bukan record-nya. Wardah muncul di dua kelompok
(16 dan 101), Artotel juga (13 venue-nya "ROCCA Artotel", 102 judulnya "TJR x Artotel").
Kalau dua kelompok itu tayang bersama, situs akan menampilkan dua entri Wardah dengan
tanggal berjarak sembilan bulan. Itu alasan sah untuk membuang salah satu kelompok. Tapi
itu cuma menjelaskan 4 dari 15.

### Penjelasan yang jauh lebih pas, dan ada di repo

`design/INVENTARIS-ACARA.md`, commit `f37b1f0`, "KARTU S-9", waktu commit
**2026-09-07 01.44 +0700 = 2026-09-06 18.44 UTC**. Zona waktu situs UTC (`modified` sama
persis dengan `modified_gmt`, sudah kucek). `modified` massal kelima belas acara:
**2026-09-06 19.00 UTC. Enam belas menit sesudahnya.**

Isi dokumen itu: daftar 16 acara, dipisah "TAMPIL di Beranda (4)" dan "TIDAK TAMPIL di
Beranda (12)", dan tentang yang 12 tertulis apa adanya "bisa dievaluasi untuk penghapusan".

Jadi urutannya: kartu inventaris selesai, enam belas menit kemudian 15 acara kena tindakan
massal. Kartu S-9 itu yang memicu pembuangan, bukan duplikasi.

Satu hal yang TIDAK cocok dengan dokumen itu: dokumennya menominasikan 12, yang dibuang 15.
Tiga tambahannya `107`, `106`, `17`, dan ketiganya justru yang di dokumen terdaftar sebagai
TAMPIL di beranda. Tindakannya melewati rekomendasinya sendiri. Yang selamat cuma `id 12`,
satu-satunya acara mendatang terdekat.

**Koreksi untuk laporanku sendiri yang sebelumnya.** Aku menulis `modified` 19.00 = saat
dibuang. Itu terlalu kuat. `wp_trash_post()` menulis `post_status` langsung dan menyimpan
waktu buang sebenarnya di meta `_wp_trash_meta_time`, yang tidak diekspos REST. Yang bisa
kubuktikan: pukul 19.00 UTC ada satu tindakan massal yang menyentuh kelima belasnya.
Bahwa tindakan itu pembuangannya adalah simpulan, kuat tapi bukan bukti.

### Akibat yang sudah tayang sekarang, terukur

- **Beranda kehilangan satu seksi utuh.** "Sesi yang lalu" (Recently) tidak ada lagi di HTML
  beranda. `patterns/jadwal-kartu.php` baris 11-38 punya penjagaan: kalau nol acara yang
  sudah lewat, seluruh seksi tidak dirender. Penjagaannya bekerja benar, dan justru itu
  sebabnya tidak ada yang melapor. Beranda kehilangan satu blok tanpa terlihat rusak.
- `/jadwal/` sekarang cuma "Embracing Growth". Tidak ada teks kosong menggantung.
- **Sitemap bersih**, `wp-sitemap-posts-acara-1.xml` isinya tepat 1 URL. Tidak ada URL
  terbuang yang masih terindeks, jadi tidak ada kebocoran 404 ke mesin pencari.
- 12 entri kolaborator masih terbit semua, tapi 10 di antaranya kehilangan acara arsip yang
  dulu memakai namanya.

Nol perubahan dilakukan. Tidak ada yang dipulihkan, status tidak disentuh, judul tidak
disentuh.
