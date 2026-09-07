# LAPORAN T-2: Status sebenarnya boost resolusi foto Magnific

**Agent:** Jim (jim-mtqt9z7n) · **Tanggal:** 7 Sep 2026 · **Kartu:** T-2
**Sifat kartu:** INVENTARISASI. Nol kredit Magnific dipakai, nol deploy.

> Ditulis bertahap. Setiap kelompok foto diappend begitu selesai diperiksa.

---

## 1. Ruang lingkup: dari mana angka "sekitar 40 foto" berasal

`wordpress/aset-manifest.json` berisi **40 entri**, dan itulah sumber angka "~40" di
delegasi. Tetapi 40 itu bukan 40 foto:

| tipe | jumlah |
|---|---|
| `foto` | 28 |
| `logo` | 12 |
| **total entri manifest** | **40** |

Instruksi asli Umar adalah "semua foto asli, **bukan logo**". Jadi denominator yang benar
untuk kartu ini adalah **28 foto**, bukan 40. Angka 40 di brief adalah jumlah baris manifest,
bukan jumlah foto.

Satu foto tambahan masuk situs SESUDAH manifest ditulis: `artotel-08` (hero final, dipilih
ronde 3 pada 6-7 Sep). Foto itu tidak ada di manifest. Diperiksa terpisah di bagian 5.

---

## 2. Metode pengukuran

Diukur **lokal**, bukan dari live. Alasan ada di bagian 6: mengukur dari live pada repo ini
menghasilkan angka palsu.

1. **Baseline** = nilai `lebar`/`tinggi`/`lebar_2x`/`tinggi_2x`/`byte` yang tercatat di
   `aset-manifest.json`. Manifest ini ditulis 5 Sep, yaitu **sebelum** Magnific tersambung
   (6-7 Sep malam). Jadi manifest adalah potret dunia pra-Magnific yang tepat.
2. **Keadaan sekarang** = `sips -g pixelWidth -g pixelHeight` plus `stat -f%z` atas tiap
   berkas nyata di `wordpress/foto-hd/`.
3. **Aturan putusan**: sebuah foto dinyatakan sudah di-boost hanya kalau dimensi lokalnya
   sekarang **melebihi** baseline manifest, atau ada berkas keluaran Magnific yang bisa
   ditunjuk. Sama persis dengan baseline berarti tidak tersentuh.
4. Silang-periksa: `git log`, mtime berkas, dan riwayat generasi di akun Magnific.

Pembanding tidak pernah diambil dari sumber yang sedang diuji.

---

## 3. Tabel per foto (28 foto manifest)

Kolom `2x baseline` = tercatat manifest 5 Sep. Kolom `2x lokal sekarang` = hasil ukur `sips` hari ini.

