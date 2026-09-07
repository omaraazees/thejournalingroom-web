# LAPORAN T-17: empat butir yang saya sendiri nyatakan belum diuji

**Agent:** Jim (jim-mtqt9z7n) · **Tanggal:** 7 Sep 2026 · **Kartu:** T-17
**Sifat:** AUDIT. Temuan dilaporkan dengan angka, **nol diperbaiki** di kartu ini.
Nol deploy. Nol em dash.

Diuji terhadap situs yang sudah membawa kiriman `ae3ffcc`.

## 0. Catatan pemotongan respons

CDN sedang dimatikan sebagai uji diagnostik. Tiap halaman tetap saya ambil **dua kali** dan
bandingkan hash. Hasil: **nol pemotongan di seluruh pengukuran kartu ini**, semua pasangan
byte-identik. Tidak ada berita buruk untuk dilaporkan di sisi itu.

---

## Butir 2: arsip tag, tanggal, dan penulis

Saya dahulukan ini karena kalau `archive.html` buatan saya merusak salah satunya, itu
regresi dari kiriman hari ini.

### Koreksi terhadap laporan saya sendiri di T-15

Di T-15 saya menulis "nol yang bisa dikunjungi sekarang". **Itu keliru**, dan penyebabnya
saya menebak URL-nya, bukan mencarinya.

Tebakan `/2026/09/`, `/author/<slug>/`, dan `/tag/<slug>/` semuanya **404**. Yang benar:
karena permalink tulisan berbasis `/cerita/`, seluruh arsip tulisan ikut bersarang di bawah
`/cerita/`. Ditemukan dengan memakai bentuk query-string (`?m=`, `?author=`, `?cat=`) lalu
mengikuti redirect kanonik WordPress, yang memang menunjuk ke URL benarnya.

| jenis arsip | URL yang benar | status |
|---|---|---|
| Tanggal | `/cerita/2026/09/` | 200 |
| Penulis | `/cerita/author/omar-aazeesgmail-com/` | 200 |
| Kategori | `/cerita/kategori/panduan-journaling/` | 200 |

### `archive.html` terbukti benar di tiga dari empat jenis arsip

| arsip | h1 | judul h1 | loop `daftar-tulisan` | byte identik 2x |
|---|---|---|---|---|
| Tanggal | **1** | "September 2026" | ada | ya (80.867) |
| Penulis | **1** | "omar.aazees@gmail.com" | ada | ya (81.233) |
| Kategori | **1** | "Panduan journaling" | ada | ya (82.360) |

**Nol regresi. Ketiganya satu h1**, yaitu tujuan `archive.html`. Alasan saya memilih
`archive.html` ketimbang `category.html` di T-15 sekarang terbukti di HTML terrender, bukan
cuma di atas kertas: dua jenis arsip di luar kategori memang ikut tertolong.

**Tag: tidak bisa diuji, dan saya tidak memaksakannya.** Situs punya **nol tag**
(`wp/v2/tags` mengembalikan array kosong), jadi nol URL tag yang bisa dikunjungi.
`/tag/apa-saja/` 404 karena termnya tidak ada, bukan karena templatnya salah, jadi itu bukan
uji yang sah. Saya bisa membuat tag sementara seperti trik templat di T-15, tapi itu menulis
data isi ke situs hidup, bukan sekadar meminjam mekanisme render, jadi saya tahan. Jalur
templatnya sama persis dengan tiga yang sudah terbukti.

### TEMUAN 1 (SEDANG, privasi): arsip penulis memajang alamat email Umar

`h1` halaman `/cerita/author/omar-aazeesgmail-com/` berbunyi **`omar.aazees@gmail.com`**.
Alamat emailnya juga ada di dalam URL.

Yang memperberat: **nol `<meta name="robots">` di halaman itu**, jadi tidak ada `noindex`,
dan halaman itu terbuka untuk dirayapi.

Ada preseden jelas bahwa ini bukan selera saya: commit `106aa3c` "Footer: jangan tulis nomor
WhatsApp secara terbuka", dan `f749450` yang sengaja mengeluarkan arsip penulis dari sitemap.
Niat menyembunyikan arsip penulis sudah pernah ada, tapi yang dilakukan waktu itu cuma
mencabutnya dari sitemap. **Mencabut dari sitemap bukan menutup halaman**: URL-nya tetap
hidup, tetap 200, dan tetap boleh diindeks.

Angka sebelum: **1 halaman publik, 200, tanpa noindex, memuat alamat email di h1 dan di URL.**

### TEMUAN 2 (SEDANG, SEO): arsip tanggal dan penulis nol judul dan nol canonical

| arsip | `<title>` | `<link rel=canonical>` |
|---|---|---|
| Kategori | "Panduan journaling \| The Journaling Room" | ada, benar |
| Tanggal | **"The Journaling Room"** (generik) | **TIDAK ADA** |
| Penulis | **"The Journaling Room"** (generik) | **TIDAK ADA** |

