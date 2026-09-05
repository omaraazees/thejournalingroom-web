# Struktur URL · The Journaling Room

Domain belum ada. `[PERLU DIKONFIRMASI: nama domain final]`
Semua contoh di bawah pakai `tjr.id` sebagai penanda sementara, tinggal ganti.

## Prinsip

1. **URL dibaca manusia dulu, mesin belakangan.** Orang menyalin link sesi ke grup WhatsApp,
   dan link yang isinya `?p=482` bikin orang ragu mengkliknya.
2. **Pendek, tapi jangan sampai kehilangan arti.**
3. **Nol tanggal di URL.** Halaman acara dan artikel panduan umurnya panjang. Tanggal di URL
   bikin isinya kelihatan basi padahal masih relevan.
4. **Slug tidak pernah diubah setelah terbit.** Kalau terpaksa, wajib pasang 301.
5. **Semua huruf kecil, pemisah tanda hubung.** Nol garis bawah, nol spasi, nol huruf besar.

---

## Peta lengkap

| Tipe konten | Pola URL | Contoh |
|---|---|---|
| Beranda | `/` | `tjr.id/` |
| Arsip acara | `/jadwal/` | `tjr.id/jadwal/` |
| Satu acara | `/jadwal/{slug-acara}/` | `tjr.id/jadwal/tracing-shadows-mapping-stars/` |
| Filter format | `/format/{slug}/` | `tjr.id/format/workshop/` |
| Filter kota | `/kota/{slug}/` | `tjr.id/kota/yogyakarta/` |
| Indeks blog | `/cerita/` | `tjr.id/cerita/` |
| Satu artikel | `/cerita/{slug-artikel}/` | `tjr.id/cerita/cara-mulai-journaling/` |
| Kategori blog | `/cerita/kategori/{slug}/` | `tjr.id/cerita/kategori/panduan-journaling/` |
| Tentang | `/tentang/` | `tjr.id/tentang/` |
| Kolaborasi | `/kolaborasi/` | `tjr.id/kolaborasi/` |
| Kontak | `/kontak/` | `tjr.id/kontak/` |
| Galeri | `/galeri/` | `tjr.id/galeri/` |
| Halaman 2 dan seterusnya | `/{apa pun}/page/2/` | `tjr.id/cerita/page/2/` |
| Testimoni | tidak punya URL | dipanggil di halaman lain, tidak diindeks |

---

## Pengaturan di WordPress

### Permalink bawaan

Settings, Permalinks, pilih **Custom Structure**:

```
/cerita/%postname%/
```

Category base diisi:

```
cerita/kategori
```

Tag base dikosongkan karena tag tidak dipakai. Kalau nanti dipakai, isi `cerita/tag`.

### CPT acara

Didaftarkan di `functions.php` child theme, argumen yang menentukan URL:

```
'has_archive' => 'jadwal',
'rewrite'     => array( 'slug' => 'jadwal', 'with_front' => false ),
```

Dengan begitu arsipnya `/jadwal/` dan tiap acara ada di bawahnya, satu jalur yang konsisten.

> **Gotcha yang gampang bikin halaman hilang.** Kalau `has_archive` diisi `jadwal`, **jangan
> bikin halaman statis dengan slug `jadwal`.** Dua duanya berebut URL yang sama, dan yang
> menang biasanya halaman statisnya, jadi arsip acara tiba tiba kosong. Kalau menu butuh item
> bernama Jadwal, arahkan langsung ke `/jadwal/` sebagai custom link, bukan ke halaman.

Sesudah mengubah pendaftaran CPT, buka sekali Settings, Permalinks, lalu klik Save.
Tanpa itu URL barunya 404 walau kodenya sudah benar.

### Taksonomi

```
format-acara : 'rewrite' => array( 'slug' => 'format', 'with_front' => false )
kota         : 'rewrite' => array( 'slug' => 'kota',   'with_front' => false )
```

Slug taksonomi sengaja tunggal dan pendek. `format-acara` itu nama internal supaya tidak bentrok
dengan istilah lain, tapi yang muncul di URL cukup `format`.

---

## Aturan slug

### Untuk acara

Judul tema acara berbahasa Inggris dan puitis, jadi slug diturunkan apa adanya, cuma dibersihkan.

| Judul acara | Slug |
|---|---|
| Tracing Shadows, Mapping Stars | `tracing-shadows-mapping-stars` |
| About Myself | `about-myself` |
| Between the Pages | `between-the-pages` |
| Much Between the Lines | `much-between-the-lines` |
| A Gentle Space to Write and Glow | `a-gentle-space-to-write-and-glow` |
| Brush Lettering & Journaling | `brush-lettering-dan-journaling` |
| A Moment Between Chapters: 2026 Half-year Reflection | `a-moment-between-chapters` |
| Journaling for a heartfelt Ramadhan | `journaling-ramadhan` |

Aturannya:

1. **Buang tanda baca.** Koma, titik dua, tanda seru hilang.
2. **Ganti `&` jadi `dan`.** Karakter `&` di URL harus di-encode jadi `%26` dan itu jelek
   di WhatsApp.
