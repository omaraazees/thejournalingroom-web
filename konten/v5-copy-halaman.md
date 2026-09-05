# Copy halaman v5 · The Journaling Room

Transkrip lengkap semua teks yang ada di `desain/prototipe/v5-fieldtime.html`.
Dipakai sebagai acuan waktu mengisi konten di WordPress: kolom **Teks sekarang** disalin apa
adanya dari prototipe, kolom **Catatan** berisi hal yang perlu diputuskan atau diperbaiki
sebelum dipasang.

## Cara baca

| Tanda | Artinya |
|---|---|
| **PLACEHOLDER** | Isinya karangan untuk keperluan desain. Wajib diganti data asli sebelum terbit |
| **PERBAIKI** | Ada yang salah atau perlu diubah sebelum masuk WordPress |
| **DINAMIS** | Nanti diisi otomatis dari field ACF, bukan diketik manual |

## Dua hal yang berlaku di seluruh halaman

**Nomor WhatsApp sudah dibetulkan di prototipe.** Nomor yang benar `0857 2022 5369`,
link `https://wa.me/6285720225369`. Sekarang terpasang di enam tempat: tombol header, kartu
sesi terdekat, tombol sesi terdekat, tombol ajakan, footer, dan tombol mengambang. Nomor lama
`0857 2822 5369` **tidak boleh dipakai lagi di mana pun**, termasuk di bio Instagram, di
Google Business Profile, dan di pesan yang sudah terlanjur beredar. Cek ulang sekali lagi
waktu memindahkannya ke WordPress, karena nomor salah di tombol utama membuang semua trafik
yang berhasil didatangkan.

**Semua jadwal yang akan datang masih placeholder.** Empat sesi mendatang di prototipe
(Tracing Shadows Mapping Stars, Much Between the Lines, Journaling Playdate, A Moment Between
Chapters) beserta tanggal, jam, venue, jumlah kursi, dan sisa slotnya adalah isian contoh.
Yang datanya asli cuma bagian arsip dan daftar kolaborator.

---

## 1. Kepala dokumen

| Elemen | Teks sekarang | Catatan |
|---|---|---|
| `<title>` | The Journaling Room | **PERBAIKI.** Terlalu pendek dan tidak membawa keyword. Pakai `Workshop Journaling Jogja \| The Journaling Room`. Lihat `v5-meta.md` |
| `<meta name="description">` | Workshop journaling di Yogyakarta. Datang tanpa pengalaman, alat tulis kami siapkan, dan tidak ada giliran bercerita. Satu meja panjang, satu sore. | Bagus, tapi diganti versi final di `v5-meta.md` supaya panjangnya masuk rentang 140 sampai 158 |

## 2. Tirai pembuka

| Elemen | Teks sekarang | Catatan |
|---|---|---|
| Logo di tirai | tidak ada teks, `alt=""` | Benar. Tirai `aria-hidden`, jadi tidak dibaca pembaca layar. Biarkan. Lapisan animasi di sekitarnya, `jatuh`, `sampul`, dan `punggung`, semuanya kosong dari teks |

## 3. Header

| Elemen | Teks sekarang | Catatan |
|---|---|---|
| Logo, label link | The Journaling Room, ke atas | `aria-label` di elemen `<a>`. Di WordPress, arahkan ke `/` bukan ke `#` |
| Menu 1 | Tentang | Jadi link ke `/tentang/` |
| Menu 2 | Jadwal | Jadi link ke `/jadwal/` |
| Menu 3 | Galeri | Jadi link ke `/galeri/` |
| Menu 4 | Cetakan | **PERBAIKI.** Di situs asli, seksi Cetakan tidak berdiri sebagai halaman sendiri. Hapus dari menu atau gabung ke Galeri |
| Menu 5 | Arsip | Jadi link ke `/kolaborasi/` atau ke arsip acara yang sudah lewat |
| Menu 6 | Kolaborator | Sama tujuannya dengan Arsip. Pilih salah satu, dua item menu ke halaman sama membingungkan |
| Tombol kanan | WhatsApp | Link ke `https://wa.me/6285720225369`, sudah benar |
| Menu mobile | tidak ada | Nav disembunyikan di bawah 900px dan tidak ada penggantinya. Butuh menu mobile sebelum terbit |

