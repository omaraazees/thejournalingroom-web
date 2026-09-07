# LAPORAN T-3 + T-4: tombol mati, field mati, dan mengisi situs kosong

**Agent:** Jim (jim-mtqt9z7n) · **Tanggal:** 7 Sep 2026 · **Kartu:** T-3 dan T-4
**Batasan:** nol deploy FTP (Pam memegang T-1 di repo sama), nol kredit Magnific, nol em dash.

> Ditulis bertahap, satu item selesai satu append. Pola ini terbukti di T-2.

## Keadaan awal, terukur lewat WP REST sebelum apa pun disentuh

| | nilai |
|---|---|
| `posts` terbit | **0** (header `x-wp-total: 0`) |
| `acara` terbit | **1** (`id 12`, `embracing-growth`) |
| halaman | 6 (`beranda` 6, `tentang` 7, `kolaborasi` 8, `kontak` 9, `galeri` 10, `cerita` 11) |

---

## T-3 item 1: tombol "Buka galeri" yang tidak melakukan apa-apa

### Lapisan render: KONTEN DATABASE, bukan tema

Ditetapkan dulu sebelum menambal, sesuai peringatan kartu.

- `grep "Buka galeri"` di seluruh repo hanya kena **dua berkas prototipe desain**
  (`desain/prototipe/arah-b-editorial.html`, `desain/prototipe/final-beranda.html`).
  Keduanya artefak desain mati, tidak pernah dimuat situs.
- Nol hasil di `wordpress/theme-v5/`. Jadi tema **bukan** sumbernya.
- Tombol ditemukan di `content.raw` halaman **id 10** (`/galeri/`), offset 1173, sebagai blok
  Gutenberg `wp:buttons` berisi satu `wp:button` dengan `href="#"`.

Curiga god ke halaman 10 benar. Menambal tema tidak akan mengubah apa pun.

### Yang dikerjakan

`POST /wp-json/wp/v2/pages/10`, membuang tepat satu blok
`<!-- wp:buttons -->...<!-- /wp:buttons -->`.

Pengaman sebelum menulis: pola regex dicocokkan dulu dan **dipastikan hanya kena satu blok**,
dan blok itu dipastikan mengandung teks "Buka galeri". Kalau jumlahnya bukan persis satu,
skrip berhenti tanpa menulis apa pun. Halaman lain tidak disentuh.

| | nilai |
|---|---|
| `content.raw` sebelum | 3.306 karakter |
| `content.raw` sesudah | 3.013 karakter |
| selisih | 293 karakter, persis panjang blok tombol |

### Verifikasi

- `GET` ulang halaman 10: `Buka galeri` **tidak ada**, `wp:buttons` **tidak ada**,
  `href="#"` **tidak ada**.
- Halaman live `/galeri/` diambil **dua kali** dengan cache-bust berbeda: dua-duanya
  HTTP 200, **71.934 byte identik**, nol kemunculan "Buka galeri". Ukuran yang sama persis
  di dua permintaan sekaligus menyingkirkan kecurigaan respons terpotong.

**Status: SELESAI dan terverifikasi di live.**

---

## T-3 item 2: field ACF `catatan_harga`

### Koreksi terhadap brief: field ini SEBENARNYA dirender

Brief menyebut `catatan_harga` "tidak pernah dirender di mana pun". Itu tidak akurat, dan
perlu diluruskan supaya keputusannya berdiri di atas fakta yang benar.

`wordpress/theme-v5/inc/isi-beranda.php` fungsi `tjr_v5_harga_acara()` MEMANG merendernya,
ditempel kecil di sebelah angka harga lewat `<span class="slot-catatan">`. Yang membuatnya
tidak pernah terlihat adalah **syarat berantai**: baris harga hanya dirender kalau
`harga >= 1`, dan satu-satunya acara terbit (`id 12`) kebetulan punya `catatan_harga` kosong
walau `harga` terisi 297.300.

Jadi audit sebelumnya menyimpulkan "nol dirender" dari keadaan data saat itu, bukan dari
kode. Begitu ada acara dengan harga terisi DAN catatan terisi, teks itu akan tayang.

Ini justru memperkuat keputusan Umar untuk menghapus: field yang tidak terlihat tapi hidup
di kode adalah jebakan, bukan field mati yang aman ditinggal.

### Lapisan render: DUA lapisan, dan cuma satu yang bisa saya sentuh

