# T-35: Beverages masuk daftar aktivitas Embracing Growth

**Selesai. Nol berkas tema disentuh, nol kiriman.** Satu field di basis data.

## Hasil sapuan: satu sumber, dan itu jawaban yang berguna

Kartu menyuruh menyapu dulu, dan kalau ternyata semuanya menarik dari satu sumber,
mengatakannya. **Memang satu sumber: `excerpt` post acara id 12.** Bukan field ACF.

Kalimat aktivitas muncul di **tiga** permukaan, semuanya membaca `excerpt` yang sama:

| Permukaan | Peran |
|---|---|
| `/` beranda | kartu Sesi Terdekat, blok `wp-block-post-excerpt` |
| `/?s=` | ringkasan hasil pencarian |
| `/wp-json/wp/v2/acara` | REST |

Dan **nol** di `/jadwal/`, `/feed/`, sitemap, serta kedelapan halaman lain.

## Kalimatnya

Sebelum (166 karakter):
`... menulis & creative visual journaling, snack & lunch time, dan sun-down walking around Tirtodipuran.`

Sesudah (177 karakter):
`... menulis & creative visual journaling, snack, lunch & beverages time, dan sun-down walking around Tirtodipuran.`

**Kenapa digabung ke butir makanan, bukan jadi butir sendiri.** Kalimatnya berisi tiga
butir yang semuanya frasa deskriptif. Menyisipkan `beverages` sebagai butir keempat
membuatnya jadi satu kata telanjang di antara tiga frasa, dan itu persis yang terbaca
sebagai tempelan. Digabung, jumlah butirnya tetap tiga, gaya ampersandnya tetap, kata
`time` tetap memayungi ketiga bendanya, dan iramanya tidak berubah.

## Ampersand, dan kenapa saya memeriksanya per kode karakter

Kalimat ini memuat ampersand, dan hari ini lantai baru saja kena kelas cacat di mana
escape bertumpuk tiap kali ditulis ulang. Jadi saya tidak memakai mata.

| Pemeriksaan | Hasil |
|---|---|
| panjang `excerpt.raw` | 177, persis yang dihitung di muka |
| `&` mentah | 2, di posisi 75 dan 118 |
| `&amp;` | 0 |
| `&#038;` | 0 |
| karakter non-ASCII | 0 |

Nol penumpukan escape. `&#038;` yang terlihat di HTML tersaji itu pengkodean normal
WordPress dan sudah begitu sebelum saya menyentuhnya, jadi bukan kerusakan.

**Satu tes saya sendiri gagal lebih dulu, dan itu bagus.** Assert pertama saya menuntut
jumlah ampersand naik dari 2 jadi 3. Salah: saya membuang satu `&` dan menambah satu,
jadi jumlahnya memang tetap 2. Assert itu berhenti sebelum apa pun ditulis, jadi nol
kiriman rusak keluar. Tesnya yang saya betulkan, bukan datanya.

## Verifikasi produksi

11 permukaan disapu. Kalimat lama **nol** di semua, kalimat baru muncul di tiga
tempat yang sama seperti sebelumnya, dan pemeriksaan escape ganda **nol** di semuanya.

Diff post: yang berubah cuma `excerpt.raw`, `excerpt.rendered`, `modified`,
`modified_gmt`, dan dua tautan riwayat revisi. Nol field isi lain tergeser.

## Temuan di luar kartu [SUDAH DIPUTUSKAN UMAR DAN DIKERJAKAN, lihat susulan di bawah]

**Halaman acaranya sendiri, `/acara/embracing-growth/`, NOL mencetak kalimat aktivitas
ini.** Yang ditampilkan di sana daftar `isi_kit`, field yang berbeda, isinya delapan
butir dan salah satunya `Snacks & Lunch`.

Artinya kalau Umar membuka halaman acara mencari kata Beverages, dia tidak akan
menemukannya, dan itu bukan kegagalan kartu ini. Kalimat yang dia kutip memang
`excerpt`, dan `excerpt` sudah benar.

