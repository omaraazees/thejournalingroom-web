# Cara mengirim tema, dan cara membuktikan kirimannya benar

README menjelaskan **mekanik** skripnya: bendera, kredensial, manifes, hash.
Berkas ini menjelaskan **urutan dan gerbangnya**: apa yang diperiksa sebelum
mengirim, apa yang dibuktikan sesudahnya, dan apa yang skrip ini tidak bisa
lakukan. Ditulis 7 Sep 2026 sesudah empat pengiriman berturut turut (kartu T-10
sampai T-12), dan angka di dalamnya hasil pengukuran, bukan taksiran.

Kalau kamu cuma punya waktu membaca satu bagian, baca yang pertama.

## 1. Gerbang pengiriman adalah DISK, bukan `git status`

Skrip menyusun daftar kirimannya dari sistem berkas:

```python
LOKAL = AKAR / "wordpress" / "theme-v5"
for p in LOKAL.rglob("*"):
    if not p.is_file() or p.name in LEWATI: continue
    rel = p.relative_to(LOKAL)
    if tersembunyi(rel): continue          # ada segmen berawalan titik
```

Bukan dari git. Konsekuensinya, dan ini pernah nyaris kejadian:

- Berkas tema yang **belum di-commit**, bahkan yang belum pernah `git add`,
  tetap berangkat ke server.
- `bin/dorong-tema.sh` memang menolak jalan kalau pohon kerja kotor, tapi itu
  menjaga **repo tema**, bukan menjaga **server**. Dua jalur terpisah.

Jadi memeriksa `git status` saja tidak cukup. Yang benar, banding isi disk lawan
isi git di folder tema:

```bash
python3 - <<'PY'
import subprocess, os
akar = 'wordpress/theme-v5'
tracked = set(subprocess.run(['git','ls-files',akar],capture_output=True,text=True).stdout.split())
disk = {os.path.join(r,n) for r,_,f in os.walk(akar) for n in f}
print('di disk tapi tidak di git:', sorted(disk-tracked) or 'NOL')
print('di git tapi tidak di disk:', sorted(tracked-disk) or 'NOL')
PY
```

Dua duanya harus NOL. Arah keduanya penting, dan alasannya beda:

- **Di disk tapi tidak di git** berarti ada yang akan tayang tanpa jejak commit.
- **Di git tapi tidak di disk** berarti daftar hapus akan berisi berkas server
  yang sah. Lihat bagian 3.

## 2. Urutan sebelum mengirim

```bash
# 1. gerbang disk lawan git di atas, dua duanya NOL
# 2. sintaks PHP
bash bin/periksa-php.sh                       # 11 berkas, 0 gagal
# 3. pohon kerja
git status --short --untracked-files=no       # harus kosong
# 4. rencana kiriman
python3 bin/kirim-tema-ftp.py --coba          # baca daftarnya, cari yang asing
# 5. berangkat
bash bin/dorong-tema.sh                       # repo tema dulu
python3 bin/kirim-tema-ftp.py                 # baru server
```

Jangan menjalankan `--coba` terlalu pagi. Daftar kirimannya berubah setiap kali
siapa pun menyimpan berkas, jadi dry run yang dijalankan setengah jam sebelum
berangkat cuma menghasilkan jawaban basi.

`dorong-tema.sh` dijalankan **sekalipun kartu cuma menyebut FTP**. Kalau
dilewatkan, repo tema menyimpan berkas yang sudah dibuang repo utama, dan
divergensi itu baru ketahuan berbulan bulan kemudian.

## 3. Kontrak `--hapus`, dan kenapa ia butuh dua gerbang

`--hapus` **tidak** menghapus satu berkas yang kamu maksud. Ia menghapus seluruh
himpunan berkas server yang tidak ada di lokal:

```python
buang = sorted(set(remote) - set(lokal)) if hapus else []
```

Perhatikan `if hapus`: jalan biasa **tidak pernah menghapus apa pun**.
Penghapusan hanya terjadi kalau benderanya diberikan.

