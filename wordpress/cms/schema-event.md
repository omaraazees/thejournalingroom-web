# Schema Event untuk halaman acara

Tujuannya satu: sesi TJR bisa muncul di kotak daftar acara Google, lengkap dengan tanggal, venue,
dan harga, tanpa orang harus mengklik dulu. Semua isinya diambil dari field yang sudah ada, jadi
tidak ada yang perlu diketik dua kali.

## Bentuk akhirnya

Ini contoh yang keluar untuk satu sesi. Perhatikan tanda zona waktu `+07:00` di tanggal, itu wajib.

```json
{
  "@context": "https://schema.org",
  "@type": "Event",
  "@id": "https://thejournalingroom.id/acara/tracing-shadows-mapping-stars/#event",
  "name": "Tracing Shadows, Mapping Stars",
  "startDate": "2026-09-20T15:00:00+07:00",
  "endDate": "2026-09-20T18:00:00+07:00",
  "eventStatus": "https://schema.org/EventScheduled",
  "eventAttendanceMode": "https://schema.org/OfflineEventAttendanceMode",
  "description": "Sesi journaling tiga jam dengan kit lengkap di Kupiku Coffee, Yogyakarta.",
  "image": [
    "https://thejournalingroom.id/wp-content/uploads/2026/09/tracing-shadows.jpg"
  ],
  "url": "https://thejournalingroom.id/acara/tracing-shadows-mapping-stars/",
  "location": {
    "@type": "Place",
    "name": "Kupiku Coffee",
    "hasMap": "https://maps.app.goo.gl/contoh",
    "address": {
      "@type": "PostalAddress",
      "streetAddress": "Jl. Kaliurang KM 5",
      "addressLocality": "Yogyakarta",
      "addressRegion": "DI Yogyakarta",
      "addressCountry": "ID"
    }
  },
  "organizer": {
    "@type": "Organization",
    "name": "The Journaling Room",
    "url": "https://thejournalingroom.id/",
    "sameAs": ["https://www.instagram.com/thejournalingroom/"]
  },
  "performer": {
    "@type": "Organization",
    "name": "The Journaling Room"
  },
  "maximumAttendeeCapacity": 15,
  "remainingAttendeeCapacity": 4,
  "offers": {
    "@type": "Offer",
    "price": "75000",
    "priceCurrency": "IDR",
    "availability": "https://schema.org/LimitedAvailability",
    "url": "https://thejournalingroom.id/acara/tracing-shadows-mapping-stars/",
    "validFrom": "2026-08-20T00:00:00+07:00",
    "validThrough": "2026-09-20T15:00:00+07:00"
  }
}
```

## Peta field ke schema

| Isian di WordPress | Masuk ke | Catatan |
|---|---|---|
| Judul acara | `name` | Judul post bawaan |
| Tanggal dan jam mulai | `startDate` | Ditulis ulang jadi format ISO plus zona waktu |
| Tanggal mulai + durasi | `endDate` | Dihitung, tidak diisi manual |
| Nama venue | `location.name` | |
| Alamat singkat | `location.address.streetAddress` | Kalau kosong, alamat tetap terkirim dengan kota saja |
| Kota (taksonomi) | `location.address.addressLocality` | |
| Link Google Maps | `location.hasMap` | Boleh kosong |
| Foto utama | `image` | Google minta minimal satu, lebar minimal 1200 piksel |
| Harga | `offers.price` | Angka polos, tanpa titik, tanpa Rp |
| Kapasitas | `maximumAttendeeCapacity` | |
| Kapasitas dikurangi slot terisi | `remainingAttendeeCapacity` | |
| Status otomatis | `offers.availability` | Lihat tabel di bawah |

### Status ke availability

| Status | `availability` |
|---|---|
| Buka | `https://schema.org/InStock` |
| Hampir penuh | `https://schema.org/LimitedAvailability` |
| Penuh | `https://schema.org/SoldOut` |
| Selesai | blok `offers` tidak dikeluarkan sama sekali |

Acara yang sudah lewat tetap mengeluarkan blok `Event`, cuma tanpa penawaran. Google memang tidak
menampilkan acara lampau di hasil pencarian, tapi markupnya berguna untuk halaman arsip dan tidak
merugikan.

## Potongan PHP

Taruh di `functions.php` child theme. Fungsi ini memakai `tjr_status_acara()`, `tjr_waktu_mulai()`,
dan `tjr_waktu_selesai()` dari `logika-status.md`, jadi pastikan file itu sudah dimuat lebih dulu.

