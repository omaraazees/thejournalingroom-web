# Laporan T-10: pengiriman gabungan T-1 + T-3 + T-4

Pengirim tunggal: Pam. Ditulis bertahap.
Status saat blok ini ditulis: **belum mengirim apa pun.** Menunggu kabar god
bahwa Jim (T-4) sudah selesai.

## Langkah 1: pra-terbang, read-only

Rentang yang diperiksa: `578900e..HEAD`.

```
46194b6  T-1 a11y (Pam)
4bcff93  T-3 buang tombol galeri mati dan catatan_harga (Jim)
```

T-4 Jim **belum ada commitnya sama sekali** di rentang ini, dan itu terlihat
juga dari pohon kerja yang masih kotor. Rinciannya di temuan C-1 di bawah.

### (a) Adakah perubahan T-1 yang tertimpa atau hilang

Tidak ada. Keenam berkas dibaca ulang di keadaan akhirnya, bukan dari ingatan
dan bukan dari pesan commit:

| Berkas | Yang saya cari | Ada di berkas akhir |
|---|---|---|
| `style.css` | `.kaki ul a` padding 13px | baris 1273, ada |
| `style.css` | `.bar .wp-block-navigation a` padding 14px | baris 264, ada |
| `style.css` | garis bawah nav `bottom:10px` | baris 267, ada |
| `functions.php` | `tjr_v5_satu_h1()` + `add_filter the_content` | baris 957 dan 972, ada |
| `functions.php` | `tjr_v5_fakta_jadi_dl()` + `add_filter render_block` | baris 1005 dan 1046, ada |
| `theme.json` | token `tinta-samar` = `#756654` | baris 52, ada |
| `patterns/hero-panggung.php` | argumen alt `$tjr_cetak_a` | baris 95, ada |
| `patterns/pengantar-kutipan.php` | argumen alt `$tjr_cetak_a` | baris 71, ada |
| `inc/isi-beranda.php` | alt bawaan `hero_cetakan` | baris 124, ada |
| `inc/isi-beranda.php` | alt bawaan `pengantar_cetakan` | baris 128, ada |

Ditambah satu pemeriksaan negatif: `tjr_v5_gambar_tag( ..., '' )` yang tersisa
di seluruh `patterns/` = **nol**. Jadi bukan cuma dua baris yang saya ubah masih
ada, tapi juga tidak ada yang kembali ke keadaan lama diam diam.

Berkas yang paling berisiko, `inc/isi-beranda.php`, diperiksa lewat diff penuh
`578900e..HEAD`. Perubahan saya (dua baris alt bawaan) dan perubahan Jim
(pembuangan `catatan_harga` dari `tjr_v5_harga_acara()`) berada di **dua wilayah
yang berjauhan di berkas yang sama**, dan dua duanya utuh. Tabrakan kemarin
memang cuma soal alamat commit, bukan soal isi.

### (b) Adakah dua commit yang saling meniadakan

Tidak ada, dan ini bisa dibuktikan dengan cara yang lebih kuat daripada membaca
diff: daftar berkas kedua commit **nol beririsan**.

```
4bcff93 : LAPORAN-T2.md, LAPORAN-T3-T4.md, desain/SCHEMA-EVENT.md,
          wordpress/cms/acf-fields.json, wordpress/cms/data-acara-contoh.json,
          wordpress/theme-v5/inc/isi-beranda.php, wordpress/theme-v5/inc/seo.php
46194b6 : LAPORAN-T1.md, wordpress/theme-v5/functions.php,
          wordpress/theme-v5/patterns/hero-panggung.php,
          wordpress/theme-v5/patterns/pengantar-kutipan.php,
          wordpress/theme-v5/style.css, wordpress/theme-v5/theme.json
irisan  : (kosong)
```

`isi-beranda.php` tidak muncul di commit saya justru KARENA perubahannya sudah
lebih dulu terbawa `4bcff93`. Jadi irisan kosong di sini bukan tanda dua kartu
bekerja terpisah rapi, melainkan sisa dari tabrakan kemarin. Hasil akhirnya
tetap benar.

