# LAPORAN T-278: alamat tombol Book Your Seat bisa diisi per acara

16 Sep 2026. Tema `tjr-v5`, live thejournalingroom.id. Commit `cbd08e5`, sudah
tayang lewat FTP, nol didorong ke remote.

## Yang diminta, dan yang jadi

Alamat tombol "Book Your Seat" sekarang bisa disunting dari layar edit acara,
satu kolom per acara. Dikosongkan berarti memakai formulir bawaan yang sekarang,
jadi selama semua sesi memakai formulir yang sama tidak ada yang perlu diisi.
Alamat bawaannya tetap tinggal di konstanta `TJR_FORM_PESAN_KURSI`, nol pindah
ke database.

## Per berkas dan baris

### `wordpress/theme-v5/functions.php` (baris 304-338)

`tjr_v5_link_pesan_kursi()` dapat parameter `$id_acara` opsional, bawaannya 0.
Kalau id diberikan, kolom `link_pesan_kursi` acara itu dibaca lewat
`get_post_meta`, bukan `get_field`, supaya tombolnya tetap hidup kalau ACF
sedang mati. Nilainya dilewatkan `esc_url_raw`: skema yang tidak diizinkan
menghasilkan string kosong, jadi alamat salah ketik nol pernah jadi href
tombol. Kosong atau tidak sah jatuh ke `TJR_FORM_PESAN_KURSI`. Konstantanya
sendiri nol disentuh.

### `wordpress/theme-v5/inc/isi-beranda.php` (baris 1214-1240)

Cabang baru di `tjr_v5_fakta_acara()`, mekanisme yang sama dengan tombol
`wa-slot` yang sudah ada: tombol berkelas `pesan-kursi` mengambil id acara lewat
`tjr_v5_id_acara_konteks( $blok )`, lalu href-nya ditukar. Di luar konteks acara
(`$id_acara` nol) isinya dikembalikan apa adanya, BUKAN ditulis ulang jadi alamat
bawaan; itu yang menjaga alamat kartu hero di bawah tetap benar.

Bedanya satu: substitusinya `preg_replace_callback`, bukan string pengganti
`'${1}'...`. Alamat ini diketik orang di layar edit, dan tanda dolar di dalamnya
akan terbaca PCRE sebagai rujukan grup kalau ditaruh di string pengganti.

### `wordpress/theme-v5/inc/isi-beranda.php` (baris 1637-1699)

Grup field baru `group_tjr_link_kursi`, judul panel "Pemesanan kursi", aturan
lokasi sendiri `post_type == acara`. Satu kolom: `link_pesan_kursi`, tipe `url`,
label "Link pemesanan kursi", petunjuk yang menyebut kosong berarti formulir
bawaan plus alamat bawaannya, dan placeholder berisi alamat bawaan itu juga.
Dua duanya membaca `TJR_FORM_PESAN_KURSI`, jadi tetap satu sumber.

`show_in_rest => true`, sama seperti dua grup kode yang sudah ada. Tanpa itu
kolomnya nol bisa dibaca maupun ditulis lewat REST.

Grup SENDIRI, bukan kolom yang dititipkan ke grup Detail Acara yang hidup di
database. `acf_add_local_field` dengan parent grup database membuat ACF
menganggap grup itu didefinisikan di kode, dan seluruh kolom aslinya hilang dari
layar edit. Bukti grup lama selamat ada di bawah.

### `wordpress/theme-v5/patterns/hero-panggung.php` (baris 11-13, 67-70)

Kartu sesi terdekat di hero BUKAN bagian Query Loop, jadi filter `render_block`
nol tahu acara mana yang tampil di situ. Patternnya memanggil helper yang sama
langsung dengan `$tjr_sid`, id sesi terdekat yang memang sudah dihitung di
berkas itu untuk judul, jam, tempat, dan harga.

`patterns/jadwal-sesi-terdekat.php` nol disentuh: tombolnya ada di dalam
`post-template`, jadi filter `render_block` yang mengurusnya. Alamat bawaan yang
tercetak di pattern tetap jadi contoh yang tampil di editor sekaligus cadangan.

### `wordpress/cms/panduan-caca-dhanty.md`

Satu subbagian baru di langkah 3, menerangkan panel Pemesanan kursi. Berkas ini
di luar folder tema, jadi nol ikut terkirim FTP.

## Cara kolomnya dibaca

1. `patterns/jadwal-sesi-terdekat.php` mencetak alamat bawaan ke href.
2. Blok tombol dirender di dalam Query Loop, jadi `$blok->context['postId']` ada
   (`core/button` sudah diberi `postId` di `usesContext` lewat
   `tjr_v5_konteks_post_id()`).
