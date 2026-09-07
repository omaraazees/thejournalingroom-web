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
 * Kalimat bawaan tiap kolom teks.
 *
 * Ini satu satunya tempat kalimat aslinya ditulis. Pattern membacanya lewat
 * tjr_v5_isi(), dan layar edit memakai kalimat yang sama untuk mengisi kolom
 * yang masih kosong. Kalau kalimatnya ditulis dua kali, cepat atau lambat yang
 * di layar edit dan yang di halaman jadi beda tanpa ada yang sadar.
 *
 * @param string $nama Nama field. Kosongkan untuk dapat seluruh peta.
 * @return string|array
 */
function tjr_v5_bawaan_teks( $nama = '' ) {
	// Filter acf/load_value memanggil ini untuk tiap kolom yang dimuat, jadi
	// petanya dirakit sekali saja per request.
	static $simpan = null;

	if ( null !== $simpan ) {
		if ( '' === $nama ) {
			return $simpan;
		}

		return isset( $simpan[ $nama ] ) ? $simpan[ $nama ] : '';
	}

	// Kalimat pengantar pertama menyebut sudah berapa kali TJR duduk bersama,
	// jadi angkanya dihitung di sini, bukan diketik.
	$jml  = function_exists( 'tjr_v5_jumlah_acara' ) ? tjr_v5_jumlah_acara( 'lewat' ) : 0;
	$kali = ( 0 === $jml )
		? 'Dari kedai kopi sampai pendopo tua'
		: tjr_v5_angka_kata( $jml ) . ' kali, dari kedai kopi sampai pendopo tua';

	$peta = array(
		/* Hero */
		'hero_label'             => 'Workshop journaling',
		'hero_judul'             => 'Your kind journaling companion',
		'hero_foto_judul'        => 'Kelas journaling di Jogja untuk semua kalangan berusia 11-55 tahun',
		'hero_foto_isi'          => 'Setiap kelas dapat diikuti oleh pemula maupun praktisi karena akan dibersamai oleh para fasilitator',
		'hero_cetakan_teks'      => 'Nov 2025',

		/* Pengantar */
		'pengantar_1'            => 'The Journaling Room menggelar workshop journaling dan kelas menulis jurnal di Yogyakarta sejak Agustus 2025. ' . $kali . ', selalu dengan pola yang sama: satu meja panjang, bahan yang sudah ditata rapi, dan waktu yang tidak diburu.',
		'pengantar_2'            => 'Tidak ada sesi perkenalan yang membuat kaku. Kamu boleh menulis, menempel, atau hanya memegang gunting sambil menonton orang lain bekerja. Sorenya selesai ketika kamu merasa selesai.',
		'kutipan'                => 'Halaman kosong tidak pernah menuntut apa apa',
		'pengantar_cetakan_teks' => 'Pendopo Radian',

		/* Galeri bento */
		'bento_1_teks'           => 'Radian',
		'bento_2_teks'           => 'Sunday Reads Club',
		'bento_3_teks'           => 'Wardah',
		'bento_4_teks'           => 'Pasar Jakal',

		/* Tumpukan cetakan */
		'cetakan_1_nama'         => 'Sunday Reads',
		'cetakan_2_nama'         => 'Radian',
		'cetakan_3_nama'         => 'Wardah',
		'cetakan_4_nama'         => 'Artotel',
		'cetakan_5_nama'         => 'Kolondjono',
		'cetakan_6_nama'         => 'AMCO x Naoki',
		'cetakan_7_nama'         => 'Snapobox',
		'cetakan_8_nama'         => 'Pasar Jakal',
		'cetakan_9_nama'         => 'Kupiku',
	);

	$simpan = $peta;

	if ( '' === $nama ) {
		return $peta;
	}

	return isset( $peta[ $nama ] ) ? $peta[ $nama ] : '';
}

/**
 * Berkas foto bawaan tiap kolom gambar, plus teks alternatifnya.
 *
 * Berkasnya ikut tema di git, bukan di Media Library, supaya halaman tetap
 * utuh di instalasi yang baru. Begitu kolomnya diisi dari dasbor, unggahan
 * itu yang menang.
 *
 * @param string $nama Nama field. Kosongkan untuk dapat seluruh peta.
 * @return array
 */
