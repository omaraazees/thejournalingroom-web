# LAPORAN A2 · WordPress Theme

Lane A2 selesai. Semua file ada di `journaling-room-web/wordpress/theme/`, dan sudah dibungkus
jadi `journaling-room-web/wordpress/dist/tjr-flat.zip`. Nol file di lane lain disentuh.

**Zip-nya sengaja flat, tanpa folder induk.** Isinya langsung `style.css`, `theme.json`,
`functions.php`, dan seterusnya di akar. Bentuk ini untuk diekstrak menimpa folder tema yang sudah
ada lewat File Manager Hostinger atau FTP. Kalau mau lewat Appearance > Themes > Add New > Upload,
WordPress justru butuh zip yang isinya dibungkus satu folder, jadi pakai `tjr-child.zip` untuk
jalur itu.

## Yang dibuat

| File | Isi |
|---|---|
| `style.css` | Header child theme GeneratePress. Sengaja hampir kosong, isinya cuma metadata |
| `theme.json` | Versi 3. Terjemahan penuh `tokens.css` jadi setting resmi WordPress |
| `functions.php` | CPT `acara`, taksonomi `format-acara` dan `kota`, pemuatan font, kategori pattern |
| `patterns/` | 11 file pattern |
| `parts/` | `header.html` dan `footer.html`, template part Site Editor |
| `templates/` | 8 template blok |
| `assets/tjr.css` | CSS yang tidak bisa diwakili theme.json |
| `assets/placeholder.svg` | Placeholder foto memakai empat warna palet, bukan warna baru |

Total 26 file.

## Terjemahan tokens.css ke theme.json

| Di `tokens.css` | Jadi apa di WordPress | Dipanggil dengan |
|---|---|---|
| 9 warna dasar + 4 varian kontras | `color.palette`, 13 swatch bernama Indonesia | `var(--wp--preset--color--burgundy)` |
| 3 variabel font | `typography.fontFamilies` slug `display`, `body`, `script` | `var(--wp--preset--font-family--display)` |
| `--step--1` sampai `--step-6` | `typography.fontSizes`, 8 langkah | `var(--wp--preset--font-size--xxl)` |
| `--sp-1` sampai `--sp-8` | `spacing.spacingSizes`, 8 langkah, slug 1 sampai 8 | `var(--wp--preset--spacing--5)` |
| `--wide` 1160, `--content` 720 | `layout.wideSize` dan `layout.contentSize` | otomatis dipakai blok |
| `--r-card`, `--r-photo` | `settings.custom.radius.*` | `var(--wp--custom--radius--card)` |
| `--line` | `settings.custom.line` | `var(--wp--custom--line)` |
| `--shadow` | `shadow.presets` slug `card` dan `lift` | `var(--wp--preset--shadow--lift)` |

Empat ukuran huruf terbesar diberi `fluid`, jadi mengecil sendiri di layar kecil tanpa perlu
media query. Ini menggantikan `clamp()` yang ada di prototipe.

## Varian aman kontras

`tokens.css` menambah empat token: tiga pengganti warna teks, satu untuk garis komponen.
Keempatnya masuk `color.palette` dengan nama yang menyebut perannya, jadi di editor kelihatan
mana yang boleh dipakai untuk teks dan mana yang cuma untuk isian.

| Token | Hex | Nama di editor | Peran |
|---|---|---|---|
| `rose-text` | `#9F534E` | Rose teks (aman kontras) | teks, pengganti `rose-deep` |
| `sage-text` | `#616D52` | Sage teks (aman kontras) | teks, pengganti `sage` |
| `kraft-text` | `#856245` | Kraft teks (aman kontras) | teks, pengganti `kraft` |
| `kraft-line` | `#A87D59` | Kraft garis komponen | garis kontrol, syarat non-teks 3:1 |

Warna aslinya tetap ada dan tetap dipakai untuk isian, latar, dan garis dekoratif. Palet naik
dari 9 jadi 13 swatch.

### Rasio yang saya hitung ulang

Diukur di atas `--paper-deep` `#F1E8DC`, ground paling gelap yang dipakai section.

