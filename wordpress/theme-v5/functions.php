<?php
/**
 * TJR v5, tema blok mandiri untuk The Journaling Room.
 *
 * Isi file ini cuma hal yang harus hidup di PHP: pemuatan aset, tipe konten
 * Acara, dua taksonomi, kategori pattern, varian gaya blok, dan tiga potong
 * chrome yang bukan konten (sprite ikon, sampul pembuka, tombol WhatsApp).
 *
 * Warna, jarak, dan tipografi TIDAK diatur di sini. Semuanya di theme.json.
 *
 * @package tjr-v5
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'TJR_V5_VERSION', '1.0.0' );

/**
 * Nama meta field tanggal mulai acara.
 *
 * Field-nya dibuat ACF (lihat wordpress/cms/acf-fields.json). Kalau nama field
 * di ACF berubah, cukup ubah satu baris ini, jangan ubah query-nya.
 */
if ( ! defined( 'TJR_FIELD_MULAI' ) ) {
	define( 'TJR_FIELD_MULAI', 'tanggal_mulai' );
}

/**
 * Nomor WhatsApp bawaan, format internasional tanpa tanda plus.
 * Tampilan untuk manusia: 0857 2022 5369.
 */
if ( ! defined( 'TJR_WA_DEFAULT' ) ) {
	define( 'TJR_WA_DEFAULT', '6285720225369' );
}

/**
 * Pesan pembuka yang sudah terisi di setiap tombol WhatsApp.
 * Sengaja ASCII murni, jadi aman lewat encoding server mana pun.
 */
if ( ! defined( 'TJR_PESAN_WA_DEFAULT' ) ) {
	define( 'TJR_PESAN_WA_DEFAULT', 'Halo, kakmin TJR, aku mau daftar journaling workshop, dong! *\\(^o^)/* <3' );
}

/**
 * Pesan awal untuk tombol yang menanyakan sisa slot, bukan mendaftar.
 * Dipakai kartu sesi terdekat, tombol jadwal, dan tombol mengambang.
 */
if ( ! defined( 'TJR_PESAN_WA_SLOT' ) ) {
	define( 'TJR_PESAN_WA_SLOT', 'Halo kakmin TJR, apakah slotnya masih ada untuk sesi terdekat? \\(*^_^*)/ <3' );
}


/**
 * Penanda versi aturan rewrite.
 *
 * Naikkan nilainya tiap kali ada perubahan yang menyentuh aturan rewrite, lalu
 * tjr_v5_flush_rewrite_sekali() menyegarkannya sekali di kunjungan berikutnya.
 * Isinya sengaja tanggal plus sebabnya, bukan angka urut, supaya yang membaca
 * tahu perubahan mana yang memaksa penyegaran.
 */
if ( ! defined( 'TJR_V5_REWRITE_VERSI' ) ) {
	define( 'TJR_V5_REWRITE_VERSI', '2026-09-07-sitemap-jadwal' );
}


/**
 * Isi beranda yang bisa diurus dari dasbor. Field-nya didaftarkan lewat kode,
 * pattern membacanya dengan aset tema sebagai bawaan.
 */
require_once get_theme_file_path( '/inc/isi-beranda.php' );

/**
 * Lapisan head: title, description, canonical, Open Graph, Twitter card, dan
 * JSON-LD. Ditulis di tema, bukan lewat plugin SEO.
 */
require_once get_theme_file_path( '/inc/seo.php' );


/* =====================================================================
 * 1. Dukungan tema
 * ===================================================================== */

/**
 * Dukungan tema yang tidak diurus theme.json.
 */
function tjr_v5_theme_support() {
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' ) );
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'editor-styles' );

	// Editor blok memakai CSS yang sama supaya tampilan di editor tidak melenceng.
	add_editor_style( 'style.css' );
	add_editor_style( tjr_v5_url_font_google() );

	// Ukuran gambar untuk kartu dan panggung hero.
	add_image_size( 'tjr-kartu', 900, 1200, true );
	add_image_size( 'tjr-panggung', 1600, 900, true );
	add_image_size( 'tjr-cetakan', 480, 480, true );

	load_theme_textdomain( 'tjr-v5', get_template_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'tjr_v5_theme_support' );


/* =====================================================================
 * 2. Aset
 * ===================================================================== */

/**
 * URL Google Fonts, dipakai sebagai cadangan.
 *
 * File woff2 lokal sudah didaftarkan lewat fontFace di theme.json. Selama
 * folder assets/fonts/ masih kosong, huruf diambil dari Google supaya halaman
 * tidak jatuh ke Times New Roman. Begitu file lokal diletakkan, hapus baris
 * wp_enqueue_style( 'tjr-v5-font-google' ) di bawah.
 */
function tjr_v5_url_font_google() {
	return 'https://fonts.googleapis.com/css2'
		. '?family=Playfair+Display:ital,wght@1,400;1,500;1,600'
		. '&family=Manrope:wght@400;500;700'
		. '&family=Pinyon+Script'
		. '&display=swap';
}

/**
 * Cek apakah file huruf lokal sudah diletakkan di assets/fonts/.
 *
 * Cukup satu file diperiksa sebagai penanda. Kalau file ini ada, dianggap
 * seluruh keluarga huruf sudah lengkap dan pemuatan dari Google dilewati.
 *
 * @return bool
 */
function tjr_v5_font_lokal_ada() {
	return file_exists( get_theme_file_path( '/assets/fonts/manrope-400.woff2' ) );
}

/**
 * Versi berkas untuk cache busting.
 *
 * Nomor versi tema jarang dinaikkan, padahal style.css dan pembuka.js sering
 * berubah. Kalau versinya diam, browser pengunjung lama menyajikan berkas
 * simpanan dan perubahan desainnya tidak pernah sampai. Jadi versinya diambil
 * dari waktu berkasnya terakhir diubah, dan turun ke versi tema kalau berkasnya
 * entah kenapa tidak terbaca.
 *
 * @param string $jalur Jalur relatif di dalam tema, contoh '/style.css'.
 * @return string
 */
