# Audit Logo Kolaborator

Sumber: `wordpress/logo-web/`, 12 berkas PNG. Semua angka diukur di kanal alpha, ambang tinta alpha lebih dari 200.

Cara baca kolom:

- **Tinta %**: persentase piksel kanvas yang alpha-nya di atas 200.
- **Alpha 1..200 %**: piksel setengah tembus, gabungan anti aliasing tepi huruf dan sisa latar.
- **Noise lepas %**: bagian dari piksel setengah tembus yang jaraknya lebih dari 3 piksel dari tinta mana pun. Ini yang benar benar sisa latar, karena anti aliasing selalu menempel di tepi huruf. Angka di atas 0,3 persen berarti latar belakang belum bersih.
- **Bounding box tinta**: kotak terkecil yang memuat semua piksel alpha di atas 200, format `x0, y0, x1, y1`.
- **Padat tepi %**: berapa persen panjang tiap sisi kanvas yang berisi tinta. Ini pembeda antara pangkasan rapat (angka kecil) dan tinta yang benar benar tergunting (angka besar).

## Tabel hasil

| Logo | Ukuran | Tinta % | Alpha 1..200 % | Noise lepas % | Bounding box tinta | Tepi tersentuh | Padat tepi % atas/bawah/kiri/kanan | Vonis |
|-|-|-|-|-|-|-|-|-|
| `amco.png` | 367x300 | 21.86 | 12.40 | 0.71 | 0, 1, 367, 300 | kiri, kanan, bawah | 0.0 / 2.7 / 0.7 / 10.3 | PERIKSA |
| `artotel.png` | 1208x300 | 34.78 | 15.38 | 0.78 | 4, 5, 1203, 300 | bawah | 0.0 / 6.5 / 0.0 / 0.0 | PERIKSA |
| `hanasui.png` | 1910x300 | 25.79 | 8.18 | 0.00 | 2, 1, 1907, 298 | tidak ada | 0.0 / 0.0 / 0.0 / 0.0 | AMAN |
| `heejaz.png` | 377x300 | 22.99 | 13.19 | 0.11 | 3, 0, 374, 300 | atas, bawah | 46.9 / 46.9 / 0.0 / 0.0 | BAHAYA |
| `kupiku.png` | 978x300 | 25.74 | 13.65 | 0.22 | 0, 0, 978, 300 | kiri, atas, kanan, bawah | 91.4 / 91.4 / 49.3 / 31.7 | BAHAYA |
| `pasar-jakal.png` | 480x300 | 37.91 | 8.20 | 0.00 | 2, 2, 479, 298 | tidak ada | 0.0 / 0.0 / 0.0 / 0.0 | AMAN |
| `radian.png` | 256x300 | 22.75 | 20.03 | 0.03 | 2, 0, 256, 300 | atas, kanan, bawah | 1.6 / 15.6 / 0.0 / 4.7 | PERIKSA |
| `snapobox.png` | 1118x300 | 46.59 | 6.74 | 0.00 | 0, 0, 1118, 300 | kiri, atas, kanan, bawah | 4.0 / 3.8 / 7.3 / 3.3 | PERIKSA |
| `statement-beauty.png` | 901x300 | 55.69 | 6.06 | 0.00 | 0, 0, 901, 300 | kiri, atas, kanan, bawah | 66.3 / 49.2 / 8.0 / 10.3 | BAHAYA |
| `sundayreads.png` | 457x300 | 16.37 | 6.70 | 0.00 | 1, 0, 457, 300 | atas, kanan, bawah | 0.4 / 0.4 / 0.0 / 1.0 | PERIKSA |
| `tjr-black.png` | 900x465 | 7.39 | 3.52 | 0.00 | 1, 0, 900, 464 | atas, kanan | 0.1 / 0.0 / 0.0 / 0.4 | PERIKSA |
| `wardah.png` | 1556x300 | 24.16 | 13.74 | 0.34 | 0, 0, 1556, 295 | kiri, atas, kanan | 7.9 / 0.0 / 4.0 / 49.3 | BAHAYA |

## Logo yang bounding box-nya menyentuh empat sisi kanvas

