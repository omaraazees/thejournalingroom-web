# Laporan T-12: buang `artotel-08-1600.webp` dari repo dan dari server

Sasaran: `wordpress/theme-v5/assets/img/artotel-08-1600.webp`, 246096 byte.
Umar menyetujui penghapusan.

Dua langkah, bukan satu. Kalau cuma `git rm` lalu berhenti, berkas itu berubah
jadi **yatim di server**, keadaan yang persis dibersihkan di T-11.

## Langkah 0: buktikan dulu berkasnya benar benar mati

Penghapusan itu murah dibalik lewat git, tapi mahal kalau ternyata berkasnya
masih dipakai dan hero situs jadi rusak di produksi. Jadi diperiksa tiga sisi,
bukan mengandalkan temuan T-11 kemarin.

**Satu, di seluruh repo.** Tiga kemunculan, ketiganya catatan, nol kode:

| Tempat | Sifat |
|---|---|
| `LAPORAN-T2.md:133` | catatan sejarah |
| `desain/LAPORAN-GAMBAR-0907.md:36` | tabel konversi |
| `desain/LAPORAN-GAMBAR-0907.md:50` | **rekaman keadaan LAMA**, waktu `hero-panggung.php` masih memakainya di `srcset` |

Baris ketiga itu yang paling perlu dikejar, karena kalau dibaca sekilas ia
seperti bukti berkas ini masih dipakai. Diperiksa di kodenya sendiri:
`patterns/hero-panggung.php` baris 39 sampai 46 sekarang memakai
`artotel-08-700.webp` dan `artotel-08-hero.webp`. Pergantiannya terjadi di
commit `9bf2519` yang mengecilkan hero ke bawah ambang aman dan memberinya nama
baru. Jadi dokumen itu merekam masa lalu, bukan masa kini.

**Dua, di sembilan halaman live.** Nol rujukan. `og:image` menunjuk
`artotel-08-hero.webp`.

**Tiga, alasan positif untuk membuangnya, bukan cuma ketiadaan alasan
menyimpannya.** 246096 byte ada di atas ambang pemotongan Hostinger, dan itu
angka dari pengukuran saya sendiri di T-11: 4 dari 12 permintaan terpotong,
selalu berhenti di 28210 byte, sementara `artotel-08-hero.webp` 86024 byte utuh
8 dari 8. Berkas ini bukan cuma tidak terpakai, ia juga akan rusak di sepertiga
pemuatan kalau suatu saat dirujuk lagi. Menyimpannya berarti menyimpan jebakan.

## Langkah 1: `git rm` dan commit

Commit `6eed7c8`, pesannya memuat kedua alasan di atas, bukan "hapus berkas".

## Langkah 2: kirim dengan `--hapus`, dua gerbang

**Gerbang (a), masukan dry run.** Ini gerbang yang saya tambahkan sendiri di
T-11 dan god minta dipakai lagi. Alasannya: `buang = remote - lokal`, jadi
daftar hapus baru bisa dipercaya sesudah sisi lokal dibuktikan utuh.

```
tracked 88, disk 88
di disk tapi tidak di git : NOL
di git tapi tidak di disk : NOL
pohon kerja               : bersih
periksa-php.sh            : 11 berkas, 0 gagal
```

**Gerbang (b), keluaran dry run.**

```
$ python3 bin/kirim-tema-ftp.py --coba --hapus
lokal 87 berkas, server 88 berkas
kirim 0, sama 87, hapus 1
  - assets/img/artotel-08-1600.webp
```

| Syarat | Hasil |
|---|---|
| Daftar hapus tepat satu baris | ya |
| Baris itu `assets/img/artotel-08-1600.webp` | ya, persis |
| Daftar kirim tidak berisi yang tidak diduga | **kosong** |

Catatan atas peringatan god bahwa "kirim tidak akan nol seperti T-11": ternyata
nol juga, dan itu benar. Commitnya memang cuma membuang satu berkas, nol berkas
lain berubah isi sejak pengiriman T-11, jadi tidak ada yang perlu naik. Daftar
kirim kosong itu justru bentuk paling bersih dari "nol yang tidak diduga".