Ini yang menentukan hasil kartu, jadi ditulis eksplisit.

| lapisan | isi | bisa lewat WP REST? |
|---|---|---|
| Kode tema (repo) | fungsi render + cermin definisi field | Tidak. Berkas repo, butuh deploy. |
| Nilai data per acara | `catatan_harga` di ACF acara | Ya, dan nilainya sudah kosong. |
| **Definisi field ACF** | grup field tersimpan di **database** | **Tidak. Lihat di bawah.** |

Definisi field-nya **terdaftar di database, bukan di repo**. Dibuktikan begini:

- Tema hanya mendaftarkan **3** field lewat PHP (`acf_add_local_field_group`):
  `bawa_sendiri`, `disediakan_teks`, `judul_acara`. `catatan_harga` bukan salah satunya.
- Tidak ada folder `acf-json/` di tema, jadi **tidak ada sinkronisasi ACF local JSON**.
  `wordpress/cms/acf-fields.json` cuma berkas siap-impor sekali jalan, bukan sumber hidup.
- Endpoint pengelolaan ACF tidak ada: `acf/v3/field-groups`, `wp/v2/acf-field-group`,
  `wp/v2/acf-field` semuanya **404**. Namespace yang tersedia cuma
  `oembed/1.0`, `hostinger-*`, `mcp`, `wp/v2`, `wp-site-health/v1`, `wp-block-editor/v1`,
  `wp-abilities/v1`, dan `wp-abilities/v1` isinya cuma 3 kemampuan BACA
  (`core/get-site-info`, `core/get-user-info`, `core/get-environment-info`).

Nilai field-nya sendiri memang terbaca lewat REST (`acara/12` mengembalikan objek `acf`),
tapi **membaca nilai bukan menghapus definisi**. Definisi hanya bisa dihapus dari dasbor ACF.

### Yang dikerjakan (semua sudah masuk repo)

| berkas | perubahan |
|---|---|
| `wordpress/theme-v5/inc/isi-beranda.php` | `tjr_v5_harga_acara()` tidak lagi membaca atau merender `catatan_harga`. Docblock ditulis ulang, sekarang mencatat kapan dan kenapa dihapus. |
| `wordpress/cms/acf-fields.json` | definisi field `field_tjr_catatan_harga` dibuang (1 field). |
| `wordpress/cms/data-acara-contoh.json` | kunci `catatan_harga` dibuang dari 8 acara contoh. |
| `wordpress/theme-v5/inc/seo.php` | komentar yang menerangkan pemetaan Event dibersihkan. |
| `desain/SCHEMA-EVENT.md` | baris tabel dipersempit jadi `disediakan_teks` saja. |

Verifikasi: `bin/periksa-php.sh` lulus, **11 berkas PHP, 0 gagal**. Kedua JSON divalidasi
ulang dengan parser. Nilai `catatan_harga` pada acara `id 12` sudah kosong, jadi nol data
publik yang perlu dibersihkan.

### Sisa satu langkah, dan itu di luar jangkauan kartu ini

Dua hal menahan item ini dari "hilang dari live":

1. **Perubahan tema butuh deploy**, dan kartu ini melarang saya deploy karena Pam sedang
   memegang T-1 di repo yang sama. Sudah dicommit, menunggu pengirim berikutnya.
2. **Definisi field di database butuh satu tindakan dasbor**, bukan REST:
   `wp-admin` > ACF > grup field acara > hapus field "Catatan harga" > Update.

Definisi lengkapnya masih tersimpan di git history kalau suatu saat perlu dipulihkan.

**Status: SELESAI di sisi repo dan data. Belum hilang dari live, menunggu 1 deploy dan
1 tindakan dasbor.** Saya tidak menyatakannya selesai penuh.

---

## T-4: pencarian draf sebelum menulis apa pun

Kartu mewajibkan mencari dulu dan melarang mengarang isi. Pencarian dilakukan lebih dulu,
dan hasilnya mengubah bentuk kartu ini. Ditulis lengkap supaya keputusannya bisa diperiksa.

### Di mana saja dicari

