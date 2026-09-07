# LAPORAN T-13: audit aksesibilitas halaman artikel, kategori, dan /cerita/

**Agent:** Jim (jim-mtqt9z7n) · **Tanggal:** 7 Sep 2026 · **Kartu:** T-13
**Sifat:** BACA SAJA. Nol tulisan ke repo selain berkas ini, nol deploy, nol perubahan WP REST.

> Ditulis bertahap. Yang diaudit adalah HTML yang benar benar dirender di
> `thejournalingroom.id`, bukan sumber tema.

## 0. Integritas pengukuran, dikerjakan lebih dulu

Kartu memperingatkan Hostinger memotong respons secara acak, dan hari ini terbukti kena HTML
juga (`/kontak/` IncompleteRead 17.754 dari 19.750). Jadi sebelum satu temuan pun dicatat,
tiap halaman diambil **tiga kali** dan dibandingkan hash-nya. Kalau elemen terlihat hilang,
itu harus cacat markup, bukan respons terpotong.

| halaman | URL | byte | 3x hash identik | diakhiri `</html>` |
|---|---|---|---|---|
| Artikel | `/cerita/mulai-journaling-nggak-tahu-mau-nulis-apa/` | 88.881 | ya | ya |
| Indeks | `/cerita/` | 83.079 | ya | ya |
| Kategori | `/cerita/kategori/panduan-journaling/` | 83.486 | ya | ya |

Ketiganya utuh dan stabil. **Semua angka di bawah ini diambil dari respons yang sudah
lolos uji ini**, jadi nol risiko bug hantu.

Catatan URL: tebakan pertama `/category/panduan-journaling/` mengembalikan **404 konsisten**
(3 kali, byte identik, jadi bukan terpotong). Basis kategori situs ini ternyata
`/cerita/kategori/`. Bukan cacat, cuma permalink yang perlu diketahui.

---

## 1. Struktur heading

| halaman | jumlah `h1` | total heading | lompatan level | putusan |
|---|---|---|---|---|
| Artikel | **1** | 6 (1x h1, 5x h2) | nol | **LULUS** |
| `/cerita/` | **1** | 2 | nol | **LULUS** |
| Kategori | **2** | 3 | nol | **GAGAL** |

### Artikel: lulus bersih

Urutan heading di badan artikel benar dan tidak melompat:

```
h1  Mulai journaling waktu nggak tahu mau nulis apa
h2  Kenapa halaman kosong bikin nyerah
h2  Lima menit pertama
h2  Tujuh pemantik yang boleh dicontek
h2  Kalau tetap mentok
h2  Sesering apa harus nulis
```

Satu h1, semua sub-bagian h2, nol h3 yatim. `single.html` yang saya buat memakai
`wp:post-title {"level":1}` sekali saja dan tidak menambah heading statis, jadi tidak
mengulangi pola dua h1 yang diperbaiki Pam di T-1.

### TEMUAN 1 (GAGAL): halaman kategori punya DUA h1

Ini persis cacat yang diperbaiki Pam di T-1, lahir kembali di halaman yang baru ada hari ini.

Terukur di HTML render:

```
h1  Category: Panduan journaling          <- dari wp:query-title
h1  Cerita dari The Journaling Room       <- heading statis
h2  Mulai journaling waktu nggak tahu mau nulis apa
```

**Sebab, dilacak sampai templatnya.** Arsip kategori bawaan WordPress mencari
`category.html`, lalu `archive.html`, lalu `index.html`. Tema tidak punya dua yang pertama
(dipastikan: nol `category.html`, nol `archive.html` di `templates/`), jadi jatuh ke
**`index.html`**.

Dipastikan bukan `taxonomy.html`, karena penanda khasnya nol di halaman render:
`lbl-taksonomi` muncul **0 kali** dan label "Arsip" tidak ada. `taxonomy.html` memang cuma
melayani taksonomi kustom (`format-acara`, `kota`), bukan `category` bawaan.

