# Checklist SEO v5 · The Journaling Room

Daftar centang untuk dikerjakan sekali situs sudah jadi. Urutannya sengaja dari yang paling
merusak kalau kelewat, ke yang bisa menunggu. Tiap item ada satu kalimat kenapa penting, jadi
tidak ada yang dikerjakan cuma karena tertulis di daftar.

Kolom kosong `[ ]` dicentang jadi `[x]` kalau sudah beres.

---

## A. Sebelum tombol terbit ditekan

- [ ] **Matikan centang "Discourage search engines from indexing this site"** di Settings,
      Reading. *Centang ini biasanya dinyalakan waktu situs masih dibangun, dan kalau lupa
      dimatikan, situsnya tidak akan pernah muncul di Google sama sekali.*
- [ ] **Pastikan semua halaman jalan di https** dan http dialihkan 301 ke https.
      *Google memperlakukan http dan https sebagai dua situs berbeda, jadi tanpa pengalihan,
      nilai yang terkumpul terbelah dua.*
- [ ] **Pilih satu bentuk domain**, tanpa www, lalu paksa lewat pengalihan di Hostinger.
      *Sama alasannya: `www.thejournalingroom.id` dan `thejournalingroom.id` dianggap dua
      situs kalau tidak disatukan.*
- [ ] **Atur permalink** sesuai `struktur-url.md`, lalu buka Settings, Permalinks, klik Save
      sekali. *Tanpa klik Save itu, URL untuk CPT acara akan 404 walaupun kodenya sudah benar.*
- [ ] **Cek nomor WhatsApp di seluruh situs** benar benar `0857 2022 5369`, link
      `https://wa.me/6285720225369`, di enam tempat: tombol header, kartu sesi terdekat, tombol
      sesi terdekat, tombol ajakan, footer, dan tombol mengambang. *Nomor salah di tombol utama
      artinya semua trafik yang berhasil didatangkan terbuang, dan nomor lama `0857 2822 5369`
      masih beredar di pesan pesan sebelumnya.*
- [ ] **Ganti semua data acara mendatang dengan data asli**, atau sembunyikan seksinya kalau
      belum ada jadwal. *Jadwal di prototipe masih isian contoh, dan acara palsu di schema
      `Event` bisa kena penalti Google plus bikin orang datang di hari yang salah.*
- [ ] **Perbaiki struktur heading** sesuai `v5-heading-outline.md`. *Tujuh judul seksi sekarang
      ditulis pakai `<p>`, jadi Google tidak punya petunjuk apa isi tiap bagian halaman.*
- [ ] **Isi alt text semua gambar** sesuai `v5-alt-text.md`, lewat Media Library supaya terbawa
      ke mana pun foto dipasang. *Situs ini isinya sebagian besar foto, dan tanpa alt, separuh
      halaman jadi tidak ada artinya untuk pembaca layar maupun untuk Google Images.*
- [ ] **Isi Title dan Description di Rank Math** untuk enam halaman utama sesuai `v5-meta.md`.
      *Kalau dikosongkan, Google mengarang sendiri potongan teks dari halaman, dan yang diambil
      sering bagian yang paling tidak meyakinkan.*
- [ ] **Pasang pola otomatis Title dan Description untuk CPT acara** di Rank Math, bagian
      Titles and Meta. *Acara baru akan terus bertambah, dan pola otomatis memastikan Caca dan
      Dhanty tidak perlu mengisi kolom SEO tiap kali.*
- [ ] **Set OG image per halaman pakai foto sesi, bukan logo.** *Link yang dibagikan di grup
      WhatsApp dan Instagram tampil dengan gambar itu, dan logo di kotak preview terlihat
      seperti link kosong yang tidak diklik siapa pun.*
- [ ] **Pasang site icon** di Appearance, Customize. *Ikon di tab browser dan di hasil pencarian
      mobile adalah pembeda pertama waktu TJR muncul bersebelahan dengan Eventbrite dan
      Instagram.*
