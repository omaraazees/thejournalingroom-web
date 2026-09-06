<?php
/**
 * Lapisan head: title, description, canonical, Open Graph, Twitter card, JSON-LD.
 *
 * Ditulis di tema, bukan lewat plugin SEO. Alasannya nilai-nilainya sudah jadi
 * data di konten/v5-meta.md, jadi plugin cuma memindahkan tempat mengetik, dan
 * situs ini diserahkan ke pemilik brand yang non-teknis.
 *
 * Polanya sama seperti inc/isi-beranda.php: nilai bawaan ditulis SATU tempat,
 * di tjr_v5_seo_bawaan(), lalu dibaca dari mana-mana. Kalau ditulis dua kali,
 * cepat atau lambat keduanya jadi beda tanpa ada yang sadar.
 *
 * Sumber isi: konten/v5-meta.md untuk title dan description, dan
 * konten/v5-structured-data.json untuk JSON-LD. Nilai yang tidak ada di kedua
 * berkas itu TIDAK dikarang di sini, halamannya cuma dapat title.
 *
 * @package tjr-v5
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/* =====================================================================
 * 1. Nilai bawaan, satu tempat
 * ===================================================================== */

/**
 * Title dan description tiap halaman, persis seperti di konten/v5-meta.md.
 *
 * Kunci petanya bukan slug halaman melainkan peran halamannya, karena satu di
 * antaranya, `jadwal`, bukan halaman WordPress melainkan arsip CPT acara.
 *
 * Panjang di komentar dihitung, bukan dikira: title batas 60 karakter,
 * description 140 sampai 158.
 *
 * @param string $kunci Peran halaman. Kosongkan untuk dapat seluruh peta.
 * @return array
 */
function tjr_v5_seo_bawaan( $kunci = '' ) {
	$peta = array(
		'beranda'    => array(
			// 47 karakter.
			'judul'     => 'Workshop Journaling Jogja | The Journaling Room',
			// 149 karakter.
			'deskripsi' => 'Kelas journaling di Yogyakarta buat yang belum pernah menulis jurnal. Alat tulis kami siapkan, tidak ada giliran bercerita. Lihat jadwal terdekatnya.',
		),
		'jadwal'     => array(
			// 54 karakter.
			'judul'     => 'Jadwal Workshop Journaling Jogja | The Journaling Room',
			// 151 karakter.
			'deskripsi' => 'Tanggal, venue, harga, dan sisa kursi tiap sesi journaling TJR di Jogja. Kursinya sengaja dibatasi, jadi tanya slot lewat WhatsApp sebelum kamu datang.',
		),
		'galeri'     => array(
			// 53 karakter.
			'judul'     => 'Galeri Sesi Journaling di Jogja | The Journaling Room',
			// 152 karakter.
			'deskripsi' => 'Dokumentasi sepuluh workshop journaling yang sudah kami gelar di Yogyakarta. Lihat isi meja, deco station, dan suasananya sebelum kamu ambil satu kursi.',
		),
		'kolaborasi' => array(
			// 47 karakter.
			'judul'     => 'Kolaborasi Workshop Journaling Yogyakarta | TJR',
			// 149 karakter.
			'deskripsi' => 'Sepuluh kali The Journaling Room duduk bareng brand dan komunitas di Yogyakarta, dari Sunday Reads Club sampai Snapobox. Tanggal dan fotonya lengkap.',
		),
		'tentang'    => array(
			// 50 karakter.
			'judul'     => 'Tentang The Journaling Room | Kelas Menulis Jurnal',
			// 154 karakter.
			'deskripsi' => 'Caca dan Dhanty bikin ruang menulis jurnal yang dulu mereka cari sendiri. Kenali cara satu sore berjalan, dan kenapa di sini tidak ada halaman yang salah.',
		),
		'kontak'     => array(
			// 48 karakter.
			'judul'     => 'Kontak dan Cara Ambil Slot | The Journaling Room',
			// 147 karakter. Nomornya dirakit dari tjr_v5_nomor_wa() waktu dipakai,
			// jadi cukup satu tempat kalau nomornya berubah.
			'deskripsi' => 'Tanya jadwal, slot, atau kolaborasi lewat WhatsApp %nomor% dan Instagram. Dibalas 09.00 sampai 21.00. Basis kami di Mantrijeron, Yogyakarta.',
		),
	);

	if ( '' === $kunci ) {
		return $peta;
	}

	return isset( $peta[ $kunci ] ) ? $peta[ $kunci ] : array();
}

/**
 * Slug halaman WordPress yang dipetakan ke kunci di tjr_v5_seo_bawaan().
 *
 * Dipisah dari petanya sendiri karena `jadwal` tidak punya slug halaman, dan
 * `cerita` punya slug tapi tidak punya baris di v5-meta.md.
 *
 * @return array
 */
function tjr_v5_seo_peta_slug() {
	return array(
		'galeri'     => 'galeri',
		'kolaborasi' => 'kolaborasi',
		'tentang'    => 'tentang',
		'kontak'     => 'kontak',
	);
}

/**
 * Nomor WhatsApp dalam bentuk yang dibaca manusia, 0857 2022 5369.
 *
 * Diturunkan dari tjr_v5_nomor_wa() supaya nomor cuma hidup di satu tempat.
 *
 * @return string
 */