Ada 3 berkas: `kupiku.png`, `snapobox.png`, `statement-beauty.png`.

Menyentuh empat sisi biasanya jadi tanda latar belakang ikut terbawa. Untuk tiga berkas ini bukan itu penyebabnya. Kalau ada kotak latar solid, tinta akan mendekati 100 persen kanvas, sementara yang tertinggi di sini cuma 55.69 persen. Yang terjadi: berkas dipangkas terlalu rapat sampai tinta ikut tergunting.

- `kupiku.png` dan `statement-beauty.png` benar benar terpotong. Padat tepinya 91 dan 66 persen.
- `snapobox.png` utuh. Padat tepinya cuma 3 sampai 7 persen, jadi ini pangkasan rapat yang wajar, cuma tanpa bantalan kosong.

Latar yang benar benar terbawa justru muncul di logo lain, dan tidak ketahuan dari bounding box. Kolom **Noise lepas %** yang menangkapnya: `artotel.png` 0,78 persen, `amco.png` 0,71 persen, `wardah.png` 0,34 persen. Piksel piksel ini adalah bercak tekstur kertas atau marmer yang tersebar di area kosong kanvas, tidak menempel di huruf mana pun. Kelihatan jelas kalau logo dipasang di atas warna yang lebih gelap dari latar aslinya.

## Temuan per berkas

### `amco.png`

Huruf A terpotong di tepi kiri dan huruf O terpotong di tepi kanan, dan baris BAKEHOUSE terpangkas di bawah. Selain itu ini salah satu dari dua berkas yang membawa sisa latar: 0,71 persen kanvas berisi bercak tekstur kertas yang tidak menempel di huruf mana pun.

Vonis PERIKSA. Dipakai di seksi: kolaborator.

### `artotel.png`

Baris YOGYAKARTA menempel di tepi bawah, 6,5 persen tepi bawah terisi tinta, jadi bagian bawah huruf besar kemungkinan terpangkas beberapa piksel. Sisa latar paling tinggi dari semua logo, 0,78 persen noise lepas.

Vonis PERIKSA. Dipakai di seksi: kolaborator.

### `hanasui.png`

Paling bersih dari sisi pangkasan. Bounding box tidak menyentuh sisi mana pun dan noise lepas nol. Tidak perlu diapa apakan.

Vonis AMAN. Dipakai di seksi: kolaborator.

### `heejaz.png`

Monogram terpotong rata di atas dan bawah, masing masing 46,9 persen tepi terisi tinta. Bentuk simetris di logo ini kehilangan ujung atas dan ujung bawahnya. Perlu potong ulang dari berkas sumber.

Vonis BAHAYA. Dipakai di seksi: kolaborator.

### `kupiku.png`

Paling parah bersama statement-beauty. Bingkai persegi panjang di sekeliling wordmark berhimpit persis dengan batas kanvas: 91,4 persen tepi atas dan tepi bawah terisi tinta, dan sudut sudut bingkainya hilang. Huruf U terakhir juga tergunting di kanan, 31,7 persen tepi kanan terisi tinta.

Vonis BAHAYA. Dipakai di seksi: kolaborator.

### `pasar-jakal.png`

Bersih. Bounding box berjarak 2 piksel dari semua sisi, noise lepas nol. Aman dipakai apa adanya.

Vonis AMAN. Dipakai di seksi: kolaborator.

### `radian.png`

Baris tagline MIND & BODY DEVELOPMENT CENTER terpotong di bawah, kiri, dan kanan. Huruf pertama dan terakhir tagline hilang sebagian. Alpha setengah tembus 20 persen, tertinggi dari semua logo, tapi noise lepasnya cuma 0,03 persen. Artinya itu bukan sisa latar melainkan tagline yang terlalu kecil untuk kanvas 300 piksel sehingga jadi bubur anti aliasing.

Vonis PERIKSA. Dipakai di seksi: kolaborator.

### `snapobox.png`

Bounding box menyentuh empat sisi tapi padat tepi cuma 3 sampai 7 persen dan noise lepas nol. Ini pangkasan rapat, bukan latar terbawa, dan wordmark-nya utuh. Yang kurang cuma bantalan kosong, jadi logo terlihat mepet kalau berdampingan dengan logo lain.

