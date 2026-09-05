# Alt text v5 · The Journaling Room

Alt text untuk 41 elemen `<img>` di `desain/prototipe/v5-fieldtime.html`.
Nama file diambil apa adanya dari HTML. Kolom **Alt usulan** yang dipakai waktu mengunggah
foto ke Media Library WordPress, karena alt di Media Library ikut terbawa ke mana pun foto itu
dipasang.

## Cara nulis alt yang benar untuk situs ini

1. **Jangan mulai dengan "Foto" atau "Gambar".** Pembaca layar sudah mengumumkan sendiri bahwa
   itu gambar. Menulisnya lagi cuma bikin tiap gambar terdengar sama.
2. **Ceritakan yang terlihat, bukan yang kamu tahu.** "Sesi Wardah" itu pengetahuan, bukan
   isi gambar. Yang terlihat adalah kartu pemantik, gunting, dan lembar stiker di meja putih.
3. **Kalau sudah ada keterangan di bawah gambar, alt jangan mengulanginya.** Di seksi Cetakan
   dan Galeri, nama kolaborator sudah ditulis sebagai `figcaption`. Alt yang menyebut nama
   yang sama bikin pembaca layar mengucapkannya dua kali berturut turut.
4. **Gambar hiasan diberi `alt=""`, bukan dihapus alt-nya.** Alt kosong menyuruh pembaca layar
   melewatinya. Tanpa atribut alt sama sekali, sebagian pembaca layar malah membacakan nama
   file, dan `sundayreads-27.jpg` dibaca huruf per huruf itu menyiksa.
5. **Nol keyword yang dipaksakan.** Alt bukan tempat menaruh "workshop journaling jogja"
   berulang ulang. Google sudah lama mengenali itu, dan pembaca layar jadi korbannya.
6. **Panjang wajar 80 sampai 125 karakter.** Cukup untuk satu kalimat yang berarti.

---

## 1. Tirai pembuka dan header

| # | File | Letak | Alt sekarang | Alt usulan | Catatan |
|---|---|---|---|---|---|
| 1 | `logo-web/tjr-black.png` | Tirai pembuka | `""` | `""` | **Sudah benar.** Tirai ber-`aria-hidden`, logonya murni animasi pembuka |
| 2 | `logo-web/tjr-black.png` | Logo header | `The Journaling Room` | `The Journaling Room` | Elemen `<a>` pembungkusnya punya `aria-label="The Journaling Room, ke atas"` yang **menimpa** alt ini. Hapus `aria-label`-nya, arahkan link ke `/`, dan biarkan alt yang bicara |

## 2. Hero

| # | File | Letak | Alt sekarang | Alt usulan | Catatan |
|---|---|---|---|---|---|
| 3 | `foto-hd/sundayreads-27.jpg` | Polaroid terselip kiri bawah | `""` | `""` | **Sudah benar.** Tapi `figcaption`-nya berisi `Nov 2025`, dan karena `figure`-nya `aria-hidden`, tanggal itu hilang untuk pembaca layar. Kalau tanggalnya dianggap penting, keluarkan dari `figure` yang disembunyikan |
| 4 | `foto-hd/snapobox-11.jpg` | Foto panggung utama | `Peserta workshop The Journaling Room memegang jurnal masing masing di bawah lampion` | `Belasan peserta berdiri di belakang meja panjang sambil mengangkat jurnal masing masing, di bawah tiga lampion kertas putih` | Alt lama sudah lumayan. Yang baru menambahkan meja panjang, yang justru jadi ciri khas TJR di semua sesi |

## 3. Foto dan kutipan

| # | File | Letak | Alt sekarang | Alt usulan | Catatan |
|---|---|---|---|---|---|
| 5 | `foto-hd/artotel-16.jpg` | Foto besar kiri | `Jurnal peserta digelar berjajar di lantai setelah sesi` | `Puluhan jurnal peserta digelar terbuka di lantai teraso, dikelilingi kaki peserta yang berdiri melihat` | Yang bikin foto ini kuat justru orang orang yang berdiri mengelilinginya, dan itu belum disebut di alt lama |
| 6 | `foto-hd/radian-24.jpg` | Polaroid terselip kanan atas | `""` | `""` | **Sudah benar**, dekoratif |
| 7 | `logo-web/tjr-black.png` | Stiker bulat merah muda | `""` | `""` | **Sudah benar**, murni hiasan |

## 4. Jadwal