function tjr_v5_bawaan_foto( $nama = '' ) {
	$peta = array(
		/* Hero */
		'hero_foto'         => array( 'artotel-13.jpg', 'Rombongan peserta workshop journaling tersenyum sambil mengangkat jurnal masing masing' ),
		'hero_cetakan'      => array( 'sundayreads-27.jpg', 'Jurnal terbuka di atas meja dikelilingi washi tape dan stiker dekorasi' ),

		/* Pengantar */
		'pengantar_foto'    => array( 'artotel-14-pengantar-v1.jpg', 'Jurnal peserta digelar berjajar di lantai setelah sesi' ),
		'pengantar_cetakan' => array( 'radian-24.jpg', 'Peserta menulis di jurnal bersampul bunga merah' ),

		/* Galeri bento */
		'bento_1'           => array( 'radian-11.jpg', 'Sesi journaling di pendopo bersama Radian' ),
		'bento_2'           => array( 'sundayreads-08.jpg', 'Bahan journaling ditata dari atas meja' ),
		'bento_3'           => array( 'wardah-09.jpg', 'Kit alat tulis Wardah di atas meja' ),
		'bento_4'           => array( 'pasar-jakal-07.jpg', 'Tangan peserta menempel bahan di halaman jurnal' ),

		/* Tumpukan cetakan */
		'cetakan_1'         => array( 'sundayreads-12.jpg', 'Dokumentasi sesi TJR bersama Sunday Reads' ),
		'cetakan_2'         => array( 'radian-30.jpg', 'Dokumentasi sesi TJR bersama Radian' ),
		'cetakan_3'         => array( 'wardah-04.jpg', 'Dokumentasi sesi TJR bersama Wardah' ),
		'cetakan_4'         => array( 'artotel-19.jpg', 'Dokumentasi sesi TJR bersama Artotel' ),
		'cetakan_5'         => array( 'kolondjono-20.jpg', 'Dokumentasi sesi TJR bersama Kolondjono' ),
		'cetakan_6'         => array( 'amco-naoki-03.jpg', 'Dokumentasi sesi TJR bersama AMCO x Naoki' ),
		'cetakan_7'         => array( 'snapobox-08.jpg', 'Dokumentasi sesi TJR bersama Snapobox' ),
		'cetakan_8'         => array( 'pasar-jakal-02.jpg', 'Dokumentasi sesi TJR bersama Pasar Jakal' ),
		'cetakan_9'         => array( 'kupiku-04.jpg', 'Dokumentasi sesi TJR bersama Kupiku' ),
	);

	if ( '' === $nama ) {
		return $peta;
	}

	return isset( $peta[ $nama ] ) ? $peta[ $nama ] : array( '', '' );
}

/**
 * Ambil satu field isi beranda.
 *
 * @param string $nama   Nama field.
 * @param mixed  $bawaan Nilai kalau field kosong. Biarkan null supaya kalimat
 *                       bawaannya diambil dari tjr_v5_bawaan_teks().
 * @return mixed
 */