| tempat | hasil |
|---|---|
| `konten/` (11 berkas) | `outline-blog.md`: **outline 8 artikel**, berisi H2, keyword, target panjang, rencana link internal. Bukan naskah. |
| `desain/halaman/cerita.html` | **8 kartu artikel**: judul, kategori, ringkasan, perkiraan waktu baca. Semuanya berlabel "Draf". Bukan naskah. |
| `desain/halaman/cerita-detail.html` | **satu naskah utuh**, ~4.300 karakter teks. Ini satu-satunya naskah lengkap yang ada. |
| `wordpress/cms/data-acara-contoh.json` | 6 acara contoh, dengan penanda `_sumber` per field. |
| `desain/halaman/jadwal.html` | mockup 5 sesi mendatang, memakai data contoh yang sama. |
| `~/Developer/wiki` (semua) | nol naskah. Cuma klaim "sudah didraf" di halaman proyek. |
| `hive/` (`board.md`, `tasks.json`, memori god) | nol naskah. Klaim yang sama diulang. |
| Riwayat git seluruh cabang | **nol berkas draf pernah ada lalu dihapus.** `git log --diff-filter=D` kosong. |

### Temuan 1: naskah artikel ada SATU, bukan tiga

Klaim "tiga draf artikel SEO sudah disiapkan" muncul di tiga tempat sekaligus
(`wiki/projects/project-journaling-room-web.md`, `hive/board.md`, `hive/tasks.json`), tapi
tidak satu pun menunjuk lokasi berkas, dan naskahnya memang tidak ada.

Yang benar benar ada:

- **1 naskah lengkap**: "Mulai journaling waktu nggak tahu mau nulis apa" (kategori Panduan
  journaling), tertanam di `desain/halaman/cerita-detail.html`. Utuh, punya lima H2, sudah
  bersuara Caca dan Dhanty.
- **7 sisanya**: judul + ringkasan satu kalimat + outline H2. Itu bahan, bukan naskah.

Menulis 2 artikel sisanya berarti mengarang 1.400 sampai 1.800 kata baru per artikel atas
nama Caca dan Dhanty. Kartu melarang itu secara eksplisit.

Ini pola yang sama persis dengan T-2: klaim "sudah dikerjakan" yang menyebar ke beberapa
berkas dari satu sesi yang hilang, tanpa artefak yang bisa ditunjuk.

### Temuan 2: konsep sesi punya identitas nyata, tapi TANGGALNYA karangan

Ini yang menahan penayangan Jadwal, dan alasannya bukan formalitas.

`wordpress/cms/data-acara-contoh.json` menandai asal tiap field lewat kunci `_sumber`:

| field | `_sumber` | status |
|---|---|---|
| `judul`, `format_acara`, `venue_nama`, `harga`, `durasi_jam` | `brand-brief` | **fakta** |
| `ringkasan` | `ditulis untuk contoh` | contoh |
| `tanggal_mulai`, `kapasitas`, `slot_terisi` | `beranda` | **karangan** |
| `venue_alamat`, `venue_maps` | `kosong` | kosong |

Dan catatan di kepala berkas itu sendiri berbunyi: *"Tanggal, kapasitas, slot terisi, alamat,
dan link Maps TIDAK ada di brand brief, jadi semuanya angka contoh. Jangan pakai angka
bertanda contoh di situs live."*

Menayangkan keempat konsep berarti menayangkan **tanggal, kapasitas, dan sisa kursi yang
dikarang** di situs bisnis yang hidup. Konsekuensinya bukan kosmetik: `tanggal_mulai` wajib
untuk schema Event, halaman Jadwal memasang hitungan "sisa slot", dan orang betulan bisa
datang di tanggal yang tidak pernah ada. Sesi yang lalu sudah menandai bahaya ini sendiri.

Yang hilang untuk menayangkan bukan naskah, melainkan **empat tanggal nyata plus kapasitas
nyata** dari Dhanty atau Caca.

### Yang DIKERJAKAN: satu artikel tayang, dan satu cacat tema yang tidak diketahui siapa pun

Naskah yang benar benar ada saya tayangkan. Nol kata dikarang, isinya persis naskah sesi
sebelumnya, cuma dipindah dari HTML mockup ke blok Gutenberg.

| | |
|---|---|
| Judul | Mulai journaling waktu nggak tahu mau nulis apa |
| URL | `/cerita/mulai-journaling-nggak-tahu-mau-nulis-apa/` (post `id 180`) |
| Kategori | Panduan journaling (`id 13`, dibuat baru, sebelumnya cuma ada Uncategorized) |
| Sumber naskah | `desain/halaman/cerita-detail.html` |
| Blok | 24: 5 heading, 17 paragraf, 1 kutipan, 1 daftar berisi 7 nomor, 1 tombol |