Dan `index.html` memuat DUA-DUANYA sekaligus:

- `wp:query-title {"type":"archive"}`
- heading statis `<h1>Cerita dari The Journaling Room</h1>`

Komentar di `index.html` menerangkan kenapa heading statis itu ada: `query-title` sengaja
dikosongkan WordPress untuk `is_home()`, jadi di `/cerita/` blok itu nol mencetak dan
heading statis jadi satu-satunya h1. Alasannya benar **untuk `/cerita/`**. Yang tidak
terpikir waktu itu: templat yang sama juga menampung arsip kategori, dan di sana
`query-title` **memang mencetak**. Jadi asumsi "blok ini nol dampak" cuma berlaku separuh.

Ini juga sebabnya cacat ini tidak pernah terlihat sebelum hari ini: nol kategori berisi
tulisan, jadi nol halaman kategori yang bisa dikunjungi.

**Angka sebelum: 2 h1 di `/cerita/kategori/panduan-journaling/`.** Tidak saya perbaiki,
sesuai batasan kartu.

### TEMUAN 2 (kecil, bukan a11y murni): awalan judul arsip masih Inggris

`query-title` mencetak **"Category: Panduan journaling"** di situs berbahasa Indonesia.
Kata "Category" itu string bawaan WordPress. Terlihat pengguna dan sebaris dengan temuan 1,
jadi kalau templat arsipnya nanti dibereskan, sekalian.

---

## 2. Gambar, tautan, dan label (ketiga halaman)

Diukur dari HTML render, bukan dari templat.

| uji | Artikel | `/cerita/` | Kategori | putusan |
|---|---|---|---|---|
| `<img>` tanpa atribut `alt` | 0 dari 3 | 0 dari 3 | 0 dari 3 | LULUS |
| `alt=""` untuk gambar dekoratif | 1 (`tjr-black.png`) | 1 | 1 | LULUS, benar |
| `aria-label` tidak memuat teks terlihat (WCAG 2.5.3) | 0 | 0 | 0 | LULUS |
| Teks tautan samar ("klik di sini", "baca") | 0 | 0 | 0 | LULUS |
| `href="#"` | 0 | 0 | 0 | LULUS |

**Tautan logo diperiksa ulang dan LULUS.** Sapuan pertama menandainya "tautan tanpa teks",
tapi itu positif palsu dari cara saya menyapu: isinya `<img class="custom-logo"
alt="The Journaling Room">`, jadi nama aksesibelnya datang dari `alt` gambar. Bukan cacat.
Dicatat supaya orang berikutnya tidak mengejar bayangan yang sama.

**Daftar fakta `dl/dt/dd`: TIDAK BERLAKU di ketiga halaman.** Nol `<dl>` terhitung, tapi itu
benar, bukan gagal: temuan `dl` aslinya soal panel fakta acara, dan panel itu memang tidak
muncul di halaman artikel, indeks, maupun kategori. Nol pola `p` berkelas yang menyamar jadi
daftar fakta di sini.

---

## 3. Target sentuh, diukur di browser sungguhan

### Catatan metode, karena hampir membuat saya salah

