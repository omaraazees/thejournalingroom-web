# TJR v5, catatan port desain ke WordPress

Tema blok mandiri hasil port dari `desain/prototipe/v5-fieldtime.html`. Berdiri sendiri, tidak
butuh tema induk, tidak butuh page builder. Semua isi beranda dipasang sebagai block pattern,
jadi Caca dan Dhanty bisa mengedit teksnya langsung dari editor tanpa menyentuh kode.

Text domain: `tjr-v5`. Butuh WordPress 6.5 ke atas, diuji sampai 6.7.

## Isi folder

```
theme-v5/
  style.css              header tema plus seluruh CSS hasil port v5
  theme.json             versi 3, semua token warna dan jarak dari :root v5
  functions.php          aset, CPT acara, taksonomi, kategori pattern, varian gaya blok
  CATATAN.md             file ini
  assets/
    fonts/               kosong, tempat file woff2 lokal (lihat bagian Huruf)
    img/                 foto contoh, logo kolaborator, dan latar meja
    js/pembuka.js        animasi pembuka halaman plus tombol WhatsApp mengambang
  parts/
    header.html          logo, navigasi, tombol WhatsApp
    footer.html          empat kolom plus baris bawah
  patterns/              sembilan seksi, satu file per seksi
  templates/             tujuh template
```

## Yang sudah diport

### Token

Semua custom property dari blok `:root` di v5 dipindah ke `theme.json`, jadi tidak ada nilai hex
yang ditulis dua kali di CSS.

| Token v5 | Slug theme.json | Nilai |
|---|---|---|
| `sheet` | `kertas` | #FBF7F0 |
| `sheet-2` | `kertas-tua` | #F3EADC |
| `ink` | `tinta` | #241C14 |
| `soft` | `tinta-lembut` | #6D5D4C |
| `faint` | `tinta-samar` | #7E6F5E |
| `line` | `garis` | #E3D8C6 |
| `burg` | `burgundy` | #5F1D1D |
| `rose` | `rose` | #E7BFB9 |
| `rose-deep` | `rose-tua` | #7E5753 |
| `rose-text` | `rose-teks` | #81403A |
| `olive` | `zaitun` | #454E3C |
| `kraft` | `kraft` | #81572D |
| `kraft-soft` | `kraft-muda` | #EBD9BE |

