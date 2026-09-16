# LAPORAN T-272: tombol CTA "Book Your Seat" dan kedip muat pertama

Tema `tjr-v5`, live di thejournalingroom.id. Dikerjakan 16 Sep 2026.

Dua commit:

| sha | isi |
|---|---|
| `2d14b3a` | repo menyusul produksi, isi 7 Sep yang sudah tayang tapi belum di-commit |
| `f4e9208` | pekerjaan T-272, dua tombol CTA plus batas tunggu huruf di pembuka |

Tag jalan balik: `produksi-sebelum-cta-form-16sep` menunjuk `2d14b3a`, yaitu
keadaan produksi tepat sebelum kiriman ini.

Sudah terkirim ke server lewat `bin/kirim-tema-ftp.py`, 5 berkas, 0 hapus.
Terbukti dari live, buktinya di bagian akhir.

---

## Langkah 0: commit yang sudah tayang, terpisah

Pohon kerja kotor waktu kartu ini mulai. Sebelum menyentuh apa pun, isinya
kucocokkan dulu dengan live dan semuanya memang sudah tayang sejak 7 Sep:

- `assets/js/pembuka.js`: server berbunyi `bukaHalaman, 170` dan `tahan + 260`.
- `functions.php`: `<img>` tirai di live punya `width="650" height="336"`.
- `inc/isi-beranda.php`: beranda live cuma punya SATU `fetchpriority="high"`.
- `bin/tulis-acara.py`: gerbang `pastikan_post()`, di luar folder tema.

Plus **10 berkas `.webp` yang belum pernah di-`git add`** di
`assets/img/`. Ketiganya kucek ke live dan byte-nya sama persis dengan disk
(`amco-naoki-03-310.webp` 27966, `wardah-09-342.webp` 33592,
`artotel-14-pengantar-v2.webp` 90484). Sembilan varian persegi `-310`/`-342`
nol disebut literal di kode mana pun, jadi `grep` nol menemukannya: namanya
diturunkan `tjr_v5_sumber_persegi()` dari nama `.jpg`. Kalau sepuluh berkas itu
dibiarkan untracked, gerbang disk lawan git di `CARA-KIRIM.md` bagian 1 nol
akan pernah nol.

Semuanya masuk `2d14b3a`, terpisah dari pekerjaan baru.

---

## A. Tombol CTA

Label persis `Book Your Seat`, tujuan `https://forms.gle/pA5cLp3PVrU7LEfS7`,
`target="_blank" rel="noopener"` tetap seperti sebelumnya.

### Berkas dan baris

**`wordpress/theme-v5/functions.php`**

| baris | perubahan |
|---|---|
| 55-68 | konstanta baru `TJR_FORM_PESAN_KURSI` berisi alamat formulir |
| 305-315 | helper baru `tjr_v5_link_pesan_kursi()` |

Alamatnya sengaja jadi konstanta sendiri, bukan dititipkan ke helper WhatsApp.
Nomor WhatsApp dan alamat formulir dipakai di beranda yang sama, jadi kalau
satu fungsi melayani dua tujuan, mengganti salah satunya akan diam diam
mengganti yang lain juga. `tjr_v5_link_wa_slot()` dan seluruh konstanta
WhatsApp NOL disentuh.

**`wordpress/theme-v5/patterns/hero-panggung.php`** (tombol kartu sesi terdekat di hero)

| baris | sebelum | sesudah |
|---|---|---|
| 11 | `$tjr_wa_slot = esc_url( tjr_v5_link_wa_slot() );` | `$tjr_pesan = esc_url( tjr_v5_link_pesan_kursi() );` |
| 6 | Description menyebut kartu sesi saja | ditambah kalimat tombolnya membuka formulir |
| 141 | `className:"aksi"`, href `$tjr_wa_slot`, aria-label "Tanyakan slotnya ke kami lewat WhatsApp", teks "Tanyakan slotnya ke kami" | `className:"aksi pesan-kursi"`, href `$tjr_pesan`, aria-label "Book Your Seat, formulir pemesanan kursi, terbuka di tab baru", teks "Book Your Seat" |