function tjr_v5_seo_nomor_tampil() {
	$nomor = tjr_v5_nomor_wa();

	// 62 di depan diganti 0, lalu dikelompokkan 4-4-4 seperti di halaman.
	if ( 0 === strpos( $nomor, '62' ) ) {
		$nomor = '0' . substr( $nomor, 2 );
	}

	if ( 12 === strlen( $nomor ) ) {
		return substr( $nomor, 0, 4 ) . ' ' . substr( $nomor, 4, 4 ) . ' ' . substr( $nomor, 8 );
	}

	return $nomor;
}


/* =====================================================================
 * 2. Konteks: halaman apa yang sedang dilihat
 * ===================================================================== */

/**
 * Judul, deskripsi, canonical, dan gambar untuk request yang sedang berjalan.
 *
 * Dirakit sekali per request. Nilai `deskripsi` boleh kosong, dan kalau kosong
 * memang tidak ada tag description yang dicetak. Itu disengaja: lebih baik
 * tidak ada daripada dikarang.
 *
 * @return array{judul:string,deskripsi:string,kanonik:string,gambar:string,tipe:string}
 */
function tjr_v5_seo_konteks() {
	static $simpan = null;

	if ( null !== $simpan ) {
		return $simpan;
	}

	$ctx = array(
		'judul'     => '',
		'deskripsi' => '',
		'kanonik'   => '',
		'gambar'    => '',
		'tipe'      => 'website',
	);

	$nama_situs = get_bloginfo( 'name' );

	if ( is_front_page() ) {
		$b = tjr_v5_seo_bawaan( 'beranda' );

		$ctx['judul']     = $b['judul'];
		$ctx['deskripsi'] = $b['deskripsi'];
		$ctx['kanonik']   = home_url( '/' );

	} elseif ( is_post_type_archive( 'acara' ) ) {
		$b = tjr_v5_seo_bawaan( 'jadwal' );

		$ctx['judul']     = $b['judul'];
		$ctx['deskripsi'] = $b['deskripsi'];
		$ctx['kanonik']   = (string) get_post_type_archive_link( 'acara' );

	} elseif ( is_singular( 'acara' ) ) {
		$id = get_queried_object_id();

		$ctx['judul']     = tjr_v5_seo_judul_acara( $id );
		$ctx['deskripsi'] = tjr_v5_seo_deskripsi_acara( $id );
		$ctx['kanonik']   = (string) get_permalink( $id );
		$ctx['gambar']    = (string) get_the_post_thumbnail_url( $id, 'full' );
		$ctx['tipe']      = 'article';

	} elseif ( is_page() ) {
		$id   = get_queried_object_id();
		$slug = get_post_field( 'post_name', $id );
		$peta = tjr_v5_seo_peta_slug();

		if ( isset( $peta[ $slug ] ) ) {
			$b = tjr_v5_seo_bawaan( $peta[ $slug ] );

			$ctx['judul']     = $b['judul'];
			$ctx['deskripsi'] = $b['deskripsi'];
		} else {
			// Halaman yang tidak punya baris di v5-meta.md, misalnya /cerita/.
			// Dapat title yang rapi, tapi TIDAK dapat description karangan.
			$ctx['judul'] = get_the_title( $id ) . ' | ' . $nama_situs;
		}

		$ctx['kanonik'] = (string) get_permalink( $id );
		$ctx['gambar']  = (string) get_the_post_thumbnail_url( $id, 'full' );

	} elseif ( is_home() ) {
		// Halaman untuk posting, /cerita/. WordPress tidak pernah mencetak
		// canonical untuk halaman ini, jadi dicetak di sini.
		$id = (int) get_option( 'page_for_posts' );

		$ctx['judul']   = ( $id ? get_the_title( $id ) : 'Cerita' ) . ' | ' . $nama_situs;
		$ctx['kanonik'] = $id ? (string) get_permalink( $id ) : home_url( '/' );

	} elseif ( is_singular() ) {
		$id = get_queried_object_id();

		$ctx['judul']     = get_the_title( $id ) . ' | ' . $nama_situs;
		$ctx['deskripsi'] = tjr_v5_seo_ringkas( get_the_excerpt( $id ) );
		$ctx['kanonik']   = (string) get_permalink( $id );
		$ctx['gambar']    = (string) get_the_post_thumbnail_url( $id, 'full' );
		$ctx['tipe']      = 'article';

	} elseif ( is_tax( array( 'format-acara', 'kota' ) ) || is_category() || is_tag() ) {
		$term = get_queried_object();

		if ( $term instanceof WP_Term ) {
			$tautan = get_term_link( $term );

			$ctx['judul']   = $term->name . ' | ' . $nama_situs;
			$ctx['kanonik'] = is_wp_error( $tautan ) ? '' : (string) $tautan;

			// Deskripsi term dipakai kalau memang diisi, kalau kosong dibuatkan
			// dari nama term dan jenis taksonominya, bukan dibiarkan kosong.
			$ctx['deskripsi'] = ( '' !== trim( (string) $term->description ) )
				? tjr_v5_seo_ringkas( $term->description )
				: tjr_v5_seo_deskripsi_taksonomi( $term );
		}

	} elseif ( is_search() ) {
		$ctx['judul'] = 'Hasil pencarian | ' . $nama_situs;

	} elseif ( is_404() ) {
		$ctx['judul'] = 'Halaman tidak ditemukan | ' . $nama_situs;
	}

	if ( '' === $ctx['judul'] ) {
		$ctx['judul'] = $nama_situs;
	}

	// Nomor WhatsApp baru disisipkan di sini, supaya petanya tetap satu tempat.
	$ctx['deskripsi'] = str_replace( '%nomor%', tjr_v5_seo_nomor_tampil(), $ctx['deskripsi'] );

	// Halaman kedua dan seterusnya kanoniknya ke dirinya sendiri, bukan ke
	// halaman pertama. Canonical yang menunjuk halaman lain membuang isi
	// halaman ini dari indeks.
	$halaman = (int) get_query_var( 'paged' );

	if ( $halaman > 1 && '' !== $ctx['kanonik'] ) {
		$ctx['kanonik'] = trailingslashit( $ctx['kanonik'] ) . 'page/' . $halaman . '/';
	}

	// Gambar bawaan: foto sesi, bukan logo. Logo di kotak preview WhatsApp
	// kelihatan seperti link kosong yang tidak diklik siapa pun.
	if ( '' === $ctx['gambar'] ) {
		$hero = tjr_v5_bawaan_foto( 'hero_foto' );

		if ( is_array( $hero ) && isset( $hero[0] ) ) {
			$ctx['gambar'] = get_theme_file_uri( '/assets/img/' . $hero[0] );
		}
	}

	$simpan = $ctx;

	return $simpan;
}

