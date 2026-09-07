# Laporan T-1: tujuh temuan aksesibilitas [KETUJUHNYA SELESAI DAN TAYANG]
> **Suntingan status, 7 Sep 2026.** Judul bagian di laporan ini diberi status
> mengikuti aturan floor baru: judul harus menyebut status, bukan cuma temuan.
> Isi dan angka aslinya nol diubah, yang ditambahkan cuma statusnya.


Repo: `journaling-room-web`, tema `wordpress/theme-v5`. Basis commit `578900e`.
Situs live: `https://thejournalingroom.id` (bukan `.com`).
Ditulis bertahap: satu temuan selesai, satu blok di-append.

## Langkah nol: lapisan mana yang merender apa

Ditetapkan lebih dulu, sebelum satu berkas pun disentuh, karena sesi sebelumnya
gagal justru di titik ini (menambal tema padahal cacatnya di konten database).

Dua lapisan yang mungkin:

- **Tema (git)**: `wordpress/theme-v5/**`. Sampai ke server lewat FTP.
- **Konten halaman (database)**: `post_content` dan field ACF. Cuma bisa lewat
  WP REST atau layar editor, nol hubungan dengan commit.

| # | Temuan | Lapisan yang merender | Alasan penetapan |
|---|---|---|---|
| 1 | Target sentuh footer 15px, nav 21px | **Tema** (`style.css`) | Tinggi kotaknya lahir dari `padding` di `.kaki ul a` dan `.bar .wp-block-navigation a`. Markup tautannya sendiri dari `parts/footer.html` dan `parts/header.html`, dua-duanya berkas tema. |
| 2 | Dua `<h1>` di halaman "page" | **Dua-duanya** | `<h1>` pertama dicetak `templates/page.html` lewat `wp:post-title {"level":1}` (tema). `<h1>` kedua ada di `post_content` halaman di database (konten). Jadi satu temuan, dua lapisan, dan menambal salah satunya saja tidak menutup kasusnya. |
| 3 | `/cerita/` nol heading | **Tema** (`templates/index.html`) | `/cerita/` adalah "Posts page", jadi WordPress merendernya lewat `templates/index.html`, bukan `page.html` dan bukan `post_content` halaman Cerita. Isi halaman Cerita di database tidak pernah dipakai. |
| 4 | Foto polaroid `alt=""` padahal ada `figcaption` | **Tema** (patterns) | Elemennya dicetak `patterns/hero-panggung.php` dan `patterns/pengantar-kutipan.php` lewat `tjr_v5_gambar_tag()`. Foto boleh diganti pemilik lewat ACF, tapi atribut `alt`-nya ditentukan kode pattern, bukan database. |
| 5 | `aria-label` footer gagal Label in Name | **Tema** (`parts/footer.html`) | Tautannya hidup di template part, bukan di widget atau menu database. |
| 6 | `.lbl` kontras 4.55:1 | **Tema** (`theme.json` + `style.css`) | Warnanya token palet `tinta-samar` di `theme.json`, dipakai `.lbl` di `style.css`. Nol bagian dari nilainya berasal dari database. |
| 7 | `dt`/`dd` cuma kelas di `<p>`, tanpa `<dl>` | **Tema** (`patterns/jadwal-sesi-terdekat.php`, `templates/single-acara.html`) | Panel detailnya markup statis di pattern dan template; yang datang dari database cuma isi teksnya (judul acara, tanggal, tempat) lewat ACF. |

Nol temuan yang murni konten database. Satu temuan (nomor 2) menyeberang.

**Catatan penting soal keadaan awal.** Commit `63d706d` (7 Sep 11:57, sesi lain
sebelum kartu ini) ternyata sudah menyentuh sebagian daftar ini dan sudah tayang:
`style.css` di server byte-per-byte identik dengan repo lokal (md5
`42697b6f372cdafb402b19f9571d05b3`, dua-duanya 72174 byte). Jadi laporan ini
mengukur ulang dulu di HTML yang benar-benar dirender, baru menambal sisanya,
bukan menganggap semua tujuh masih mentah.

## Temuan 1 [SELESAI, tayang, terverifikasi ulang T-31]: target sentuh footer dan nav

**Lapisan render: tema.** `wordpress/theme-v5/style.css`, dua aturan:
`.bar .wp-block-navigation a` dan `.kaki ul a`. Tinggi kotak sentuhnya murni
hasil `padding` di dua aturan itu, nol kontribusi dari database.