- [ ] **Beri `noindex`** pada arsip taksonomi `format` dan `kota`, arsip penulis, arsip tanggal,
      dan halaman hasil pencarian. *Halaman halaman itu isinya hampir sama dengan halaman
      Jadwal, dan situs baru yang penuh halaman kembar akan dinilai tipis.*
- [ ] **Pasang JSON-LD Organization dan LocalBusiness** dari `v5-structured-data.json` di semua
      halaman. *Ini yang bikin Google yakin TJR adalah bisnis nyata di Yogyakarta, bukan blog
      yang kebetulan menulis tentang journaling.*
- [ ] **Bikin halaman 404 yang mengarahkan ke Jadwal dan Kontak.** *Link acara lama akan terus
      beredar di grup WhatsApp setelah acaranya lewat, dan halaman 404 kosong menghentikan
      orang yang niatnya sudah bagus.*
- [ ] **Bikin halaman kebijakan privasi.** *Wajib ada begitu situs memasang Analytics atau form
      kontak, dan Google Business Profile juga menanyakannya untuk sebagian kategori.*

## B. Hari pertama online

- [ ] **Verifikasi situs di Google Search Console**, pilih tipe Domain, bukan URL prefix.
      *Tipe Domain mencakup http, https, www, dan subdomain sekaligus, jadi tidak ada data yang
      hilang gara gara salah pilih properti.*
- [ ] **Kirim sitemap XML** ke Search Console, alamatnya `https://thejournalingroom.id/sitemap_index.xml`
      dari Rank Math. *Situs baru tidak punya satu pun link masuk, jadi sitemap adalah satu
      satunya cara Google tahu halaman halaman ini ada.*
- [ ] **Cek `robots.txt`** tidak memblokir apa pun yang penting, dan memang menyebut sitemap.
      *Satu baris `Disallow: /` yang tertinggal dari masa pembangunan bisa menyembunyikan
      seluruh situs tanpa pesan error apa pun.*
- [ ] **Minta indexing manual** untuk beranda dan halaman Jadwal lewat URL Inspection.
      *Mempercepat halaman terpenting masuk indeks dari hitungan minggu jadi hitungan hari.*
- [ ] **Pasang link situs di bio Instagram** `@thejournalingroom`. *Instagram adalah satu
      satunya sumber trafik TJR sekarang, dan link itu juga sinyal pertama ke Google bahwa
      situs ini milik akun yang sudah punya audiens.*
- [ ] **Cek tampilan di ponsel sungguhan**, bukan cuma di mode responsif browser.
      *Menu navigasi di prototipe disembunyikan di bawah lebar 900 piksel dan belum ada
      penggantinya, jadi tanpa menu mobile, sebagian besar pengunjung tidak bisa berpindah
      halaman sama sekali.*

## C. Minggu pertama

- [ ] **Klaim dan verifikasi Google Business Profile.** *Untuk bisnis lokal sekecil TJR, muncul
      di Maps jauh lebih cepat daripada menang di hasil pencarian biasa, dan orang yang mencari
      di Maps niatnya sudah lebih matang.*
- [ ] **Pilih kategori GBP yang tepat**, misalnya penyelenggara workshop atau kelas seni, bukan
      kategori umum seperti toko. *Kategori adalah faktor terbesar yang menentukan pencarian
      mana yang memunculkan TJR di Maps.*
- [ ] **Isi kolom Website di GBP dengan `https://thejournalingroom.id/`.** *Ini yang
      menyambungkan profil Maps dengan situs, dan tanpa itu kedua aset bekerja sendiri sendiri.*
- [ ] **Samakan nama, alamat, dan nomor telepon di GBP dengan yang tertulis di halaman Kontak,
      huruf per huruf.** *Google mencocokkan ketiganya sebelum memutuskan mempercayai lokasi
      bisnis, dan perbedaan sekecil singkatan jalan sudah cukup bikin ragu.*