| Token | vs paper-deep | vs paper | Ambang |
|---|---|---|---|
| `rose-deep` | 2.34:1 | 2.64:1 | gagal 4.5:1 |
| `rose-text` | **4.53:1** | 5.10:1 | lulus |
| `sage` | 2.26:1 | 2.55:1 | gagal 4.5:1 |
| `sage-text` | **4.54:1** | 5.11:1 | lulus |
| `kraft` | 2.61:1 | 2.94:1 | gagal 4.5:1 |
| `kraft-text` | **4.52:1** | 5.09:1 | lulus |
| `kraft-line` | **3.02:1** | 3.40:1 | lulus 3:1 non-teks |

Satu catatan angka. Komentar di `tokens.css` menulis nilai sebelum sebagai 2.64, 2.55, dan 2.94
"di atas --paper-deep". Angka itu sebenarnya diukur di atas `--paper`. Di atas `--paper-deep`
warna lamanya lebih buruk lagi: 2.34, 2.26, dan 2.61. Jadi alasan menggantinya lebih kuat dari
yang tertulis, bukan lebih lemah. `tokens.css` tidak saya ubah karena file itu read only untuk
lane ini.

### Yang diganti di tema

| Tempat | Sebelum | Sesudah | Kenapa |
|---|---|---|---|
| `.script` di hero, pattern `tjr/hero-booking` | `rose-deep` | `rose-text` | teks 20px |
| `.hero h1 em`, kata miring di judul | `rose-deep` | `rose-text` | 2.34:1 gagal bahkan untuk ambang teks besar 3:1 |
| `.tst .stars`, bintang testimoni | `rose-deep` | `rose-text` | dirender sebagai karakter teks 13px |
| `.tag`, label format | `#5d6b4d` lalu `color-mix` | `sage-text` | teks 11px, sekaligus menghapus hex mentah terakhir |
| `.faq summary::after`, penanda buka tutup | `kraft` | `kraft-text` | karakter + dan - yang menandakan keadaan |
| Garis `.btn-ghost` | `kraft` | `kraft-line` | garis kontrol interaktif, syarat 3:1. Sama dengan yang dilakukan `tokens.css` |

### Yang sengaja tidak diganti

| Tempat | Warna | Alasan |
|---|---|---|
| Selotip di kartu sesi | `rose` dan `kraft` transparan | murni hiasan |
| Isian bar slot | `rose-deep` | isian grafis, dan jumlahnya sudah ditulis sebagai teks di bawahnya plus `aria-label` |
| Kotak ikon isi kit | `sage` 28 persen | hiasan, tidak membawa arti |
| Tanda bintang di ticker | `rose` | seluruh ticker `aria-hidden="true"` |
| `.script` di penutup | `rose` di atas `ink` | 7.09:1, sudah lolos |

### Satu temuan yang belum ditangani

`--line`, garis pembatas bawaan `tokens.css`, nilainya `rgba(176,137,104,.3)`. Di atas `paper`
warnanya jadi `#E4D5C7` dengan kontras **1.33:1**, jauh di bawah 3:1. Dipakai di `.card`, `.kv`,
`.ev`, `.chip`, dan pembatas FAQ.

Tidak saya ubah, karena `tokens.css` tidak mengubahnya dan permintaannya spesifik ke `rose-deep`,
`sage`, dan `kraft`. Yang perlu diputuskan pemilik cuma satu tempat: **`.chip`**, karena itu
kontrol filter yang bisa diklik, dan batas kontrol termasuk yang kena syarat 3:1. Sisanya
(`.card`, `.kv`, `.ev`, FAQ) cuma pembatas visual antar baris, bukan batas kontrol, jadi 1.33:1
di situ tidak melanggar. Kalau mau ditutup, ganti border `.chip` saja jadi `var(--kraft-line)`,
jangan semua pembatas, supaya garis dekoratif tidak ikut jadi berat.

## Template dan template part

### Dua template part

**`parts/header.html`** menempel di atas layar (`position: sticky`, `top: 0`), latar `paper` 90
persen dengan blur, garis bawah tipis. Tiga hal berjajar: nama situs pakai huruf script di kiri,
menu di tengah, dan tombol **Ambil slot** burgundy di kanan. Menunya blok `core/navigation`, jadi
Caca dan Dhanty mengaturnya lewat menu WordPress biasa, dan di layar kecil otomatis jadi overlay.