/**
 * Potong teks jadi panjang description yang wajar, di batas kata.
 *
 * @param string $teks  Teks mentah, boleh mengandung HTML.
 * @param int    $batas Panjang maksimum.
 * @return string
 */
function tjr_v5_seo_ringkas( $teks, $batas = 158 ) {
	$teks = trim( preg_replace( '/\s+/', ' ', wp_strip_all_tags( (string) $teks ) ) );

	// mb_ dipakai kalau ada, supaya potongannya tidak membelah karakter
	// multibyte jadi dua dan meninggalkan tanda tanya di hasil pencarian.
	$mb  = function_exists( 'mb_substr' );
	$len = $mb ? mb_strlen( $teks ) : strlen( $teks );

	if ( '' === $teks || $len <= $batas ) {
		return $teks;
	}

	$potong = $mb ? mb_substr( $teks, 0, $batas ) : substr( $teks, 0, $batas );
	$spasi  = strrpos( $potong, ' ' );

	if ( false !== $spasi ) {
		$potong = substr( $potong, 0, $spasi );
	}

	return rtrim( $potong, " ,.;:" );
}

/**
 * Deskripsi bawaan arsip taksonomi, dipakai kalau term-nya sendiri kosong.
 *
 * Menyebut nama term DAN jenis taksonominya, supaya /kota/yogyakarta/ dan
 * /format/brand-activation/ tidak menghasilkan kalimat berbentuk sama tapi
 * konteksnya beda.
 *
 * @param WP_Term $term Term arsip yang sedang dilihat.
 * @return string
 */
function tjr_v5_seo_deskripsi_taksonomi( $term ) {
	if ( 'kota' === $term->taxonomy ) {
		return tjr_v5_seo_ringkas( sprintf(
			'Jadwal workshop journaling TJR di %s: tanggal, venue, dan sisa kursi tiap sesi. Kursinya dibatasi, jadi tanya slot lewat WhatsApp sebelum datang.',
			$term->name
		) );
	}

	if ( 'format-acara' === $term->taxonomy ) {
		return tjr_v5_seo_ringkas( sprintf(
			'Jadwal acara TJR berformat %s: tanggal, venue, dan sisa kursi tiap sesi. Kursinya dibatasi, jadi tanya slot lewat WhatsApp sebelum datang.',
			$term->name
		) );
	}

	// Kategori atau tag bawaan WordPress, kalau situs ini pernah memakainya.
	return tjr_v5_seo_ringkas( sprintf( '%s di The Journaling Room.', $term->name ) );
}


/* =====================================================================
 * 3. Acara: title dan description dari field ACF
 * ===================================================================== */

/**
 * Apakah acara ini berformat lettering.
 *
 * v5-meta.md menyebut taksonomi format `lettering`. Term yang benar-benar ada
 * di situs bernama `brush-lettering-class`, jadi yang dicocokkan katanya, bukan
 * slug persisnya, supaya tidak patah kalau term-nya ditambah.
 *
 * @param int $id ID acara.
 * @return bool
 */
function tjr_v5_seo_acara_lettering( $id ) {
	$terms = get_the_terms( $id, 'format-acara' );

	if ( ! is_array( $terms ) ) {
		return false;
	}

	foreach ( $terms as $term ) {
		if ( false !== stripos( $term->slug, 'lettering' ) ) {
			return true;
		}
	}

	return false;
}

/**
 * Title halaman detail acara.
 *
 * Pola dari v5-meta.md: `%judul% | TJR Jogja`, dan untuk format lettering
 * `%judul% | Kelas Lettering Jogja`.
 *
 * Aturan pengaman judul panjang: kalau judulnya lewat batas, spesifikasi minta
 * SEO title diisi manual lewat field ACF `judul_seo_pendek`. Field itu BELUM
 * didaftarkan di situs ini. Selama belum ada, nilainya tetap dibaca kalau
 * kebetulan terisi, dan kalau tidak, suffix-nya yang dibuang, bukan judulnya
 * yang dipotong di tengah kata.
 *
 * @param int $id ID acara.
 * @return string
 */
