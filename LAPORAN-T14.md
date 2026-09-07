# Laporan T-14: cache basi `artotel-08.jpg`

**Rekomendasi: tutup kartu ini tanpa mengerjakan apa pun.** Dua alasan yang
berdiri sendiri sendiri, jadi kalau salah satunya nanti gugur, yang lain masih
menahan kesimpulannya.

Kartu ini eksplisit menyuruh saya menetapkan dulu apakah pekerjaannya layak,
bukan langsung mengerjakannya. Nol berkas disentuh, nol kiriman.

## Alasan 1: berkasnya nol dirujuk di mana pun

`artotel-08.jpg` disapu di **19 permukaan publik**. Kemunculannya: **0**.

| Kelompok | Permukaan | Kemunculan |
|---|---|---|
| Halaman | `/`, `/tentang/`, `/kolaborasi/`, `/kontak/`, `/galeri/`, `/cerita/`, artikel, `/jadwal/`, `/acara/embracing-growth/` | 0 |
| Arsip | kategori, penulis, tanggal | 0 |
| Umpan | `/feed/`, `/cerita/feed/` | 0 |
| Mesin | `wp-sitemap.xml`, REST pages, REST posts `_embed` | 0 |
| Lain | `/?s=journaling`, halaman 404 | 0 |

**Kenapa nol, padahal grep di kode menemukan tujuh kemunculan.** Ini persis pola
yang sudah dua kali hampir menipu saya hari ini, jadi saya buka kodenya alih
alih berhenti di hasil grep. Ketujuhnya **kunci pencarian, bukan nilai yang
dicetak**:

| Tempat | Perannya |
|---|---|
| `inc/seo.php:332` | `if ( $ctx['gambar'] === ...artotel-08.jpg ) { $ctx['gambar'] = ...artotel-08-hero.webp; }` untuk og:image dan twitter:image |
| `inc/seo.php:694` | closure `$foto()`: `if ( 'artotel-08.jpg' === $berkas ) return ...artotel-08-hero.webp;` |
| `inc/seo.php:716` dan `:748` | pemanggilan `$foto('artotel-08.jpg')` di JSON-LD Organization dan LocalBusiness, dua duanya lewat substitusi di atas |
| `patterns/hero-panggung.php:38` | `if ( $tjr_foto === ...artotel-08.jpg )` lalu `$tjr_foto` **diganti** jadi `artotel-08-hero.webp` plus `srcset` |
| `inc/isi-beranda.php:123` | nilai bawaan field ACF `hero_foto`, yang lalu masuk ke perbandingan di atas |
| `inc/seo.php:328` dan `:692` | komentar yang menjelaskan dua substitusi itu |

Jadi nama berkas itu masuk sebagai kunci dan keluar sebagai `.webp`. Tidak ada
satu pun jalur di mana URL-nya sampai ke HTML, meta, JSON-LD, umpan, atau REST.
Substitusi itu memang **sengaja** dipasang di kartu sebelumnya justru karena
berkas 377 KB kepotong Hostinger, dan komentarnya masih ada di kode.

Karena nol dirujuk, cache basi pada URL itu **nol berdampak**: tidak ada
pengunjung dan tidak ada crawler yang halamannya menyuruh mereka memintanya.

## Alasan 2: cache basinya sendiri sudah tidak ada lagi

Ini mengoreksi premis kartunya, jadi saya ukur rapat sebelum mengatakannya.

Brief menyatakan generasi lama **masih** tersaji 377215 byte sesudah Flush cache
dan sesudah CDN dimatikan. Yang saya ukur sekarang:

| Cara | Permintaan | Hasil |
|---|---|---|
| Dengan cache-buster acak | 12 | **12 dari 12** mengembalikan 368126 byte |
| URL telanjang, persis yang dipakai crawler | 6 | **6 dari 6** mengembalikan 368126 byte |
| `md5` berkas terunduh lawan berkas di repo | 2 sampel | **identik**, `c76a10fa7752d1573b68a5a6f03ebbbe` |

368126 itu ukuran yang benar, sama dengan berkas di repo dan di origin.

**Header responsnya yang menjelaskan kenapa berubah.** Waktu T-12 saya mengukur,
respons datang dengan `server: hcdn` dan `x-hcdn-request-id: ...dci-edge3` atau
`dci-edge5`, dan itu yang menyajikan 377215. Sekarang **seluruh respons datang
dengan `server: LiteSpeed` dan nol header `hcdn` sama sekali**. Artinya CDN
benar benar sudah keluar dari jalur permintaan, dan yang menjawab origin
langsung.

