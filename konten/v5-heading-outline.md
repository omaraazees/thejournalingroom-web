# Struktur heading v5 · The Journaling Room

Susunan `h1` sampai `h3` yang seharusnya dipakai tiap halaman, plus catatan urutan heading yang
lompat di `desain/prototipe/v5-fieldtime.html`.

Kenapa ini penting sampai perlu satu dokumen sendiri: heading itu daftar isi halaman. Pengguna
pembaca layar melompat antar heading untuk memindai halaman, sama seperti orang lain memindai
dengan mata. Kalau judul seksi ditulis pakai `<p>` yang cuma dibesarkan lewat CSS, seksi itu
**tidak ada** di daftar isi tersebut, dan halamannya terasa seperti satu blok panjang tanpa
pegangan. Google juga membaca heading untuk menebak isi halaman.

---

## Bagian satu · Apa yang ada di prototipe sekarang

Ini hasil pembacaan langsung dari file HTML-nya.

```
h1   Your kind journaling companion                 baris 590, hero
h2   Kelas journaling di Jogja untuk yang belum     baris 601, di dalam foto panggung
     tahu mau menulis apa
h2   Tracing Shadows, Mapping Stars                 baris 611, kartu kecil di atas foto
     [ Tentang ruangnya ]  judul seksi pakai <p>
     [ Jadwal ]            judul seksi pakai <p>
h3   Tracing Shadows, Mapping Stars                 baris 675, kartu sesi terdekat
     [ Dokumentasi ]       judul seksi pakai <p>
     [ Cetakan ]           judul seksi pakai <p>
     [ Sudah lewat ]       judul seksi pakai <p>
h3   TJR x Snapobox                                 baris 809
h3   TJR x Pasar Jakal                              baris 812
h3   TJR x AMCO Bakehouse x Naoki Pics              baris 815
h3   TJR x Kolondjono                               baris 818
h3   TJR x Statement Beauty                         baris 821
h3   TJR x Artotel                                  baris 824
h3   TJR x Wardah                                   baris 827
h3   TJR x Kupiku Coffee                            baris 830
h3   TJR x Radian                                   baris 833
h3   TJR x Sunday Reads Club                        baris 836
     [ Pernah bareng ]     judul seksi pakai <p>
     [ Sampai ketemu ]     judul seksi pakai <p>
```

### Enam temuan

**1. Tujuh judul seksi tidak berbentuk heading sama sekali.**
`Yang tumbuh di meja panjang`, `Sore yang sudah dijadwalkan`, `Apa yang tertinggal di meja`,
`Sore sore yang sudah lewat`, `Sepuluh kali duduk bareng`, `Sebelas nama di meja`, dan
`Bawa dirimu saja` semuanya ditulis `<p class="d d-xl">`. Kelihatan seperti judul karena
ukurannya besar, tapi bagi pembaca layar dan bagi Google, itu paragraf biasa.

Akibatnya, halaman beranda yang panjangnya tujuh seksi cuma punya tiga tingkat heading yang
semuanya menumpuk di area hero. **Ini temuan yang paling penting di dokumen ini.**

**2. Urutan heading lompat dari h2 ke h3 tanpa induk.**
Sepuluh `h3` di seksi Arsip menggantung. Heading terdekat di atasnya adalah `h2` berisi
`Tracing Shadows, Mapping Stars`, yaitu kartu kecil di pojok foto hero. Jadi kalau pengguna
pembaca layar membuka daftar heading, dia melihat sepuluh nama kolaborasi seolah olah anak
dari kartu sesi terdekat. Tidak ada hubungannya.

Hal yang sama terjadi pada `h3` sesi terdekat di baris 675.

**3. Satu judul yang sama muncul dua kali, di dua tingkat berbeda.**
`Tracing Shadows, Mapping Stars` jadi `h2` di kartu hero dan `h3` di kartu sesi terdekat.
Dua duanya menunjuk acara yang sama. Di daftar heading, ini terbaca seperti dua acara berbeda.

**4. `h2` di dalam foto panggung sebenarnya bukan judul seksi.**
`Kelas journaling di Jogja untuk yang belum tahu mau menulis apa` posisinya di dalam foto,
fungsinya subjudul hero. Menulisnya sebagai `h2` bikin dia sejajar dengan judul seksi lain
padahal isinya bagian dari hero.

