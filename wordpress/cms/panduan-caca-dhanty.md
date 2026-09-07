# Panduan situs untuk Caca dan Dhanty

Ini catatan serah terima situs thejournalingroom.id. Nggak perlu ngerti kode sama sekali.
Isinya empat: cara nambah acara, cara nulis di Cerita, cara kerja header dan footer, dan menu
mana yang bisa bikin situs mati.

Semua langkah di sini sudah dicek langsung di dasbor situs kalian, bukan ditulis dari ingatan.

## Dua hal yang bikin bingung di awal

**Tombolnya bahasa Inggris.** Situsnya bahasa Indonesia, tapi tombol bawaan WordPress masih
Inggris. Jadi yang kalian klik itu **Publish**, bukan Terbitkan. **Save** kalau nyimpan
perubahan di halaman yang sudah tayang. **Move to trash** kalau mau buang. Nama menu yang kami
bikin sendiri tetap Indonesia: Acara, Kolaborator, Detail Acara.

**Blog namanya Cerita di situs, tapi Posts di dasbor.** Menu **Posts** di kolom kiri itu yang
isinya tulisan di halaman /cerita/. Nggak ada menu bernama Cerita.

## Peta menu

Buka `thejournalingroom.id/wp-admin`, masuk pakai email dan kata sandi. Kolom kiri isinya
berurutan begini. Kalau layarnya sempit, menunya sembunyi di balik tombol tiga garis di pojok
kiri atas.

| Menu | Isinya |
|---|---|
| **Acara** | Semua sesi. Ini yang paling sering kalian pakai. |
| **Kolaborator** | Nama dan logo brand yang muncul di pita kolaborator di beranda. |
| **Posts** | Tulisan di halaman Cerita. |
| **Media** | Semua foto yang pernah diunggah. |
| **Pages** | Halaman tetap: Tentang, Galeri, Kolaborasi, Kontak, Beranda. |
| **Appearance** | Tata letak situs: header, footer, navigasi. Isinya Themes, Editor, Fonts, Customize. Lihat bagian 3. |
| Plugins, Users, Tools, Settings | Bagian teknis. Lihat bagian 4. Menu Tools menyimpan dua layar paling berbahaya di dasbor. |
| ACF, Rank Math SEO | Menu milik plugin. Nggak perlu dibuka. |

---

# 1. Nambah acara baru

Kalau bahannya sudah lengkap, ini selesai sekitar lima menit.

## Siapkan dulu biar nggak bolak balik

- Nama temanya
- Tanggal dan jam mulai
- Berapa jam sesinya
- Nama venue, dan link Google Maps kalau ada
- Harga per orang
- Berapa orang yang muat
- Satu foto buat kartu acaranya

Belum lengkap juga nggak apa apa. Simpan dulu sebagai draf lewat **Save draft**, lanjut nanti.

## Langkah

### 1. Buka Acara, klik Tambah acara baru

Menu **Acara** di kolom kiri, lalu tombol **Tambah acara baru** di atas.

### 2. Isi Judul acara

Panel paling atas namanya **Judul dan catatan sesi**. Kolom pertamanya **Judul acara**.

Ketik nama temanya di situ, contohnya `Tracing Shadows, Mapping Stars`. Judul ini yang muncul
di kartu jadwal, di halaman acara, dan di Google.

Perhatikan: kotak judul besar di kanvas sengaja disembunyikan, jadi jangan bingung kalau di
bagian atas masih tertulis No title. Yang dibaca situs adalah kolom **Judul acara** ini.
Begitu disimpan, judul di atas ikut sendiri.

Nggak usah nambahin kata workshop atau tanggal di judul. Semua sudah ada tempatnya sendiri.

### 3. Isi Detail Acara

Panel keduanya **Detail Acara**, isinya empat tab. Kolom bertanda bintang merah wajib diisi.

**Tab Waktu dan tempat**

| Kolom | Cara isi |
|---|---|
| Tanggal dan jam mulai (wajib) | Klik, pilih dari kalender. Ini yang menentukan urutan di Jadwal dan kapan acaranya pindah sendiri ke arsip. |
| Durasi (jam) (wajib) | Angka saja. Tiga jam ketik `3`, dua setengah jam ketik `2.5`. Isinya sudah otomatis 3. Batasnya 0.5 sampai 12. |
| Nama venue (wajib) | Tulis seperti orang nyebut, `Kupiku Coffee`, bukan alamat lengkap. Maksimal 80 huruf. |
| Link Google Maps | Buka Maps, cari venuenya, tekan Bagikan lalu Salin tautan, tempel di sini. Boleh kosong, tombol Lihat peta tinggal nggak muncul. |
| Alamat singkat | Satu baris, contoh `Jl. Kaliurang KM 5, Sleman`. Dipakai Google buat nampilin acaranya di hasil pencarian. Maksimal 140 huruf. |