Kesimpulan yang bisa saya tarik: **generasi lama itu memang tinggal di lapisan
CDN**, dan hilang begitu CDN benar benar keluar dari jalur. Kemungkinan
pengukuran di brief diambil saat CDN masih melayani atau saat penonaktifannya
belum menyebar. Saya tidak bisa membuktikan yang mana, dan tidak akan menebak.

## Bonus: staleness dan pemotongan sama sekali tidak menyentuh berkas yang dipakai

Selagi mengukur, saya periksa **seluruh 39 aset yang benar benar dirujuk
beranda**, empat permintaan masing masing, total 156 permintaan:

```
39 dari 39 berkas menyajikan ukuran yang sama persis dengan berkas lokal
0 dari 156 permintaan meleset
```

Termasuk beberapa JPEG besar yang dulu jadi korban pemotongan:
`snapobox-08.jpg` 394400, `pasar-jakal-02.jpg` 393912, `kolondjono-20.jpg`
376254, `sundayreads-27.jpg` 368799. Keempat empatnya utuh 4 dari 4.

Jadi dengan CDN mati, **dua gejala hilang bersamaan**: generasi basi dan
pemotongan respons. Itu satu sampel besar lagi yang searah dengan dugaan bahwa
CDN yang jadi penyebabnya.

## Kenapa saya juga TIDAK menyarankan menghapus berkasnya

Berbeda dari `artotel-08-1600.webp` yang saya buang di T-12. Yang itu benar
benar mati: nol dirujuk kode maupun halaman. `artotel-08.jpg` **hidup di
logika**, cuma tidak di keluaran:

- Ia nilai bawaan `hero_foto` di `tjr_v5_bawaan_foto()`.
- Ia kunci perbandingan di tiga jalur kode.
- Ia berkas asli sumber dari mana `artotel-08-hero.webp` dan
  `artotel-08-700.webp` diturunkan.
- Ia jaring pengaman: kalau salah satu `.webp` itu suatu saat hilang dari
  server, `hero-panggung.php` jatuh kembali ke jpg ini dan hero tetap tampil.

Menghapusnya akan menukar masalah nol dampak dengan risiko nyata.

## Kalau CDN dinyalakan lagi

Ini yang perlu diingat, karena kesimpulan di atas diukur dalam keadaan CDN mati:

- Gejala basi bisa kembali, dan berlaku untuk berkas apa pun yang pernah diganti
  dengan nama sama.
- Untuk `artotel-08.jpg` sendiri tetap nol dampak, karena Alasan 1 tidak
  bergantung pada CDN sama sekali.
- Aturan yang sudah tercatat tetap berlaku dan justru terbukti lagi hari ini:
  **dengan `max-age` gambar satu tahun, versikan nama berkas, jangan menimpa di
  tempat.**
- Yang layak diperiksa ulang saat itu bukan berkas ini, tapi **39 aset yang
  benar benar dipakai**, dengan cara yang sama seperti di bagian bonus di atas.

## Ongkos yang dihindari, supaya rekomendasinya bisa ditimbang

Kalau kartu ini dipaksa selesai, versikan namanya berarti menyentuh
`inc/seo.php` di empat titik, `patterns/hero-panggung.php`, dan
`inc/isi-beranda.php`. Jim sedang memegang `inc/seo.php` untuk tiga kartu, jadi
itu tabrakan berkas yang nyata, untuk perbaikan yang manfaatnya nol.

## Ringkasan T-14

| | |
|---|---|
| Rekomendasi | **tutup tanpa mengerjakan apa pun** |
| Berkas disentuh | 0 |
| Kiriman | 0 |
| Alasan 1 | nol dirujuk di 19 permukaan publik; tujuh kemunculan di kode semuanya kunci yang disubstitusi ke `.webp` |
| Alasan 2 | generasi basi sudah hilang, 18 dari 18 permintaan mengembalikan 368126 dan md5-nya sama dengan repo |
| Koreksi premis | brief menyatakan 377215 masih tersaji; sekarang `server: LiteSpeed`, nol header `hcdn`, dan ukurannya benar |
| Bonus | 39 aset terpakai, 156 permintaan, nol meleset, nol terpotong |
| Jangan dihapus | berkasnya kunci perbandingan di tiga jalur, nilai bawaan ACF, dan jaring pengaman kalau `.webp` hilang |