```php
<?php
/**
 * Cetak JSON-LD Event di halaman detail acara.
 */
add_action( 'wp_head', 'tjr_schema_event', 20 );
function tjr_schema_event() {
	if ( ! is_singular( 'acara' ) ) {
		return;
	}

	$id     = get_the_ID();
	$mulai  = tjr_waktu_mulai( $id );
	$status = tjr_status_acara( $id );

	// Tanpa tanggal, schema Event tidak sah. Lebih baik tidak mencetak apa apa.
	if ( ! $mulai ) {
		return;
	}

	$selesai = tjr_waktu_selesai( $id );
	$permalink = get_permalink( $id );

	$kota = '';
	$term = get_the_terms( $id, 'kota' );
	if ( $term && ! is_wp_error( $term ) ) {
		$kota = $term[0]->name;
	}

	$alamat = array(
		'@type'          => 'PostalAddress',
		'addressCountry' => 'ID',
	);
	$jalan = get_field( 'venue_alamat', $id );
	if ( $jalan ) {
		$alamat['streetAddress'] = $jalan;
	}
	if ( $kota ) {
		$alamat['addressLocality'] = $kota;
	}

	$tempat = array(
		'@type'   => 'Place',
		'name'    => get_field( 'venue_nama', $id ),
		'address' => $alamat,
	);
	$maps = get_field( 'venue_maps', $id );
	if ( $maps ) {
		$tempat['hasMap'] = $maps;
	}

	$data = array(
		'@context'            => 'https://schema.org',
		'@type'               => 'Event',
		'@id'                 => $permalink . '#event',
		'name'                => get_the_title( $id ),
		'startDate'           => $mulai->format( 'c' ),
		'eventStatus'         => 'https://schema.org/EventScheduled',
		'eventAttendanceMode' => 'https://schema.org/OfflineEventAttendanceMode',
		'url'                 => $permalink,
		'location'            => $tempat,
		'organizer'           => array(
			'@type'  => 'Organization',
			'name'   => 'The Journaling Room',
			'url'    => home_url( '/' ),
			'sameAs' => array( 'https://www.instagram.com/thejournalingroom/' ),
		),
		'performer'           => array(
			'@type' => 'Organization',
			'name'  => 'The Journaling Room',
		),
	);

	if ( $selesai ) {
		$data['endDate'] = $selesai->format( 'c' );
	}

	$ringkas = get_the_excerpt( $id );
	if ( $ringkas ) {
		$data['description'] = wp_strip_all_tags( $ringkas );
	}

	$gambar = get_the_post_thumbnail_url( $id, 'full' );
	if ( $gambar ) {
		$data['image'] = array( $gambar );
	}

	if ( $status['kapasitas'] > 0 ) {
		$data['maximumAttendeeCapacity']   = $status['kapasitas'];
		$data['remainingAttendeeCapacity'] = $status['sisa'];
	}

	$harga = (int) get_field( 'harga', $id );

	if ( ! $status['lewat'] && $harga > 0 ) {
		$peta = array(
			'buka'         => 'https://schema.org/InStock',
			'hampir-penuh' => 'https://schema.org/LimitedAvailability',
			'penuh'        => 'https://schema.org/SoldOut',
		);

		$data['offers'] = array(
			'@type'         => 'Offer',
			'price'         => (string) $harga,
			'priceCurrency' => 'IDR',
			'availability'  => isset( $peta[ $status['slug'] ] ) ? $peta[ $status['slug'] ] : 'https://schema.org/InStock',
			'url'           => $permalink,
			'validFrom'     => get_post_datetime( $id )->format( 'c' ),
			'validThrough'  => $mulai->format( 'c' ),
		);
	}

	printf(
		'<script type="application/ld+json">%s</script>' . "\n",
		wp_json_encode( $data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE )
	);
}
```

## Hal yang gampang bikin schema ditolak

- **Zona waktu wajib ada di tanggal.** `format('c')` sudah menghasilkan `2026-09-20T15:00:00+07:00`
  selama zona waktu situs diset Jakarta. Kalau situs masih UTC, tanggalnya akan salah tujuh jam dan
  Google menampilkan jam yang keliru.
- **Harga ditulis polos.** `75000`, bukan `Rp75.000` dan bukan `75.000`. Pemisah ribuan membuat
  Google membaca harganya jadi 75.
- **Satu Event per halaman.** Rank Math juga bisa mengeluarkan schema Event sendiri. Kalau dua
  duanya menyala, akan ada dua blok Event dan Google memilih sendiri, biasanya yang lebih miskin.
  Matikan salah satu. Cara paling gampang, buka Rank Math, Titles and Meta, pilih tipe konten
  Acara, lalu set Schema Type ke None.
- **Foto utama wajib diisi.** Tanpa `image`, Google tetap membaca acaranya tapi tidak menampilkan
  kartunya. Ukuran aman lebar 1200 piksel dengan rasio 16 banding 9.
- **Jangan mengarang `eventStatus`.** Kalau sesi dibatalkan atau dipindah, ubah ke
  `EventCancelled` atau `EventPostponed` dan isi `previousStartDate`. Ini belum ditangani di kode
  di atas karena field pembatalan belum ada. Kalau nanti dibutuhkan, cukup satu field pilihan.

## Cara mengecek

1. Buka satu halaman acara di situs.
2. Tempel URL-nya ke Rich Results Test milik Google.
3. Yang harus muncul: satu Event, tanpa error, dengan tanggal, tempat, dan harga terbaca.
4. Setelah situs live dan terhubung Search Console, cek menu Enhancements, Events, untuk melihat
   apakah Google benar benar mengindeksnya.

## Yang masih nunggu orang

- Domain final. Semua contoh di atas memakai `thejournalingroom.id` sebagai penanda, bukan domain
  yang sudah dibeli.
- Alamat lengkap tiap venue. Sekarang cuma nama venue yang ada di brand brief.
- Foto utama resolusi tinggi untuk tiap sesi.
