# T-32: nama berkas yang berbohong

## Ringkas

Sebelum: **5 berkas yang MASIH DIPAKAI** punya nama yang tidak cocok dengan isinya.
Sesudah: **0**. Semua rujukan di kode tema sekarang memakai nama jujur.

Sisanya, 14 berkas yang namanya tidak cocok, sudah tidak dirujuk kode dan **didaftar lengkap**
di `wordpress/theme-v5/assets/img/CATATAN.md` supaya jebakan yang sama tidak menangkap orang
berikutnya. Berkas itu tidak ikut terkirim ke server, karena `LEWATI` di `kirim-tema-ftp.py`
melewatkan nama `CATATAN.md`.

## 1. Yang dipakai dan namanya bohong

| berkas | isi sebenarnya | dirujuk di |
|---|---|---|
| `artotel-08.jpg` | artotel-13 | `inc/isi-beranda.php` (default `hero_foto`), `patterns/hero-panggung.php` (pemicu), `inc/seo.php` (4 tempat) |
| `artotel-08-hero.webp` | artotel-13 | `inc/seo.php` (2 tempat, gambar og dan JSON-LD) |
| `pasar-jakal-06.jpg` | pasar-jakal-07 | `inc/isi-beranda.php` (default `bento_4`) |
| `pasar-jakal-06.webp` | pasar-jakal-07 | pendamping webp dari yang di atas |
| `latar-meja.jpg` | **ternyata JUJUR**, lihat bagian 3 | `style.css` baris 32 |

**Temuan yang tidak kuduga:** `artotel-08.jpg` ternyata bukan cuma dipakai pattern hero. Ia
juga jadi sumber **gambar og:image, twitter:image, dan JSON-LD** di `inc/seo.php`, lewat dua
jalur kode terpisah. Jadi ini bukan sekadar nama jelek di satu tempat, ia nama jelek yang
sudah menyebar ke metadata sosial situs.

## 2. Cara memperbaikinya, dan kenapa nyaris nol berkas baru

Berkas bernama **`artotel-13.jpg` dan `artotel-13.webp` SUDAH ADA**, isinya benar artotel-13,
dan sudah ada di server. Jadi hero tidak butuh berkas baru sama sekali, cuma rujukannya
diarahkan ulang.

| berkas | perubahan |
|---|---|
| `inc/isi-beranda.php` | default `hero_foto`: `artotel-08.jpg` jadi `artotel-13.jpg`; default `bento_4`: `pasar-jakal-06.jpg` jadi `pasar-jakal-07.jpg` |
| `patterns/hero-panggung.php` | pemicu perbandingan ikut `artotel-13.jpg` |
| `inc/seo.php` | dua jalur kode kini memakai `artotel-13.jpg` dan mengembalikan `artotel-13-panggung-v1.webp` |
| `assets/img/pasar-jakal-07.jpg` dan `.webp` | **salinan byte identik** dari `pasar-jakal-06.*`, cuma namanya jujur |

Nol berkas ditimpa di tempat, sesuai aturan cache setahun.

**Efek samping yang bagus di seo.php.** Special case di sana ada karena jpg hero 380-an KB
terbukti kepotong Hostinger kalau diminta crawler apa adanya, jadi kodenya menukar ke webp
yang lebih kecil. Sekarang penukarannya mengarah ke `artotel-13-panggung-v1.webp`, 85,6 KB,
1600x900. Sebelumnya `artotel-08-hero.webp`, 86,0 KB, 1280x720. Jadi **gambar sosial situs
ikut naik dari 1280 px ke 1600 px**, dan itu di atas anjuran minimum og:image 1200 px.

Komentar sejarah di `seo.php` sengaja kupertahankan dan kuperbarui, jadi masih tertulis bahwa
berkas ini dulu bernama `artotel-08.jpg` dan gagal 80 persen dari lima percobaan. Menghapus
sejarahnya akan membuat orang berikutnya mengira special case itu tidak perlu.

## 3. KOREKSI ATAS LAPORAN T-30-KU SENDIRI

Di T-30 aku menulis `latar-meja.jpg` termasuk nama yang berbohong, "isinya potongan
artotel-17". **Itu salah, dan kutarik.**

`latar-meja.jpg` isinya memang meja kerja: jurnal terbuka, pena, stiker, stempel, kotak
perlengkapan. Namanya jujur. Yang terjadi, ukurannya **2200x1237, lebih besar dari master mana
pun di `foto-2026`** (paling besar 1600 px), jadi foto itu **bukan berasal dari sana**, dan
pencocokan otomatis wajar memberi skor tinggi 43,3.

**Pelajarannya: skor tinggi berarti TIDAK TAHU, bukan berarti BOHONG.** Di T-30 aku membaca
skor lemah sebagai tuduhan, padahal ia cuma ketidaktahuan. Sekarang tabelnya mencantumkan
tingkat keyakinan, bukan cuma tebakan.

## 4. Temuan di luar lingkup, tapi perlu dicatat

`latar-meja.jpg` **313 KB** dan dipasang di `style.css` baris 32 sebagai latar `body`:

```css
background:var(--wp--preset--color--meja) url("assets/img/latar-meja.jpg") center/cover fixed no-repeat;
```

Artinya berkas 313 KB dimuat di **setiap halaman**, dan itu **3,4 kali ambang aman 91 KB**.
Sekarang tidak menggigit karena CDN mati. Begitu CDN dinyalakan lagi, ini kandidat kuat rusak,
dan rusaknya akan terlihat sebagai latar belakang hilang di seluruh situs sekaligus.
Ukurannya 2200x1237 juga jauh lebih besar dari yang dibutuhkan latar `cover`.

Ini bukan lingkup T-32 jadi tidak kusentuh. Kalau mau dijadikan kartu, perbaikannya gratis:
turunkan ke sekitar 1400 px dan encode ulang, kemungkinan besar muat di bawah 91 KB.

## 5. Verifikasi

- `bin/periksa-php.sh`: 11 berkas PHP, 0 gagal.
- Sapuan isi terhadap seluruh berkas yang dirujuk kode tema: **nol nama berbohong**.
  Satu satunya sisa `artotel-08` di repo ada di **komentar sejarah**, bukan di kode.
- Belum dikirim. Izin kirim T-30 dulu khusus untuk perubahan itu, jadi tidak kuanggap berlaku
  untuk kartu ini.

## 6. Berkas yang kupegang

`inc/isi-beranda.php`, `inc/seo.php`, `patterns/hero-panggung.php`,
`assets/img/pasar-jakal-07.jpg`, `assets/img/pasar-jakal-07.webp`,
`assets/img/CATATAN.md` (tidak ikut terkirim).
