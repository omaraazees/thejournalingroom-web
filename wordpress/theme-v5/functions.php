<?php
/**
 * TJR v5, tema blok mandiri untuk The Journaling Room.
 *
 * Isi file ini cuma hal yang harus hidup di PHP: pemuatan aset, tipe konten
 * Acara, dua taksonomi, kategori pattern, varian gaya blok, dan tiga potong
 * chrome yang bukan konten (sprite ikon, tirai pembuka, tombol WhatsApp).
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
		TJR_V5_VERSION
	);

	wp_enqueue_script(
		'tjr-v5-pembuka',
		get_theme_file_uri( '/assets/js/pembuka.js' ),
		array(),
		TJR_V5_VERSION,
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
			'supports'         => array( 'title', 'editor', 'thumbnail', 'excerpt', 'revisions', 'custom-fields' ),
			'has_archive'      => 'jadwal',
			'rewrite'          => array(
				'slug'       => 'acara',
				'with_front' => false,
			),
			'taxonomies'       => array( 'format-acara', 'kota' ),
			'hierarchical'     => false,
			'capability_type'  => 'post',
			'delete_with_user' => false,
		)
	);
}
add_action( 'init', 'tjr_v5_register_acara', 0 );

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

	if ( 'tjr/sesi-terdekat' !== $ns && 'tjr/baru-lewat' !== $ns ) {
		return $args;
	}

	$sekarang = current_datetime()->format( 'Y-m-d H:i:s' );
	$mendatang = ( 'tjr/sesi-terdekat' === $ns );

	$args['post_type']      = 'acara';
	$args['posts_per_page'] = $mendatang ? 1 : 3;
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
 * 6. Chrome halaman: sprite ikon, tirai pembuka, tombol WhatsApp
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
 * Tirai pembuka halaman.
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
<div class="tirai" id="tirai" aria-hidden="true"><img src="<?php echo esc_url( $logo ); ?>" alt=""></div>
<script>document.documentElement.classList.add('intro')</script>
	<?php
}

/**
 * Cetak sprite dan tirai tepat setelah body dibuka.
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
<a class="fab" id="fab" href="<?php echo esc_url( tjr_v5_link_wa() ); ?>" target="_blank" rel="noopener" aria-label="Hubungi kami lewat WhatsApp">
	<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.47 14.38c-.3-.15-1.76-.87-2.03-.97-.27-.1-.47-.15-.67.15-.2.3-.77.97-.94 1.17-.17.2-.35.22-.65.07-.3-.15-1.26-.46-2.4-1.48-.89-.79-1.49-1.77-1.66-2.07-.17-.3-.02-.46.13-.61.13-.13.3-.35.45-.52.15-.17.2-.3.3-.5.1-.2.05-.37-.02-.52-.08-.15-.67-1.61-.92-2.21-.24-.58-.49-.5-.67-.51h-.57c-.2 0-.52.07-.79.37-.27.3-1.04 1.02-1.04 2.48s1.06 2.88 1.21 3.08c.15.2 2.1 3.2 5.08 4.49.71.31 1.26.49 1.69.62.71.23 1.36.19 1.87.12.57-.09 1.76-.72 2.01-1.41.25-.7.25-1.29.17-1.42-.07-.12-.27-.2-.57-.35z"/><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.46 1.32 4.96L2 22l5.25-1.38a9.87 9.87 0 0 0 4.79 1.22h.01c5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.82 9.82 0 0 0 12.04 2zm0 18.13h-.01a8.2 8.2 0 0 1-4.18-1.15l-.3-.18-3.11.82.83-3.04-.2-.31a8.19 8.19 0 0 1-1.26-4.36c0-4.54 3.7-8.23 8.24-8.23 2.2 0 4.27.86 5.82 2.42a8.18 8.18 0 0 1 2.41 5.82c0 4.54-3.69 8.21-8.24 8.21z"/></svg>
	<span>Tanyakan slot</span>
</a>
	<?php
}
add_action( 'wp_footer', 'tjr_v5_fab' );