## 4. Hero

| Elemen | Teks sekarang | Catatan |
|---|---|---|
| Tombol kiri 1 | Jadwal terdekat | Anchor ke seksi jadwal |
| Tombol kiri 2 | Lihat dokumentasi | Anchor ke seksi galeri |
| Eyebrow | Workshop journaling · Yogyakarta | Ini satu satunya tempat keyword utama muncul di area atas. Pertahankan |
| H1 | Your kind journaling companion | **PERBAIKI.** Ini tagline Instagram, bukan judul halaman. Nol keyword, dan bahasanya Inggris di situs berbahasa Indonesia. Usul ganti: `Workshop journaling di Jogja untuk yang belum tahu mau menulis apa`. Tagline lama dipindah jadi eyebrow atau kutipan. Lihat `v5-heading-outline.md` |
| Judul di atas foto | Kelas journaling di Jogja untuk yang belum tahu mau menulis apa | Sekarang `<h2>`. Kalau H1 diganti seperti usul di atas, kalimat ini jadi mubazir. Ganti jadi kalimat pendukung tanpa heading |
| Paragraf di atas foto | Kertasnya kosong, jamnya pelan. Alat tulis sudah kami siapkan, dan tidak ada giliran bercerita di depan orang. | Bagus, tidak perlu diubah |
| Label kartu | Sesi terdekat | **DINAMIS.** Muncul kalau ada acara mendatang |
| Judul kartu | Tracing Shadows, Mapping Stars | **PLACEHOLDER · DINAMIS.** Dari judul CPT acara |
| Baris tanggal | Sabtu, 20 September | **PLACEHOLDER · DINAMIS** |
| Baris jam | 15.00 sampai 18.00 | **PLACEHOLDER · DINAMIS** |
| Baris tempat | Kupiku Coffee, Jogja | **PLACEHOLDER · DINAMIS** |
| Baris kursi | 11 dari 15 kursi terisi | **PLACEHOLDER · DINAMIS.** Angka dihitung dari kapasitas dikurangi slot terisi |
| Tombol kartu | Tanya slot lewat WhatsApp | Teks tombolnya sudah pas, tidak menjanjikan pembayaran online yang belum ada. Nomornya sudah benar |

## 5. Pengantar

| Elemen | Teks sekarang | Catatan |
|---|---|---|
| Eyebrow | Tentang ruangnya | |
| Judul seksi | Yang tumbuh di meja panjang | **PERBAIKI.** Sekarang ditulis pakai `<p>`, bukan heading. Harus jadi `<h2>` |
| Paragraf 1 | The Journaling Room menggelar workshop journaling dan kelas menulis jurnal di Yogyakarta sejak November 2025. Sepuluh kali, dari kedai kopi sampai pendopo tua, selalu dengan pola yang sama: satu meja panjang, bahan yang sudah ditata rapi, dan waktu yang tidak diburu. | Fakta benar. "Sepuluh kali" wajib diperbarui tiap ada sesi baru, atau dibuat dinamis dari jumlah post |
| Paragraf 2 | Tidak ada sesi perkenalan yang bikin kaku. Kamu boleh menulis, menempel, atau cuma memegang gunting sambil melihat orang lain bekerja. Sorenya selesai kalau kamu merasa selesai. | Bagus |
| Tombol | Sepuluh kolaborasi | Angkanya ikut berubah kalau jumlah kolaborasi bertambah |

## 6. Foto dan kutipan

| Elemen | Teks sekarang | Catatan |
|---|---|---|
| Kutipan | Halaman kosong tidak pernah menuntut apa apa | Tanda kutip pembuka di sebelahnya sudah `aria-hidden`, benar |
| Keterangan polaroid | Pendopo Radian | Ada di dalam `figure` ber-`aria-hidden`, jadi tidak terbaca pembaca layar. Lihat `v5-alt-text.md` |
| Stiker bulat | tidak ada teks | Dekoratif. Biarkan `alt=""` |

## 7. Jadwal