Satu perubahan tema lain dari Jim ikut diperiksa karena akan tayang:
`inc/seo.php` cuma kehilangan **dua baris komentar** soal `catatan_harga`. Nol
perubahan perilaku.

### Bonus: sintaks PHP sudah bisa diperiksa di mesin ini

Di laporan T-1 saya menulis bahwa kode temuan 2 dan 7 belum pernah lewat PHP
karena PHP CLI tidak terpasang. Ternyata repo ini **sudah punya jalan keluarnya**
dan saya melewatkannya: `bin/periksa-php.sh`, parser PHP murni JavaScript lewat
npm karena Node ada walau `php` tidak. Dijalankan sekarang:

```
11 berkas PHP, 0 gagal
```

`functions.php` (dua fungsi baru T-1) dan `inc/isi-beranda.php` (dua kartu
sekaligus) dua duanya lolos. Ini menutup kelas kesalahan layar putih sebelum
berangkat. Yang masih belum tertutup dan memang tidak bisa ditutup di sini:
fatal saat jalan, misalnya fungsi yang tidak ada. Itu urusan Langkah 3.

### (c) Adakah berkas yang masuk repo tapi tidak dimaksudkan tayang

Tiga temuan. Yang pertama sudah selesai sendiri saat laporan ini ditulis, dan
saya tetap mencatatnya karena polanya yang penting, bukan berkasnya.

**C-1. Berkas tema tanpa commit tetap akan dikapalkan. Kebersihan git BUKAN
gerbangnya.** Ini temuan struktural, dan menurut saya yang paling berharga dari
pra-terbang ini.

`bin/kirim-tema-ftp.py` menyusun daftar kirimannya begini:

```python
LOKAL = AKAR / "wordpress" / "theme-v5"
for p in LOKAL.rglob("*"):
    if p.is_file() and p.name not in LEWATI:
```

Sumbernya **sistem berkas, bukan git**. Jadi berkas yang belum di-commit,
bahkan yang belum pernah di-`git add` sama sekali, tetap berangkat ke server
selama ia ada di dalam `wordpress/theme-v5/`. Dan `LEWATI` isinya cuma dua nama:
`.DS_Store` dan `CATATAN.md`. Skripnya juga **menghapus** berkas di server yang
tidak ada di lokal, jadi ini cermin penuh, bukan tambal sulam.

`bin/dorong-tema.sh` memang menolak jalan kalau pohon kerja kotor, tapi itu
menjaga repo tema, bukan menjaga server. Dua jalur itu terpisah.

Waktu saya mulai pra-terbang, ada satu berkas nyata dalam keadaan itu:
`wordpress/theme-v5/templates/single.html`, 803 byte, belum tracked, waktu ubah
13:02, yaitu kerja T-4 Jim yang sedang berjalan. Kalau saya mengirim di menit
itu, template setengah jadi tayang di produksi sementara git tidak punya
catatannya sama sekali. Beberapa menit kemudian Jim meng-commitnya lewat
`cf999b5`, jadi berkasnya sekarang sah. Yang tidak hilang: **lain kali gerbangnya
tetap bukan git.** Sebelum mengirim, yang harus dibandingkan adalah isi folder
tema di disk lawan git, bukan `git status` saja.

**C-2. Tiga folder gores milik alat tulis ada DI DALAM folder yang dikapalkan.**

```
wordpress/theme-v5/.claude/.cc-writes
wordpress/theme-v5/templates/.claude/.cc-writes
wordpress/theme-v5/assets/img/.claude/.cc-writes
```

