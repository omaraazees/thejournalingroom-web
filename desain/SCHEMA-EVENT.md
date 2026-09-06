# TJR — schema.org Event untuk halaman acara

Kartu S-6. JSON-LD `Event` disisipkan ke `@graph` yang sudah ada di
`wordpress/theme-v5/inc/seo.php` (fungsi `tjr_v5_seo_graf()`), bukan blok
`<script type="application/ld+json">` kedua. Node cuma disisipkan waktu
`is_singular( 'acara' )`, jadi halaman lain (beranda, jadwal, dll) tetap
cuma dapat `Organization` dan `LocalBusiness` seperti sebelumnya.

Fungsi baru: `tjr_v5_seo_event_acara( $id, $situs )` merakit node Event-nya,
dan `tjr_v5_seo_alamat_acara( $alamat )` memecah `venue_alamat` jadi
`PostalAddress`. Keduanya di `inc/seo.php`, bagian 6.5.

## Contoh nyata: acara id 12 (`embracing-growth`)

Diambil dari WP REST situs live (`GET /wp-json/wp/v2/acara/12?context=edit`,
plus `GET /wp-json/wp/v2/media/149` untuk `featured_media`), lalu dijalankan
lewat logika `tjr_v5_seo_event_acara()` yang sudah ditulis ulang di Python
untuk divalidasi (PHP tidak tersedia di mesin ini — lihat bagian Validasi).
Ini persis apa yang akan dicetak `wp_head` di halaman acara ini, sebagai
bagian dari elemen `@graph` ketiga, setelah `Organization` dan
`LocalBusiness`:

```json
{
  "@type": "Event",
  "@id": "https://thejournalingroom.id/acara/embracing-growth/#acara",
  "name": "Embracing Growth: A Full-day Journaling Activity",
  "startDate": "2026-09-27T09:00:00+07:00",
  "eventAttendanceMode": "https://schema.org/OfflineEventAttendanceMode",
  "eventStatus": "https://schema.org/EventScheduled",
  "url": "https://thejournalingroom.id/acara/embracing-growth/",
  "description": "Sesi journaling Minggu, 27 September 2026 di Villa Pondok Joglo Yogyakarta, Yogyakarta. Kit lengkap, Rp150.000. Sisa 8 kursi. Tanya slot lewat WhatsApp 0857",
  "endDate": "2026-09-27T17:00:00+07:00",
  "image": [
    "https://thejournalingroom.id/wp-content/uploads/2026/09/embracing-growth-menulis-jurnal-hd.jpg"
  ],
  "location": {
    "@type": "Place",
    "name": "Villa Pondok Joglo Yogyakarta",
    "address": {
      "@type": "PostalAddress",
      "streetAddress": "Gg. Melati, Jl. Ngadinegaran MJ 3 No. 99, Mantrijeron",
      "addressLocality": "Yogyakarta",
      "addressRegion": "DI Yogyakarta",
      "addressCountry": "ID",
      "postalCode": "55143"
    },
    "hasMap": "https://maps.app.goo.gl/88ByabFHUUvMXDUS7"
  },
  "offers": {
    "@type": "Offer",
    "price": "150000",
    "priceCurrency": "IDR",
    "availability": "https://schema.org/InStock",
    "url": "https://thejournalingroom.id/acara/embracing-growth/"
  },
  "organizer": {
    "@id": "https://thejournalingroom.id/#organisasi"
  }
}
```

`organizer` sengaja cuma `{"@id": ...}`, merujuk ke node `Organization` yang
sudah ada di `@graph` yang sama (pola yang sama seperti `logo` dan
`contactPoint` di kode `Organization`/`LocalBusiness` yang sudah ada
sebelumnya) — bukan menulis ulang `name`/`url`-nya.

## Field wajib vs opsional