Karena daftarnya himpunan, ia butuh gerbang di **dua** sisi:

**Gerbang (a), MASUKAN.** Buktikan sisi lokal utuh lebih dulu, dengan
perbandingan disk lawan git di bagian 1. Ini bukan formalitas: `buang` dihitung
dari `remote - lokal`, jadi satu berkas lokal yang hilang diam diam akan
menghapus berkas server yang sah, dan dry run akan melaporkannya dengan tenang
seolah itu benar.

**Gerbang (b), KELUARAN.** Cetak daftar lengkapnya, bukan jumlahnya:

```bash
python3 bin/kirim-tema-ftp.py --coba --hapus
```

`--coba` mencetak daftar kirim berawalan `  + ` dan daftar hapus berawalan
`  - `, lalu `return` sebelum menulis apa pun termasuk manifes.

Lanjut **hanya** kalau daftar hapus berisi persis berkas yang dimaksud. Kalau
lebih, kalau lain, atau kalau kosong: berhenti. Jangan menyaring manual, jangan
menghapus di luar skrip, jangan mengakali daftarnya. Daftar yang tidak sesuai
artinya alat ini bukan alat yang tepat untuk pekerjaan itu.

### Keterbatasan yang perlu diketahui, bukan ditambal

**Skrip ini tidak bisa menghapus satu berkas tertentu.** Seluruh permukaan
argumennya tiga bendera boolean:

```python
hapus  = "--hapus"  in sys.argv
coba   = "--coba"   in sys.argv
teliti = "--teliti" in sys.argv
```

Nol argumen jalur, nol pola. Jadi menghapus satu berkas selalu berarti
mempercayakan bahwa `remote - lokal` kebetulan berisi satu anggota, dan itulah
sebabnya gerbang (b) ada. Kalau suatu saat ada dua berkas yatim dan cuma satu
yang ingin dibuang, alat ini tidak cukup.

### Membuang berkas aset yang ADA DI REPO butuh dua langkah

`git rm` saja tidak cukup. Berkas itu sudah pernah terkirim, jadi kalau berhenti
di commit, ia berubah jadi **yatim di server**. Urutannya: `git rm` dan commit,
lalu kirim dengan `--hapus` melewati kedua gerbang di atas.

## 4. Verifikasi sesudah mengirim, lima lapis

Yang pertama paling sering dilewatkan dan paling sering menyelamatkan.

**Lapis 1, potret daftar server sebelum dan sesudah, bandingkan nama demi nama.**
Bukan hitungannya. Hitungan yang cocok bisa menyembunyikan satu berkas hilang
dan satu berkas baru sekaligus.

```bash
python3 - <<'PY'
import importlib.util
spec = importlib.util.spec_from_file_location("k","bin/kirim-tema-ftp.py")
m = importlib.util.module_from_spec(spec); spec.loader.exec_module(m)
host,user,sandi = m.kredensial(); ftp = m.sambung(host,user,sandi)
r = m.daftar_remote(ftp, m.REMOTE)
for k in sorted(r): print(f"{k}\t{r[k]['size']}")
ftp.quit()
PY
```

Simpan keluarannya sebelum dan sesudah, lalu diff. Bandingkan tiga hal: yang
hilang, yang baru, dan yang ukurannya berubah.

**Lapis 2, isi asli di server.** `--coba --teliti` mengunduh dan mem-hash isi
server, bukan membaca manifes. Ini satu satunya cara membuktikan "yang tayang
sama dengan repo" tanpa menebak:

```bash
python3 bin/kirim-tema-ftp.py --coba --teliti   # sekitar 32 detik
```

Ia juga membocorkan berkas yatim: kalau `server` lebih besar dari `lokal`, ada
berkas di server yang tidak ada di repo.

**Lapis 3, HTTP.** Berkas yang dihapus harus 404, berkas yang dipakai harus
terlayani seukuran berkasnya.