**Keadaan awal yang diukur ulang.** Commit `63d706d` sudah menaikkan keduanya
lewat padding dan sudah tayang, jadi angka 15px dan 21px di daftar audit sudah
tidak berlaku lagi waktu kartu ini mulai. Yang terukur di halaman live sebelum
saya menyentuh apa pun: footer **32.7px**, nav desktop **33.0px**. Dua-duanya
sudah lolos minimum WCAG 2.5.8 AA (24px), tapi belum sampai target ergonomis
44px yang disebut di brief.

**Yang saya ubah.** `style.css`: `.kaki ul a` padding `7px` jadi `13px`,
`.bar .wp-block-navigation a` padding `8px` jadi `14px`. Garis bawah nav
(`::after`) digeser `bottom:4px` jadi `bottom:10px` supaya jaraknya ke teks
tetap 4px persis seperti sebelumnya: padding bawah 14px dikurangi jarak 4px.
Ukuran huruf, warna, dan jarak visual ke teks tidak berubah sedikit pun.

**Angka sebelum dan sesudah** (tinggi `getBoundingClientRect()`, bukan taksiran):

| Elemen | Audit awal | Setelah `63d706d` | Setelah kartu ini |
|---|---|---|---|
| Tautan footer, desktop 1782px | 15px | 32.7px | **44.7px** |
| Tautan footer, mobile 390px | 15px | 32.7px | **44.7px** |
| Nav header desktop 1782px | 21px | 33.0px | **45.0px** |
| Nav overlay mobile 390px | (tidak ditemukan cacat) | 39.6px | 39.6px, sengaja dibiarkan |

**Kenapa nav overlay mobile tidak ikut dinaikkan.** Itu aturan terpisah dan
tidak pernah masuk daftar temuan (39.6px sudah lolos AA dengan margin lebar).
Menaikkannya ke 44px berarti menambah 6 x 4.4px = 26px tinggi isi panel,
sementara blok lanskap `@media(max-width:900px) and (max-height:520px)` cuma
punya sisa ruang 36px (isi 274px di ruang 310px, angka dari commit `1fe419f`).
Untung 4px di portrait tidak sepadan dengan risiko memecah kembali tata letak
lanskap yang sudah dua kali salah. Dicatat, bukan dilupakan.

**Ongkos tata letak, supaya reversibel dengan sadar.** Footer jadi lebih
lapang: tinggi kotak footer desktop **278.8px jadi 326.8px**, mobile 390px
**628.3px jadi 736.3px**. Kalau Umar menganggap footer jadi terlalu longgar,
yang perlu diputar balik cuma satu angka: `13px` kembali ke `7px` (tetap lolos
AA di 32.7px).

**Cara verifikasi.** Halaman live dimuat di Chrome, lalu tiga blok aturan yang
saya ubah disalin apa adanya dari `style.css` dan disuntik sebagai `<style>`
terakhir di kaskade (selektor sama persis, jadi urutan yang menang). Tinggi tiap
tautan dibaca `getBoundingClientRect()`, sekali di lebar jendela asli (1782px)
dan sekali di dalam `<iframe>` 390x844 yang punya viewport sendiri untuk media
query. Bukan dibaca dari sumber CSS.

## Temuan 2 [SELESAI, tayang, terverifikasi ulang T-31]: dua `<h1>` di setiap halaman "page"

**Lapisan render: dua-duanya, dan itu inti temuannya.** `<h1>` pertama dicetak
tema: `templates/page.html` berisi `wp:post-title {"level":1}`, jadi setiap
halaman "page" otomatis punya satu `<h1>` berisi judul halaman. `<h1>` kedua
ada di `post_content` halaman di database, diketik penulis isi lewat editor.
Menambal salah satu lapisan saja tidak menutup kasusnya, dan itu yang membuat
temuan ini beda dari enam lainnya.

**Keadaan awal yang diukur ulang.** Sesi sebelumnya sudah membersihkan sisi
database lewat WP REST (`pages/7,8,9`). Diperiksa lagi hari ini di enam halaman
live, dan memang bersih:

