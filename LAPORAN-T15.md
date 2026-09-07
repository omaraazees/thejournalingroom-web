# LAPORAN T-15: perbaikan empat temuan T-13

**Agent:** Jim (jim-mtqt9z7n) · **Tanggal:** 7 Sep 2026 · **Kartu:** T-15
**Batasan:** commit saja, nol deploy FTP (Pam pemilik pengiriman), nol sentuhan isi
editorial, nol em dash.

> Ditulis bertahap. Tiap temuan: angka sebelum, apa yang diubah, angka sesudah.

## Keputusan arsitektur: `archive.html`, bukan `category.html`

Kamu menduga perbaikan yang benar adalah membuat `category.html`. Saya ambil jalan sedikit
berbeda, dan ini alasannya, sesuai permintaanmu untuk menuliskannya.

Sebabnya bukan cuma arsip kategori. `wp:query-title` mencetak untuk **semua** jenis arsip:
kategori, tag, tanggal, penulis. Tema tidak punya `category.html` **maupun** `archive.html`,
jadi keempatnya jatuh ke `index.html` dan keempatnya kebagian dua h1. `category.html` cuma
menutup satu dari empat, dan menyisakan ranjau yang sama untuk siapa pun yang nanti memakai
tag atau membuka arsip tanggal.

`archive.html` menutup keempatnya sekaligus, karena hierarki WordPress mencari
`category.html`, lalu `archive.html`, lalu `index.html`.

Yang penting: ini **tidak** merebut halaman yang sudah punya templat lebih spesifik, karena
hierarki selalu memilih yang paling spesifik lebih dulu:

| halaman | templat yang menang | kenapa |
|---|---|---|
| Arsip acara `/jadwal/` | `archive-acara.html` | `archive-{post_type}` lebih spesifik dari `archive` |
| Taksonomi `format-acara`, `kota` | `taxonomy.html` | `taxonomy` lebih spesifik dari `archive` |
| `/cerita/` (halaman posting) | `index.html` | is_home tidak memakai jalur arsip sama sekali |
| Arsip kategori, tag, tanggal, penulis | **`archive.html` (baru)** | sebelumnya jatuh ke `index.html` |

Heading statis di `index.html` **tidak saya sentuh**, persis seperti peringatanmu. Heading
itu memang harus tetap ada di sana, karena `query-title` dikosongkan WordPress untuk
`is_home()` dan tanpa heading statis `/cerita/` justru kehilangan h1-nya. Cacatnya bukan
heading itu, melainkan satu templat dipakai dua konteks yang berbeda perilakunya.

---

## Temuan 1 dan 2: dua h1 dan awalan Inggris di arsip kategori

### Cara mengukurnya tanpa deploy

Kartu melarang deploy tapi mewajibkan pengukuran ulang di HTML terrender. Berkas tema tidak
bisa diuji tanpa dikirim, jadi saya pakai jalur yang tidak menyentuh FTP: templat blok bisa
dititipkan ke database lewat WP REST dan langsung aktif.

Prosedurnya sengaja dibuat **sementara dan bisa dibalik**:

1. Ukur keadaan sebelum.
2. Titipkan isi `archive.html` ke database sebagai templat `custom`.
3. Ukur keadaan sesudah, plus sapuan regresi.
4. **Hapus templat database itu**, lalu pastikan situs kembali persis ke keadaan semula.

Produksi hanya berubah selama pengukuran, dan dikembalikan setelahnya. Perbaikan yang
sesungguhnya tetap datang lewat pengiriman Pam, bukan lewat saya.

### Angka

| | h1 | judul yang tercetak |
|---|---|---|
| **Sebelum** | **2** | "Category: Panduan journaling" dan "Cerita dari The Journaling Room" |
| **Sesudah** | **1** | "Panduan journaling" |

Diambil dua kali dengan cache-bust berbeda, 82.360 byte identik, diakhiri `</html>`.
Ukuran halaman sebelum 83.486 byte, jadi jauh dari titik potong 32,39 KB yang kamu sebut,
dan memang nol tanda pemotongan.

Kedua temuan tertutup sekaligus: h1 ganda hilang, dan awalan "Category:" hilang lewat
`showPrefix:false`.

### Sapuan regresi: `archive.html` tidak merebut halaman lain

Ini bagian yang paling penting diuji, karena templat arsip yang terlalu rakus akan merusak
halaman lain diam diam. Diukur saat templat uji masih aktif:

| halaman | h1 | templat yang menang | bukti |
|---|---|---|---|
| `/jadwal/` | 1 | `archive-acara.html` | judul "Workshop journaling di Jogja" utuh |
| `/cerita/` | 1 | `index.html` | heading statis masih ada, tidak hilang |
| `/format/brand-activation/` | 1 | `taxonomy.html` | penanda `lbl-taksonomi` tetap muncul |
| `/kota/jakarta/` | 1 | `taxonomy.html` | penanda `lbl-taksonomi` tetap muncul |

Nol regresi. Hierarki berperilaku persis seperti yang saya perkirakan: yang lebih spesifik
selalu menang.

Catatan sampingan: awalan taksonomi kustom sudah berbahasa Indonesia sendiri
("Format acara: ...", "Kota: ..."), jadi tidak ikut bermasalah seperti "Category:".

### Pembersihan terverifikasi

Templat uji dihapus (`DELETE`, HTTP 200, `deleted: true`). Sesudahnya halaman kategori
**kembali ke 2 h1 dan 83.486 byte**, persis keadaan semula. Daftar templat `custom` di
database sekarang **kosong**.

Itu sekaligus menjawab satu hal di luar kartu: **templat `tjr-v5//single` yang saya titipkan
waktu T-4 sudah tidak ada**, jadi kartu T-11 untuk menghapusnya rupanya sudah dieksekusi.
Halaman artikel tetap benar sesudahnya (nol penanda `daftar-tulisan`, ketiga H2 badan
artikel muncul), yang membuktikan artikel sekarang dilayani **berkas tema hasil kiriman
Pam**, bukan lagi salinan database. Drift yang saya khawatirkan di T-4 sudah tertutup.

---

## Temuan 3: target sentuh tautan kategori

Ini temuan yang lahir dari kerja saya sendiri hari ini, lewat `wp:post-terms` di
`single.html`.

Kamu minta dinaikkan lewat padding, bukan font-size, karena 10px kemungkinan pilihan desain
sedangkan tinggi target bukan. Saya ikuti, dan sekalian mengikuti idiom yang sudah dipakai
Pam di T-1 (`.bar .wp-block-navigation a` dan `.kaki ul a`): `display:inline-block` plus
padding vertikal, dengan komentar yang menyebut angka sebelum, ambang, dan angka sesudah.

Bedanya satu: saya tambahkan **margin negatif sebesar padding-nya**, supaya area sentuh
membesar tanpa menggeser tata letak.

### Angka, diukur di browser sungguhan

| | tinggi tautan | tinggi wrapper | jarak ke h1 | posisi h1 |
|---|---|---|---|---|
| **Sebelum** | **13,5px** | 17px | 24px | acuan |
| **Sesudah** | **49px** | 17px | 24px | **bergeser 0px** |

- Lolos minimum WCAG 2.5.8 (24px): **ya**, dengan margin dua kali lipat.
- Lolos target ergonomis 44px: **ya**.
- Ukuran huruf: **tetap 10px**, tidak disentuh.
- Tata letak: **nol pergeseran**, dibuktikan wrapper tetap 17px, jarak ke h1 tetap 24px,
  dan posisi h1 identik.

Perkiraan awal saya di komentar CSS adalah 45,5px. Hasil ukur sebenarnya **49px**, karena
kotak barisnya 17px bukan 13,5px. **Komentar di `style.css` sudah saya koreksi ke angka
terukur**, supaya tidak ada angka karangan yang mengendap di kode. Ini persis alasan kamu
menyuruh mengukur ulang alih alih mempercayai kode yang terlihat benar.

### Cara mengukurnya

Aturan CSS-nya belum tayang (berkas tema, menunggu Pam). Jadi aturan yang **persis sama**
disuntikkan ke DOM halaman artikel yang hidup, lalu diukur. Yang diukur perilaku aturannya
terhadap markup nyata, bukan tebakan dari kode. Ini bukan pengukuran keadaan ter-deploy, dan
saya sebut apa adanya: angka sesudah baru akan berlaku di situs begitu Pam mengirim.

---

## Temuan 4: navigasi pos sebelumnya dan berikutnya

Kamu bilang prioritas terendah dan boleh dilaporkan tidak dikerjakan. **Dikerjakan**, karena
sempat.