function tjr_v5_versi_berkas( $jalur ) {
	$penuh = get_theme_file_path( $jalur );
	$waktu = file_exists( $penuh ) ? filemtime( $penuh ) : 0;

	return $waktu ? TJR_V5_VERSION . '.' . $waktu : TJR_V5_VERSION;
}

/**
 * Muat stylesheet tema, font cadangan, dan skrip pembuka halaman.
 */
function tjr_v5_enqueue() {
	// Selama file woff2 lokal belum ada, huruf diambil dari Google.
	// Begitu assets/fonts/ terisi, baris ini berhenti jalan dengan sendirinya.
	if ( ! tjr_v5_font_lokal_ada() ) {
		wp_enqueue_style(
			'tjr-v5-font-google',
			tjr_v5_url_font_google(),
			array(),
			null
		);
	}

	wp_enqueue_style(
		'tjr-v5',
		get_stylesheet_uri(),
		array(),
		tjr_v5_versi_berkas( '/style.css' )
	);

	wp_enqueue_script(
		'tjr-v5-pembuka',
		get_theme_file_uri( '/assets/js/pembuka.js' ),
		array(),
		tjr_v5_versi_berkas( '/assets/js/pembuka.js' ),
		array(
			'strategy'  => 'defer',
			'in_footer' => true,
		)
	);
}
add_action( 'wp_enqueue_scripts', 'tjr_v5_enqueue' );

/**
 * Preconnect ke server font supaya huruf muncul lebih cepat.
 *
 * @param array  $urls          Daftar URL.
 * @param string $relation_type Jenis relasi.
 * @return array
 */
function tjr_v5_preconnect_font( $urls, $relation_type ) {
	if ( 'preconnect' === $relation_type ) {
		$urls[] = array( 'href' => 'https://fonts.googleapis.com' );
		$urls[] = array(
			'href'        => 'https://fonts.gstatic.com',
			'crossorigin' => 'anonymous',
		);
	}
	return $urls;
}
add_filter( 'wp_resource_hints', 'tjr_v5_preconnect_font', 10, 2 );


/* =====================================================================
 * 3. Nomor dan link WhatsApp
 * ===================================================================== */

/**
 * Nomor WhatsApp TJR, sudah dibersihkan jadi angka saja.
 *
 * @return string
 */
function tjr_v5_nomor_wa() {
	$nomor = get_option( 'tjr_wa_number', TJR_WA_DEFAULT );
	$nomor = preg_replace( '/\D+/', '', (string) $nomor );

	if ( 0 === strpos( $nomor, '0' ) ) {
		$nomor = '62' . substr( $nomor, 1 );
	}

	return $nomor ? $nomor : TJR_WA_DEFAULT;
}

/**
 * Link WhatsApp umum, dipakai tombol mengambang dan kaki halaman.
 *
 * @param string $pesan Pesan awal. Kosongkan untuk memakai sapaan bawaan.
 * @return string
 */
function tjr_v5_link_wa( $pesan = '' ) {
	if ( '' === $pesan ) {
		$pesan = TJR_PESAN_WA_DEFAULT;
	}

	return 'https://wa.me/' . tjr_v5_nomor_wa() . '?text=' . rawurlencode( $pesan );
}

/**
 * Link WhatsApp untuk menanyakan sisa slot sesi terdekat.
 *
 * @return string
 */
function tjr_v5_link_wa_slot() {
	return tjr_v5_link_wa( TJR_PESAN_WA_SLOT );
}


/* =====================================================================
 * 3b. Angka yang ikut isi, supaya judul tidak pernah berbohong
 * ===================================================================== */

/**
 * Ubah bilangan jadi kata Indonesia. Di atas dua belas dikembalikan sebagai
 * angka, karena judul yang berbunyi "tiga puluh tujuh kali" jadi terlalu panjang.
 *
 * @param int $n Bilangan.
 * @return string
 */
function tjr_v5_angka_kata( $n ) {
	$kata = array(
		0  => 'Belum ada',
		1  => 'Satu',
		2  => 'Dua',
		3  => 'Tiga',
		4  => 'Empat',
		5  => 'Lima',
		6  => 'Enam',
		7  => 'Tujuh',
		8  => 'Delapan',
		9  => 'Sembilan',
		10 => 'Sepuluh',
		11 => 'Sebelas',
		12 => 'Dua belas',
	);

	$n = (int) $n;

	return isset( $kata[ $n ] ) ? $kata[ $n ] : (string) $n;
}

/**
 * Jumlah acara yang sudah terbit.
 *
 * @param string $kapan 'semua', 'lewat', atau 'depan'.
 * @return int
 */
function tjr_v5_jumlah_acara( $kapan = 'semua' ) {
	$kunci = 'tjr_v5_jumlah_acara_' . $kapan;
	$simpan = get_transient( $kunci );

	if ( false !== $simpan ) {
		return (int) $simpan;
	}

	$args = array(
		'post_type'              => 'acara',
		'post_status'            => 'publish',
		'posts_per_page'         => 100,
		'fields'                 => 'ids',
		'no_found_rows'          => false,
		'update_post_meta_cache' => false,
		'update_post_term_cache' => false,
	);

	if ( 'semua' !== $kapan ) {
		$args['meta_key']   = TJR_FIELD_MULAI;
		$args['meta_query'] = array(
			array(
				'key'     => TJR_FIELD_MULAI,
				'value'   => current_datetime()->format( 'Y-m-d H:i:s' ),
				'compare' => ( 'lewat' === $kapan ) ? '<' : '>=',
				'type'    => 'DATETIME',
			),
		);
	}

	$q = new WP_Query( $args );
	$jumlah = (int) $q->found_posts;

	set_transient( $kunci, $jumlah, HOUR_IN_SECONDS );

	return $jumlah;
}

/**
 * Buang hitungan yang tersimpan begitu ada acara yang berubah, supaya judulnya
 * ikut berubah di kunjungan berikutnya.
 *
 * @param int $id ID post.
 */