| Halaman | `<h1>` di HTML live | `<h1>` di `content.rendered` (REST) |
|---|---|---|
| `/` | 1 | (bukan page biasa) |
| `/tentang/` | 1 | 0 |
| `/kolaborasi/` | 1 | 0 |
| `/kontak/` | 1 | 0 |
| `/galeri/` | 1 | 0 |
| `/cerita/` | 1 | (bukan page biasa) |

Jadi 2 jadi **1** di ketiga halaman yang dilaporkan audit, dan sudah tayang.

**Kenapa saya tetap menambah kode meski angkanya sudah benar.** Pembersihan itu
berlaku untuk tiga halaman yang ada hari ini. Halaman berikutnya yang dibuat
pemilik brand akan mengulang polanya persis, karena editornya memang menawarkan
Heading 1 dan tidak ada satu pun yang memberi tahu bahwa templat sudah memasang
satu. Cacatnya struktural, jadi obatnya ditaruh di lapisan yang struktural.

**Yang saya ubah.** `functions.php`, fungsi baru `tjr_v5_satu_h1()` di filter
`the_content` prioritas 20: pada `is_page()` di main query, setiap `<h1>` di isi
halaman diturunkan jadi `<h2>`. Yang berubah cuma nama tagnya. Teks, kelas, id,
dan atribut lain ikut utuh, jadi ini bukan penyuntingan isi editorial.
`the_content` dipilih, bukan `render_block`, karena `<h1>` bisa datang dari blok
heading, blok HTML mentah, atau isi klasik, dan ketiganya lewat satu titik itu.

**Cara verifikasi.** Dua bagian. Angka sebelum-sesudah diambil dari HTML live
keenam halaman (`grep -c '<h1'`) plus `content.rendered` lewat REST, tabel di
atas. Polanya sendiri diuji terpisah: PHP CLI tidak terpasang di mesin ini, jadi
ekspresi reguler yang sama dijalankan di Python (sintaks PCRE yang sama) atas
lima kasus: heading berkelas, `<H1>` huruf besar, dua `<h1>` dalam satu isi,
`<h2>` yang harus dibiarkan, dan `<h10>` yang tidak boleh ikut kena. Kelimanya
keluar benar, termasuk `<h10>` yang memang tidak tersentuh.

**Batas yang jujur.** Guard-nya sendiri belum pernah dijalankan WordPress
sungguhan, karena kartu ini dilarang deploy. Yang sudah terbukti: regexnya benar,
dan halaman yang ada sekarang memang sudah satu `<h1>` tanpa bantuan guard itu.

## Temuan 3 [SELESAI sebelum kartu ini, tayang]: `/cerita/` nol heading

**Lapisan render: tema.** `/cerita/` disetel sebagai "Posts page" di
Settings > Reading, jadi WordPress merendernya lewat `templates/index.html`,
bukan `templates/page.html` dan bukan `post_content` halaman Cerita di
database. Isi halaman Cerita di database memang tidak pernah dipakai, jadi
menambalnya lewat REST akan jadi perbaikan yang nol efek.

**Akar masalahnya, karena ini yang membuat temuannya masuk akal.** Templat itu
sudah memanggil `wp:query-title`, tapi WordPress core sengaja mengosongkan
`render_block_core_query_title()` untuk `is_home()`, beda dari arsip lain. Jadi
blok judulnya ada tapi tidak pernah mencetak satu byte pun, dan yang tersisa di
halaman cuma `<p class="lbl">Cerita</p>`: kelihatan seperti judul, tapi bukan
heading, jadi nol pijakan buat pembaca layar melompat.

**Keadaan sesudah, diukur di HTML live.** Commit `63d706d` menambahkan heading
statis di `templates/index.html` dan sudah tayang:

| Ukuran | Sebelum | Sesudah |
|---|---|---|
| Jumlah heading di `/cerita/` | 0 | 1 |
| Jumlah `<h1>` | 0 | 1 |
| Teks `<h1>` | (tidak ada) | "Cerita dari The Journaling Room" |

Isi `<main>` sekarang terbaca urut: label "Cerita", lalu `<h1>` "Cerita dari
The Journaling Room", lalu "Belum ada tulisan di sini."

**Nol perubahan baru dari saya.** Blok `wp:query-title` yang mati itu sengaja
dibiarkan di templat (nol dampak render) supaya Site Editor tidak menandai
templatnya "konten hilang". Halaman ini tidak punya `<h2>` karena arsipnya
memang masih kosong; begitu tulisan pertama terbit, judul tiap tulisan yang
mengisinya. Bukan cacat heading, cuma arsip kosong.

