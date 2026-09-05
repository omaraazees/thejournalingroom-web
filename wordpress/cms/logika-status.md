# Logika status acara

Status tidak pernah diketik manual. Dia dihitung ulang tiap halaman dibuka, dari dua hal saja:
tanggal mulai dan slot terisi. Artinya Caca dan Dhanty cukup mengubah angka slot terisi, sisanya
jalan sendiri.

## Aturannya

Dibaca dari atas ke bawah. Yang pertama cocok, itu yang dipakai.

| Urutan | Kondisi | Status | Yang muncul di situs |
|---|---|---|---|
| 1 | Tanggal mulai belum diisi | `belum-siap` | Kartu tidak tampil di beranda maupun jadwal |
| 2 | Waktu selesai sudah lewat | `selesai` | Pindah ke arsip, tombol WhatsApp hilang, galeri muncul |
| 3 | Slot terisi sama dengan atau lebih dari kapasitas | `penuh` | Badge Penuh, tombol berubah jadi Masuk waitlist |
| 4 | Sisa slot di bawah 25 persen kapasitas | `hampir-penuh` | Badge Hampir penuh warna burgundy, sisa slot ditebalkan |
| 5 | Selain itu | `buka` | Badge Buka, tombol Ambil slot lewat WhatsApp |

Waktu selesai dihitung dari tanggal mulai ditambah durasi. Jadi sesi jam 15.00 durasi 3 jam baru
berubah jadi Selesai lewat jam 18.00, bukan tengah malam. Ini penting supaya acara yang sedang
berlangsung tidak tiba tiba hilang dari beranda.

Ambang 25 persen sengaja dihitung dari kapasitas, bukan angka tetap. Sesi 15 orang jadi Hampir
penuh saat sisa 3, sesi 6 orang jadi Hampir penuh saat sisa 1. Kalau dipakai angka tetap, sesi
Playdate yang cuma 6 orang akan berstatus Hampir penuh sejak orang pertama daftar.

## Potongan PHP

Taruh di `functions.php` child theme, atau di file terpisah yang di-`require` dari situ.

```php
<?php
/**
 * Status acara, dihitung dari tanggal mulai dan slot terisi.
 *
 * @param int|null $post_id ID acara. Kosongkan untuk memakai post yang sedang dibuka.
 * @return array{slug:string,label:string,sisa:int,kapasitas:int,terisi:int,persen:int,lewat:bool}
 */
function tjr_status_acara( $post_id = null ) {
	$post_id = $post_id ? (int) $post_id : get_the_ID();

	$kapasitas = max( 0, (int) get_field( 'kapasitas', $post_id ) );
	$terisi    = max( 0, (int) get_field( 'slot_terisi', $post_id ) );
	$terisi    = $kapasitas > 0 ? min( $terisi, $kapasitas ) : $terisi;
	$sisa      = max( 0, $kapasitas - $terisi );
	$persen    = $kapasitas > 0 ? (int) round( $terisi / $kapasitas * 100 ) : 0;

	$dasar = array(
		'sisa'      => $sisa,
		'kapasitas' => $kapasitas,
		'terisi'    => $terisi,
		'persen'    => $persen,
		'lewat'     => false,
	);

	$selesai = tjr_waktu_selesai( $post_id );

	if ( ! $selesai ) {
		return array_merge( $dasar, array( 'slug' => 'belum-siap', 'label' => 'Belum siap tayang' ) );
	}

	if ( $selesai < current_datetime() ) {
		return array_merge( $dasar, array( 'slug' => 'selesai', 'label' => 'Selesai', 'lewat' => true ) );
	}

	if ( $kapasitas > 0 && $sisa === 0 ) {
		return array_merge( $dasar, array( 'slug' => 'penuh', 'label' => 'Penuh' ) );
	}

	if ( $kapasitas > 0 && ( $sisa / $kapasitas ) < 0.25 ) {
		return array_merge( $dasar, array( 'slug' => 'hampir-penuh', 'label' => 'Hampir penuh' ) );
	}

	return array_merge( $dasar, array( 'slug' => 'buka', 'label' => 'Buka' ) );
}

/**
 * Waktu mulai acara sebagai objek, memakai zona waktu situs.
 *
 * @return DateTimeImmutable|null
 */
function tjr_waktu_mulai( $post_id = null ) {
	$post_id = $post_id ? (int) $post_id : get_the_ID();
	$raw     = get_field( 'tanggal_mulai', $post_id, false );

	if ( ! $raw ) {
		return null;
	}

	$mulai = DateTimeImmutable::createFromFormat( 'Y-m-d H:i:s', $raw, wp_timezone() );

	return $mulai ? $mulai : null;
}

/**
 * Waktu selesai, yaitu waktu mulai ditambah durasi.
 *
 * @return DateTimeImmutable|null
 */
function tjr_waktu_selesai( $post_id = null ) {
	$mulai = tjr_waktu_mulai( $post_id );

	if ( ! $mulai ) {
		return null;
	}

	$post_id = $post_id ? (int) $post_id : get_the_ID();
	$jam     = (float) get_field( 'durasi_jam', $post_id );
	$menit   = (int) round( ( $jam > 0 ? $jam : 3 ) * 60 );

	return $mulai->modify( '+' . $menit . ' minutes' );
}
```

