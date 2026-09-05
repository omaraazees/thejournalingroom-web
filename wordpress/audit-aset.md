# Audit Aset Gambar The Journaling Room

Cakupan: `wordpress/foto-hd/`, `wordpress/foto-webp/`, `wordpress/foto-2026/`, `wordpress/foto-web/`, `wordpress/logo-web/`, dengan acuan pemakaian dari `desain/prototipe/v5-fieldtime.html`.

## 1. Ukuran per direktori

| Direktori | Berkas | Ukuran | Isi |
|-|-:|-:|-|
| `wordpress/foto-hd` | 56 | 23.96 MB | JPEG 1300px dan 2600px, aset produksi |
| `wordpress/foto-webp` | 56 | 11.50 MB | WebP kualitas 78, kembaran foto-hd |
| `wordpress/foto-2026` | 167 | 56.60 MB | JPEG maksimum 1600px, kumpulan kerja per acara |
| `wordpress/foto-web` | 40 | 16.92 MB | JPEG batch lama, nama file mentah |
| `wordpress/logo-web` | 12 | 0.53 MB | PNG transparan 12 logo |
| `wordpress/dist` | 3 | 1.07 MB | paket zip tema |
| `wordpress/theme` | 26 | 0.09 MB | tema anak |
| `wordpress/theme-v5` | 57 | 5.44 MB | tema v5 |
| `wordpress/cms` | 10 | 0.08 MB | konfigurasi CMS |
| **Total `wordpress/`** | **427** | **116.20 MB** | |

Folder sumber asli `Documents/FOTO TJR BARU/DOKUMENTASI ` berisi 260 berkas, 2019 MB, termasuk video MOV dan MP4 yang tidak dipakai di web.

Catatan penting soal dua direktori foto lama:

- `wordpress/foto-web/` (16.92 MB, 40 berkas) memakai nama mentah seperti `tjr x artotel-img_6830.jpg` dan tidak dirujuk sama sekali oleh v5. Ini sisa batch pertama dan bisa dihapus setelah dipastikan tidak ada halaman lain yang memanggilnya.
- `wordpress/foto-2026/` (56.60 MB, 167 berkas) berperan sebagai kumpulan kerja. Hanya satu berkasnya yang benar benar dimuat browser, yaitu `latar-meja.jpg` lewat CSS `background` pada `body`. Sisanya arsip pilihan.

## 2. Foto yang belum dipakai di desain v5

Dari 28 foto di `foto-hd/`, 23 dipakai di v5 dan 5 menganggur.

| Foto | Ukuran 1x | Ukuran 2x | Total byte | Sumber asli |
|-|-|-|-:|-|
| `artotel-01` | 975x1300 | 1950x2600 | 1043 KB | `IMG_6830.HEIC` |
| `kolondjono-09` | 975x1300 | 1950x2600 | 795 KB | `IMG_1286.heic` |
| `radian-33` | 975x1300 | 1950x2600 | 1299 KB | `IMG_1673.HEIC` |
| `statement-beauty-01` | 731x1300 | 1462x2600 | 672 KB | `D55B7D6E-C32C-4779-AD7F-7181CB193297.JPG` |
| `sundayreads-20` | 975x1300 | 1950x2600 | 703 KB | `IMG_4017.HEIC` |

Total 4.41 MB JPEG plus kembaran WebP-nya. Dua pilihan: pakai di seksi galeri atau arsip, atau keluarkan dari `foto-hd/` supaya direktori produksi cuma berisi yang benar benar dipasang.

Di luar itu, `foto-2026/` masih menyimpan 139 foto yang belum pernah diangkat ke `foto-hd/`. Ini stok galeri yang siap dipakai kalau seksi arsip mau diperluas. Rinciannya per acara:

| Acara | Foto belum dipakai |
|-|-:|
| amco-naoki | 6 |
| artotel | 16 |
| kolondjono | 24 |
| kupiku | 3 |
| pasar-jakal | 12 |
| radian | 29 |
| snapobox | 11 |
| statement-beauty | 1 |
| sundayreads | 27 |
| wardah | 10 |

## 3. Resolusi di bawah 2000px sisi terpanjang

Patokan: layar retina menggambar dua piksel fisik untuk satu piksel CSS. Aset dengan sisi terpanjang di bawah 2000px hanya cukup untuk slot selebar 1000px CSS ke bawah.

### 3a. Berkas 2x yang tidak sampai 2000px

| Foto | Ukuran 2x | Sisi terpanjang | Sebab |
|-|-|-:|-|
| `latar-meja` | 1900x1069 | 1900 | tidak ada berkas asli, sumber tertinggi cuma 1900px |

