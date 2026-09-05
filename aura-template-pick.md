# Aura Template Pick — The Journaling Room

Disaring dari pencarian: workshop, craft, creative studio, retreat, art therapy, gsap, parallax, plus katalog Meng To (968 template). Kriteria: palet hangat, struktur event/booking, ruang buat foto banyak, tipografi serif, dan motion yang beneran ada.

## Data ukur

Tiap kandidat dibuka live preview-nya, lalu diukur langsung dari DOM di dalam iframe. Ini bukan tebakan dari thumbnail.

| Template | Slug | Tinggi | Section | Elemen | GSAP | Lenis | Canvas | Keyframes | Plan |
|---|---|---|---|---|---|---|---|---|---|
| ØDE Atelier | `ode-atelier` | 19.873px | 12 | 457 | ya | ya | 0 | 0 | PRO |
| Koisei Cultural Journey | `koisei` | 17.824px | 6 | 236 | ya | ya | 3 | 2 | PRO |
| Koisei Immersive Storytelling | `koisei-minimalist` | 8.904px | 6 | 145 | tidak | tidak | 1 | 2 | PRO |
| Veloura Skincare | `veloura-skincare-39` | 8.678px | 8 | 387 | ya | tidak | 0 | 3 | PRO |
| ÂTMA Wellness Hotel | `tma-luxury-wellness-85` | 7.809px | 8 | 360 | tidak | tidak | 0 | 0 | Gratis |
| Elysian Editorial | `elysian` | 7.379px | 6 | 231 | ya | tidak | 1 | 1 | PRO |
| **Asagiri Artisanal Matcha** | `asagiri-38matcha` | 7.200px | 8 | 238 | ya | ya | 1 | 2 | PRO |
| Somatic Sanctuary | `somatic-sanctuary` | 5.637px | 7 | 772 | ya | ya | 0 (2 video) | 2 | PRO |
| Lumora Artisan | `lumora-luxury-62` | 4.213px | 5 | 167 | tidak | tidak | 0 | 0 | Gratis |

Live preview bisa dibuka tanpa Pro di `<slug>.aura.build`.

Catatan: ÂTMA sama sekali tidak punya animasi scroll, cuma 56 transition hover. Lumora diklaim "immersive parallax" tapi di halaman jadinya tidak ada GSAP maupun keyframes. Semua yang motion-nya hidup memang PRO.

Kata kunci `collage`, `sketchbook`, `scrapbook` nol hasil di Aura. Tidak ada template yang literal bertema journaling. Yang paling dekat adalah kelompok craft-ritual Jepang: Asagiri dan dua Koisei.

## Pilihan utama

### Asagiri Artisanal Matcha
`aura.build/templates/asagiri-38matcha` — Vannarot Roeung, PRO, 2,6k views

Ini yang paling nyambung sama tema journaling artistik, dan sekaligus paling praktis.

Kenapa:
- **Pakai foto asli, bukan ilustrasi.** Ini pembeda terbesar dari Koisei. Aset TJR itu foto candid tangan menulis dan meja penuh alat tulis. Di Asagiri tinggal tukar foto, struktur tetap jalan
- Logika jualannya identik. Copy hero-nya: "We don't sell caffeine. We sell ninety seconds of quiet." TJR juga bukan jual kelas lettering, tapi jual dua jam tenang. Tinggal ganti subjeknya
- Section dinomori seperti bab: "03 · THE RITUAL". Cocok sama cara TJR menamai acara
- Palet linen, batu, krem hangat, plus hijau tua. Tinggal geser hijau ke sage atau dusty rose
- Ada **Journal** di kolom footer
- GSAP + Lenis + 1 canvas, hero-nya pinned saat scroll

Peta section ke TJR:

| Asagiri | The Journaling Room |
|---|---|
| Hero pinned + "Scroll to steep" | Hero + "Lihat jadwal terdekat" |
| 02 · Craft, foto proses + 3 metrik | Tentang TJR + apa yang terjadi di sesi |
| 03 · The Ritual, "Four movements, ninety seconds" | Alur sesi, "Empat babak, dua jam" |
| "One leaf, three rituals", 3 kartu produk berharga | "Satu ruang, tiga cara ikut", 3 tier acara |
| Band statistik | Jumlah sesi, kota, peserta, teman baru |
| CTA "Start your ninety seconds" + form | CTA daftar + WhatsApp |
| Footer SHOP / LEARN / STUDIO | Acara / Cerita / Kolaborasi |

