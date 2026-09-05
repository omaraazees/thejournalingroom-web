# Link WhatsApp otomatis

Semua tombol WhatsApp di situs dibuat sendiri oleh kode, lengkap dengan pesan awal yang sudah
berisi nama acara dan tanggalnya. Caca dan Dhanty tidak perlu menempel link apa pun, dan orang yang
klik tidak perlu mengetik ulang acara mana yang dia maksud.

## Nomor yang dipakai

Nomornya `+62 857 2022 5369`. Brand brief sempat menuliskannya `+62 857 2822 5369`, dan itu
salah, digit keempat dari nomornya nol bukan delapan. Untuk link, semua spasi dan tanda plus
dibuang:

```
6285720225369
```

Nomor ini tidak ditulis langsung di banyak tempat. Dia disimpan sekali di satu tempat, lalu dibaca
dari situ. Kalau nomornya ganti, cukup diubah sekali.

## Bentuk linknya

```
https://wa.me/6285720225369?text=Halo%20TJR%2C%20aku%20mau%20ambil%20slot%20untuk%20Tracing%20Shadows%2C%20Mapping%20Stars%20tanggal%2020%20September%202026%20di%20Kupiku%20Coffee.%20Masih%20ada%3F
```

Yang dibaca orang saat WhatsApp terbuka:

> Halo TJR, aku mau ambil slot untuk Tracing Shadows, Mapping Stars tanggal 20 September 2026 di
> Kupiku Coffee. Masih ada?

Kalau sesinya sudah penuh, pesannya berubah sendiri:

> Halo TJR, sesi Tracing Shadows, Mapping Stars tanggal 20 September 2026 sudah penuh ya. Aku mau
> masuk waitlist kalau ada yang batal.

## Potongan PHP

Fungsi ini memakai `tjr_status_acara()` dan `tjr_waktu_mulai()` dari `logika-status.md`.

```php
<?php
/**
 * Nomor WhatsApp TJR dalam format internasional tanpa tanda plus.
 * Disimpan sebagai option supaya bisa diganti tanpa menyentuh kode.
 */
function tjr_nomor_wa() {
	$nomor = get_option( 'tjr_wa_number', '6285720225369' );

	// Buang apa pun selain angka, supaya nomor yang diketik pakai spasi atau plus tetap jalan.
	$nomor = preg_replace( '/\D+/', '', (string) $nomor );

	// Nomor yang diketik mulai dari 0 diubah jadi 62.
	if ( 0 === strpos( $nomor, '0' ) ) {
		$nomor = '62' . substr( $nomor, 1 );
	}

	return $nomor;
}

/**
 * Link WhatsApp untuk satu acara, pesannya menyesuaikan status.
 *
 * @param int|null $post_id ID acara.
 * @return string URL siap pakai di atribut href.
 */
function tjr_link_wa_acara( $post_id = null ) {
	$post_id = $post_id ? (int) $post_id : get_the_ID();

	$judul = get_the_title( $post_id );
	$venue = get_field( 'venue_nama', $post_id );
	$mulai = tjr_waktu_mulai( $post_id );
	$tgl   = $mulai ? wp_date( 'j F Y', $mulai->getTimestamp() ) : '';

	$status = tjr_status_acara( $post_id );

	if ( 'penuh' === $status['slug'] ) {
		$pesan = sprintf(
			'Halo TJR, sesi %1$s tanggal %2$s sudah penuh ya. Aku mau masuk waitlist kalau ada yang batal.',
			$judul,
			$tgl
		);
	} else {
		$pesan = sprintf(
			'Halo TJR, aku mau ambil slot untuk %1$s tanggal %2$s di %3$s. Masih ada?',
			$judul,
			$tgl,
			$venue
		);
	}

	/**
	 * Ubah pesan awal WhatsApp untuk satu acara.
	 */
	$pesan = apply_filters( 'tjr_pesan_wa_acara', $pesan, $post_id, $status );

	return 'https://wa.me/' . tjr_nomor_wa() . '?text=' . rawurlencode( $pesan );
}

/**
 * Link WhatsApp umum, untuk halaman kontak dan tombol di footer.
 *
 * @param string $pesan Pesan awal. Kosongkan untuk memakai sapaan bawaan.
 */
function tjr_link_wa( $pesan = '' ) {
	if ( '' === $pesan ) {
		$pesan = 'Halo TJR, aku mau tanya tanya soal sesi journaling.';
	}

	return 'https://wa.me/' . tjr_nomor_wa() . '?text=' . rawurlencode( $pesan );
}

/**
 * Tombol siap pakai. Acara yang sudah selesai tidak dapat tombol sama sekali.
 */
function tjr_tombol_wa_acara( $post_id = null ) {
	$status = tjr_status_acara( $post_id );

	if ( $status['lewat'] ) {
		return;
	}

	$teks = 'penuh' === $status['slug'] ? 'Masuk waitlist lewat WhatsApp' : 'Ambil slot lewat WhatsApp';

	printf(
		'<a class="btn btn-primary" href="%1$s" target="_blank" rel="noopener">%2$s</a>',
		esc_url( tjr_link_wa_acara( $post_id ) ),
		esc_html( $teks )
	);
}
```