3. **Potong anak judul.** Apa pun setelah titik dua dibuang. `A Moment Between Chapters:
   2026 Half-year Reflection` cukup jadi `a-moment-between-chapters`.
4. **Maksimal 60 karakter.** Kalau lebih, potong di batas kata, jangan di tengah kata.
5. **Buang kata sambung yang tidak membedakan arti** kalau slug kepanjangan. `for a heartfelt`
   boleh hilang, `Between the Pages` jangan, karena itu memang judulnya.

### Kalau tema yang sama diulang

Ini pasti terjadi, karena tema bagus biasanya dijalankan lagi. WordPress akan otomatis membuat
`about-myself-2`, dan itu jelek serta tidak memberi tahu apa apa.

**Aturannya: tambahkan bulan dan tahun, bukan angka.**

```
about-myself                  sesi pertama
about-myself-oktober-2026     sesi kedua
about-myself-maret-2027       sesi ketiga
```

Alasannya dua. Orang yang melihat link di grup WhatsApp langsung tahu itu sesi yang mana, dan
sesi lama tetap punya URL sendiri yang bisa dipakai sebagai halaman dokumentasi.

**Yang jangan dilakukan:** mengganti tanggal di acara lama lalu memakainya ulang untuk sesi baru.
Itu menghapus arsip, mematahkan link yang sudah tersebar, dan bikin schema `Event` menunjuk
tanggal yang salah.

### Untuk artikel

Slug artikel **bahasa Indonesia**, pendek, dan mengandung keyword utamanya.

| Judul artikel | Slug |
|---|---|
| Cara mulai journaling waktu belum tahu harus nulis apa | `cara-mulai-journaling` |
| Tiga puluh pemantik journaling untuk hari yang kepalanya penuh | `pemantik-journaling` |
| Kegiatan sendirian di Jogja yang nggak bikin canggung | `kegiatan-sendirian-di-jogja` |
| Bedanya diary dan journaling | `bedanya-diary-dan-journaling` |
| Journaling waktu lagi overthinking | `journaling-untuk-overthinking` |
| Alat journaling untuk pemula | `alat-journaling-pemula` |
| Di balik sesi: gimana satu tema disiapkan | `di-balik-sesi-menyiapkan-tema` |
| Sesi journaling di acara brand | `sesi-journaling-untuk-brand` |

Aturannya:

1. **Slug bukan judul.** Judul boleh panjang dan enak dibaca, slug cukup inti keyword-nya.
2. **Buang kata seperti `yang`, `waktu`, `untuk`** kalau tidak mengubah arti. `untuk` dipertahankan
   di `journaling-untuk-overthinking` karena memang bagian dari cara orang mengetik.
3. **Nol angka di depan.** `30-pemantik-journaling` bikin URL terlihat seperti listicle yang
   akan basi begitu daftarnya ditambah.
4. **Maksimal 50 karakter.**

### Untuk taksonomi

| Nama tampil | Slug |
|---|---|
| Journaling Workshop | `workshop` |
| Brush Lettering Class | `lettering` |
| Journaling Playdate | `playdate` |
| Inner Circle | `inner-circle` |
| Kolaborasi | `kolaborasi` |
| Brand Activation | `brand-activation` |
| Yogyakarta | `yogyakarta` |
| Magelang | `magelang` |
| Jakarta | `jakarta` |

Kota pakai nama resmi, bukan `jogja`, supaya konsisten dengan alamat venue dan schema `Event`.
Kata `jogja` tetap dipakai di judul, deskripsi, dan isi halaman, karena itu yang diketik orang.

---

## Aturan teknis lain

| Hal | Keputusan |
|---|---|
| Garis miring di akhir | **Ada.** `/tentang/` bukan `/tentang`. Konsisten, dan itu bawaan WordPress |
| www atau tanpa www | **Tanpa www.** Pilih satu, lalu paksa lewat redirect di Hostinger |
| http atau https | **https saja.** Semua http dialihkan 301 |
| Huruf besar di URL | Dialihkan ke huruf kecil |
| Arsip taksonomi | `noindex` dulu sampai tiap taksonomi punya minimal lima acara. Sebelum itu isinya terlalu mirip halaman Jadwal |
| Arsip penulis | Dimatikan. Cuma ada dua penulis dan halamannya kosong makna |
| Arsip tanggal | Dimatikan. Tidak ada gunanya untuk situs ini dan cuma menghasilkan halaman tipis |
| Halaman hasil pencarian | `noindex` |
| Attachment page | Dimatikan, dialihkan ke file atau ke induknya |

## Kalau nanti pindah domain atau ganti slug

1. Catat dulu semua URL lama, ambil dari sitemap XML.
2. Pasang 301 satu per satu ke URL baru yang paling dekat maknanya, bukan semua ke beranda.
   Mengarahkan semua ke beranda itu cara tercepat kehilangan peringkat yang sudah didapat.
3. Perbarui link di bio Instagram dan di pesan WhatsApp otomatis.
4. Kirim ulang sitemap di Search Console, lalu pantau laporan Coverage selama sebulan.