| # | nama berkas | 2x baseline (manifest) | 2x lokal sekarang | byte baseline | byte lokal | selisih | boost? |
|---|---|---|---|---|---|---|---|
| 1 | `amco-naoki-03` | 1462x2600 | 1462x2600 | 774,631 | 774,631 | identik | **BELUM** |
| 2 | `amco-naoki-06` | 1462x2600 | 1462x2600 | 562,772 | 562,772 | identik | **BELUM** |
| 3 | `artotel-01` | 1950x2600 | 1950x2600 | 800,800 | 800,800 | identik | **BELUM** |
| 4 | `artotel-13` | 2600x1462 | 2600x1462 | 622,161 | 622,161 | identik | **BELUM** |
| 5 | `artotel-16` | 1462x2600 | 1462x2600 | 932,305 | 932,305 | identik | **BELUM** |
| 6 | `artotel-19` | 1462x2600 | 1462x2600 | 785,398 | 785,398 | identik | **BELUM** |
| 7 | `kolondjono-09` | 1950x2600 | 1950x2600 | 606,485 | 606,485 | identik | **BELUM** |
| 8 | `kolondjono-20` | 1462x2600 | 1462x2600 | 801,809 | 801,809 | identik | **BELUM** |
| 9 | `kupiku-01` | 1950x2600 | 1950x2600 | 471,923 | 471,923 | identik | **BELUM** |
| 10 | `kupiku-04` | 2600x1462 | 2600x1462 | 513,117 | 513,117 | identik | **BELUM** |
| 11 | `latar-meja` | 1900x1069 | 1900x1069 | 182,126 | 182,126 | identik | **BELUM** |
| 12 | `pasar-jakal-02` | 2600x1462 | 2600x1462 | 934,735 | 934,735 | identik | **BELUM** |
| 13 | `pasar-jakal-05` | 1462x2600 | 1462x2600 | 696,158 | 696,158 | identik | **BELUM** |
| 14 | `pasar-jakal-06` | 1462x2600 | 1462x2600 | 714,071 | 714,071 | identik | **BELUM** |
| 15 | `radian-11` | 1950x2600 | 1950x2600 | 640,851 | 640,851 | identik | **BELUM** |
| 16 | `radian-24` | 1950x2600 | 1950x2600 | 508,172 | 508,172 | identik | **BELUM** |
| 17 | `radian-30` | 1950x2600 | 1950x2600 | 682,761 | 682,761 | identik | **BELUM** |
| 18 | `radian-33` | 1950x2600 | 1950x2600 | 1,025,891 | 1,025,891 | identik | **BELUM** |
| 19 | `snapobox-02` | 1467x2600 | 1467x2600 | 625,378 | 625,378 | identik | **BELUM** |
| 20 | `snapobox-08` | 1462x2600 | 1462x2600 | 739,183 | 739,183 | identik | **BELUM** |
| 21 | `snapobox-11` | 2600x1462 | 2600x1462 | 845,258 | 845,258 | identik | **BELUM** |
| 22 | `statement-beauty-01` | 1462x2600 | 1462x2600 | 516,416 | 516,416 | identik | **BELUM** |
| 23 | `sundayreads-08` | 1950x2600 | 1950x2600 | 933,025 | 933,025 | identik | **BELUM** |
| 24 | `sundayreads-12` | 1950x2600 | 1950x2600 | 488,580 | 488,580 | identik | **BELUM** |
| 25 | `sundayreads-20` | 1950x2600 | 1950x2600 | 533,878 | 533,878 | identik | **BELUM** |
| 26 | `sundayreads-27` | 1950x2600 | 1950x2600 | 791,282 | 791,282 | identik | **BELUM** |
| 27 | `wardah-04` | 1950x2600 | 1950x2600 | 502,731 | 502,731 | identik | **BELUM** |
| 28 | `wardah-09` | 1462x2600 | 1462x2600 | 781,689 | 781,689 | identik | **BELUM** |

**Hasil ukur: 28 dari 28 foto identik dengan baseline pra-Magnific, sampai byte terakhir.** Nol berkas berubah.

---

## 4. Bukti silang: apakah ada keluaran Magnific di mana pun

Empat jalur diperiksa. Semuanya negatif.

**4.1 Berkas HD kanonik.** Semua 81 berkas di `wordpress/foto-hd/` bertanggal 5 Sep,
pukul 17.37 sampai 18.35. Nol berkas di sana disentuh pada 6 atau 7 Sep, yaitu jendela waktu
Magnific tersambung. Sama untuk `wordpress/foto-webp/`.

**4.2 Riwayat git.** Nol commit menyebut Magnific atau upscale. Pohon kerja bersih kecuali
`.impeccable/` yang tidak berhubungan. Jadi tidak ada hasil yang dicommit lalu dibalik.

**4.3 Aset tema, satu-satunya gambar yang berubah sesudah 6 Sep.** Terlihat menjanjikan,
ternyata kebalikannya. Semua dibatasi sisi terpanjang **1600 px**, sedangkan `foto-hd@2x`
sudah **2600 px**. Itu operasi MENGECILKAN, bukan membesarkan, dan memang cocok dengan commit
yang ada: `c802496` "Resize + WebP 18 foto tema oversize" dan `39206b6` "Pangkas berat gambar
tema (kartu P-3)". Kartu P-3 mengecilkan berkas supaya lolos ambang pemotongan Hostinger.
Tidak ada hubungannya dengan Magnific.

