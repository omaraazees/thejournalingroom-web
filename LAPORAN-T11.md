# Laporan T-11: buang berkas yatim di server

Satu berkas, `assets/img/artotel-08-1600-v2.webp` (246096 byte). Ada di server,
nol di repo, nol dirujuk tema. Sisa penamaan ulang foto hero pagi 7 Sep.

Umar memberi lampu hijau, hive yang mengeksekusi.

## Kenapa ini butuh kontrak, bukan sekadar menjalankan satu perintah

`--hapus` menghapus **semua** berkas server yang tidak ada di lokal, bukan satu
berkas yang kita maksud. Flag itu belum pernah dijalankan di produksi. Yang
diketahui dari pengiriman T-10 cuma `hapus 0` pada mode **tanpa** `--hapus`, dan
itu bukan bukti bahwa daftar hapusnya berisi satu berkas. Jadi urutannya
ditetapkan god lebih dulu, dan saya jalankan apa adanya.

## Langkah 0: pastikan folder lokal utuh sebelum menghitung daftar hapus

Ini bukan bagian dari kontrak god, saya tambahkan sendiri karena
`buang = remote - lokal`. Kalau satu berkas lokal hilang tanpa saya sadari, ia
akan muncul di daftar hapus dan ikut terbuang dari server.

```
tracked 89, disk 89
di disk tapi tidak di git : NOL
di git tapi tidak di disk : NOL
```

Aman. Daftar hapus yang dihitung sesudah ini bisa dipercaya.

## Langkah 1 dan 2: dry run, dan daftar hapus lengkapnya

```
$ python3 bin/kirim-tema-ftp.py --coba --hapus
lokal 88 berkas, server 89 berkas
kirim 0, sama 88, hapus 1
  - assets/img/artotel-08-1600-v2.webp
```

Daftar hapus **lengkap**, bukan jumlahnya saja, seperti diminta. Satu baris.
Perhatikan juga `kirim 0`: jalan ini murni penghapusan, nol berkas ikut naik,
jadi nol kesempatan perubahan lain menyelinap.

## Langkah 3: gerbang

| Syarat | Hasil |
|---|---|
| Daftar hapus berisi tepat satu berkas | ya, 1 |
| Berkas itu `assets/img/artotel-08-1600-v2.webp` | ya, persis |
| Daftar tidak kosong | ya, tidak kosong |

**Lolos.** Nol penyaringan manual, nol penghapusan di luar skrip, nol akal
akalan atas daftarnya.

Satu hal yang terlihat di potret server dan layak dicatat sebelum eksekusi:
ada juga `assets/img/artotel-08-1600.webp` (tanpa `-v2`) di server, ukurannya
sama persis 246096 byte. Berkas itu **tidak** masuk daftar hapus karena ada di
repo. Saya tidak menyentuhnya. Ada catatan terpisah soal berkas ini di bawah.

## Langkah 4: eksekusi

```
$ python3 bin/kirim-tema-ftp.py --hapus
lokal 88 berkas, server 89 berkas
kirim 0, sama 88, hapus 1
  - assets/img/artotel-08-1600-v2.webp
selesai
```

## Langkah 5: verifikasi, nol berkas lain hilang

**Daftar berkas server dipotret sebelum dan sesudah**, disimpan di
`hive/agents/pam-mtqta34j/backup/remote-sebelum-t11.txt` dan
`remote-sesudah-t11.txt`, lalu dibandingkan nama demi nama:

| Ukuran | Hasil |
|---|---|
| Jumlah berkas server | 89 jadi **88** |
| Berkas yang HILANG | `assets/img/artotel-08-1600-v2.webp`, dan cuma itu |
| Berkas BARU yang tidak diminta | NOL |
| Berkas yang UKURANNYA berubah | NOL |

**Berkas benar hilang dari web server.** Diminta empat kali dengan cache-buster
acak, konsisten:

```
percobaan 1..4: http=404
```

**Tetangganya tetap terlayani** dan ukurannya benar.

**Isi sisanya masih identik.** `--coba --teliti` mengunduh dan mem-hash isi
asli di server, bukan membaca manifes:

| | T-10 kemarin | T-11 sekarang |
|---|---|---|
| lokal | 88 | 88 |
| server | 89 | **88** |
| kirim | 0 | 0 |
| sama | 88 | **88** |
| hapus | 0 | 0 |

