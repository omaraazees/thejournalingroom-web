# Laporan lane A3 · Konten dan SEO

Ditulis ke `konten/` dan satu file laporan ini. **Nol file di luar dua tempat itu yang kusentuh.**
Folder `pages/`, `wp-theme/`, dan `cms/` sekarang berisi, tapi itu hasil kerja lane lain yang
jalan bersamaan, bukan aku. Waktu aku mulai ketiganya masih kosong.

## Yang dibuat

| File | Isi | Baris |
|---|---|---|
| `konten/keyword-research.md` | 5 kelompok keyword per intent, 38 keyword, prioritas 6 bulan, daftar verifikasi | 160 |
| `konten/meta-per-halaman.md` | Title dan description 8 halaman utama, plus pola otomatis CPT acara | 180 |
| `konten/copy-halaman.md` | Copy final Tentang, Kolaborasi, Kontak | 256 |
| `konten/outline-blog.md` | 8 artikel: judul, keyword, outline H2, panjang, link internal, urutan terbit | 284 |
| `konten/struktur-url.md` | Pola permalink semua tipe konten, aturan slug, pengaturan WordPress | 199 |

## Keputusan yang kuambil

**1. Nol angka volume pencarian di riset keyword.**
Aku tidak punya akses ke Keyword Planner, Ahrefs, maupun Search Console TJR. Menulis
"320 pencarian per bulan" cuma akan jadi angka yang kelihatan meyakinkan padahal karangan, dan
brief melarang itu. Gantinya kupakai band kualitatif (Sangat rendah sampai Tinggi) yang tiap
band kujelaskan dasarnya, plus daftar lima hal yang wajib diverifikasi begitu situs jalan.

**2. "Workshop journaling jogja" jadi satu satunya keyword utama.**
Permintaannya kecil tapi niat belinya matang, dan halaman satunya sekarang praktis kosong dari
halaman khusus. Ini keyword yang bisa dimenangkan tanpa backlink. Sebaliknya "manfaat journaling"
sengaja **tidak** dikejar walau permintaannya tinggi, karena lawannya situs kesehatan besar dan
yang datang bukan orang Jogja yang mau ambil slot.

**3. Pola title CPT acara diuji, bukan dikira.**
Judul acara TJR panjang dan puitis, jadi kuuji 4 pola kandidat terhadap 9 judul acara asli
dikali 5 venue, 45 kombinasi. Yang menang `%title% | TJR Jogja`, cuma 5 dari 45 yang lewat 60
karakter. Suffix-nya 12 karakter, jadi aturan pengamannya: **judul acara di atas 48 karakter
wajib pakai SEO title manual.** Dari 9 judul yang ada sekarang cuma 1 yang kena.

**4. Description acara dibedakan per status.**
Acara yang penuh atau sudah selesai tidak boleh tetap menulis "Ambil slot lewat WhatsApp",
karena itu janji yang tidak bisa ditepati di klik pertama. Sudah kutulis tiga varian.

**5. Arsip acara dan halaman Jadwal digabung jadi satu jalur `/jadwal/`.**
Arsip di `/jadwal/`, tiap acara di `/jadwal/{slug}/`. Konsekuensinya ada gotcha yang gampang
bikin halaman hilang, dan sudah kutulis di `struktur-url.md`: **jangan bikin halaman statis
dengan slug `jadwal`**, karena akan berebut URL dengan arsip CPT.

**6. Tema acara yang diulang pakai bulan dan tahun, bukan angka.**
`about-myself-oktober-2026`, bukan `about-myself-2`. Orang yang lihat link di grup WhatsApp
langsung tahu itu sesi yang mana, dan arsip sesi lama tetap punya URL sendiri.

**7. Slug kota pakai `yogyakarta`, bukan `jogja`.**
Supaya konsisten dengan alamat venue dan schema `Event`. Kata "jogja" tetap dipakai di judul,
description, dan isi halaman, karena itu yang diketik orang.

**8. Arsip taksonomi, arsip penulis, arsip tanggal, dan halaman pencarian di-`noindex`.**
Empat jenis halaman itu isinya terlalu mirip halaman Jadwal atau terlalu tipis, dan cuma akan
mengencerkan situs sekecil ini.

**9. Blog 8 artikel, bukan 20.**
Tempo satu artikel tiap tiga minggu itu yang realistis untuk dua orang yang pekerjaan utamanya
bikin acara. Artikel 1 dijadikan jangkar, tujuh lainnya menautkan balik ke sana.