**Saya tidak menyentuh `isi_kit`, dan itu disengaja.** Daftar kit adalah pernyataan
tentang apa yang didapat peserta atas harga yang dibayar, jadi menambahkan butir ke
sana adalah klaim komersial, bukan penyuntingan gaya bahasa. Itu keputusan Umar.

Kalau memang minuman termasuk yang didapat peserta, kartu lanjutannya satu baris:
tambahkan satu butir ke `isi_kit`.

---

# Susulan 7 Sep 2026: Beverages dipindah ke blok yang benar

Umar: "beverages nya belum tampil", dan screenshotnya blok **Yang disediakan**.
Dia benar, dan **sasaran kartu awalnya memang salah**. Ini bukan kiriman yang
gagal, ini kotak yang keliru.

## Di mana Beverages sebenarnya mendarat

Di `excerpt`, kalimat aktivitas, persis seperti kartu T-35 minta. Bukan di blok
yang Umar lihat. Diperiksa dulu sebelum menambah yang kedua, supaya nol menulis
dua kali untuk satu fakta.

## Blok "Yang disediakan" itu field apa

`dd-kit` di panel `.fakta`, dirakit `isi-beranda.php:1109`: pakai
`disediakan_teks` kalau terisi, kalau kosong jatuh ke `tjr_v5_kit_acara()` yang
menggabung larik `isi_kit` dengan koma.

`disediakan_teks` **kosong**, jadi **`isi_kit` sumbernya**. Satu field, dirender
di dua tempat: halaman acara dan kartu di beranda.

## Keputusan penempatan, dan saya sempat salah sekali

Percobaan pertama saya menggabung jadi satu butir `Snacks, Lunch & Beverages`,
menyalin bentuk yang saya pakai di kalimat aktivitas. **Itu salah, dan kelihatan
begitu keluaran produksinya dibaca:**

> A5 Notebook, Writing Kit, **Snacks, Lunch & Beverages**, Sharing Deco Station...

Daftarnya dipisah **koma**, jadi butir yang memuat koma di dalamnya nol bisa
dibedakan dari dua butir terpisah. Pembaca melihat sembilan butir, bukan delapan.

Diperbaiki jadi butir berdiri sendiri:

> A5 Notebook, Writing Kit, **Snacks & Lunch, Beverages**, Sharing Deco Station...

**Aturannya, dan ini berlaku umum:** kalau pemisah daftar sebuah karakter, butirnya
nol boleh memuat karakter itu. Sekarang sembilan butir, nol yang memuat koma.

## Kalimat aktivitas: saya CABUT, dan ini alasannya

Kartu memberi saya pilihan. Saya cabut, jadi kembali `snack & lunch time`.

- **Beverages barang yang disediakan, bukan aktivitas.** Alasan god sendiri, dan
  sesudah melihat kedua blok berdampingan saya setuju.
- **Satu fakta sebaiknya dinyatakan di satu tempat.** Sekarang blok Yang
  disediakan memuatnya, jadi menyebutnya lagi di kalimat aktivitas cuma duplikasi.
- **Ambiguitas koma yang sama juga muncul di sana.** `snack, lunch & beverages
  time` punya penyakit yang persis sama dengan butir gabungan tadi.

## Bukti dari produksi, bukan dari basis data

Blok Yang disediakan, diambil dari HTML tersaji lalu didekode, di **dua** tempat
yang merendernya:

> A5 Notebook, Writing Kit, Snacks & Lunch, Beverages, Sharing Deco Station, Tools, 2 Instax Polaroid, Documentation, New Friends

Sapuan 14 permukaan: varian berkoma **nol**, pengkodean ganda **nol**. `Beverages`
muncul di tiga permukaan, yaitu halaman acara, beranda, dan REST, dan itu memang
semua tempat `dd-kit` dirender.

## Batas kartu, diperiksa di produksi

Judul `“This is My First Time Too!”: Embracing My Growth`, harga `Rp260.000`,
tanggal `27 September 2026`. Ketiganya utuh. Diff ACF: **tepat satu field**,
`isi_kit`.