function tjr_v5_seo_judul_acara( $id ) {
	$pendek = trim( (string) get_post_meta( $id, 'judul_seo_pendek', true ) );
	$judul  = '' !== $pendek ? $pendek : get_the_title( $id );

	$suffix = tjr_v5_seo_acara_lettering( $id ) ? ' | Kelas Lettering Jogja' : ' | TJR Jogja';
	$batas  = 60;

	if ( strlen( $judul . $suffix ) > $batas ) {
		return $judul;
	}

	return $judul . $suffix;
}

/**
 * Meta description halaman detail acara, dirakit dari field ACF.
 *
 * Tiga bentuk, sesuai v5-meta.md: sesi yang masih membuka slot, sesi yang
 * sudah penuh, dan sesi yang sudah lewat. Yang penuh dan yang lewat sengaja
 * tidak menulis "tanya slot", karena itu janji yang tidak bisa ditepati.
 *
 * @param int $id ID acara.
 * @return string
 */
function tjr_v5_seo_deskripsi_acara( $id ) {
	$mulai   = get_post_meta( $id, TJR_FIELD_MULAI, true );
	$stempel = $mulai ? strtotime( $mulai ) : 0;
	$tanggal = $stempel ? tjr_v5_tanggal_id( 'l, j F Y', $stempel ) : '';
	$venue   = trim( (string) get_post_meta( $id, 'venue_nama', true ) );

	// Tanpa tanggal atau venue, kalimatnya jadi timpang. Lebih baik tidak ada.
	if ( '' === $tanggal || '' === $venue ) {
		return '';
	}

	$kota  = 'Yogyakarta';
	$terms = get_the_terms( $id, 'kota' );

	if ( is_array( $terms ) && $terms ) {
		$kota = $terms[0]->name;
	}

	$tempat = $venue . ', ' . $kota;
	$kursi  = tjr_v5_kursi_acara( $id );
	$sisa   = $kursi ? $kursi['kapasitas'] - $kursi['terisi'] : null;

	// Sudah lewat: arahkan ke dokumentasi, bukan ke slot. Perbandingannya
	// string lawan string seperti di query jadwal, supaya zona waktunya sama.
	if ( $mulai && $mulai < current_datetime()->format( 'Y-m-d H:i:s' ) ) {
		return sprintf(
			'Sesi journaling %s di %s sudah selesai. Lihat foto dokumentasinya, lalu cek jadwal sesi berikutnya di halaman jadwal.',
			$tanggal,
			$tempat
		);
	}

	// Penuh: jangan menjanjikan slot yang tidak ada.
	if ( 0 === $sisa ) {
		return sprintf(
			'Sesi journaling %s di %s sudah penuh. Lihat jadwal workshop journaling Jogja berikutnya di halaman jadwal.',
			$tanggal,
			$tempat
		);
	}

	$kalimat = sprintf( 'Sesi journaling %s di %s.', $tanggal, $tempat );

	$harga = (int) get_post_meta( $id, 'harga', true );

	// Harga nol berarti belum diisi, bukan gratis. Jangan cetak Rp0.
	if ( $harga > 0 ) {
		$kalimat .= ' Kit lengkap, Rp' . number_format( $harga, 0, ',', '.' ) . '.';
	} else {
		$kalimat .= ' Kit lengkap.';
	}

	if ( null !== $sisa && $sisa > 0 ) {
		$kalimat .= ' Sisa ' . $sisa . ' kursi.';
	}

	// Ajakan WhatsApp cuma ditempel kalau MUAT UTUH. Sebelumnya selalu
	// ditempel lalu dipotong tjr_v5_seo_ringkas() di batas kata, dan karena
	// nomornya sendiri berspasi hasilnya berhenti di tengah nomor:
	// "Tanya slot lewat WhatsApp 0857". Nomor separuh di hasil pencarian
	// lebih merugikan daripada tidak ada ajakan sama sekali.
	$ajakan = ' Tanya slot lewat WhatsApp ' . tjr_v5_seo_nomor_tampil() . '.';
	$panjang = function_exists( 'mb_strlen' )
		? mb_strlen( $kalimat . $ajakan )
		: strlen( $kalimat . $ajakan );

	if ( $panjang <= 158 ) {
		$kalimat .= $ajakan;
	}

	return tjr_v5_seo_ringkas( $kalimat );
}


/* =====================================================================
 * 4. Title tag
 * ===================================================================== */

/**
 * Ambil alih seluruh title tag.
 *
 * Bawaan WordPress `%judul% - %nama situs%` memakai suffix 22 karakter, dan
 * itu yang bikin judul acara panjang terpotong di hasil pencarian. Pola di
 * v5-meta.md suffix-nya 12.
 *
 * @param string $judul Judul yang sudah dirakit inti, biasanya kosong.
 * @return string
 */
function tjr_v5_seo_document_title( $judul ) {
	$ctx = tjr_v5_seo_konteks();

	return $ctx['judul'] ? $ctx['judul'] : $judul;
}
add_filter( 'pre_get_document_title', 'tjr_v5_seo_document_title' );


/* =====================================================================
 * 5. Canonical
 * ===================================================================== */

/**
 * Canonical dicetak sendiri untuk SEMUA jenis halaman.
 *
 * WordPress inti cuma mencetaknya di halaman tunggal lewat rel_canonical, jadi
 * arsip CPT, arsip taksonomi, dan halaman untuk posting tidak pernah dapat.
 * Hook bawaannya dilepas dulu supaya tidak tercetak dua kali.
 */
remove_action( 'wp_head', 'rel_canonical' );


