<?php
/**
 * The Journaling Room - child theme GeneratePress
 *
 * Isi file ini cuma hal yang harus hidup di PHP:
 * pemuatan aset, tipe konten Acara, dua taksonomi, dan kategori pattern.
 * Warna, tipografi, dan spacing TIDAK diatur di sini, semuanya di theme.json.
 *
 * @package tjr
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Nama meta field tanggal mulai acara.
 *
 * Field-nya sendiri dibuat oleh ACF di lane A4. Kalau nama field di
 * acf-fields.json berbeda, cukup ubah satu baris ini, jangan ubah query-nya.
 */
if ( ! defined( 'TJR_FIELD_MULAI' ) ) {
	define( 'TJR_FIELD_MULAI', 'tjr_mulai' );
}

define( 'TJR_VERSION', '1.0.0' );


/* -------------------------------------------------------------------------
 * 1. Aset
 * ---------------------------------------------------------------------- */

/**
 * Muat CSS parent, child, font, dan tjr.css.
 */
function tjr_enqueue_assets() {
	wp_enqueue_style(
		'generatepress',
		get_template_directory_uri() . '/style.css',
		array(),
		wp_get_theme( 'generatepress' )->get( 'Version' )
	);

	wp_enqueue_style(
		'tjr-child',
		get_stylesheet_uri(),
		array( 'generatepress' ),
		TJR_VERSION
	);

	wp_enqueue_style(
		'tjr-fonts',
		tjr_font_url(),
		array(),
		null
	);

	wp_enqueue_style(
		'tjr-main',
		get_stylesheet_directory_uri() . '/assets/tjr.css',
		array( 'tjr-child', 'tjr-fonts' ),
		TJR_VERSION
	);
}
add_action( 'wp_enqueue_scripts', 'tjr_enqueue_assets', 20 );

/**
 * URL Google Fonts untuk tiga muka huruf TJR.
 *
 * CATATAN: ini masih memuat dari server Google. Untuk jangka panjang,
 * unduh file font ke assets/fonts/ lalu daftarkan lewat fontFace di theme.json.
 * Alasannya dua: halaman jadi lebih cepat, dan tidak ada permintaan ke
 * server pihak ketiga.
 */
function tjr_font_url() {
	return 'https://fonts.googleapis.com/css2'
		. '?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,400;1,500'
		. '&family=Inter:wght@400;500;600'
		. '&family=Petit+Formal+Script'
		. '&display=swap';
}

/**
 * Preconnect ke server font supaya huruf muncul lebih cepat.
 */
function tjr_font_preconnect( $urls, $relation_type ) {
	if ( 'preconnect' === $relation_type ) {
		$urls[] = array( 'href' => 'https://fonts.googleapis.com' );
		$urls[] = array(
			'href'        => 'https://fonts.gstatic.com',
			'crossorigin' => 'anonymous',
		);
	}
	return $urls;
}
add_filter( 'wp_resource_hints', 'tjr_font_preconnect', 10, 2 );

/**
 * Editor blok memakai CSS yang sama, supaya tampilan di editor
 * tidak berbeda jauh dari tampilan di situs.
 */
function tjr_editor_assets() {
	add_theme_support( 'editor-styles' );
	add_editor_style( 'assets/tjr.css' );
	add_editor_style( tjr_font_url() );
}
add_action( 'after_setup_theme', 'tjr_editor_assets' );

/**
 * Dukungan tema yang tidak diurus theme.json.
 */
function tjr_theme_support() {
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'wp-block-styles' );

	// Ukuran gambar untuk kartu jadwal dan hero acara.
	add_image_size( 'tjr-kartu', 720, 480, true );
	add_image_size( 'tjr-hero', 1600, 900, true );
}
add_action( 'after_setup_theme', 'tjr_theme_support' );


/* -------------------------------------------------------------------------
 * 2. Tipe konten Acara
 * ---------------------------------------------------------------------- */

/**
 * Daftarkan CPT acara.
 *
 * show_in_rest wajib true, kalau tidak Query Loop dan editor blok
 * tidak bisa melihat tipe konten ini.
 */
function tjr_register_acara() {
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
			'labels'             => $labels,
			'description'        => 'Satu sesi journaling: tanggal, venue, harga, dan slot.',
			'public'             => true,
			'show_in_rest'       => true,
			'menu_position'      => 5,
			'menu_icon'          => 'dashicons-calendar-alt',
			'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'revisions', 'custom-fields' ),
			'has_archive'        => 'jadwal',
			'rewrite'            => array(
				'slug'       => 'acara',
				'with_front' => false,
			),
			'taxonomies'         => array( 'format-acara', 'kota' ),
			'hierarchical'       => false,
			'capability_type'    => 'post',
			'delete_with_user'   => false,
		)
	);
}
add_action( 'init', 'tjr_register_acara', 0 );

/**
 * Dua taksonomi: format acara dan kota.
 *
 * Dua-duanya dibuat hierarkis supaya di editor tampil sebagai daftar centang,
 * bukan kotak isian bebas. Ini disengaja: Caca dan Dhanty tinggal mencentang,
 * dan tidak ada istilah baru yang lahir dari salah ketik.
 */
function tjr_register_taxonomies() {
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
add_action( 'init', 'tjr_register_taxonomies', 0 );

/**
 * Isi awal taksonomi, dijalankan sekali saat tema diaktifkan.
 *
 * Enam format diambil dari brand-brief.md. Kota diisi tiga yang sudah pernah.
 */
function tjr_seed_terms() {
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
add_action( 'after_switch_theme', 'tjr_seed_terms' );


/* -------------------------------------------------------------------------
 * 3. Urutan jadwal
 * ---------------------------------------------------------------------- */

/**
 * Arsip acara diurut dari tanggal mulai, bukan tanggal publikasi.
 *
 * Acara yang belum lewat naik ke atas dan diurut dari yang paling dekat.
 * Kalau field tanggalnya belum diisi, acara tetap muncul, tidak hilang.
 */
function tjr_urutkan_jadwal( $query ) {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}

	if ( ! $query->is_post_type_archive( 'acara' ) && ! $query->is_tax( array( 'format-acara', 'kota' ) ) ) {
		return;
	}

	$query->set( 'meta_key', TJR_FIELD_MULAI );
	$query->set( 'orderby', array( 'meta_value' => 'ASC', 'date' => 'DESC' ) );
	$query->set( 'meta_type', 'DATETIME' );
	$query->set( 'posts_per_page', 24 );
}
add_action( 'pre_get_posts', 'tjr_urutkan_jadwal' );


/* -------------------------------------------------------------------------
 * 4. Block pattern
 * ---------------------------------------------------------------------- */

/**
 * Kategori pattern sendiri, supaya semua section TJR berkumpul
 * di satu tab di penyisip blok dan tidak tercampur bawaan WordPress.
 *
 * File di folder patterns/ didaftarkan otomatis oleh WordPress 6.4 ke atas,
 * jadi tidak ada register_block_pattern() manual di sini.
 */
function tjr_register_pattern_category() {
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
}
add_action( 'init', 'tjr_register_pattern_category', 9 );

/**
 * Buang pattern bawaan WordPress dan pola dari Pattern Directory.
 *
 * Alasannya supaya penyisip blok cuma menampilkan pattern TJR.
 * Kalau suatu saat butuh pattern bawaan, hapus dua baris di bawah.
 */
function tjr_batasi_pattern() {
	remove_theme_support( 'core-block-patterns' );
}
add_action( 'after_setup_theme', 'tjr_batasi_pattern', 20 );

add_filter( 'should_load_remote_block_patterns', '__return_false' );