**Tab Harga**

| Kolom | Cara isi |
|---|---|
| Harga per orang (wajib) | Angka doang, `75000`. Jangan pakai titik, jangan pakai Rp. |
| Catatan harga | Biarkan kosong. Kolom ini sisa versi lama dan isinya sudah nggak ditampilkan di situs. |

**Tab Slot**

| Kolom | Cara isi |
|---|---|
| Kapasitas (wajib) | Berapa orang maksimal. Isinya sudah otomatis 15. Batasnya 1 sampai 200. |
| Slot terisi (wajib) | Buat acara baru isi `0`. Angka ini yang menggerakkan bar slot dan status acara. |

**Tab Isi kit**

Namanya **Yang didapat peserta**. Centang yang termasuk di sesi ini. Pilihannya sudah panjang,
dari Notebook A6 sampai Teman baru. Kalau ada yang belum ada, klik **Tambah pilihan baru**,
ketik, selesai. Pilihan baru itu otomatis tersedia buat acara berikutnya.

Kalau satu sesi kitnya beda banget dan nggak cocok dijelaskan lewat centang, balik ke panel
**Judul dan catatan sesi** dan isi kolom **Yang disediakan** dengan tulisan sendiri, dipisah
koma. Kolom itu menang atas daftar centang. Ada juga kolom **Yang perlu dibawa** di sebelahnya.
Dua duanya boleh kosong, nanti pakai isi bawaan.

### 4. Pasang foto acara

Lihat kolom kanan. Kalau kolomnya nggak kelihatan, klik ikon kotak di sebelah tombol Publish
buat munculin.

Tombolnya **Pilih foto acara**. Klik, unggah fotonya.

Foto ini yang muncul di kartu jadwal dan yang ikut kekirim waktu link acaranya di-share ke
WhatsApp atau Instagram. Pilih yang enak dilihat kecil. Kalau bisa lebarnya minimal 1200
piksel, dan foto dari HP biasanya sudah lebih dari cukup.

### 5. Centang Format acara dan Kota

Masih di kolom kanan, di bawah. Dua kotak centang:

- **Format acara**. Pilihannya sudah ada: Brand Activation, Brush Lettering Class, Inner Circle,
  Journaling Playdate, Journaling Workshop, Sunday Reads Club. Kalau formatnya baru, klik
  **Tambah format**.
- **Kota**. Sama caranya.

Dua kotak ini yang bikin tombol saring di halaman Jadwal jalan. Kalau nggak dicentang acaranya
tetap tampil, cuma nggak ikut kesaring.

### 6. Klik Publish

Tombol biru di kanan atas. Selesai. Acaranya langsung nongol di Jadwal dan di beranda, urut
sesuai tanggal.

## Yang jalan sendiri, nggak usah diurus

- **Status acara.** Buka, Hampir penuh, Penuh, atau Selesai. Dihitung sendiri dari tanggal dan
  angka slot terisi.
- **Acara yang sudah lewat.** Begitu tanggal dan jamnya lewat, acaranya pindah sendiri ke arsip
  dan hilang dari beranda. Nggak usah dihapus.
- **Tombol WhatsApp.** Dibikin sendiri lengkap sama pesan yang sudah nyebut nama acara dan
  tanggalnya.
- **Urutan jadwal.** Yang paling dekat selalu di atas.
- **Info buat Google.** Tanggal, tempat, dan harga otomatis dikirim ke Google dalam bentuk yang
  dia ngerti.

## Selama pendaftaran jalan

Tiap ada yang transfer: buka acaranya, tab **Slot**, ubah angka **Slot terisi**, klik **Save**.

| Contoh | Yang muncul di situs |
|---|---|
| 5 dari 15 | Buka |
| 11 dari 15 | Buka, sisa 4 |
| 12 dari 15 | Hampir penuh |
| 15 dari 15 | Penuh, tombolnya berubah jadi ajakan waitlist |

Hampir penuh muncul kalau sisanya tinggal di bawah seperempat kapasitas. Sesi 15 orang berubah
di angka 12, sesi 6 orang berubah di angka 5.