Vonis PERIKSA. Dipakai di seksi: kolaborator.

### `statement-beauty.png`

Rusak. Kata STATEMENT terpotong di atas, 66,3 persen tepi atas terisi tinta, dan di bawah, 49,2 persen. Kata BEAUTY hilang seluruhnya dari kanvas. Yang tersimpan cuma potongan tengah wordmark.

Vonis BAHAYA. Dipakai di seksi: kolaborator.

### `sundayreads.png`

Bounding box menyentuh atas, kanan, dan bawah tapi padat tepi di bawah 1 persen dan noise lepas nol. Pangkasan rapat yang wajar, logo utuh.

Vonis PERIKSA. Dipakai di seksi: kolaborator.

### `tjr-black.png`

Logo utama The Journaling Room. Tinta cuma 7,39 persen, alpha setengah tembus 3,52 persen, noise lepas nol. Paling bersih dari semua berkas. Ekor huruf g pada Journaling menyentuh tepi kanan sepanjang 0,4 persen, praktis tidak kelihatan.

Vonis PERIKSA. Dipakai di seksi: global, tentang, arsip, kolaborator, cta.

### `wardah.png`

Huruf h terakhir terpotong di tepi kanan, 49,3 persen tepi kanan terisi tinta, jadi wordmark terbaca sebagai Warda dengan potongan huruf terakhir. Noise lepas 0,34 persen, ada sisa latar juga.

Vonis BAHAYA. Dipakai di seksi: kolaborator.

## Ringkasan prioritas

| Prioritas | Logo | Alasan |
|-|-|-|
| 1 | `statement-beauty.png` | Kata BEAUTY hilang, STATEMENT terpotong atas dan bawah. Ini logo yang salah, bukan cuma kurang rapi. |
| 2 | `kupiku.png` | Bingkai dan huruf terakhir tergunting, 91 persen tepi atas dan bawah terisi tinta. |
| 3 | `wardah.png` | Huruf terakhir terpotong, plus sisa latar 0,34 persen. Merek paling besar, salah potong paling kelihatan. |
| 4 | `heejaz.png` | Monogram terpotong atas dan bawah. |
| 5 | `radian.png` | Tagline terpotong tiga sisi dan terlalu kecil untuk kanvas 300 piksel. |
| 6 | `artotel.png`, `amco.png` | Terpotong tipis di satu sampai tiga sisi, dan dua duanya masih membawa sisa latar. |
| 7 | `snapobox.png`, `sundayreads.png`, `tjr-black.png` | Utuh, cuma tanpa bantalan. Tambah ruang kosong 4 sampai 8 persen kalau mau rapi. |
| Aman | `hanasui.png`, `pasar-jakal.png` | Tidak perlu diapa apakan. |

## Saran teknis

- Ambil ulang tujuh logo bermasalah dari berkas vektor kolaborator berformat SVG, AI, atau EPS, bukan dari tangkapan layar. Noise lepas di amco, artotel, dan wardah membuktikan berkas sekarang berasal dari raster yang latarnya dihapus manual.
- Standarkan kanvas: tinggi 300 piksel dengan bantalan kosong minimal 6 persen di semua sisi, supaya bounding box tidak pernah menyentuh tepi dan barisan logo di seksi kolaborator terlihat rata.
- Sediakan versi 2x setinggi 600 piksel. Di v5 logo tampil selebar 164 piksel CSS, jadi 300 piksel masih cukup untuk retina pada logo yang pendek, tapi `hanasui.png` dan `wardah.png` yang lebar akan terlihat lunak kalau nanti dipasang lebih besar.
- Warna tinta sudah seragam rgb(31, 24, 17) untuk semua kolaborator, hanya `tjr-black.png` yang murni hitam rgb(0, 0, 0). Kalau mau satu nada, samakan tjr-black ke rgb(31, 24, 17) juga.
- Karena semua logo ini satu warna tinta di atas transparan, format paling tepat sebenarnya SVG. Dua belas PNG ini totalnya 547 KB, versi SVG-nya kemungkinan besar di bawah 60 KB dan tajam di semua ukuran.