**`parts/footer.html`** latar `ink` gelap, teks `paper-deep`, empat kolom: nama plus deskripsi
singkat, Acara, Tentang, Kontak. Di bawahnya satu baris penutup dengan garis pemisah tipis. Link
WhatsApp dan Instagram sudah terisi nomor dan handle asli dari `brand-brief.md`.

### Delapan template

| File | Untuk apa |
|---|---|
| `index.html` | Cadangan terakhir sekaligus indeks blog. Diberi H1 "Cerita" |
| `front-page.html` | Beranda. Isinya satu blok pattern `tjr/beranda` |
| `page.html` | Halaman statis: Tentang, Kolaborasi, Kontak |
| `single.html` | Satu artikel Cerita, lebar baca 720px |
| `archive-acara.html` | Jadwal. Query Loop ke CPT acara plus tautan saring format dan kota |
| `single-acara.html` | Detail satu sesi. Dua kolom, kartu detail menempel di kanan |
| `search.html` | Hasil pencarian, lengkap dengan kotak cari |
| `404.html` | Halaman tidak ketemu, ada tombol ke Jadwal dan kotak cari |

Kedelapannya memanggil `parts/header` dan `parts/footer`, sudah dicek satu per satu. Satu H1 per
template, dan di `front-page.html` H1-nya datang dari pattern hero.

### Konsekuensi yang perlu diputuskan pemilik

**Begitu `templates/index.html` ada, WordPress memperlakukan tema ini sebagai block theme.**
Template PHP GeneratePress berhenti dipakai, dan pengaturan layout GeneratePress di Customizer
tidak lagi berpengaruh. Tempat mengedit tampilan pindah ke Appearance > Editor.

Itu memang arah yang diminta brief, "child theme block-based". Tapi dua hal jadi mengambang:

1. `style.css` masih menulis `Template: generatepress`, jadi GeneratePress tetap wajib terpasang
   walau template-nya tidak dipakai lagi. `functions.php` GeneratePress tetap ikut jalan.
2. Karena tidak ada satu pun template PHP GeneratePress yang terpakai, tema ini sebenarnya sudah
   bisa berdiri sendiri. Melepas baris `Template:` akan menghapus ketergantungan itu. **Tidak saya
   lakukan sendiri** karena brief menyebut child theme, dan itu keputusan pemilik, bukan keputusan
   teknis yang bisa saya ambil diam-diam.

### Full bleed

Empat pattern diberi `align: full` supaya latarnya mepet tepi layar sementara isinya tetap terkunci
di 1160px: `tjr/ticker`, `tjr/stats`, `tjr/cta`, `tjr/gallery-rail`.

`tjr/ticker` isinya cuma satu blok HTML, dan blok HTML tidak punya pengaturan align, jadi dia
dibungkus dulu dalam group `align: full`. Tiga lainnya tinggal ditambah atribut di group terluar
yang memang sudah ada.

Supaya `alignfull` benar-benar mepet tepi, `styles.spacing.padding` di theme.json diset `0` untuk
atas bawah dan `spacing 3` untuk kiri kanan. Padding kiri kanan itu yang dipakai
`useRootPaddingAwareAlignments` buat menghitung margin negatif blok full.

### Setelan theme.json yang diminta, hasil cek

| Setelan | Nilai | Status |
|---|---|---|
| `appearanceTools` | `true` | sudah ada sejak awal |
| `useRootPaddingAwareAlignments` | `true` | sudah ada sejak awal |
| `layout.contentSize` | `720px` | sudah ada sejak awal |
| `layout.wideSize` | `1160px` | sudah ada sejak awal |
| `templateParts` | header dan footer | **baru** |
| `customTemplates` | `halaman-lebar` untuk page | **baru** |
| `styles.spacing.padding` | atas bawah 0, kiri kanan spacing 3 | **baru** |

## Putaran perbaikan tampilan

### Latar halaman

`styles.color.background` di `theme.json` **sudah benar** sejak awal, isinya
`var(--wp--preset--color--paper)` yang menunjuk ke `#FAF6F0`. Jadi masalahnya bukan di situ.