| Elemen | Teks sekarang | Catatan |
|---|---|---|
| Eyebrow | Jadwal | |
| Judul seksi | Sore yang sudah dijadwalkan | **PERBAIKI.** Harus jadi `<h2>` |
| Pengantar | Jadwal workshop journaling Jogja yang berikutnya. Kursinya sengaja dibatasi supaya semua kebagian meja, jadi tanya slot dulu lewat WhatsApp sebelum datang. | Keyword `jadwal workshop journaling jogja` sudah masuk secara wajar |
| Tanda foto | Sesi terdekat | **DINAMIS** |
| Judul sesi | Tracing Shadows, Mapping Stars | **PLACEHOLDER · DINAMIS.** Sekarang `<h3>`, harusnya `<h3>` di bawah `<h2>` seksi yang belum ada |
| Deskripsi sesi | Tiga jam untuk yang belum pernah journaling sama sekali. Kami mulai dari satu pertanyaan pendek, lanjut ke tata halaman, lalu waktu bebas sampai lemnya kering. Tidak ada giliran bercerita, tidak ada halaman yang salah. | **DINAMIS** dari field deskripsi ACF. Nada tulisannya jadi contoh yang bagus untuk sesi berikutnya |
| Fakta, Tanggal | Sabtu, 20 September 2026 | **PLACEHOLDER · DINAMIS.** Sudah pakai `<time datetime="2026-09-20">`, pertahankan |
| Fakta, Waktu | 15.00 sampai 18.00 WIB | **PLACEHOLDER · DINAMIS** |
| Fakta, Tempat | Kupiku Coffee, Mantrijeron, Yogyakarta | **PLACEHOLDER · DINAMIS.** Tambahkan link Google Maps di sini |
| Fakta, Format | Workshop dipandu, 15 kursi | **PLACEHOLDER · DINAMIS** |
| Fakta, Yang disediakan | Jurnal, stiker, booklet prompt, deco station, satu minuman | **DINAMIS.** Isi kit ini cocok dengan yang terlihat di foto dokumentasi |
| Fakta, Yang perlu dibawa | Tidak ada. Boleh bawa jurnal sendiri kalau mau | Menjawab keberatan pemula, pertahankan |
| Bar kursi | 11 dari 15 kursi sudah terisi | **PLACEHOLDER · DINAMIS.** Muncul dua kali, di `aria-label` bar dan di teks bawahnya. Cukup satu yang dibaca, satunya beri `aria-hidden` |
| Harga | tidak ada | **PERBAIKI.** Harga sama sekali tidak muncul di halaman, padahal `harga workshop journaling` termasuk keyword yang dikejar dan harga adalah hal pertama yang ditanya orang. Tambahkan satu baris fakta harga |
| Label kartu berikutnya | Setelah itu | |
| Kartu 1, judul | Much Between the Lines | **PLACEHOLDER · DINAMIS** |
| Kartu 1, keterangan | 28 Sep · Artotel, Yogyakarta | **PLACEHOLDER · DINAMIS** |
| Kartu 1, deskripsi | Belajar menata huruf sampai halaman terasa punya suara sendiri. | **PLACEHOLDER** |
| Kartu 1, baris kecil | 14.00 sampai 17.00 · 12 kursi · alat tulis disediakan · 5 kursi tersisa | **PLACEHOLDER · DINAMIS** |
| Kartu 2, judul | Journaling Playdate | **PLACEHOLDER · DINAMIS** |
| Kartu 2, keterangan | 04 Okt · Kolondjono, Yogyakarta | **PLACEHOLDER · DINAMIS** |
| Kartu 2, deskripsi | Tanpa materi, tanpa target. Datang, duduk, tulis apa saja. | **PLACEHOLDER** |
| Kartu 2, baris kecil | 15.30 sampai 18.00 · 20 kursi · boleh bawa jurnal sendiri · waitlist | **PLACEHOLDER · DINAMIS** |
| Kartu 3, judul | A Moment Between Chapters | **PLACEHOLDER · DINAMIS** |
| Kartu 3, keterangan | 18 Okt · Pasar Jakal, Yogyakarta | **PLACEHOLDER · DINAMIS** |
| Kartu 3, deskripsi | Sesi panjang untuk menutup tahun, satu halaman untuk tiap bulan. | **PLACEHOLDER** |
| Kartu 3, baris kecil | 14.00 sampai 18.00 · 25 kursi · kit lengkap · baru dibuka | **PLACEHOLDER · DINAMIS** |
| Tautan tiga kartu | seluruh kartu jadi link ke WhatsApp | **PERBAIKI.** Kartu acara sebaiknya menuju halaman detail acara, bukan langsung ke WhatsApp. Kalau langsung ke WhatsApp, halaman detail acara tidak pernah dikunjungi dan schema `Event` kehilangan gunanya |