/* =====================================================================
 * 6. JSON-LD Organization dan LocalBusiness
 * ===================================================================== */

/**
 * Rentang harga sesi, dihitung dari acara yang sudah ada harganya.
 *
 * Ditulis rentang tetap di v5-structured-data.json, tapi angka itu cepat basi.
 * Dihitung supaya tidak pernah berbohong. Harga nol dianggap belum diisi,
 * bukan gratis, jadi tidak ikut dihitung.
 *
 * @return string Kosong kalau tidak ada satu pun harga terisi.
 */
function tjr_v5_seo_rentang_harga() {
	$kunci  = 'tjr_v5_seo_harga';
	$simpan = get_transient( $kunci );

	if ( false !== $simpan ) {
		return (string) $simpan;
	}

	$acara = get_posts(
		array(
			'post_type'              => 'acara',
			'post_status'            => 'publish',
			'posts_per_page'         => 100,
			'fields'                 => 'ids',
			'no_found_rows'          => true,
			'update_post_term_cache' => false,
		)
	);

	$harga = array();

	foreach ( $acara as $id ) {
		$nilai = (int) get_post_meta( $id, 'harga', true );

		if ( $nilai > 0 ) {
			$harga[] = $nilai;
		}
	}

	if ( $harga ) {
		$min     = 'Rp' . number_format( min( $harga ), 0, ',', '.' );
		$max     = 'Rp' . number_format( max( $harga ), 0, ',', '.' );
		$rentang = ( $min === $max ) ? $min : $min . ' sampai ' . $max;
	} else {
		$rentang = '';
	}

	// Dibuang lagi oleh tjr_v5_reset_hitungan() begitu ada acara yang berubah.
	set_transient( $kunci, $rentang, HOUR_IN_SECONDS );

	return $rentang;
}

/**
 * Graf JSON-LD yang dipasang di semua halaman.
 *
 * Isinya dari konten/v5-structured-data.json, dengan empat penyesuaian yang
 * disengaja dan semuanya dicatat:
 *
 * 1. URL gambar dan logo di berkas itu menunjuk /wp-content/uploads/, padahal
 *    berkasnya hidup di tema. Diarahkan ke assets/img/ yang benar-benar ada.
 * 2. Blok `address` dan `geo` TIDAK dipasang. Berkas sumbernya sendiri menulis
 *    "GANTI" di kedua tempat itu dan memberi dua pilihan: isi alamat persis
 *    seperti di Google Business Profile, atau hapus keduanya dan andalkan GBP.
 *    Alamat jalan TJR belum ada di halaman mana pun, dan mengarang alamat
 *    justru bikin Google ragu, jadi dipakai pilihan kedua. Begitu alamatnya
 *    ada, hidupkan lagi di sini DAN samakan huruf per huruf dengan GBP.
 * 3. `paymentAccepted` tidak dipasang. Berkas sumbernya menandainya "konfirmasi
 *    dulu, kalau QRIS belum ada hapus dari daftar", dan konfirmasinya belum ada.
 * 4. `priceRange` dihitung dari harga acara, bukan ditulis mati.
 *
 * Node Event acara yang sedang dibuka disisipkan di bawah, lewat
 * tjr_v5_seo_event_acara(). Cuma acara yang sedang dilihat yang disisipkan,
 * bukan seluruh acara sekaligus, karena blok ini dicetak di SETIAP halaman
 * dan mengambil semua acara di sini akan memboroskan query di halaman yang
 * tidak sedang menampilkan acara.
 *
 * @return array
 */
