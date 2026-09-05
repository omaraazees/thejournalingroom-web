# The Journaling Room

Situs untuk The Journaling Room, penyelenggara workshop journaling di Yogyakarta.
Domain: [thejournalingroom.id](https://thejournalingroom.id)

## Isi repo

| Folder | Isi |
| --- | --- |
| `desain/prototipe/v5-fieldtime.html` | Desain final, satu berkas HTML mandiri. Ini sumber kebenaran tampilan. |
| `desain/prototipe/_uji-responsif.html` | Harness uji responsif, delapan lebar berdampingan dalam iframe. |
| `wordpress/theme-v5/` | Tema blok WordPress hasil port dari desain v5. |
| `wordpress/cms/` | Panduan pemakaian untuk pemilik brand, struktur field ACF, logika status acara. |
| `wordpress/foto-hd/` | Foto dokumentasi siap web, dua ukuran per foto plus varian kecil untuk polaroid. |
| `wordpress/logo-web/` | Logo TJR dan sebelas logo kolaborator, sudah dinormalkan jadi tinta tunggal di atas transparan. |
| `konten/` | Riset keyword, meta per halaman, structured data, alt text, checklist SEO. |

## Menjalankan prototipe

Berkas HTML-nya memakai path relatif ke folder `wordpress/`, jadi tidak bisa dibuka
lewat `file://`. Jalankan server statis dari akar repo:

```
python3 -m http.server 8811
```

Lalu buka `http://127.0.0.1:8811/desain/prototipe/v5-fieldtime.html`

## Catatan teknis

- **Tanpa pustaka animasi.** Efek gulir memakai `animation-timeline: view()` bawaan browser,
  pembuka halaman memakai Web Animations API. Nol dependensi runtime.
- **Pembuka halaman** berupa sampul buku yang terbuka pada sumbu kiri. Dilewati kalau
  pengguna memilih `prefers-reduced-motion`, kalau tab ada di latar belakang, atau kalau
  4,2 detik terlewat tanpa animasi selesai. Tanpa JavaScript halaman tetap tampil utuh.
- **Kontras** sudah diaudit, seluruh teks lolos WCAG AA di delapan lebar layar.
- **Foto turunan tidak ikut repo.** `foto-2026`, `foto-webp`, dan `foto-web` dibuat ulang
  dari file asli, lihat `.gitignore`.

## Data yang masih contoh

Jadwal sesi mendatang di prototipe masih data contoh. Tanggal, tempat, dan jumlah kursi
harus diisi dari WordPress sebelum situs dipakai.

## Deploy tema ke WordPress lewat Git

> **PERINGATAN. Direktori tujuan tidak boleh `public_html`.**
> Git deployment di hPanel Hostinger **mengganti** isi folder tujuan, bukan menambah.
> Kalau tujuannya diisi `public_html`, seluruh instalasi WordPress terhapus:
> wp-admin, wp-includes, wp-config.php, semua tema dan plugin. Ini pernah terjadi
> di situs ini pada 5 September 2026 dan harus dipulihkan manual.
> Tujuan yang benar hanya folder tema: `public_html/wp-content/themes/tjr-v5`

Tema hidup di `wordpress/theme-v5/`, tapi WordPress mencari `style.css` persis di
akar folder tema. Jadi ada repo kedua yang isinya cuma tema, dengan akar repo
sama dengan akar tema:

**https://github.com/omaraazees/tjr-v5-theme**

Repo itu tidak diedit langsung. Isinya didorong dari sini:

```
./bin/dorong-tema.sh
```

### Sekali saja, di hPanel Hostinger

1. **Backup dulu.** Website, thejournalingroom.id, Backup, lalu buat backup manual
   file dan database. Jangan lewati langkah ini.
2. Buka **Website, thejournalingroom.id, Tingkat lanjut, GIT**
3. Klik **Hubungkan dengan GitHub**, izinkan aksesnya
4. Pilih repositori `tjr-v5-theme`, branch `main`
5. Isi direktori tujuan **persis** seperti ini, tanpa memakai tombol pilih folder
   yang defaultnya ke akar:

   ```
   public_html/wp-content/themes/tjr-v5
   ```

6. Sebelum menekan simpan, baca ulang isian nomor 5. Kalau isinya `public_html`
   atau kosong, batalkan.
7. Nyalakan **Auto deployment** kalau mau tema ikut berubah tiap kali didorong
8. Aktifkan tema TJR v5 dari **wp-admin, Tampilan, Tema**

Setelah itu alurnya: edit tema di repo ini, commit, jalankan `./bin/dorong-tema.sh`,
lalu Hostinger menarik sendiri.

### Alternatif tanpa risiko

Paket hosting ini tidak punya akses SSH, jadi tidak ada `git pull` manual di server.
Kalau tidak mau memakai Git deployment sama sekali, pakai FTP. Ada skripnya di repo:

```
python3 bin/kirim-tema-ftp.py --coba     # lihat rencana dulu
python3 bin/kirim-tema-ftp.py            # kirim berkas yang berubah
python3 bin/kirim-tema-ftp.py --hapus    # plus bersihkan berkas usang di server
```

Kredensial dibaca dari `~/.tjr-ftp`, tiga baris: host, user, password.
Ambil host dan user di hPanel, File, Akun FTP. Passwordnya diatur sendiri lewat
tombol Ubah password FTP di halaman yang sama.

```
46.202.138.57
u952235165
<password ftp>
```

Sambungannya FTPS, port 21, dengan AUTH TLS. Perhatikan bahwa akar FTP itu home
akun, bukan `public_html`. Situsnya ada di
`/domains/thejournalingroom.id/public_html/`, dan itu yang dipakai skrip.

Skrip ini hanya menyentuh folder tema `tjr-v5`, tidak bisa menghapus instalasi
WordPress. Berkas yang ukurannya sama dilewati, jadi pengiriman kedua dan
seterusnya selesai dalam hitungan detik.

## Pemulihan darurat

Kalau `public_html` kosong atau situs mati total:

1. Backup database dulu lewat phpMyAdmin, ekspor SQL, simpan di luar server.
2. Instal ulang WordPress dari hPanel, Website, Instalasi otomatis. Ini membuat
   database baru dan wp-config.php yang benar.
3. Impor SQL lama ke database baru. Tambahkan `DROP TABLE IF EXISTS` di atas
   tiap `CREATE TABLE` supaya impor tidak bentrok dengan tabel bawaan instalasi baru.
4. Unggah ulang tema ke `wp-content/themes/` dan plugin ke `wp-content/plugins/`.
   Zip harus rata, isinya langsung di akar zip, bukan di dalam satu folder pembungkus.
5. WordPress menonaktifkan plugin yang foldernya hilang. Aktifkan lagi dari
   wp-admin, Plugin.