Ketiganya **kosong sekarang**, jadi pengiriman hari ini nol byte tambahan
(skripnya cuma mengambil `p.is_file()`). Tapi ketiganya tidak ada di
`.gitignore` dan tidak ada di `LEWATI`, sementara letaknya di dalam folder yang
dicermin penuh ke `wp-content/themes/tjr-v5/` di server publik. Artinya satu
berkas apa pun yang jatuh ke situ, kapan pun, berangkat ke web server tanpa ada
yang menahan. Ini bukan blokir untuk pengiriman kali ini, tapi lubang yang
sebaiknya ditutup dengan menambahkan `.claude/` ke `LEWATI` atau ke pemeriksaan
skripnya. **Saya tidak mengubahnya sendiri**, karena itu menyentuh skrip deploy
milik lantai di tengah kartu pengiriman, dan itu waktu yang paling salah.

**C-3. Tiga berkas tak-tracked di akar repo, dan ketiganya AMAN.**

```
.impeccable/          TIKET-HOSTINGER.md          :memory:.ses
```

Tidak satu pun berangkat: FTP cuma menyentuh `wordpress/theme-v5/`, dan
`dorong-tema.sh` memakai `git subtree --prefix=wordpress/theme-v5`, jadi akar
repo tidak pernah ikut ke repo tema. Dicatat supaya tidak jadi kekhawatiran
belakangan. Satu di antaranya, `:memory:.ses`, kelihatan seperti ampas alat
(nama berkas sesi SQLite yang tercipta gara gara basis data `:memory:`), bukan
kerja siapa pun. Bukan urusan kartu ini, tapi layak dibuang oleh yang tahu asalnya.

### Kesimpulan pra-terbang

- (a) **Bersih.** Nol perubahan T-1 yang hilang atau tertimpa. Sepuluh titik
  diperiksa di berkas akhirnya, plus satu pemeriksaan negatif.
- (b) **Bersih.** Nol commit yang saling meniadakan. Daftar berkas dua commit
  awal nol beririsan, dan berkas yang dipakai bersama sudah diperiksa diff penuh.
- (c) **Dua catatan, nol blokir.** C-1 sudah beres sendiri lewat `cf999b5` tapi
  polanya perlu diingat setiap kali mengirim. C-2 lubang laten yang perlu
  ditutup di luar kartu ini. C-3 aman.

Nol temuan yang cukup serius untuk menahan pengiriman. Tidak ada yang saya
perbaiki diam diam.

### Yang akan saya jalankan tepat sebelum mengirim, bukan sekarang

Dry run `python3 bin/kirim-tema-ftp.py --coba` sengaja **belum** dijalankan.
Daftar kirimannya berubah setiap kali Jim menyimpan berkas, jadi dry run
sekarang cuma menghasilkan jawaban basi. Urutan waktu kirim nanti:

1. `git status` plus banding disk lawan git di folder tema (gerbang C-1).
2. `bash bin/periksa-php.sh`.
3. `python3 bin/kirim-tema-ftp.py --coba`, baca daftarnya, pastikan tidak ada
   yang asing.
4. `bash bin/dorong-tema.sh`, lalu `python3 bin/kirim-tema-ftp.py`.

## Koreksi atas bagian pra-terbang di atas

Sebelum melanjutkan, satu hal yang saya tulis salah di bagian C-1 dan sudah
terlanjur saya sampaikan ke god.

Saya menulis bahwa `kirim-tema-ftp.py` "menghapus berkas di server yang tidak
ada di lokal" seolah itu perilaku bawaan. **Salah.** Penghapusan cuma terjadi
kalau flag `--hapus` diberikan:

```python
buang = sorted(set(remote) - set(lokal)) if hapus else []      # baris 230
hapus = "--hapus" in sys.argv                                   # baris 181
```

Uji nomor 10 di `bin/uji-kirim-tema.py` memang menguji perilaku itu, dan
namanya sendiri sudah menyebut flagnya. Jalan biasa **tidak pernah menghapus
apa pun**. Inti temuan C-1 tetap berdiri (daftar kiriman disusun dari disk, jadi
berkas tanpa commit tetap berangkat), tapi separuh soal penghapusannya saya
gambarkan lebih berbahaya daripada kenyataannya.

## Langkah 0: lubang `.cc-writes` ditutup

