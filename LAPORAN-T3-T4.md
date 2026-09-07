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