Yang perlu diubah: nav ORIGIN/CRAFT/RITUAL/SHOP jadi Cerita/Sesi/Jadwal/Kolaborasi, hijau tua digeser ke sage, dan kartu produk diubah jadi kartu acara dengan tanggal dan sisa slot.

### Kalau mau lebih puitis: Koisei Luxury Cultural Journey
`aura.build/templates/koisei` — Meng To, PRO, 1,6k views

Motion paling kuat dari semua kandidat. Yang terverifikasi jalan: partikel kelopak jatuh di 3 canvas, angka menghitung naik saat masuk viewport (338 km ke 1.200 km), headline muncul dari blur, rail penanda bab 01/07 sampai 07/07 di kiri, progress bar di dasar layar, layer gambar beda kecepatan.

Cocoknya: halaman disusun sebagai perjalanan berbab, dan nama acara TJR memang berbab ("A Moment Between Chapters", "Between the Pages"). Tekstur kertas lecek sepanjang halaman. Kelopak jatuh gampang diganti jadi serpihan kertas atau bunga kering.

Risikonya: isinya lukisan tinta Jepang, sedangkan aset TJR foto candid. 17.824px itu 21 layar, kepanjangan untuk halaman yang tujuannya isi seat. Dan temanya sakura Kyoto, jangan diikut mentah.

### Kalau mau paling cepat konversi: Somatic Sanctuary
`aura.build/templates/somatic-sanctuary` — Meng To, PRO

772 elemen, paling padat komponennya. Struktur booking paling lengkap: kartu program, timeline harian, testimoni, waitlist. Nav-nya ada JOURNAL. Motion-nya nyata (GSAP, Lenis, 4 observer, 2 video). Kurangnya, rasanya "retreat mahal", bukan "teman journaling".

### Yang aku coret

| Template | Alasan |
|---|---|
| ØDE Atelier | Komponen paling banyak (12 section) tapi arsitektur dingin, tipografi raksasa, tanpa kehangatan |
| Elysian Editorial | Hangat dan ber-GSAP, tapi tetap tema arsitektur |
| Veloura Skincare | Krem hangat dan ber-GSAP, tapi struktur beauty ecommerce |
| Koisei Immersive Storytelling | Paling mirip kertas, tapi cuma 145 elemen dan 3 gambar. Lebih karya seni daripada landing page |
| ÂTMA Wellness Hotel | Nol animasi scroll |
| Lumora Artisan | Klaim parallax tidak terbukti. Creator baru, 1 desain |
| Semua hasil "creative studio" | Agency dark semua |
| Art Therapy Studio | Kesannya brosur klinik, oranye korporat |

## Urutan lihat sebelum beli

Semua bisa dibuka gratis. Buka berurutan, scroll sampai habis:

1. `asagiri-38matcha.aura.build` — kandidat utama
2. `koisei.aura.build` — kalau mau motion maksimal
3. `somatic-sanctuary.aura.build` — kalau mau struktur booking

Kalau salah satu klik, baru ambil Pro. Trial 3 hari dapat 20 prompt, cukup untuk generate versi TJR dan menilai hasilnya sebelum bayar setahun.

## Soal plan

Akun kamu sekarang **Free**. Yang perlu kamu tahu:

- Free tidak dapat AI prompt sama sekali. Generate butuh trial atau kredit
- Free cuma bisa copy DESIGN.md dan prompt dari template gratis, tidak dari yang PRO
- Free dibatasi 2 halaman per project
- Free itu **personal use only**. TJR ini usaha yang jual tiket workshop, jadi commercial use-nya sudah butuh Pro terlepas dari template mana yang dipilih

Pro $12,50/bulan kalau tahunan, ada trial 3 hari dengan 20 prompt. Buat kasus ini Pro praktis wajib, bukan cuma buat buka Koisei.

---

## Token TJR

Dipakai di semua prompt di bawah. Diturunkan dari feed IG.

### Warna