**4.4 Riwayat akun Magnific lewat MCP.** Di seluruh akun, jendela 6 sampai 8 Sep hanya berisi
**6 item**, dan keenamnya milik pekerjaan pribadi Umar yang tidak berhubungan (4 `text-to-image`
peta walking tour Yogyakarta, 2 `upload-reference`). **Nol pekerjaan upscale.** Diperiksa di
`from: history` dan `from: all-assets`, hasilnya sama.

### Peringatan penting soal bukti 4.4

Riwayat MCP **tidak boleh dipakai sendirian** untuk menyimpulkan apa pun, dan ini terbukti,
bukan dugaan. Uji end-to-end god yang SUDAH DIPASTIKAN berhasil (`radian-11.jpg`,
975x1300 ke 1944x2600) **juga tidak muncul** di riwayat MCP. Pencarian nama "radian"
mengembalikan nol hasil.

Artinya: **pekerjaan yang dijalankan lewat API key tidak terindeks di riwayat akun yang
dibaca MCP.** MCP hanya menampilkan pekerjaan lewat aplikasi web. Jadi 4.4 tidak bisa
membuktikan Jim tidak menjalankan apa pun. Bukti yang menentukan tetap 4.1 sampai 4.3, yaitu
keadaan berkas nyata.

Catatan brief bahwa MCP adalah "cara tercepat melihat bukti apa yang pernah diproses Jim"
tidak berlaku untuk alur API. Layak diwariskan.

---

## 5. Foto di luar manifest

`artotel-08` (hero final, dipilih ronde 3 pada 6-7 Sep, sesudah manifest ditulis) tidak ada
di manifest. Diukur terpisah: berkas tema `artotel-08.jpg` berukuran **1600x900**. Itu
konsisten dengan re-export dari HEIC asli lalu dikecilkan, sama seperti foto tema lain, dan
sama sekali bukan hasil upscale. Turunannya (`artotel-08-1600.webp`, `artotel-08-700.webp`,
`artotel-08-hero.webp`) semuanya lebih kecil lagi, sesuai commit `9bf2519` yang justru
MENGECILKAN hero ke 86 KB supaya lolos ambang pemotongan Hostinger.

Jadi `artotel-08` juga belum di-boost.

---

## 6. Kenapa dimensi live tidak dipakai sebagai angka putusan

Brief meminta kolom "dimensi dan ukuran live". Kolom itu sengaja TIDAK dijadikan dasar putusan,
dan ini keputusan metodologis yang perlu dicatat, bukan pekerjaan yang dilewat.

Tiga sifat produksi membuat angka live tidak bisa dipercaya untuk pertanyaan kartu ini:

1. **Hostinger memotong respons berkas besar secara acak** sambil tetap mengirim
   `content-length` penuh. Berkas 163 KB ke atas gagal sekitar separuh dari 6 permintaan.
   Hampir semua berkas `@2x` yang relevan di sini berada di 470 KB sampai 1,0 MB, artinya
   jauh di atas ambang itu. Mengukur dimensinya dari live akan sering menghasilkan angka
   dari berkas yang terpotong.
2. **`.htaccess` menyetel `max-age` satu tahun**, jadi satu URL bisa menyajikan beberapa
   generasi berbeda dari edge CDN berbeda. Commit `23b084a` pada 7 Sep mencatat persis itu
   terjadi: "CDN nyampur 3 generasi beda di edge".
3. Situs live sekarang menyajikan **turunan tema 1600 px**, bukan `foto-hd@2x` 2600 px. Jadi
   angka live mengukur pipeline penyajian, bukan pertanyaan "apakah master sudah di-boost".

Pertanyaan kartu ini adalah apakah master beresolusi lebih tinggi sudah ADA. Itu pertanyaan
tentang berkas lokal, dan dijawab dengan tepat secara lokal. Menambah kolom live hanya akan
menyuntikkan angka yang diketahui palsu ke dalam tabel keputusan.

---

## 7. Anomali kredit yang perlu diputuskan god

Ini temuan yang tidak diminta kartu, tapi berkonsekuensi biaya, jadi dicatat.