function tjr_v5_reset_hitungan( $id = 0 ) {
	if ( $id && 'acara' !== get_post_type( $id ) ) {
		return;
	}

	foreach ( array( 'semua', 'lewat', 'depan' ) as $kapan ) {
		delete_transient( 'tjr_v5_jumlah_acara_' . $kapan );
	}

	// Rentang harga di JSON-LD LocalBusiness dihitung dari acara juga.
	delete_transient( 'tjr_v5_seo_harga' );
}
add_action( 'save_post_acara', 'tjr_v5_reset_hitungan' );
add_action( 'deleted_post', 'tjr_v5_reset_hitungan' );
add_action( 'trashed_post', 'tjr_v5_reset_hitungan' );
add_action( 'untrashed_post', 'tjr_v5_reset_hitungan' );

/**
 * Kolom nomor WhatsApp di Customizer, supaya bisa diganti tanpa menyentuh kode.
 *
 * @param WP_Customize_Manager $wp_customize Objek Customizer.
 */
function tjr_v5_customizer_wa( $wp_customize ) {
	$wp_customize->add_section(
		'tjr_kontak',
		array(
			'title'    => 'Kontak TJR',
			'priority' => 30,
		)
	);

	$wp_customize->add_setting(
		'tjr_wa_number',
		array(
			'default'           => TJR_WA_DEFAULT,
			'type'              => 'option',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'tjr_wa_number',
		array(
			'label'       => 'Nomor WhatsApp',
			'description' => 'Boleh diketik pakai spasi atau tanda plus, nanti dirapikan sendiri.',
			'section'     => 'tjr_kontak',
			'type'        => 'text',
		)
	);
}
add_action( 'customize_register', 'tjr_v5_customizer_wa' );

/**
 * Sediakan link WhatsApp untuk dipakai di dalam pattern.
 *
 * @return string
 */
function tjr_v5_href_wa() {
	return esc_url( tjr_v5_link_wa() );
}


/* =====================================================================
 * 4. Tipe konten Acara
 * ===================================================================== */

/**
 * Daftarkan CPT acara kalau belum ada.
 *
 * Pengecekan post_type_exists penting karena tema lama atau plugin bisa saja
 * sudah mendaftarkan tipe konten yang sama. Mendaftar dua kali bikin label
 * bentrok dan rewrite rule ganda.
 *
 * show_in_rest wajib true, kalau tidak Query Loop dan editor blok tidak bisa
 * melihat tipe konten ini.
 */
function tjr_v5_register_acara() {
	if ( post_type_exists( 'acara' ) ) {
		return;
	}

	$labels = array(
		'name'                  => 'Acara',
		'singular_name'         => 'Acara',
		'menu_name'             => 'Acara',
		'add_new'               => 'Tambah acara',
		'add_new_item'          => 'Tambah acara baru',
		'edit_item'             => 'Edit acara',
		'new_item'              => 'Acara baru',
		'view_item'             => 'Lihat acara',
		'view_items'            => 'Lihat acara',
		'search_items'          => 'Cari acara',
		'not_found'             => 'Belum ada acara',
		'not_found_in_trash'    => 'Tidak ada acara di tempat sampah',
		'all_items'             => 'Semua acara',
		'archives'              => 'Jadwal',
		'featured_image'        => 'Foto acara',
		'set_featured_image'    => 'Pilih foto acara',
		'remove_featured_image' => 'Hapus foto acara',
		'use_featured_image'    => 'Pakai sebagai foto acara',
		'item_published'        => 'Acara terbit.',
		'item_updated'          => 'Acara diperbarui.',
	);

	register_post_type(
		'acara',
		array(
			'labels'           => $labels,
			'description'      => 'Satu sesi journaling: tanggal, venue, harga, dan slot.',
			'public'           => true,
			'show_in_rest'     => true,
			'menu_position'    => 5,
			'menu_icon'        => 'dashicons-calendar-alt',
			// 'title' tetap didukung supaya WordPress tahu judulnya, slug terbentuk
			// sendiri, dan bar atas editor tidak berbunyi No title. Kotak judul di
			// kanvas disembunyikan lewat CSS di tjr_v5_sembunyikan_judul_kanvas(),
			// karena pengisiannya sudah pindah ke kolom Judul acara di panel.
			'supports'         => array( 'title', 'editor', 'thumbnail', 'excerpt', 'revisions', 'custom-fields' ),
			'has_archive'      => 'jadwal',
			'rewrite'          => array(
				'slug'       => 'acara',
				'with_front' => false,
			),
			'taxonomies'       => array( 'format-acara', 'kota' ),
			'hierarchical'     => false,
			'capability_type'  => 'post',
			// map_meta_cap WAJIB true. Tanpa ini WordPress tidak pernah memetakan
			// meta cap delete_post ke cap primitif delete_posts, jadi menghapus
			// acara ditolak untuk SEMUA ORANG, administrator sekalipun: REST
			// menjawab rest_cannot_delete dan tombol Trash di dasbor pun mati.
			'map_meta_cap'     => true,
			'delete_with_user' => false,
		)
	);
}
add_action( 'init', 'tjr_v5_register_acara', 0 );


/**
 * Tipe konten Kolaborator.
 *
 * Satu entri sama dengan satu logo di pita kolaborator. Judulnya nama brand,
 * logonya diunggah sebagai Featured image. Dibuat tipe konten sendiri, bukan
 * daftar di kode, karena partnernya akan bertambah dan pemilik brand harus
 * bisa menambah tanpa menyentuh tema.
 *
 * public dibuat false karena logo tidak butuh halamannya sendiri. Yang
 * dinyalakan cuma layar dasbornya. Urutannya ikut kolom Order, jadi bisa
 * digeser tanpa mengubah nama.
 */
function tjr_v5_register_kolaborator() {
	register_post_type(
		'kolaborator',
		array(
			'labels'                => array(
				'name'                  => 'Kolaborator',
				'singular_name'         => 'Kolaborator',
				'add_new'               => 'Tambah kolaborator',
				'add_new_item'          => 'Tambah kolaborator baru',
				'edit_item'             => 'Ubah kolaborator',
				'new_item'              => 'Kolaborator baru',
				'search_items'          => 'Cari kolaborator',
				'not_found'             => 'Belum ada kolaborator',
				'not_found_in_trash'    => 'Tidak ada kolaborator di tong sampah',
				'all_items'             => 'Semua kolaborator',
				'menu_name'             => 'Kolaborator',
				'featured_image'        => 'Logo',
				'set_featured_image'    => 'Pilih logo',
				'remove_featured_image' => 'Hapus logo',
				'use_featured_image'    => 'Pakai sebagai logo',
			),
			'description'           => 'Nama dan logo brand yang pernah berkolaborasi. Muncul di pita kolaborator di beranda.',
			'public'                => false,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'show_in_rest'          => true,
			'menu_position'         => 6,
			'menu_icon'             => 'dashicons-groups',
			'supports'              => array( 'title', 'thumbnail', 'page-attributes' ),
			'has_archive'           => false,
			'rewrite'               => false,
			'capability_type'       => 'post',
		)
	);
}
add_action( 'init', 'tjr_v5_register_kolaborator', 0 );

/**
 * Daftar kolaborator untuk pita di beranda.
 *
 * Selama belum ada satu pun entri Kolaborator, hasilnya kosong dan pattern
 * memakai daftar bawaannya, jadi halaman tidak pernah kehilangan pita logo.
 *
 * @return array Daftar array{nama:string,logo:string}.
 */
function tjr_v5_kolaborator() {
	$q = new WP_Query(
		array(
			'post_type'      => 'kolaborator',
			'post_status'    => 'publish',
			'posts_per_page' => 60,
			'orderby'        => array(
				'menu_order' => 'ASC',
				'title'      => 'ASC',
			),
			'no_found_rows'  => true,
		)
	);

	$hasil = array();

	foreach ( $q->posts as $satu ) {
		$logo = get_the_post_thumbnail_url( $satu->ID, 'medium' );

		if ( ! $logo ) {
			continue;
		}

		$hasil[] = array(
			'nama' => get_the_title( $satu->ID ),
			'logo' => $logo,
		);
	}

	return $hasil;
}

/**
 * Dua taksonomi: format acara dan kota.
 *
 * Keduanya hierarkis supaya di editor tampil sebagai daftar centang, bukan
 * kotak isian bebas. Ini disengaja: pengelola tinggal mencentang, dan tidak
 * ada istilah baru yang lahir dari salah ketik.
 */
function tjr_v5_register_taxonomies() {
	if ( ! taxonomy_exists( 'format-acara' ) ) {
		register_taxonomy(
			'format-acara',
			array( 'acara' ),
			array(
				'labels'            => array(
					'name'          => 'Format acara',
					'singular_name' => 'Format acara',
					'all_items'     => 'Semua format',
					'edit_item'     => 'Edit format',
					'add_new_item'  => 'Tambah format',
					'search_items'  => 'Cari format',
					'not_found'     => 'Belum ada format',
				),
				'public'            => true,
				'show_in_rest'      => true,
				'hierarchical'      => true,
				'show_admin_column' => true,
				'rewrite'           => array(
					'slug'       => 'format',
					'with_front' => false,
				),
			)
		);
	}

	if ( ! taxonomy_exists( 'kota' ) ) {
		register_taxonomy(
			'kota',
			array( 'acara' ),
			array(
				'labels'            => array(
					'name'          => 'Kota',
					'singular_name' => 'Kota',
					'all_items'     => 'Semua kota',
					'edit_item'     => 'Edit kota',
					'add_new_item'  => 'Tambah kota',
					'search_items'  => 'Cari kota',
					'not_found'     => 'Belum ada kota',
				),
				'public'            => true,
				'show_in_rest'      => true,
				'hierarchical'      => true,
				'show_admin_column' => true,
				'rewrite'           => array(
					'slug'       => 'kota',
					'with_front' => false,
				),
			)
		);
	}
}
add_action( 'init', 'tjr_v5_register_taxonomies', 0 );

/**
 * Isi awal taksonomi, dijalankan sekali saat tema diaktifkan.
 */
function tjr_v5_seed_terms() {
	$formats = array(
		'Journaling Workshop',
		'Brush Lettering Class',
		'Sunday Reads Club',
		'Journaling Playdate',
		'Inner Circle',
		'Brand Activation',
	);

	foreach ( $formats as $nama ) {
		if ( ! term_exists( $nama, 'format-acara' ) ) {
			wp_insert_term( $nama, 'format-acara' );
		}
	}

	foreach ( array( 'Yogyakarta', 'Magelang', 'Jakarta' ) as $nama ) {
		if ( ! term_exists( $nama, 'kota' ) ) {
			wp_insert_term( $nama, 'kota' );
		}
	}

	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'tjr_v5_seed_terms' );

/**
 * Segarkan aturan rewrite sekali, dijaga penanda versi.
 *
 * Kenapa ini ada. Aturan rewrite disimpan WordPress di satu option dan cuma
 * ditulis ulang waktu ada yang memanggil flush. Kalau flush terakhir terjadi
 * saat pendaftar aturannya belum jalan, aturannya tidak pernah masuk, dan
 * URL-nya 404 selamanya walau fiturnya sendiri hidup.
 *
 * Itu yang terjadi di situs ini: flush terakhir jalan waktu opsi "Discourage
 * search engines" masih menyala. Selama menyala, WordPress mematikan seluruh
 * XML sitemap, jadi aturan `wp-sitemap*.xml` tidak ikut terdaftar. Sesudah
 * opsinya dimatikan, generatornya kembali hidup, `?sitemap=index` mengeluarkan
 * XML yang benar, dan `robots.txt` sudah menyebut sitemapnya, tapi
 * `/wp-sitemap.xml` tetap 404 karena aturannya tidak ada di option. Aturan CPT
 * Acara ikut flush yang sama dan selamat, itu sebabnya `/acara/{slug}/` jalan
 * sementara sitemap tidak.
 *
 * Penanda versinya dinaikkan tiap kali ada perubahan yang menyentuh aturan
 * rewrite: slug CPT, taksonomi baru, atau hal seperti sitemap yang mendaftarkan
 * aturannya sendiri.
 *
 * Tiga keputusan yang sengaja diambil:
 *
 * 1. Hook-nya `wp_loaded`, bukan `init`. Sitemap bawaan mendaftarkan aturannya
 *    di `init` prioritas 10, dan CPT di sini di prioritas 0. `wp_loaded` jalan
 *    sesudah seluruh `init` selesai, jadi tidak perlu menebak prioritas.
 * 2. Flush LUNAK, `flush_rewrite_rules( false )`. Yang perlu diperbarui cuma
 *    option-nya. Flush keras ikut menulis ulang `.htaccess`, dan menulis ke
 *    berkas milik hosting bukan risiko yang perlu diambil untuk ini.
 * 3. Tidak ada penjaga kedua yang memeriksa "kalau aturan sitemap hilang, flush
 *    lagi". Kelihatannya lebih pintar, tapi kalau suatu saat aturan itu memang
 *    seharusnya tidak ada, misalnya indexing dimatikan lagi, pemeriksaan itu
 *    akan memanggil flush di SETIAP request. Penanda versi tidak punya mode
 *    gagal seperti itu.
 */
function tjr_v5_flush_rewrite_sekali() {
	if ( TJR_V5_REWRITE_VERSI === get_option( 'tjr_v5_rewrite_versi' ) ) {
		return;
	}

	flush_rewrite_rules( false );

	// Penanda dipasang SESUDAH flush, supaya percobaan yang gagal di tengah
	// jalan diulang di request berikutnya, bukan dianggap sudah beres.
	update_option( 'tjr_v5_rewrite_versi', TJR_V5_REWRITE_VERSI, true );
}
add_action( 'wp_loaded', 'tjr_v5_flush_rewrite_sekali' );

/**
 * Jangan biarkan WordPress memasang 404 di request sitemap.
 *
 * Gejalanya: `/wp-sitemap.xml` mengeluarkan XML sitemapindex yang BENAR dan
 * lengkap, tapi kepalanya `HTTP 404`. Isinya betul, statusnya salah, dan Google
 * menolak sitemap yang menjawab 404.
 *
 * Sebabnya bukan aturan rewrite. Kalau aturannya tidak ada, query var `sitemap`
 * tidak akan terisi, dan tanpa itu `WP_Sitemaps::render_sitemaps()` pulang lebih
 * awal sehingga yang keluar halaman 404 tema berupa HTML, bukan XML. Kita dapat
 * XML, jadi aturannya ada dan requestnya memang sampai ke perender sitemap.
 *
 * Yang memasang 404-nya `WP::handle_404()`, dan itu jalan SEBELUM
 * `template_redirect`. Perender sitemap tidak pernah memanggil
 * `status_header( 200 )` untuk membatalkannya; di inti WordPress dia cuma
 * memasang 404, tidak pernah 200. Jadi statusnya sudah terlanjur waktu XML-nya
 * dicetak di atasnya.
 *
 * `pre_handle_404` adalah kait resmi untuk keadaan ini. Mengembalikan nilai
 * selain `false` membuat `handle_404()` pulang tanpa menyentuh status sama
 * sekali, dan status bawaan sebuah respons memang 200.
 *
 * Filternya SEMPIT dengan sengaja. Kalau kedua query var sitemap kosong, nilai
 * aslinya dikembalikan apa adanya, bukan `false`, supaya perilaku 404 halaman
 * lain dan filter milik pihak lain tidak ikut berubah diam-diam.
 *
 * Aman kalau indexing dimatikan lagi: dalam keadaan itu
 * `WP_Sitemaps::render_sitemaps()` memanggil `set_404()` sendiri, dan filter ini
 * tidak menghalanginya karena yang dilewati cuma `handle_404()`.
 *
 * @param bool     $preempt Nilai bawaan filter.
 * @param WP_Query $query   Query utama.
 * @return bool
 */
function tjr_v5_sitemap_jangan_404( $preempt, $query ) {
	if ( ! $query instanceof WP_Query ) {
		return $preempt;
	}

	$sitemap = (string) $query->get( 'sitemap' );
	$gaya    = (string) $query->get( 'sitemap-stylesheet' );

	if ( '' === $sitemap && '' === $gaya ) {
		return $preempt;
	}

	return true;
}
add_filter( 'pre_handle_404', 'tjr_v5_sitemap_jangan_404', 10, 2 );

/**
 * Logo cadangan untuk blok Site Logo.
 *
 * Blok Site Logo bawaan tidak mencetak apa apa kalau logo situs belum diatur,
 * dan di instalasi yang Media Library-nya masih kosong itu artinya bar atas
 * tampil tanpa merek sama sekali. Filter ini menambal lubang itu dengan berkas
 * logo yang ikut di dalam tema. Begitu logo asli diunggah lewat editor situs,
 * filter ini berhenti ikut campur dengan sendirinya.
 *
 * @param string $konten Hasil render blok.
 * @param array  $parsed Blok yang sudah diurai.
 * @return string
 */
function tjr_v5_logo_cadangan( $konten, $parsed ) {
	if ( ! isset( $parsed['blockName'] ) || 'core/site-logo' !== $parsed['blockName'] ) {
		return $konten;
	}

	// Logo situs sudah ada, biarkan blok bawaan yang bekerja.
	if ( get_theme_mod( 'custom_logo' ) ) {
		return $konten;
	}

	// tjr-mark, bukan tjr-black. Logonya tulisan tangan, dan di bar atas dia
	// dicat cuma 52 piksel tinggi. Berkas 900 piksel diturunkan sembilan kali
	// oleh browser, dan garis rambut di huruf sambungnya hilang. tjr-mark sudah
	// diperkecil lebih dulu dengan goresan yang ditebalkan sedikit.
	$berkas = get_theme_file_path( '/assets/img/tjr-mark.png' );

	if ( ! file_exists( $berkas ) ) {
		return $konten;
	}

	$kelas = isset( $parsed['attrs']['className'] ) ? $parsed['attrs']['className'] : '';
	$lebar = isset( $parsed['attrs']['width'] ) ? (int) $parsed['attrs']['width'] : 0;

	return sprintf(
		'<div class="wp-block-site-logo %1$s"><a href="%2$s" class="custom-logo-link" rel="home"><img class="custom-logo" src="%3$s" srcset="%3$s 400w, %4$s 800w" sizes="110px" alt="%5$s"%6$s></a></div>',
		esc_attr( $kelas ),
		esc_url( home_url( '/' ) ),
		esc_url( get_theme_file_uri( '/assets/img/tjr-mark.png' ) ),
		esc_url( get_theme_file_uri( '/assets/img/tjr-mark@2x.png' ) ),
		esc_attr( get_bloginfo( 'name' ) ),
		$lebar ? ' width="' . $lebar . '"' : ''
	);
}
add_filter( 'render_block', 'tjr_v5_logo_cadangan', 10, 2 );

/**
 * Tanggal acara yang tampil adalah tanggal sesinya, bukan tanggal posting.
 *
 * Blok Post Date bawaan cuma tahu post_date. Untuk tipe konten acara, tanggal
 * yang berarti buat pembaca adalah field ACF tanggal_mulai. Isi elemen time
 * ditukar di sini supaya markup dan kelasnya tetap sama persis.
 *
 * @param string   $konten Hasil render blok.
 * @param array    $parsed Blok yang sudah diurai.
 * @param WP_Block $blok   Instance blok, dipakai untuk membaca postId.
 * @return string
 */
function tjr_v5_tanggal_acara( $konten, $parsed, $blok = null ) {
	if ( ! isset( $parsed['blockName'] ) || 'core/post-date' !== $parsed['blockName'] ) {
		return $konten;
	}

	$id = ( $blok && isset( $blok->context['postId'] ) ) ? (int) $blok->context['postId'] : 0;

	if ( ! $id || 'acara' !== get_post_type( $id ) ) {
		return $konten;
	}

	$mulai = get_post_meta( $id, TJR_FIELD_MULAI, true );
	$stempel = $mulai ? strtotime( $mulai ) : false;

	if ( ! $stempel ) {
		return $konten;
	}

	$format = isset( $parsed['attrs']['format'] ) ? $parsed['attrs']['format'] : get_option( 'date_format' );

	// Atribut datetime ikut ditukar, bukan cuma teks yang dibaca orang. Isinya
	// dipakai mesin, jadi kalau dibiarkan menunjuk tanggal posting, data
	// terstrukturnya ikut salah.
	$konten = preg_replace(
		'#(<time\b[^>]*\bdatetime=")[^"]*(")#',
		'${1}' . esc_attr( wp_date( 'c', $stempel ) ) . '${2}',
		$konten,
		1
	);

	// Kurung kurawal wajib. Tanpa itu, '$1' . '23 Aug 2026' terbaca PCRE sebagai
	// referensi grup 123, dan angka pertama tanggalnya ikut hilang.
	return preg_replace(
		'#(<time\b[^>]*>).*?(</time>)#s',
		'${1}' . esc_html( tjr_v5_tanggal_id( $format, $stempel ) ) . '${2}',
		$konten,
		1
	);
}
add_filter( 'render_block', 'tjr_v5_tanggal_acara', 10, 3 );

/**
 * Label arsip taksonomi di templates/taxonomy.html.
 *
 * Satu berkas templat itu dipakai bersama oleh kota DAN format-acara, jadi
 * labelnya tidak bisa dipatok statis di HTML seperti "Cerita" di index.html.
 * Diisi di sini dari nama taksonomi yang sedang dilihat.
 *
 * @param string $konten Hasil render blok.
 * @param array  $parsed Blok yang sudah diurai.
 * @return string
 */
function tjr_v5_label_arsip_taksonomi( $konten, $parsed ) {
	if ( ! isset( $parsed['blockName'] ) || 'core/paragraph' !== $parsed['blockName'] ) {
		return $konten;
	}

	$kelas = isset( $parsed['attrs']['className'] ) ? $parsed['attrs']['className'] : '';

	if ( false === strpos( $kelas, 'lbl-taksonomi' ) ) {
		return $konten;
	}

	$term = get_queried_object();

	if ( ! ( $term instanceof WP_Term ) ) {
		return $konten;
	}

	$taksonomi = get_taxonomy( $term->taxonomy );
	$label     = $taksonomi ? $taksonomi->labels->singular_name : $term->taxonomy;

	return preg_replace( '#(<p\b[^>]*>).*?(</p>)#s', '${1}' . esc_html( $label ) . '${2}', $konten, 1 );
}
add_filter( 'render_block', 'tjr_v5_label_arsip_taksonomi', 10, 2 );

/**
 * Arsip acara diurut dari tanggal mulai, bukan tanggal publikasi.
 *
 * Acara yang belum lewat naik ke atas dan diurut dari yang paling dekat.
 * Kalau field tanggalnya belum diisi, acara tetap muncul, tidak hilang.
 *
 * @param WP_Query $query Query utama.
 */
function tjr_v5_urutkan_jadwal( $query ) {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}

	if ( ! $query->is_post_type_archive( 'acara' ) && ! $query->is_tax( array( 'format-acara', 'kota' ) ) ) {
		return;
	}

	$query->set( 'meta_key', TJR_FIELD_MULAI );
	$query->set( 'meta_type', 'DATETIME' );
	$query->set(
		'orderby',
		array(
			'meta_value' => 'ASC',
			'date'       => 'DESC',
		)
	);
	$query->set( 'posts_per_page', 24 );
}
add_action( 'pre_get_posts', 'tjr_v5_urutkan_jadwal' );