**Lapis 4, halaman.** Sapu semua URL terbit, hitung penanda yang relevan dengan
perubahannya. Contoh yang dipakai 7 Sep: jumlah `<h1>` per halaman, jumlah
`<dl>`/`<dt>`/`<dd>`, dan nol rujukan ke berkas yang dibuang.

**Lapis 5, sintaks bukan jaminan.** `periksa-php.sh` menangkap salah ketik, nol
fatal saat jalan seperti fungsi yang tidak ada. Buka situsnya.

## 5. HTTP 200 tidak berarti isinya utuh

Hostinger memotong respons berkas besar secara acak **sambil tetap mengirim
`content-length` penuh dan status 200**. Terukur 7 Sep 2026:

| Berkas | Ukuran | Utuh | Terpotong |
|---|---|---|---|
| `artotel-08-1600.webp` | 246096 | 8 dari 12 | 4 dari 12, selalu berhenti di 28210 byte |
| `artotel-08-hero.webp` | 86024 | 8 dari 8 | 0 |

Ini terjadi pada **HTML juga**, bukan cuma gambar: satu permintaan `/kontak/`
putus dengan `IncompleteRead(17754 bytes read, 1996 more expected)`.

Akibatnya untuk siapa pun yang menulis skrip verifikasi:

- **Wajib punya retry**, jangan sekali tembak. Sekitar satu dari sepuluh gagal.
- Kalau memeriksa berkas, **hitung byte** dan bandingkan dengan ukuran
  sebenarnya. Jangan percaya `%{http_code}`.
- Kalau memeriksa halaman, ulangi sampai lolos lalu catat berapa kali gagal.

Akar masalahnya di sisi hosting dan belum ditangani.

### Membedakan "terpotong" dari "generasi lama"

Ukuran yang beda dari yang diharapkan punya dua sebab yang sangat berbeda, dan
obatnya berlawanan. Bedakan lewat **penanda akhir format**, bukan lewat ukuran:

```bash
python3 -c "
b=open('a.jpg','rb').read()
print(len(b), b[:2].hex(), b[-2:].hex())   # JPEG utuh: ffd8 ... ffd9
"
```

- Penanda akhir **hilang** berarti **terpotong**. Ulangi permintaannya.
- Penanda akhir **ada** tapi ukurannya beda berarti **generasi lama di edge
  CDN**. `.htaccess` menyetel `max-age` gambar satu tahun, jadi mengganti berkas
  dengan nama sama membuat beberapa edge menyajikan generasi berbeda dari satu
  URL. Mengirim ulang **tidak** menyembuhkannya kalau origin sudah benar;
  obatnya purge di hPanel **Performa > CDN > Flush cache**, dan itu menu yang
  BERBEDA dari "Cache Manager" yang layernya lain.

Kalau ragu origin atau edge: `--coba --teliti` membaca lewat FTP, jadi ia
melihat origin. Kalau `--teliti` bilang identik tapi HTTP memberi ukuran lain,
yang basi itu edge.

## 6. Mengukur perubahan tema TANPA mengirimnya

Berguna waktu kartu melarang deploy, atau waktu ingin tahu efek sebuah
perubahan sebelum ia tayang. Semuanya dijalankan di halaman **live** di Chrome.

**Perubahan CSS.** Salin blok aturan yang kamu ubah apa adanya dari `style.css`,
suntik sebagai `<style>` terakhir di kaskade. Selektornya sama, jadi urutan yang
menang, dan hasilnya sama dengan berkas baru. Lalu ukur dengan
`getBoundingClientRect()`.

**Perubahan token `theme.json`.** Cukup setel ulang custom property-nya
(`--wp--preset--color--*`) di `:root`, karena itu persis yang dicetak WordPress
dari `theme.json`.

**Perubahan markup.** Ganti `el.outerHTML` dengan hasil transformasinya, lalu
bandingkan `getBoundingClientRect()` tiap baris sebelum dan sesudah. Ini cara
membuktikan sebuah perubahan struktur **nol menggeser tata letak**.

