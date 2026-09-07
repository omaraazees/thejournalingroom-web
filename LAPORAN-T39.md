# T-39: latar body 320 KB jadi 24,5 KB

**Selesai dan tayang.** Ukuran sebelum **320.444 byte**, sesudah **24.512 byte**.
13,1x lebih kecil, turun 92,4%, dan **289 KB hilang dari setiap kunjungan halaman
mana pun** karena dia latar `body`.

## Premis Jim diverifikasi ulang, dan benar

`latar-meja.jpg` 320.444 byte, 2200x1237, dipasang di `style.css:32` sebagai
latar `body`. Saya ukur sendiri alih-alih memakai angkanya, dan angkanya cocok.

## Kenapa boleh ditekan sekeras itu

Fotonya bukan tekstur, dia foto meja berisi tulisan tangan yang terbaca. Tapi
cara dia dipakai yang menentukan, dan itu saya periksa sebelum memilih ukuran:

- `.wp-site-blocks` adalah lembar kertas **buram** dengan `max-width` dan margin,
  jadi fotonya cuma terlihat di **talang samping** dan bingkai tipis, 8px di
  ponsel.
- `body::before` melapisinya dengan peredam gelap `radial-gradient` **12% sampai
  38%**.

Jadi dia dekoratif dan selalu terlihat di balik peredam. Perbandingan visual di
skala yang benar-benar terlihat, yaitu talang 360px pada layar 1920, nyaris tak
terbedakan dari aslinya, dan itu bahkan **sebelum** peredamnya dipasang.

## Ukurannya dipilih dengan margin, bukan sekadar lolos

`1000x562 q42 = 24.512 byte`, dan dia di bawah **dua** ambang yang berdiri
sendiri-sendiri:

| Ambang | Nilai | Selisih |
|---|---|---|
| terbukti aman | 32.391 | **7.879 byte di bawah** |
| titik potong yang pernah teramati | 28.210 | **3.698 byte di bawah** |

Kandidat bermutu tertinggi yang masih lolos ambang pertama adalah `1100px q55`
pada 31.496 byte, tapi itu cuma **895 byte** di bawah ambang. Margin setipis itu
tidak pantas untuk kartu yang tujuannya justru menjinakkan bom, jadi saya ambil
lebar yang sama dengan mutu lebih rendah dan margin empat kali lebih lebar.

## Nol fallback JPEG, dan itu diukur bukan diasumsikan

Kartu meminta fallback kalau CSS memungkinkan. Saya ukur dulu, dan jawabannya
**tidak boleh**:

| Kandidat JPEG | Byte | |
|---|---|---|
| 900px q35 | 33.445 | di atas ambang |
| 1000px q35 | 38.169 | di atas ambang |
| 1100px q35 | 43.284 | di atas ambang |

Pada lebar dan mutu serendah apa pun yang masih layak, JPEG-nya tetap di atas
ambang. **Memasang fallback JPEG berarti memasang ulang bom yang persis sedang
dijinakkan kartu ini**, cuma untuk peramban yang jumlahnya mendekati nol.

Cadangannya sudah ada dan bagian dari desain sejak awal: warna `meja` di
deklarasi yang sama, dan `theme.json` menamainya
**"Meja (warna cadangan latar foto)"**. Peramban tanpa dukungan WebP melihat
warna itu, bukan halaman kosong.

## Nama diversikan, bukan ditimpa

`latar-meja-v2.webp`. `.htaccess` menyetel gambar `max-age` setahun, dan satu URL
di situs ini pernah mengembalikan tiga generasi berbeda yang semuanya valid.

Berkas 2200px aslinya **sengaja disimpan** sebagai sumber turunan, alasannya sama
dengan `artotel-08` di T-14. `CATATAN.md` diperbarui supaya "nol dirujuk" di situ
tidak dibaca sebagai "mati".

## Pengiriman, gerbang penuh

- disk bersih terhadap HEAD
- rencana: `kirim 2, sama 97, hapus 0`, tepat dua berkas yang dimaksud
- **sisi hapus** lewat `--coba --hapus`: `hapus 0`, diperiksa sebelum mengirim
- kirim: `kirim 2, sama 97, hapus 0`
- sesudahnya `lokal 99, server 99, kirim 0`
- `CATATAN.md` tidak ikut terkirim, saringan bekerja

## Verifikasi dari produksi

**8 permintaan, empat di antaranya dengan cache-buster. 8 dari 8 utuh:**

- `200`, **24.512 byte** persis, kedelapan kalinya
- md5 identik kedelapan kalinya, dan **cocok dengan berkas lokal**
  `43c90c5905fc5cff469b3559fb5dffa9`
- panjang RIFF di header cocok dengan panjang berkas, kedelapan kalinya
- semuanya lewat `server: LiteSpeed`

CSS di produksi menunjuk `latar-meja-v2.webp`, dan **rujukan ke `latar-meja.jpg`
tersisa nol**. Ketiga halaman yang dicek memuat `style.css` yang benar.

## Syarat angka di laporan ini

Angka keutuhan 8 dari 8 diukur **selagi CDN TJR mati**. Itu justru inti kartunya:
selama CDN mati, berkas 320 KB pun tidak pernah gagal. Nilai perubahan ini muncul
**begitu CDN dinyalakan lagi**, dan saat itu terjadi, yang perlu diukur ulang
bukan berkas ini saja melainkan seluruh aset.
