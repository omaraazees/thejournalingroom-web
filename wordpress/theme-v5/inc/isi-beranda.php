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


/* =====================================================================
 * Baris fakta acara yang hidup di dalam Query Loop
 *
 * Pattern dirender sekali waktu berkasnya dibaca, jadi get_field() di dalam
 * pattern tidak bisa tahu acara mana yang sedang diulang. Nilainya ditukar di
 * sini lewat render_block, yang menerima postId dari konteks blok.
 * ===================================================================== */

/**
 * Tanggal dalam bahasa Indonesia.
 *
 * WordPress di situs ini berjalan dengan berkas terjemahan bawaan Inggris,
 * jadi wp_date menghasilkan Sunday dan August. Nama hari dan bulan ditukar di
 * sini, supaya tidak bergantung pada paket bahasa yang mungkin tidak terpasang.
 *
 * @param string $format  Format tanggal ala PHP.
 * @param int    $stempel Timestamp.
 * @return string
 */
function tjr_v5_tanggal_id( $format, $stempel ) {
	$peta = array(
		'Sunday'    => 'Minggu',
		'Monday'    => 'Senin',
		'Tuesday'   => 'Selasa',
		'Wednesday' => 'Rabu',
		'Thursday'  => 'Kamis',
		'Friday'    => 'Jumat',
		'Saturday'  => 'Sabtu',
		'Sun'       => 'Min',
		'Mon'       => 'Sen',
		'Tue'       => 'Sel',
		'Wed'       => 'Rab',
		'Thu'       => 'Kam',
		'Fri'       => 'Jum',
		'Sat'       => 'Sab',
		'January'   => 'Januari',
		'February'  => 'Februari',
		'March'     => 'Maret',
		'April'     => 'April',
		'May'       => 'Mei',
		'June'      => 'Juni',
		'July'      => 'Juli',
		'August'    => 'Agustus',
		'September' => 'September',
		'October'   => 'Oktober',
		'November'  => 'November',
		'December'  => 'Desember',
		'Jan'       => 'Jan',
		'Feb'       => 'Feb',
		'Mar'       => 'Mar',
		'Apr'       => 'Apr',
		'Jun'       => 'Jun',
		'Jul'       => 'Jul',
		'Aug'       => 'Agu',
		'Sep'       => 'Sep',
		'Oct'       => 'Okt',
		'Nov'       => 'Nov',
		'Dec'       => 'Des',
	);

	return strtr( wp_date( $format, $stempel ), $peta );
}

/**
 * Jam sesi, dari tanggal mulai plus durasi.
 *
 * @param int $id ID acara.
 * @return string
 */
function tjr_v5_jam_acara( $id ) {
	$mulai = get_post_meta( $id, TJR_FIELD_MULAI, true );
	$stempel = $mulai ? strtotime( $mulai ) : false;

	if ( ! $stempel ) {
		return '';
	}

	$durasi = (float) get_post_meta( $id, 'durasi_jam', true );
	$selesai = $durasi ? $stempel + (int) round( $durasi * HOUR_IN_SECONDS ) : 0;

	$jam = wp_date( 'H.i', $stempel );

	if ( $selesai ) {
		$jam .= ' sampai ' . wp_date( 'H.i', $selesai );
	}

	return $jam . ' WIB';
}

/**
 * Nama tempat plus tautannya ke peta.
 *
 * @param int $id ID acara.
 * @return string HTML tautan, atau teks biasa kalau tidak ada tujuan peta.
 */
function tjr_v5_tempat_acara( $id ) {
	$nama   = trim( (string) get_post_meta( $id, 'venue_nama', true ) );
	$alamat = trim( (string) get_post_meta( $id, 'venue_alamat', true ) );
	$peta   = trim( (string) get_post_meta( $id, 'venue_maps', true ) );

	if ( '' === $nama ) {
		return '';
	}

	$tampil = $alamat ? $nama . ', ' . $alamat : $nama;

	if ( '' === $peta ) {
		// Tanpa link Maps yang diisi, dibuatkan pencarian dari namanya.
		$peta = 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode( $tampil );
	}

	return sprintf(
		'<a class="tempat" href="%s" target="_blank" rel="noopener">%s</a>',
		esc_url( $peta ),
		esc_html( $tampil )
	);
}