| | kredit |
|---|---|
| Paket total | 20.000 |
| Tercatat di wiki sesudah uji `radian-11` god (6 Sep, ~23.42) | ~19.100 tersedia |
| Terbaca hari ini lewat `account_balance` | **13.220 tersedia, 6.780 terpakai** |
| Selisih sejak catatan wiki | **~5.880 terpakai** |

Yang terlihat di riwayat aplikasi web pada jendela itu hanya **4 generasi `text-to-image`**
(peta walking tour, pekerjaan pribadi Umar) dan 2 unggahan referensi. Empat generasi gambar
tidak masuk akal menghabiskan ~5.880 kredit.

Sisa yang tidak terjelaskan itu **konsisten dengan sekumpulan pekerjaan upscale lewat API**,
yaitu persis jenis pekerjaan yang sudah dibuktikan di bagian 4.4 TIDAK muncul di riwayat MCP.
Pada tarif Precision 2x (90 sampai 270 kredit per foto tergantung tier ukuran), ~5.880 kredit
setara kira-kira **22 sampai 65 foto**. Rentang itu mencakup lingkup 28 foto.

**Hipotesis paling masuk akal: Jim sebelumnya benar-benar menjalankan sebagian atau seluruh
batch, kredit benar-benar terpakai, tetapi hasilnya tidak pernah ditulis ke repo dan sekarang
hilang bersama agent-nya.**

Ini **hipotesis, bukan fakta**. Dua hal melemahkannya dan harus disebut: angka 19.1K di wiki
adalah angka bulat tanpa stempel waktu persis, dan akun ini juga dipakai Umar untuk pekerjaan
pribadi yang tarifnya tidak saya ukur satu per satu.

**Cara memastikan, tanpa biaya:** halaman penggunaan/tagihan di akun Magnific milik Umar
mencantumkan riwayat pemakaian kredit per pekerjaan, termasuk yang lewat API. Itu di luar
kewenangan kartu ini. Diserahkan ke god.

Konsekuensinya nyata: kalau hipotesis ini benar, menjalankan ulang batch berarti **membayar
dua kali** untuk pekerjaan yang sama.

---

## 8. Hitungan akhir

> ### 0 dari 29 foto sudah di-boost. 29 belum.

- **28** foto bertipe `foto` di `aset-manifest.json`: **0 sudah, 28 belum.** Seluruh 28
  identik dengan baseline pra-Magnific sampai byte terakhir.
- **1** foto luar manifest (`artotel-08`, hero final): **belum.**
- **Total: 0 dari 29 sudah, 29 belum.**
- 12 entri `logo` di manifest **di luar lingkup** sesuai instruksi Umar "bukan logo".

Uji `radian-11` milik god memang berhasil, tapi keluarannya (1944x2600) tidak pernah mendarat
di repo: `radian-11@2x.jpg` lokal masih 1950x2600 milik pipeline resize 5 Sep. Jadi uji itu
tidak menambah satu pun foto ke kolom "sudah".

**Status kartu asal yang benar: BELUM DIKERJAKAN, bukan setengah jadi.** Di sisi repo,
tidak ada satu pun artefak yang bisa diselamatkan dari pekerjaan Jim sebelumnya.

---

## 9. Daftar 29 foto yang belum di-boost

Jalur master terbesar yang ada sekarang, yaitu calon masukan untuk upscale.

