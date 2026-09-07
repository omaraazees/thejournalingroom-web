# T-30: foto kembar di beranda dan resolusi yang disajikan

**Nol kredit Magnific. Nol upscale AI. Semua dari master yang sudah ada di repo.**

## 1. Sapuan: bukan satu foto kembar, tapi EMPAT

Umar menemukan satu. Sapuan seluruh beranda menemukan empat, dan yang paling penting
**tidak bisa ditemukan dari nama berkas**, karena nama berkasnya berbohong.

`artotel-08-hero.webp`, `artotel-08-700.webp`, dan `artotel-08.jpg` **isinya bukan
artotel-08**. Ketiganya potongan **artotel-13**, foto rombongan mengangkat jurnal di depan
blinds. Itulah sebabnya sapuan berbasis nama berkas melihatnya sebagai dua foto berbeda,
sementara mata Umar langsung melihatnya sebagai satu foto yang sama.

Jadi sapuannya kuulang **berbasis ISI**, mencocokkan tiap berkas terkirim ke master aslinya
lewat perbandingan piksel (grayscale 48x48, dicoba beberapa potongan: penuh, 16:9, 9:16,
kotak, 4:3). Hasilnya:

| foto | muncul di | keterangan |
|---|---|---|
| **artotel-13** | hero (latar) + penutup (kanan) | temuan Umar, tersembunyi di balik nama artotel-08 |
| **sundayreads-27** | hero (cetakan) + penutup (cetakan 1) | |
| **radian-24** | tentang (cetakan) + penutup (cetakan 2) | |
| **artotel-17/18** | tentang (lanskap) + kolaborator (cetakan) | dua potongan adegan lantai yang sama |

**Seksi penutup ternyata 100 persen daur ulang**: ketiga fotonya dipinjam dari seksi lain.
Itu sebabnya ia terasa redundan, bukan cuma satu fotonya.

Nama berkas lain yang juga berbohong, ditemukan sambil jalan dan belum kuperbaiki karena di
luar lingkup kartu: `pasar-jakal-05.jpg` isinya pasar-jakal-06, `pasar-jakal-06.*` isinya
pasar-jakal-07, `artotel-16.*` isinya artotel-17, `latar-meja.jpg` isinya potongan artotel-17.

## 2. Pilihan pengganti, dan alasannya

Aturan yang kupakai: penggantinya harus beda SUASANA, bukan cuma beda berkas. Waktu kulihat
kandidat lanskapnya, hampir semuanya **rombongan berbaris mengangkat jurnal**, yaitu genre
yang sama persis dengan hero. Mengganti hero-lineup dengan lineup lain tidak menyelesaikan
apa pun. Dari 166 foto, cuma kolondjono-01/02 yang benar-benar kandid, dan keduanya nyaris
identik satu sama lain, jadi cuma satu yang boleh dipakai.

| tempat | sebelum | sesudah | alasan |
|---|---|---|---|
| hero, latar | artotel-13 | **artotel-13, tetap** | foto rombongan terkuat, dan hero itu wajah pertama situs |
| hero, cetakan | sundayreads-27 | **tetap** | jadi unik sendirinya begitu penutup diganti |
| tentang, lanskap | artotel-17/18 | **tetap** | adegan lantai lebih cocok jadi lanskap |
| kolaborator, cetakan | artotel-17/18 | **artotel-05** | mural Artotel, langsung terbaca "Artotel" |
| penutup, kanan | artotel-13 | **kolondjono-01** | kandid di kedai, bukan barisan berpose |
| penutup, cetakan 1 | sundayreads-27 | **wardah-06** | dua orang mengerjakan jurnal berdampingan |
| penutup, cetakan 2 | radian-24 | **pasar-jakal-08** | tangan menempel hiasan, detail kerajinan |

**Kenapa yang diganti kolaborator, bukan tentang.** Cetakan kolaborator punya caption
"Artotel, Apr 2026". Kalau kuisi foto venue lain, captionnya jadi bohong. Jadi kuganti
dengan **foto Artotel yang lain**, dan captionnya tetap benar. Caption penutup juga ikut
diganti mengikuti fotonya: "Sunday Reads" jadi "Wardah", "Radian" jadi "Pasar Jakal".
Alt text ketiganya ditulis ulang sesuai gambar baru.

Hasil akhir: **nol foto muncul dua kali**, diverifikasi ulang berbasis isi, bukan nama.

## 3. Resolusi: sebelum dan sesudah

| tempat | sebelum | sesudah | piksel |
|---|---|---|---|
| hero besar | 1280x720, 84,0 KB | **1600x900, 85,5 KB** | +56% |
| hero kecil | 700x394, 60,0 KB | 700x394, **51,4 KB** | sama, lebih ringan |
| penutup kanan | 600x336, 48,1 KB | **800x600, 82,8 KB** | +138% |
| penutup cetakan 1 | 300x401, 27,1 KB | 320x320, 22,6 KB | +14% terlihat |
| penutup cetakan 2 | 300x401, 18,3 KB | 320x320, 30,8 KB | +14% terlihat |
| kolaborator cetakan | 540x960, **85,5 KB** | 320x320, **38,7 KB** | -47 KB |