Commit `53b401d`, terpisah seperti diminta.

Saringan lama cuma menguji nama berkas (`p.name in LEWATI`), jadi `gores.json`
di dalam `.cc-writes` lolos tanpa hambatan. Ditambahkan `LEWATI_FOLDER =
{".claude", ".cc-writes"}` yang diuji per **segmen jalur induk**
(`rel.parts[:-1]`), karena yang perlu dibuang seisi folder, bukan satu nama.
Segmen terakhir sengaja tidak ikut diuji supaya berkas yang kebetulan bernama
`.claude` tetap lewat jalur `LEWATI` yang benar. `.gitignore` ikut ditambah
untuk menjaga sisi git; nol berkas `.claude` yang tracked, jadi nol yang berubah
status.

Diuji dua lapis:

| Uji | Hasil |
|---|---|
| `bin/uji-kirim-tema.py` (suite yang sudah ada) | 10 dari 10 lulus |
| Uji khusus, sembilan jalur tiruan | 3 berkas tema sah lewat, 6 ditahan |

Enam yang ditahan: tiga berkas di bawah `.claude/.cc-writes` pada tiga kedalaman
berbeda (akar tema, `templates/`, `assets/img/`), `.DS_Store`, `CATATAN.md`, dan
satu `.cc-writes` tanpa `.claude` di atasnya.

## Langkah 1: pengiriman

Empat gerbang dijalankan berurutan sebelum mengirim:

| Gerbang | Hasil |
|---|---|
| Disk lawan git di folder tema | 89 tracked, 89 di disk, nol selisih dua arah |
| `bin/periksa-php.sh` | 11 berkas PHP, 0 gagal |
| Pohon kerja | bersih |
| `kirim-tema-ftp.py --coba` | kirim 8, sama 80, hapus 0 |

Delapan berkas di dry run persis gabungan tiga kartu, nol berkas asing:

```
functions.php                     T-1  dua fungsi baru
inc/isi-beranda.php               T-1 alt bawaan + T-3 catatan_harga
inc/seo.php                       T-3  komentar saja
patterns/hero-panggung.php        T-1  alt polaroid
patterns/pengantar-kutipan.php    T-1  alt polaroid
style.css                         T-1  target sentuh
templates/single.html             T-4  BERKAS BARU
theme.json                        T-1  token kontras
```

Lalu dijalankan sungguhan:

- `bin/dorong-tema.sh`: `2e9e292..a7ec7cb` ke `omaraazees/tjr-v5-theme`.
- `bin/kirim-tema-ftp.py`: kirim 8, sama 80, **hapus 0**, 9.0 detik.

Catatan lingkungan: koneksi FTP dan `git push` diblokir sandbox
(`PermissionError: Operation not permitted` di `socket.connect`), jadi keduanya
dijalankan di luar sandbox. Itu satu satunya cara skrip ini bisa jalan di mesin
ini.

## Langkah 2: tujuh verifikasi di produksi

Semua diukur di HTML yang benar benar dikirim server, dengan cache-buster acak
per permintaan.

### 1. `<dl>` dengan `dt` dan `dd`

| Halaman | `<dl>` | `<dt>` | `<dd>` | `<p class="dt` tersisa |
|---|---|---|---|---|
| `/` (kartu sesi terdekat) | 1 | 7 | 7 | 0 |
| `/acara/embracing-growth/` | 1 | 7 | 7 | 0 |
| `/` di viewport 390px | 1 | 7 | 7 | 0 |

Diperiksa juga di DOM Chrome: `document.querySelector('dl.fakta').tagName`
mengembalikan `DL`, bukan `DIV`.

**`/jadwal/` nol `<dl>`, dan itu BENAR, bukan kegagalan.** Brief menyebut
`/jadwal/` sebagai salah satu tempat panel itu, tapi halaman itu memang tidak
pernah punya panelnya. `/jadwal/` dirender `templates/archive-acara.html` yang
menampilkan kartu ringkas lewat `patterns/jadwal-kartu.php`, dan pattern itu
berisi **nol** kemunculan `fakta`, `class="dt`, maupun `class="dd`. Diperiksa
di dua sisi: di berkas pattern dan di HTML live-nya. Panel tujuh fakta cuma
hidup di dua tempat, dan dua duanya sudah terbukti di atas.