Konversi diperiksa hitungannya lawan sumber supaya nol kalimat hilang: sumber 5 `h2`,
18 `p`, 7 `li`, 1 `blockquote`. Hasil 5 heading, 17 paragraf (satu `p` sumber jadi isi
kutipan, satu lagi jadi tombol), 7 butir daftar, 1 kutipan. Cocok.

Tombol penutup diarahkan ke `/jadwal/` yang nyata (dicek HTTP 200), bukan ke jalur relatif
mockup `../pages/jadwal.html`. Ini sekaligus memenuhi aturan `outline-blog.md` bahwa tiap
artikel wajib punya minimal satu link ke Jadwal.

#### Cacat yang ketemu waktu verifikasi: tema tidak punya template `single`

Sesudah artikel terbit, halamannya **HTTP 200 tapi nol isi**: cuma judul dan ringkasan,
badan artikel tidak muncul sama sekali.

Sebabnya bukan artikelnya. `wordpress/theme-v5/templates/` berisi `404`, `archive-acara`,
`front-page`, `index`, `page`, `search`, `single-acara`, `taxonomy`. **Tidak ada `single`.**
Jadi WordPress jatuh ke `index.html`, yaitu template DAFTAR blog, yang cuma mencetak
`post-title` dan `post-excerpt`. Isi artikel tidak pernah dirender.

Artinya situs ini **belum pernah bisa menampilkan satu artikel pun**. Cacat ini tidak
kelihatan selama `/cerita/` masih kosong, dan baru muncul begitu ada artikel pertama.

**Diperbaiki tanpa deploy.** Tema blok bisa menyimpan template di database lewat
`POST /wp-json/wp/v2/templates`, jadi masih di jalur WP REST yang saya pegang dan tidak
menyenggol larangan FTP. Template `single` dibuat: kategori, H1 judul, lead, tanggal,
gambar utama, lalu `post-content`. Strukturnya mengikuti `page.html` yang sudah ada supaya
header, footer, dan kelas `isi-halaman` tetap seragam.

Berkas yang sama juga ditulis ke repo di `wordpress/theme-v5/templates/single.html` supaya
tema membawanya sendiri ke depan.

> **Catatan untuk yang deploy berikutnya.** Sekarang ada DUA salinan template ini: satu
> `source: custom` di database (yang aktif sekarang), satu berkas tema di repo. Template
> database selalu menang atas berkas tema. Isinya identik hari ini, jadi aman, tapi kalau
> nanti `single.html` di repo diubah lalu di-deploy, perubahannya TIDAK akan terlihat
> selama salinan database masih ada. Hapus template `tjr-v5//single` yang `source: custom`
> lewat Site Editor sesudah deploy pertama yang membawa berkasnya, supaya cuma satu sumber
> kebenaran yang tersisa.

#### Verifikasi

| yang dicek | hasil |
|---|---|
| `/cerita/`, 2 permintaan cache-bust | HTTP 200, **83.079 byte identik**, judul artikel muncul, pesan "Belum ada tulisan di sini" **hilang** |
| Halaman artikel, 2 permintaan | HTTP 200, **88.881 byte identik**, diakhiri `</html>` |
| Kelima H2 plus tombol | **6 dari 6 muncul** di kedua permintaan |
| Teks terlihat | 3.880 karakter (sebelum template dibuat: 573) |
| `wp-sitemap-posts-post-1.xml` | berisi URL artikel. **Sebelumnya nol entri.** |
| `x-wp-total` posts | **1** (sebelumnya 0) |

Ukuran byte yang identik di dua permintaan sekaligus menyingkirkan kecurigaan respons
terpotong Hostinger.

**Status T-4 artikel: 1 dari 3 tayang.** Dua sisanya tidak ditulis karena naskahnya tidak
ada dan kartu melarang mengarang.

**Status T-4 jadwal: 0 dari 4 tayang.** Tertahan tanggal karangan, bukan tertahan naskah.

---

## Ringkasan status

