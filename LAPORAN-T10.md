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