Yang menimpa datang dari GeneratePress: parent theme itu juga menulis `background-color` untuk
`body` lewat CSS-nya sendiri, dan karena section non-alt di pattern sengaja dibiarkan transparan,
warna body itulah yang kelihatan di seluruh halaman.

Perbaikannya satu baris di `tjr.css`, `html body{background-color:var(--wp--preset--color--paper)}`.
Dua tag dipakai supaya kekhususannya lebih tinggi dari `body` milik GeneratePress, jadi menang
tanpa bergantung file mana yang dimuat belakangan. Section `alt` tidak disentuh, tetap `paper-deep`
lewat atribut blok.

### Badge taksonomi

`core/post-terms` merender `<div class="wp-block-post-terms ...">` berisi `<a>` untuk tiap istilah.
Kelas `tag` menempel di div-nya, jadi yang jadi kotak pill adalah seluruh baris, sementara link di
dalamnya tetap bergaris bawah dengan warna link biasa.

Sekarang dibalik: div-nya jadi pembungkus polos dengan `display:flex` dan `gap` 6px, dan pill-nya
pindah ke `<a>`. Hasilnya tiap kategori jadi satu pill sendiri, tanpa garis bawah, teks
`sage-text` di atas sage 20 persen. Pemisah koma bawaan WordPress disembunyikan karena jaraknya
sudah diurus `gap`. Fokus keyboard tetap terlihat.

Post-terms kota di kolom yang sama bukan pill, cuma teks, jadi di situ yang dihapus hanya garis
bawahnya. Garis bawah muncul lagi saat di-hover supaya tetap kelihatan bisa diklik.

### Kartu kalender

Tanggalnya memang belum berubah-ubah karena field ACF belum terisi, dan itu bagian lane CMS.
Tidak saya sentuh. Yang saya kerjakan cuma memastikan kartunya tidak berantakan begitu tanggalnya
nanti beda beda:

| Yang bisa bikin berantakan | Penjagaannya |
|---|---|
| Angka lebar beda, misal 1 lebih sempit dari 8 | `font-variant-numeric: tabular-nums` plus `font-feature-settings: "tnum"` |
| Nama bulan patah jadi dua baris | `white-space: nowrap` di bulan dan tanggal |
| Tinggi kartu naik turun antar baris | `min-height: 58px` dan isi ditengahkan pakai flex |
| Jarak bawaan blok post-date bikin kartu memanjang | margin `core/post-date` dan `core/post-terms` diset 0 di `theme.json` |

Lebar kartunya sendiri sudah dikunci `flex: 0 0 72px` sejak awal, jadi kolom tanggal tidak akan
menggeser judul acara walau isinya berubah.

## Mana yang bisa diatur Caca dan Dhanty, mana yang terkunci

### Bisa diatur lewat editor, tanpa nyentuh kode

- Semua teks di tiap section: judul, paragraf, pertanyaan FAQ, teks tombol, kutipan testimoni
- Semua foto, tinggal klik placeholder lalu pilih dari Media Library
- Warna teks dan latar, **tapi cuma dari 9 swatch TJR**
- Ukuran huruf, **tapi cuma dari 8 langkah yang tersedia**
- Jarak dan padding, **tapi cuma dari 8 langkah yang tersedia**
- Tambah, ubah, hapus acara. Format dan kota tinggal dicentang
- Berapa acara yang tampil di beranda dan urutannya, lewat pengaturan blok Query Loop
- Section mana yang muncul di sebuah halaman dan urutannya, tinggal sisip pattern lalu geser
- Isi header dan footer lewat Appearance > Editor > Patterns > Template Parts
- Menu navigasi lewat blok Navigation di header, termasuk urutan dan halaman apa saja
- Susunan tiap template lewat Appearance > Editor > Templates

### Terkunci di kode, sengaja