| Field | Wajib/opsional | Kenapa |
| --- | --- | --- |
| `name`, `startDate`, `location.name` | **Wajib.** | Ini tiga syarat minimum Google untuk Event. Kalau salah satu kosong, `tjr_v5_seo_event_acara()` mengembalikan `null` dan node Event-nya TIDAK disisipkan sama sekali — sisa `@graph` (`Organization`, `LocalBusiness`) tetap tercetak normal. |
| `endDate` | Opsional, dari `durasi_jam`. | `durasi_jam` acara ini terisi (8), jadi `endDate` ikut ada. Kalau kosong/nol di acara lain, `endDate` cuma tidak ditulis — Google tidak mewajibkannya. |
| `image` | Opsional, dari `featured_media`. | Acara ini punya featured image (id 149). Acara tanpa featured image, `get_the_post_thumbnail_url()` balik `false`, dan key `image` tidak ditulis. |
| `location.address` | Opsional secara kode, tapi Google merekomendasikan alamat lengkap untuk lokasi fisik. | Kalau `venue_alamat` kosong, `tjr_v5_seo_alamat_acara()` balik `null` dan `location` cuma berisi `name` (masih valid menurut cek wajib di atas, tapi kurang lengkap di mata Google). |
| `location.hasMap` | Opsional, dari `venue_maps`. | Kosong → tidak ditulis. |
| `offers` | Opsional, dari `harga`. | Sama seperti `tjr_v5_harga_acara()` yang sudah ada: harga 0 dianggap BELUM DIISI, bukan gratis, jadi `offers` dilewati total kalau harga masih 0, bukan mengirim `price: "0"`. |
| `offers.availability` | Dihitung dari `kapasitas` dan `slot_terisi`. | Kosong (`slot_terisi` kosong dianggap 0 oleh `tjr_v5_kursi_acara()`) → sisa kursi penuh → `InStock`. Baru jadi `SoldOut` kalau `slot_terisi` sama dengan `kapasitas`. |
| `description` | Opsional, pakai `tjr_v5_seo_deskripsi_acara()` yang sudah ada (dipakai juga untuk meta description). | Kalau tanggal atau venue kosong, fungsi itu sendiri balik string kosong, dan `description` tidak ditulis ke Event. |
| `catatan_harga`, `disediakan_teks` | **Tidak dipetakan ke Event sama sekali.** | `catatan_harga` cuma modifier tampilan di sebelah harga (lihat `tjr_v5_harga_acara()`), bukan bagian dari `Offer` schema.org. `disediakan_teks` (isi kit) tidak relevan untuk field Event manapun yang diminta kartu ini. |

## `PostalAddress`: cara memecah `venue_alamat`

`venue_alamat` adalah satu baris teks bebas (field ACF), bukan data
terstruktur, jadi tidak ada jaminan formatnya konsisten dari satu acara ke
acara lain. Cara yang dipakai:

1. Pecah berdasarkan koma.
2. Kalau tidak ada koma sama sekali → seluruh teks jadi `streetAddress`,
   `addressLocality` TIDAK ditulis (tidak ditebak).
3. Kalau ada koma → segmen **terakhir** dianggap `"<kota> <kode pos 5 digit>"`.
   Kalau polanya cocok, kota dan kode pos dipisah. Kalau tidak cocok (tidak
   diakhiri 5 digit), segmen terakhir itu dipakai apa adanya sebagai
   `addressLocality`, tanpa `postalCode`.
4. **Semua segmen sebelum yang terakhir** — termasuk kecamatan kalau ditulis
   — digabung jadi `streetAddress`.
5. `addressRegion` selalu `"DI Yogyakarta"` dan `addressCountry` selalu
   `"ID"`, ditulis mati karena situs ini cuma beroperasi di satu provinsi.

Untuk acara id 12, `venue_alamat` = `"Gg. Melati, Jl. Ngadinegaran MJ 3
No. 99, Mantrijeron, Yogyakarta 55143"` pecah jadi:

- `streetAddress`: `"Gg. Melati, Jl. Ngadinegaran MJ 3 No. 99, Mantrijeron"`
- `addressLocality`: `"Yogyakarta"`
- `postalCode`: `"55143"`