## 8. Galeri

| Elemen | Teks sekarang | Catatan |
|---|---|---|
| Eyebrow | Dokumentasi | |
| Judul seksi | Apa yang tertinggal di meja | **PERBAIKI.** Harus jadi `<h2>` |
| Pengantar | Foto dokumentasi dari sepuluh workshop journaling yang sudah kami gelar di Yogyakarta. | Angka sepuluh ikut arsip, konsisten |
| Tombol | Semua kolaborasi | |
| Keterangan foto 1 | Radian | |
| Keterangan foto 2 | Sunday Reads Club | |
| Keterangan foto 3 | Wardah | |
| Keterangan foto 4 | Pasar Jakal | |

## 9. Cetakan

| Elemen | Teks sekarang | Catatan |
|---|---|---|
| Eyebrow | Cetakan | |
| Judul seksi | Sore sore yang sudah lewat | **PERBAIKI.** Harus jadi `<h2>` |
| Pengantar | Setiap kelas journaling meninggalkan setumpuk foto di meja. Ini sebagiannya, dari sembilan kolaborasi yang berbeda. | Angkanya benar, di tumpukan memang ada sembilan foto. Yang tidak ikut cuma Statement Beauty, karena dokumentasinya cuma dua foto |
| Keterangan 1 | Sunday Reads | Di arsip ditulis lengkap `Sunday Reads Club`. Samakan |
| Keterangan 2 | Radian | |
| Keterangan 3 | Wardah | |
| Keterangan 4 | Artotel | |
| Keterangan 5 | Kolondjono | |
| Keterangan 6 | AMCO x Naoki | Di arsip ditulis `AMCO Bakehouse × Naoki Pics`. Samakan |
| Keterangan 7 | Snapobox | |
| Keterangan 8 | Pasar Jakal | |
| Keterangan 9 | Kupiku | Di arsip ditulis `Kupiku Coffee`. Samakan |

## 10. Arsip

| Elemen | Teks sekarang | Catatan |
|---|---|---|
| Eyebrow | Sudah lewat | |
| Judul seksi | Sepuluh kali duduk bareng | **PERBAIKI.** Harus jadi `<h2>`. Sekarang sepuluh `<h3>` di bawahnya menggantung tanpa induk |
| Pengantar | Tanggalnya diambil dari metadata foto dokumentasi, jadi urutannya persis seperti yang terjadi. | Kalimat ini jujur dan bagus, pertahankan |
| Baris 1 | 30 Agu 2026 · TJR × Snapobox · 14 foto · Lihat foto | Data asli |
| Baris 2 | 29 Agu 2026 · TJR × Pasar Jakal · 15 foto · Lihat foto | Data asli |
| Baris 3 | 2 Agu 2026 · TJR × AMCO Bakehouse × Naoki Pics · 8 foto · Lihat foto | Data asli |
| Baris 4 | 4 Jul 2026 · TJR × Kolondjono · 26 foto · Lihat foto | Data asli |
| Baris 5 | 18 Jun 2026 · TJR × Statement Beauty · 2 foto · Lihat foto | Data asli |
| Baris 6 | 11 Apr 2026 · TJR × Artotel · 20 foto · Lihat foto | Data asli |
| Baris 7 | 21 Feb 2026 · TJR × Wardah · 12 foto · Lihat foto | Data asli |
| Baris 8 | 11 Jan 2026 · TJR × Kupiku Coffee · 5 foto · Lihat foto | Data asli |
| Baris 9 | 30 Nov 2025 · TJR × Radian · 33 foto · Lihat foto | Data asli |
| Baris 10 | 9 Nov 2025 · TJR × Sunday Reads Club · 31 foto · Lihat foto | Data asli |
| Tujuan link | semua baris menuju `#galeri` | **PERBAIKI.** Sepuluh link dengan tujuan sama dan teks sama, `Lihat foto`. Tiap baris harus menuju halaman kolaborasinya sendiri, dan teks linknya dibuat unik, misalnya `Lihat 14 foto sesi Snapobox` |