| Yang dikunci | Di mana | Kenapa |
|---|---|---|
| Nilai hex 13 warna | `theme.json` | Supaya palet tidak melebar sendiri |
| Pemilih warna bebas | `color.custom: false` | Diminta di brief. Tidak ada color picker sama sekali |
| Palet bawaan WordPress | `defaultPalette: false` | Kalau tidak dimatikan, 30-an warna WordPress ikut muncul |
| Ukuran huruf bebas | `customFontSize: false` | **Keputusan saya**, bukan permintaan brief. Skala 8 langkah jadi tidak bisa dilewati. Kalau terasa terlalu ketat, hapus satu baris itu |
| Jarak bebas | `customSpacingSize: false` | **Keputusan saya**, alasan sama. Satu baris juga untuk membukanya |
| Tiga muka huruf | `theme.json` | Font baru harus lewat kode |
| Lebar konten 720 dan 1160 | `theme.json` | Lebar baca ideal, jangan diubah dari editor |
| Tekstur kertas, reveal saat scroll, ticker, selotip, foto miring, kartu sesi yang menempel | `assets/tjr.css` | Ini efek yang tidak punya kontrol di editor |
| Slug URL `acara`, `format`, `kota`, arsip `jadwal` | `functions.php` | Mengubahnya memutus link yang sudah tersebar |
| Urutan jadwal dari tanggal acara | `functions.php` | Supaya sesi terdekat selalu di atas tanpa diurutkan manual |
| Pattern bawaan WordPress | `functions.php` | Disembunyikan supaya penyisip blok cuma berisi section TJR |

## Pattern

Delapan penanda `pattern: tjr/*` di `final-beranda.html` semuanya ada, nama persis sama:

`tjr/hero-booking` · `tjr/inclusions` · `tjr/gallery-rail` · `tjr/about` · `tjr/stats` ·
`tjr/testimonials` · `tjr/faq` · `tjr/cta`

Tiga tambahan di luar daftar, dan alasannya:

| Pattern | Kenapa ditambah |
|---|---|
| `tjr/jadwal` | Di beranda final section ini ditandai "query loop", bukan "pattern". Tanpa pattern, tidak ada cara memasangnya selain menyusun Query Loop dari nol tiap kali |
| `tjr/ticker` | Deretan judul tema di bawah hero tidak punya penanda sama sekali di beranda final, padahal terlihat di desain yang disetujui. Kalau tidak dibuat, ada lubang antara hero dan jadwal |
| `tjr/beranda` | Menyusun kesepuluh section jadi satu sisipan. Isinya cuma sepuluh blok `wp:pattern`, jadi tidak ada isi yang digandakan |

## Keputusan yang saya ambil sendiri

1. **Chip filter jadwal diganti tautan taksonomi asli.** Di prototipe chip-nya digerakkan 6 baris
   JavaScript yang cuma memindah kelas aktif, tidak benar-benar menyaring. Di tema ini tiap chip
   jadi tautan ke arsip taksonomi (`/format/journaling-workshop/`). Nol JavaScript, jalan tanpa JS,
   dan tiap format punya URL sendiri yang bisa masuk indeks Google.

2. **FAQ pakai blok `core/details`, bukan HTML mentah.** Hasil render-nya tetap
   `<details><summary>` seperti prototipe, tapi pertanyaan dan jawabannya bisa disunting langsung
   di editor. Kalau ditulis sebagai HTML mentah, Caca dan Dhanty harus mengedit tag.

3. **Taksonomi dibuat hierarkis.** Format dan kota tampil sebagai daftar centang, bukan kotak
   ketik bebas. Tidak ada istilah baru yang lahir dari salah ketik, misalnya "Jogja" dan
   "Yogyakarta" jadi dua kota berbeda.

4. **Enam format dan tiga kota diisi otomatis saat tema diaktifkan.** Diambil dari
   `brand-brief.md`, jadi begitu tema aktif daftarnya sudah siap dicentang.

5. **Warna di luar palet dihapus, bukan dibawa.** Prototipe punya tiga nilai yang tidak ada di
   `tokens.css`:
   - teks footer `#EDE3D7` diganti `paper-deep`, kontras di atas `ink` tetap tinggi
   - hover tombol `#611f1f` diganti `color-mix` burgundy dengan ink
   - teks tag `#5d6b4d` awalnya diganti `color-mix` sage dengan ink, sekarang diganti lagi
     jadi `sage-text` sejak token itu ada
   Sesudah putaran kontras, **`assets/tjr.css` nol hex mentah**. Semua warna lewat token.

