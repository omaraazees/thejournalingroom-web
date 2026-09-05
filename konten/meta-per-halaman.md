# Meta per halaman · The Journaling Room

Batas yang dipakai: **title tag maksimal 60 karakter**, **meta description maksimal 155 karakter**.
Semua panjang di bawah ini dihitung, bukan dikira kira.

Cara pasang: Rank Math, tab SEO di bawah editor tiap halaman. Kolom `Title` dan `Description`.
Jangan mengandalkan judul halaman otomatis, karena judul di halaman dan judul di hasil pencarian
punya tugas yang berbeda.

---

## Delapan halaman utama

### 1. Beranda

| | |
|---|---|
| Title | `Workshop Journaling Jogja \| The Journaling Room` |
| Panjang | 47 |
| Description | `Sesi journaling bareng di Yogyakarta. Datang tanpa pengalaman dan tanpa alat, semuanya sudah disiapkan. Lihat jadwal terdekat dan ambil slotmu.` |
| Panjang | 143 |
| Keyword utama | workshop journaling jogja |

Keyword utama ditaruh paling depan karena itu bagian yang paling kelihatan di hasil pencarian
mobile, dan karena "workshop journaling jogja" adalah satu satunya keyword yang niat belinya
sudah matang.

### 2. Jadwal

| | |
|---|---|
| Title | `Jadwal Sesi Journaling di Jogja \| The Journaling Room` |
| Panjang | 53 |
| Description | `Semua sesi journaling TJR yang masih buka, lengkap dengan tanggal, venue, harga, dan sisa slot. Bisa disaring per format acara dan per kota.` |
| Panjang | 140 |
| Keyword utama | jadwal workshop journaling jogja |

Menyebut tanggal, harga, dan sisa slot di description itu disengaja. Tiga hal itu yang dicari
orang sebelum klik, dan menyebutnya menaikkan rasio klik walau peringkatnya sama.

### 3. Tentang

| | |
|---|---|
| Title | `Tentang Kami \| The Journaling Room` |
| Panjang | 34 |
| Description | `Caca dan Dhanty bikin ruang journaling yang dulu mereka cari sendiri. Kenali cara kerja sesinya, dan kenapa tidak ada yang dinilai di sini.` |
| Panjang | 139 |
| Keyword utama | the journaling room (navigasi) |

Halaman ini tidak mengejar keyword komersial. Tugasnya meyakinkan orang yang sudah ragu ragu,
jadi description-nya menjawab keberatan, bukan menjual.

### 4. Kolaborasi

| | |
|---|---|
| Title | `Kolaborasi Brand \| The Journaling Room` |
| Panjang | 38 |
| Description | `Workshop journaling untuk brand, komunitas, dan tim kantor di Jogja. Sudah pernah jalan bareng Wardah, Artotel, dan Copenhagen. Kirim brief kamu.` |
| Panjang | 145 |
| Keyword utama | brand activation jogja |

Nama brand partner disebut di description karena itu bukti sosial paling cepat terbaca oleh
orang marketing yang lagi menyaring vendor.

### 5. Kontak

| | |
|---|---|
| Title | `Kontak \| The Journaling Room` |
| Panjang | 28 |
| Description | `Tanya jadwal, slot, atau kolaborasi lewat WhatsApp dan Instagram. Dibalas jam 09.00 sampai 21.00. Kami berbasis di Yogyakarta.` |
| Panjang | 126 |
| Keyword utama | tidak ada, halaman navigasi |

Jam balas disebut supaya orang tidak merasa mengirim pesan ke ruang kosong.

### 6. Cerita, indeks blog

| | |
|---|---|
| Title | `Cerita dan Panduan Journaling \| The Journaling Room` |
| Panjang | 51 |
| Description | `Panduan journaling untuk pemula, cerita di balik sesi, dan catatan kolaborasi. Ditulis santai oleh dua orang yang journaling tiap minggu.` |
| Panjang | 137 |
| Keyword utama | panduan journaling |

### 7. Galeri

| | |
|---|---|
| Title | `Galeri Sesi \| The Journaling Room` |
| Panjang | 33 |
| Description | `Foto dari sesi journaling yang sudah lewat, dikelompokkan per acara. Lihat isi meja, deco station, dan halaman peserta sebelum kamu ikut.` |
| Panjang | 137 |
| Keyword utama | tidak ada, halaman pendukung |

Halaman galeri jarang jadi pintu masuk pencarian, tapi sering jadi halaman terakhir yang
dilihat orang sebelum mengirim WhatsApp. Jadi description-nya diarahkan ke keraguan, bukan ke
kata kunci.