**5. `h1` tidak membawa keyword apa pun.**
`Your kind journaling companion` adalah tagline Instagram. Bahasanya Inggris di situs
berbahasa Indonesia, dan nol kata yang diketik orang waktu mencari. Kalimat yang membawa
keyword justru diturunkan jadi `h2` di dalam foto, tempat yang bobotnya lebih ringan.

**6. Judul tiga kartu jadwal berikutnya juga bukan heading.**
`Much Between the Lines`, `Journaling Playdate`, dan `A Moment Between Chapters` ditulis
`<p class="d">`. Pengguna yang memindai lewat heading melewatinya begitu saja, padahal tiga
kartu itu tiga acara yang bisa dipesan.

### Yang sudah benar, jangan diubah

- Cuma ada satu `h1` di halaman.
- Ikon SVG dekoratif sudah diberi `aria-hidden="true"`.
- Label kecil di atas judul, `.lbl`, memang **tidak boleh** jadi heading. `Jadwal`,
  `Dokumentasi`, `Cetakan`, `Sudah lewat`, `Pernah bareng` itu penanda mata, bukan judul.
  Biarkan sebagai `<p>`.

---

## Bagian dua · Struktur yang seharusnya, per halaman

### Beranda · `/`

```
h1  Workshop journaling di Jogja untuk yang belum tahu mau menulis apa
    ( tagline "Your kind journaling companion" turun jadi eyebrow atau kutipan )

h2  Sesi terdekat
    h3  {judul acara terdekat}

h2  Yang tumbuh di meja panjang
h2  Sore yang sudah dijadwalkan
    h3  {judul acara}          untuk tiap kartu, termasuk tiga kartu "Setelah itu"
h2  Apa yang tertinggal di meja
h2  Sore sore yang sudah lewat
h2  Sepuluh kali duduk bareng
    h3  TJR x {nama kolaborator}    sepuluh baris
h2  Sebelas nama di meja
h2  Bawa dirimu saja
```

Catatan penting untuk kartu kecil di atas foto hero. Kartu itu isinya sama dengan seksi
`Sore yang sudah dijadwalkan`. Dua pilihan, dan yang mana pun boleh asal konsisten:

- **Kalau kartunya dianggap ringkasan**, buang heading dari kartu itu, cukup teks biasa.
  Judul acara yang asli tetap hidup sebagai `h3` di seksi jadwal.
- **Kalau kartunya dianggap seksi sendiri**, beri `h2 Sesi terdekat` lalu judul acaranya `h3`.

Yang tidak boleh: membiarkan judul acara jadi `h2` di kartu dan `h3` di seksi jadwal, karena
itu keadaan sekarang dan itulah yang bikin urutannya lompat.

### Jadwal · `/jadwal/`

```
h1  Jadwal workshop journaling di Jogja

h2  Sesi yang masih buka
    h3  {judul acara}          satu per acara mendatang
h2  Sesi yang sudah lewat
    h3  {judul acara}          satu per acara yang sudah selesai
h2  Pertanyaan yang sering masuk
    h3  Belum pernah journaling sama sekali, boleh ikut?
    h3  Perlu bawa apa?
    h3  Datang sendirian, canggung nggak?
    h3  Berapa harganya?
```

Kalau filter format dan kota nanti dipakai, judul filternya bukan heading. Itu kontrol, dan
tempatnya `<fieldset>` dengan `<legend>`, bukan `h2`.

### Detail acara · `/jadwal/{slug-acara}/`

```
h1  {judul acara}

h2  Yang akan kita kerjakan
h2  Tanggal, tempat, dan kursi
h2  Yang sudah disediakan
h2  Yang perlu kamu bawa
h2  Cara ambil slot
h2  Foto dari sesi sebelumnya
h2  Sesi lain yang mirip
    h3  {judul acara lain}
```

Judul acara wajib `h1`, dan tidak boleh ada `h1` kedua. Nama venue bukan heading, tempatnya di
dalam daftar fakta `<dl>` seperti yang sudah dipakai di prototipe. Bagian `<dl>` itu sudah
benar, pertahankan.