| Peran | Hex | Dipakai untuk |
|---|---|---|
| `paper` | `#FAF6F0` | Background utama |
| `paper-deep` | `#F1E8DC` | Background section selang-seling |
| `ink` | `#3E2F24` | Teks utama |
| `ink-soft` | `#6B5847` | Teks sekunder |
| `rose` | `#E0B7B4` | Aksen lembut, badge, highlight |
| `rose-deep` | `#C48B87` | Tombol sekunder, hover |
| `sage` | `#93A181` | Aksen kedua, tag kategori |
| `kraft` | `#B08968` | Border, garis pemisah |
| `burgundy` | `#7C2D2D` | CTA utama, harga |

### Tipografi

| Peran | Font | Ukuran | Weight | Letter spacing |
|---|---|---|---|---|
| Display | Cormorant Garamond | 48-72px | 500 | -0.02em |
| Heading | Cormorant Garamond | 28-36px | 500 | -0.01em |
| Aksen tulisan tangan | Petit Formal Script | 20-28px | 400 | 0 |
| Body | Inter | 16-17px | 400 | 0 | 
| Label / eyebrow | Inter | 12px uppercase | 500 | 0.12em |

### Bentuk

- Sudut kartu 16px, foto 12px
- Shadow tipis dan hangat: `0 2px 12px rgba(62,47,36,0.06)`
- Border 1px `#B08968` opacity 30% buat kartu, bukan shadow tebal
- Beberapa foto dimiringkan 1.5 sampai 3 derajat biar kesan ditempel

---

## Prompt A — Generate di Aura

Remix ÂTMA dulu, terus paste ini. Atau paste langsung sebagai prompt baru.

```
Buat landing page satu halaman untuk "The Journaling Room", brand workshop
journaling artistik dari Yogyakarta. Copy dalam Bahasa Indonesia, kecuali nama
tema acara yang tetap Bahasa Inggris. Pakai Tailwind CSS, fully responsive.

POSITIONING
Bukan kelas skill. Ini ruang aman untuk journaling bareng. Nada bicaranya hangat,
tenang, personal. Hindari bahasa marketing dan hindari klaim berlebihan.

PALET
paper #FAF6F0 (background utama)
paper-deep #F1E8DC (section selang-seling)
ink #3E2F24 (teks utama)
ink-soft #6B5847 (teks sekunder)
rose #E0B7B4 (aksen lembut)
rose-deep #C48B87 (hover, tombol sekunder)
sage #93A181 (tag kategori)
kraft #B08968 (garis dan border)
burgundy #7C2D2D (CTA utama dan harga)

TIPOGRAFI
Display dan heading: Cormorant Garamond, 48-72px, weight 500,
letter-spacing -0.02em
Aksen tulisan tangan: Petit Formal Script, 20-28px, dipakai untuk eyebrow dan
kutipan saja
Body: Inter, 16-17px, weight 400, line-height 1.7
Label: Inter 12px uppercase, weight 500, letter-spacing 0.12em

TEKSTUR
Background pakai tekstur kertas halus (noise overlay opacity 4 persen).
Beberapa foto dimiringkan 1.5 sampai 3 derajat seperti ditempel di album.
Border kartu 1px kraft opacity 30 persen, bukan shadow tebal.
Shadow kalau perlu: 0 2px 12px rgba(62,47,36,0.06).
Sudut kartu 16px, foto 12px.

STRUKTUR SECTION, urut dari atas:

1. Nav sticky. Logo wordmark script kiri. Menu tengah: Acara, Cerita, Galeri,
   Kolaborasi. Tombol kanan "Daftar Sekarang" warna burgundy. Di bawah 768px
   jadi hamburger dengan overlay full screen.

2. Hero split kiri-kanan. Kiri: eyebrow script "Your kind, journaling
   companions", H1 Cormorant 64px "Beberapa halaman lebih enak ditulis
   bareng", paragraf pendek, dua tombol (primer "Lihat Jadwal Terdekat"
   burgundy, sekunder "Tanya via WhatsApp" outline kraft). Kanan: foto
   potrait tangan sedang menulis di jurnal, dimiringkan 2 derajat, ada
   sudut foto di dua ujung. Di mobile foto pindah ke bawah teks.

3. Kartu acara terdekat. Satu kartu lebar background paper-deep. Isinya:
   badge tanggal, judul tema, lokasi venue, harga, sisa slot, tombol RSVP.
   Kasih border kraft dan sedikit rotasi 1 derajat.

4. Tentang TJR. Dua kolom. Kiri teks 2 paragraf. Kanan tiga foto polaroid
   bertumpuk dengan rotasi berbeda-beda.

5. Format acara. Grid 3 kolom desktop, 2 kolom tablet, 1 kolom mobile.
   Enam kartu: Journaling Workshop, Brush Lettering Class, Sunday Reads Club,
   Journaling Playdate, Inner Circle, Brand Activation. Tiap kartu: tag sage,
   judul, deskripsi 2 baris, kisaran harga.

6. Yang kamu dapat. Checklist dua kolom dengan ikon garis tipis:
   notebook A6, sticker sheet, booklet panduan, foto cetak, akses deco
   station, minuman, dokumentasi, teman baru.

7. Alur sesi. Timeline vertikal 4 langkah dengan garis kraft di kiri dan
   titik bulat rose di tiap langkah.

8. Galeri dokumentasi. Bento grid masonry, ukuran sel campur, 8 sampai 10 foto.
   Hover: scale 1.03 dan rotasi kembali ke 0 derajat, transisi 300ms ease-out.

9. Suara peserta. Tiga kartu testimoni. Kutipan pakai Cormorant italic,
   nama pakai Inter kecil.

10. Pernah bareng. Baris logo partner grayscale opacity 60 persen, jadi
    penuh saat hover.

11. Kolaborasi brand. Background ink dengan teks paper. Ajakan untuk brand
    yang mau bikin workshop. CTA terpisah "Ngobrol Dulu" ke email dan DM.

12. FAQ accordion 6 pertanyaan.

13. Founder. Dua foto bulat berdampingan, nama Caca dan Dhanty, satu kalimat
    per orang.

14. CTA penutup dengan kutipan besar Cormorant, lalu footer: Instagram,
    WhatsApp, lokasi Yogyakarta.

ANIMASI
Fade in dari opacity 0 dan translateY 16px saat section masuk viewport,
durasi 600ms ease-out, stagger 100ms antar elemen.
Hormati prefers-reduced-motion.

RESPONSIVE
Desktop 1024px ke atas 3 kolom, tablet 768-1023px 2 kolom, mobile di bawah
768px 1 kolom. Semua tombol minimal tinggi 44px di mobile.
```