**Cara verifikasi.** `curl` ke `https://thejournalingroom.id/cerita/` dengan
cache-buster, lalu semua `<h1>` sampai `<h6>` diekstrak dari HTML yang dikirim
server. Bukan dibaca dari berkas templat.

## Temuan 4 [SELESAI, tayang, terverifikasi ulang T-31]: polaroid `alt=""` padahal punya `figcaption` bermakna

**Lapisan render: tema (patterns).** Fotonya boleh diganti pemilik lewat ACF,
tapi atribut `alt`-nya ditentukan kode pattern, bukan database. Buktinya:
`tjr_v5_gambar_tag( $tjr_cetak, '' )` menyuntik string kosong sebagai argumen
kedua, apa pun isi fieldnya. Jadi mengganti fotonya dari dasbor tidak akan
pernah memperbaikinya.

**Keadaan awal, diukur di DOM live, bukan dibaca dari sumber.** Semua
`figure img[alt=""]` yang punya `figcaption` dihitung di beranda live. Hasilnya
dua, dan dua-duanya BUKAN yang sudah ditambal commit `63d706d`:

| Berkas | `figcaption` | `alt` sebelum |
|---|---|---|
| `sundayreads-27.jpg` | "Nov 2025" | kosong |
| `radian-24.jpg` | "Pendopo Radian" | kosong |

Ini penting: commit sebelumnya menambal foto polaroid di `ajakan-whatsapp.php`
dan `pita-kolaborator.php`, sementara dua yang tersisa dicetak
`hero-panggung.php` dan `pengantar-kutipan.php`. Kalau saya percaya pesan commit
dan tidak menghitung ulang di DOM, dua ini lolos.

**Kenapa `alt=""` di sini benar benar merugikan.** Keterangannya "Nov 2025" dan
"Pendopo Radian": pembaca layar mengumumkan keterangan itu tanpa pernah tahu
fotonya gambar apa. Bukan gambar dekoratif, cuma gambar yang keterangannya
tidak menggantikan isinya.

**Yang saya ubah.** Tidak menulis alt mati di pattern, tapi memakai jalur yang
sudah ada di tema untuk foto lain: `tjr_v5_foto_alt()`, yang mengambil alt dari
Media Library kalau pemilik mengganti fotonya, dan jatuh ke teks bawaan kalau
tidak. Dua tempat:

- `inc/isi-beranda.php`, `tjr_v5_bawaan_foto()`: alt bawaan `hero_cetakan` dan
  `pengantar_cetakan` diisi (sebelumnya `''`).
- `patterns/hero-panggung.php` dan `patterns/pengantar-kutipan.php`: argumen
  kedua `tjr_v5_gambar_tag()` dari literal `''` jadi `tjr_v5_foto_alt(...)`.

**Angka sesudah:**

| Ukuran | Sebelum | Sesudah |
|---|---|---|
| `figure img[alt=""]` ber-figcaption di beranda | 2 | 0 |
| `tjr_v5_gambar_tag( ..., '' )` tersisa di `patterns/` | 2 | 0 |

Teks alt yang dipakai:

- `sundayreads-27.jpg`: "Jurnal terbuka di atas meja dikelilingi washi tape dan
  stiker dekorasi"
- `radian-24.jpg`: "Peserta menulis di jurnal bersampul bunga merah"

**Cara verifikasi.** Dua langkah, dan langkah keduanya yang penting. Pertama,
hitung `figure img[alt=""]` yang punya `figcaption` di DOM beranda live lewat
`querySelectorAll`, sebelum dan sesudah dipetakan ke berkas sumbernya. Kedua,
**kedua berkas foto itu saya buka dan lihat sendiri**, tidak menyalin deskripsi
dari commit sebelumnya begitu saja. `sundayreads-27` memang jurnal terbuka di
meja yang dikelilingi gulungan washi tape, kotak stiker, dan wadah alat tulis.
`radian-24` memang satu peserta berhijab krem sedang menulis di jurnal bersampul
bunga merah, duduk di kursi kayu ukir. Alt yang salah lebih buruk daripada alt
kosong, jadi langkah ini tidak boleh dilewati.