Satu catatan pengukuran supaya tidak menyesatkan siapa pun yang mengulang:
pembacaan pertama saya di iframe 390px sempat melaporkan `dl` nol. Itu
**artefak pengukuran**, bukan temuan. Pembacaan itu diambil sesudah panel menu
diklik buka dan dengan jeda tunggu yang terlalu pendek. Diulang bersih tanpa
klik dan dengan jeda lebih panjang, hasilnya `dl` 1, `dt` 7, `dd` 7. Angka yang
saya laporkan yang kedua.

### 2. Nol halaman kehilangan `<h1>`

Sembilan URL terbit di sitemap diperiksa, bukan tiga:

| Halaman | `<h1>` |
|---|---|
| `/` | 1 |
| `/tentang/` | 1 |
| `/kolaborasi/` | 1 |
| `/kontak/` | 1 |
| `/galeri/` | 1 |
| `/cerita/` | 1 |
| `/cerita/mulai-journaling-nggak-tahu-mau-nulis-apa/` | 1 |
| `/jadwal/` | 1 |
| `/acara/embracing-growth/` | 1 |

Persis satu di semuanya. Nol yang kehilangan, nol yang punya dua. Guard
`the_content` tidak memakan `<h1>` siapa pun.

### 3. Target sentuh naik di live

| Elemen | Sebelum semua kartu | Sesudah `63d706d` | Sekarang di produksi |
|---|---|---|---|
| Tautan footer, desktop 1782px | 15px | 32.7px | **44.7px** |
| Tautan footer, 390px | 15px | 32.7px | **44.7px** |
| Nav header desktop | 21px | 33.0px | **45.0px** |
| Nav overlay 390px | (bukan temuan) | 39.6px | 39.6px, sengaja |

Sekalian terukur di produksi: `.lbl` sekarang `rgb(117, 102, 84)` di atas
`rgb(251, 247, 240)` = **5.20:1** (dulu 4.55:1), dan gambar ber-`figcaption`
yang `alt`-nya kosong = **0** (dulu 2).

### 4. `catatan_harga` tidak lagi dirender

Baris harga di produksi sekarang isinya mentah `Rp297.300`, nol
`<span class="slot-catatan">` menempel. Satu satunya `slot-catatan` yang tersisa
di halaman adalah catatan kursi, `<p>8 kursi tersedia</p>`, dan itu memang
bukan bagian temuan. Diperiksa di `/`, `/acara/embracing-growth/`, dan
`/jadwal/`.

### 5. Artikel memakai `templates/single.html` yang baru

`/cerita/mulai-journaling-nggak-tahu-mau-nulis-apa/`:

| Penanda | Nilai | Artinya |
|---|---|---|
| `<h1>` | "Mulai journaling waktu nggak tahu mau nulis apa" | judul artikel, bukan judul arsip |
| `<main class>` | `wp-block-group isi-halaman` | kerangka `single.html` |
| `wp-block-post-terms` | 1 | baris kategori khas `single.html` |
| `wp-block-post-date` | 1 | idem |
| `daftar-tulisan` | **0** | penanda `index.html`, kalau muncul berarti masih jatuh ke fallback |

Nol `daftar-tulisan` itu buktinya: sebelum ada `single.html`, artikel jatuh ke
`index.html` yang membungkus isinya dengan kelas itu.

### 6. Tombol "Buka galeri" tetap hilang

Nol kemunculan teks "Buka galeri" dan nol `href="#"` telanjang, diperiksa di
`/galeri/` dan `/`.

### 7. Nol berkas asing terkirim, nol berkas server terhapus