Sebabnya bisa ditunjuk: `wordpress/theme-v5/inc/seo.php` baris 234 mencabangkan arsip dengan
`is_tax( array('format-acara','kota') ) || is_category() || is_tag()`. Arsip **tanggal** dan
**penulis** tidak masuk cabang itu, jadi keduanya jatuh tanpa judul khusus dan tanpa canonical.

Ini tidak berhubungan dengan `archive.html` buatan saya, dan sudah begitu sebelum hari ini.
Tapi baru terlihat sekarang karena sebelum ada artikel, nol arsip tulisan yang bisa dibuka.
Pola yang sama untuk ketiga kalinya: cacat lama tak terlihat karena datanya kosong.

---

## Butir 1: navigasi papan ketik dan cincin fokus

### Kabar baiknya: dugaan lantai soal cincin fokus TIDAK berlaku di situs ini

Kartu menyebut catatan lama bahwa produk live sejenis hampir nol punya cincin fokus. Di sini
kebalikannya. Tema sudah punya cincin fokus global yang benar, dan bahkan sudah pernah
melewati satu putaran perbaikan yang terdokumentasi di `style.css` baris 55 sampai 63.

```
:focus-visible{outline:2px solid var(--wp--preset--color--burgundy);
               outline-offset:3px;border-radius:4px}
```

Komentar di atasnya bahkan mencatat pelajaran yang pernah didapat: `:focus` telanjang di
`theme.json` dulu membuat kotak burgundy muncul saat klik mouse, dan format `theme.json`
tidak menerima `:focus-visible`, jadi cincin fokus memang tempatnya di `style.css`.

### Hampir saja saya melaporkan temuan palsu, dua kali

Ini perlu ditulis karena metodenya yang salah, bukan situsnya.

**Positif palsu pertama.** Sapuan awal saya memakai `element.focus()` dari JavaScript lalu
membaca `outline-style`. Hasilnya **21 dari 21 elemen "tanpa cincin fokus"**, yang kalau
dilaporkan akan jadi temuan berat dan salah total. Sebabnya: `focus()` programatik **tidak
memicu `:focus-visible`** di Chrome untuk tautan, jadi yang saya baca cuma keadaan diam.
`el.focus({focusVisible:true})` juga tidak menolong, `matches(':focus-visible')` tetap
`false`.

Yang membuktikan: **penekanan Tab sungguhan** lewat papan ketik. Begitu Tab betulan ditekan,
`matches(':focus-visible')` jadi `true` dan `outline-style` jadi `solid 2px rgb(95, 29, 29)`.

**Positif palsu kedua.** Saya menghitung kontras cincin burgundy lawan latar tombol gelap
`rgb(36, 28, 20)` dan dapat **1,34:1**, jauh di bawah ambang 3:1 WCAG 1.4.11. Itu tampak
seperti temuan bagus. Salah juga: `outline` digambar **di luar** kotak elemen, dan
`outline-offset` di sini **3px**, jadi cincinnya duduk di atas kertas krem, bukan di atas
tombol. Dipastikan dengan melihat gambarnya, bukan dengan menghitung: cincin burgundy
terlihat jelas mengelilingi tombol gelap.

### Angka

| yang diuji | hasil | ambang | putusan |
|---|---|---|---|
| Cincin fokus ada saat Tab sungguhan | `solid 2px rgb(95,29,29)`, offset 3px | harus terlihat | **LULUS** |
| Kontras cincin lawan kertas `rgb(251,247,240)` | **11,70:1** | 3:1 (WCAG 1.4.11) | **LULUS**, 3,9x ambang |
| `tabindex` positif di mana pun | **0** | 0 | **LULUS**, urutan tab = urutan DOM |
| Urutan tab | header, isi, footer | logis | **LULUS** |
| Tautan lewati-ke-konten ada | ya, `#wp--skip-link--target` | ada | **LULUS** |
| Skip link urutan pertama (UI publik) | **indeks 0 dari 24** | pertama | **LULUS** |
| Skip link muncul saat difokus | **1x1px jadi 150,7 x 49,5px** | harus terlihat | **LULUS** |
| Kontras teks skip link | 8,39:1 | 4,5:1 | **LULUS** |
| Sasaran skip link ada | ya, `<main>` | ada | **LULUS** |

Catatan: pengamatan "Tab pertama mendarat di logo, bukan skip link" **bukan cacat**. Sesi
Umar login sebagai admin, jadi admin bar mendahului di DOM. Di antara elemen publik, skip
link tetap indeks 0.

### TEMUAN 3 (kecil, i18n): label menu mobile masih Inggris

Tombol menu di layar sempit memakai `aria-label` **"Open menu"** dan **"Close menu"** di
situs berbahasa Indonesia. Sekelas dengan awalan "Category:" di temuan sebelumnya. Ukuran
tombolnya sendiri sehat: buka 50x42px, tutup 44x44px, keduanya lewat ambang 24px.