function tjr_v5_seo_graf() {
	$situs = home_url( '/' );
	$telp  = '+' . tjr_v5_nomor_wa();
	$jam   = array(
		'@type'     => 'OpeningHoursSpecification',
		'dayOfWeek' => array(
			'https://schema.org/Monday',
			'https://schema.org/Tuesday',
			'https://schema.org/Wednesday',
			'https://schema.org/Thursday',
			'https://schema.org/Friday',
			'https://schema.org/Saturday',
			'https://schema.org/Sunday',
		),
		'opens'     => '09:00',
		'closes'    => '21:00',
	);

	$foto = static function ( $berkas ) {
		return get_theme_file_uri( '/assets/img/' . $berkas );
	};

	$organisasi = array(
		'@type'         => 'Organization',
		'@id'           => $situs . '#organisasi',
		'name'          => 'The Journaling Room',
		'alternateName' => array( 'TJR', 'thejournalingroom' ),
		'url'           => $situs,
		'logo'          => array(
			'@type'   => 'ImageObject',
			'@id'     => $situs . '#logo',
			'url'     => $foto( 'tjr-black.png' ),
			'caption' => 'The Journaling Room',
		),
		'image'         => $foto( 'artotel-08.jpg' ),
		'description'   => 'Penyelenggara workshop journaling di Yogyakarta. Sesi dipandu, alat tulis dan bahan disediakan, tidak ada giliran bercerita di depan orang.',
		'slogan'        => 'Your kind journaling companions',
		'foundingDate'  => '2025-08',
		'sameAs'        => array( 'https://instagram.com/thejournalingroom' ),
		'knowsLanguage' => array( 'id', 'en' ),
		'areaServed'    => array(
			'@type'  => 'City',
			'name'   => 'Yogyakarta',
			'sameAs' => 'https://id.wikipedia.org/wiki/Kota_Yogyakarta',
		),
		'contactPoint'  => array(
			array(
				'@type'             => 'ContactPoint',
				'@id'               => $situs . '#whatsapp',
				'contactType'       => 'customer service',
				'name'              => 'WhatsApp The Journaling Room',
				'telephone'         => $telp,
				'url'               => 'https://wa.me/' . tjr_v5_nomor_wa(),
				'availableLanguage' => array( 'id', 'en' ),
				'areaServed'        => 'ID',
				'hoursAvailable'    => $jam,
			),
		),
	);

	$bisnis = array(
		'@type'                     => 'LocalBusiness',
		'@id'                       => $situs . '#bisnis',
		'name'                      => 'The Journaling Room',
		'url'                       => $situs,
		'image'                     => array(
			$foto( 'artotel-08.jpg' ),
			$foto( 'radian-11.jpg' ),
			$foto( 'kupiku-01.jpg' ),
		),
		'logo'                      => array( '@id' => $situs . '#logo' ),
		'description'               => 'Workshop journaling dan kelas menulis jurnal di Yogyakarta. Venue berpindah tiap sesi, dari kedai kopi sampai pendopo. Kursi dibatasi, kit disediakan.',
		'parentOrganization'        => array( '@id' => $situs . '#organisasi' ),
		'telephone'                 => $telp,
		'sameAs'                    => array( 'https://instagram.com/thejournalingroom' ),
		'currenciesAccepted'        => 'IDR',
		'areaServed'                => array(
			array(
				'@type'  => 'City',
				'name'   => 'Yogyakarta',
				'sameAs' => 'https://id.wikipedia.org/wiki/Kota_Yogyakarta',
			),
			array(
				'@type' => 'AdministrativeArea',
				'name'  => 'Daerah Istimewa Yogyakarta',
			),
		),
		'openingHoursSpecification' => array( $jam ),
		'contactPoint'              => array( '@id' => $situs . '#whatsapp' ),
		'makesOffer'                => array(
			array(
				'@type'       => 'Offer',
				'name'        => 'Journaling Workshop',
				'description' => 'Sesi tiga jam dengan tema, kit lengkap, dan pendampingan.',
			),
			array(
				'@type'       => 'Offer',
				'name'        => 'Brush Lettering Class dan Journaling',
				'description' => 'Kelas lettering digabung sesi journaling, kursi terbatas.',
			),
			array(
				'@type'       => 'Offer',
				'name'        => 'Journaling Playdate',
				'description' => 'Sesi santai tanpa materi, bawa jurnal sendiri boleh.',
			),
			array(
				'@type'       => 'Offer',
				'name'        => 'Brand Activation',
				'description' => 'Sesi journaling untuk brand, komunitas, dan tim kantor.',
			),
		),
	);

	$rentang = tjr_v5_seo_rentang_harga();

	if ( '' !== $rentang ) {
		$bisnis['priceRange'] = $rentang;
	}

	$graf = array( $organisasi, $bisnis );

	if ( is_singular( 'acara' ) ) {
		$event = tjr_v5_seo_event_acara( get_queried_object_id(), $situs );

		if ( null !== $event ) {
			$graf[] = $event;
		}
	}

	return array(
		'@context' => 'https://schema.org',
		'@graph'   => $graf,
	);
}


/* =====================================================================
 * 6.5. JSON-LD Event, untuk halaman detail acara
 * ===================================================================== */

/**
 * Pecah venue_alamat jadi PostalAddress: streetAddress, addressLocality,
 * postalCode, addressRegion, addressCountry.
 *
 * venue_alamat ditulis bebas sebagai satu baris teks (lihat field ACF-nya),
 * jadi tidak ada batas yang pasti antara jalan dan kota. Heuristik yang
 * dipakai: alamat Indonesia pada umumnya menutup dengan "<kota> <kode pos>"
 * sebagai segmen terakhir setelah koma, jadi segmen TERAKHIR itu yang
 * dianggap kota + kode pos, dan SEMUA segmen sebelumnya (termasuk
 * kecamatan, kalau ditulis) digabung jadi streetAddress apa adanya.
 *
 * Kecamatan sengaja tidak dipisah ke field sendiri: schema.org PostalAddress
 * tidak punya properti untuk itu, dan memaksanya jadi addressLocality akan
 * salah (addressLocality semestinya nama kota, bukan kecamatan). Contoh:
 * "Gg. Melati, Jl. Ngadinegaran MJ 3 No. 99, Mantrijeron, Yogyakarta 55143"
 * jadi streetAddress "Gg. Melati, Jl. Ngadinegaran MJ 3 No. 99, Mantrijeron"
 * dan addressLocality "Yogyakarta". Alamat yang formatnya beda (tidak
 * berakhir "<kota> <kode pos>", atau tidak ada koma sama sekali) akan
 * meleset, dan kalau itu terjadi baris ini yang perlu ditulis ulang, bukan
 * ditambal lagi jadi tebakan berlapis.
 *
 * @param string $alamat Isi field venue_alamat, boleh kosong.
 * @return array|null Null kalau alamatnya kosong.
 */
