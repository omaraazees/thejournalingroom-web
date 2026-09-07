# T-36: saringan kirim dari daftar tolak jadi predikat

> **Catatan penomoran.** Nomor T-36 dipakai DUA kartu. Berkas ini soal SARINGAN
> SKRIP KIRIM. Kartu judul acara ada di `LAPORAN-T36-JUDUL.md`.


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

---

# Susulan: syarat keempat dari Jim, sisi HAPUS

Jim menemukan lubang di syarat yang dipasang god, bukan di rencana saya, dan
temuannya benar. Baris 258: `buang = sorted(set(remote) - set(lokal)) if hapus`.
Himpunan `lokal` yang sama memberi makan daftar **kirim** dan daftar **hapus**,
dan arahnya berlawanan. Menyempitkan `lokal` otomatis **melebarkan** daftar hapus.

Kartunya sampai sesudah pekerjaan selesai, jadi ini pemeriksaan susulan.

## Apakah lubang itu kena ke yang saya bangun

**Tidak, dan alasannya struktural bukan kebetulan.** Saya nol membangun allow-list
ekstensi. Yang saya bangun predikat penolak jalur bertitik, arahnya sama dengan
daftar tolak lama, cuma mekanismenya berbeda.

Buktinya sudah ada sejak sebelum saya menyentuh berkas: `lokal` **identik**,
98 lawan 98, nol beda di kedua arah. Kalau `lokal` identik, maka
`remote - lokal` identik untuk **remote apa pun**. Itu berlaku umum, bukan
cuma untuk keadaan server hari ini.

## Dibuktikan juga secara empiris, dan bukan dengan angka nol yang menipu

`--coba --hapus` terhadap server sungguhan, kedua versi:
`lokal 98, server 98, kirim 0, sama 98, hapus 0`.

Sebelum menjalankannya saya baca dulu kodenya, tidak menuruti jaminan orang:
`buang` dihitung di baris 258, dicetak di 261, lalu `if coba:` di 263 mencetak
rencana, `ftp.quit()`, dan `return`. Nol unggah dan nol hapus dipanggil.

Tapi `hapus 0` di kedua versi itu **bukti lemah**, karena kedua himpunannya
kebetulan berimpit sempurna. Jadi diulang dengan server yang **sengaja tidak
berimpit**, memuat berkas yatim, jalur bertitik, dan tiga jenis berkas di luar
delapan yang dipakai tema sekarang:

| | lama | baru |
|---|---|---|
| kirim | `assets/x.webp`, `inc/seo.php`, `style.css` | sama persis |
| hapus | `.claude/gores.txt`, `README.txt`, `assets/lama.svg`, `fonts/x.woff2`, `yatim.css` | sama persis |

Identik di kedua sisi.

## Kenapa peringatan Jim tetap penting, dan lebih kuat dari alasan saya sendiri

Waktu mengusulkan kartu ini saya menolak allow-list ekstensi dengan alasan yang
lemah: tema wajar menerima jenis berkas baru, jadi allow-list akan menahan kerja
orang tanpa sebab. Itu soal **kenyamanan**.

Alasan Jim soal **kerusakan**, dan itu jauh lebih kuat. Simulasi logikanya, dengan
disk dan server yang cerminan persis sehingga seharusnya nol ada yang dihapus:

| Saringan | `lokal` | kandidat hapus |
|---|---|---|
| predikat titik | 6 | **nol** |
| allow-list 8 jenis | 3 | `assets/ikon.svg`, `assets/logo.avif`, `fonts/inter.woff2` |

Ketiganya **ada di disk dan ada di server**, sah keduanya, dan semuanya tambahan
tema yang masuk akal di masa depan. Sebuah webfont, sebuah ikon, sebuah AVIF.
Allow-list ekstensi akan menandainya untuk dihapus dari server publik.

Jadi bentuk yang god minta di syarat awalnya, kalau saya turuti apa adanya,
justru akan memasang senjata yang menembak ke arah sendiri.

## Uji 12: sisi hapus sekarang punya penjaga tetap

Sisi hapus dulu **nol punya penjaga sama sekali**, dan itu sebabnya lubang ini
bisa lolos. Sekarang ada.

Uji 12 memakai server yang tidak berimpit dan menaruh `fonts/inter.woff2` di
**dua-duanya**, sebagai berkas sah yang harus tetap hidup. Kalau saringannya
suatu hari diubah jadi allow-list ekstensi, berkas itu jatuh dari `lokal`, muncul
di daftar hapus, dan uji ini gagal.

**Suite sekarang 12 dari 12.**