Delapan puluh delapan berkas lokal tetap identik byte per byte dengan yang ada
di server. Yang berubah cuma hitungan sisi server, dari 89 jadi 88, persis satu
berkas yang memang dituju.

## Jawaban untuk pertanyaan god: bisakah skrip ini menghapus SATU berkas tertentu

**Tidak bisa. Ini keterbatasan alat, dan layak dicatat.**

Seluruh permukaan argumen skrip cuma tiga bendera boolean:

```python
hapus  = "--hapus"  in sys.argv     # baris 188
coba   = "--coba"   in sys.argv     # baris 189
teliti = "--teliti" in sys.argv     # baris 190
```

Nol argumen jalur, nol pola, nol daftar. `LOKAL` dan `REMOTE` konstanta di
kepala berkas. Satu satunya jalur penghapusan adalah `--hapus`, dan ia selalu
bekerja atas himpunan penuh `remote - lokal`:

```python
buang = sorted(set(remote) - set(lokal)) if hapus else []
```

Artinya menghapus satu berkas tertentu **selalu** berarti mempercayakan bahwa
himpunan itu kebetulan berisi satu anggota, dan itulah sebabnya gerbang di
Langkah 3 diperlukan. Kalau suatu saat ada dua berkas yatim dan cuma satu yang
ingin dibuang, alat ini **bukan alat yang tepat** dan gerbangnya akan menahan,
sesuai keputusan god.

Saya **tidak membangun** kemampuan hapus-satu-berkas sekarang. God menyebutnya
"layak dicatat sebagai keterbatasan alat, bukan sesuatu yang harus kamu bangun
sekarang", dan saya setuju: menambah jalur penghapusan bertarget ke skrip deploy
itu perubahan yang menuntut ujinya sendiri, dan waktunya bukan di tengah kartu
pembersihan.

## Temuan sampingan: kembarannya masih ada di REPO, dan terbukti bermasalah

`assets/img/artotel-08-1600.webp` (tanpa `-v2`), 246096 byte, ukurannya sama
persis dengan yang baru saya buang. Bedanya: yang ini **ada di repo**, jadi ia
bukan yatim dan bukan sasaran kartu ini.

Dua fakta soal berkas ini, dua duanya diukur bukan ditaksir:

**Satu, nol yang merujuknya.** Dicari di seluruh `wordpress/theme-v5`: nol
kemunculan. Dicari di HTML beranda live: nol. Yang benar benar dipakai hero
sekarang `artotel-08-700.webp`, `artotel-08-hero.webp`, dan `artotel-08.jpg`.

**Dua, ia memang terpotong Hostinger.** Diminta dua belas kali dengan
cache-buster acak:

| Berkas | Ukuran seharusnya | Utuh | Terpotong |
|---|---|---|---|
| `artotel-08-1600.webp` | 246096 | 8 dari 12 | **4 dari 12**, selalu di 28210 byte |
| `artotel-08-hero.webp` | 86024 | 8 dari 8 | 0 dari 8 |

Sepertiga permintaan gagal pada berkas 246 KB, nol gagal pada berkas 86 KB.
Ini menegaskan lagi ambang yang sudah tercatat sebelumnya, dan menegaskan bahwa
membuang yang `-v2` tadi memang benar, bukan sekadar rapi rapi: kalau suatu saat
ada yang merujuk berkas seukuran itu, ia akan rusak di sepertiga pemuatan.

**Saya tidak membuangnya.** Ia ada di repo, jadi menghapusnya keputusan yang
berbeda kelas dari membuang berkas yatim di server, dan tidak diminta kartu ini.
Dilaporkan supaya jadi keputusan sadar, bukan sisa yang terlupakan.

## Ringkasan T-11

| Langkah | Hasil |
|---|---|
| Gerbang pra, folder lokal utuh | 89/89, nol selisih |
| Dry run `--coba --hapus` | daftar berisi tepat satu berkas, yang benar |
| Gerbang god | lolos |
| Eksekusi `--hapus` | hapus 1, kirim 0 |
| Server sebelum lawan sesudah | 89 jadi 88, cuma satu yang hilang, nol ukuran berubah |
| Berkas di web server | 404 di empat percobaan |
| `--coba --teliti` | 88 dari 88 identik, sama seperti kemarin |
| Bisakah hapus satu berkas tanpa `--hapus` | **tidak bisa**, keterbatasan alat, dicatat bukan dibangun |
| Temuan sampingan | kembarannya di repo nol dirujuk dan terpotong 4 dari 12 |

