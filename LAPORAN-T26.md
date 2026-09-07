# T-26: kiriman T-21 dan T-20 milik Jim

Commit yang dikirim: `3f778f9`. Dua berkas, `functions.php` dan `inc/seo.php`.
Kiriman ini uji pertama kode tersebut di lingkungan sungguhan, sama seperti T-19:
berkas PHP tidak bisa dititipkan lewat basis data seperti templat blok, jadi
verifikasi Jim sebelumnya tidak pernah menyentuh jalur ini.

## Pra-kirim

- `wordpress/theme-v5/` bersih terhadap HEAD. Nol berkas berubah, nol tak terlacak.
- md5 di disk sama dengan md5 di HEAD untuk kedua berkas kartu. Yang dikirim
  memang isi commit, bukan pekerjaan Jim yang belum jadi.
- `aa1569a` (commit sesudahnya) cuma menambah `LAPORAN-T21-T20-T5.md`, nol berkas tema.
- `bin/periksa-php.sh`: 11 berkas, 0 gagal.
- `.claude/`, `.cc-writes`, `CATATAN.md` ada di dalam folder tema tapi tersaring skrip
  (tambalan `53b401d`). Terbukti di keluaran kirim: **lokal 88, server 88**.
- Kiriman: **kirim 2, sama 86, hapus 0**. Persis dua berkas kartu.

## Verifikasi

Semua diukur pada HTML yang benar-benar tersaji, bukan sumber. Total 45 permintaan
untuk sapuan regresi ditambah sapuan awal.

**1. Skip-link.** Kesebelas URL terbit memunculkan `Lewati ke konten`. Nol halaman
masih memunculkan `Skip to content`.

**2. Judul pencarian.** `/?s=journaling` h1-nya `Hasil pencarian untuk: “journaling”`.
Kata kuncinya utuh termasuk tanda kutip lengkungnya, karena yang diganti cuma awalan.

**3. Arsip penulis.** `/cerita/author/the-journaling-room/` menjawab
`<meta name='robots' content='max-image-preview:large, noindex, follow' />`.
`follow` menyala seperti yang dimaksudkan Jim.

**4. Kebocoran noindex, butir terpenting.** Disapu seluruhnya, bukan sampel:

| Rute | noindex |
|---|---|
| 11 URL terbit dari sitemap (beranda, acara, 3 cerita, /cerita/, galeri, jadwal, kolaborasi, kontak, tentang) | tidak |
| `/cerita/kategori/panduan-journaling/` | tidak |
| `/format/journaling-workshop/` | tidak |
| `/kota/yogyakarta/` | tidak |
| 404 | tidak |
| `/cerita/author/the-journaling-room/` | **ya, memang sasarannya** |

Nol kebocoran. Satu-satunya halaman ber-noindex selain arsip penulis adalah
`/?s=` dengan `noindex, follow`, dan itu **bukan** dari filter Jim: itu perilaku
bawaan WordPress untuk hasil pencarian, sudah ada sebelum kartu ini. Filter Jim
bersyarat `is_author()` saja.

**5. Sapuan regresi byte.** 15 rute, 3 permintaan masing-masing. Ketiga ukuran
identik di setiap rute, dan ketiganya berakhir di `</html>`. Nol tidak stabil,
nol terpotong. CDN masih mati dan tidak ada gejala pemotongan yang kembali.

## Yang Jim nyatakan belum diuji, sekarang sudah

Efek samping filter `gettext` terhadap string inti lain yang berbunyi sama.
Ongkosnya nol karena bisa menumpang sapuan yang sudah jalan. Dihitung per rute
di 15 rute: `Lewati ke konten` x1, `Buka menu` x1, `Tutup menu` x1, dan
`Skip to content` / `Open menu` / `Close menu` masing-masing **x0**.

Artinya peta tiga kunci itu kena tepat tiga kali per halaman, tidak lebih. Tidak
ada string inti lain di permukaan publik yang ikut tergeser. Batasnya perlu
disebut jujur: yang tersapu adalah HTML yang tersaji ke pengunjung anonim.
Bilah admin dan layar wp-admin tidak masuk sapuan ini.

## Catatan di luar kartu, tidak dikerjakan

Halaman 404 tidak ber-noindex. Bukan regresi, keadaannya sudah begitu sebelum
kiriman ini, dan bukan bagian dari T-20. Disebut supaya tercatat, bukan diusulkan.