## 11. Kolaborator

| Elemen | Teks sekarang | Catatan |
|---|---|---|
| Eyebrow | Pernah bareng | |
| Judul seksi | Sebelas nama di meja | **PERBAIKI.** Harus jadi `<h2>`. Angkanya benar: sebelas logo kolaborator plus logo TJR sendiri, total dua belas gambar |
| Nama logo | Sunday Reads Club, Radian, Kupiku Coffee, Wardah, Artotel, Hanasui, Heejaz, Statement Beauty, AMCO Bakehouse, Pasar Jakal, Snapobox, The Journaling Room | Kolondjono tidak ada karena logonya belum ada. Kalau nanti dikirim, tambahkan dan ubah judul jadi dua belas |

## 12. Ajakan penutup

| Elemen | Teks sekarang | Catatan |
|---|---|---|
| Eyebrow | Sampai ketemu di ruangnya | |
| Judul seksi | Bawa dirimu saja | **PERBAIKI.** Harus jadi `<h2>` |
| Paragraf | Jurnal, alat tulis, dan bahan tempel sudah menunggu di meja. Kalau mau bawa jurnal sendiri juga boleh. Tanya slot lewat WhatsApp, dibalas 09.00 sampai 21.00. | Jam balas disebut, bagus. Pastikan jamnya memang benar |
| Tombol | Tanya slot lewat WhatsApp | Nomornya sudah benar |

## 13. Footer

| Elemen | Teks sekarang | Catatan |
|---|---|---|
| Logo, label link | The Journaling Room, ke atas | Arahkan ke `/` di WordPress |
| Judul kolom 1 | Halaman | Pakai `<h2>` tersembunyi atau `aria-label` di `<nav>` supaya daftar linknya punya nama |
| Kolom 1 | Tentang kami, Jadwal sesi, Galeri dokumentasi, Cetakan | Item `Cetakan` ikut dihapus kalau seksi Cetakan tidak jadi halaman |
| Judul kolom 2 | Dokumentasi | |
| Kolom 2 | Sepuluh kolaborasi, Daftar kolaborator | |
| Judul kolom 3 | Hubungi kami | |
| Kolom 3, WhatsApp | WhatsApp 0857 2022 5369 | Sudah benar. Ini satu satunya tempat nomornya ditulis terbaca, jadi pastikan tetap sama persis dengan yang didaftarkan di Google Business Profile |
| Kolom 3, Instagram | Instagram | Sebut handle-nya, `Instagram @thejournalingroom`, supaya teks linknya berarti sendiri |
| Kolom 3, lokasi | Mantrijeron, Yogyakarta | Menuju Google Maps. Pastikan pin-nya sama dengan Google Business Profile |
| Baris bawah | 2026 · The Journaling Room | Tambahkan simbol hak cipta kalau perlu |
| Baris bawah | Yogyakarta | |
| Yang belum ada | Kebijakan privasi, syarat, dan alamat email | Halaman kebijakan privasi wajib ada kalau nanti pasang Google Analytics atau form kontak |

## 14. Tombol mengambang

| Elemen | Teks sekarang | Catatan |
|---|---|---|
| Label | Tanya slot lewat WhatsApp | `aria-label` |
| Teks tampil | Tanya slot | Muncul setelah gulir 60 persen tinggi layar. Nomornya sudah benar |

---

## Ringkasan yang harus dikerjakan sebelum masuk WordPress

1. Cek ulang nomor WhatsApp di enam tempat waktu memindahkannya ke WordPress.
2. Ganti H1 supaya membawa keyword, lalu rapikan heading seksi jadi `<h2>` sungguhan.
3. Ganti semua data acara mendatang dengan data asli, atau kosongkan seksinya sampai ada jadwal.
4. Tambahkan harga di kartu dan di detail acara.
5. Arahkan kartu acara ke halaman detail acara, bukan langsung ke WhatsApp.
6. Bikin teks link arsip jadi unik, satu baris satu tujuan.
7. Putuskan nasib menu `Cetakan` dan `Kolaborator` supaya tidak ada dua item menu ke tempat sama.
8. Siapkan menu mobile.