- Terkirim **8**, persis daftar dry run, nol tambahan.
- **Hapus 0.** Flag `--hapus` tidak dipakai, dan seperti koreksi di atas, jalan
  biasa memang tidak pernah menghapus.
- `kirim-tema-ftp.py --coba --teliti` dijalankan sesudahnya. Mode ini mengunduh
  dan mem-hash isi asli di server, bukan membaca manifes: **88 dari 88 berkas
  lokal identik dengan yang ada di server**, `kirim 0, sama 88`.

**Satu berkas yatim ditemukan di server, dan bukan dari pengiriman ini:**

```
assets/img/artotel-08-1600-v2.webp    246096 byte    diunggah 2026-09-07 05:25:05 UTC
```

Ada di server, tidak ada di repo, dan **nol dirujuk** oleh tema (dicari di
seluruh `wordpress/theme-v5`, yang dipakai sekarang `artotel-08-700.webp`,
`artotel-08-hero.webp`, dan `artotel-08.jpg`). Sisa dari sesi penamaan ulang
foto hero pagi tadi. Ukurannya 246 KB, jauh di atas ambang pemotongan Hostinger
yang terukur (berkas 163 KB ke atas gagal sekitar separuh), jadi kalau suatu
saat ada yang merujuknya lagi, ia akan bermasalah.

**Saya TIDAK menghapusnya.** Membuangnya menuntut `--hapus`, dan itu tindakan
yang sulit dibalik di server produksi yang tidak diminta kartu ini. Dilaporkan,
bukan dibereskan diam diam.

### Catatan pengukuran: pemotongan Hostinger benar benar kejadian

Saat verifikasi, satu permintaan ke `/kontak/` putus di tengah:
`IncompleteRead(17754 bytes read, 1996 more expected)`. Diulang, dan sembilan
halaman lolos utuh dengan **nol** pemotongan di putaran kedua. Jadi kira kira
satu dari sepuluh permintaan, dan ini terjadi pada **HTML**, bukan cuma gambar.
Bukan akibat pengiriman ini, dan bukan hal baru: akar masalahnya di sisi hosting
dan masih menunggu tiket Umar. Dicatat di sini sebagai bukti bahwa peringatan
"HTTP 200 bukan berarti isinya utuh" itu bukan kehati hatian teoretis.

## Sisa satu, sengaja diserahkan

Jim mencatat ada template `tjr-v5//single` bersumber custom di database yang
sebaiknya dihapus lewat Site Editor supaya tidak ada dua sumber kebenaran.
Isinya identik dengan berkas tema, jadi situs tetap benar selama itu belum
dihapus, dan god sendiri menyebut ini rapi rapi, bukan darurat.

**Saya serahkan ke Umar, tidak saya kerjakan sendiri.** Alasannya: itu
penghapusan data di database produksi lewat layar admin, sifatnya sulit dibalik,
dan god memberi pilihan dengan kalimat "kalau kamu ragu, serahkan". Nol biaya
menundanya, karena gejalanya nol.

Langkahnya buat yang mengerjakan: WP Admin > Appearance > Editor > Templates >
cari **Single Posts** (`tjr-v5//single`) yang bertanda "Customized", buka menu
tiga titik, pilih **Clear customizations**. Sesudah itu template kembali dibaca
dari berkas tema, dan pembaruan desain dari git kembali sampai ke halaman
artikel.

## Ringkasan T-10

| Langkah | Hasil |
|---|---|
| Pra-terbang (a)(b)(c) | bersih, dua catatan, nol blokir |
| Langkah 0, tutup lubang `.cc-writes` | commit `53b401d`, 10/10 uji lama plus uji baru lulus |
| Langkah 1, kirim | subtree `2e9e292..a7ec7cb`, FTP kirim 8 hapus 0 |
| Langkah 2, tujuh verifikasi | tujuh terbukti di produksi |
| Temuan baru | satu berkas yatim 246 KB di server, dilaporkan bukan dihapus |
| Diserahkan | pembersihan template `tjr-v5//single` di Site Editor |