**`wordpress/theme-v5/patterns/jadwal-sesi-terdekat.php`** (tombol seksi Sesi terdekat)

| baris | sebelum | sesudah |
|---|---|---|
| 11 | `$tjr_wa = esc_url( tjr_v5_link_wa_slot() );` | `$tjr_pesan = esc_url( tjr_v5_link_pesan_kursi() );` |
| 6 | Description berbunyi "dan tombol WhatsApp" | "dan tombol yang membuka formulir pemesanan kursi" |
| 113 | `className:"is-style-pil-isi"`, href `$tjr_wa`, nol aria-label, teks "Tanyakan slotnya ke kami" | `className:"is-style-pil-isi pesan-kursi"`, href `$tjr_pesan`, aria-label sama seperti di atas, teks "Book Your Seat" |

aria-label di tombol kedua memang DITAMBAH, bukan diperbarui, karena sebelumnya
nol ada. Sekarang dua tombol itu mengerjakan hal yang sama dan dua duanya buka
tab baru, jadi dua duanya mengumumkannya.

**`wordpress/theme-v5/style.css`** baris 646-671, satu blok baru sesudah blok ikon kartu sesi.

### Ikon yang dipilih: TIKET, bukan panah keluar

Ikon WhatsApp nol boleh tinggal di tombol yang menuju Google Form, itu
menjanjikan tujuan yang salah. Yang menggantikannya tiket.

Panah keluar sempat jadi calon karena tautannya memang meninggalkan situs,
tapi kutolak setelah melihat pemakaiannya: panah itu sudah jadi hiasan bawaan
**semua** tombol `is-style-pil`, `is-style-pil-isi`, dan `is-style-pil-rose` di
situs ini (`style.css:214-216`). Artinya di mata pengunjung dia berbunyi "ini
tombol pil", bukan "ini memesan kursi". Memakainya di sini membuat CTA utama
kelihatan sama persis dengan setiap tombol sekunder. Bahwa tautannya keluar
situs sudah dibawa `target="_blank"` dan aria-label, jadi nol perlu diulang
ikon.

Digambar dengan pola yang sama persis seperti ikon yang sudah ada: CSS `mask`
data URI, `background:currentColor`, nol SVG di markup, nol entri sprite baru,
lewat satu kelas modifier baru `pesan-kursi` yang dipasang di kedua tombol.
Selektornya sengaja menumpuk kelas yang sudah ada (`.aksi.pesan-kursi`,
`.is-style-pil-isi.pesan-kursi`) supaya spesifisitasnya menang atas rule ikon
lama tanpa `!important`.

Kotak ikonnya NOL diubah, tetap 15px di kartu sesi dan 12px di pil isi.
Diukur di halaman live sebelum kirim, dengan menyuntik rule baru dan menukar
markup di tempat (`CARA-KIRIM.md` bagian 6), dua tombol nol bergeser:

| tombol | sebelum | sesudah |
|---|---|---|
| kartu sesi | 238,3 x 42,7 px | 238,3 x 42,7 px |
| seksi Sesi terdekat | 174,4 x 43,0 px | 174,4 x 43,0 px |

Gerakan hover tombol kedua ikut diluruskan dari `translate(4px,-4px)` jadi
`translateX(3px)`. Diagonal itu gerakan panah yang terbang ke sudut; tiket nol
terbang ke mana mana, jadi geserannya mendatar, sama dengan tombol kartu sesi.

### Tempat CTA yang SENGAJA masih WhatsApp

Tiga, semuanya di luar permintaan Umar dan semuanya nol kusentuh:

| tempat | baris | keadaan |
|---|---|---|
| `functions.php`, tombol mengambang `tjr_v5_fab()` | 1397 | href `tjr_v5_link_wa_slot()`, aria-label masih menyebut WhatsApp, ikon WhatsApp SVG inline, teks "Tanyakan slot" |
| `patterns/ajakan-whatsapp.php` | 23 | href `tjr_v5_link_wa()`, teks "Tanyakan slotnya ke kami" |
| `templates/single-acara.html` | 103 | kelas `wa-slot`, href ditukar `tjr_v5_fakta_acara()` jadi link WA yang berlaku |

Catatan yang perlu diketahui sebelum menyentuh yang ketiga:
`inc/isi-beranda.php:1229` menulis ulang href SETIAP tombol yang kelasnya
memuat `wa-slot`. Dua tombol yang kuubah kelasnya `aksi pesan-kursi` dan
`is-style-pil-isi pesan-kursi`, nol memuat `wa-slot`, jadi filter itu nol
menyentuhnya. Kalau suatu saat ada yang memberi nama kelas ber-`wa-slot` ke
tombol formulir, hrefnya akan diam diam balik ke WhatsApp.

---

## B. Kedip di muat pertama

### Yang diukur, sebelum menambal

Beranda live, Chrome, cache dikosongkan (`Network.clearBrowserCache` plus
`setCacheDisabled`), throttle Fast 3G (latency 562,5 ms, turun 188743 B/s,
naik 86400 B/s). Instrumentasinya disuntik di `document-start` dan menulis
hasilnya ke atribut DOM, jadi ia melihat frame pertama.

| | jalan 1 | jalan 2 |
|---|---|---|
| cat pertama | 1940 ms | 1736 ms |
| DOMContentLoaded | 1920 ms | 2367 ms |
| `.bar` mulai kelihatan | 3763 ms | 3910 ms |
| `.bar` penuh | 4130 ms | (nol diambil) |
| `.intro` dilepas | 5142 ms | 5283 ms |
| `load` | 4326 ms | 4233 ms |
| **atas halaman diam sesudah cat pertama** | **1823 ms** | **2175 ms** |

Yang dilihat pengunjung selama itu bukan halaman blank. `.tirai` sudah tercat
sebagai sampul krem penuh layar, tapi logonya pun belum ada karena
`style.css:1434` menyetel `.sampul img{opacity:0}` sampai rantai animasi mulai.
Jadi layarnya krem polos, diam, hampir dua detik. Diam itulah yang terbaca
sebagai kedip.

### Hipotesis kartu: TERBANTAH

Kartu menduga rantainya digantung `window.addEventListener('load')` di
`pembuka.js:360`. Dua hal membantahnya:

1. **Angka.** Di jalan 1 `.bar` sudah kelihatan di 3763 ms sementara `load`
   baru di 4326 ms. Rantainya sudah jalan 563 ms sebelum `load`.
2. **Berkasnya.** Baris 360 ada di IIFE KETIGA (baris 334-365), yang urusannya
   pendaratan tautan nav berhash, bukan pembuka. Pembuka ada di IIFE pertama
   (baris 15-301) dan ia nol menunggu `load` sama sekali.

### Sebab sebenarnya

Skripnya di-enqueue `defer` + `in_footer` (`functions.php:228-237`), jadi ia
jalan di DOMContentLoaded. Di titik itu ia memanggil `document.fonts.ready`,
diadu balap dengan batas 1400 ms.

Di muat dingin, saat DOMContentLoaded stylesheet Google Fonts sudah terurai
sementara berkas woff2-nya masih di jalan, jadi promise itu menggantung dan
batas 1400 ms terpakai hampir penuh. Buktinya dari selisih: rantai dari
eksekusi skrip sampai `.bar` kelihatan berdurasi 1843 ms di jalan 1, dan ekor
tetap rantai itu 430 ms (`+260` lalu `+170`), jadi tunggu hurufnya 1413 ms.

Batas 1400 ms itu ditulis supaya halaman nol disandera huruf yang lambat, tapi
ia dihitung **dari saat skrip jalan**, padahal skripnya sendiri baru jalan 1,9
sampai 2,4 detik sesudah navigasi di muat dingin. Anggarannya terbayar dua
kali: sekali oleh jaringan sebelum skrip hidup, sekali lagi oleh timer.