/**
 * Query Loop di pattern jadwal memakai dua kunci: sesi terdekat dan yang baru lewat.
 *
 * Blok Query di pattern menandai dirinya lewat namespace di atribut, lalu
 * filter ini menukar argumen query-nya. Dengan begitu pattern-nya tetap blok
 * inti biasa dan bisa diedit dari editor, tapi urutannya tetap benar.
 *
 * @param array    $args  Argumen WP_Query.
 * @param WP_Block $block Blok yang sedang dirender.
 * @return array
 */
function tjr_v5_query_acara( $args, $block ) {
	$ns = '';

	if ( isset( $block->context['namespace'] ) ) {
		$ns = (string) $block->context['namespace'];
	} elseif ( isset( $block->context['query']['namespace'] ) ) {
		$ns = (string) $block->context['query']['namespace'];
	}

	$dikenal = array( 'tjr/sesi-terdekat', 'tjr/baru-lewat', 'tjr/arsip' );

	if ( ! in_array( $ns, $dikenal, true ) ) {
		return $args;
	}

	$sekarang  = current_datetime()->format( 'Y-m-d H:i:s' );
	$mendatang = ( 'tjr/sesi-terdekat' === $ns );

	$jumlah = array(
		'tjr/sesi-terdekat' => 1,
		'tjr/baru-lewat'    => 3,
		'tjr/arsip'         => 24,
	);

	$args['post_type']      = 'acara';
	$args['posts_per_page'] = $jumlah[ $ns ];
	$args['meta_key']       = TJR_FIELD_MULAI;
	$args['orderby']        = 'meta_value';
	$args['order']          = $mendatang ? 'ASC' : 'DESC';
	$args['meta_query']     = array(
		array(
			'key'     => TJR_FIELD_MULAI,
			'value'   => $sekarang,
			'compare' => $mendatang ? '>=' : '<',
			'type'    => 'DATETIME',
		),
	);

	return $args;
}
add_filter( 'query_loop_block_query_vars', 'tjr_v5_query_acara', 10, 2 );