`latar-meja.jpg` adalah satu satunya kasus. Berkas ini dipakai sebagai latar `body` dengan `center/cover fixed`, jadi selalu diregangkan sepenuh viewport. Di layar 2560px lebar, sumber 1900px berarti perbesaran 1,35 kali. Untungnya gambarnya memang sengaja diburamkan, jadi kerusakannya tidak kelihatan. Berkas 2x-nya sengaja tidak saya upscale ke 2600px supaya tidak ada aset yang mengaku punya detail yang tidak dimilikinya.

### 3b. Berkas asli yang di bawah 2000px

Tidak ada. Semua 166 berkas asli di folder dokumentasi punya sisi terpanjang minimal 2080px, mayoritas 4032px, dan sebagian Sundayreadsclub sampai 8064px.

### 3c. Slot desain yang kekurangan piksel pada layar retina

Ini analisis yang lebih berguna daripada sekadar ambang 2000px: membandingkan lebar tampil dari atribut `sizes` dengan kandidat terbesar di `srcset`, pada viewport 1620px.

| Foto | Lebar tampil CSS | Butuh untuk 2x | Tersedia | Kurang |
|-|-:|-:|-:|-:|
| `snapobox-11.jpg` | 1528px | 3056px | 2600px | 456px |

Hero adalah satu satunya slot yang kurang. `snapobox-11.jpg` tampil selebar 1528px CSS, jadi butuh 3056px untuk tajam pada retina, sementara kandidat terbesar cuma 2600px. Berkas aslinya `IMG_4054.JPG` berukuran 4032x2268, jadi ekspor 3200px masih bisa dan tidak perlu upscale. Ini foto pertama yang dilihat pengunjung dan satu satunya yang tidak lazy, jadi paling layak dibetulkan.

### 3d. Kebalikannya: slot yang kelebihan piksel

Sembilan kartu di seksi cetakan tampil selebar 164px CSS dan dua gambar kecil di blok penutup selebar 124px. Pada retina keduanya cuma butuh 328px dan 248px. Tapi kandidat terkecil di `srcset` adalah 1300w, jadi browser terpaksa mengunduh berkas 1300px untuk kotak selebar 164px.

| Foto | Lebar tampil CSS | Butuh 2x | Yang diunduh | Berat |
|-|-:|-:|-:|-:|
| `sundayreads-12.jpg` | 164px | 328px | 1300px | 159 KB |
| `radian-30.jpg` | 164px | 328px | 1300px | 193 KB |
| `wardah-04.jpg` | 164px | 328px | 1300px | 165 KB |
| `artotel-19.jpg` | 164px | 328px | 1300px | 215 KB |
| `kolondjono-20.jpg` | 164px | 328px | 1300px | 249 KB |
| `amco-naoki-03.jpg` | 164px | 328px | 1300px | 243 KB |
| `snapobox-08.jpg` | 164px | 328px | 1300px | 224 KB |
| `pasar-jakal-02.jpg` | 164px | 328px | 1300px | 301 KB |
| `kupiku-04.jpg` | 164px | 328px | 1300px | 158 KB |
| `sundayreads-27.jpg` | 124px | 248px | 1300px | 253 KB |
| `radian-24.jpg` | 124px | 248px | 1300px | 162 KB |

Total 2322 KB untuk sebelas gambar yang secara visual tidak butuh lebih dari 400px. Saya coba ekspor kesebelasnya pada 400w dengan setelan yang sama: hasilnya 339 KB dalam JPEG dan 210 KB dalam WebP. Artinya ada sekitar 1983 KB yang bisa dipangkas cuma dengan menambah kandidat `400w` dan `800w` di `srcset` seksi cetakan dan blok penutup. Ini penghematan terbesar yang bisa didapat tanpa mengubah desain sama sekali.

## 4. Rekomendasi foto ulang dan ekspor ulang