## Setelah acaranya selesai

1. **Pastikan angka slot terisi sudah benar.** Kepakai buat statistik di beranda.
2. **Masukin foto sesinya.** Buka acaranya lagi, klik di kanvas tulisan yang besar, klik tanda
   tambah, cari **Galeri**, tarik semua fotonya ke situ. Halaman Galeri ngambil dari situ
   sendiri. Foto sesi memang nggak ditaruh di panel Detail Acara, dan di panel itu ada
   pengingatnya.

Acara lama nggak usah dihapus. Itu yang bikin situs kelihatan hidup dan kebaca sama Google.

## Kalau ada yang berubah

**Sesi diundur.** Ganti tanggalnya, klik Save.

**Sesi dibatalkan.** Jangan dihapus permanen. Di kolom kanan ada tombol **Move to trash**.
Masih bisa dibalikin kalau berubah pikiran.

**Harga berubah.** Ganti angkanya, klik Save.

**Salah ketik.** Semua bisa diedit kapan aja.

---

# 2. Nulis di Cerita

Halaman /cerita/ isinya diatur dari menu **Posts**.

### Langkah

1. Menu **Posts**, klik **Add Post**.
2. Ketik judulnya di kotak besar paling atas. Untuk tulisan, judulnya memang di kanvas, beda
   sama Acara.
3. Tulis isinya di bawah judul. Tiap kali tekan Enter jadi paragraf baru. Buat sub judul, klik
   tanda tambah di kiri atas, cari **Heading**.
4. Buka kolom kanan, cari panel **Categories**, centang **Panduan journaling**. Kategori itu
   sudah ada, nggak usah bikin baru.
5. Klik **Set featured image** di kolom kanan, unggah satu foto. Ini yang muncul di daftar
   Cerita dan waktu link-nya di-share.
6. Klik **Edit excerpt**, tulis satu dua kalimat ringkasan. Ini yang kebaca di Google dan di
   daftar Cerita. Kalau dikosongkan, Google ngambil kalimat pertama apa adanya.
7. Klik **Publish**.

### Tiga hal yang bikin tulisan lebih kepakai

- **Satu judul besar saja.** Judul di paling atas itu H1-nya. Sub judul di dalam tulisan pakai
  Heading biasa, jangan dibikin sebesar judul utama.
- **Selalu ada satu link ke halaman Jadwal.** Blognya tugasnya nyalurin orang ke sesi.
- **Foto sendiri, bukan foto stok.** Foto sesi TJR jauh lebih meyakinkan.

Tiga tulisan yang sudah tayang bisa dijadikan contoh bentuk dan panjangnya.

---

# 3. Header, navigasi, dan footer

Nomor WhatsApp, tautan Instagram, menu navigasi di header, dan daftar tautan di footer bukan
diatur dari layar Acara atau Posts. Semua itu bagian dari tata letak situs, dan diubah lewat
menu **Appearance**, bagian **Editor**. Bisa juga lewat tulisan **Edit site** di bar hitam
paling atas.

Kalian punya akses ke sana dan boleh mengubahnya sendiri. Tapi ada dua hal yang perlu diketahui
sebelum masuk.

**Satu perubahan bisa kena banyak halaman sekaligus.** Header dan footer dipakai bersama oleh
semua halaman, jadi salah geser di satu tempat langsung kelihatan di seluruh situs. Beda dengan
acara atau tulisan yang efeknya cuma di halaman itu sendiri.

**Perubahan di Editor tersimpan di situs, bukan di kode kami.** Kami menyimpan tampilan situs
sebagai kode di komputer kami, dan tiap kali mengirim versi baru, yang kami kirim adalah versi
kode itu. Perubahan yang kalian buat lewat Editor tidak ikut ke sana. Akibatnya ada dua: bisa
hilang tertimpa, atau bertabrakan sehingga situs menampilkan campuran dua versi. Hari ini kami
baru saja membereskan satu kasus persis seperti itu.

Jadi bukan dilarang, cuma jangan dikerjakan diam diam. Kabari kami sebelum atau sesudahnya,
supaya perubahannya kami masukkan ke kode dan tidak hilang di kiriman berikutnya. Kalau cuma
ganti nomor WhatsApp atau satu tautan, biasanya lebih cepat kalian bilang dan kami yang
kerjakan, sekalian aman.