function tjr_v5_seo_alamat_acara( $alamat ) {
	$alamat = trim( (string) $alamat );

	if ( '' === $alamat ) {
		return null;
	}

	$bagian = array_map( 'trim', explode( ',', $alamat ) );

	// Tidak ada koma sama sekali: tidak ada batas yang bisa diandalkan, jadi
	// seluruh alamat dipakai sebagai streetAddress, TANPA menebak kotanya.
	if ( count( $bagian ) < 2 ) {
		return array(
			'@type'          => 'PostalAddress',
			'streetAddress'  => $alamat,
			'addressRegion'  => 'DI Yogyakarta',
			'addressCountry' => 'ID',
		);
	}

	$akhir    = array_pop( $bagian );
	$kode_pos = '';

	if ( preg_match( '/^(.*?)\s+(\d{5})$/', $akhir, $cocok ) ) {
		$akhir    = trim( $cocok[1] );
		$kode_pos = $cocok[2];
	}

	$pos = array(
		'@type'           => 'PostalAddress',
		'streetAddress'   => implode( ', ', $bagian ),
		'addressLocality' => $akhir,
		'addressRegion'   => 'DI Yogyakarta',
		'addressCountry'  => 'ID',
	);

	if ( '' !== $kode_pos ) {
		$pos['postalCode'] = $kode_pos;
	}

	return $pos;
}

/**
 * Node Event schema.org untuk satu acara.
 *
 * Field wajib Google untuk Event: name, startDate, location. Tanpa salah
 * satu, fungsi ini mengembalikan null dan tjr_v5_seo_graf() tidak menyisipkan
 * apa-apa, daripada mengirim Event setengah jadi yang ditolak validator.
 *
 * Field acara yang lain semuanya OPSIONAL di sini dengan sengaja:
 * - catatan_harga: tidak dipetakan ke Event sama sekali (itu modifier tampilan
 *   harga di kartu, bukan bagian dari Offer).
 * - slot_terisi: kalau kosong dianggap 0 oleh tjr_v5_kursi_acara(), jadi
 *   availability jatuh ke InStock, bukan bikin fungsi ini gagal.
 * - disediakan_teks: tidak relevan untuk Event, dilewati.
 * - venue_alamat, venue_maps, featured_media, durasi_jam, harga: masing-masing
 *   boleh kosong, dan kalau kosong properti terkaitnya (address, hasMap,
 *   image, endDate, offers) di bawah cuma tidak ikut ditulis.
 *
 * endDate dihitung dari tanggal_mulai + durasi_jam di wp_timezone(), BUKAN
 * UTC. Pola sama seperti tjr_v5_jam_acara() dan tjr_v5_acara_lewat() di
 * inc/isi-beranda.php: tanggal_mulai tersimpan sebagai waktu lokal tanpa
 * zona, jadi DateTimeImmutable dibuat dengan wp_timezone() secara eksplisit.
 *
 * @param int    $id    ID acara.
 * @param string $situs home_url( '/' ), diteruskan supaya tidak dihitung ulang.
 * @return array|null
 */
function tjr_v5_seo_event_acara( $id, $situs ) {
	$nama  = get_the_title( $id );
	$mulai = trim( (string) get_post_meta( $id, TJR_FIELD_MULAI, true ) );
	$venue = trim( (string) get_post_meta( $id, 'venue_nama', true ) );

	if ( '' === $nama || '' === $mulai || '' === $venue ) {
		return null;
	}

	try {
		$waktu_mulai = new DateTimeImmutable( $mulai, wp_timezone() );
	} catch ( Exception $e ) {
		return null;
	}

	$tautan = (string) get_permalink( $id );

	$event = array(
		'@type'               => 'Event',
		'@id'                 => $tautan . '#acara',
		'name'                => $nama,
		'startDate'           => $waktu_mulai->format( 'c' ),
		'eventAttendanceMode' => 'https://schema.org/OfflineEventAttendanceMode',
		'eventStatus'         => 'https://schema.org/EventScheduled',
		'url'                 => $tautan,
	);

	$deskripsi = tjr_v5_seo_deskripsi_acara( $id );

	if ( '' !== $deskripsi ) {
		$event['description'] = $deskripsi;
	}

	$durasi = (float) get_post_meta( $id, 'durasi_jam', true );

	if ( $durasi > 0 ) {
		$event['endDate'] = $waktu_mulai
			->modify( '+' . (int) round( $durasi * HOUR_IN_SECONDS ) . ' seconds' )
			->format( 'c' );
	}

	$gambar = get_the_post_thumbnail_url( $id, 'full' );

	if ( $gambar ) {
		$event['image'] = array( $gambar );
	}

	$lokasi = array(
		'@type' => 'Place',
		'name'  => $venue,
	);

	$alamat = tjr_v5_seo_alamat_acara( get_post_meta( $id, 'venue_alamat', true ) );

	if ( null !== $alamat ) {
		$lokasi['address'] = $alamat;
	}

	$peta = trim( (string) get_post_meta( $id, 'venue_maps', true ) );

	if ( '' !== $peta ) {
		$lokasi['hasMap'] = $peta;
	}

	$event['location'] = $lokasi;

	$harga = (int) get_post_meta( $id, 'harga', true );

	// Harga nol berarti belum diisi (lihat tjr_v5_harga_acara di
	// inc/isi-beranda.php), bukan gratis, jadi offers dilewati semua daripada
	// mengirim price 0.
	if ( $harga > 0 ) {
		$kursi        = tjr_v5_kursi_acara( $id );
		$penuh        = $kursi && 0 === ( $kursi['kapasitas'] - $kursi['terisi'] );
		$ketersediaan = $penuh ? 'https://schema.org/SoldOut' : 'https://schema.org/InStock';

		$event['offers'] = array(
			'@type'         => 'Offer',
			'price'         => (string) $harga,
			'priceCurrency' => 'IDR',
			'availability'  => $ketersediaan,
			'url'           => $tautan,
		);
	}

	// Organizer dirujuk lewat @id ke node Organization yang sudah ada di
	// @graph yang sama, pola yang sama seperti logo dan contactPoint di atas.
	// Tidak menulis ulang name/url Organization di sini.
	$event['organizer'] = array( '@id' => $situs . '#organisasi' );

	return $event;
}