**Lebar layar lain.** `resize_window` tidak menggigit di jendela Chrome yang
ter-maximize. Yang berhasil: `<iframe>` selebar 390 atau 768 yang memuat
situsnya, karena iframe punya viewport sendiri untuk media query dan
same-origin jadi isinya bisa diukur dari luar.

Satu jebakan yang sudah memakan waktu: ukur dengan halaman dalam keadaan
tenang. Mengklik tombol lalu membaca terlalu cepat pernah menghasilkan angka nol
yang mengejutkan dan salah. Kalau sebuah angka mengejutkan, ukur dua kali
sebelum melaporkannya.

## 7. Sandbox memblokir soket keluar

Di mesin kerja ini, koneksi keluar dan bind soket diblokir sandbox dan gagal
dengan `PermissionError: [Errno 1] Operation not permitted`. Yang kena:

- `python3 bin/kirim-tema-ftp.py` (FTP)
- `bash bin/dorong-tema.sh` (`git push`)
- `python3 -m http.server` (bind), jadi server statis lokal bukan pilihan, dan
  itu sebabnya teknik di bagian 6 dipakai

Jalankan perintah perintah itu dengan sandbox dimatikan. Kegagalannya bukan bug
skrip.

## 8. Template di database SELALU menang atas berkas tema

Kalau seseorang pernah menyimpan template lewat Site Editor, salinannya masuk
database dan **mengunci** berkas temanya. Situs tetap terlihat benar selama
isinya sama, tapi setiap suntingan berkas tema sesudah itu akan terlihat "tidak
berefek" tanpa satu pun error muncul. Bug yang mahal dilacak justru karena
senyap.

Periksa dan bersihkan lewat WP REST, bukan klik, supaya bisa diverifikasi
sebelum dan sesudah:

```bash
set -a; . ~/.tjr-wp; set +a     # WP_URL, WP_USER, WP_APP_PASSWORD

# template mana yang sumbernya database
curl -sS -u "$WP_USER:$WP_APP_PASSWORD" \
  "$WP_URL/wp-json/wp/v2/templates?per_page=100&_fields=id,slug,source"

# isi lengkapnya, context=edit wajib untuk content.raw
curl -sS -u "$WP_USER:$WP_APP_PASSWORD" \
  "$WP_URL/wp-json/wp/v2/templates/tjr-v5//single?context=edit"

# hapus, sama persis dengan "Clear customizations" di Site Editor
curl -sS -X DELETE -u "$WP_USER:$WP_APP_PASSWORD" \
  "$WP_URL/wp-json/wp/v2/templates/tjr-v5//single?force=true"
```

Semua template sehat berbunyi `"source": "theme"`. Yang berbunyi `"custom"`
sedang mengunci berkasnya.

**Sebelum menghapus, bandingkan isinya dengan berkas tema.** Kalau database
ternyata memuat perubahan yang tidak ada di git, menghapusnya berarti membuang
kerja orang. Perlu diketahui supaya tidak salah alarm: WordPress **menyuntik
sendiri** `"theme":"tjr-v5"` ke blok `template-part` waktu menyimpan, jadi
salinan database wajar sedikit lebih panjang daripada berkasnya (pernah terukur
837 lawan 803 byte) tanpa ada perbedaan perilaku. Simpan salinannya dulu, dan
simpan **di luar** `wordpress/theme-v5/` supaya tidak ikut terkirim.

## 9. Jangan menaruh apa pun di dalam folder tema yang tidak mau tayang

`wordpress/theme-v5/` dicermin ke `wp-content/themes/tjr-v5` di web server
publik. Berkas catatan, backup, potret, atau apa pun yang bersifat kerja
internal harus hidup di luar folder itu.

Yang disaring skrip ada dua, dan bentuknya sengaja berbeda:

```python
LEWATI = {"CATATAN.md"}                          # daftar, per nama berkas

def tersembunyi(rel):                            # predikat, per segmen jalur
    return any(bagian.startswith(".") for bagian in rel.parts)
```

Kalau menambah alat baru yang menulis folder goresnya sendiri, **periksa dulu apakah
nama foldernya berawalan titik.** Kalau ya, kamu nol perlu melakukan apa pun di sini.

**KALAU TIDAK, BERKASNYA AKAN TERKIRIM KE SERVER PUBLIK, DIAM DIAM.** Predikat ini
menyaring **titik di awal segmen**, bukan "berkas gores" sebagai gagasan. Nama seperti
`_cache/`, `tmp-agent/`, atau `:memory:.ses` LOLOS sepenuhnya.

Itu bukan kekhawatiran teoretis: `:memory:.ses` **ada sekarang di akar repo ini**.
Dia selamat cuma karena kebetulan berada di luar `wordpress/theme-v5/`, bukan karena
saringannya menangkapnya. Kalau alat berikutnya menulisnya satu tingkat lebih dalam,
dia berangkat.

Jadi untuk folder gores yang namanya nol berawalan titik: tambahkan namanya ke
`LEWATI`, atau lebih baik, suruh alatnya menulis di luar folder tema.

Dulu instruksinya menambahkan tiap nama ke sebuah daftar tolak.
Itu dihapus di kartu T-36, karena daftar tolak menuntut kita mengetahui setiap hal
buruk di muka, dan yang tidak disebut justru **terkirim** ke server publik. Predikat
tetap benar untuk hal yang belum ada.

Yang lolos daftar lama dan tertahan predikat ini, ketiganya pernah benar-benar
terjadi di repo orang: folder alat yang belum dikenal, `.env` yang nyasar ke dalam
folder tema, dan `.git` yang tertinggal di `assets/`. Dua yang terakhir lebih buruk
daripada berkas gores: satu rahasia, satu seluruh riwayat.

Arahnya tidak berlebihan. Berkas yang cuma **memuat** kata seperti
`patterns/x.claude.php` tetap terkirim, karena tidak ada segmen jalurnya yang
berawalan titik. Ini diuji di `uji-kirim-tema.py` nomor 11, dan pohon ujinya sengaja
memakai folder yang belum pernah disebut di mana pun, supaya yang teruji
mekanismenya bukan daftarnya.

Yang masih butuh tangan cuma berkas yang **tidak** berawalan titik dan tidak boleh
tayang, seperti `CATATAN.md`. Tambahkan ke `LEWATI` dan ke `.gitignore`, lalu
jalankan `python3 bin/uji-kirim-tema.py`.

## 9b. Kalau Umar menyunting langsung: jalankan `--teliti` sebelum mengirim

Sejak 7 Sep 2026 Umar menyunting berkas proyek **langsung**, tanpa lewat kartu dan
tanpa pengumuman. Untuk TJR itu berarti sebuah berkas tema bisa berubah **di
server** tanpa jejak apa pun di sisi kita.

**Bahayanya bukan konflik, melainkan pembatalan yang diam.** Skrip kirim
memperlakukan lokal sebagai sumber kebenaran. Berkas yang diubah orang di server
muncul di daftar kirim **persis sama** dengan berkas yang kamu ubah sendiri di
lokal, lalu ditimpa tanpa satu pun peringatan. Nol error, nol konflik, dan
suntingan tangan pemilik file hilang.

**Gerbangnya satu perintah, dan dia membaca ISI ASLI server bukan manifes:**

```bash
python3 bin/kirim-tema-ftp.py --coba --teliti
```

`kirim 0` berarti seluruh berkas server identik dengan lokal, jadi nol ada yang
disunting tangan. Kalau ada yang muncul dan kamu **nol** menyentuhnya di lokal,
**BERHENTI**: itu suntingan orang, bukan kiriman yang tertinggal. Tanya dulu.