**10. Satu batas etis yang kupasang sendiri di artikel overthinking.**
Bagian penutupnya wajib menyatakan journaling bukan pengganti bantuan profesional. TJR bukan
layanan kesehatan mental dan tidak boleh terbaca begitu. Nol klaim medis, nol kutipan penelitian
yang belum dibaca sendiri.

## Yang masih nunggu orang

### Nunggu Caca dan Dhanty

| Yang kosong | Dipakai di |
|---|---|
| Jumlah peserta tiap contoh kerja brand | `copy-halaman.md`, halaman Kolaborasi |
| Berapa hari kerja untuk balas brief | Kolaborasi |
| Harga B2B ditampilkan atau selalu lewat penawaran | Kolaborasi |
| Batas umur minimum peserta | Kontak |
| Konfirmasi jam balas WhatsApp 09.00 sampai 21.00 | Kontak |
| Izin menyebut nama brand di artikel blog | `outline-blog.md`, artikel 8 |
| Foto Caca dan Dhanty resolusi tinggi | Tentang |
| Nama domain final | `struktur-url.md`, semua contoh masih pakai `tjr.id` |

### Nunggu situs jalan

| Yang perlu diverifikasi | Caranya |
|---|---|
| Volume nyata tiap keyword | Keyword Planner, target lokasi Yogyakarta, bukan Indonesia |
| Siapa yang sekarang di halaman satu untuk keyword utama | Cek manual, lalu sesuaikan prioritas |
| Ejaan yang lebih sering diketik: journaling, jurnaling, atau menulis jurnal | Search Console setelah 3 bulan |
| Jogja lawan Yogyakarta | Search Console |
| Query nyata yang membawa orang | Search Console, biasanya yang paling berharga tidak ada di daftar mana pun |

## Catatan untuk lane lain

**Untuk A4 · CMS dan Data.** Ada satu hal yang gampang jatuh di antara dua lane: aturan
"judul acara di atas 48 karakter wajib pakai SEO title manual". Cara paling aman supaya Caca
dan Dhanty tidak perlu menghitung sendiri, sediakan satu field ACF opsional
`judul_seo_pendek` yang dipakai kalau diisi. Itu keputusan lane A4, kucatat di sini supaya
tidak hilang.

**Untuk A1 · Halaman Dalam.** Slug halaman yang kupakai di semua dokumen: `/tentang/`,
`/kolaborasi/`, `/kontak/`, `/galeri/`, `/cerita/`, `/jadwal/`. Kalau A1 memakai nama file
berbeda, yang menang struktur URL di `struktur-url.md`, karena itu yang sudah dipakai di meta
description dan peta link internal blog.

**Soal FAQ.** Beranda sudah punya 6 pertanyaan dengan schema FAQPage. Lima pertanyaan di
halaman Kontak sengaja kubuat berbeda semua supaya schema-nya tidak bentrok. Jangan menyalin
FAQ beranda ke halaman Kontak.

## Satu hal yang perlu diketahui orkestrator

Beranda final menampilkan angka **"22 sesi berjalan"** dan **"240 peserta"**. Dua angka itu
tidak ada di `brand-brief.md`. Yang ada di sana 22 **post** Instagram dan 329 follower, bukan
22 sesi dan bukan 240 peserta. Angka "3 kota" dan "9 brand partner" memang cocok dengan brand
brief dan aman.

Aku tidak mengubah beranda, karena itu di luar lane-ku dan sudah disetujui. Tapi dua angka itu
juga sengaja **tidak kupakai ulang** di copy Tentang, Kolaborasi, maupun Kontak, dan tidak
kupakai di meta description mana pun. Kalau angkanya benar, tinggal dipakai. Kalau ternyata
perkiraan, lebih baik ketahuan sekarang daripada setelah ada brand yang menanyakannya.

## Pemeriksaan yang kujalankan

| Yang dicek | Hasil |
|---|---|
| Em dash di 5 file | 0 |
| Frasa AI slop, 26 pola Inggris dan Indonesia | 0 |
| Panjang title tag, 14 nilai dihitung ulang dari teks aslinya | 14 dari 14 cocok, semua di bawah 60 |
| Panjang meta description | semua di bawah 155 |
| Pola title CPT diuji terhadap judul acara asli | 45 kombinasi |
| File di luar `konten/` yang tersentuh | 0 |