/**
 * Isi kit, dirangkai jadi satu kalimat.
 *
 * @param int $id ID acara.
 * @return string
 */
function tjr_v5_kit_acara( $id ) {
	$kit = get_post_meta( $id, 'isi_kit', true );

	if ( is_string( $kit ) ) {
		$kit = maybe_unserialize( $kit );
	}

	if ( ! is_array( $kit ) || ! $kit ) {
		return '';
	}

	$kit = array_map( 'trim', array_filter( array_map( 'strval', $kit ) ) );

	if ( ! $kit ) {
		return '';
	}

	// Huruf pertama besar, sisanya apa adanya.
	$kit[0] = ucfirst( $kit[0] );

	return implode( ', ', $kit );
}

/**
 * Angka kursi: terisi, kapasitas, dan persennya.
 *
 * @param int $id ID acara.
 * @return array{terisi:int,kapasitas:int,persen:int}|null
 */
function tjr_v5_kursi_acara( $id ) {
	$kapasitas = (int) get_post_meta( $id, 'kapasitas', true );
	$terisi    = (int) get_post_meta( $id, 'slot_terisi', true );

	if ( $kapasitas < 1 ) {
		return null;
	}

	$terisi = max( 0, min( $terisi, $kapasitas ) );

	return array(
		'terisi'    => $terisi,
		'kapasitas' => $kapasitas,
		'persen'    => (int) round( $terisi / $kapasitas * 100 ),
	);
}

/**
 * Tukar isi baris fakta dan bar kursi dengan nilai acara yang sedang dirender.
 *
 * @param string   $konten Hasil render blok.
 * @param array    $parsed Blok yang sudah diurai.
 * @param WP_Block $blok   Instance blok.
 * @return string
 */
function tjr_v5_fakta_acara( $konten, $parsed, $blok = null ) {
	$nama = isset( $parsed['blockName'] ) ? $parsed['blockName'] : '';

	if ( 'core/paragraph' !== $nama && 'core/html' !== $nama ) {
		return $konten;
	}

	$id = ( $blok && isset( $blok->context['postId'] ) ) ? (int) $blok->context['postId'] : 0;

	if ( ! $id || 'acara' !== get_post_type( $id ) ) {
		return $konten;
	}

	$kelas = isset( $parsed['attrs']['className'] ) ? $parsed['attrs']['className'] : '';

	// Bar kursi, satu satunya HTML mentah, dicocokkan dari isinya.
	if ( 'core/html' === $nama || false !== strpos( $kelas, 'dd-bar' ) ) {
		if ( false === strpos( $konten, 'class="slot"' ) ) {
			return $konten;
		}

		$kursi = tjr_v5_kursi_acara( $id );

		if ( ! $kursi ) {
			return '';
		}

		return sprintf(
			'<div class="slot" role="img" aria-label="%1$s dari %2$s kursi sudah terisi"><i style="--p:%3$s%%"></i></div>',
			(int) $kursi['terisi'],
			(int) $kursi['kapasitas'],
			(int) $kursi['persen']
		);
	}

	$isi = null;

	if ( false !== strpos( $kelas, 'dd-waktu' ) ) {
		$isi = esc_html( tjr_v5_jam_acara( $id ) );
	} elseif ( false !== strpos( $kelas, 'dd-tempat' ) ) {
		$isi = tjr_v5_tempat_acara( $id );
	} elseif ( false !== strpos( $kelas, 'dd-kit' ) ) {
		$isi = esc_html( tjr_v5_kit_acara( $id ) );
	} elseif ( false !== strpos( $kelas, 'dd-bawa' ) ) {
		$bawa = trim( (string) get_post_meta( $id, 'bawa_sendiri', true ) );
		// Kalimat bawaannya berlaku untuk hampir semua sesi, jadi baris ini tidak
		// dibuang waktu kosong. Yang dipakai teks yang sudah tertulis di pattern.
		$isi  = '' !== $bawa ? esc_html( $bawa ) : null;
	} elseif ( false !== strpos( $kelas, 'dd-kursi' ) ) {
		$kursi = tjr_v5_kursi_acara( $id );
		$isi   = $kursi ? esc_html( $kursi['terisi'] . ' dari ' . $kursi['kapasitas'] . ' kursi sudah terisi' ) : '';
	}

	if ( null === $isi ) {
		return $konten;
	}

	// Kalau field-nya kosong, seluruh barisnya dibuang, bukan diisi kalimat lama.
	if ( '' === $isi ) {
		return '';
	}

	return preg_replace( '#(<p\b[^>]*>).*?(</p>)#s', '${1}' . $isi . '${2}', $konten, 1 );
}
add_filter( 'render_block', 'tjr_v5_fakta_acara', 10, 3 );