Skrip juga melaporkan baris terpisah untuk berkas yang **disentuh tapi isinya
sama**:

```
disentuh di server tapi isinya SAMA: 1
  ~ style.css  stempel 20260907140000 jadi 20260907152233
```

Isinya identik jadi **aman ditimpa**, dan berkasnya memang **nol dikirim**. Tapi
stempel yang bergerak berarti **ada yang membuka dan menyimpannya**, dan itu
peringatan dini bahwa seseorang sedang bekerja di berkas itu. Kalau kamu nol
merasa menyentuhnya, tanya dulu sebelum mengirim apa pun ke folder itu.

Laporan ini sengaja dicetak **sebelum** manifes diperbarui, karena pembaruan
manifes menulis stempel server yang baru dan dengan itu **menghapus satu satunya
bukti** bahwa berkasnya pernah disentuh. Diuji di `uji-kirim-tema.py` nomor 13,
yang memeriksa dua hal sekaligus: laporannya muncul, **dan** berkasnya tetap nol
dikirim.

Ongkosnya sekitar 50 detik karena seluruh isi server diunduh dan di-hash. Itu
murah dibandingkan membatalkan suntingan tangan pemilik file tanpa dia tahu.

Diukur 7 Sep 2026 pukul 22.00 WIB: 100 dari 100 berkas identik, nol suntingan
tangan.

## 10. Menulis data acara: pakai `bin/tulis-acara.py`, jangan curl langsung

Perubahan isi acara (harga, judul, excerpt, isi kit) masuk lewat WP REST, bukan
lewat kiriman tema. Jangan menulisnya dengan `curl` polos.

```bash
python3 bin/tulis-acara.py 12 --set acf.harga=265000 --coba   # rencana saja
python3 bin/tulis-acara.py 12 --set acf.harga=265000          # kirim
```

**Kenapa bukan curl.** Pola yang wajar dipakai orang adalah baca, susun, kirim,
lalu diff. Diff sesudah menulis itu **detektor, bukan gerbang**: dia memberi tahu
apa yang terjadi, dia nol menghentikan apa pun.

Kalimat Oscar yang dipakai lantai: gerbang yang cuma mengenali nilai yang kamu
**harapkan** bukan gerbang, itu cuma penghindar pekerjaan ganda. Gerbang
sungguhan menolak **semua** yang tidak dikenal.

**Bahayanya paling besar di ACF.** REST menolak kiriman sebagian, jadi seluruh
objek `acf` harus dikirim ulang. Kalau bacaanmu basi, kamu diam diam
**mengembalikan** perubahan orang lain di field **lain**, bukan cuma di field
yang kamu sentuh.

Skrip itu membaca dua kali: sekali untuk menyusun rencana, sekali lagi tepat
sebelum mengirim. Kalau ada satu pun field isi yang bergeser di antaranya, dia
berhenti dan menyebutkan field mana. `modified`, `_links`, dan `generated_slug`
diabaikan karena berubah sendiri tiap penyimpanan, tapi `modified` tetap
dilaporkan sebagai tripwire.

Sisi gagalnya diuji, bukan diasumsikan: enam kasus, tiga yang harus menyalakan
gerbang dan tiga yang tidak boleh.

### Judul acara: tulis `title`, JANGAN `acf.judul_acara`

Dua field memuat judul yang sama, dan salah satunya **cermin**. Menulis cermin
adalah penulisan yang **berhasil tanpa berefek**: nol error, nol peringatan, dan
judulnya nol berubah.

```bash
python3 bin/tulis-acara.py 12 --set title='Judul baru'          # benar
python3 bin/tulis-acara.py 12 --set acf.judul_acara='Judul baru' # JANGAN
```

Yang sudah **terbukti**, dibaca dari kode dan dari data:

- `post_title` sumber kebenarannya. Slug, tautan, dan daftar dasbor memakai dia.
- `tjr_v5_muat_judul_acara()` mengisi kolom ACF itu dari `get_the_title()` tiap
  kali layar edit dibuka, jadi nilai yang muncul di REST **dihitung ulang**,
  bukan dibaca dari simpanan.
