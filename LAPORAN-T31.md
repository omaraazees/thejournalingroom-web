# T-31: verifikasi ulang TJR live sesudah belasan kiriman dan CDN dimatikan

**Hasil: nol temuan.** Cakupannya di bawah, karena laporan nol temuan cuma berguna
kalau yang diperiksa disebutkan.

Sekitar 250 permintaan HTTP. Baca saja, nol kiriman, nol berkas tema disentuh.

## Yang diperiksa

### 1. CDN benar-benar mati, dan itu terlihat di header
Seluruh respons datang dari `server: LiteSpeed` dengan **nol** header `x-hcdn-*`.
Bandingkan dengan T-12, waktu itu `server: hcdn` dengan `dci-edge3` dan `dci-edge5`,
dan justru itu yang menyajikan generasi basi. Jadi lapisan yang dulu bermasalah
memang sudah tidak ada di jalur.

### 2. Gambar, tempat kerusakan lama muncul
Pemotongan lama mendarat di 32 sampai 39 KB, jadi yang dicari ukuran yang meleset
dan berkas yang tidak berakhir di penanda formatnya.

- **43 aset yang benar-benar dirujuk beranda, 3 permintaan masing-masing, 129 permintaan. Nol gagal.**
- **10 aset terbesar, 5 permintaan masing-masing.** Ukuran identik kelima kalinya,
  md5 tunggal, dan penanda akhir benar semua. JPEG diperiksa `ffd9`, PNG `IEND`,
  WebP dicocokkan panjang RIFF di header dengan panjang berkas sungguhan.
- Yang terbesar `snapobox-08.jpg` 394400 B, `pasar-jakal-02.jpg` 393912 B,
  `kolondjono-20.jpg` 376254 B. Ketiganya kelas ukuran yang dulu paling sering korban.
- **Nol aset mendarat di pita 30 sampai 42 KB**, jadi tidak ada yang perlu mata ekstra.

### 3. Perbaikan lama masih berlaku
| Kartu | Yang diperiksa | Hasil |
|---|---|---|
| T-15/T-16 | satu `h1` per halaman | 14 halaman, **semuanya h1=1** |
| T-1 | kontras `tinta-samar` | `--wp--preset--color--tinta-samar: #756654` hidup, `#7E6F5E` lama **nol** |
| T-1 | panel fakta jadi `<dl>` | `/acara/embracing-growth/` dan beranda: **dl=1 dt=7 dd=7**, persis angka T-1 |
| T-1 | alt gambar hero dan pengantar | terisi, dan ikut terbawa ke turunan `.webp` |
| T-1 | target sentuh | `padding:14px 0` nav dan `padding:13px 0` kaki masih di `style.css` |
| T-19 | label menu Indonesia | `Buka menu` ada, `Open menu` **nol** |
| T-21 | skip-link Indonesia | 14 halaman, `Lewati ke konten` semua, `Skip to content` **nol** |
| T-20 | arsip penulis `noindex` | ada, dengan `follow` |
| T-18 | email pemilik tidak terpajang | arsip penulis **nol**, dan `/wp-json/wp/v2/users` juga nol |

### 4. Permukaan lain
`wp-sitemap.xml`, `/feed/`, `/cerita/feed/`, dua endpoint REST, `/?s=`, 404, dan
`robots.txt`. Semuanya menjawab wajar. `robots.txt` menutup `/wp-admin/` dan
menunjuk sitemap dengan benar.

### 5. Kecepatan dari origin, bahan untuk keputusan CDN
CDN mati berarti semuanya dilayani origin, jadi ini diukur bukan ditebak.

| | median | rentang |
|---|---|---|
| halaman (5 halaman, 4 sampel) | **0,52 sampai 0,65 s** | 0,51 sampai 0,91 s |
| aset besar 385 KB | **0,42 s** | 876 sampai 907 KB/s |

Situsnya **tidak** jadi lambat. Satu-satunya sampel di atas 0,8 s adalah satu
`/jadwal/` yang 0,91 s, dan tiga sampel lain di halaman yang sama di bawah 0,6 s.

## Empat "kegagalan" yang ternyata tes saya yang salah

Saya tulis ini supaya tidak ada yang mengulangi tes naif yang sama lalu panik.
Sapuan pertama saya memunculkan empat GAGAL. Keempatnya cacat tes, bukan cacat situs.

1. **Kontras dicari di `style.css`.** Token palet keluar sebagai properti CSS di
   blok `global-styles` inline di `<head>`, bukan di `style.css`. Salah berkas.
2. **`<dl>` dicari di halaman artikel.** Panel `.fakta` tidak pernah ada di artikel.
   Dia ada di halaman acara dan beranda, dan di sana nilainya benar.
3. **"Nol alt kosong" dipakai sebagai syarat lulus.** Salah. `alt=""` justru BENAR
   untuk gambar hiasan. Dari 36 gambar, 35 beralt isi dan satu yang kosong adalah
   logo `tjr-black.png`, dan memang seharusnya kosong.
4. **Ambang jumlah heading yang saya karang sendiri.** Empat halaman punya tepat
   satu `h1` dan satu `h2`, dan sesudah dibaca isinya memang benar begitu: arsip
   `/format/` dan `/kota/` masing-masing memuat satu acara karena situs cuma punya
   satu acara terbit. Bukan cacat.

## Dua catatan kecil, tidak diusulkan jadi kartu

- `x-powered-by: PHP/8.3.33` terpajang di header. Umum, dan bukan kerentanan sendiri.
- `/wp-json/wp/v2/users` memuat `"url":"http://thejournalingroom.id"` dengan `http`.
  Kosmetik, isi kolom profil, nol pengaruh ke pengunjung.

## Jawaban atas tawaran membantah

Kartu ini **tidak** tipis dan saya tidak membantahnya. Alasan god benar: nol cacat
yang diketahui bukan nol cacat, dan hari ini kita sudah kena satu kelas kejutan
di mana satu URL mengembalikan tiga generasi berbeda.

Dan saya juga tidak punya usulan pekerjaan pengganti. Sesudah sapuan ini saya tidak
menemukan sesuatu di TJR yang lebih pantas dikerjakan sekarang.