Halaman ini pertama diukur lewat Playwright dan kena **403 anti-bot** ("Checking your
browser"), yaitu proteksi hosting yang sama yang dulu menolak fetcher Magnific. Pengukuran
akhirnya memakai Chrome sungguhan lewat sesi Umar.

Konsekuensinya: **admin bar WordPress ikut terender**, dan sapuan pertama melaporkan 15
elemen di bawah 44px. Empat belas di antaranya adalah `ab-item` milik admin bar, yang tidak
pernah dilihat pengunjung. Angka itu palsu dan dibuang. Semua angka di bawah sudah
**mengecualikan `#wpadminbar`**, jadi yang tersisa benar-benar UI publik.

Viewport pengukuran: 1782px. Elemen interaktif publik: **24**.

| elemen | ukuran terukur | sisi terkecil | ambang WCAG 2.5.8 (24px) | ideal 44px |
|---|---|---|---|---|
| Tautan kategori "PANDUAN JOURNALING" | 152 x **13,5** px | **13,5px** | **GAGAL** | gagal |
| Tombol "HUBUNGI KAMI" | 159,4 x 43 px | 43px | lulus | kurang 1px |
| Tombol "LIHAT JADWAL SESI" | 164,4 x 43 px | 43px | lulus | kurang 1px |

`skip-link` (1x1px) sengaja dikecualikan: itu pola "visually hidden" standar yang membesar
saat menerima fokus, bukan cacat.

### TEMUAN 3 (GAGAL): tautan kategori tingginya 13,5px, dan itu berasal dari templat saya

Ini satu-satunya kegagalan target sentuh di UI publik, dan ini **lahir dari
`templates/single.html` yang saya buat hari ini**, dari blok `wp:post-terms`. Persis jenis
kebocoran yang dikhawatirkan kartu ini: perbaikan T-1 Pam soal target sentuh tidak pernah
diuji terhadap berkas yang belum ada waktu audit itu jalan.

Tingginya 13,5px karena `.lbl` memakai `font-size:10px` tanpa padding vertikal maupun
`min-height`. Lebarnya 152px tidak menolong: WCAG 2.5.8 menilai **sisi terkecil**.

**Angka sebelum: 13,5px. Kurang 10,5px dari ambang minimum 24px, kurang 30,5px dari ideal
44px.** Tidak saya perbaiki, sesuai batasan kartu.

Dua tombol di 43px cuma meleset 1px dari ideal dan **lulus** ambang wajib. Keduanya pola
tombol lama yang dipakai di seluruh situs, bukan bawaan templat saya, jadi bukan regresi.

### Temuan 3 bertahan di lebar mobile

Target sentuh paling penting justru di layar sentuh, jadi diukur ulang di 390px dan 768px.

| lebar | elemen < 24px | elemen < 44px | sisi terkecil |
|---|---|---|---|
| 390px | **1** (tautan kategori, tetap 13,5px) | 5 | 13,5px |
| 768px | **1** (sama) | 4 | 13,5px |
| 1782px | **1** (sama) | 3 | 13,5px |

Tingginya tidak berubah di lebar mana pun, karena ditentukan `font-size:10px` tanpa padding,
bukan oleh layout. Jadi temuan ini nyata di semua breakpoint, bukan artefak satu ukuran.

Elemen tambahan di 390px (77,3x40 dan 50x42) berada di antara 24 dan 44px: **lulus ambang
wajib**, meleset dari ideal. Keduanya kontrol header lama, bukan bawaan templat saya.

### TEMUAN 4 (kecil): artikel tidak punya navigasi pos sebelumnya/berikutnya

Diperiksa karena diminta kartu. `single.html` yang saya buat **tidak memuat blok navigasi
pos sama sekali** (nol `wp-block-post-navigation-link`). Hari ini dampaknya nol karena baru
ada satu artikel, tapi begitu artikel kedua terbit, pembaca yang sampai di akhir tulisan
tidak punya jalan ke tulisan lain selain tombol kembali. Bukan pelanggaran WCAG, ini catatan
kelengkapan supaya tidak terlupa waktu artikel berikutnya tayang.

---

## 4. Kontras teks

Dihitung dari warna computed di browser sungguhan, dengan latar ditelusuri ke atas sampai
menemukan elemen yang benar benar punya warna latar buram.

| kelas | ukuran | warna teks | latar | rasio | AA normal (4,5:1) | AAA (7:1) |
|---|---|---|---|---|---|---|
| `.lbl` (kategori, "Cerita") | 10px/700 | `rgb(117,102,84)` | `rgb(251,247,240)` | **5,20:1** | lulus | gagal |
| `.wp-block-post-date` | 10px/700 | `rgb(117,102,84)` | `rgb(251,247,240)` | **5,20:1** | lulus | gagal |
| `.lead` (ringkasan) | 16,5px | | | **5,92:1** | lulus | gagal |
| Paragraf badan | 17px | `rgb(109,93,76)` | `rgb(251,247,240)` | **5,92:1** | lulus | gagal |
| `h1` dan `h2` | 58px | `rgb(95,29,29)` | `rgb(251,247,240)` | **11,70:1** | lulus | lulus |

**Nol kegagalan kontras.** Angka `.lbl` 5,20:1 cocok persis dengan yang disebut kartu, jadi
nilai itu terkonfirmasi bertahan di halaman baru dan tidak memburuk.

Satu catatan jujur di luar kontras: `.lbl` dan tanggal dipasang di **10px**. Kontrasnya lulus,
tapi 10px itu kecil untuk teks berjalan. Ini keputusan desain yang sudah ada di seluruh situs,
bukan regresi dari kerja saya, dan saya tidak menghitungnya sebagai temuan.

---

## 5. Ringkasan

| # | temuan | halaman | angka sebelum | berat |
|---|---|---|---|---|
| 1 | Dua `h1` di arsip kategori | Kategori | 2 `h1` | **sedang**, regresi pola T-1 |
| 2 | Awalan judul arsip masih Inggris | Kategori | "Category: ..." | kecil |
| 3 | Tautan kategori tingginya 13,5px | Artikel | 13,5px lawan ambang 24px | **sedang**, dari templat saya |
| 4 | Nol navigasi pos sebelumnya/berikutnya | Artikel | tidak ada blok | kecil |

Nol temuan diperbaiki, sesuai batasan kartu.

**Yang LULUS bersih:** satu `h1` di artikel dan `/cerita/`, nol lompatan level heading di
ketiga halaman, semua `alt` benar, nol `aria-label` menyalahi Label in Name, nol teks tautan
samar, nol `href="#"`, dan nol kegagalan kontras.

Dari empat temuan, **satu berasal dari kerja saya hari ini** (nomor 3, dari `wp:post-terms`
di `single.html`). Nomor 1 dan 2 adalah cacat templat lama yang baru bisa terlihat karena
kategori berisi tulisan untuk pertama kalinya.

---

## 6. Yang TIDAK sempat saya uji

Disebut supaya tidak ada yang mengira halaman ini sudah bersih sepenuhnya.

- **Navigasi papan ketik**: urutan fokus, jebakan fokus, dan apakah cincin fokus terlihat di
  tiap kontrol. Nol diuji.
- **Perilaku `skip-link` saat difokus.** Saya kecualikan dari hitungan target sentuh karena
  polanya standar, tapi saya **tidak memverifikasi** dia benar benar tampil dan berfungsi
  saat di-tab.
- **Pembaca layar sungguhan** (VoiceOver atau NVDA). Semua putusan di sini dari markup dan
  nilai computed, bukan dari mendengarkan hasilnya.
- **Zoom 200 persen dan `prefers-reduced-motion`.**
- **Kontras pada keadaan hover, fokus, dan visited.** Yang diukur cuma keadaan diam.
- **Halaman kategori pada taksonomi lain** (`format-acara`, `kota`). Keduanya memakai
  `taxonomy.html`, templat yang berbeda dari yang bercacat di temuan 1, jadi belum tentu
  ikut kena, tapi juga belum saya periksa.

Satu catatan metode yang layak diwariskan: pengukuran a11y di situs ini **harus lewat Chrome
sungguhan**, karena Playwright kena 403 anti-bot, dan **harus mengecualikan `#wpadminbar`**,
karena sesi Umar login sebagai admin dan admin bar sendirian menyumbang 14 positif palsu
target sentuh.