Satu jebakan yang ikut ketemu dan layak dicatat: instrumentasiku memanggil
`document.fonts.ready` di `document-start` dan ia beres di **587 ms**, jauh
sebelum hurufnya ada. `document.fonts.ready` beres begitu antrean muat huruf
SAAT ITU kosong, dan di `document-start` CSS font-nya memang belum terurai
sehingga antreannya memang kosong. Jadi angka `fonts.ready` cuma bermakna kalau
dibaca dari titik yang sama dengan pemanggil aslinya.

### Yang diubah

`wordpress/theme-v5/assets/js/pembuka.js` baris 213-256. Satu hal saja:
batasnya dihitung dari NAVIGASI, bukan dari saat skrip jalan.

```js
var ANGGARAN_HURUF = 900;
var LANTAI_HURUF = 150;
var lalu = window.performance && performance.now ? performance.now() : 0;
var batasMs = Math.max(LANTAI_HURUF, ANGGARAN_HURUF - lalu);
```

Anggarannya jadi satu: pembuka boleh menunggu huruf sampai 900 ms sejak
navigasi, dan nol pernah kurang dari 150 ms supaya muat hangat tetap punya
jeda. Animasinya, urutannya, durasi tiap gerakannya, dan `tahan` yang menjamin
sampul tertahan minimal 420 ms semuanya NOL disentuh. Jaring pengaman 4200 ms
juga nol disentuh.

### Yang diukur sesudah, cara yang sama persis

| | jalan 1 | jalan 2 | jalan 3 |
|---|---|---|---|
| cat pertama | 2004 ms | 1752 ms | 1764 ms |
| DOMContentLoaded | 1976 ms | 2383 ms | 2415 ms |
| logo sampul mulai kelihatan | 2146 ms | 2560 ms | 2589 ms |
| `.bar` mulai kelihatan | 2845 ms | 3260 ms | 3289 ms |
| `.bar` penuh | 3212 ms | 3627 ms | 3656 ms |
| `.intro` dilepas | 4214 ms | 4634 ms | 4669 ms |
| **atas halaman diam sesudah cat pertama** | **841 ms** | **1508 ms** | **1525 ms** |

### Dua angka berdampingan

Angka "diam sesudah cat pertama" ikut memuat jarak cat pertama ke
DOMContentLoaded, yang urusan jaringan dan parsing, bukan urusan rantai ini.
Jadi kutaruh dua duanya, dan yang kedua yang benar benar mengisolasi
perubahanku.

| ukuran | sebelum | sesudah |
|---|---|---|
| atas halaman diam sesudah cat pertama | 1823 dan 2175 ms | 841, 1508, 1525 ms |
| **rantai pembuka, DOMContentLoaded sampai `.bar` kelihatan** | **1843 dan 1543 ms** | **869, 877, 874 ms** |
| layar krem polos tanpa logo, sesudah skrip jalan | ~1413 ms (diturunkan) | 170, 177, 174 ms (diukur) |

Rantainya turun sekitar **950 ms**, dan sebarannya menyempit dari rentang 300 ms
jadi 8 ms, karena sekarang ia nol lagi bergantung pada kapan berkas huruf tiba.

Angka "sebelum" untuk baris ketiga kutandai diturunkan, bukan diukur: di jalan
sebelum aku belum memantau opacity logo, jadi 1413 ms itu hasil hitung mundur
dari total rantai 1843 ms dikurangi ekor tetap 430 ms. Dua baris lainnya
diukur langsung.

### Yang nol boleh rusak, dicek

- **`prefers-reduced-motion: reduce`**: masih jalan. Diukur dengan
  `emulateMedia({reducedMotion:'reduce'})`: `.bar` opacity > 0,02 di 1740 ms,
  yaitu SEBELUM cat pertama di 1760 ms, jadi bagian atas terlihat sejak frame
  pertama. Logo sampul nol pernah beranimasi, dan `.intro` dilepas di 2417 ms,
  sama persis dengan DOMContentLoaded, artinya `selesai()` jalan langsung dan
  seluruh rantai dilewati. Persis seperti sebelumnya.
