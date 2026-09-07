# T-36: saringan kirim dari daftar tolak jadi predikat

**Selesai. Nol kiriman dilakukan.** Diuji dengan `--coba` dan suite uji.

## Yang diubah

`bin/kirim-tema-ftp.py`. Dua daftar tolak jadi satu daftar plus satu predikat.

Sebelum:
```python
LEWATI        = {".DS_Store", "CATATAN.md"}
LEWATI_FOLDER = {".claude", ".cc-writes"}
```

Sesudah:
```python
LEWATI = {"CATATAN.md"}

def tersembunyi(rel):
    return any(bagian.startswith(".") for bagian in rel.parts)
```

`.DS_Store` tidak hilang dari saringan, dia cuma pindah mekanisme: namanya
berawalan titik, jadi predikat menangkapnya tanpa perlu disebut. `CATATAN.md`
tetap di daftar karena dia satu-satunya yang harus dibuang tapi tidak berawalan
titik.

## Syarat 3 dipenuhi: daftar kiriman identik

Ini syarat yang paling penting, jadi dibuktikan dua kali dengan cara berbeda.

**Sebelum menyentuh berkas apa pun**, kedua logika dijalankan berdampingan atas
pohon tema yang sungguhan: **lama 98, baru 98, nol beda di kedua arah.**

**Sesudah perubahan**, `--coba` terhadap server sungguhan: `lokal 98 berkas,
server 98 berkas, kirim 0, sama 98, hapus 0`. Sama persis dengan sebelumnya.

Perilakunya tidak berubah untuk keadaan sekarang. Yang berubah cuma apa yang
terjadi pada keadaan yang **belum** ada.

## Kenapa perubahannya berarti

Diuji atas jalur yang belum pernah ada. Ketiganya lolos saringan lama:

| Jalur | Lama | Baru |
|---|---|---|
| `.cursor/rules.md` folder alat yang belum dikenal | terkirim | **ditahan** |
| `inc/.env` rahasia nyasar ke dalam tema | terkirim | **ditahan** |
| `assets/.git/config` repo tertinggal | terkirim | **ditahan** |
| `patterns/x.claude.php` nama memuat kata, tapi sah | terkirim | terkirim |

Baris terakhir penting: predikatnya per **segmen jalur**, bukan pencocokan nama,
jadi dia tidak berubah jadi saringan yang kelewat rakus.

Dua baris tengah lebih buruk daripada berkas gores yang dulu ditambal. Satu
rahasia, satu seluruh riwayat, dan keduanya akan mendarat di
`wp-content/themes/tjr-v5` di web server publik.

## Uji

Suite lama tetap **10 dari 10**, lalu ditambah satu jadi **11 dari 11**.

Uji 11 sengaja menguji **mekanismenya, bukan daftarnya**: pohon ujinya memakai
`.alat-baru/` yang belum pernah disebut di mana pun, plus `inc/.env` dan
`assets/.git/config`, plus `patterns/x.claude.php` sebagai penjaga arah
sebaliknya. Kalau saringannya suatu hari dikembalikan jadi daftar, uji ini gagal.

**Uji ini sempat merah sekali, dan itu parsing saya sendiri.** Saya memotong
prefiks keluaran dengan `split(" ", 1)` padahal barisnya berawalan dua spasi,
jadi yang terbaca `"+ style.css"` bukan `"style.css"`. Isi himpunannya sudah
benar sejak awal. Tesnya yang saya betulkan.

## Dokumentasi

`bin/CARA-KIRIM.md` bagian 1 dan 9 diperbarui, karena **instruksi perawatannya
jadi usang**, bukan cuma kodenya. Dulu bagian 9 menyuruh menambahkan folder gores
alat baru ke `LEWATI_FOLDER`. Sekarang jawabannya: tidak perlu melakukan apa pun,
selama nama foldernya berawalan titik.

Yang masih butuh tangan cuma berkas yang tidak berawalan titik dan tidak boleh
tayang, dan sampai sekarang cuma ada satu, `CATATAN.md`.

## Catatan untuk Jim

Cara memakai skripnya **tidak berubah sama sekali**. Bendera sama, keluaran sama,
daftar kiriman sama. Yang berubah cuma satu hal yang tidak pernah perlu dia
lakukan lagi: mendaftarkan folder gores alat baru sebelum mengirim.
