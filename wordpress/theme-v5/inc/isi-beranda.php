<?php
/**
 * Isi beranda yang bisa diurus sendiri dari dasbor.
 *
 * Tata letak beranda tetap hidup di pattern, di dalam git. Yang pindah ke
 * database cuma isinya: foto dan kalimat. Jadi pemilik brand bisa mengganti
 * foto tanpa menyentuh kode, dan pembaruan desain dari repo tetap sampai.
 *
 * Semua field punya bawaan berupa aset yang sekarang dipakai. Selama field
 * dibiarkan kosong, halaman tampil persis seperti sebelumnya.
 *
 * ACF di situs ini versi gratis, jadi tidak ada Repeater, Gallery, atau
 * Options Page. Field-nya ditulis satu per satu dan ditempel ke halaman depan.
 *
 * @package tjr-v5
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * ID halaman yang dipakai sebagai beranda.
 *
 * @return int
 */
function tjr_v5_id_beranda() {
	$id = (int) get_option( 'page_on_front' );

	if ( ! $id ) {
		$hal = get_page_by_path( 'beranda' );
		$id  = $hal ? (int) $hal->ID : 0;
	}

	return $id;
}

/**
 * Ambil satu field isi beranda.
 *
 * @param string $nama   Nama field.
 * @param mixed  $bawaan Nilai kalau field kosong atau ACF belum aktif.
 * @return mixed
 */
function tjr_v5_isi( $nama, $bawaan = '' ) {
	if ( ! function_exists( 'get_field' ) ) {
		return $bawaan;
	}

	$id = tjr_v5_id_beranda();

	if ( ! $id ) {
		return $bawaan;
	}

	$nilai = get_field( $nama, $id );

	if ( null === $nilai || '' === $nilai || array() === $nilai || false === $nilai ) {
		return $bawaan;
	}

	return $nilai;
}

/**
 * URL gambar dari field, dengan berkas tema sebagai cadangan.
 *
 * @param string $nama    Nama field bertipe Image.
 * @param string $berkas  Nama berkas di assets/img/ yang dipakai kalau kosong.
 * @return string
 */
function tjr_v5_foto( $nama, $berkas ) {
	$nilai = tjr_v5_isi( $nama, null );

	if ( is_array( $nilai ) && ! empty( $nilai['url'] ) ) {
		return $nilai['url'];
	}

	if ( is_string( $nilai ) && '' !== $nilai ) {
		return $nilai;
	}

	if ( is_numeric( $nilai ) ) {
		$url = wp_get_attachment_image_url( (int) $nilai, 'full' );
		if ( $url ) {
			return $url;
		}
	}

	return get_theme_file_uri( '/assets/img/' . $berkas );
}

/**
 * Teks alternatif gambar dari field, dengan cadangan yang ditulis di pattern.
 *
 * @param string $nama   Nama field bertipe Image.
 * @param string $bawaan Alt cadangan.
 * @return string
 */
function tjr_v5_foto_alt( $nama, $bawaan = '' ) {
	$nilai = tjr_v5_isi( $nama, null );

	if ( is_array( $nilai ) && ! empty( $nilai['alt'] ) ) {
		return $nilai['alt'];
	}

	return $bawaan;
}


/* =====================================================================
 * Pendaftaran field
 * ===================================================================== */

/**
 * Bikin satu field gambar.
 *
 * @param string $nama      Nama field.
 * @param string $label     Label yang dibaca orang.
 * @param string $petunjuk  Kalimat bantuan di bawah label.
 * @return array
 */
function tjr_v5_field_foto( $nama, $label, $petunjuk = '' ) {
	return array(
		'key'           => 'field_tjr_' . $nama,
		'label'         => $label,
		'name'          => $nama,
		'type'          => 'image',
		'instructions'  => $petunjuk,
		'return_format' => 'array',
		'preview_size'  => 'medium',
		'library'       => 'all',
		'mime_types'    => 'jpg,jpeg,png,webp',
	);
}

/**
 * Bikin satu field teks.
 *
 * @param string $nama     Nama field.
 * @param string $label    Label.
 * @param string $petunjuk Kalimat bantuan.
 * @param bool   $panjang  Pakai textarea, bukan satu baris.
 * @return array
 */
function tjr_v5_field_teks( $nama, $label, $petunjuk = '', $panjang = false ) {
	return array(
		'key'          => 'field_tjr_' . $nama,
		'label'        => $label,
		'name'         => $nama,
		'type'         => $panjang ? 'textarea' : 'text',
		'instructions' => $petunjuk,
		'rows'         => 3,
		'new_lines'    => '',
	);
}

/**
 * Daftarkan grup field Isi Beranda.
 *
 * Ditulis di kode, bukan dibuat lewat layar ACF, supaya ikut versi di git dan
 * tidak bisa terhapus tidak sengaja dari dasbor.
 */