- **Muat kedua, cache hidup**: rantainya 871 ms, sama dengan muat dingin. Nol
  ada yang memburuk.
- **Animasi pembukanya nol dihapus.** Sampul buku masih berputar, logo masih
  naik, kata judul masih terbit bergantian, cetakan polaroid masih jatuh.

---

## Bukti dari live

Diambil sesudah kiriman, dengan `curl`, bukan dari pembacaan lokal.

**Halaman.** `https://thejournalingroom.id/` balas `200`, 98992 byte, badan
penuh sampai `</html>`. Nol permintaan yang terpotong di percobaan pertama.

**Dua tombol, apa adanya dari HTML live:**

```
<a class="wp-block-button__link wp-element-button" href="https://forms.gle/pA5cLp3PVrU7LEfS7" target="_blank" rel="noopener" aria-label="Book Your Seat, formulir pemesanan kursi, terbuka di tab baru">Book Your Seat</a>
<a class="wp-block-button__link wp-element-button" href="https://forms.gle/pA5cLp3PVrU7LEfS7" target="_blank" rel="noopener" aria-label="Book Your Seat, formulir pemesanan kursi, terbuka di tab baru">Book Your Seat</a>
```

Hitungan di HTML beranda live: `Book Your Seat` 2, `forms.gle/pA5cLp3PVrU7LEfS7`
2, kelas `pesan-kursi` 2, dan `aria-label="Tanyakan slotnya ke kami lewat
WhatsApp"` tersisa 1, yaitu tombol mengambang yang memang sengaja dibiarkan.

**Berkas.** md5 lokal lawan live, dua duanya sama:

| berkas | ukuran | md5 |
|---|---|---|
| `style.css` | 78268 byte | `e6ed52efb8ad65a005edfb684ce310ca` |
| `assets/js/pembuka.js` | 12971 byte | `83203ae868915f8c5de510c0b5b2f9c2` |

`setTimeout(r, 1400)` nol lagi ada di berkas yang tayang; yang ada
`ANGGARAN_HURUF = 900`, `LANTAI_HURUF = 150`, dan `setTimeout(r, batasMs)`.

---

## Gerbang yang dilewati sebelum kirim

1. Disk lawan git di `wordpress/theme-v5`: NOL di dua arah.
2. `bash bin/periksa-php.sh`: 11 berkas PHP, 0 gagal.
3. `node --check assets/js/pembuka.js`: lulus.
4. `git status --short --untracked-files=no`: kosong.
5. `python3 bin/kirim-tema-ftp.py --coba --teliti`: lokal 110, server 110,
   kirim 5, hapus 0, dan nol baris "disentuh di server tapi isinya SAMA".
   Artinya nol ada suntingan tangan di server yang akan tertimpa.
6. Tag `produksi-sebelum-cta-form-16sep` dibuat di `2d14b3a`.

## Yang belum dikerjakan, sengaja

`bash bin/dorong-tema.sh` **nol dijalankan**. `CARA-KIRIM.md` bagian 2 memintanya
di tiap kiriman supaya repo tema nol menyimpang dari repo utama, tapi ia
`git push` ke remote, dan temp dilarang mendorong ke remote mana pun. Dua commit
di atas menunggu god untuk mendorongnya.

## Sisa yang nol disentuh karena di luar batas kartu

Cat pertama sendiri jatuh di 1,7 sampai 2,0 detik di Fast 3G, dan itu dipegang
`style.css` yang render-blocking (`responseEnd` 1698 sampai 1902 ms). Kartu ini
cuma mengizinkan mengubah kapan rantai animasi mulai dan berapa lama batas
tunggunya, jadi itu kubiarkan. Kalau Umar mau menurunkannya lagi, di situ
tempatnya.