/**
 * Pastikan atribut namespace milik blok Query sampai ke blok Post Template.
 *
 * Tanpa ini, filter di atas tidak punya cara membedakan Query Loop mana yang
 * sedang dirender, karena filter query_loop_block_query_vars menerima blok
 * Post Template, bukan blok Query induknya. Dua baris ini menyambungkan
 * keduanya lewat context, dan aman dijalankan di versi WordPress yang sudah
 * menyambungkannya sendiri.
 *
 * @param array $metadata Metadata block.json.
 * @return array
 */
function tjr_v5_teruskan_namespace( $metadata ) {
	if ( ! isset( $metadata['name'] ) ) {
		return $metadata;
	}

	if ( 'core/query' === $metadata['name'] ) {
		if ( ! isset( $metadata['providesContext'] ) || ! is_array( $metadata['providesContext'] ) ) {
			$metadata['providesContext'] = array();
		}
		$metadata['providesContext']['namespace'] = 'namespace';
	}

	if ( 'core/post-template' === $metadata['name'] ) {
		if ( ! isset( $metadata['usesContext'] ) || ! is_array( $metadata['usesContext'] ) ) {
			$metadata['usesContext'] = array();
		}
		if ( ! in_array( 'namespace', $metadata['usesContext'], true ) ) {
			$metadata['usesContext'][] = 'namespace';
		}
	}

	return $metadata;
}
add_filter( 'block_type_metadata', 'tjr_v5_teruskan_namespace' );


