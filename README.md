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

Tema hidup di `wordpress/theme-v5/`, tapi WordPress mencari `style.css` persis di
akar folder tema. Jadi ada repo kedua yang isinya cuma tema, dengan akar repo
sama dengan akar tema:

**https://github.com/omaraazees/tjr-v5-theme**

Repo itu tidak diedit langsung. Isinya didorong dari sini:

```
./bin/dorong-tema.sh
```

### Sekali saja, di hPanel Hostinger

1. Buka **Website, thejournalingroom.id, Tingkat lanjut, GIT**
2. Klik **Hubungkan dengan GitHub**, izinkan aksesnya
3. Pilih repositori `tjr-v5-theme`, branch `main`
4. Isi direktori tujuan: `public_html/wp-content/themes/tjr-v5`
5. Nyalakan **Auto deployment** kalau mau tema ikut berubah tiap kali didorong
6. Aktifkan tema TJR v5 dari **wp-admin, Tampilan, Tema**

Setelah itu alurnya: edit tema di repo ini, commit, jalankan `./bin/dorong-tema.sh`,
lalu Hostinger menarik sendiri.