/* =====================================================================
 * 7. Cetak ke head
 * ===================================================================== */

/**
 * Satu blok berisi description, canonical, Open Graph, Twitter card, dan JSON-LD.
 *
 * Prioritas 2 supaya berada di atas antrean wp_head, dekat dengan meta robots.
 */
function tjr_v5_seo_head() {
	$ctx = tjr_v5_seo_konteks();

	echo "\n<!-- TJR head -->\n";

	if ( '' !== $ctx['deskripsi'] ) {
		printf( '<meta name="description" content="%s" />' . "\n", esc_attr( $ctx['deskripsi'] ) );
	}

	// Halaman hasil pencarian dan 404 tidak punya alamat tetap, jadi tidak
	// diberi canonical maupun Open Graph. JSON-LD tetap dicetak, karena node
	// Organization dan LocalBusiness berlaku untuk seluruh situs.
	if ( '' !== $ctx['kanonik'] ) {
		printf( '<link rel="canonical" href="%s" />' . "\n", esc_url( $ctx['kanonik'] ) );

		printf( '<meta property="og:locale" content="%s" />' . "\n", esc_attr( get_locale() ) );
		printf( '<meta property="og:site_name" content="%s" />' . "\n", esc_attr( get_bloginfo( 'name' ) ) );
		printf( '<meta property="og:type" content="%s" />' . "\n", esc_attr( $ctx['tipe'] ) );
		printf( '<meta property="og:title" content="%s" />' . "\n", esc_attr( $ctx['judul'] ) );
		printf( '<meta property="og:url" content="%s" />' . "\n", esc_url( $ctx['kanonik'] ) );

		if ( '' !== $ctx['deskripsi'] ) {
			printf( '<meta property="og:description" content="%s" />' . "\n", esc_attr( $ctx['deskripsi'] ) );
		}

		if ( '' !== $ctx['gambar'] ) {
			printf( '<meta property="og:image" content="%s" />' . "\n", esc_url( $ctx['gambar'] ) );
			printf( '<meta name="twitter:image" content="%s" />' . "\n", esc_url( $ctx['gambar'] ) );
		}

		// summary_large_image dipakai karena isinya foto sesi. Kartu kecil
		// membuat foto dokumentasi terlihat seperti ikon.
		echo '<meta name="twitter:card" content="summary_large_image" />' . "\n";
		printf( '<meta name="twitter:title" content="%s" />' . "\n", esc_attr( $ctx['judul'] ) );

		if ( '' !== $ctx['deskripsi'] ) {
			printf( '<meta name="twitter:description" content="%s" />' . "\n", esc_attr( $ctx['deskripsi'] ) );
		}
	}

	$json = wp_json_encode( tjr_v5_seo_graf(), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );

	// Garis miring dibiarkan apa adanya supaya URL-nya enak dibaca, tapi urutan
	// "</" tetap harus dilolos, kalau tidak satu nilai yang memuatnya bisa
	// menutup tag script lebih awal dan merusak seluruh halaman.
	printf(
		'<script type="application/ld+json">%s</script>' . "\n",
		str_replace( '</', '<\\/', $json )
	);
}
add_action( 'wp_head', 'tjr_v5_seo_head', 2 );


/* =====================================================================
 * 6. Sitemap: daftarkan halaman arsip acara
 * ===================================================================== */

/**
 * Masukkan /jadwal/ ke sitemap.
 *
 * Sitemap bawaan WordPress cuma memuat post INDIVIDUAL sebuah custom post
 * type, tidak pernah halaman arsipnya. Akibatnya /jadwal/ tidak terdaftar di
 * mana pun: diperiksa lewat URL Inspection Search Console pada 2026-09-07,
 * halaman itu satu-satunya yang berstatus "URL is unknown to Google" dengan
 * "No referring sitemaps detected", sementara URL lain tercatat ditemukan
 * lewat wp-sitemap.xml.
 *
 * Google masih bisa menemukannya lewat tautan internal "Semua jadwal", tapi
 * jalur itu lebih lambat dan bergantung pada halaman lain ikut ter-crawl.
 *
 * Disisipkan di depan halaman pertama saja supaya tidak berulang kalau
 * acaranya nanti banyak dan sitemap-nya terbagi beberapa halaman.
 *
 * @param array  $url_list  Daftar URL yang sudah dirakit inti.
 * @param string $post_type Tipe konten yang sedang dirender.
 * @param int    $page_num  Halaman sitemap ke berapa.
 * @return array
 */
function tjr_v5_sitemap_arsip_acara( $url_list, $post_type, $page_num ) {
	if ( 'acara' !== $post_type || 1 !== (int) $page_num ) {
		return $url_list;
	}

	$arsip = get_post_type_archive_link( 'acara' );

	if ( ! $arsip ) {
		return $url_list;
	}

	array_unshift( $url_list, array( 'loc' => $arsip ) );

	return $url_list;
}
add_filter( 'wp_sitemaps_posts_url_list', 'tjr_v5_sitemap_arsip_acara', 10, 3 );