| # | File | Letak | Alt sekarang | Alt usulan | Catatan |
|---|---|---|---|---|---|
| 8 | `foto-hd/kupiku-01.jpg` | Foto sesi terdekat | `Peserta menulis jurnal di sesi Kupiku Coffee` | `Peserta tertawa di meja sesi journaling sementara peserta lain membuka jurnalnya di depan kamera` | Alt lama menyebut "menulis", padahal di fotonya tidak ada yang sedang menulis. **DINAMIS**: nanti diambil dari alt featured image acara |
| 9 | `foto-hd/artotel-01.jpg` | Kartu Much Between the Lines | `Peserta menulis di depan mural Artotel` | `Dua peserta menulis di meja, di depan mural perempuan berambut merah dikelilingi dedaunan` | Muralnya spesifik dan mudah diingat, jadi layak disebut. **DINAMIS** |
| 10 | `foto-hd/kolondjono-09.jpg` | Kartu Journaling Playdate | `Dua peserta berbagi meja dan alat tulis` | `Dua peserta menulis berdampingan di kedai kopi, dengan spidol warna warni dan gulungan washi tape di meja` | **DINAMIS** |
| 11 | `foto-hd/pasar-jakal-06.jpg` | Kartu A Moment Between Chapters | `Tangan peserta menempel bahan di halaman jurnal` | `Tangan peserta memegang jurnal yang masih kosong di meja kayu berhias daun eukaliptus dan lembar stiker` | Alt lama keliru, di fotonya halamannya masih kosong dan belum ada yang ditempel. **DINAMIS** |

## 5. Galeri

| # | File | Letak | Alt sekarang | Alt usulan | Catatan |
|---|---|---|---|---|---|
| 12 | `foto-hd/radian-11.jpg` | Kotak besar kiri | `Sesi journaling di pendopo bersama Radian` | `Dua peserta duduk menulis di pendopo, dengan langit langit batik dan kotak alat tulis di meja` | Nama `Radian` sudah ada di `figcaption` di bawahnya. Alt tidak perlu mengulang |
| 13 | `foto-hd/sundayreads-08.jpg` | Kotak kecil atas | `Bahan journaling ditata dari atas meja` | `Meja penuh spidol, lembar stiker, dan gulungan washi tape, dengan jurnal bersampul kuning dan segelas kopi dingin` | Nama `Sunday Reads Club` sudah ada di `figcaption` |
| 14 | `foto-hd/wardah-09.jpg` | Kotak kecil bawah | `Kit alat tulis Wardah di atas meja` | `Kartu pemantik tulisan tangan, gunting, dan lembar stiker berserak di meja putih` | Nama `Wardah` sudah ada di `figcaption`. Yang menarik di foto ini kartu pemantiknya, bukan kitnya |
| 15 | `foto-hd/pasar-jakal-06.jpg` | Kotak besar kanan | `Tangan peserta menempel bahan di halaman jurnal` | `Jurnal kosong dibuka di atas meja kayu, dikelilingi daun eukaliptus dan bunga putih` | File yang sama dengan nomor 11. Karena muncul dua kali di satu halaman, alt-nya sengaja dibedakan supaya pembaca layar tidak mendengar kalimat identik dua kali |

## 6. Cetakan

Sembilan foto ini ada di dalam `figure` yang punya `figcaption` berisi nama kolaborator.
Alt sekarang seragam, `Dokumentasi sesi TJR bareng {nama}`, dan itu **mengulang persis isi
`figcaption`**. Hasilnya pembaca layar mengucapkan nama kolaborator dua kali beruntun,
sembilan kali berturut turut. Usulannya: alt menggambarkan isi foto, `figcaption` tetap
memegang nama.

| # | File | Keterangan di bawah | Alt sekarang | Alt usulan |
|---|---|---|---|---|
| 16 | `foto-hd/sundayreads-12.jpg` | Sunday Reads | `Dokumentasi sesi TJR bareng Sunday Reads` | `Peserta berkerumun di sekeliling meja di ruangan bermural kuning` |
| 17 | `foto-hd/radian-30.jpg` | Radian | `Dokumentasi sesi TJR bareng Radian` | `Tangan membuka buklet stiker di atas meja marmer, di samping keranjang berisi cetakan foto` |
| 18 | `foto-hd/wardah-04.jpg` | Wardah | `Dokumentasi sesi TJR bareng Wardah` | `Dua peserta menulis di kafe terang, dengan kartu nama acara berdiri di meja` |
| 19 | `foto-hd/artotel-19.jpg` | Artotel | `Dokumentasi sesi TJR bareng Artotel` | `Lingkaran tangan peserta menyatukan halaman jurnal yang sudah selesai dihias` |
| 20 | `foto-hd/kolondjono-20.jpg` | Kolondjono | `Dokumentasi sesi TJR bareng Kolondjono` | `Belasan jurnal terbuka diangkat bareng di atas meja kayu bundar` |
| 21 | `foto-hd/amco-naoki-03.jpg` | AMCO x Naoki | `Dokumentasi sesi TJR bareng AMCO x Naoki` | `Buklet Brush Lettering dan satu brush pen di atas lantai kerikil, di samping lembar stiker huruf` |
| 22 | `foto-hd/snapobox-08.jpg` | Snapobox | `Dokumentasi sesi TJR bareng Snapobox` | `Strip foto instan berjajar di meja kayu bertepi alami, di samping jurnal terbuka dan gelas kopi dingin` |
| 23 | `foto-hd/pasar-jakal-02.jpg` | Pasar Jakal | `Dokumentasi sesi TJR bareng Pasar Jakal` | `Peserta berfoto bersama di joglo kayu sambil memegang jurnal, dengan meja panjang berhias daun eukaliptus` |
| 24 | `foto-hd/kupiku-04.jpg` | Kupiku | `Dokumentasi sesi TJR bareng Kupiku` | `Peserta berfoto bersama di kafe terang sambil mengangkat jurnal masing masing` |

