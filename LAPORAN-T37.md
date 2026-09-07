# T-37: harga 265.000 plus keterangan (tax incl.)

**Selesai.** Dua perubahan yang sengaja dikerjakan lewat dua jalur berbeda:
nilai lewat basis data, keterangan lewat kode tema.

## Kenapa dua jalur, dan bukan satu

Kartu benar bahwa memperlakukan ini sebagai satu perubahan adalah jebakannya.
`harga` **integer**, dan tiga peran membacanya sebagai angka:

- `offers.price` di JSON-LD memakai integernya langsung. Kalau `(tax incl.)`
  masuk ke sana, nilainya berhenti jadi angka dan `schema.org/Offer` pecah.
- `priceRange` dan meta description merakit angkanya sendiri lewat
  `number_format`, yang akan berhenti memberi pemisah ribuan begitu nilainya teks.

Jadi nilai dan keterangan tinggal di tempat yang berbeda, dan itu bukan
kompromi, itu memang pembagian yang benar.

## Field terpisah untuk keterangan: PERNAH ADA, dan Umar sendiri membuangnya

Ini yang paling perlu dicatat sebelum apa pun ditulis.

`catatan_harga` dulu persis untuk ini. Dirender kecil di sebelah angka lewat
`<span class="slot-catatan">`, disambungkan di kartu S-7 commit `244d73d`.
**Dibuang atas keputusan Umar di kartu T-3 pagi ini, commit `4bcff93`.**

Jadi membangkitkannya berarti membatalkan keputusan yang baru dibuat hari itu
juga. **Tidak saya lakukan**, dan alasannya bukan sekadar sopan santun:

Perlakuan pajak **sama untuk semua sesi**. Menyimpannya per acara berarti
mengetik kalimat yang sama berulang, dan membiarkannya bisa berbeda antar acara
tanpa ada yang menyadarinya. Justru kelebihan itu yang membuat Umar membuangnya.
`catatan_harga` tetap kosong dan tetap tidak dipakai.

## Tempat yang saya pilih, dan kenapa tepat di situ

Ditulis mati di `tjr_v5_harga_acara()`, `inc/isi-beranda.php:938`.

Fungsi itu **cuma memberi makan dua permukaan yang dibaca manusia**: `dd-harga`
di panel fakta lewat `:1106`, dan `ik-tag` di kartu sesi terdekat lewat
`hero-panggung.php:64`. Dia **nol** menyentuh `offers.price`, `priceRange`,
maupun meta description, karena ketiganya merakit angkanya sendiri.

Artinya keterangan itu mendarat tepat di tempat orang membaca harga, dan nol
mencemari satu pun angka yang dibaca mesin. Itu sifat yang saya cari, bukan
kebetulan yang saya temukan.

**Nol perubahan CSS.** `.slot-catatan` masih ada di `style.css:939`, yatim sejak
T-3 membuang PHP yang memakainya. Gayanya memang sudah dirancang untuk ini,
yaitu 12,5px warna `tinta-lembut`, jadi dipakai ulang.

**Span HTML-nya aman.** Kedua pemanggil sengaja tidak meng-`esc_html` keluaran
fungsi ini, sama seperti waktu `catatan_harga` masih hidup.

## Verifikasi di produksi

**Disapu 18 permukaan. Sisa harga lama nol di semuanya**, baik `297.300` maupun
`260.000` yang baru dipasang beberapa jam lalu.

Kesebelas tempat diperiksa satu per satu, bukan yang paling gampang dilihat:

| # | Tempat | Isi | Keterangan |
|---|---|---|---|
| 1 | `dd-harga` halaman acara | `Rp265.000 (tax incl.)` | ada |
| 2 | `dd-harga` beranda | `Rp265.000 (tax incl.)` | ada |
| 3 | `ik-tag` kartu sesi | `Rp265.000 (tax incl.)` | ada |
| 4 | meta description | `... Rp265.000. ...` | nol, benar |
| 5 | og:description | sama | nol, benar |
| 6 | twitter:description | sama | nol, benar |
| 7 | JSON-LD Event description | sama | nol, benar |
| 8 | JSON-LD `offers.price` | `265000` | nol, **wajib** |
| 9 | `priceRange` halaman acara | `Rp265.000` | nol, benar |
| 10 | `priceRange` beranda | `Rp265.000` | nol, benar |
| 11 | `priceRange` jadwal | `Rp265.000` | nol, benar |

**Tiga menampilkan keterangan, delapan sengaja tidak.** Baris 8 yang paling
penting: dia tetap `265000` telanjang, jadi `schema.org/Offer` tetap sah.

## Satu keputusan gaya yang saya ambil, dan saya sebut supaya bisa dibantah

Umar menulis `Rp 265.000` dengan spasi sesudah `Rp`. Situs ini menulisnya
**tanpa** spasi di keempat tempat `number_format` dipakai, dan sudah konsisten
begitu sejak lama.

Saya pertahankan tanpa spasi. Mengubahnya berarti menyentuh tiga tempat lain di
`inc/seo.php` supaya nol jadi campur, dan itu perubahan gaya menyeluruh yang
lebih besar daripada yang diminta kartu ini. Kalau Umar memang mau bergaya
`Rp 265.000`, itu kartu tersendiri dan saya kerjakan.

## Pengiriman

Satu berkas, `inc/isi-beranda.php`. Gerbang lengkap dijalankan, termasuk syarat
keempat soal sisi hapus:

- disk bersih terhadap HEAD sebelum kirim
- `periksa-php.sh` 11 dari 11 lolos
- `--coba`: `kirim 1, sama 97, hapus 0`
- `--coba --hapus`: `hapus 0`, jadi sisi hapus terbukti kosong sebelum mengirim
- kirim sungguhan tanpa `--hapus`: `kirim 1, sama 97, hapus 0`