### Menampilkan badge dan bar slot

```php
<?php
/**
 * Badge status siap pakai. Kelas CSS mengikuti token yang sudah ada di tokens.css.
 */
function tjr_badge_status( $post_id = null ) {
	$s = tjr_status_acara( $post_id );

	printf(
		'<span class="tag tag--%1$s">%2$s</span>',
		esc_attr( $s['slug'] ),
		esc_html( $s['label'] )
	);
}

/**
 * Bar slot. Dibungkus role img plus aria-label supaya kebaca screen reader,
 * karena bar warna saja tidak menyampaikan apa apa.
 */
function tjr_bar_slot( $post_id = null ) {
	$s = tjr_status_acara( $post_id );

	if ( $s['kapasitas'] < 1 || $s['lewat'] ) {
		return;
	}

	$label = sprintf(
		/* translators: 1: slot terisi, 2: kapasitas */
		'%1$d dari %2$d slot terisi',
		$s['terisi'],
		$s['kapasitas']
	);

	printf(
		'<div class="slotbar" role="img" aria-label="%1$s"><i style="width:%2$d%%"></i></div><p class="note">%1$s</p>',
		esc_attr( $label ),
		(int) $s['persen']
	);
}
```

### Memisahkan acara mendatang dari arsip

ACF menyimpan tanggal sebagai teks `Y-m-d H:i:s`. Format itu urut secara abjad sekaligus urut
secara waktu, jadi bisa langsung dipakai untuk mengurutkan dan membandingkan.

```php
<?php
/**
 * Argumen query untuk daftar acara.
 *
 * @param string $mana 'mendatang' atau 'arsip'.
 */
function tjr_query_acara( $mana = 'mendatang', $jumlah = 6 ) {
	$sekarang = current_datetime()->format( 'Y-m-d H:i:s' );

	return array(
		'post_type'      => 'acara',
		'posts_per_page' => $jumlah,
		'meta_key'       => 'tanggal_mulai',
		'orderby'        => 'meta_value',
		'order'          => 'mendatang' === $mana ? 'ASC' : 'DESC',
		'meta_query'     => array(
			array(
				'key'     => 'tanggal_mulai',
				'value'   => $sekarang,
				'compare' => 'mendatang' === $mana ? '>=' : '<',
				'type'    => 'DATETIME',
			),
		),
	);
}
```

Perbandingan di query ini memakai tanggal mulai, bukan tanggal selesai, supaya tetap ringan untuk
database. Selisihnya paling lama beberapa jam, dan halaman detail tetap memakai
`tjr_status_acara()` yang lebih teliti.

## Yang perlu diketahui sebelum dipakai

- Zona waktu situs harus diset ke Jakarta di Pengaturan, Umum. Kalau masih UTC, acara akan berubah
  jadi Selesai tujuh jam lebih awal.
- Fungsi ini memanggil `get_field()` milik ACF. Kalau plugin ACF dimatikan, seluruh status akan
  jatuh ke `belum-siap`.
- Kalau kapasitas diisi 0, status tidak pernah jadi Penuh maupun Hampir penuh. Ini disengaja untuk
  sesi yang tidak dibatasi seat, misalnya Playdate dengan minimum spend.
- Angka slot terisi tidak terhubung ke pembayaran. Selama booking masih manual lewat WhatsApp,
  angka ini diketik tangan. Kalau nanti ada sistem booking, fungsi ini tinggal membaca dari sana.