3. `tjr_v5_fakta_acara()` melihat kelas `pesan-kursi`, mengambil id acara, dan
   memanggil `tjr_v5_link_pesan_kursi( $id )`.
4. Helper membaca meta `link_pesan_kursi`. Terisi dan sah: itu yang dipakai.
   Kosong atau tidak sah: `TJR_FORM_PESAN_KURSI`.

Hero memotong langkah 1 sampai 3 dan langsung ke langkah 4, karena kartunya
sudah tahu acara mana yang tampil.

## Uji sesudah kirim

Acara uji: id 12, "This is My First Time Too!": Embracing My Growth, mulai
27/09/2026, satu satunya acara yang tanggalnya belum lewat, jadi dia yang tampil
di hero dan di seksi Sesi terdekat.

### Kolom baru muncul, kolom lama MASIH ADA

Layar edit dibuka di wp-admin lewat sesi Chrome yang sudah login.

- Panel baru "Pemesanan kursi" muncul di antara "Judul dan catatan sesi" dan
  "Detail Acara": `hive/research/t278/layar-edit-acara-panel-baru.jpg`
- Detail Acara, tab Waktu dan tempat, semua terisi: tanggal 27/09/2026 09:00,
  durasi 8, venue "Villa Pondok Joglo Yogyakarta", link Maps, alamat singkat:
  `hive/research/t278/detail-acara-waktu-dan-tempat.jpg`
- Tab Slot: kapasitas 8, slot terisi 0:
  `hive/research/t278/detail-acara-slot.jpg`
- Tab Harga: harga per orang 265000:
  `hive/research/t278/detail-acara-harga.jpg`
- Tab Isi kit tetap ada di deret tab yang sama.

REST `/wp/v2/acara/12` juga masih memulangkan seluruh kolom lama berdampingan
dengan kolom baru: `bawa_sendiri`, `catatan_harga`, `disediakan_teks`,
`durasi_jam`, `harga`, `isi_kit`, `judul_acara`, `kapasitas`,
`link_pesan_kursi`, `slot_terisi`, `tanggal_mulai`, `venue_alamat`,
`venue_maps`, `venue_nama`.

### Dua href, dibaca dari HTML live

Kolom DIISI `https://forms.gle/UJI-T278-PER-ACARA`, lalu
`https://thejournalingroom.id/?probe=t278b` (200, 99035 byte):

```
aksi pesan-kursi ... href="https://forms.gle/UJI-T278-PER-ACARA"      <- kartu hero
is-style-pil-isi pesan-kursi ... href="https://forms.gle/UJI-T278-PER-ACARA"   <- seksi Sesi terdekat
```

Kolom DIKOSONGKAN lagi lewat layar edit dan ditekan Save (revisi naik 15 ke 16),
lalu `https://thejournalingroom.id/?probe=t278c` (200, 99033 byte):

```
aksi pesan-kursi ... href="https://forms.gle/pA5cLp3PVrU7LEfS7"       <- kartu hero
is-style-pil-isi pesan-kursi ... href="https://forms.gle/pA5cLp3PVrU7LEfS7"    <- seksi Sesi terdekat
```

Itu persis `TJR_FORM_PESAN_KURSI`. HTML beranda sesudah uji identik byte per byte
dengan HTML sebelum uji, jadi keadaan situs kembali seperti semula.

### Yang nol berubah

- Tiga tombol WhatsApp nol disentuh. Beranda masih memulangkan dua alamat
  `wa.me/6285720225369` yang sama seperti sebelumnya, satu pesan tanya slot dan
  satu pesan daftar.
- Halaman acara `/acara/embracing-growth/` 200, 79486 byte, enam kemunculan
  `wa.me` utuh, dan nol tombol `pesan-kursi` di sana (memang nol pernah ada).
- Label, ikon, dan tata letak tombol nol diubah.

## Gerbang sebelum kirim

1. Disk lawan git di `wordpress/theme-v5`: NOL di dua arah.
2. `bash bin/periksa-php.sh`: 11 berkas, 0 gagal.
3. `git status --short --untracked-files=no`: kosong sesudah commit `cbd08e5`.
4. `python3 bin/kirim-tema-ftp.py --coba --teliti`: kirim 3, sama 107, hapus 0.
   Tiga berkas itu persis `functions.php`, `inc/isi-beranda.php`, dan
   `patterns/hero-panggung.php`. Kiriman sungguhan sama persis, 4 detik.

`bin/dorong-tema.sh` NOL dijalankan: dia `git push` ke remote dan temp dilarang
mendorong. Commit ini menambah antrean yang menunggu god.
