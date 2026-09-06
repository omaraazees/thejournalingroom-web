# TJR — Audit & Rekonsiliasi Design Token

Kartu D-1. Dibaca langsung dari lima berkas yang diminta, plus pengecekan silang
git log dan README.md untuk memastikan tema mana yang benar-benar tayang.

## Temuan paling penting dulu: premis kartu ini keliru

Instruksi kartu bilang "yang menang adalah yang dipakai tema live
(`wordpress/theme/`)". Itu **tidak lagi benar**:

- `README.md` baris 54: *"Tema hidup di `wordpress/theme-v5/`"*. Repo deploy terpisah
  `tjr-v5-theme` di GitHub cuma berisi isi `wordpress/theme-v5/`.
- `git log` untuk `wordpress/theme/` (tanpa v5): **satu commit saja**, `e899840`,
  commit awal repo ini. Tidak pernah disentuh lagi.
- `git log` untuk `wordpress/theme-v5/`: 12+ commit dalam sehari terakhir
  (hero foto, ribbon kolaborator, upscale foto, dll) — persis "12 commit kemarin"
  yang disebut brief.
- `wordpress/theme/` adalah child theme GeneratePress, iterasi lama dari palet
  paper/ink/rose/sage/kraft/burgundy `#7C2D2D`. `desain/prototipe/tokens.css` cocok
  1:1 dengan token lama ini — keduanya bagian dari generasi desain yang sama,
  sudah ditinggalkan.
- `wordpress/theme-v5/` adalah tema blok mandiri (FSE), port dari
  `desain/prototipe/v5-fieldtime.html`, dengan palet kertas/tinta/burgundy
  `#5F1D1D` yang sama sekali berbeda.

**Keputusan yang saya ambil:** rekonsiliasi di bawah memakai `wordpress/theme-v5/`
sebagai sumber kebenaran (tema yang benar-benar tayang di thejournalingroom.id),
bukan `wordpress/theme/`. Ini sudah saya kirim sebagai pesan ke outbox god untuk
konfirmasi Umar, tapi saya lanjutkan bagian yang tidak bergantung jawaban itu
(publikasi Figma) karena buktinya sudah tidak ambigu.

## Sumber per berkas

| Berkas | Peran sebenarnya |
| --- | --- |
| `desain/prototipe/tokens.css` | Prototipe lama (generasi paper/ink), basi. Tidak dipakai lagi. |
| `desain/halaman/pages.css` | CSS bersama generasi lama, komentar sendiri bilang "nol nilai baru" — 100% turunan dari `tokens.css`, tidak ada token tambahan. |
| `wordpress/theme/assets/tjr.css` | CSS pelengkap tema lama. 100% konsisten dengan `wordpress/theme/theme.json`-nya sendiri, nol hex liar. Tapi temanya sendiri sudah ditinggalkan. |
| `wordpress/theme/style.css` | Header tema lama saja, sengaja kosong. |
| `wordpress/theme-v5/style.css` | **CSS tema yang live.** Sebagian besar memakai `var(--wp--preset--*)`, tapi ada kebocoran nilai literal (lihat Penyimpangan). |
| `wordpress/theme-v5/theme.json` (dibaca tambahan, di luar daftar kartu, karena itu sumber token sebenarnya untuk tema live) | Definisi token resmi: warna, tipografi, spacing, radius, shadow. |

## Token final (sumber: `wordpress/theme-v5/theme.json`)

### Warna

| Slug | Nama | Hex | Catatan |
| --- | --- | --- | --- |
| `kertas` | Kertas | `#FBF7F0` | latar utama |
| `kertas-tua` | Kertas tua | `#F3EADC` | latar section alt / bento |
| `kertas-redup` | Kertas redup | `#EAE4D6` | teks di atas strip gelap |
| `meja` | Meja | `#E8DCC8` | cadangan sebelum foto latar termuat |
| `tinta` | Tinta | `#241C14` | teks utama |
| `tinta-lembut` | Tinta lembut | `#6D5D4C` | teks sekunder |
| `tinta-samar` | Tinta samar | `#7E6F5E` | label/eyebrow, kontras 4.5:1 di kertas |
| `garis` | Garis | `#E3D8C6` | border/divider |
| `burgundy` | Burgundy | `#5F1D1D` | aksen utama, heading, tombol |
| `rose` | Rose | `#E7BFB9` | aksen lembut |
| `rose-pucat` | Rose pucat | `#F1DCD7` | latar seksi ajakan |
| `rose-tua` | Rose tua | `#7E5753` | latar strip |
| `rose-teks` | Rose teks | `#81403A` | teks di atas rose, kontras 4.6:1 |
| `zaitun` | Zaitun | `#454E3C` | strip warna alternatif |
| `kraft` | Kraft | `#81572D` | strip warna alternatif |
| `kraft-muda` | Kraft muda | `#EBD9BE` | latar lembut |

### Tipografi — keluarga

| Slug | Nama | Font stack | Pemakaian |
| --- | --- | --- | --- |
| `display` | Playfair Display | `"Playfair Display", Georgia, "Times New Roman", serif` | heading, italic 400/500/600 |
| `teks` | Manrope | `"Manrope", system-ui, -apple-system, "Segoe UI", sans-serif` | body, 400/500/700 |
| `tangan` | Pinyon Script | `"Pinyon Script", "Snell Roundhand", "Apple Chancery", cursive` | aksen tulisan tangan |

### Tipografi — skala ukuran