## Menyimpan nomornya

ACF versi gratis tidak punya halaman opsi, jadi nomor disimpan sebagai option WordPress biasa.
Ada dua cara, pilih salah satu.

**Cara pertama, lewat Customizer.** Caca dan Dhanty bisa mengubahnya sendiri dari Tampilan,
Sesuaikan, tanpa menyentuh kode.

```php
<?php
add_action( 'customize_register', 'tjr_customizer_wa' );
function tjr_customizer_wa( $wp_customize ) {
	$wp_customize->add_section( 'tjr_kontak', array(
		'title'    => 'Kontak TJR',
		'priority' => 30,
	) );

	$wp_customize->add_setting( 'tjr_wa_number', array(
		'default'           => '6285720225369',
		'type'              => 'option',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'refresh',
	) );

	$wp_customize->add_control( 'tjr_wa_number', array(
		'label'       => 'Nomor WhatsApp',
		'description' => 'Boleh diketik pakai spasi atau tanda plus, nanti dirapikan sendiri.',
		'section'     => 'tjr_kontak',
		'type'        => 'text',
	) );
}
```

**Cara kedua, dikunci di kode.** Kalau nomornya dianggap tidak akan berubah, hapus `get_option()`
dan tulis nomornya langsung di `tjr_nomor_wa()`. Lebih aman dari salah ketik, tapi ganti nomor
jadi butuh orang teknis.

Rekomendasiku cara pertama. Nomor kontak itu hal yang cepat atau lambat berubah, dan menunggu
orang teknis untuk hal sekecil itu tidak sepadan.

## Yang perlu diperhatikan

- Link `wa.me` hanya jalan kalau nomornya sudah terdaftar di WhatsApp. Nomor yang belum aktif akan
  memunculkan halaman error dari WhatsApp, bukan dari situs.
- Pesan awal itu saran, bukan kunci. Orang bisa menghapusnya sebelum mengirim. Jadi jangan pernah
  memakai isi pesan sebagai satu satunya cara tahu orang daftar sesi yang mana.
- `target="_blank"` dipasang bersama `rel="noopener"`. Tanpa itu, tab WhatsApp yang terbuka bisa
  mengakses halaman asalnya.
- Tombol memakai kelas `btn btn-primary` yang sudah ada di `tokens.css`, tinggi 44 piksel lebih,
  jadi target sentuhnya sudah memenuhi syarat.
- Emoji sengaja tidak dipakai di pesan awal. Sebagian ponsel lama menampilkannya jadi kotak, dan
  panjang URL jadi membengkak.
