# Nama berkas di folder ini TIDAK semuanya jujur

Berkas ini tidak ikut terkirim ke server: `LEWATI` di `bin/kirim-tema-ftp.py` melewatkan nama
`CATATAN.md`, jadi aman ditulis di sini.

## Kenapa catatan ini ada

Sebagian berkas di folder ini **isinya bukan foto yang disebut namanya**. Akibatnya nyata:
siapa pun yang memilih foto dari namanya akan salah pilih, dan sapuan duplikat berbasis nama
akan bilang dua berkas itu foto berbeda padahal foto yang sama. Itu persis yang terjadi di
kartu T-30: mata Umar melihat satu foto dipakai dua kali, sementara namanya bilang dua foto
berbeda. Yang benar mata Umar.

## Cara memeriksa, jangan percaya nama

Cocokkan isi berkas ke master di `wordpress/foto-2026/` berbasis piksel: grayscale 48x48,
bandingkan ke beberapa potongan master (penuh, 16:9, 9:16, 1:1, 4:3, 3:4), ambil selisih
rata-rata absolut terkecil. Di bawah 6 = yakin. 6 sampai 25 = kemungkinan potongan ketat.
Di atas 25 = jangan simpulkan apa apa tanpa melihat gambarnya sendiri.

## Berkas yang namanya tidak cocok dengan isinya

| berkas | isi sebenarnya | skor | keyakinan |
|---|---|---|---|
| `artotel-08-700.webp` | artotel-13 | 1.8 | yakin |
| `artotel-08-hero.webp` | artotel-13 | 1.9 | yakin |
| `artotel-08.jpg` | artotel-13 | 1.7 | yakin |
| `artotel-16-lebar-v2.jpg` | artotel-18 | 28.4 | KURANG YAKIN, potongan sangat ketat |
| `artotel-16-lebar-v2.webp` | artotel-18 | 28.4 | KURANG YAKIN, potongan sangat ketat |
| `artotel-16-lebar.jpg` | artotel-18 | 20.0 | cukup yakin, potongan ketat |
| `artotel-16-lebar.webp` | artotel-18 | 20.0 | cukup yakin, potongan ketat |
| `artotel-16-v2.webp` | artotel-18 | 0.3 | yakin |
| `artotel-16.jpg` | artotel-17 | 1.7 | yakin |
| `artotel-16.webp` | artotel-17 | 1.7 | yakin |
| `latar-meja.jpg` | - | 43.3 | BUKAN dari foto-2026 (2200x1237, lebih besar dari master mana pun) |
| `pasar-jakal-05.jpg` | pasar-jakal-06 | 1.7 | yakin |
| `pasar-jakal-06.jpg` | pasar-jakal-07 | 1.7 | yakin |
| `pasar-jakal-06.webp` | pasar-jakal-07 | 1.8 | yakin |

`latar-meja.jpg` **namanya jujur**. Isinya memang meja kerja dengan jurnal, pena, dan stiker.
Ia cuma bukan berasal dari `foto-2026`, jadi pencocokan otomatis wajar memberi skor tinggi.
**Skor tinggi berarti tidak tahu, bukan berarti bohong.**

## Yang sudah dibereskan (kartu T-32)

Semua rujukan di KODE tema sekarang memakai nama yang jujur. Berkas bernama salah di atas
masih ada di folder ini dan di server, tapi **nol dirujuk kode**. Sengaja tidak dihapus:
menghapus butuh `--hapus` yang bekerja pada seluruh himpunan `remote - lokal`, dan
membiarkannya berarti pembatalan cukup mengembalikan rujukannya.

Kalau menambah berkas baru, **beri nama sesuai foto sumbernya di `foto-2026`**, lalu tambahkan
peran dan versi: `<foto>-<peran>-v<N>.<ext>`, contoh `artotel-13-panggung-v1.webp`.
