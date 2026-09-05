# LAPORAN A1 · Halaman Dalam

Semua file ada di `journaling-room-web/pages/`. Nol file di lane lain disentuh.

## Yang dibuat

| File | Isi | Baris |
|---|---|---|
| `jadwal.html` | Arsip semua acara, filter format dan kota, pemisah mendatang lawan arsip | 206 |
| `acara-detail.html` | Satu sesi: hero foto, tanggal, venue plus Maps, harga, isi kit, sisa slot, tombol WhatsApp, galeri, sesi mirip | 192 |
| `tentang.html` | Cerita Caca dan Dhanty, kutipan feed, cara kerja sesi, nilai brand, angka | 158 |
| `kolaborasi.html` | Tiga format B2B, contoh kerja Wardah dan Artotel dan Sunday Reads Club, isi paket, form brief | 224 |
| `kontak.html` | WhatsApp, Instagram, jam balas, area layanan tiga kota, FAQ enam butir | 156 |
| `cerita.html` | Indeks blog, filter tiga kategori, satu artikel unggulan, sembilan kartu | 194 |
| `cerita-detail.html` | Satu artikel, lebar baca 65ch, daftar isi, artikel terkait | 193 |
| `pages.css` | CSS bersama untuk header, footer, dan komponen yang dipakai lintas halaman | 129 |

## Hasil pemeriksaan

| Yang dicek | Hasil |
|---|---|
| Em dash | 0 |
| Frasa AI slop | 0 |
| H1 per halaman | tepat 1 di ketujuh halaman |
| Lompatan heading | 0 |
| Link lokal putus | 0 |
| Warna teks di bawah 4.5:1 | 0 |
| Target sentuh di bawah 44px | 0 |
| Tag blok tidak seimbang | 0 |
| Title lebih dari 60 karakter | 0 |
| Meta description lebih dari 155 karakter | 0 |

## Keputusan yang diambil

**1. Bikin satu file `pages.css`, tidak ada di daftar brief.**
Header, footer, baris acara, chip filter, FAQ, dan blok penutup identik di tujuh halaman. Menyalinnya tujuh kali bikin perbaikan satu hal harus dikerjakan tujuh kali. Isinya diangkat apa adanya dari `final-beranda.html`, **nol nilai baru**: tidak ada warna, font, atau langkah spacing yang saya karang. Nanti A2 tinggal memecahnya, sebagian ke `theme.json` dan sisanya ke `assets/tjr.css`.

**2. Judul kolom footer diubah dari `h4` jadi `h2`.**
Urutan heading tiap halaman jadi h2 lalu langsung h4 di footer, dan itu lompatan yang ditandai axe sebagai `heading-order`. Karena `footer` itu landmark sederajat dengan `main`, heading di dalamnya memang seharusnya mulai dari h2. Tampilannya dijaga sama persis lewat aturan `.fgrid h2` yang menyalin isi aturan `footer h4` di `tokens.css`, jadi nol perbedaan visual.
**Ini bikin markup footer saya beda satu tag dari `final-beranda.html`.** Beranda dan template-part A2 perlu ikut supaya konsisten.

**3. Warna teks `kraft` dan `sage` diganti.**
Diukur di atas latar `paper`:

| Warna | Rasio | Status |
|---|---|---|
| `--kraft` `#B08968` | 2.94:1 | gagal AA |
| `--sage` `#93A181` | 2.55:1 | gagal AA |
| `--ink-soft` `#6B5847` | 6.27:1 | lolos |
| `--burgundy` `#7C2D2D` | 8.59:1 | lolos |

Dua-duanya tetap dipakai sebagai garis dan latar, cuma tidak lagi jadi warna teks. **Dua di antaranya kebawa dari `final-beranda.html`**: `.ev .r .s` (teks sisa slot, sage) dan `.faq summary::after` (penanda buka tutup, kraft). Beranda perlu ikut diperbaiki, kalau tidak halaman itu sendiri yang gagal audit.