/* =====================================================================
 * 5. Kategori pattern dan varian gaya blok
 * ===================================================================== */

/**
 * Dua kategori pattern sendiri, supaya section TJR berkumpul di satu tab
 * di penyisip blok dan tidak tercampur bawaan WordPress.
 *
 * File di folder patterns/ didaftarkan otomatis oleh WordPress 6.4 ke atas,
 * jadi tidak ada register_block_pattern() manual di sini.
 */
function tjr_v5_pattern_categories() {
	if ( ! function_exists( 'register_block_pattern_category' ) ) {
		return;
	}

	register_block_pattern_category(
		'tjr',
		array(
			'label'       => 'The Journaling Room',
			'description' => 'Section siap pakai untuk halaman TJR.',
		)
	);

	register_block_pattern_category(
		'tjr-beranda',
		array(
			'label'       => 'TJR beranda',
			'description' => 'Urutan section beranda, dari hero sampai ajakan WhatsApp.',
		)
	);
}
add_action( 'init', 'tjr_v5_pattern_categories', 9 );

/**
 * Varian gaya blok yang dipakai pattern.
 *
 * Semua tampilannya ada di style.css. Yang didaftarkan di sini cuma namanya,
 * supaya muncul di panel Gaya di editor dan bisa dipakai tanpa mengetik kelas.
 */