| # | nama | master terbesar sekarang | dimensi | dipakai di |
|---|---|---|---|---|
| 1 | `amco-naoki-03` | `wordpress/foto-hd/amco-naoki-03@2x.jpg` | 1462x2600 | cetakan |
| 2 | `amco-naoki-06` | `wordpress/foto-hd/amco-naoki-06@2x.jpg` | 1462x2600 | baru-lewat |
| 3 | `artotel-01` | `wordpress/foto-hd/artotel-01@2x.jpg` | 1950x2600 | (tidak dipakai v5) |
| 4 | `artotel-13` | `wordpress/foto-hd/artotel-13@2x.jpg` | 2600x1462 | cta |
| 5 | `artotel-16` | `wordpress/foto-hd/artotel-16@2x.jpg` | 1462x2600 | tentang, kolaborator |
| 6 | `artotel-19` | `wordpress/foto-hd/artotel-19@2x.jpg` | 1462x2600 | cetakan |
| 7 | `kolondjono-09` | `wordpress/foto-hd/kolondjono-09@2x.jpg` | 1950x2600 | (tidak dipakai v5) |
| 8 | `kolondjono-20` | `wordpress/foto-hd/kolondjono-20@2x.jpg` | 1462x2600 | cetakan |
| 9 | `kupiku-01` | `wordpress/foto-hd/kupiku-01@2x.jpg` | 1950x2600 | jadwal |
| 10 | `kupiku-04` | `wordpress/foto-hd/kupiku-04@2x.jpg` | 2600x1462 | cetakan |
| 11 | `latar-meja` | `wordpress/foto-hd/latar-meja@2x.jpg` | 1900x1069 | global |
| 12 | `pasar-jakal-02` | `wordpress/foto-hd/pasar-jakal-02@2x.jpg` | 2600x1462 | cetakan |
| 13 | `pasar-jakal-05` | `wordpress/foto-hd/pasar-jakal-05@2x.jpg` | 1462x2600 | baru-lewat |
| 14 | `pasar-jakal-06` | `wordpress/foto-hd/pasar-jakal-06@2x.jpg` | 1462x2600 | galeri |
| 15 | `radian-11` | `wordpress/foto-hd/radian-11@2x.jpg` | 1950x2600 | galeri |
| 16 | `radian-24` | `wordpress/foto-hd/radian-24@2x.jpg` | 1950x2600 | tentang, cta |
| 17 | `radian-30` | `wordpress/foto-hd/radian-30@2x.jpg` | 1950x2600 | cetakan |
| 18 | `radian-33` | `wordpress/foto-hd/radian-33@2x.jpg` | 1950x2600 | (tidak dipakai v5) |
| 19 | `snapobox-02` | `wordpress/foto-hd/snapobox-02@2x.jpg` | 1467x2600 | baru-lewat |
| 20 | `snapobox-08` | `wordpress/foto-hd/snapobox-08@2x.jpg` | 1462x2600 | cetakan |
| 21 | `snapobox-11` | `wordpress/foto-hd/snapobox-11@2x.jpg` | 2600x1462 | hero |
| 22 | `statement-beauty-01` | `wordpress/foto-hd/statement-beauty-01@2x.jpg` | 1462x2600 | (tidak dipakai v5) |
| 23 | `sundayreads-08` | `wordpress/foto-hd/sundayreads-08@2x.jpg` | 1950x2600 | galeri |
| 24 | `sundayreads-12` | `wordpress/foto-hd/sundayreads-12@2x.jpg` | 1950x2600 | cetakan |
| 25 | `sundayreads-20` | `wordpress/foto-hd/sundayreads-20@2x.jpg` | 1950x2600 | (tidak dipakai v5) |
| 26 | `sundayreads-27` | `wordpress/foto-hd/sundayreads-27@2x.jpg` | 1950x2600 | hero, cta |
| 27 | `wardah-04` | `wordpress/foto-hd/wardah-04@2x.jpg` | 1950x2600 | cetakan |
| 28 | `wardah-09` | `wordpress/foto-hd/wardah-09@2x.jpg` | 1462x2600 | galeri |
| 29 | `artotel-08` | `wordpress/theme-v5/assets/img/artotel-08.jpg` | 1600x900 | hero |

Daftar nama saja, untuk disalin:

```
amco-naoki-03
amco-naoki-06
artotel-01
artotel-13
artotel-16
artotel-19
kolondjono-09
kolondjono-20
kupiku-01
kupiku-04
latar-meja
pasar-jakal-02
pasar-jakal-05
pasar-jakal-06
radian-11
radian-24
radian-30
radian-33
snapobox-02
snapobox-08
snapobox-11
statement-beauty-01
sundayreads-08
sundayreads-12
sundayreads-20
sundayreads-27
wardah-04
wardah-09
artotel-08
```
---

## 10. Perkiraan kredit untuk menyelesaikan sisanya

Tarif diambil dari `simulate_cost` (alat baca, **nol kredit terpakai**) untuk
`images_upscale` mode Precision 2x. Biaya bergantung dimensi masukan:

| tier ukuran masukan | kredit per foto | 29 foto |
|---|---|---|
| S | 90 | **2.610** |
| M | 180 | **5.220** |
| L | 270 | **7.830** |
| XL | 1.080 | 31.320 (melebihi saldo) |

Saldo tersedia sekarang **13.220 kredit**.

**Dua skenario yang masuk akal:**

- **Masukan berkas `1x`** (975x1300, ~1,3 MP), persis seperti uji god yang menghasilkan
  1944x2600. Kemungkinan besar tier S sampai M: **~2.600 sampai 5.200 kredit**. Terjangkau.
  Tetapi hasilnya hanya menyamai master 2600 px yang SUDAH ADA, jadi nyaris tidak menambah
  resolusi. Hanya bermakna kalau tujuannya kualitas piksel, bukan jumlah piksel.
- **Masukan berkas `@2x`** (2600 px, ~5 MP) untuk benar-benar naik ke ~5200 px. Tier M sampai
  L: **~5.200 sampai 7.830 kredit**. Ini yang benar-benar menaikkan resolusi. Masih di bawah
  saldo, tapi memakan 40 sampai 59 persen sisa kredit Umar.

Catatan: mode yang tepat adalah `ultra-photo` (Magnific Precision photo, "paling setia pada
asli, nol kreativitas"), bukan `creative`. Konsisten dengan keputusan sesi sebelumnya.

---

## 11. Yang perlu diputuskan sebelum eksekusi

Tiga hal, urut kepentingan. Semuanya di luar kewenangan kartu inventarisasi ini.

**1. Cek dulu apakah kredit sudah pernah terbakar (bagian 7).** Kalau Jim sebelumnya sudah
menjalankan batch, mengulang berarti bayar dua kali. Halaman penggunaan akun Magnific
menjawabnya tanpa biaya. Ini harus lebih dulu dari keputusan lain.

**2. Foto asli 4032 px tidak ada di repo, dan itu mengubah perhitungan.** Manifest mencatat
`sisi_terpanjang_asli` 4032 px untuk hampir semua foto, tapi master terbesar di repo cuma
2600 px, dan berkas `foto-2026/` sudah dikecilkan ke 1600 px. Nol berkas `.heic` di seluruh
`~/Developer`. Artinya foto ada di perangkat atau iCloud Umar, bukan di repo.

Konsekuensinya: **untuk naik dari 2600 px ke 4032 px, tidak perlu Magnific sama sekali.**
Cukup ekspor ulang dari HEIC asli. Itu gratis, dan detailnya nyata, bukan detail yang
ditebak AI. Magnific baru benar-benar diperlukan untuk melewati 4032 px.

Meminta Umar menyalin HEIC asli ke repo kemungkinan besar memberi sebagian besar kenaikan
resolusi yang diminta, dengan nol kredit. Ini layak ditanyakan sebelum membelanjakan apa pun.

**3. Ambang pemotongan Hostinger belum selesai, dan menarik ke arah berlawanan.** Sepanjang
7 Sep arah kerja situs justru MENGECILKAN berkas (kartu P-3, commit `9bf2519` menurunkan hero
ke 86 KB) karena berkas di atas 163 KB gagal termuat sekitar separuh waktu. Foto hasil upscale
akan jauh lebih besar lagi. Selama akar masalah di sisi hosting belum beres, **master
beresolusi tinggi tidak bisa disajikan langsung ke pengunjung**, dan hanya berguna sebagai
arsip atau sumber turunan kecil. Itu tidak membatalkan pekerjaannya, tapi mengubah urgensinya.

---

## 12. Kepatuhan batasan

- **Nol kredit Magnific terpakai.** Hanya alat baca: `account_balance`, `creations_search`,
  `images_upscale_modes_list`, `simulate_cost`. Nol foto diproses.
- **Nol deploy.** `bin/kirim-tema-ftp.py` dan `bin/dorong-tema.sh` tidak dijalankan.
- **Nol berkas repo diubah** selain menulis laporan ini.
- Ditulis bertahap, diappend per kelompok, bukan dibuffer di konteks lalu ditulis sekali.
- Nol em dash.