**4. Filter jadwal dua sumbu, 12 baris vanilla JS.**
Format dan kota bisa dipakai barengan. Nol library. Jumlah hasil diumumkan lewat `role="status"` supaya kebaca screen reader, dan ada pesan khusus kalau hasilnya kosong. Filter kategori di `cerita.html` polanya sama, 6 baris.

**5. Form brief di halaman kolaborasi menyusun pesan WhatsApp, tidak menyimpan data.**
TJR sekarang memang jalan manual lewat WhatsApp, jadi form yang mengirim ke database yang belum ada cuma bikin brief hilang. Tombolnya merangkai isian jadi satu pesan lalu membuka WhatsApp. Ada tulisan jelas di bawah tombol bahwa belum ada data yang tersimpan di situs.

**6. Angka harga cuma memakai yang ada di `brand-brief.md`.**
Yang dipakai: 40K, 75K, 89K, 98K, 100K, 125K. Tidak ada harga baru yang saya karang. Tanggal, sisa slot, dan pasangan venue disusun sebagai contoh, dan itu ditulis terang-terangan di kotak catatan bawah halaman jadwal.

**7. Judul artikel ditandai Draf.**
Sembilan judul di `cerita.html` disusun supaya tiga kategorinya kelihatan terisi. Semuanya diberi label Draf, dan ada catatan bahwa judul final datang dari `konten/outline-blog.md`. Artikel contoh di `cerita-detail.html` ditulis lengkap supaya lebar baca 65ch dan daftar isinya bisa benar-benar diuji.

**8. Title dan meta description ditulis sementara, sudah dalam batas.**
Semua di bawah 60 dan 155 karakter supaya halaman sudah sah walau A3 belum selesai. Versi final tetap punya A3 di `konten/meta-per-halaman.md`.

## Penanda blok untuk A2

Yang dipakai ulang dari beranda: `tjr/inclusions`, `tjr/gallery-rail`, `tjr/about`, `tjr/stats`, `tjr/faq`, `tjr/cta`.

Yang baru dan perlu didaftarkan: `tjr/page-header`, `tjr/schedule-filters`, `tjr/event-hero`, `tjr/event-rundown`, `tjr/quote`, `tjr/founders`, `tjr/principles`, `tjr/b2b-hero`, `tjr/b2b-formats`, `tjr/case-studies`, `tjr/b2b-deliverables`, `tjr/brief-form`, `tjr/contact-channels`, `tjr/service-area`, `tjr/post-header`, `tjr/post-body`.

Dua tempat pakai query loop, bukan pattern: daftar acara di `jadwal.html` dan daftar artikel di `cerita.html`. Halaman detail memakai template single.

## Yang masih nunggu orang

**Nunggu Caca dan Dhanty**
- Foto asli. Semua kotak `.ph` di tujuh halaman masih placeholder berlabel, dan foto candid itu aset paling kuat TJR
- Angka 240 peserta masih perkiraan dari dokumentasi sesi, belum dihitung dari catatan pendaftaran. Sudah saya tandai di halaman Tentang
- Rata-rata waktu balas WhatsApp masih perkiraan, belum diukur dari riwayat chat. Sudah saya tandai di halaman Kontak
- Hasil tiap kolaborasi brand: jumlah peserta, jangkauan, foto dokumentasi. Belum ada satu pun, dan halaman Kolaborasi susah dipakai pitching tanpa itu
- Logo resolusi tinggi dan domain

**Nunggu lane lain**
- A2: ikut memperbaiki heading footer dan dua warna teks di `final-beranda.html`, lalu memecah `pages.css`
- A3: title dan meta final, outline blog, copy final Tentang dan Kolaborasi dan Kontak
- A4: data acara asli, logika status otomatis, dan link WhatsApp yang dibangun dari field, supaya link statis di halaman detail bisa diganti

**Catatan kecil**
Ejaan "Journaling" satu L dipakai konsisten di semua halaman, mengikuti Instagram. Kalau ternyata ejaan resminya beda, satu kali cari ganti di tujuh file sudah cukup.
