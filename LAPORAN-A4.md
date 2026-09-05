# LAPORAN A4 · CMS dan Data

Semua file ada di `journaling-room-web/cms/`. Nol file di lane lain yang disentuh.

## Yang dibuat

| File | Isi |
|---|---|
| `acf-fields.json` | Field group ACF siap import, 16 field termasuk 4 tab pengelompokan |
| `logika-status.md` | Aturan status otomatis plus 5 fungsi PHP, termasuk query pemisah mendatang dan arsip |
| `schema-event.md` | JSON-LD Event dan Offer, peta field ke schema, fungsi `wp_head`, daftar hal yang bikin schema ditolak |
| `whatsapp-link.md` | Generator link WhatsApp per acara, dua cara menyimpan nomor, fungsi tombol siap pakai |
| `data-acara-contoh.json` | 6 acara contoh, tiap field ditandai sumbernya |
| `panduan-caca-dhanty.md` | Panduan langkah per langkah tanpa jargon |

Target lima menit per acara terpenuhi lewat tiga hal: field dikelompokkan dalam tab supaya tidak
terlihat seperti formulir panjang, status dan link WhatsApp dihitung sendiri, dan tiap field punya
kalimat petunjuk di bawah labelnya.

## Keputusan yang diambil

**1. Semua field pakai ACF versi gratis.**
`index.html` menyebut ACF gratis sudah cukup, jadi saya patuhi. Konsekuensinya dua field harus
dicari jalan lain, karena Gallery dan Options Page cuma ada di ACF PRO:

- **Galeri sesi** tidak jadi field ACF. Foto sesi ditaruh di blok Galeri bawaan WordPress di dalam
  kotak tulisan. Lebih enak juga buat Caca dan Dhanty, tinggal tarik foto. Di field group ada
  kotak petunjuk yang menjelaskan ini, jadi mereka tidak bingung nyari.
- **Nomor WhatsApp** tidak jadi halaman opsi ACF. Disimpan sebagai option biasa dan diberi kontrol
  di Customizer, jadi tetap bisa diganti sendiri tanpa kode.

Kalau nanti ACF PRO dibeli, dua duanya bisa dipindah, tapi tidak ada yang rusak kalau tidak.

**2. Nambah satu field yang tidak ada di daftar brief: `venue_alamat`.**
Alasannya schema Event. Google butuh `PostalAddress` untuk menampilkan acara di hasil pencarian,
dan nama venue saja tidak cukup. Field ini tidak wajib diisi, dan kalau kosong schema tetap keluar
dengan kota saja.

**3. Status dihitung dari waktu SELESAI, bukan tanggal mulai.**
Sesi jam 15.00 durasi 3 jam baru berubah jadi Selesai lewat jam 18.00. Kalau dipakai tanggal mulai,
acara yang sedang berlangsung akan hilang dari beranda di tengah sesi.

**4. Ambang Hampir penuh dihitung dari kapasitas, bukan angka tetap.**
Di bawah 25 persen sisa. Sesi 15 orang berubah di angka 12, sesi 6 orang berubah di angka 5. Kalau
dipakai angka tetap seperti sisa 3, Playdate yang cuma 6 orang akan berstatus Hampir penuh sejak
orang pertama daftar.

**5. Query jadwal membandingkan tanggal mulai, halaman detail memakai fungsi yang lebih teliti.**
Perbandingan di database dibuat sederhana supaya ringan. Selisihnya paling lama beberapa jam dan
tidak kelihatan di halaman daftar.

**6. Acara yang sudah lewat tetap mengeluarkan schema Event, tapi tanpa blok `offers`.**
Menawarkan harga untuk sesi yang sudah lewat itu menyesatkan, sementara markup acaranya sendiri
masih berguna untuk halaman arsip.

**7. Kapasitas 0 berarti tidak dibatasi.**
Statusnya tidak pernah jadi Penuh atau Hampir penuh, dan bar slot tidak muncul. Dipakai untuk
format seperti Playdate kalau nanti dibuka tanpa batas seat.