function tjr_v5_daftar_isi_beranda() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	$field = array();

	/* ---- Hero ---- */
	$field[] = array(
		'key'   => 'field_tjr_tab_hero',
		'label' => 'Hero',
		'type'  => 'tab',
	);
	$field[] = tjr_v5_field_teks(
		'hero_label',
		'Label kecil di atas judul',
		'Tulisan kapital kecil di atas judul besar.'
	);
	$field[] = tjr_v5_field_teks(
		'hero_judul',
		'Judul besar',
		'Judul utama halaman. Ini juga yang dibaca Google sebagai judul isi.'
	);
	$field[] = tjr_v5_field_foto(
		'hero_foto',
		'Foto besar',
		'Foto lebar di bawah judul. Pakai foto mendatar, minimal 1600 piksel.'
	);
	$field[] = tjr_v5_field_teks(
		'hero_foto_judul',
		'Kalimat di atas foto',
		'Kalimat putih besar di sisi kiri foto.'
	);
	$field[] = tjr_v5_field_teks(
		'hero_foto_isi',
		'Kalimat pendukung di atas foto',
		'Dua kalimat pendek di bawah kalimat besar tadi.',
		true
	);
	$field[] = tjr_v5_field_foto(
		'hero_cetakan',
		'Cetakan kecil di sudut kiri',
		'Foto persegi kecil yang diselipkan di sudut kiri bawah judul.'
	);
	$field[] = tjr_v5_field_teks(
		'hero_cetakan_teks',
		'Tulisan di cetakan kecil',
		'Contoh: Nov 2025.'
	);

	/* ---- Pengantar ---- */
	$field[] = array(
		'key'   => 'field_tjr_tab_pengantar',
		'label' => 'Pengantar',
		'type'  => 'tab',
	);
	$field[] = tjr_v5_field_teks(
		'pengantar_1',
		'Paragraf pertama',
		'Kalimat pembuka tentang ruangnya. Jumlah kolaborasi diisi sendiri oleh sistem, jangan ditulis angkanya.',
		true
	);
	$field[] = tjr_v5_field_teks(
		'pengantar_2',
		'Paragraf kedua',
		'',
		true
	);
	$field[] = tjr_v5_field_foto(
		'pengantar_foto',
		'Foto lebar',
		'Foto mendatar di sebelah kiri kotak kutipan.'
	);
	$field[] = tjr_v5_field_teks(
		'kutipan',
		'Kalimat kutipan',
		'Kalimat pendek di kotak merah muda. Tanpa tanda kutip, tanda kutipnya sudah digambar.'
	);
	$field[] = tjr_v5_field_foto(
		'pengantar_cetakan',
		'Cetakan kecil di sudut kanan',
		''
	);
	$field[] = tjr_v5_field_teks(
		'pengantar_cetakan_teks',
		'Tulisan di cetakan kecil',
		'Contoh: Pendopo Radian.'
	);

	/* ---- Galeri bento ---- */
	$field[] = array(
		'key'   => 'field_tjr_tab_bento',
		'label' => 'Galeri',
		'type'  => 'tab',
	);

	$bento = array(
		1 => 'Petak besar kiri atas, mendatar',
		2 => 'Petak kanan atas, agak persegi',
		3 => 'Petak kanan bawah, agak persegi',
		4 => 'Petak lebar bawah',
	);

	foreach ( $bento as $n => $keterangan ) {
		$field[] = tjr_v5_field_foto( 'bento_' . $n, 'Foto ' . $n, $keterangan );
		$field[] = tjr_v5_field_teks( 'bento_' . $n . '_teks', 'Tulisan di strip foto ' . $n, '' );
	}

	/* ---- Tumpukan cetakan ---- */
	$field[] = array(
		'key'   => 'field_tjr_tab_cetakan',
		'label' => 'Cetakan',
		'type'  => 'tab',
	);
	$field[] = array(
		'key'     => 'field_tjr_cetakan_catatan',
		'label'   => '',
		'type'    => 'message',
		'message' => 'Sembilan cetakan yang menyebar waktu halaman digulir. Pakai foto persegi. Kalau salah satu dikosongkan, foto bawaan yang dipakai.',
	);

	for ( $n = 1; $n <= 9; $n++ ) {
		$field[] = tjr_v5_field_foto( 'cetakan_' . $n, 'Cetakan ' . $n, '' );
		$field[] = tjr_v5_field_teks( 'cetakan_' . $n . '_nama', 'Nama di cetakan ' . $n, '' );
	}

	acf_add_local_field_group(
		array(
			'key'                   => 'group_tjr_isi_beranda',
			'title'                 => 'Isi Beranda',
			'fields'                => $field,
			'location'              => array(
				array(
					array(
						'param'    => 'page_type',
						'operator' => '==',
						'value'    => 'front_page',
					),
				),
			),
			'menu_order'            => 0,
			'position'              => 'normal',
			'style'                 => 'default',
			'label_placement'       => 'top',
			'instruction_placement' => 'label',
			'active'                => true,
			'description'           => 'Foto dan kalimat di beranda. Tata letaknya diatur di tema, jadi yang perlu diisi cuma isinya.',
		)
	);
}
add_action( 'acf/init', 'tjr_v5_daftar_isi_beranda' );