## Prompt B — Simpan sebagai DESIGN.md di Aura

Setelah hasil generate-nya oke, klik DESIGN.md di kartu template. Kalau mau bikin dari nol, pakai ini:

```
Buat DESIGN.md untuk brand bernama The Journaling Room. Estetikanya scrapbook
analog hangat: kertas krem, dusty pink, sage, kraft brown. Tipografi Cormorant
Garamond untuk display, Petit Formal Script untuk aksen tulisan tangan, Inter
untuk body. Tekstur kertas halus di background. Kartu pakai border tipis
bukan shadow tebal. Foto sesekali dimiringkan 1.5 sampai 3 derajat.
Nada bicara tenang dan personal.

Dokumentasikan: skala warna lengkap dengan hex, skala tipografi dengan ukuran
dan weight dan letter-spacing, skala spacing, radius, shadow, aturan motion,
dan style komponen untuk button, card, badge, accordion, timeline, dan
bento grid.
```

## Prompt C — Lanjut di Claude Code setelah export

Aura bisa export HTML dan Figma. Setelah export, buka di sini dan pakai prompt ini:

```
Ini hasil export Aura untuk landing page The Journaling Room. Tolong:
1. Rapikan jadi struktur file yang bisa dirawat, pisahkan CSS variable ke
   satu file token
2. Ganti semua placeholder copy dengan copy final Bahasa Indonesia (lihat
   brand-brief.md di folder yang sama)
3. Sambungkan semua CTA ke WhatsApp +62 8572 8225 369 dengan pesan
   pre-filled per section
4. Tambahkan meta tag dan Open Graph
5. Cek kontras teks minimal WCAG AA
```

---

## Catatan urutan kerja

Rekomendasiku: generate di Aura pakai Prompt A, export ke Figma buat dirapikan manual (Aura punya export Figma), lalu export HTML dan finishing di Claude Code. Aura kuat buat dapat struktur cepat, tapi hasilnya bakal terlalu bersih. Sentuhan scrapbook-nya paling enak dikerjain manual di Figma.

Yang masih perlu dari pacarmu sebelum eksekusi: file logo, foto resolusi tinggi, dan kepastian ejaan "Journaling" satu L.