6. **Placeholder foto jadi file SVG di tema, bukan `<img src="">`.** `src` kosong itu HTML tidak
   sah dan di sebagian browser memicu permintaan ulang ke halaman itu sendiri. SVG-nya memakai
   empat warna palet. Ketujuh gambar punya `alt` yang menjelaskan isinya, nol alt kosong.

7. **`theme.json` versi 3, jadi minimal WordPress 6.6.** Ditulis di header `style.css`. Hostinger
   menjalankan WordPress terbaru jadi ini aman, tapi perlu dicek sekali sebelum upload.

## Yang masih nunggu orang

| Yang ditunggu | Dari siapa | Dampak kalau belum ada |
|---|---|---|
| Nama field ACF tanggal mulai | lane A4 | `functions.php` sekarang memakai `tjr_mulai` lewat konstanta `TJR_FIELD_MULAI`. Kalau A4 memakai nama lain, **ubah satu baris** di baris 24 `functions.php`, jangan sentuh query-nya |
| Kolom harga dan sisa slot di daftar jadwal | lane A4 | Query Loop sekarang cuma menampilkan tanggal, judul, ringkasan, kota, dan format. Harga dan slot butuh field ACF, dan blok inti tidak bisa membacanya |
| Link WhatsApp otomatis | lane A4 | Tombol di hero dan penutup masih `href="#"` |
| Foto asli resolusi tinggi | Caca dan Dhanty | 7 placeholder terpasang |
| Review Google Business Profile | Caca dan Dhanty | 3 testimoni masih placeholder, sudah ditandai di teksnya |
| File font untuk self-hosting | Caca dan Dhanty atau orkestrator | Font sekarang dimuat dari server Google. Setelah file font ada, daftarkan lewat `fontFace` di `theme.json` supaya halaman lebih cepat dan tidak ada permintaan ke pihak ketiga. Jalurnya sudah ditulis sebagai catatan di `functions.php` |
| Template part header dan footer | orkestrator | Pattern cuma mengisi bagian tengah halaman. Bar CTA yang menempel di bawah layar CSS-nya sudah ada (`.tjr-sticky`), markup-nya belum, karena tempatnya di footer |
| Domain dan file logo | Caca dan Dhanty | Nama brand sekarang teks biasa |
| Ejaan resmi "Journaling" | Caca dan Dhanty | Dipakai satu L di seluruh tema, mengikuti Instagram |

## Pemeriksaan yang sudah dijalankan

- Struktur komentar blok Gutenberg diperiksa dengan parser stack di 11 file: semuanya seimbang
  dan urutannya benar
- Tag HTML `div`, `section`, `aside`, `blockquote`, `details`, `figure`: seimbang di semua file
- `theme.json` valid JSON, 9 warna, 3 font, 8 ukuran huruf, 8 langkah jarak
- Kurung di `functions.php` seimbang. **Tidak bisa dijalankan `php -l`** karena PHP CLI tidak ada
  di mesin ini, jadi sintaksnya belum diuji mesin, baru diperiksa manual
- Nol em dash, nol frasa AI slop di seluruh lane
- Nol hex mentah di `assets/tjr.css`, semua warna lewat `var(--wp--preset--color--*)`
- Struktur blok 21 file (11 pattern, 2 part, 8 template) diperiksa parser stack: semuanya seimbang
- Kedelapan template terbukti memanggil `parts/header` dan `parts/footer`
- Satu H1 per template, nol template tanpa H1
- `tjr-flat.zip` dibongkar ulang untuk memastikan akarnya langsung `style.css`, bukan folder
- Sesudah putaran perbaikan tampilan, `tjr-flat.zip` dibangun ulang dan checksum `assets/tjr.css`
  serta `theme.json` di dalam zip dicocokkan dengan file di folder tema, dua-duanya sama
- Satu blok `<h1>` di seluruh pattern, dan letaknya di hero
- 7 gambar, 7 punya `alt` deskriptif, nol alt kosong
- `focus-visible` diatur di 6 tempat, `prefers-reduced-motion` dijaga di 2 tempat,
  target sentuh 44px dipasang di chip, tombol, dan summary FAQ