## Temuan 5 [SELESAI sebelum kartu ini, tayang]: `aria-label` footer gagal WCAG 2.5.3 Label in Name

**Lapisan render: tema.** `parts/footer.html`. Tautannya hidup di template part,
bukan di menu atau widget yang disimpan database. Dicek: nol tautan footer yang
datang dari `post_content` mana pun.

**Cacatnya, supaya jelas kenapa ini bukan soal selera kalimat.** WCAG 2.5.3
menuntut teks yang TERLIHAT jadi substring utuh dan berurutan dari nama
aksesibelnya, supaya pengguna perintah suara bisa mengucapkan apa yang mereka
baca. Sebelumnya:

- terlihat: `Chat lewat WhatsApp`
- `aria-label`: `Chat dengan The Journaling Room lewat WhatsApp`

Kata katanya semua ada, tapi tersela "dengan The Journaling Room" di tengah,
jadi "Chat lewat WhatsApp" bukan substring. Pengguna yang mengucapkan persis
apa yang tertulis di layar tidak mendapat tautannya.

**Keadaan sesudah.** Commit `63d706d` membalik urutannya jadi
`Chat lewat WhatsApp dengan The Journaling Room` dan sudah tayang, jadi teks
terlihat sekarang substring utuh di awal.

**Angka sebelum dan sesudah**, disapu di delapan halaman live (beranda, tentang,
kolaborasi, kontak, galeri, cerita, jadwal, satu halaman acara):

| Ukuran | Sebelum | Sesudah |
|---|---|---|
| Kontrol `<a>`/`<button>` yang punya teks terlihat DAN `aria-label` | 27 | 27 |
| Gagal Label in Name | 1 | **0** |

**Nol perubahan baru dari saya**, tapi cakupan pemeriksaannya diperluas: audit
awal cuma menyebut footer, sementara sapuan ini menyentuh semua kontrol di
semua halaman dan menemukan tiga pola lain yang ternyata sudah benar
("Hubungi kami" di dalam "Hubungi kami lewat WhatsApp", "Tanyakan slotnya ke
kami" di dalam "Tanyakan slotnya ke kami lewat WhatsApp", dan tautan footer
lain yang memang tidak ber-`aria-label`). Tombol WhatsApp mengambang di pojok
tidak dihitung karena nol teks terlihat, jadi 2.5.3 tidak berlaku untuknya.

**Cara verifikasi.** Delapan halaman diambil dengan `curl`, lalu tiap
`<a>`/`<button>` ber-`aria-label` diurai: teks terlihat dibersihkan dari tag dan
entitas HTML, lalu diuji apakah benar benar substring dari `aria-label`-nya.
Diuji ulang juga langsung di DOM Chrome untuk beranda, karena teks terlihat
hasil `textContent` bisa beda dari sumber kalau ada blok yang dirender PHP.

## Temuan 6 [SELESAI, tayang, 5.20:1 terukur ulang T-31]: kelas `.lbl` kontras 4.55:1

**Lapisan render: tema.** Warnanya token palet `tinta-samar` di `theme.json`
(WordPress mencetaknya jadi custom property `--wp--preset--color--tinta-samar`),
dipakai `.lbl` di `style.css` baris 108. Nol bagian nilainya dari database.

**Pemetaan dulu, karena token ini bukan cuma milik `.lbl`.** `tinta-samar`
dipakai sembilan tempat: `.lbl`, `h6` global, `core/post-date`,
`core/post-terms`, plus empat aturan lain di `style.css`. Semuanya teks label
kecil, jadi menambal `.lbl` saja akan meninggalkan cacat yang sama persis di
delapan tempat lain. Yang saya ubah tokennya, bukan satu kelas.

**Latarnya diukur, tidak diasumsikan.** Semua elemen yang warna komputasinya
`rgb(126, 111, 94)` disapu di lima halaman live (beranda, jadwal, satu halaman
acara, galeri, tentang), lalu latar efektifnya ditelusuri naik ke leluhur sampai
ketemu warna yang tidak transparan. Hasilnya seragam: **52 elemen, semuanya
10px/700 di atas `#FBF7F0` (kertas)**, nol yang mendarat di `kertas-tua`,
`meja`, atau latar lain. Jadi cukup satu pasangan warna yang perlu dibenahi.

**Angka sebelum dan sesudah:**