### Galeri · `/galeri/`

```
h1  Galeri sesi journaling di Jogja

h2  {nama kolaborasi}         satu h2 per kelompok foto, sepuluh kelompok
```

Jangan pakai `h3` di sini. Tiap kelompok foto setara, dan satu tingkat sudah cukup. Menambah
tingkat cuma bikin daftar isi lebih dalam tanpa alasan.

### Arsip kolaborasi · `/kolaborasi/`

```
h1  Kolaborasi workshop journaling di Yogyakarta

h2  Sepuluh kali duduk bareng
    h3  TJR x {nama kolaborator}     sepuluh baris, urut dari yang terbaru
h2  Sebelas nama di meja
h2  Mau bikin sesi buat brand atau tim kamu
```

Sepuluh `h3` di sini punya induk yang jelas. Persis ini yang hilang di prototipe.

### Tentang · `/tentang/`

```
h1  Tentang The Journaling Room

h2  Kenapa ruangan ini ada
h2  Caca dan Dhanty
    h3  Caca
    h3  Dhanty
h2  Cara satu sore berjalan
    h3  Datang dan duduk
    h3  Satu pertanyaan pendek
    h3  Waktu bebas sampai lemnya kering
h2  Yang tidak akan terjadi di sini
h2  Tempat kami biasa berkumpul
```

Seksi `Yang tidak akan terjadi di sini` sengaja ada. Itu yang menjawab keberatan terbesar
pemula, dan judulnya cukup jelas untuk berdiri sendiri di daftar heading.

### Kontak · `/kontak/`

```
h1  Kontak The Journaling Room

h2  WhatsApp
h2  Instagram
h2  Tempat dan area layanan
h2  Pertanyaan yang sering masuk
    h3  {pertanyaan}
```

Nomor WhatsApp bukan heading. Nomor adalah isi, dan isi tempatnya di paragraf atau link.

---

## Bagian tiga · Aturan yang berlaku di semua halaman

1. **Satu `h1` per halaman.** Tidak nol, tidak dua.
2. **Tidak boleh lompat tingkat.** Sesudah `h2` boleh `h3`, tidak boleh langsung `h4`.
   Sesudah `h3` boleh naik lagi ke `h2` kapan saja, itu bukan lompatan.
3. **Ukuran teks tidak menentukan tingkat heading.** Kalau butuh `h3` yang tampil besar, atur
   lewat kelas CSS. Di prototipe sudah ada kelasnya, `.d-xl`, `.d-lg`, `.d-md`, dan itu bisa
   dipasang di elemen heading mana pun tanpa mengubah tingkatnya.
4. **Label kecil di atas judul bukan heading.** `.lbl` tetap `<p>`.
5. **Kalau judul cuma ada untuk pembaca layar**, pakai heading sungguhan lalu sembunyikan
   dengan kelas `.screen-reader-text` bawaan WordPress. Jangan pakai `display:none`, itu
   menyembunyikannya dari pembaca layar juga.
6. **Tiga blok navigasi di footer butuh nama.** Judul kolom `Halaman`, `Dokumentasi`, dan
   `Hubungi kami` sekarang `<p class="lbl">`. Ubah jadi heading yang disembunyikan, atau beri
   `aria-labelledby` yang menunjuk ke `<p>` itu. Tanpa nama, pembaca layar cuma mengumumkan
   `daftar, 4 item` tiga kali dan pengguna tidak tahu daftar apa.

## Yang harus dikerjakan waktu bikin block pattern

Judul seksi di prototipe ditulis `<p class="d d-xl">`. Di WordPress, blok Heading punya
pengaturan tingkat sendiri, jadi solusinya gampang: pakai blok Heading, atur tingkatnya `H2`,
lalu tempelkan kelas `d d-xl` di kolom Advanced, Additional CSS class. Tampilannya sama persis,
strukturnya jadi benar.

**Kunci tingkat headingnya di block pattern** lewat `"lock":{"move":false,"remove":false}`
atau lewat `templateLock`, supaya Caca dan Dhanty tidak sengaja mengubah `H2` jadi `H1` waktu
mengedit. Sekali ada dua `h1` di satu halaman, tidak ada yang akan sadar sampai audit
berikutnya.