- [ ] **Unggah minimal sepuluh foto sesi ke GBP.** *Profil dengan foto dibuka jauh lebih sering
      daripada profil kosong, dan TJR kebetulan punya dokumentasi paling banyak di antara semua
      asetnya.*
- [ ] **Isi jam operasional GBP sesuai jam balas WhatsApp, 09.00 sampai 21.00.** *Kalau jamnya
      berbeda dengan yang tertulis di situs, salah satunya pasti bikin orang merasa diabaikan.*
- [ ] **Uji schema di Rich Results Test** milik Google, untuk beranda dan satu halaman detail
      acara. *Schema yang salah tidak memberi pesan error di halaman, jadi satu satunya cara
      tahu adalah mengujinya.*
- [ ] **Cek semua link internal tidak ada yang mati**, terutama link `#` yang tertinggal dari
      prototipe. *Prototipe memakai anchor seperti `#jadwal` untuk semua tombol, dan kalau
      terbawa ke WordPress, semua tombol akan berputar di halaman yang sama.*
- [ ] **Pastikan tiap kartu acara menuju halaman detail acara, bukan langsung ke WhatsApp.**
      *Kalau semua tombol langsung ke WhatsApp, halaman detail acara tidak pernah dikunjungi,
      dan schema `Event` yang sudah dibangun jadi tidak ada gunanya.*

## D. Bulan pertama

- [ ] **Ukur Core Web Vitals di PageSpeed Insights**, catat angka LCP, CLS, dan INP untuk versi
      mobile. *Versi mobile yang dinilai Google, dan angkanya biasanya jauh lebih buruk
      daripada versi desktop yang biasa kita lihat waktu bekerja.*
- [ ] **Periksa animasi tirai pembuka.** Di prototipe ada layar penutup yang menahan halaman
      sampai sekitar 1,4 detik sebelum isinya muncul. *Penundaan itu masuk hitungan LCP, jadi
      pembuka yang cantik bisa langsung menjatuhkan nilai kecepatan. Pertimbangkan
      memperpendeknya atau menjalankannya cuma di kunjungan pertama.*
- [ ] **Periksa latar meja yang memakai `background-attachment: fixed`.** *Latar tetap dengan
      foto besar memaksa browser menggambar ulang tiap kali digulir, dan di ponsel kelas
      menengah itu terasa patah patah sekaligus menurunkan nilai INP.*
- [ ] **Pastikan foto hero pakai `fetchpriority="high"` dan tidak `loading="lazy"`.**
      *Foto hero adalah elemen LCP di hampir semua halaman, dan menunda pemuatannya berarti
      menunda nilai yang diukur Google.*
- [ ] **Kompres semua foto dan sediakan versi WebP.** *Foto dokumentasi TJR beresolusi tinggi
      dan berjumlah puluhan, dan itu satu satunya hal terbesar yang menentukan cepat lambatnya
      situs ini.*
- [ ] **Pastikan tiap gambar punya `width` dan `height`.** *Tanpa itu, teks melompat waktu
      gambar selesai dimuat, dan lompatan itulah yang dihitung sebagai CLS.*
- [ ] **Cek `site:thejournalingroom.id` di Google.** *Cara paling cepat melihat halaman mana
      yang sudah masuk indeks dan halaman mana yang tertinggal.*
- [ ] **Buka laporan Pages di Search Console**, lihat halaman yang berstatus Crawled tapi belum
      diindeks. *Status itu biasanya berarti Google menganggap halamannya terlalu tipis, dan
      lebih baik diketahui sekarang daripada tiga bulan lagi.*
- [ ] **Pasang analitik yang ringan**, lalu sebutkan di kebijakan privasi. *Tanpa data,
      keputusan berikutnya cuma tebakan, tapi skrip analitik yang berat juga menghapus kerja
      keras di bagian kecepatan.*
- [ ] **Tambahkan link internal dari beranda ke Jadwal, dari Jadwal ke tiap detail acara, dan
      dari detail acara kembali ke Jadwal.** *Link internal yang rapi membuat Google menemukan
      halaman baru tanpa menunggu sitemap dibaca ulang.*