## 7. Kolaborator

| # | File | Alt sekarang | Alt usulan | Catatan |
|---|---|---|---|---|
| 25 | `foto-hd/artotel-16.jpg` | `""` | `""` | Polaroid terselip di judul seksi, dekoratif. **Sudah benar** |
| 26 | `logo-web/sundayreads.png` | `Sunday Reads Club` | `Sunday Reads Club` | **Sudah benar** |
| 27 | `logo-web/radian.png` | `Radian` | `Radian` | **Sudah benar** |
| 28 | `logo-web/kupiku.png` | `Kupiku Coffee` | `Kupiku Coffee` | **Sudah benar** |
| 29 | `logo-web/wardah.png` | `Wardah` | `Wardah` | **Sudah benar** |
| 30 | `logo-web/artotel.png` | `Artotel` | `Artotel` | **Sudah benar** |
| 31 | `logo-web/hanasui.png` | `Hanasui` | `Hanasui` | **Sudah benar** |
| 32 | `logo-web/heejaz.png` | `Heejaz` | `Heejaz` | **Sudah benar** |
| 33 | `logo-web/statement-beauty.png` | `Statement Beauty` | `Statement Beauty` | **Sudah benar** |
| 34 | `logo-web/amco.png` | `AMCO Bakehouse` | `AMCO Bakehouse` | **Sudah benar** |
| 35 | `logo-web/pasar-jakal.png` | `Pasar Jakal` | `Pasar Jakal` | **Sudah benar** |
| 36 | `logo-web/snapobox.png` | `Snapobox` | `Snapobox` | **Sudah benar** |
| 37 | `logo-web/tjr-black.png` | `The Journaling Room` | `The Journaling Room` | Ini logo TJR sendiri yang ikut nimbrung di deretan kolaborator. Alt-nya benar, tapi tanya dulu apakah memang mau ditampilkan di sana. Judul seksinya `Sebelas nama di meja`, dan kalau logo TJR ikut dihitung orang, angkanya jadi terasa salah |

Untuk logo, nama brand saja sudah cukup. Menambahkan kata `logo` di depan bikin dua belas
baris terdengar seperti `logo, logo, logo` dan tidak menambah informasi apa apa.

## 8. Ajakan penutup

| # | File | Letak | Alt sekarang | Alt usulan | Catatan |
|---|---|---|---|---|---|
| 38 | `foto-hd/artotel-13.jpg` | Foto besar kanan | `Foto bersama peserta workshop TJR di Artotel` | `Peserta berfoto bersama di ruang berjendela kerai sambil mengangkat halaman jurnal yang baru selesai` | Alt lama mulai dengan kata `Foto`, persis yang harus dihindari |
| 39 | `foto-hd/sundayreads-27.jpg` | Polaroid kiri | `""` | `""` | **Sudah benar**, dekoratif |
| 40 | `foto-hd/radian-24.jpg` | Polaroid kanan | `""` | `""` | **Sudah benar**, dekoratif |

## 9. Footer

| # | File | Alt sekarang | Alt usulan | Catatan |
|---|---|---|---|---|
| 41 | `logo-web/tjr-black.png` | `The Journaling Room` | `The Journaling Room` | Sama seperti nomor 2, `aria-label` di `<a>` pembungkusnya menimpa alt ini. Hapus `aria-label`-nya |

## Di luar tag img

| Berkas | Letak | Catatan |
|---|---|---|
| `foto-2026/latar-meja.jpg` | Latar `body` lewat CSS | Latar dekoratif, tidak butuh alt dan memang tidak bisa diberi alt. Yang perlu dijaga cuma kontras teks di atasnya |

---

## Ringkasan

| Jenis | Jumlah |
|---|---|
| Total `<img>` | 41 |
| Perlu alt deskriptif | 21 |
| Sengaja `alt=""`, hiasan | 7 |
| Logo, alt berupa nama brand | 12 |
| Logo TJR di header dan footer | 2, sudah ikut hitungan di atas |

**Yang paling banyak berubah:** sembilan foto di seksi Cetakan, karena alt lamanya cuma
mengulang keterangan di bawah gambar.

**Satu hal yang bukan soal alt tapi ketemu waktu memeriksa.** Lima `figure` diberi
`aria-hidden="true"`, dan `figcaption` di dalamnya ikut hilang untuk pembaca layar:
`Nov 2025`, `Pendopo Radian`, `Artotel, Apr 2026`, `Sunday Reads`, dan `Radian`. Untuk polaroid
hiasan itu keputusan yang benar. Yang perlu dipastikan cuma satu: informasi di keterangan itu
jangan sampai cuma ada di situ. Semuanya sudah ada di seksi Arsip, jadi aman.