### TEMUAN 4 (perlu konfirmasi): panel menu mobile nol `aria-modal` dan nol `role`

Saat panel menu dibuka, wadahnya mendapat kelas `is-menu-open` tapi saya membaca
`aria-modal` **null** dan `role` **null**. Kalau benar, pemakai pembaca layar tidak
diberitahu bahwa panel itu modal, dan pengurungan fokus tidak dijamin.

**Saya beri label perlu konfirmasi, bukan temuan pasti.** Menunya saya buka dengan `click()`
programatik di dalam iframe, dan skrip navigasi WordPress bisa berperilaku beda di luar
kondisi itu. Uji jebakan fokus yang sesungguhnya butuh penekanan Tab berulang di viewport
sempit sungguhan, dan **itu tidak saya lakukan**. Enam tautan di dalam panel masing masing
setinggi 40px, lewat ambang 24px tapi kurang 4px dari ideal 44px.

---

## Butir 3: tata letak arsip kategori di 390px

Diukur dengan menyuntik halaman hidup ke iframe selebar 390px, teknik yang sama seperti T-13.
Catatan: `resize_window` tidak mengubah viewport di lingkungan ini (`innerWidth` tetap 1782),
jadi jalur iframe yang dipakai, dan itu memang yang terbukti bekerja.

| yang diuji | hasil | putusan |
|---|---|---|
| Gulir horizontal | tidak ada (`scrollWidth` 375 = `clientWidth` 375) | **LULUS** |
| Elemen meluber ke luar viewport | **0** | **LULUS** |
| Jumlah `h1` | **1** | **LULUS** |
| Ukuran `h1` | 58px di desktop turun ke **31,56px** | **LULUS**, clamp bekerja |
| Judul terpotong | tidak | **LULUS** |
| Elemen interaktif di bawah 24px | **0** | **LULUS** |

Bersih. Nol temuan di butir ini.

---

## Butir 4: navigasi pos dengan tetangga sungguhan

**Tidak bisa diuji, dan saya tidak memaksakannya.**

Situs masih punya **satu** artikel, jadi nol tetangga untuk `wp:post-navigation-link`.
Kartu melarang menerbitkan artikel kedua demi menguji, dan saya setuju itu batas yang benar.

Yang sudah terbukti dan tetap berlaku dari T-15, lewat simulasi DOM:

| kondisi | `display` | tinggi wadah | tinggi tautan |
|---|---|---|---|
| Nav kosong (keadaan sekarang) | `none` | 0px | tidak berlaku |
| Nav berisi (nanti) | `block` | 69,9px | 52,9px |

Yang **belum** terbukti dan cuma bisa dibuktikan setelah artikel kedua terbit: bentuk
aslinya di halaman, apakah judul tetangganya terpotong di layar sempit, dan apakah tata
letak `space-between` berperilaku benar saat cuma satu sisi terisi (artikel pertama dan
terakhir dalam urutan hanya punya satu tetangga). Yang terakhir itu kasus nyata yang akan
langsung muncul begitu ada artikel kedua, jadi layak diperiksa saat itu.

Saya konfirmasi halaman artikel hari ini **tidak menampilkan garis menggantung**: penanda
`nav-tulisan` nol muncul di HTML terrender, jadi aturan sembunyi bekerja di produksi.

---

## Ringkasan temuan

| # | temuan | berat | angka |
|---|---|---|---|
| 1 | Arsip penulis memajang alamat email Umar, tanpa `noindex` | **sedang** | 1 URL publik, 200, email di `h1` dan di URL |
| 2 | Arsip tanggal dan penulis nol judul khusus dan nol canonical | **sedang** | 2 arsip, judul generik "The Journaling Room" |
| 3 | Label menu mobile masih Inggris | kecil | "Open menu", "Close menu" |
| 4 | Panel menu mobile nol `aria-modal` dan nol `role` | kecil, **perlu konfirmasi** | keduanya null |

Nol temuan diperbaiki, sesuai batasan kartu.

**Yang LULUS bersih dan tidak perlu dikerjakan:** cincin fokus dan seluruh perilaku papan
ketik, `archive.html` di tiga jenis arsip, dan tata letak kategori di 390px.

## Yang tidak diuji

- **Jebakan fokus sungguhan di panel menu mobile.** Butuh Tab berulang di viewport sempit
  nyata. Ini yang paling layak jadi kartu lanjutan, karena temuan 4 menggantung tanpanya.
- **Arsip tag.** Situs nol tag, dan membuat tag berarti menulis data isi ke situs hidup.
- Urutan tab di halaman selain artikel dan kategori.
- Pembaca layar sungguhan.
- Zoom 200 persen dan `prefers-reduced-motion`.