/**
 * Pastikan blok paragraf dan HTML tahu sedang berada di postingan mana.
 *
 * @param array $metadata Metadata block.json.
 * @return array
 */
function tjr_v5_konteks_post_id( $metadata ) {
	if ( ! isset( $metadata['name'] ) ) {
		return $metadata;
	}

	if ( ! in_array( $metadata['name'], array( 'core/paragraph', 'core/html' ), true ) ) {
		return $metadata;
	}

	if ( ! isset( $metadata['usesContext'] ) || ! is_array( $metadata['usesContext'] ) ) {
		$metadata['usesContext'] = array();
	}

	if ( ! in_array( 'postId', $metadata['usesContext'], true ) ) {
		$metadata['usesContext'][] = 'postId';
	}

	if ( ! in_array( 'postType', $metadata['usesContext'], true ) ) {
		$metadata['usesContext'][] = 'postType';
	}

	return $metadata;
}
add_filter( 'block_type_metadata', 'tjr_v5_konteks_post_id' );


/**
 * Acara terdekat yang tanggalnya belum lewat.
 *
 * Dipakai kartu kecil di hero, yang bukan bagian dari Query Loop jadi harus
 * mencari sendiri.
 *
 * @return WP_Post|null
 */
function tjr_v5_acara_terdekat() {
	$q = new WP_Query(
		array(
			'post_type'      => 'acara',
			'post_status'    => 'publish',
			'posts_per_page' => 1,
			'meta_key'       => TJR_FIELD_MULAI,
			'orderby'        => 'meta_value',
			'order'          => 'ASC',
			'no_found_rows'  => true,
			'meta_query'     => array(
				array(
					'key'     => TJR_FIELD_MULAI,
					'value'   => current_datetime()->format( 'Y-m-d H:i:s' ),
					'compare' => '>=',
					'type'    => 'DATETIME',
				),
			),
		)
	);

	return $q->have_posts() ? $q->posts[0] : null;
}


/**
 * Kolom Yang perlu dibawa, disuntikkan ke grup Detail Acara.
 *
 * Grup itu dibuat lewat layar ACF dan hidup di database, jadi tidak ikut git.
 * acf_add_local_field dengan parent grup itu menambah satu kolom dari kode
 * tanpa menyentuh grupnya, jadi kolom ini ikut versi dan tidak bisa terhapus
 * tidak sengaja dari dasbor.
 */
function tjr_v5_field_bawa() {
	if ( ! function_exists( 'acf_add_local_field' ) ) {
		return;
	}

	acf_add_local_field(
		array(
			'key'           => 'field_tjr_bawa_sendiri',
			'label'         => 'Yang perlu dibawa',
			'name'          => 'bawa_sendiri',
			'type'          => 'text',
			'parent'        => 'group_tjr_acara',
			'instructions'  => 'Muncul di kartu sesi, sebelah kanan Yang disediakan. Kosongkan untuk memakai kalimat bawaan: Tidak ada. Jurnal sendiri boleh dibawa kalau ingin.',
			'placeholder'   => 'Tidak ada. Jurnal sendiri boleh dibawa kalau ingin',
			'menu_order'    => 90,
		)
	);
}
add_action( 'acf/init', 'tjr_v5_field_bawa' );