| kartu | item | status |
|---|---|---|
| T-3 | Tombol "Buka galeri" | **Selesai, terverifikasi live** |
| T-3 | Field `catatan_harga` | Repo dan data selesai. Perlu 1 deploy + 1 tindakan dasbor ACF |
| T-4 | Artikel SEO | **1 dari 3 tayang.** 2 sisanya nol naskah |
| T-4 | Konsep sesi ke Jadwal | **0 dari 4.** Tertahan tanggal karangan |
| bonus | Template `single` tema | **Dibuat dan aktif.** Cacat yang sebelumnya tidak diketahui |

## Yang dibutuhkan untuk menutup sisanya

**Untuk 4 konsep sesi**, yang kurang bukan tulisan melainkan data nyata dari Dhanty atau
Caca: **tanggal dan jam mulai**, **kapasitas**, dan **alamat venue**. Judul, format, venue,
harga, dan durasi sudah ada dan berstatus fakta dari brand brief. Begitu tanggalnya turun,
penayangannya cepat, tinggal `POST /wp-json/wp/v2/acara` empat kali.

**Untuk 2 artikel sisanya**, yang kurang naskah. Outline, judul, ringkasan, dan target
keyword semuanya sudah ada di `konten/outline-blog.md` dan `desain/halaman/cerita.html`.
Dua kandidat berikutnya yang outline-nya paling matang: "Tujuh pertanyaan pemantik buat
halaman pertama" dan "Bedanya journaling, diary, dan bullet journal". Butuh keputusan siapa
yang menulis, dan kalau boleh ditulis agent, itu izin baru karena kartu ini melarangnya.

## Kepatuhan batasan

- **Nol deploy.** `bin/kirim-tema-ftp.py` dan `bin/dorong-tema.sh` tidak dijalankan. Semua
  perubahan live lewat WP REST, dan template `single` sengaja dibuat lewat REST justru
  supaya tidak perlu FTP selagi Pam memegang T-1.
- **Nol kredit Magnific.**
- **Nol copy dikarang.** Artikel yang tayang isinya naskah yang sudah ada.
- **Nol berkas milik Pam disentuh.** Commit menyebut berkas satu per satu, tidak pakai
  `git add -A`.
- **Nol em dash.**
---

## Untuk Pam: berkas tema yang saya sentuh

Pam yang mengirim semuanya sekali jalan. Ini daftar persis berkas tema yang berubah karena
kerja saya, supaya tidak ada yang terlewat atau terkejut.

| berkas | kartu | kenapa |
|---|---|---|
| `wordpress/theme-v5/inc/isi-beranda.php` | T-3 | `tjr_v5_harga_acara()` tidak lagi merender `catatan_harga` |
| `wordpress/theme-v5/inc/seo.php` | T-3 | komentar pemetaan Event dibersihkan (komentar saja, nol perubahan perilaku) |
| `wordpress/theme-v5/templates/single.html` | T-4 | **berkas baru.** Template artikel yang selama ini tidak ada |

Di luar tema (tidak perlu dikirim FTP): `wordpress/cms/acf-fields.json`,
`wordpress/cms/data-acara-contoh.json`, `desain/SCHEMA-EVENT.md`, dua berkas laporan.

Catatan: `inc/isi-beranda.php` juga disentuh Pam untuk T-1, dan commit `4bcff93` milik saya
ikut membawa perubahan itu karena kami menyunting berkas yang sama. Isinya utuh dan benar,
tidak perlu dirapikan.

Sesudah deploy pertama yang membawa `templates/single.html`, hapus template
`tjr-v5//single` yang `source: custom` di database lewat Site Editor, supaya tidak ada dua
sumber kebenaran. Selama belum dihapus, situs tetap benar karena isinya identik.

## Sisa pekerjaan kalau ada yang melanjutkan

Dua-duanya tertahan data, bukan tertahan kode, dan tidak ada yang setengah jadi:

1. **4 acara**: butuh tanggal, jam mulai, kapasitas, alamat venue dari Dhanty atau Caca.
   Lalu `POST /wp-json/wp/v2/acara` empat kali. Judul, format, venue, harga, durasi sudah
   berstatus fakta di `wordpress/cms/data-acara-contoh.json`.
2. **2 artikel**: butuh naskah, atau izin baru untuk menulisnya. Outline lengkap ada di
   `konten/outline-blog.md`, judul dan ringkasan di `desain/halaman/cerita.html`.

Nol pekerjaan saya yang tertinggal di konteks. Semua yang terverifikasi ada di laporan ini.