**8. Data contoh sengaja dipilih supaya keempat status ikut teruji.**
Buka, Hampir penuh, Penuh, dan Selesai semuanya ada. Tiap field dikasih penanda sumbernya: judul,
format, venue, harga, dan sebagian durasi diambil dari `brand-brief.md`, sedangkan tanggal,
kapasitas, dan slot terisi ditandai `contoh` karena memang tidak ada datanya.

**9. Ada satu beda antara brand brief dan beranda final, saya ikut brand brief.**
`A Moment Between Chapters` di `brand-brief.md` tercatat di Kopi Kalandjana, di
`final-beranda.html` tertulis Copenhagen. Aturan 7 menyuruh ambil fakta dari brand brief, jadi itu
yang saya pakai. Perlu dipastikan yang mana yang benar.

**10. Nomor WhatsApp di dua berkas beda pengelompokan, tapi digitnya sama.**
`BRIEF.md` menulis `+62 857 2822 5369`, `brand-brief.md` menulis `+62 8572 8225 369`. Dua duanya
menghasilkan `6285728225369`. Itu yang dipakai.

## Yang perlu lane lain

- **A2** yang mendaftarkan CPT `acara` dan taksonomi `format-acara` serta `kota` di `functions.php`.
  Semua potongan PHP saya memakai nama itu persis. Kalau nama slugnya beda, fungsi saya ikut meleset.
- Potongan PHP di tiga file markdown perlu ditempel ke child theme buatan A2, urutannya
  `logika-status` dulu, baru `schema-event` dan `whatsapp-link`, karena dua yang terakhir memanggil
  fungsi dari yang pertama.
- Kalau A3 memakai Rank Math untuk schema, schema Event bawaan Rank Math untuk tipe konten Acara
  harus dimatikan. Dua blok Event di satu halaman bikin Google memilih sendiri, biasanya yang lebih
  miskin isinya.

## Yang masih nunggu orang

| Yang dibutuhkan | Buat apa | Tanpa itu jadinya |
|---|---|---|
| Domain final | `@id` dan `url` di schema | Contoh masih pakai penanda `thejournalingroom.id` |
| Alamat lengkap tiap venue | `PostalAddress` di schema | Google cuma dapat nama venue dan kota |
| Link Google Maps tiap venue | Tombol Lihat peta | Tombolnya tidak muncul, tidak error |
| Foto resolusi tinggi tiap sesi | Kartu jadwal dan kartu acara di Google | Acara terbaca Google tapi tidak dapat gambar |
| Harga `Between the Pages` | Data contoh | Sekarang diisi 0 dengan catatan jelas |
| Konfirmasi nomor WhatsApp | Semua tombol | Sudah dipakai versi yang digitnya sama di dua berkas |
| Kepastian venue `A Moment Between Chapters` | Data contoh dan beranda | Lihat keputusan nomor 9 |
| Zona waktu situs diset ke Jakarta | Status dan schema | Kalau masih UTC, acara jadi Selesai tujuh jam lebih awal dan jam di Google salah |

Dua kota lain yang disebut brand brief, Magelang dan Jakarta, tidak saya pasangkan ke acara mana
pun karena tidak ada data acaranya. Taksonominya tetap saya daftarkan supaya siap dipakai.

## Catatan kepatuhan

- Nol warna, font, atau skala spacing baru. File saya tidak memuat satu pun nilai hex.
- Nol em dash, nol frasa AI slop. Sudah dicek dengan pencarian teks di keenam file.
- Copy berbahasa Indonesia, nama tema acara dibiarkan Inggris.
- Nol library JavaScript. Semua yang saya kerjakan berjalan di sisi PHP.
- Bar slot diberi `role="img"` dan `aria-label` berisi angka, karena warna bar saja tidak
  menyampaikan apa apa ke pembaca layar. Tombol memakai kelas yang tingginya sudah di atas 44px.