| Slug | Nama | Ukuran statis | Fluid min–max |
| --- | --- | --- | --- |
| `label` | Label | 10px | — |
| `mikro` | Mikro | 12px | — |
| `kecil` | Kecil | 13px | — |
| `teks` | Teks | 15px | — |
| `sedang` | Sedang | 17px | 15–17px |
| `display-md` | Display sedang | 29px | 21–29px |
| `display-lg` | Display besar | 42px | 26–42px |
| `display-xl` | Display raksasa | 58px | 30–58px |
| `display-xxl` | Display judul utama | 92px | 38–92px |

Heading `h1`–`h6` dan elemen (`button`, `caption`, `link`) memetakan langsung ke
slug-slug ini lewat `styles.elements` di `theme.json` — lihat berkas untuk detail
per elemen (font-style italic di heading, letter-spacing, dst).

### Spacing

| Slug | Nilai |
| --- | --- |
| `jarak-1` | 8px |
| `jarak-2` | 16px |
| `jarak-3` | 24px |
| `jarak-4` | 40px |
| `jarak-5` | 64px |
| `jarak-6` | 96px |
| `jarak-7` | 144px |

### Radius (`settings.custom.radius`)

| Slug | Nilai |
| --- | --- |
| `lembar` | 24px |
| `besar` | 20px |
| `sedang` | 16px |
| `kecil` | 12px |
| `cetakan` | 4px |
| `pil` | 999px |

### Shadow

| Slug | Nama | Nilai |
| --- | --- | --- |
| `lembar` | Lembar melayang | `0 26px 64px rgb(40 30 18 / .34)` |
| `kartu` | Kartu | `0 16px 40px rgb(36 28 20 / .2)` |
| `cetakan` | Cetakan polaroid | `0 14px 32px rgb(36 28 20 / .26)` |
| `mengambang` | Tombol mengambang | `0 12px 30px rgb(36 28 20 / .3)` |

### Lain-lain (`settings.custom`, bukan token WP standar tapi dipakai konsisten)

| Grup | Slug | Nilai |
| --- | --- | --- |
| lengkung (easing) | `halus` | `cubic-bezier(.16,1,.3,1)` |
| lengkung (easing) | `tirai` | `cubic-bezier(.7,0,.2,1)` |
| durasi | `cepat` / `sedang` / `pelan` / `sangatPelan` | `.35s` / `.45s` / `.6s` / `.9s` |
| tebal (letter-spacing) | `spasiLabel` / `spasiTombol` / `spasiNav` | `.22em` / `.18em` / `.2em` |
| layout | `lebarLembar` | `1580px` (= `contentSize`/`wideSize`) |
| layout | `tinggiBento` | `clamp(432px, 45vw, 656px)` |
| layout | `tinggiTumpukan` | `clamp(432px, 44vw, 624px)` |

## Penyimpangan yang ditemukan

1. **Premis kartu salah** (lihat di atas) — `wordpress/theme/` bukan tema live,
   `wordpress/theme-v5/` yang live. Butuh konfirmasi Umar kalau ini disengaja
   atau bukan; sudah dikirim ke outbox.
2. **Dua generasi desain berbeda total**, bukan cuma drift kecil: palet warna,
   nama slug, skala tipografi, skala spacing, bahkan sistem font (Cormorant
   Garamond+Inter+Petit Formal Script di generasi lama vs Playfair
   Display+Manrope+Pinyon Script di v5) semuanya berbeda. Ini bukan tipo, ini
   redesign penuh.
3. **`wordpress/theme-v5/style.css` sendiri bocor dari token miliknya**, walau
   komentar di baris 17–22 berkas itu mengklaim "tidak ada nilai hex yang
   ditulis dua kali":
   - Ukuran font literal di luar skala 9 langkah: `11px`, `14px`, `15px`,
     `17px`, `40px`, plus lima `clamp()` satu-kali yang tidak memetakan ke
     token manapun (baris 353, 512, 695, 762, 1079).
   - Radius literal `2px` (3 tempat) yang tidak ada di daftar 6 radius token.
   - Dua `box-shadow` literal (baris 583, 1300) di luar 4 preset shadow.
   - Hex mentah: `#fff` (hover teks tombol), `#5A4C3D` (warna teks `.ajakan-kiri
     .lead`, dekat tapi tidak sama dengan `tinta-lembut` `#6D5D4C`), `#20160B`,
     dan gradient sampul buku pembuka (`#E9DAC1`,`#F4ECDD`,`#EADCC4`,`#DCC9A8`)
     — ini dekorasi satu-pakai untuk animasi pembuka halaman, bukan token yang
     perlu diulang, tapi tetap tercatat di sini karena bukan token resmi.
   - Ini kosmetik kecil, tidak menghalangi penerbitan Figma, tapi kalau A2/A3
     menyentuh CSS ini lagi sebaiknya nilai-nilai itu ditarik jadi token atau
     dirapikan.
4. **`desain/halaman/pages.css` dan `wordpress/theme/assets/tjr.css`**: tidak
   ada penyimpangan token internal — keduanya sudah konsisten sempurna dengan
   `tokens.css` / `theme.json` generasi lamanya masing-masing. Bersih, tapi
   basi.

## Yang dipakai untuk build Figma

Seluruh tabel "Token final" di atas (warna, family, skala tipografi, spacing,
radius, shadow) — sumbernya `wordpress/theme-v5/theme.json`, karena itu yang
tayang di thejournalingroom.id sekarang.