- `tjr_v5_simpan_judul_acara()` justru **menghapus** meta `judul_acara` lalu
  menulis `post_title`.
- Buktinya di data: `meta` post cuma berisi `_acf_changed` dan `footnotes`.
  **Nol `judul_acara` tersimpan di sana.**

Yang **belum diuji dan sengaja nol diuji di produksi**: apa persisnya yang
tertinggal kalau seseorang benar-benar menulis `acf.judul_acara` lewat REST.
Kemungkinannya meta yatim yang nol pernah dibaca. Yang pasti, judulnya nol ikut
berubah, karena yang dipakai `post_title`.

**Bentuk umumnya, dan dia berulang di luar sini:** kalau dua field memuat fakta
yang sama, cari dulu mana yang **diturunkan**, lalu tulis **sumbernya saja**.
Penulisan ke salinan turunan berhasil dengan tenang dan hilang tanpa suara.

## 11. Mengecilkan aset: ambang, dan cara memilih yang benar

Dipakai di kartu T-39 dan lanjutannya, 7 Sep 2026. Ditulis di sini karena
metodenya berulang dan angkanya mahal didapat.

### Dua ambang, dan keduanya dipakai

| Ambang | Byte | Asalnya |
|---|---|---|
| terbukti aman | 32.391 | ambang yang dipakai lantai |
| titik potong teramati | 28.210 | berkas 246 KB dulu dipotong **tepat** di angka ini |

Yang di bawah **keduanya** punya dua alasan aman yang berdiri sendiri-sendiri.
Itu yang dikejar, bukan sekadar lolos yang pertama.

### Pilih dengan MARGIN, bukan sekadar lolos

Godaannya mengambil kandidat bermutu tertinggi yang masih lolos. Di T-39 itu
`1100px q55` pada 31.496 byte, cuma **895 byte** di bawah ambang. Margin setipis
itu nol pantas untuk pekerjaan yang tujuannya menjinakkan bom. Yang dipakai
`1000px q42` pada 24.512, yaitu 7.879 di bawah ambang pertama dan 3.698 di bawah
ambang kedua.

### Format bukan obat, UKURAN PIKSEL yang biasanya salah

- Foto padat detail: WebP memberi **13x**.
- **PNG beralfa yang sudah dioptimalkan: WebP cuma memberi 1,5 sampai 1,7x.**

Jadi sebelum menyalahkan format, hitung dulu berapa piksel yang **benar-benar
dibutuhkan** ukuran tampilnya. Itu yang biasanya memberi keuntungan besar.

### Pakai lossless kalau isinya garis tipis

Logo TJR tulisan tangan, dan catatan di `tjr_v5_logo_bar()` mencatat garis rambut
huruf sambungnya hilang kalau diperkecil sembarangan. Untuk isi begini, WebP
**lossless** menghapus seluruh pertanyaan artefak: yang berubah cuma penyampelan.

### Ukur mutu pada UKURAN TAMPIL, dan pasang kontrol dulu

Membandingkan pada ukuran berkas menjawab pertanyaan yang salah. Yang penting
rupanya pada tinggi atau lebar yang benar-benar dirender.

**Dua jebakan yang keduanya pernah kejadian:**

1. **Gambar beralfa nol bisa dibandingkan sesudah `convert("RGB")`.** Logo hitam
   di atas transparan menyimpan seluruh bentuknya di kanal **alfa**. Membuang
   alfa menyisakan dua bidang hitam identik, dan selisihnya **0,00 untuk apa
   pun**. Benarnya: `alpha_composite` ke warna latar tempat gambar itu duduk,
   baru buang alfa.
2. **Selalu jalankan kontrol yang HARUS menyala** sebelum mempercayai angka
   kandidat, terutama sebelum mempercayai nol. Jebakan nomor 1 di atas ketahuan
   justru karena kontrolnya ikut memberi 0,00.

