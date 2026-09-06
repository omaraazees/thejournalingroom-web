<?php
/**
 * Title: Tumpukan cetakan
 * Slug: tjr-v5/tumpukan-cetakan
 * Categories: tjr, tjr-beranda
 * Description: Sembilan cetakan polaroid yang membuka dari satu tumpukan waktu halaman digulir, memakai animation-timeline view(). Di layar sempit tumpukannya berubah jadi grid biasa.
 * Keywords: cetakan, polaroid, tumpukan, scroll, galeri
 * Viewport Width: 1400
 */

// Sembilan cetakan, isinya dari tab Cetakan di halaman depan. Foto dan nama
// bawaannya ada di tjr_v5_bawaan_foto() dan tjr_v5_bawaan_teks().

// Label yang dibaca pengunjung sekarang "Memori", tapi id seksinya tetap
// #cetakan dan nama fieldnya tetap cetakan_1 dan seterusnya. Itu DISENGAJA:
// id dipakai enam tautan nav dan kaki, dan mengubahnya memutus tautan yang
// sudah tayang. Jangan diseragamkan.
$tjr_cetakan = array();

// Kartu P-3: sama seperti galeri bento, cuma alihkan ke .webp kalau
// padanannya memang ada di assets/img/; foto ACF di luar situ tidak disentuh.
$tjr_ke_webp = static function ( $url ) {
	$webp = preg_replace( '/\.jpe?g$/i', '.webp', (string) $url );
	if ( $webp === $url ) {
		return $url;
	}
	$jalur = get_theme_file_path( '/assets/img/' . basename( (string) wp_parse_url( $webp, PHP_URL_PATH ) ) );
	return file_exists( $jalur ) ? $webp : $url;
};

for ( $tjr_n = 1; $tjr_n <= 9; $tjr_n++ ) {
	$tjr_cetakan[] = array(
		$tjr_ke_webp( tjr_v5_foto( 'cetakan_' . $tjr_n ) ),
		tjr_v5_isi( 'cetakan_' . $tjr_n . '_nama' ),
		tjr_v5_foto_alt( 'cetakan_' . $tjr_n ),
	);
}
?>
<!-- wp:group {"tagName":"section","className":"seksi","anchor":"cetakan","layout":{"type":"default"}} -->
<section class="wp-block-group seksi" id="cetakan">

<!-- wp:group {"className":"kepala kepala-tengah","layout":{"type":"default"}} -->
<div class="wp-block-group kepala kepala-tengah">
<!-- wp:paragraph {"className":"lbl"} --><p class="lbl">Memori</p><!-- /wp:paragraph -->
<!-- wp:heading {"level":2,"className":"d d-xl"} --><h2 class="wp-block-heading d d-xl">Kenangan yang tersimpan</h2><!-- /wp:heading -->
<!-- wp:paragraph {"className":"lead"} --><p class="lead">Setiap pertemuan meninggalkan setumpuk memori diatas meja. Memori itu kami kenang lengkap dengan rasa syukur dan bahagianya.</p><!-- /wp:paragraph -->
</div>
<!-- /wp:group -->

<!-- wp:group {"className":"tumpukan","layout":{"type":"default"}} -->
<div class="wp-block-group tumpukan">
<?php foreach ( $tjr_cetakan as $tjr_satu ) : ?>
<!-- wp:image {"className":"cetakan","sizeSlug":"full","linkDestination":"none"} -->
<figure class="wp-block-image size-full cetakan"><img src="<?php echo esc_url( $tjr_satu[0] ); ?>" alt="<?php echo esc_attr( $tjr_satu[2] ); ?>"<?php echo tjr_v5_sifat_gambar( $tjr_satu[0] ); ?>/><figcaption class="wp-element-caption"><?php echo esc_html( $tjr_satu[1] ); ?></figcaption></figure>
<!-- /wp:image -->
<?php endforeach; ?>
</div>
<!-- /wp:group -->

</section>
<!-- /wp:group -->