function tjr_v5_block_styles() {
	$daftar = array(
		'core/button' => array(
			array( 'pil', 'Pil garis' ),
			array( 'pil-isi', 'Pil isi' ),
			array( 'pil-rose', 'Pil rose' ),
		),
		'core/image'  => array(
			array( 'cetakan', 'Cetakan polaroid' ),
			array( 'strip-zaitun', 'Strip zaitun' ),
			array( 'strip-kraft', 'Strip kraft' ),
			array( 'strip-rose', 'Strip rose' ),
			array( 'strip-burgundy', 'Strip burgundy' ),
		),
		'core/group'  => array(
			array( 'seksi', 'Seksi berjarak' ),
			array( 'kepala-tengah', 'Kepala seksi rata tengah' ),
		),
	);

	foreach ( $daftar as $blok => $gaya ) {
		foreach ( $gaya as $satu ) {
			register_block_style(
				$blok,
				array(
					'name'  => $satu[0],
					'label' => $satu[1],
				)
			);
		}
	}
}
add_action( 'init', 'tjr_v5_block_styles' );

/**
 * Buang pattern bawaan WordPress dan pola dari Pattern Directory.
 *
 * Alasannya supaya penyisip blok cuma menampilkan pattern TJR. Kalau suatu
 * saat butuh pattern bawaan, hapus dua baris di bawah.
 */
function tjr_v5_batasi_pattern() {
	remove_theme_support( 'core-block-patterns' );
}
add_action( 'after_setup_theme', 'tjr_v5_batasi_pattern', 20 );

add_filter( 'should_load_remote_block_patterns', '__return_false' );