| Prioritas | Aset | Masalah | Tindakan |
|-|-|-|-|
| 1 | `latar-meja.jpg` | Sumber tertinggi cuma 1900x1069 dan tidak ada berkas aslinya di folder dokumentasi. Sudah dipakai sebagai latar seluruh halaman. | Foto ulang meja kerja workshop dalam format lanskap, minimal 4000px sisi panjang, lalu terapkan lagi blur dan penurunan saturasinya. Ini satu satunya aset yang benar benar perlu jepretan baru. |
| 2 | `snapobox-11.jpg` | Slot hero butuh 3056px, tersedia 2600px. | Ekspor ulang varian 3200px dari `IMG_4054.JPG` (4032x2268) dan tambahkan sebagai kandidat `3200w`. Tidak perlu foto ulang. |
| 3 | Sepuluh logo kolaborator | Terpotong di tepi kanvas, lihat `audit-logo.md`. | Minta berkas vektor ke masing masing kolaborator. |
| 4 | Seksi Statement Beauty | Cuma ada 2 foto di folder sumber, dan satu satunya yang masuk `foto-hd` belum dipakai di v5. Acara ini praktis tidak terdokumentasi. | Kalau Statement Beauty mau ditampilkan setara kolaborator lain, perlu sesi foto ulang atau minta dokumentasi dari pihak mereka. |
| 5 | Seksi AMCO x Naoki dan Kupiku | Masing masing cuma 8 dan 5 foto sumber, paling sedikit setelah Statement Beauty. | Cukup untuk sekarang, tapi tidak ada ruang untuk memilih ulang kalau desain berubah. |

Yang tidak perlu disentuh: 21 dari 23 foto yang dipakai v5 sudah diekspor dari berkas asli beresolusi 3024px sampai 8064px. Saya verifikasi ulang dengan membandingkan berkas `@2x` yang ada terhadap dua kandidat, yaitu ekspor segar dari berkas asli dan hasil perbesaran dari `foto-2026` yang cuma 1600px. Hasilnya konsisten: PSNR terhadap ekspor dari asli 37 sampai 44 dB, terhadap hasil perbesaran cuma 29 sampai 40 dB. Artinya isi `foto-hd/` memang berasal dari berkas asli, bukan dari daur ulang `foto-2026`.

## 5. Perkiraan berat halaman v5

Halaman memuat 51 tag `img` ditambah satu latar CSS. 3 gambar dimuat langsung, sisanya `loading="lazy"`. Belum ada `<picture>` atau `<source type="image/webp">`, jadi WebP yang sudah dibuat belum terpakai sama sekali.

| Skenario | Gambar | Berat gambar | Plus HTML | Catatan |
|-|-:|-:|-:|-|
| Muat awal, layar biasa | 3 | 1023 KB | 1087 KB | hero, logo header, latar meja |
| Muat awal, retina | 3 | 1023 KB | 1087 KB | sama karena hero sudah otomatis pakai kandidat 2600w |
| Semua foto dimuat, layar biasa | 35 | 5.86 MB | 5.92 MB | pengunjung menggulir sampai bawah |
| Semua foto dimuat, retina | 36 | 8.02 MB | 8.08 MB | **kondisi terberat yang realistis** |
| Semua foto, retina, kalau WebP dipasang | 36 | 4.41 MB | 4.48 MB | hemat 45 persen |
| Semua foto, layar biasa, kalau WebP dipasang | 35 | 3.37 MB | 3.44 MB | hemat 42 persen |

Sepuluh berkas terberat pada skenario retina:

| Berkas | Ukuran |
|-|-:|
| `artotel-16@2x.jpg` | 910 KB |
| `snapobox-11@2x.jpg` | 825 KB |
| `pasar-jakal-06@2x.jpg` | 697 KB |
| `radian-11@2x.jpg` | 626 KB |
| `artotel-13@2x.jpg` | 608 KB |
| `pasar-jakal-02.jpg` | 301 KB |
| `sundayreads-08.jpg` | 294 KB |
| `artotel-16.jpg` | 275 KB |
| `sundayreads-27.jpg` | 253 KB |
| `kolondjono-20.jpg` | 249 KB |

Kesimpulan berat halaman: pengunjung yang menggulir sampai habis di MacBook retina menarik sekitar 8.08 MB. Angka itu turun ke 4.48 MB kalau WebP dipasang, dan turun lagi ke kisaran 3.45 MB kalau `srcset` seksi cetakan dan blok penutup diberi kandidat kecil. Muat awal sendiri sudah ringan, 1087 KB pada retina, karena 48 dari 51 tag gambar sudah `lazy`.

## 6. Yang sudah beres

- Seluruh 28 foto di `foto-hd/` punya pasangan 1x dan 2x lengkap, dan semuanya sudah punya kembaran WebP di `foto-webp/`.
- Tidak ada satu pun berkas di `foto-hd/` yang menyisakan tag EXIF orientation selain 1. Rotasi sudah dibakar ke piksel lewat `ImageOps.exif_transpose`, jadi tidak ada lagi foto yang tampil miring di browser yang mengabaikan EXIF.
- Semua JPEG di `foto-hd/` sudah progressive dengan subsampling 1 dan tanpa profil ICC nyangkut.
- Semua gambar yang dirujuk v5 ada berkasnya di disk. Tidak ada tautan mati.