Angka acuan yang sehat: kandidat memberi beda rata-rata 0,12 sampai 0,25 dari
255, sementara kontrol yang sengaja dibuat buruk memberi 0,72 dan 5,47.

### Versikan nama, jangan menimpa

`.htaccess` menyetel gambar `max-age` setahun, dan satu URL di situs ini pernah
mengembalikan **tiga generasi berbeda yang semuanya valid**.

Berkas asli beresolusi penuh **disimpan** sebagai sumber turunan. Perbarui
`assets/img/CATATAN.md` supaya "nol dirujuk" di situ nol dibaca sebagai "mati".

### Fallback: ukur dulu, jangan diasumsikan

Di T-39 fallback JPEG **ditolak karena diukur**: pada lebar dan mutu serendah apa
pun yang masih layak, JPEG-nya tetap di atas ambang, yang paling kecil 900px q35
sudah 33.445 byte. Memasangnya berarti memasang ulang bom yang sedang dijinakkan.

Cadangan yang benar untuk latar `body` adalah warna di deklarasi yang sama, dan
`theme.json` memang sudah menamainya "Meja (warna cadangan latar foto)".

### Satu cacat metode yang perlu diketahui

**Sapuan aset yang memanen `src` dan `srcset` dari HTML BUTA terhadap aset yang
dirujuk dari CSS.** Latar `body` nol pernah masuk sapuan aset T-31 karena itu.
Kalau menyapu bobot halaman, panen dari CSS juga.

### RUJUKAN BUKAN UNDUHAN, dan ini jebakan yang paling sering mengulang

Sebelum menjumlahkan apa pun sebagai "berat halaman", tanya satu hal:
**apa yang membuat berkas ini BENAR-BENAR diminta peramban, di halaman ini?**
Mengunduh sendiri dengan skrip lalu menjumlahkannya menjawab pertanyaan lain,
yaitu "berapa besar berkas ini kalau diminta", dan dua pertanyaan itu sering
punya jawaban yang jauh berbeda.

Tiga bentuk yang sudah benar benar menipu di proyek ini, 7 Sep 2026:

**1. `srcset` bukan daftar unduhan.** Peramban memilih SATU kandidat menurut
`sizes` dikali DPR. `tjr-mark@2x.png` ada di srcset tapi baru terpilih di DPR
sekitar 3,64 ke atas, jadi praktis nol pernah diunduh.

**2. `@font-face` bukan daftar unduhan.** Berkas font cuma diunduh kalau ada
**glyph yang benar benar dirender** dengan keluarga itu. Aturan CSS yang ADA tapi
nol cocok elemen apa pun **nol memicu unduhan**, dan `document.fonts.ready` nol
menunggunya karena dia nol pernah masuk keadaan loading.
Kasusnya: `Pinyon Script` terdaftar di `theme.json` dan ikut diminta di URL Google
Fonts, tapi **nol dipakai satu elemen pun** di 14 halaman terbit. Aku menjumlahkan
28.072 byte-nya sebagai penghematan, dan penghematannya **nol byte nol milidetik**.
Yang tersisa dari membuangnya cuma kerapian token di editor.

**3. Bobot font variabel berbagi satu berkas.** Manrope 400, 500, dan 700 menunjuk
URL yang sama persis, begitu juga Playfair 400, 500, 600. Jadi **membuang bobot
yang nol dipakai menghemat nol byte**. Bandingkan URL dan md5-nya sebelum
mengusulkan.

**Cara memeriksanya tanpa peramban:** hitung PEMAKAIAN, bukan DEKLARASI. Untuk font,
cari elemen ber-`class="has-<slug>-font-family"` atau `style` yang merujuk varnya,
dan JANGAN menghitung aturan yang mendefinisikan kelas itu, karena WordPress
membuatnya otomatis untuk tiap token terdaftar.