/* =====================================================================
 * 6. Chrome halaman: sprite ikon, sampul pembuka, tombol WhatsApp
 * ===================================================================== */

/**
 * Sprite ikon garis, dicetak sekali per halaman.
 *
 * Pattern memakainya lewat use href, misalnya:
 * <svg class="ikon"><use href="#i-kalender"></use></svg>
 *
 * Blok inti yang tidak bisa menampung SVG (butir daftar, paragraf) memakai
 * kelas ik-kalender dan kawan kawan, yang digambar lewat mask di style.css.
 */
function tjr_v5_sprite_ikon() {
	?>
<svg width="0" height="0" style="position:absolute" aria-hidden="true" focusable="false"><defs>
<symbol id="i-kalender" viewBox="0 0 16 16"><rect x="2.2" y="3.4" width="11.6" height="10.4" rx="1.6"/><path d="M2.2 6.8h11.6M5.4 1.9v2.6M10.6 1.9v2.6"/></symbol>
<symbol id="i-jam" viewBox="0 0 16 16"><circle cx="8" cy="8" r="5.9"/><path d="M8 4.5V8l2.5 1.7"/></symbol>
<symbol id="i-pin" viewBox="0 0 16 16"><path d="M8 14.4s5-4.3 5-7.7a5 5 0 0 0-10 0c0 3.4 5 7.7 5 7.7Z"/><circle cx="8" cy="6.7" r="1.9"/></symbol>
<symbol id="i-orang" viewBox="0 0 16 16"><circle cx="8" cy="5.2" r="2.5"/><path d="M3.1 13.7a4.9 4.9 0 0 1 9.8 0"/></symbol>
<symbol id="i-kotak" viewBox="0 0 16 16"><path d="M2.6 5.4 8 2.5l5.4 2.9v5.2L8 13.5l-5.4-2.9z"/><path d="M2.6 5.4 8 8.3l5.4-2.9M8 8.3v5.2"/></symbol>
<symbol id="i-tas" viewBox="0 0 16 16"><path d="M4.2 5.4h7.6l.8 8.2H3.4z"/><path d="M6 5.4a2 2 0 0 1 4 0"/></symbol>
<symbol id="i-foto" viewBox="0 0 16 16"><rect x="2.2" y="3.6" width="11.6" height="9.4" rx="1.6"/><circle cx="6" cy="7" r="1.2"/><path d="M2.6 12 6.4 8.6l2.3 2 2-1.7 2.6 2.4"/></symbol>
<symbol id="i-panah" viewBox="0 0 12 12"><path d="M1 11L11 1M11 1H3.5M11 1V8.5" stroke-width="1.4"/></symbol>
</defs></svg>
	<?php
}

/**
 * Sampul buku yang terbuka waktu halaman dibuka.
 *
 * Kelas intro dipasang lewat skrip sebaris supaya keadaan awal sudah terpasang
 * sebelum gambar pertama dicat. Tanpa JavaScript, kelas itu tidak pernah ada
 * dan halaman langsung tampil utuh.
 */
function tjr_v5_tirai() {
	$logo = get_theme_file_uri( '/assets/img/tjr-black.png' );

	// Kalau logo situs sudah diatur di Customizer, itu yang dipakai.
	$id_logo = (int) get_theme_mod( 'custom_logo' );
	if ( $id_logo ) {
		$src = wp_get_attachment_image_url( $id_logo, 'full' );
		if ( $src ) {
			$logo = $src;
		}
	}
	?>
<div class="tirai" id="tirai" aria-hidden="true">
	<div class="jatuh" id="jatuh"></div>
	<div class="sampul" id="sampul"><img src="<?php echo esc_url( $logo ); ?>" alt=""></div>
</div>
<script>document.documentElement.classList.add('intro')</script>
	<?php
}

/**
 * Cetak sprite dan sampul tepat setelah body dibuka.
 */
function tjr_v5_body_open() {
	tjr_v5_sprite_ikon();
	tjr_v5_tirai();
}
add_action( 'wp_body_open', 'tjr_v5_body_open' );

/**
 * Tombol WhatsApp mengambang. Muncul setelah halaman digulir 60 persen layar.
 */
function tjr_v5_fab() {
	?>
<a class="fab" id="fab" href="<?php echo esc_url( tjr_v5_link_wa_slot() ); ?>" target="_blank" rel="noopener" aria-label="Tanyakan slotnya ke kami lewat WhatsApp">
	<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.47 14.38c-.3-.15-1.76-.87-2.03-.97-.27-.1-.47-.15-.67.15-.2.3-.77.97-.94 1.17-.17.2-.35.22-.65.07-.3-.15-1.26-.46-2.4-1.48-.89-.79-1.49-1.77-1.66-2.07-.17-.3-.02-.46.13-.61.13-.13.3-.35.45-.52.15-.17.2-.3.3-.5.1-.2.05-.37-.02-.52-.08-.15-.67-1.61-.92-2.21-.24-.58-.49-.5-.67-.51h-.57c-.2 0-.52.07-.79.37-.27.3-1.04 1.02-1.04 2.48s1.06 2.88 1.21 3.08c.15.2 2.1 3.2 5.08 4.49.71.31 1.26.49 1.69.62.71.23 1.36.19 1.87.12.57-.09 1.76-.72 2.01-1.41.25-.7.25-1.29.17-1.42-.07-.12-.27-.2-.57-.35z"/><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.46 1.32 4.96L2 22l5.25-1.38a9.87 9.87 0 0 0 4.79 1.22h.01c5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.82 9.82 0 0 0 12.04 2zm0 18.13h-.01a8.2 8.2 0 0 1-4.18-1.15l-.3-.18-3.11.82.83-3.04-.2-.31a8.19 8.19 0 0 1-1.26-4.36c0-4.54 3.7-8.23 8.24-8.23 2.2 0 4.27.86 5.82 2.42a8.18 8.18 0 0 1 2.41 5.82c0 4.54-3.69 8.21-8.24 8.21z"/></svg>
	<span>Tanyakan slot</span>
</a>
	<?php
}
add_action( 'wp_footer', 'tjr_v5_fab' );