Yang aman kalian ubah sendiri kapan saja tanpa lapor: acara, tulisan di Cerita, kolaborator,
foto, dan isi halaman biasa lewat menu **Pages**.

---

# 4. Menu yang bisa bikin situs mati

Akun kalian **Administrator**, jadi semua menu kebuka. Ini daftar yang benar benar bisa bikin
situs berhenti jalan, supaya kalian tahu bedanya sama menu yang cuma bikin tampilan geser.

**Plugins.** Jangan matikan **Advanced Custom Fields**. Itu yang bikin panel Detail Acara ada.
Kalau dimatikan, kolom tanggal, harga, dan slot hilang dari layar dan halaman Jadwal ikut
kosong. Untuk memasang plugin baru, kabari kami dulu. Plugin yang bentrok bisa bikin situs
nggak kebuka sama sekali, dan yang muncul cuma halaman putih tanpa keterangan.

**Appearance, bagian Themes.** Di situ ada lima tema terpasang, dan yang menyala **TJR v5**.
Kalau tema lain diklik aktifkan, seluruh tampilan situs langsung berubah jadi tampilan bawaan
WordPress: warna, huruf, kartu jadwal, semuanya. Acaranya tidak hilang, tapi situsnya jadi tidak
kenal bentuk sampai TJR v5 dinyalakan lagi. Jangan diaktifkan buat coba coba.

**Tools, bagian Theme File Editor dan Plugin File Editor.** Ini yang paling berbahaya di seluruh
dasbor, dan letaknya agak tersembunyi di menu Tools, bukan di Appearance. Dua layar itu
menyunting kode tema dan kode plugin langsung. Satu tanda baca yang salah di situ bikin seluruh
situs beserta dasbornya mati bersamaan, dan benerinnya harus lewat panel hosting karena
WordPress-nya sendiri sudah nggak kebuka. Tidak ada alasan kalian perlu masuk ke sana.

**Settings, bagian General.** Jangan sentuh WordPress Address dan Site Address. Satu huruf salah
di situ bikin situs dan dasbor sama sama nggak kebuka, dan pemulihannya juga lewat hosting.

**Settings, bagian Permalinks.** Kalau ini diubah, semua tautan acara dan tulisan yang pernah
di-share jadi mati, termasuk yang sudah tersebar di Instagram dan WhatsApp.

**Users.** Jangan hapus pengguna. Acara dan tulisan yang dibuat pengguna itu bisa ikut terbawa.

**Tools, bagian Erase Personal Data.** Sekali jalan, nggak bisa dibatalkan.

Yang aman di menu Tools cuma **Site Health**, itu cuma laporan dan nggak mengubah apa apa.

Satu hal yang menolong: selama tombol simpan belum diklik, belum ada yang berubah. Jadi kalau
kepencet dan kelihatan aneh, tutup tabnya tanpa nyimpan dan situsnya baik baik saja.

---

# Pertanyaan yang biasanya muncul

**Bisa nyiapin acara dari jauh jauh hari?**
Bisa. Isi semuanya, di kolom kanan klik tanggal di sebelah tulisan Publish, atur tanggal
tayangnya. Acaranya baru muncul pas tanggal itu.

**Kalau kapasitasnya nggak dibatasi gimana?**
Isi angka yang lebih besar dari perkiraan peserta. Kolom Kapasitas minimal 1, angka 0 ditolak,
dan itu memang disengaja. Kapasitas 0 bikin kartu acaranya diam soal kursi dan statusnya Buka
terus, jadi orang nggak pernah tahu tinggal berapa slot.

**Fotonya belum ada tapi mau dipublish duluan.**
Boleh. Kartunya muncul tanpa foto. Cuma di Google acaranya nggak dapat gambar, jadi kalau bisa
dilengkapi sebelum disebar.

**Salah masukin angka slot, keburu kepublish.**
Ganti aja, langsung berubah.

**Dua orang ngedit bareng?**
WordPress ngasih tahu kalau ada yang lagi buka acara yang sama. Ikutin peringatannya biar nggak
saling nimpa.

**Kok tombolnya kadang Publish kadang Save?**
Publish buat yang belum pernah tayang. Save buat yang sudah tayang dan lagi diubah. Fungsinya
sama: nyimpan.

**Nambah kolaborator gimana?**
Menu **Kolaborator**, tambah baru, ketik nama brandnya sebagai judul, lalu unggah logonya lewat
kotak Logo di kolom kanan. Urutannya ikut kolom Order, jadi bisa digeser tanpa ganti nama.