## E. Rutin, tiap bulan

- [ ] **Buka laporan Performance di Search Console**, urutkan berdasarkan Impressions.
      *Keyword yang benar benar membawa orang sering sama sekali tidak ada di riset awal, dan
      itu justru data paling berharga yang dimiliki TJR.*
- [ ] **Cek ejaan mana yang lebih sering dipakai orang, `jogja` atau `yogyakarta`.** *Riset
      keyword sekarang cuma menebak, dan begitu ada data nyata, title beranda dan title arsip
      kolaborasi tinggal ditukar.*
- [ ] **Posting satu update di Google Business Profile.** *Profil yang aktif ditampilkan lebih
      sering daripada profil yang diam, dan satu foto sesi terakhir sudah cukup.*
- [ ] **Minta satu peserta menulis ulasan di GBP.** *Ulasan adalah faktor peringkat lokal
      terkuat yang bisa dikendalikan sendiri, dan TJR punya peserta yang senang tapi belum
      pernah diminta.*
- [ ] **Perbarui angka di halaman kalau jumlah sesi bertambah.** *Kalimat `Sepuluh kali` dan
      `Sebelas nama di meja` tertulis manual di beberapa tempat, dan angka yang basi bikin
      situs terlihat ditinggalkan.*
- [ ] **Cek tidak ada dua halaman dengan title tag yang mirip** lewat Rank Math.
      *Halaman yang saling menyerupai akan saling menekan peringkat, dan yang paling gampang
      bocor adalah arsip taksonomi.*

## F. Tiap kali menambah acara baru

- [ ] **Isi semua field ACF**, terutama tanggal, venue, alamat lengkap, harga, dan kapasitas.
      *Schema `Event` dibangun dari field itu, dan satu field kosong bisa bikin seluruh
      schema-nya ditolak.*
- [ ] **Cek slug-nya** mengikuti aturan di `struktur-url.md`, dan kalau temanya diulang,
      tambahkan bulan dan tahun, bukan angka. *Slug `about-myself-2` tidak memberi tahu apa
      apa ke orang yang melihat link itu di grup WhatsApp.*
- [ ] **Isi alt text foto acara** waktu mengunggah, jangan nanti. *Foto tanpa alt hampir tidak
      pernah dibetulkan belakangan, karena tidak ada yang mengingatkan.*
- [ ] **Kalau judul acara lebih dari 48 karakter, isi SEO title manual.** *Suffix `| TJR Jogja`
      panjangnya 12 karakter, dan judul yang lewat batas akan terpotong di tengah kata di hasil
      pencarian.*
- [ ] **Setelah acara lewat, ubah statusnya, jangan hapus halamannya.** *Halaman acara lama
      jadi bukti bahwa TJR benar benar rutin berjalan, dan menghapusnya memutus link yang sudah
      tersebar sekaligus membuang nilai yang sudah dikumpulkan.*
- [ ] **Jangan mengubah tanggal acara lama lalu memakainya ulang untuk sesi baru.** *Itu
      menghapus arsip, membuat schema menunjuk tanggal yang salah, dan mematahkan link yang
      sudah beredar.*

---

## Empat hal yang paling menentukan, kalau waktunya cuma sedikit

1. **Google Business Profile diverifikasi dan disambungkan ke situs.** Jalur tercepat menuju
   orang yang benar benar akan datang.
2. **Title dan description enam halaman utama terisi.** Pekerjaan setengah jam yang menentukan
   apakah hasil pencarian TJR diklik atau dilewati.
3. **Schema `Event` benar di tiap detail acara.** Ini yang bisa memunculkan sesi TJR di panel
   acara Google tanpa harus menang di hasil organik.
4. **Situs cepat di ponsel.** Semua yang di atas jadi sia sia kalau halamannya ditinggal
   sebelum sempat muncul.