### 8. Cerita detail, satu artikel

| | |
|---|---|
| Title | `{judul artikel} \| The Journaling Room` |
| Panjang tetap | 23 karakter di luar judul |
| Batas judul | maksimal **37 karakter** supaya total tidak lewat 60 |
| Description | Ditulis manual di kolom excerpt, 120 sampai 150 karakter |

Kalau judul artikel lebih dari 37 karakter, pakai title tag manual yang lebih pendek dari judul
di halaman. Judul di halaman boleh panjang dan puitis, judul di hasil pencarian harus muat.

---

## Pola otomatis untuk CPT acara

Judul acara TJR panjang dan puitis, jadi pola otomatis apa pun akan kelewat batas di sebagian
kasus. Aku uji sembilan judul acara asli dari brand brief dikali lima venue, empat pola berbeda.
Hasilnya:

| Pola | Terpendek | Terpanjang | Lewat 60 karakter |
|---|---|---|---|
| `{judul} di {venue}, Jogja \| TJR` | 35 | 84 | 13 dari 45 |
| `{judul} di {venue} \| TJR` | 28 | 77 | 5 dari 45 |
| `{judul} \| Workshop Journaling Jogja` | 40 | 80 | 10 dari 45 |
| **`{judul} \| TJR Jogja`** | **24** | **64** | **5 dari 45** |

### Pola yang dipakai

```
Title       : %title% | TJR Jogja
Description : Sesi journaling %tanggal% di %venue%, %kota%. Kit lengkap, %harga%. Sisa %sisa_slot% slot. Ambil slot lewat WhatsApp.
```

Contoh terisi, dua duanya aman:

- `Sesi journaling Sabtu, 20 September 2026 di Kupiku Coffee, Jogja. Kit lengkap, 75K. Sisa 4 slot. Ambil slot lewat WhatsApp.` **123 karakter**
- `Sesi journaling Sabtu, 18 Oktober 2026 di Kopi Kalandjana, Jogja. Kit lengkap, 100K. Sisa 12 slot. Ambil slot lewat WhatsApp.` **125 karakter**

### Aturan pengaman untuk judul panjang

Suffix ` | TJR Jogja` panjangnya 12 karakter. Jadi:

> **Kalau judul acara lebih dari 48 karakter, isi kolom SEO title manual.**

Dari sembilan judul acara yang ada sekarang, cuma satu yang kena aturan ini:
`A Moment Between Chapters: 2026 Half-year Reflection` (52 karakter, total jadi 64).
Untuk acara itu, SEO title manualnya: `A Moment Between Chapters | TJR Jogja` (37 karakter).

Cara paling aman supaya Caca dan Dhanty tidak perlu menghitung: kasih peringatan di editor,
atau siapkan satu field ACF opsional `judul_seo_pendek` yang dipakai kalau diisi. Itu keputusan
lane A4, dan sudah kucatat di laporan supaya tidak jatuh di antara dua lane.

### Kalau slot sudah penuh

Description untuk acara yang penuh atau selesai jangan tetap menulis "Ambil slot lewat WhatsApp",
karena itu janji yang tidak bisa ditepati dan bikin orang kecewa di klik pertama.

```
Penuh   : Sesi journaling %tanggal% di %venue%, %kota%. Slot sudah penuh. Lihat jadwal berikutnya.
Selesai : Sesi journaling %tanggal% di %venue%, %kota% sudah selesai. Lihat foto sesinya dan jadwal berikutnya.
```

---

## Aturan yang berlaku untuk semua halaman

1. **Satu halaman satu keyword utama.** Tidak ada dua halaman dengan title tag yang saling
   menyerupai, karena keduanya akan saling menekan peringkat.
2. **Nama brand di belakang, bukan di depan**, kecuali di halaman Kontak dan Tentang. Orang
   yang belum kenal TJR mencari masalahnya, bukan namanya.
3. **Nol clickbait.** Kalau description menjanjikan sisa slot, angkanya harus datang dari field,
   bukan diketik manual, supaya tidak pernah basi.
4. **Open Graph ikut title dan description ini** kecuali diisi sendiri. Yang perlu dibedakan
   cuma gambar: OG image pakai foto sesi, bukan logo.
5. **Nol duplikat.** Cek ulang lewat Rank Math setelah semua halaman jadi, karena halaman arsip
   taksonomi `format-acara` dan `kota` gampang menghasilkan title yang mirip halaman Jadwal.
   Rekomendasi: arsip taksonomi diberi `noindex` sampai isinya cukup banyak untuk berdiri sendiri.