Total keenam berkas: **323,0 KB jadi 311,8 KB**. Lebih ringan 11 KB, sementara hero naik
56 persen piksel dan foto penutup naik 138 persen.

**Dua temuan ukuran yang menjelaskan kenapa bisa lebih ringan sekaligus lebih tajam.**

Pertama, `.cetakan img` di CSS itu `aspect-ratio:1` di dalam kartu selebar maksimum 132 px,
jadi **cetakan polaroid tampil sekitar 116 px persegi**. Berkas 300x401 berarti seperempat
tingginya dipotong CSS dan tidak pernah terlihat, dan berkas kolaborator 540x960 seberat
85,5 KB itu **tujuh kali lebih besar dari slot yang menampungnya**. Menaikkan cetakan ke
600 px akan jadi pemborosan persis seperti yang kutolak di T-2, jadi tidak kulakukan.

Kedua, kebalikannya: `.ajakan-kanan` tampil sekitar **727 px** (kolom kanan grid 1fr/1.1fr
di dalam contentSize 1580 px), sedangkan berkasnya cuma 600 px. **Foto itu diperbesar
browser bahkan di layar biasa.** Dan aspeknya salah: CSS-nya `aspect-ratio:4/3` sementara
berkasnya 16:9, jadi dipotong lagi. Sekarang 800x600, sudah 4:3, jadi tidak dipotong.

Satu bug kecil ikut beres: srcset hero menyatakan `1600w` padahal berkasnya 1280x720.
Sekarang berkasnya benar-benar 1600 px, jadi keterangannya jujur.

## 4. Selamat di kedua keadaan CDN, dan batas jujurnya

CDN **mati**, kuverifikasi sendiri: nol header CDN, `server: LiteSpeed`, dan beranda 97.523
byte identik di 4 permintaan berturut-turut.

Semua berkas baru **di bawah 91 KB**, terbesar 85,5 KB. 91 KB itu ambang terukur di
`TIKET-HOSTINGER.md`: 0 gagal dari 6 permintaan.

**Tapi aku tidak mau menyebut itu "aman" tanpa kualifikasi.** Pemotongannya terjadi di titik
**tetap 32.391 byte**. Artinya satu-satunya ukuran yang benar-benar KEBAL adalah yang lebih
kecil dari 32.391 byte, karena berkas segitu tidak punya apa-apa untuk dipotong. 91 KB itu
"nol gagal dari 6 permintaan", sampel kecil, bukan bukti kebal.

Dari enam berkas baru, yang benar-benar kebal cuma dua: wardah-06 (22,6 KB) dan
pasar-jakal-08 (30,8 KB). Empat sisanya ada di pita 32 sampai 91 KB, yaitu **teramati aman,
bukan terbukti aman**.

Menahan SEMUA gambar di bawah 32 KB berarti hero maksimum sekitar 700 px, dan itu
menghancurkan desainnya. Jadi jawaban jujurnya: ini pilihan terbaik dengan bukti yang ada,
dan **perbaikan sebenarnya tetap di tangan Hostinger**. Selama tiketnya belum dijawab,
jangan terbitkan apa pun di atas 91 KB.

**Yang TIDAK kusentuh dan masih jadi risiko terbesar:** 15 fallback jpg 273 sampai 385 KB
yang kulaporkan di T-2. Semua slot yang kuubah memakai `<img>` webp tanpa fallback jpg,
jadi nol bom baru kutambahkan, tapi yang lama masih di sana.

## 5. Batas master, dan satu hal yang gratis

Hero sekarang disajikan **100 persen resolusi masternya** (master artotel-13 = 1600x900).
Tidak bisa lebih tinggi lagi tanpa master yang lebih besar. Slot penutup butuh ~727 px dan
dapat 800 px, jadi sekitar 1x, bukan 2x retina. Keduanya mentok di anggaran byte, bukan di
kualitas master.

Kalau suatu saat hero mau benar-benar tajam di layar retina, jalannya **HEIC 4032 px di
perangkat Umar**, bukan AI. Gratis, dan sudah kusebut di T-2.

## 6. Pertimbangan yang bukan teknis

Foto-foto ini orang sungguhan di kelas Umar, jadi kusebut walau tidak diminta:

- Hero adalah foto rombongan dengan sekitar 25 wajah yang bisa dikenali, dan sekarang ia
  gambar paling besar dan paling menonjol di situs. Itu keputusan Umar, bukan keputusanku,
  tapi layak dia sadari.
- Pengganti kupilih menyebar ke **lima sesi dan venue berbeda** (Kolondjono, Wardah, Pasar
  Jakal, Artotel), jadi tidak ada satu peserta pun yang mendominasi beranda.
- pasar-jakal-08 cuma memperlihatkan tangan, nol wajah. Aman untuk dipakai di mana pun.

## 7. Berkas yang kupegang

Enam berkas gambar baru di `wordpress/theme-v5/assets/img/` dan tiga pattern:
`patterns/hero-panggung.php`, `patterns/ajakan-whatsapp.php`, `patterns/pita-kolaborator.php`.

`bin/periksa-php.sh`: 11 berkas PHP, 0 gagal. **Belum dikirim.** Berkas lama sengaja
dibiarkan di tempatnya, nol ditimpa, jadi kalau perlu mundur cukup kembalikan patternnya.