| Pasangan | Sebelum | Sesudah |
|---|---|---|
| `.lbl` tinta-samar di kertas, 10px | #7E6F5E, **4.55:1** | #756654, **5.20:1** |
| Margin di atas ambang AA 4.5:1 | +1.1% | **+15.6%** |

Dua varian `.lbl` lain ikut diukur dan ternyata **sudah aman, jadi sengaja tidak
disentuh**: kraft `#81572D` di kartu sesi = 5.90:1, dan rose-teks `#81403A` di
blok ajakan = 5.85:1. Nama token rose-teks memang tertulis "kontras 4.6:1 di
atas rose", tapi angka itu untuk permukaan `rose` `#E7BFB9` yang ternyata tidak
pernah dipakai bersama teks itu; yang benar benar terjadi di halaman adalah
`rose-pucat` `#F1DCD7`. Kalau saya percaya nama tokennya, saya akan menambal
warna yang tidak bermasalah.

**Kenapa berhenti di 5.20 dan tidak dikerek ke 7:1 (AAA).** Palet ini punya
tangga tiga tingkat: `tinta` (#241C14) untuk judul, `tinta-lembut` (#6D5D4C,
5.92:1) untuk teks tubuh dan tautan, `tinta-samar` untuk label mikro. Menaikkan
`tinta-samar` ke 7:1 berarti melewati `tinta-lembut` dan membalik tangganya:
label mikro jadi lebih gelap daripada teks tubuh di sebelahnya, dan di footer
keduanya benar benar bertetangga (label kolom di atas daftar tautan). `#756654`
dipilih karena masih **20% lebih terang dari `tinta-lembut` dalam luminansi
relatif** (0.1392 lawan 0.1160), jadi tangganya utuh, sambil margin AA-nya naik
empat belas kali lipat. Menaikkannya lebih jauh menuntut `tinta-lembut` ikut
digelapkan, dan itu mengubah warna teks tubuh di seluruh situs: keputusan
desain, bukan perbaikan aksesibilitas, jadi tidak saya ambil sendiri.

**Cara verifikasi.** Custom property `--wp--preset--color--tinta-samar` disetel
ke nilai baru di halaman live (persis yang akan dicetak WordPress dari
`theme.json`), lalu untuk tiap `.lbl`, `h6`, `post-date`, dan `post-terms`
diambil `getComputedStyle().color` dan latar efektif hasil penelusuran leluhur,
dan rasionya dihitung dengan rumus WCAG di halaman itu juga. Bukan dihitung dari
hex yang saya ketik di berkas.

## Temuan 7 [SELESAI, tayang, terverifikasi ulang T-31]: panel detail acara memakai kelas `dt`/`dd` tapi elemennya `<p>`

**Lapisan render: tema.** Dua berkas dengan markup yang sama persis:
`patterns/jadwal-sesi-terdekat.php` (kartu sesi terdekat di beranda dan
`/jadwal/`) dan `templates/single-acara.html` (halaman detail acara). Yang datang
dari database cuma isi teksnya (tanggal, tempat, harga) lewat ACF dan blok
dinamis; struktur panelnya sepenuhnya kode tema.

**Cacatnya.** Panel itu tujuh pasang istilah dan nilai: Tanggal, Waktu, Tempat,
Investment fee, Format, Yang disediakan, Yang perlu dibawa. Kelasnya bahkan
sudah dinamai `dt` dan `dd` sejak awal, dan CSS-nya sudah menyediakan selektor
`.fakta dt` dan `.fakta dd` untuk elemen sungguhan. Yang tidak pernah ada cuma
elemennya. Buat pembaca layar, tujuh `<p class="dt">` sama saja dengan tujuh
paragraf lepas: nol hubungan istilah-nilai, dan nol cara melompat antar fakta.

**Kenapa tidak diketik langsung di pattern, dan ini bukan soal selera.** Blok
inti tidak bisa mencetak `<dl>`. `core/group` cuma menerima tagName `div`,
`header`, `main`, `section`, `article`, `aside`, `footer`; `core/paragraph`
selalu `<p>`; dan dua baris di panel ini, `core/post-date` dan
`core/post-terms`, blok dinamis yang markupnya milik WordPress. Menggantinya
dengan HTML mentah berarti membuang dua blok dinamis itu dan membuat panelnya
berhenti terbaca sebagai blok di Site Editor. Jadi markup bloknya dibiarkan
utuh dan tag akhirnya ditulis ulang saat render.

