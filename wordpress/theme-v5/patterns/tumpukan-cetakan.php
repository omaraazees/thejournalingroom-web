<?php
/**
 * Title: Tumpukan cetakan
 * Slug: tjr-v5/tumpukan-cetakan
 * Categories: tjr, tjr-beranda
 * Description: Sembilan cetakan polaroid yang membuka dari satu tumpukan waktu halaman digulir, memakai animation-timeline view(). Di layar sempit tumpukannya berubah jadi grid biasa.
 * Keywords: cetakan, polaroid, tumpukan, scroll, galeri
 * Viewport Width: 1400
 */

$tjr_bawaan = array(
	array( 'sundayreads-12', 'Sunday Reads', 'Dokumentasi sesi TJR bersama Sunday Reads' ),
	array( 'radian-30', 'Radian', 'Dokumentasi sesi TJR bersama Radian' ),
	array( 'wardah-04', 'Wardah', 'Dokumentasi sesi TJR bersama Wardah' ),
	array( 'artotel-19', 'Artotel', 'Dokumentasi sesi TJR bersama Artotel' ),
	array( 'kolondjono-20', 'Kolondjono', 'Dokumentasi sesi TJR bersama Kolondjono' ),
	array( 'amco-naoki-03', 'AMCO x Naoki', 'Dokumentasi sesi TJR bersama AMCO x Naoki' ),
	array( 'snapobox-08', 'Snapobox', 'Dokumentasi sesi TJR bersama Snapobox' ),
	array( 'pasar-jakal-02', 'Pasar Jakal', 'Dokumentasi sesi TJR bersama Pasar Jakal' ),
	array( 'kupiku-04', 'Kupiku', 'Dokumentasi sesi TJR bersama Kupiku' ),
);

// Tiap cetakan boleh diganti dari tab Cetakan di halaman depan. Yang dibiarkan
// kosong memakai foto bawaan di atas.
$tjr_cetakan = array();

foreach ( $tjr_bawaan as $tjr_i => $tjr_satu ) {
	$tjr_n = $tjr_i + 1;
	$tjr_cetakan[] = array(
		tjr_v5_foto( 'cetakan_' . $tjr_n, $tjr_satu[0] . '.jpg' ),
		tjr_v5_isi( 'cetakan_' . $tjr_n . '_nama', $tjr_satu[1] ),
		tjr_v5_foto_alt( 'cetakan_' . $tjr_n, $tjr_satu[2] ),
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
<figure class="wp-block-image size-full cetakan"><img src="<?php echo esc_url( $tjr_satu[0] ); ?>" alt="<?php echo esc_attr( $tjr_satu[2] ); ?>"/><figcaption class="wp-element-caption"><?php echo esc_html( $tjr_satu[1] ); ?></figcaption></figure>
<!-- /wp:image -->
<?php endforeach; ?>
</div>
<!-- /wp:group -->

</section>
<!-- /wp:group -->