**Eksekusi:** `hapus 1, kirim 0`.

## Langkah 3: verifikasi

**Daftar server dipotret sebelum dan sesudah**, dibandingkan nama demi nama
(tersimpan di `hive/agents/pam-mtqta34j/backup/remote-sebelum-t12.txt` dan
`remote-sesudah-t12.txt`):

| Ukuran | Hasil |
|---|---|
| Jumlah berkas server | 88 jadi **87** |
| Berkas yang HILANG | `assets/img/artotel-08-1600.webp`, dan cuma itu |
| Berkas BARU | NOL |
| Berkas yang ukurannya berubah | NOL |

**404 di server**, empat percobaan berturut turut dengan cache-buster acak.

**Isi sisanya utuh.** `--coba --teliti` mengunduh dan mem-hash isi asli:

| | T-11 | T-12 |
|---|---|---|
| lokal | 88 | 87 |
| server | 88 | 87 |
| sama | 88 | **87** |
| hapus | 0 | 0 |

**Aset hero yang benar benar dipakai tetap terlayani utuh:**
`artotel-08-700.webp` 61422 byte, `artotel-08-hero.webp` 86024 byte, dua duanya
persis seukuran berkasnya.

## Temuan sampingan: edge CDN menyajikan generasi lama `artotel-08.jpg`

Ditemukan saat memeriksa aset hero, dan **bukan akibat pengiriman ini**: daftar
kirim T-12 kosong, jadi berkas ini tidak saya sentuh sama sekali.

| Sumber | Ukuran |
|---|---|
| Repo lokal | 368126 byte |
| Origin lewat FTP (`--teliti` bilang identik dengan lokal) | 368126 byte |
| Dilayani `dci-edge3` dan `dci-edge5` | **377215 byte** |
| Dilayani `dci-edge4` | tanpa `content-length`, isi tidak dibandingkan |

Delapan permintaan, tiga jatuh ke edge4 dan lima ke edge3/edge5.

**Ini generasi lama, bukan korupsi.** Berkas 377215 byte itu diunduh penuh lalu
diperiksa penanda formatnya: mulai `ffd8` (SOI) dan berakhir `ffd9` (EOI), jadi
JPEG utuh dan sah, cuma bukan generasi yang sekarang ada di origin. Persis
perilaku yang sudah tercatat sebelumnya: `.htaccess` menyetel `max-age` satu
tahun untuk gambar, jadi mengganti berkas dengan nama sama membuat beberapa edge
menyajikan generasi berbeda dari satu URL.

Yang penting dari pembagian di atas: **origin sudah benar**, yang basi cuma
cache edge. Jadi ini bukan sesuatu yang bisa diperbaiki dengan mengirim ulang;
obatnya purge CDN di hPanel lewat **Performa > CDN > Flush cache** (bukan
"Cache Manager" yang layernya berbeda).

Dampaknya kecil dan itu perlu dikatakan jujur: `artotel-08.jpg` cuma fallback
`<img src>` di dalam `<picture>`, dan browser modern mengambil `<source>` webp
lebih dulu. **Saya tidak memurge CDN**, karena itu di luar kartu ini dan
menyentuh layanan yang dipakai seluruh situs.

## Ringkasan T-12

| Langkah | Hasil |
|---|---|
| Bukti berkas mati | nol rujukan di kode, nol di sembilan halaman live, tiga kemunculan tersisa semuanya catatan |
| Langkah 1, `git rm` | commit `6eed7c8` |
| Gerbang (a), masukan | 88/88 nol selisih, pohon bersih, PHP 11/0 |
| Gerbang (b), keluaran | hapus tepat 1 yang benar, kirim kosong |
| Langkah 2, eksekusi | hapus 1, kirim 0 |
| Langkah 3, verifikasi | server 88 jadi 87, cuma satu hilang, 404 empat kali, `--teliti` 87 dari 87 |
| Temuan sampingan | edge CDN menyajikan generasi lama `artotel-08.jpg`, origin benar, dilaporkan tidak dipurge |