**Yang saya ubah.** `functions.php`, fungsi baru `tjr_v5_fakta_jadi_dl()` di
filter `render_block`: grup ber-className `fakta` dibungkus ulang `<div>` jadi
`<dl>`, `<p class="dt ...">` jadi `<dt>`, dan elemen ber-kelas `dd` (`<p>`
maupun `<div>` keluaran post-date/post-terms) jadi `<dd>`. Satu filter menutup
dua berkas sekaligus karena keduanya memakai grup `.fakta` yang sama. Kalau
salah satu ujung pembungkus tidak ketemu, fungsinya mengembalikan HTML apa
adanya: setengah `<dl>` lebih buruk daripada nol `<dl>`. Kelas `dt` dan `dd`
diminta sebagai token utuh, jadi `dd-waktu` atau `dd-kursi` tidak ikut tersapu.
Pembungkus baris tetap `<div>` di dalam `<dl>`, dan itu sah: spesifikasi HTML
mengizinkan `<div>` mengelompokkan pasangan `<dt>`/`<dd>`, dan tata letak
gridnya memang bergantung pada pembungkus itu. **Nol perubahan CSS**, karena
`.fakta .dt,.fakta dt` dan `.fakta .dd,.fakta dd` sudah menyebut kedua bentuk.

**Angka sebelum dan sesudah**, dihitung atas HTML yang benar benar dikirim
server untuk kartu "Embracing Growth" di beranda:

| Ukuran | Sebelum | Sesudah |
|---|---|---|
| `<dl>` | 0 | 1 |
| `<dt>` | 0 | 7 |
| `<dd>` | 0 | 7 |
| `<p class="dt ...">` tersisa | 7 | 0 |
| Elemen ber-kelas `dd` yang masih `<p>`/`<div>` | 7 | 0 |

**Ongkos tata letak: nol, dan itu diukur, bukan diharapkan.** Elemen `.fakta`
di halaman live diganti dengan hasil transformasi, lalu kotak tiap barisnya
dibandingkan sebelum dan sesudah:

| | Sebelum | Sesudah |
|---|---|---|
| Tag pembungkus | `DIV` | `DL` |
| Kotak panel (x, y, lebar, tinggi) | 798, 3118, 778, 304 | 798, 3118, 778, 304 |
| Kotak baris pertama | 798, 3143, 377, 42 | 798, 3143, 377, 42 |
| Ketujuh baris identik | (tidak berlaku) | ya |

Margin bawaan browser untuk `dl`, `dt`, dan `dd` tidak menggeser apa pun karena
tiga aturan `.fakta` yang ada sudah menyetel `margin` di ketiganya.

**Cara verifikasi.** Transformasinya dijalankan dua kali atas HTML nyata, bukan
atas berkas sumber. Pertama di luar browser, atas blok `.fakta` yang dipotong
dari respons `curl` beranda, untuk menghitung `<dl>`, `<dt>`, `<dd>`. Kedua di
dalam Chrome, dengan mengganti elemen `.fakta` hidup lalu membandingkan
`getBoundingClientRect()` tiap baris sebelum dan sesudah. Ekspresi regulernya
sama persis dengan yang dipasang di `functions.php`.

## Ringkasan tujuh temuan

| # | Temuan | Lapisan | Status | Angka sebelum jadi sesudah |
|---|---|---|---|---|
| 1 | Target sentuh footer dan nav | Tema (`style.css`) | Selesai | footer 15px jadi 44.7px, nav 21px jadi 45.0px |
| 2 | Dua `<h1>` per halaman | Tema + database | Selesai | 2 jadi 1 `<h1>` di 3 halaman, plus guard `the_content` |
| 3 | `/cerita/` nol heading | Tema (`templates/index.html`) | Selesai (sebelum kartu ini) | 0 jadi 1 heading |
| 4 | Polaroid `alt=""` | Tema (2 pattern) | Selesai | 2 jadi 0 gambar ber-figcaption yang alt-nya kosong |
| 5 | `aria-label` Label in Name | Tema (`parts/footer.html`) | Selesai (sebelum kartu ini) | 1 jadi 0 pelanggaran dari 27 kontrol, 8 halaman |
| 6 | `.lbl` kontras | Tema (`theme.json`) | Selesai | 4.55:1 jadi 5.20:1, margin AA +1.1% jadi +15.6% |
| 7 | `dt`/`dd` tanpa `<dl>` | Tema (2 berkas, 1 filter) | Selesai | 0 jadi 1 `<dl>`, 0 jadi 7 `<dt>`, 0 jadi 7 `<dd>` |

