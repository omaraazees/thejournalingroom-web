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
$tjr_cetakan = array();

for ( $tjr_n = 1; $tjr_n <= 9; $tjr_n++ ) {
	$tjr_cetakan[] = array(
		tjr_v5_foto( 'cetakan_' . $tjr_n ),
		tjr_v5_isi( 'cetakan_' . $tjr_n . '_nama' ),
		tjr_v5_foto_alt( 'cetakan_' . $tjr_n ),
	);
}
?>
<!-- wp:group {"tagName":"section","className":"seksi","anchor":"cetakan","layout":{"type":"default"}} -->
<section class="wp-block-group seksi" id="cetakan">

<!-- wp:group {"className":"kepala kepala-tengah","layout":{"type":"default"}} -->
<div class="wp-block-group kepala kepala-tengah">
<!-- wp:paragraph {"className":"lbl"} --><p class="lbl">Cetakan</p><!-- /wp:paragraph -->
<!-- wp:heading {"level":2,"className":"d d-xl"} --><h2 class="wp-block-heading d d-xl">Sore yang tersimpan</h2><!-- /wp:heading -->
<!-- wp:paragraph {"className":"lead"} --><p class="lead">Setiap kelas meninggalkan setumpuk cetakan di atas meja. Ini sebagiannya, dari sembilan kolaborasi yang berbeda.</p><!-- /wp:paragraph -->
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