function tjr_v5_isi( $nama, $bawaan = null ) {
	if ( null === $bawaan ) {
		$bawaan = tjr_v5_bawaan_teks( $nama );
	}

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
 *                        Biarkan null supaya diambil dari tjr_v5_bawaan_foto().
 * @return string
 */
function tjr_v5_foto( $nama, $berkas = null ) {
	if ( null === $berkas ) {
		$bawaan = tjr_v5_bawaan_foto( $nama );
		$berkas = $bawaan[0];
	}

	$nilai = tjr_v5_isi( $nama, '' );

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
 * @param string $bawaan Alt cadangan. Biarkan null supaya diambil dari
 *                       tjr_v5_bawaan_foto().
 * @return string
 */
function tjr_v5_foto_alt( $nama, $bawaan = null ) {
	if ( null === $bawaan ) {
		$peta   = tjr_v5_bawaan_foto( $nama );
		$bawaan = $peta[1];
	}

	$nilai = tjr_v5_isi( $nama, '' );

	if ( is_array( $nilai ) && ! empty( $nilai['alt'] ) ) {
		return $nilai['alt'];
	}

	return $bawaan;
}


/* =====================================================================
 * Sifat gambar: ukuran asli, lazy, dan prioritas
 * ===================================================================== */

/**
 * Ukuran asli satu gambar, dicari dari URL-nya.
 *
 * Dipakai buat mencetak width dan height di tiap <img>. Ukurannya tidak boleh
 * ditulis tangan di pattern karena fotonya bisa diganti dari dasbor lewat ACF,
 * dan foto pengganti belum tentu sebangun dengan bawaannya. Angka yang salah
 * lebih buruk daripada tidak ada angka: browser memakainya untuk menghitung
 * aspect-ratio cadangan sebelum CSS sampai.
 *
 * Tiga sumber, berurutan:
 * 1. Unggahan Media Library, ukurannya sudah ada di metadata attachment.
 * 2. Berkas tema di assets/img/, dibaca sekali lalu disimpan di transient.
 * 3. Menyerah, kembalikan nol supaya pemanggilnya tidak mencetak apa apa.
 *
 * @param string $url URL gambar.
 * @return array array( int $lebar, int $tinggi ). Nol berarti tidak ketemu.
 */
function tjr_v5_ukuran_gambar( $url ) {
	static $ingat = array();

	if ( ! is_string( $url ) || '' === $url ) {
		return array( 0, 0 );
	}

	if ( isset( $ingat[ $url ] ) ) {
		return $ingat[ $url ];
	}

	$hasil = array( 0, 0 );

	// 1. Unggahan. attachment_url_to_postid() query database, jadi cuma
	// dicoba untuk URL yang memang ada di folder uploads.
	$unggahan = wp_get_upload_dir();

	if ( ! empty( $unggahan['baseurl'] ) && 0 === strpos( $url, $unggahan['baseurl'] ) ) {
		$id = attachment_url_to_postid( $url );

		if ( $id ) {
			$meta = wp_get_attachment_metadata( $id );

			if ( ! empty( $meta['width'] ) && ! empty( $meta['height'] ) ) {
				$hasil = array( (int) $meta['width'], (int) $meta['height'] );
			}
		}
	}

	// 2. Berkas tema. Satu transient menampung seluruh peta, jadi halaman
	// dengan dua puluh gambar tetap cuma sekali baca, bukan dua puluh kali
	// getimagesize() ke disk. Tiap entri menyimpan mtime berkasnya, jadi
	// deploy yang mengganti sebuah foto otomatis bikin entri itu saja
	// dihitung ulang, tanpa perlu menaikkan versi tema dengan tangan.
	if ( array( 0, 0 ) === $hasil ) {
		$pangkal = get_theme_file_uri( '/assets/img/' );

		if ( 0 === strpos( $url, $pangkal ) ) {
			$berkas = basename( (string) wp_parse_url( $url, PHP_URL_PATH ) );
			$jalur  = $berkas ? get_theme_file_path( '/assets/img/' . $berkas ) : '';
			$umur   = ( $jalur && file_exists( $jalur ) ) ? (int) filemtime( $jalur ) : 0;

			if ( $umur ) {
				$kunci = 'tjr_v5_ukuran_gambar';
				$peta  = get_transient( $kunci );

				if ( ! is_array( $peta ) ) {
					$peta = array();
				}

				if ( ! isset( $peta[ $berkas ] ) || $peta[ $berkas ][2] !== $umur ) {
					$ukur = getimagesize( $jalur );

					$peta[ $berkas ] = ( $ukur && ! empty( $ukur[0] ) )
						? array( (int) $ukur[0], (int) $ukur[1], $umur )
						: array( 0, 0, $umur );

					set_transient( $kunci, $peta, WEEK_IN_SECONDS );
				}

				$hasil = array( $peta[ $berkas ][0], $peta[ $berkas ][1] );
			}
		}
	}

	$ingat[ $url ] = $hasil;

	return $hasil;
}

/**
 * Atribut siap tempel untuk satu <img> di beranda.
 *
 * Yang dicetak: width dan height supaya kotaknya sudah dipesan sebelum
 * gambarnya datang, lalu salah satu dari dua perlakuan.
 *
 * Gambar pembuka ($utama true) TIDAK di-lazy dan diberi fetchpriority high.
 * Me-lazy gambar pembuka justru menunda LCP karena browser baru mengantrenya
 * sesudah tata letak dihitung.
 *
 * Gambar lain di-lazy. Beranda memuat 32 gambar, ~3,2 MB, dan di layar ponsel
 * cuma satu yang benar benar terlihat sebelum digulir. Sisanya sekarang baru
 * diunduh waktu didekati.
 *
 * Tema ini menulis <img> mentah di pattern, bukan lewat wp_get_attachment_image(),
 * jadi lapisan otomatis WordPress (wp_get_loading_optimization_attributes)
 * tidak pernah melihatnya. Itu sebabnya atributnya dipasang tangan di sini.
 *
 * @param string $url    URL gambar yang sama dengan yang dipakai di src.
 * @param bool   $utama  True cuma untuk gambar pembuka di atas lipatan.
 * @return string Atribut HTML, sudah diawali spasi. Kosong kalau tidak ada apa apa.
 */
function tjr_v5_sifat_gambar( $url, $utama = false ) {
	list( $lebar, $tinggi ) = tjr_v5_ukuran_gambar( $url );

	$sifat = '';

	if ( $lebar > 0 && $tinggi > 0 ) {
		$sifat .= ' width="' . (int) $lebar . '" height="' . (int) $tinggi . '"';
	}

	if ( $utama ) {
		$sifat .= ' fetchpriority="high" decoding="async"';
	} else {
		$sifat .= ' loading="lazy" decoding="async"';
	}

	return $sifat;
}

/**
 * URL .webp pendamping sebuah .jpg di assets/img/, kalau berkasnya ada.
 *
 * Kartu G-4. Server Hostinger memotong respons gambar JPEG besar (350-450 KB)
 * secara acak di tengah unduhan, sementara .webp yang jauh lebih kecil (hasil
 * optimasi kartu P-3) lolos utuh setiap kali diminta. Yang bisa ditambal dari
 * kode cuma menyajikan .webp yang sudah ada; potongan di sisi hosting sendiri
 * bukan urusan tema.
 *
 * Sebelum ini empat pattern (galeri bento, hero, pengantar, tumpukan cetakan)
 * masing masing menulis closure yang sama persis untuk pengecekan ini.
 * Disatukan di sini supaya slot baru ikut otomatis, dan cuma satu tempat yang
 * perlu diingat kalau berkas pendampingnya bertambah.
 *
 * @param string $url URL gambar asli. Cuma berkas .jpg/.jpeg di assets/img/
 *                     tema yang dicek; foto unggahan ACF di luar situ tidak
 *                     disentuh, dikembalikan apa adanya lewat string kosong.
 * @return string URL .webp, atau string kosong kalau sibling-nya tidak ada.
 */
function tjr_v5_webp_pendamping( $url ) {
	$webp = preg_replace( '/\.jpe?g$/i', '.webp', (string) $url );

	if ( $webp === $url ) {
		return '';
	}

	$jalur = get_theme_file_path( '/assets/img/' . basename( (string) wp_parse_url( $webp, PHP_URL_PATH ) ) );

	return ( $jalur && file_exists( $jalur ) ) ? $webp : '';
}

/**
 * <img> siap tempel di pattern, dibungkus <picture> kalau ada .webp pendamping.
 *
 * Kartu G-4, pertahanan lapis pertama. Sampai sebelum ini, alih ke .webp
 * dikerjakan dengan menukar src SEBELUM dicetak, jadi begitu unduhan .webp
 * itu sendiri yang gagal, tidak ada jalan mundur karena .jpg aslinya sudah
 * tidak disebut sama sekali di HTML. <picture> menjaga <img src> tetap
 * berisi URL asli (jpg) sebagai fallback, dan cuma menambahkan
 * <source type="image/webp"> di depannya yang dicoba browser lebih dulu.
 *
 * @param string $url    URL gambar asli, sama seperti keluaran tjr_v5_foto().
 * @param string $alt    Teks alternatif. Kosongkan untuk gambar dekoratif.
 * @param bool   $utama  Diteruskan ke tjr_v5_sifat_gambar().
 * @return string HTML siap echo: <picture>...</picture>, atau <img> tunggal
 *                kalau tidak ada .webp pendamping.
 */
function tjr_v5_gambar_tag( $url, $alt, $utama = false ) {
	$webp  = tjr_v5_webp_pendamping( $url );
	$sifat = tjr_v5_sifat_gambar( '' !== $webp ? $webp : $url, $utama );
	$img   = '<img src="' . esc_url( $url ) . '" alt="' . esc_attr( $alt ) . '"' . $sifat . '/>';

	if ( '' === $webp ) {
		return $img;
	}

	return '<picture><source srcset="' . esc_url( $webp ) . '" type="image/webp"/>' . $img . '</picture>';
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
	// Foto bawaannya ikut tema, bukan Media Library, jadi tidak bisa dipasang
	// sebagai isi kolom. Yang bisa: ditunjukkan. Tanpa ini kolomnya cuma
	// tertulis "No image selected" dan tidak ada cara tahu foto mana yang
	// sedang tampil di halaman.
	$bawaan = tjr_v5_bawaan_foto( $nama );

	if ( '' !== $bawaan[0] ) {
		$petunjuk .= '<span class="tjr-bawaan">'
			. '<img src="' . esc_url( get_theme_file_uri( '/assets/img/' . $bawaan[0] ) ) . '" alt="" />'
			. '<span>Ini yang tampil sekarang. Kosongkan kolom ini kalau mau memakainya lagi.</span>'
			. '</span>';
	}

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
		'placeholder'  => tjr_v5_bawaan_teks( $nama ),
		'rows'         => 3,
		'new_lines'    => '',
	);
}

/**
 * Isi kolom teks yang masih kosong dengan kalimat yang sekarang tampil.
 *
 * Kolom kosong itu jalan buntu buat orang yang tidak menulis kodenya: tidak
 * kelihatan kalimat mana yang sedang tampil, dan mengganti satu kata berarti
 * mengetik ulang seluruh paragraf. Jadi kolomnya dibuka sudah terisi, tinggal
 * disunting. Yang disimpan tetap cuma yang mereka tekan Update.
 *
 * @param mixed $nilai Nilai dari database.
 * @param mixed $id    ID post.
 * @param array $field Definisi field.
 * @return mixed
 */
function tjr_v5_muat_isi_beranda( $nilai, $id, $field ) {
	// Filter ini global, jadi kolom milik plugin lain disingkirkan lebih dulu
	// sebelum peta bawaannya ikut dirakit.
	if ( ! empty( $nilai ) || empty( $field['key'] ) || 0 !== strpos( $field['key'], 'field_tjr_' ) ) {
		return $nilai;
	}

	if ( empty( $field['name'] ) ) {
		return $nilai;
	}

	$bawaan = tjr_v5_bawaan_teks( $field['name'] );

	return ( '' !== $bawaan ) ? $bawaan : $nilai;
}
add_filter( 'acf/load_value', 'tjr_v5_muat_isi_beranda', 10, 3 );

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
			// Tanpa ini kolomnya TIDAK bisa dibaca maupun ditulis lewat REST:
			// /wp/v2/pages/6 mengembalikan "acf": [] dan tidak ada jalan lain.
			// Akibatnya satu satunya cara mengubah isi beranda lewat layar edit,
			// dan menyimpan di sana menuliskan SELURUH kolom yang terisi bawaan
			// ke database sekaligus, jadi bawaan di PHP berhenti berpengaruh.
			// Dengan ini satu kolom bisa diubah sendirian, yang lain tetap kosong
			// dan tetap ikut bawaan di tjr_v5_bawaan_teks().
			'show_in_rest'          => true,
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
/**
 * Huruf pertama dijadikan kapital, aman untuk UTF-8.
 *
 * Kenapa fungsi ini ada. Baris fakta acara diisi lewat CMS, jadi kapitalnya
 * ikut cara mengetik saat itu. Hasilnya berdampingan: baris kit terbaca
 * "A5 Notebook, Writing Kit, ..." sementara baris bawaan terbaca "tumbler,
 * barang/stationery ...". Yang rapi bukan mengandalkan penulisnya konsisten,
 * melainkan barisnya sendiri yang menjamin bentuknya.
 *
 * `ucfirst()` bawaan PHP bekerja per byte, jadi salah kalau nanti ada isian
 * yang diawali huruf beraksen. Yang dipakai di sini versi multibyte.
 *
 * @param string $teks Teks apa adanya dari CMS.
 * @return string
 */
function tjr_v5_awali_kapital( $teks ) {
	$teks = trim( (string) $teks );

	if ( '' === $teks ) {
		return '';
	}

	return mb_strtoupper( mb_substr( $teks, 0, 1 ) ) . mb_substr( $teks, 1 );
}

/**
 * Stempel waktu acara, dibaca sebagai waktu lokal situs.
 *
 * Kenapa fungsi ini ada. Nilai TJR_FIELD_MULAI disimpan sebagai "Y-m-d H:i:s"
 * TANPA zona, dan yang dimaksud penulisnya selalu jam dinding Jakarta.
 * `strtotime()` telanjang membacanya memakai zona default PHP, dan WordPress
 * menyetel zona default PHP ke UTC. Hasilnya digeser lagi oleh `wp_date()`
 * ke zona situs, jadi jamnya maju tujuh jam: 09.00 tampil sebagai 16.00.
 *
 * Empat pemanggil dulu mengulang kesalahan yang sama sendiri-sendiri. Sekarang
 * satu pintu, supaya tidak ada lagi yang lolos.
 *
 * @param int $id ID acara.
 * @return int|false Stempel UTC, atau false kalau tanggalnya kosong/tidak sah.
 */
function tjr_v5_stempel_acara( $id ) {
	$mulai = trim( (string) get_post_meta( $id, TJR_FIELD_MULAI, true ) );

	if ( '' === $mulai ) {
		return false;
	}

	try {
		$waktu = new DateTimeImmutable( $mulai, wp_timezone() );
	} catch ( Exception $e ) {
		return false;
	}

	return $waktu->getTimestamp();
}

/**
 * Kalimat kursi, satu sumber untuk semua tempat yang menampilkannya.
 *
 * Kenapa fungsi ini ada. Kalimat ini dulu disusun di tiga tempat terpisah
 * (baris fakta, bar kursi, dan kartu hero) dengan kata-kata yang sedikit
 * berbeda, jadi menambal satu tempat meninggalkan dua yang lain tetap tayang.
 *
 * Aturan isinya: acara tanpa pendaftar TIDAK mengumumkan "0 dari 8 kursi sudah
 * terisi". Itu bukti sosial terbalik, terpampang tepat waktu orang menimbang
 * ikut atau tidak. Angka kapasitasnya tetap jujur, cuma dibaca dari sisi yang
 * masih kosong.
 *
 * @param int $id ID acara.
 * @return string Kalimat siap tampil, atau kosong kalau kapasitasnya tidak ada.
 */
function tjr_v5_label_kursi( $id ) {
	$kursi = tjr_v5_kursi_acara( $id );

	if ( ! $kursi ) {
		return '';
	}

	if ( $kursi['terisi'] < 1 ) {
		return $kursi['kapasitas'] . ' kursi tersedia';
	}

	return $kursi['terisi'] . ' dari ' . $kursi['kapasitas'] . ' kursi sudah terisi';
}

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
	$stempel = tjr_v5_stempel_acara( $id );

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
 * Harga per orang, format Rupiah.
 *
 * Acara yang harganya belum diisi (kolom kosong atau nol, misalnya sesi yang
 * harganya belum turun dari brand brief) tidak menampilkan baris ini sama
 * sekali.
 *
 * Field `catatan_harga` dihapus atas keputusan Umar (kartu T-3, 7 Sep 2026).
 * Dulu isinya ditempel kecil di sebelah angka lewat span .slot-catatan.
 *
 * @param int $id ID acara.
 * @return string
 */
function tjr_v5_harga_acara( $id ) {
	$harga = (int) get_post_meta( $id, 'harga', true );

	if ( $harga < 1 ) {
		return '';
	}

	return 'Rp' . number_format( $harga, 0, ',', '.' );
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
 * Apakah tanggal acara ini sudah lewat.
 *
 * Dipakai untuk membedakan sesi yang masih bisa didaftari dari sesi yang sudah
 * selesai. Acara tanpa tanggal DIANGGAP BELUM lewat, karena menebak "sudah
 * selesai" dari data yang kosong lebih merugikan daripada menampilkan barisnya.
 *
 * Tanggalnya tersimpan sebagai waktu lokal tanpa zona, jadi dibaca dengan
 * wp_timezone(). strtotime() polos akan membacanya sebagai UTC dan meleset
 * tujuh jam untuk acara yang jatuh hari ini.
 *
 * @param int $id ID acara.
 * @return bool
 */
function tjr_v5_acara_lewat( $id ) {
	$mulai = trim( (string) get_post_meta( $id, TJR_FIELD_MULAI, true ) );

	if ( '' === $mulai ) {
		return false;
	}

	try {
		$waktu = new DateTimeImmutable( $mulai, wp_timezone() );
	} catch ( Exception $e ) {
		return false;
	}

	return $waktu->getTimestamp() < current_datetime()->getTimestamp();
}

/**
 * ID acara yang sedang dirender, dari konteks blok atau dari halaman tunggal.
 *
 * @param WP_Block|null $blok Instance blok.
 * @return int 0 kalau yang dirender bukan acara.
 */
function tjr_v5_id_acara_konteks( $blok = null ) {
	$id = ( $blok && isset( $blok->context['postId'] ) ) ? (int) $blok->context['postId'] : 0;

	// Di template halaman tunggal konteksnya kadang tidak diteruskan, jadi
	// dipakai postingan yang sedang ditampilkan.
	if ( ! $id && is_singular( 'acara' ) ) {
		$id = (int) get_the_ID();
	}

	if ( ! $id || 'acara' !== get_post_type( $id ) ) {
		return 0;
	}

	return $id;
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

	if ( 'core/button' === $nama ) {
		// Tombol bertanda wa-slot memakai nomor dan pesan tanya slot yang berlaku,
		// bukan alamat yang diketik di template.
		$kelas = isset( $parsed['attrs']['className'] ) ? $parsed['attrs']['className'] : '';

		if ( false === strpos( $kelas, 'wa-slot' ) ) {
			return $konten;
		}

		// Sesi yang sudah lewat tidak punya slot untuk ditanyakan, jadi ajakannya
		// dibuang. Tautan "Semua jadwal" di kepala halaman tetap ada, jadi
		// pengunjung tidak jadi buntu.
		$id_acara = tjr_v5_id_acara_konteks( $blok );

		if ( $id_acara && tjr_v5_acara_lewat( $id_acara ) ) {
			return '';
		}

		return preg_replace(
			'#(<a\b[^>]*\bhref=")[^"]*(")#',
			'${1}' . esc_url( tjr_v5_link_wa_slot() ) . '${2}',
			$konten,
			1
		);
	}

	if ( 'core/paragraph' !== $nama && 'core/html' !== $nama ) {
		return $konten;
	}

	$id = tjr_v5_id_acara_konteks( $blok );

	if ( ! $id ) {
		return $konten;
	}

	// Sesi yang sudah lewat tidak menawarkan kursi, jadi penghitungnya salah
	// berapa pun angkanya. Barisnya dan barnya dibuang, bukan diisi angka lain.
	$lewat = tjr_v5_acara_lewat( $id );

	$kelas = isset( $parsed['attrs']['className'] ) ? $parsed['attrs']['className'] : '';

	// Bar kursi, satu satunya HTML mentah, dicocokkan dari isinya.
	if ( 'core/html' === $nama || false !== strpos( $kelas, 'dd-bar' ) ) {
		if ( false === strpos( $konten, 'class="slot"' ) ) {
			return $konten;
		}

		$kursi = tjr_v5_kursi_acara( $id );

		if ( ! $kursi || $lewat ) {
			return '';
		}

		// Nama aksesibelnya wajib berbunyi sama dengan teks yang dilihat mata,
		// jadi kalimatnya diambil dari sumber yang sama.
		$label = tjr_v5_label_kursi( $id );

		return sprintf(
			'<div class="slot" role="img" aria-label="%1$s"><i style="--p:%2$s%%"></i></div>',
			esc_attr( $label ),
			(int) $kursi['persen']
		);
	}

	$isi = null;

	if ( false !== strpos( $kelas, 'dd-waktu' ) ) {
		$isi = esc_html( tjr_v5_jam_acara( $id ) );
	} elseif ( false !== strpos( $kelas, 'dd-tempat' ) ) {
		$isi = tjr_v5_tempat_acara( $id );
	} elseif ( false !== strpos( $kelas, 'dd-harga' ) ) {
		$isi = tjr_v5_harga_acara( $id );
	} elseif ( false !== strpos( $kelas, 'dd-kit' ) ) {
		// Teks bebas menang atas daftar centang, kalau diisi.
		$tulis = trim( (string) get_post_meta( $id, 'disediakan_teks', true ) );
		$isi   = esc_html( tjr_v5_awali_kapital( '' !== $tulis ? $tulis : tjr_v5_kit_acara( $id ) ) );
	} elseif ( false !== strpos( $kelas, 'dd-bawa' ) ) {
		$bawa = trim( (string) get_post_meta( $id, 'bawa_sendiri', true ) );
		// Kalimat bawaannya berlaku untuk hampir semua sesi, jadi baris ini tidak
		// dibuang waktu kosong. Yang dipakai teks yang sudah tertulis di pattern.
		$isi  = '' !== $bawa ? esc_html( tjr_v5_awali_kapital( $bawa ) ) : null;
	} elseif ( false !== strpos( $kelas, 'dd-kursi' ) ) {
		$isi = $lewat ? '' : esc_html( tjr_v5_label_kursi( $id ) );
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
 * Baris "Format" di detail acara dibuang utuh kalau acaranya tidak punya term.
 *
 * `wp:post-terms` bukan paragraf atau html, jadi tidak kena tjr_v5_fakta_acara
 * di atas dan tidak punya teks cadangan seperti baris tetangganya. Tanpa ini
 * label "Format" tercetak menggantung tanpa nilai. Baris ini baris fakta
 * SATU-SATUNYA yang isinya dari wp:post-terms, jadi pengecekan bentuknya
 * (paragraf lalu post-terms format-acara) aman dipakai tanpa salah sasaran.
 *
 * @param string $konten Hasil render blok.
 * @param array  $parsed Blok yang sudah diurai.
 * @return string
 */
function tjr_v5_baris_format_kosong( $konten, $parsed ) {
	if ( 'core/group' !== ( $parsed['blockName'] ?? '' ) ) {
		return $konten;
	}

	$anak = $parsed['innerBlocks'] ?? array();

	if ( 2 !== count( $anak ) ) {
		return $konten;
	}

	if ( 'core/paragraph' !== ( $anak[0]['blockName'] ?? '' ) || 'core/post-terms' !== ( $anak[1]['blockName'] ?? '' ) ) {
		return $konten;
	}

	if ( 'format-acara' !== ( $anak[1]['attrs']['term'] ?? '' ) ) {
		return $konten;
	}

	// core/post-terms mencetak '' tanpa pembungkus sama sekali kalau acaranya
	// tidak punya term, jadi ketiadaan class wp-block-post-terms cukup untuk
	// tahu barisnya kosong.
	if ( false === strpos( $konten, 'wp-block-post-terms' ) ) {
		return '';
	}

	return $konten;
}
add_filter( 'render_block', 'tjr_v5_baris_format_kosong', 9, 2 );

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

	if ( ! in_array( $metadata['name'], array( 'core/paragraph', 'core/html', 'core/button' ), true ) ) {
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
 * Panel Judul dan catatan sesi.
 *
 * Judul acara sengaja disediakan sebagai kolom biasa, karena kotak judul besar
 * di kanvas tidak terbaca sebagai kolom isian oleh yang bukan orang teknis.
 * Nilainya disinkronkan dua arah dengan judul postingan: waktu layar dibuka
 * kolomnya diisi dari judul, waktu disimpan judulnya ikut kolom. Jadi tidak ada
 * dua sumber kebenaran, dan tautan serta alamat halaman tetap benar.
 *
 * Dua baris di kartu sesi yang tidak punya tempat di grup Detail Acara. Yang
 * disediakan sebenarnya sudah ada sebagai daftar centang di sana, tapi kolom
 * teks di sini memberi jalan keluar waktu satu sesi kitnya berbeda dan tidak
 * cocok dijelaskan lewat centang.
 *
 * Dibuat sebagai grup sendiri, bukan disuntikkan ke grup Detail Acara.
 * acf_add_local_field dengan parent grup yang hidup di database membuat ACF
 * menganggap grup itu didefinisikan di kode, dan seluruh kolom aslinya hilang
 * dari layar. Grup terpisah tidak menyentuh grup lain sama sekali.
 */
function tjr_v5_daftar_catatan_sesi() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	acf_add_local_field_group(
		array(
			'key'                   => 'group_tjr_catatan_sesi',
			'title'                 => 'Judul dan catatan sesi',
			'fields'                => array(
				array(
					'key'          => 'field_tjr_judul_acara',
					'label'        => 'Judul acara',
					'name'         => 'judul_acara',
					'type'         => 'text',
					'instructions' => 'Nama sesinya. Ini yang tampil sebagai judul di kartu sesi terdekat, di arsip, dan di halaman acara.',
					'placeholder'  => 'Tracing Shadows, Mapping Stars',
					'required'     => 0,
				),
				array(
					'key'          => 'field_tjr_disediakan_teks',
					'label'        => 'Yang disediakan',
					'name'         => 'disediakan_teks',
					'type'         => 'text',
					'instructions' => 'Kosongkan untuk memakai daftar centang Yang didapat peserta di panel Detail Acara. Isi kalau ingin menulis sendiri, misalnya untuk sesi yang kitnya beda dari biasanya. Pisahkan dengan koma.',
					'placeholder'  => 'Jurnal, stiker, booklet prompt, deco station, satu minuman',
				),
				array(
					'key'          => 'field_tjr_bawa_sendiri',
					'label'        => 'Yang perlu dibawa',
					'name'         => 'bawa_sendiri',
					'type'         => 'text',
					'instructions' => 'Muncul di kartu sesi, sebelah kanan Yang disediakan. Kosongkan untuk memakai kalimat bawaan: Tidak ada. Jurnal sendiri boleh dibawa kalau ingin.',
					'placeholder'  => 'Tidak ada. Jurnal sendiri boleh dibawa kalau ingin',
				),
			),
			'location'              => array(
				array(
					array(
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => 'acara',
					),
				),
			),
			'menu_order'            => 20,
			'position'              => 'normal',
			'style'                 => 'default',
			'label_placement'       => 'top',
			'instruction_placement' => 'label',
			'active'                => true,
			// Tanpa ini kolomnya TIDAK bisa dibaca maupun ditulis lewat REST,
			// sama seperti group_tjr_isi_beranda di atas.
			'show_in_rest'          => true,
		)
	);
}
add_action( 'acf/init', 'tjr_v5_daftar_catatan_sesi' );


/**
 * Isi kolom Judul acara dari judul postingan waktu layar edit dibuka.
 *
 * @param mixed $nilai Nilai tersimpan.
 * @param int   $id    ID post.
 * @return mixed
 */
function tjr_v5_muat_judul_acara( $nilai, $id ) {
	if ( is_numeric( $id ) && 'acara' === get_post_type( $id ) ) {
		$judul = get_the_title( $id );

		// Acara baru lahir dengan status auto-draft dan judul "Auto Draft".
		// Itu judul internal WordPress, bukan isi, jadi kolomnya dibiarkan kosong.
		if ( 'auto-draft' === get_post_status( $id ) ) {
			return '';
		}

		if ( '' !== $judul ) {
			return $judul;
		}
	}

	return $nilai;
}
add_filter( 'acf/load_value/key=field_tjr_judul_acara', 'tjr_v5_muat_judul_acara', 10, 2 );

/**
 * Simpan kolom Judul acara balik ke judul postingan.
 *
 * Judul postingan tetap sumber kebenaran, karena dia yang dipakai tautan,
 * alamat halaman, dan daftar di dasbor. Kolom cuma pintu masuknya.
 *
 * @param int|string $id ID post.
 */
function tjr_v5_simpan_judul_acara( $id ) {
	if ( ! is_numeric( $id ) || 'acara' !== get_post_type( $id ) ) {
		return;
	}

	$baru = isset( $_POST['acf']['field_tjr_judul_acara'] )
		? sanitize_text_field( wp_unslash( $_POST['acf']['field_tjr_judul_acara'] ) )
		: '';

	if ( '' === $baru || $baru === get_the_title( $id ) ) {
		return;
	}

	// Nilainya tidak perlu disimpan sebagai meta, judul postingan yang dipakai.
	delete_post_meta( $id, 'judul_acara' );

	$ubah = array(
		'ID'         => (int) $id,
		'post_title' => $baru,
	);

	// Kotak judul di kanvas sudah tidak ada, jadi acara baru lahir tanpa judul
	// dan slug-nya jatuh ke angka ID. Begitu judulnya diisi, slug ikut dibuatkan.
	// Slug yang sudah rapi tidak pernah diganggu, supaya tautan lama tidak putus.
	$slug = get_post_field( 'post_name', $id );

	if ( '' === $slug || ctype_digit( (string) $slug ) ) {
		$ubah['post_name'] = sanitize_title( $baru );
	}

	remove_action( 'acf/save_post', 'tjr_v5_simpan_judul_acara', 20 );
	wp_update_post( $ubah );
	add_action( 'acf/save_post', 'tjr_v5_simpan_judul_acara', 20 );
}
add_action( 'acf/save_post', 'tjr_v5_simpan_judul_acara', 20 );


/**
 * Sembunyikan kotak judul besar di kanvas editor Acara.
 *
 * Judulnya diisi lewat kolom Judul acara di panel, jadi kotak di kanvas cuma
 * jadi tempat kedua yang membingungkan. Disembunyikan lewat CSS, bukan dengan
 * mencabut dukungan title, supaya WordPress tetap tahu judulnya: slug terbentuk
 * sendiri dan bar atas editor tidak berbunyi No title.
 */
function tjr_v5_sembunyikan_judul_kanvas() {
	$layar = get_current_screen();

	if ( ! $layar || 'acara' !== $layar->post_type || 'post' !== $layar->base ) {
		return;
	}

	$css = '.edit-post-visual-editor__post-title-wrapper,'
		. '.editor-visual-editor__post-title-wrapper{display:none}';

	wp_register_style( 'tjr-v5-editor-acara', false, array(), TJR_V5_VERSION );
	wp_enqueue_style( 'tjr-v5-editor-acara' );
	wp_add_inline_style( 'tjr-v5-editor-acara', $css );
}
add_action( 'admin_enqueue_scripts', 'tjr_v5_sembunyikan_judul_kanvas' );

/**
 * Gaya kecil untuk contekan foto bawaan di panel Isi Beranda.
 */
function tjr_v5_gaya_isi_beranda() {
	$css = '.tjr-bawaan{display:flex;align-items:center;gap:10px;margin-top:8px}'
		. '.tjr-bawaan img{width:72px;height:54px;object-fit:cover;border-radius:4px;'
		. 'border:1px solid #dcdcde;flex:none}'
		. '.tjr-bawaan > span{font-style:italic}';

	wp_register_style( 'tjr-v5-isi-beranda', false, array(), TJR_V5_VERSION );
	wp_enqueue_style( 'tjr-v5-isi-beranda' );
	wp_add_inline_style( 'tjr-v5-isi-beranda', $css );
}
add_action( 'admin_enqueue_scripts', 'tjr_v5_gaya_isi_beranda' );

/**
 * Atribut `sizes` gambar unggulan disesuaikan dengan lebar tampilnya.
 *
 * Kenapa fungsi ini ada. Blok `core/post-featured-image` selalu menulis
 * `sizes="(max-width: 1200px) 100vw, 1200px"`, karena WordPress menganggap
 * gambar unggulan selebar konten. Di tema ini gambar itu justru duduk di kolom
 * sempit: kartu sesi terdekat memberinya 41% lebar kartu, dan kartu arsip
 * membaginya bertiga. Akibatnya browser diberi tahu ia butuh 1200px padahal
 * ruang nyatanya sekitar 540px, lalu mengunduh kandidat srcset yang jauh lebih
 * besar dari perlu.
 *
 * Angka di bawah dihitung dari grid dan lebar lembar, bukan ditebak:
 * lembar 1580px dengan padding clamp(16px,3.2vw,48px) dan margin 16px.
 * Sengaja dibulatkan ke atas sedikit, karena `sizes` yang kekecilan membuat
 * gambar tampil buram sementara yang kebesaran cuma kehilangan sedikit hemat.
 *
 * @param string $konten HTML blok.
 * @param array  $parsed Blok terurai.
 * @return string
 */
function tjr_v5_sizes_gambar( $konten, $parsed ) {
	if ( ! isset( $parsed['blockName'] ) || 'core/post-featured-image' !== $parsed['blockName'] ) {
		return $konten;
	}

	$kelas = isset( $parsed['attrs']['className'] ) ? $parsed['attrs']['className'] : '';

	if ( false !== strpos( $kelas, 'gbr-sesi' ) ) {
		// Kartu sesi terdekat: satu kolom penuh sampai 900px, lalu 41% lebar kartu.
		$sizes = '(max-width: 900px) 92vw, (max-width: 1611px) 38vw, 608px';
	} elseif ( false !== strpos( $kelas, 'gbr-kartu' ) ) {
		// Kartu arsip: satu kolom, lalu dua, lalu tiga, dengan jarak 16px.
		$sizes = '(max-width: 600px) 92vw, (max-width: 900px) 45vw, (max-width: 1611px) 30vw, 484px';
	} else {
		return $konten;
	}

	return preg_replace(
		'#(<img\b[^>]*\bsizes=")[^"]*(")#',
		'${1}' . esc_attr( $sizes ) . '${2}',
		$konten,
		1
	);
}
add_filter( 'render_block', 'tjr_v5_sizes_gambar', 10, 2 );
