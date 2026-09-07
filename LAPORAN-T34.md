# T-34: harga acara terdekat 297300 jadi 260000

**Selesai. Nol berkas tema disentuh, nol kiriman, nol deploy.** Perubahannya satu
bilangan bulat di basis data, lewat REST.

## Langkah 1: memastikan "acara terdekat" itu siapa

Kartu menyuruh memverifikasi sendiri, jadi saya tidak memakai kalimat god.
REST dengan kredensial: **acara terbit ada 1**, yaitu id `12`, "Embracing Growth:
A Full-day Journaling Activity". Sisanya **15 di tempat sampah**. Jadi "terdekat"
tidak ambigu, dia satu-satunya.

## Langkah 2: sapuan berbasis isi, sebelum mengubah apa pun

Kartu menyebut tiga kali hive salah karena memeriksa tempat yang MUDAH diperiksa
alih-alih tempat datanya BERADA. Jadi urutannya dibalik: cari dulu, ubah belakangan.

**Repo: nol.** Grep seluruh repo untuk `297300`, `297.300`, `297,300`, `297rb`,
`297k`, `297ribu`: **nol kemunculan**, termasuk di `wordpress/cms/` dan `konten/`.
Artinya nol yang hardcode, jadi batas "berhenti dan lapor kalau ada di tema"
tidak pernah tersentuh.

**Produksi: 11 kemunculan, 5 peran, semuanya TURUNAN dari satu bilangan.**

| Peran | Di mana | Bentuk |
|---|---|---|
| `priceRange` JSON-LD LocalBusiness | kedelapan halaman terbit dan seluruh arsip | `Rp297.300` |
| `description`, `og:description`, `twitter:description` | `/acara/embracing-growth/` | `Kit lengkap, Rp297.300.` |
| `description` JSON-LD Event | `/acara/embracing-growth/` | sama |
| `offers.price` JSON-LD Event | `/acara/embracing-growth/` | `297300` telanjang, tanpa Rp dan tanpa titik |
| `dd-harga` panel fakta | `/` dan `/acara/` | `Rp297.300` |
| `ik-tag` kartu sesi terdekat | `/` | `Rp297.300` |

**Tempat yang dicurigai kartu tapi ternyata bersih:** tautan WhatsApp. Ada 3 per
halaman dan **nol** memuat harga, sudah dicek setelah URL di-unquote. Halaman
`/jadwal/` juga tidak mencetak harga di kartunya, cuma kena lewat `priceRange`.

**Sumber tunggalnya** post meta `harga`, sebuah integer, dibaca tiga fungsi:
`tjr_v5_harga_acara()` di `isi-beranda.php:938`, kalimat deskripsi di
`seo.php:523`, dan `tjr_v5_seo_rentang_harga()` di `seo.php:599`.

## Langkah 3: mengubah, dengan jaring pengaman

ACF menolak kiriman sebagian (`tanggal_mulai is a required property`), jadi
terpaksa baca-ubah-tulis seluruh objek `acf`. Itu persis pola yang siaran cacat
entitas hari ini peringatkan, jadi saya pasang pembuktiannya: keadaan asli disimpan
dulu, lalu hasilnya di-diff field per field.

**Hasil diff: tepat SATU field berubah,** `harga` 297300 ke 260000. Dua belas field
lain identik, termasuk `isi_kit` yang berupa larik 8 item dan `catatan_harga` yang
berupa string kosong. Nol yang tergeser diam-diam.

## Langkah 4: verifikasi di produksi

**17 permukaan disapu. Angka lama: nol di semua.**

Kedelapan halaman terbit, tiga artikel, tiga arsip taksonomi, feed, sitemap, dan
endpoint REST acara. Kesebelas kemunculan berbalik ke peran yang sama persis,
termasuk `offers.price` yang benar jadi `260000` telanjang dan bukan `Rp260.000`.

`priceRange` ikut berubah **seketika**, yang membuktikan transient `tjr_v5_seo_harga`
memang dibuang `tjr_v5_reset_hitungan()` lewat `save_post_acara`. Tanpa itu angka
lama akan menempel sampai satu jam.

## Batas kartu yang saya patuhi

**Acara di tempat sampah tidak disentuh, dan ini dibuktikan bukan diklaim.** Sebaran
harga kelima belasnya sekarang: sepuluh kosong, satu nol, lalu 125000, 100000, 40000,
dan 98000. **Nol yang bernilai 260000**, jadi nol yang tercemar perubahan saya.

Sekalian menutup satu kemungkinan yang dikhawatirkan kartu: **nol acara di tempat
sampah yang harganya 297300**, jadi pertanyaan "jangan ubah yang di sampah kalau
harganya sama" ternyata tidak pernah punya kasus.