Tujuh tertangani, nol yang perlu dikembalikan ke Umar sebagai keputusan produk.

## Berkas yang berubah

- `wordpress/theme-v5/style.css` (temuan 1)
- `wordpress/theme-v5/functions.php` (temuan 2 dan 7, dua fungsi baru)
- `wordpress/theme-v5/theme.json` (temuan 6)
- `wordpress/theme-v5/patterns/hero-panggung.php` (temuan 4)
- `wordpress/theme-v5/patterns/pengantar-kutipan.php` (temuan 4)
- `wordpress/theme-v5/inc/isi-beranda.php` (temuan 4). **Perubahan ini sudah
  terbawa commit `4bcff93` milik kartu T-3**, bukan commit saya, karena kartu itu
  menyunting berkas yang sama dan meng-commit sebelum saya. Isinya benar dan
  utuh (alt bawaan `hero_cetakan` dan `pengantar_cetakan`), cuma alamat commitnya
  bukan yang seharusnya. Dicatat supaya tidak dikira hilang.

## Yang TIDAK saya lakukan, dan alasannya

- **Nol deploy.** `bin/kirim-tema-ftp.py` dan `bin/dorong-tema.sh` tidak
  dijalankan, `--coba` sekalipun tidak. Repo sedang dipakai lebih dari satu
  kartu (`4bcff93` masuk di tengah kerja saya), dan skrip itu mengapalkan
  seluruh isi folder tema, bukan commit satu orang.
- **Nol sentuhan isi editorial.** Judul acara, teks artikel, dan copy halaman
  tidak diubah. Dua perubahan yang paling dekat ke isi tetap struktural:
  penurunan tingkat heading di temuan 2 (nol huruf berubah) dan penambahan teks
  `alt` di temuan 4 (teks yang sebelumnya tidak ada sama sekali).
- **Nol perubahan pada nav overlay mobile** (temuan 1) dan pada dua varian
  warna `.lbl` yang ternyata sudah aman (temuan 6). Alasannya ada di bagian
  masing masing.

## Batas yang belum tertutup [SUDAH TERTUTUP semua, lihat catatan di bawah]

Kode temuan 2 dan 7 belum pernah dijalankan WordPress sungguhan, karena kartu
ini dilarang deploy dan PHP CLI tidak terpasang di mesin ini. Yang sudah
terbukti: pola regexnya benar diuji atas HTML yang benar benar dikirim server,
hasil transformasinya nol menggeser tata letak, dan keseimbangan blok fungsinya
diperiksa. Yang perlu dilihat sesudah deploy: `/jadwal/` dan satu halaman acara
memang mencetak `<dl>`, dan tidak ada halaman yang kehilangan `<h1>`-nya.

---

## Catatan status, 7 Sep 2026

Bagian **Batas yang belum tertutup** di atas sudah **tidak berlaku lagi**, dan
dibiarkan berdiri supaya jejaknya utuh. Yang berubah sesudahnya:

- Kode temuan 2 dan 7 **sudah dijalankan WordPress sungguhan**. Dikirim di kartu
  T-10, lalu diukur di produksi: `dl` 1, `dt` 7, `dd` 7 di halaman acara dan
  beranda. Diverifikasi ulang di T-31.
- Kalimat "PHP CLI tidak terpasang di mesin ini" **salah waktu ditulis**.
  `bin/periksa-php.sh` sudah ada sejak sebelumnya. Kodenya sekarang lolos
  pemeriksaan sintaks itu.
- Ketujuh temuan sudah tayang dan diperiksa ulang hidup-hidup di kartu T-31.

**Satu hal yang sengaja dibiarkan, jangan dikira terlewat:** target sentuh
overlay menu ponsel tetap 39,6 px. Itu **lolos** WCAG 2.5.8 AA yang minimumnya
24 px; 44 px cuma target ergonomis. Menaikkannya menelan 26 px tinggi panel
sementara blok lanskap cuma punya sisa 36 px.