Tiga warna tambahan yang di v5 ditulis langsung di badan CSS ikut dijadikan token supaya tidak
ada angka yang menggantung: `kertas-redup` (#EAE4D6, teks di atas strip gelap), `rose-pucat`
(#F1DCD7, latar seksi ajakan), dan `meja` (#E8DCC8, warna cadangan sebelum foto latar termuat).

Skala jarak dipetakan satu lawan satu, semuanya kelipatan 8:

| Token v5 | Slug theme.json | Nilai |
|---|---|---|
| `g1` | `jarak-1` | 8px |
| `g2` | `jarak-2` | 16px |
| `g3` | `jarak-3` | 24px |
| `g4` | `jarak-4` | 40px |
| `g5` | `jarak-5` | 64px |
| `g6` | `jarak-6` | 96px |
| `g7` | `jarak-7` | 144px |

Radius (`r-sheet`, `r-lg`, `r-md`), kurva easing, dan tinggi kontainer bento serta tumpukan masuk
ke `settings.custom`, jadi keluar sebagai `--wp--custom--radius--lembar` dan seterusnya.

### Tata letak

Lembar kertas krem yang melayang di atas foto meja dipasang di `.wp-site-blocks`, pembungkus yang
otomatis dibuat WordPress untuk seluruh isi template. Jadi header, isi, dan footer semuanya duduk
di dalam satu lembar, persis seperti di v5, tanpa perlu menambah pembungkus buatan sendiri.

### Interaksi

Semua interaksi v5 ikut, tidak ada yang dipangkas:

- Pil dengan sapuan warna yang naik dari bawah, tiga varian: garis, isi, dan rose. Terdaftar
  sebagai varian gaya blok, jadi muncul di panel Gaya pada blok Tombol.
- Panah kecil di dalam pil yang bergeser ke kanan atas waktu kursor lewat.
- Garis bawah navigasi yang tumbuh dari kiri.
- Kartu acara: foto membesar pelan, strip kaki berubah jadi burgundy.
- Strip warna di galeri bento, empat pilihan warna sebagai varian gaya blok Gambar.
- Bento dengan tinggi baris terkunci, jadi foto potret tidak menarik petaknya ke bawah.
- Cetakan polaroid yang lurus dan naik sedikit waktu disentuh kursor.
- Tumpukan cetakan yang membuka waktu digulir, memakai `animation-timeline: view()`. Di browser
  yang belum mendukungnya, cetakan tampil di posisi akhir tanpa animasi, bukan hilang.
- Sticker bulat yang bergoyang pelan.
- Panah tulis tangan yang menunjuk kartu sesi terdekat, digambar sekali lalu bergerak pelan.
- Tombol WhatsApp mengambang yang muncul setelah 60 persen tinggi layar digulir, dan melebar
  memperlihatkan teksnya waktu disentuh kursor.
- Pembuka halaman: tirai kertas dengan logo, judul naik per kata, foto panggung membuka dari
  bawah sambil zoom keluar.

Setiap animasi dimatikan sendiri kalau pengunjung memasang `prefers-reduced-motion: reduce`.

### Pembuka halaman

Logikanya disalin apa adanya dari v5 ke `assets/js/pembuka.js`, termasuk tiga pengaman:

1. `setTimeout(selesai, 4200)` sebagai jaring pengaman. Kalau animasi tidak sempat jalan, semua
   dipaksa ke keadaan akhir supaya halaman tidak pernah tertinggal kosong.
2. Pengecekan `prefers-reduced-motion`. Kalau reduce, tirai tidak pernah muncul.
3. Pengecekan `document.visibilityState`. Kalau tab dibuka di latar belakang, browser membekukan
   timeline dan animasi tidak akan pernah selesai, jadi pembuka dilewati.

Kelas `intro` dipasang lewat skrip sebaris di `functions.php`, bukan di file JS. Artinya tanpa
JavaScript kelas itu tidak pernah ada, dan halaman langsung tampil utuh.

### Pattern

Sembilan seksi, satu file per seksi, semuanya memakai markup blok inti sehingga bisa diedit dari
editor. Kategorinya dua: `tjr` dan `tjr-beranda`.

| File | Slug | Isi |
|---|---|---|
| `hero-panggung.php` | `tjr-v5/hero-panggung` | Judul, dua pil, foto panggung, panah, kartu sesi |
| `pengantar-kutipan.php` | `tjr-v5/pengantar-kutipan` | Dua kolom pengantar, foto, kotak kutipan |
| `jadwal-sesi-terdekat.php` | `tjr-v5/jadwal-sesi-terdekat` | Satu sesi terdekat, kartu besar |
| `jadwal-kartu.php` | `tjr-v5/jadwal-kartu` | Tiga acara yang baru saja lewat |
| `galeri-bento.php` | `tjr-v5/galeri-bento` | Empat foto dalam petak bento |
| `tumpukan-cetakan.php` | `tjr-v5/tumpukan-cetakan` | Sembilan cetakan polaroid |
| `pita-kolaborator.php` | `tjr-v5/pita-kolaborator` | Petak dua belas logo |
| `ajakan-whatsapp.php` | `tjr-v5/ajakan-whatsapp` | Penutup rose dengan lengkung besar |

### Query acara

Seksi jadwal dipecah dua sesuai cara partner mengumumkan acara, yaitu satu sesi dalam satu waktu:

- `jadwal-sesi-terdekat` menampilkan **satu** acara dengan tanggal mulai paling dekat yang belum
  lewat.
- `jadwal-kartu` menampilkan **tiga** acara dengan tanggal mulai paling baru yang sudah lewat, dan
  menautkan ke arsip, bukan ke WhatsApp.

Dua Query Loop itu ditandai lewat atribut `namespace` (`tjr/sesi-terdekat` dan `tjr/baru-lewat`).
`functions.php` menangkap penanda itu di filter `query_loop_block_query_vars` lalu menukar argumen
query-nya jadi `meta_query` terhadap field `tanggal_mulai`. Blok di pattern tetap blok inti biasa,
jadi tetap bisa diedit, tapi urutannya tidak bisa dirusak dari editor.

Supaya penanda itu sampai ke tempat yang tepat, `functions.php` juga menambahkan `namespace` ke
`providesContext` blok Query dan ke `usesContext` blok Post Template lewat filter
`block_type_metadata`. Aman dijalankan di versi WordPress yang sudah menyambungkannya sendiri.

### Templates

`index.html`, `front-page.html`, `single-acara.html`, `archive-acara.html`, `page.html`,
`404.html`, `search.html`, plus dua template part `header` dan `footer`.

Catatan kecil: bar atas dan kaki halaman memakai kelas langsung di panggilan `wp:template-part`,
bukan pembungkus tambahan di dalam file part. Alasannya `position: sticky` terkurung kotak elemen
induknya. Kalau bar dibungkus lagi oleh div setinggi bar itu sendiri, dia akan lepas dari atas
layar begitu digulir sedikit.

## Yang masih perlu dikerjakan manual

1. **File huruf lokal.** `theme.json` sudah mendaftarkan enam `fontFace` yang menunjuk ke
   `assets/fonts/`, tapi foldernya masih kosong. Nama file yang ditunggu:
   `playfair-display-italic-400.woff2`, `-500`, `-600`, lalu `manrope-400.woff2`, `-500`, `-700`.
   Selama `manrope-400.woff2` belum ada, `functions.php` memuat huruf dari Google Fonts sebagai
   cadangan. Begitu file itu diletakkan, pemuatan dari Google berhenti sendiri, tidak perlu
   mengubah kode.

2. **Field ACF di kartu sesi terdekat.** Enam baris fakta (tanggal, waktu, tempat, format, yang
   disediakan, yang perlu dibawa) sekarang berisi teks contoh. Tanggal dan format sudah tersambung
   ke data asli lewat blok Post Date dan Post Terms. Empat sisanya menunggu lane CMS memasang
   `get_field()` untuk `venue_nama`, `durasi_jam`, dan `isi_kit`.

3. **Bar sisa kursi.** Satu satunya HTML mentah yang tersisa di pattern, karena batang warna bukan
   konten teks. Persennya masih angka contoh. Ganti dengan panggilan ke `tjr_bar_slot()` dari
   `wordpress/cms/logika-status.md`.

4. **Jumlah foto di baris arsip.** Di desain tertulis "14 foto". Data itu belum ada di CPT, jadi
   untuk sementara kolom itu diisi taksonomi kota. Butuh field galeri atau penghitung lampiran.

5. **Pesan WhatsApp per acara.** Tombol sekarang memakai pesan umum. `tjr_link_wa_acara()` dari
   `wordpress/cms/whatsapp-link.md` membuat pesan yang sudah berisi nama acara dan tanggalnya,
   dan berubah sendiri jadi pesan waitlist kalau sesinya penuh. Tinggal disalin ke `functions.php`
   lalu dipakai di `single-acara.html`.

6. **Foto contoh pindah ke Media Library.** Foto di `assets/img/` dipakai supaya pattern langsung
   kelihatan benar begitu diaktifkan. Untuk jangka panjang lebih baik diunggah ke Media Library
   supaya WordPress bisa membuat ukuran turunan dan `srcset`. Ukuran turunan sudah didaftarkan di
   `functions.php`: `tjr-kartu`, `tjr-panggung`, dan `tjr-cetakan`.

7. **Halaman statis lain.** Tentang, Kontak, dan Kolaborasi belum punya template khusus. Sementara
   ini jatuh ke `page.html`.

8. **Logo situs.** Tirai pembuka memakai logo dari Customizer kalau sudah diatur, kalau belum
   memakai `assets/img/tjr-black.png`. Sebaiknya logo diunggah lewat Tampilan, Sesuaikan, Identitas
   Situs supaya bar atas dan kaki halaman ikut memakainya.

9. **Screenshot tema.** Belum ada `screenshot.png`, jadi kartu tema di Tampilan, Tema masih
   kosong. Tangkap layar beranda ukuran 1200 x 900 piksel, simpan sebagai `screenshot.png` di akar
   folder tema.

10. **Nomor WhatsApp.** Nomor bawaan `6285720225369` (tampilan 0857 2022 5369) dipakai di tiga
   tempat yang ditulis langsung, yaitu `parts/header.html`, `parts/footer.html`, dan
   `templates/single-acara.html`, karena file HTML template tidak bisa memanggil PHP. Kalau nomornya
   berubah, tiga file itu ikut diubah manual. Yang lain sudah membaca dari `tjr_v5_nomor_wa()` dan
   bisa diganti dari Tampilan, Sesuaikan, Kontak TJR.

## Cara pasang ke Hostinger

Hostinger punya dua kebiasaan yang gampang bikin frustrasi. Keduanya sudah diperhitungkan di
langkah di bawah, jadi ikuti apa adanya.

**Kebiasaan pertama: zip harus datar.** Kalau di dalam zip ada satu folder pembungkus, WordPress
akan melihat tema di `theme-v5/theme-v5/style.css` dan menolak memasangnya dengan pesan
"stylesheet is missing". Jadi zip dibuat dari **isi** folder, bukan dari foldernya.

**Kebiasaan kedua: File Manager tidak bisa menimpa.** Mengunggah file dengan nama yang sudah ada
tidak menimpa file lama, dan sering gagal diam diam. Jadi setiap kali mengunggah versi baru,
pakai nama file yang baru, lalu ekstrak ke folder baru.

### Langkah

1. Buat zip datar dari isi folder tema. Dari terminal, di dalam folder `wordpress/`:

   ```
   cd theme-v5
   zip -r ../tjr-v5-01.zip . -x '.DS_Store' -x '__MACOSX/*'
   cd ..
   ```

   Perhatikan titik setelah nama zip. Titik itu artinya "isi folder ini", bukan foldernya.

2. Cek isinya sudah datar sebelum diunggah:

   ```
   unzip -l tjr-v5-01.zip | head
   ```

   Baris pertama harus langsung `style.css`, `theme.json`, `functions.php`, dan seterusnya. Kalau
   yang muncul `theme-v5/style.css`, zip-nya salah, ulangi langkah 1.

3. Masuk hPanel, buka File Manager, lalu ke `public_html/wp-content/themes/`.

4. Unggah `tjr-v5-01.zip`. Kalau namanya sudah ada dari percobaan sebelumnya, ganti angkanya jadi
   `tjr-v5-02.zip`, dan seterusnya. Jangan mengunggah nama yang sama dua kali.

5. Klik kanan zip-nya, pilih Extract, lalu isi tujuannya folder **baru**, misalnya `tjr-v5`. Untuk
   pembaruan berikutnya pakai `tjr-v5-2`, lalu aktifkan yang baru dan hapus yang lama setelah
   dipastikan jalan.

6. Hapus file zip dari server setelah diekstrak, supaya tidak bisa diunduh orang lain.

7. Masuk dasbor WordPress, Tampilan, Tema, lalu aktifkan TJR v5.

8. Setelah aktif, buka Pengaturan, Permalink, lalu klik Simpan sekali. Ini menyegarkan rewrite rule
   supaya `/jadwal/` dan `/acara/nama-acara/` bisa dibuka. Tanpa langkah ini halaman acara akan
   404 padahal datanya ada.

9. Buka Pengaturan, Umum, pastikan zona waktu diset Jakarta. Kalau masih UTC, acara akan berubah
   status jadi selesai tujuh jam lebih awal.

10. Buka Pengaturan, Membaca, set halaman depan ke halaman statis dan pilih halaman berandanya.
    Template `front-page.html` yang akan dipakai.

### Kalau tema sudah aktif dan mau memperbarui

Jangan mengekstrak ke folder yang sedang aktif. Urutannya:

1. Ekstrak versi baru ke folder baru, misalnya `tjr-v5-2`.
2. Aktifkan tema dari folder baru itu di Tampilan, Tema.
3. Buka situsnya, pastikan tidak ada yang rusak.
4. Baru hapus folder lama.

Perubahan template dan pattern yang sudah pernah diedit lewat Site Editor tersimpan di database,
bukan di file tema. Perubahan itu menang atas file. Kalau setelah pembaruan tampilannya masih yang
lama, buka Tampilan, Editor, pilih template yang bersangkutan, lalu Clear customizations.