**Kenapa "Mantrijeron" (kecamatan) ikut masuk ke `streetAddress`, bukan jadi
`addressLocality`-nya sendiri:** schema.org `PostalAddress` tidak punya
properti untuk kecamatan. `addressLocality` semestinya nama KOTA
(Yogyakarta), bukan kecamatan di dalamnya — menaruh "Mantrijeron" di situ
justru salah secara semantik. Menggabungkannya ke `streetAddress` adalah
pilihan yang jujur: alamatnya tetap lengkap dan bisa dibaca manusia maupun
mesin pencari, tanpa berpura-pura tahu batas kota/kecamatan yang sebenarnya
tidak bisa disimpulkan dari format teks bebas ini.

**Ini heuristik, bukan parser alamat Indonesia yang lengkap.** Alamat yang
tidak mengikuti pola `..., <kota> <kode pos>` — misalnya alamat luar kota,
atau yang tidak menulis kode pos — akan menghasilkan `addressLocality` yang
kurang tepat (biasanya jatuh ke segmen terakhir apa adanya). Selama semua
acara TJR ada di Yogyakarta dan venue_alamat konsisten menulis kode pos di
akhir (seperti data yang ada sekarang), ini aman dipakai. Kalau suatu saat
ada acara di luar format itu, `tjr_v5_seo_alamat_acara()` yang ini yang
perlu ditulis ulang, bukan ditambal jadi tebakan berlapis.

## endDate dan zona waktu

`tanggal_mulai` tersimpan sebagai waktu lokal tanpa zona (`2026-09-27
09:00:00`), pola yang sama seperti `TJR_FIELD_MULAI` di tempat lain di tema
ini (`tjr_v5_jam_acara()`, `tjr_v5_acara_lewat()`). `tjr_v5_seo_event_acara()`
membaca ini dengan `new DateTimeImmutable( $mulai, wp_timezone() )` —
BUKAN `strtotime()` polos, yang membaca string itu sebagai UTC dan bikin
`startDate`/`endDate` meleset tujuh jam (WIB = UTC+7). `endDate` dihitung
`$waktu_mulai + durasi_jam` jam, lalu di-format `c` (ISO 8601 dengan offset
zona, contoh `2026-09-27T17:00:00+07:00`) — bukan `Y-m-d\TH:i:s\Z` yang
akan salah menandai jam lokal sebagai UTC.

## Validasi

PHP tidak terpasang di mesin agen ini, jadi tidak bisa `php -l` langsung.
Yang dilakukan sebagai gantinya:

1. Cek kurung `()`, `{}`, `[]` seimbang di seluruh `inc/seo.php` (364/364,
   80/80, 85/85) — tidak ada blok yang lupa ditutup.
2. Logika `tjr_v5_seo_alamat_acara()` dan `tjr_v5_seo_event_acara()` ditulis
   ulang persis (langkah demi langkah) di Python, dijalankan dengan data
   ACARA ID 12 YANG SEBENARNYA (diambil lewat WP REST, bukan dikarang), lalu
   hasilnya di-`json.loads()` — parse berhasil, dan tiga field wajib Google
   (`name`, `startDate`, `location` dengan `location.name` terisi) ada
   semua. Contoh di atas adalah output dari simulasi itu, bukan ditulis
   tangan.
3. Dibaca ulang bahwa `tjr_v5_seo_graf()` tetap mencetak `Organization` dan
   `LocalBusiness` seperti sebelumnya di semua halaman, dan Event cuma
   nambah sebagai elemen ketiga di `@graph`, cuma di halaman
   `is_singular( 'acara' )`.

Belum divalidasi lewat Google Rich Results Test yang sesungguhnya (butuh
kode ini live di server, dan kartu ini melarang deploy FTP — itu wewenang
god).

## Berkas yang TIDAK disentuh

Sesuai batasan kartu ini: `patterns/jadwal-sesi-terdekat.php`,
`templates/single-acara.html`, dan `inc/isi-beranda.php` (baris harga)
tidak disentuh. Satu-satunya perubahan kode ada di
`wordpress/theme-v5/inc/seo.php`.