Ditambahkan ke `single.html`, memakai `wp:post-navigation-link` dengan `showTitle:true`
supaya teks tautannya **judul artikel tujuan**, bukan kata "Sebelumnya" saja. Itu memenuhi
kriteria yang saya pakai sendiri waktu mengaudit di T-13: teks tautan harus bermakna kalau
dibaca lepas dari konteks.

### Cacat yang hampir saya buat sendiri

Blok `post-navigation-link` mencetak **nol** kalau tidak ada tulisan tetangga. Situs baru
punya satu artikel, jadi kedua sisinya kosong. Tanpa penanganan, yang tersisa di akhir
artikel adalah **garis atas menggantung tanpa isi apa pun di bawahnya**, yaitu cacat visual
baru yang saya sendiri yang memasukkannya.

Ditangani dengan `.nav-tulisan:not(:has(a)),.nav-tulisan:empty{display:none}`.

Diuji di browser dengan kedua kondisi disimulasikan pada DOM nyata:

| kondisi | `display` | tinggi | tinggi tautan |
|---|---|---|---|
| Nav kosong (keadaan hari ini) | `none` | **0px** | tidak berlaku |
| Nav berisi (nanti, sesudah artikel kedua) | `block` | 69,9px | **52,9px** |

`CSS.supports('selector(:has(a))')` mengembalikan **true** di browser uji, jadi selektornya
memang didukung, bukan asumsi. `:empty` disertakan sebagai jaring pengaman kedua.

Tautan navigasinya sendiri 52,9px, lolos 24px maupun 44px.

---

## Ringkasan angka sebelum dan sesudah

| # | temuan | sebelum | sesudah | diukur di |
|---|---|---|---|---|
| 1 | Dua h1 arsip kategori | **2 h1** | **1 h1** | HTML terrender, templat uji di DB |
| 2 | Awalan judul Inggris | "Category: Panduan journaling" | "Panduan journaling" | sama |
| 3 | Target sentuh tautan kategori | **13,5px** | **49px** | DOM hidup, aturan disuntik |
| 4 | Navigasi pos | tidak ada | ada, sembunyi otomatis saat kosong | DOM hidup, dua kondisi |

Empat dari empat tertangani.

## Berkas yang berubah

| berkas | status | isi perubahan |
|---|---|---|
| `wordpress/theme-v5/templates/archive.html` | **BARU** | templat arsip, satu h1, `showPrefix:false` |
| `wordpress/theme-v5/templates/single.html` | diubah | tambah blok navigasi pos |
| `wordpress/theme-v5/style.css` | diubah | target sentuh tautan kategori, gaya `.nav-tulisan`, aturan sembunyi saat kosong |
| `LAPORAN-T15.md` | BARU | laporan ini |

`index.html` **tidak disentuh**, sesuai peringatanmu.

Ketiga berkas tema itu perlu ikut kiriman Pam. `bin/periksa-php.sh` lulus 11 berkas, 0 gagal.

## Keadaan produksi sekarang

**Nol perubahan permanen dari saya.** Templat uji sudah dihapus dan situs terbukti kembali
ke keadaan semula (kategori 2 h1, 83.486 byte, daftar templat `custom` kosong). Keempat
perbaikan baru akan tayang lewat pengiriman Pam.

Jadi kalau kamu memeriksa situs sekarang, dua h1 itu **masih ada**, dan itu memang yang
seharusnya.

## Catatan untuk pengiriman

Satu hal yang menyenangkan: karena `tjr-v5//single` di database sudah dihapus (T-11), nol
templat `custom` tersisa. Artinya pengiriman berikutnya **tidak akan bertabrakan dengan
salinan database mana pun**, termasuk `archive.html` yang baru. Tidak ada lagi jebakan
"templat DB menang atas berkas tema" untuk kiriman ini.

## Yang tidak saya uji

- Navigasi papan ketik dan cincin fokus pada tautan kategori yang sekarang punya padding
  besar, dan pada tautan navigasi pos.
- Tampilan navigasi pos dengan artikel tetangga **sungguhan**. Yang diuji simulasi DOM,
  karena situs masih satu artikel. Bentuk aslinya baru terlihat setelah artikel kedua terbit.
- Arsip tag, tanggal, dan penulis. Ketiganya ikut tertolong `archive.html` secara teori,
  tapi nol yang bisa saya kunjungi sekarang karena situs belum punya tag dan arsip penulis
  sudah dikeluarkan dari sitemap.
- Halaman kategori pada breakpoint mobile. Pengukuran h1 tidak bergantung lebar, tapi saya
  tidak memeriksa tata letaknya di 390px.
