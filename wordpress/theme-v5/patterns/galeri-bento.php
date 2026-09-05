<?php
/**
 * Title: Galeri bento
 * Slug: tjr-v5/galeri-bento
 * Categories: tjr, tjr-beranda
 * Description: Empat foto dokumentasi dalam petak bento dengan tinggi baris terkunci, masing masing bertanda strip judul berwarna di sudut kiri bawah.
 * Keywords: galeri, dokumentasi, bento, foto
 * Viewport Width: 1400
 */

$tjr_bento = array(
	1 => array( 'radian-11.jpg', 'Radian', 'Sesi journaling di pendopo bersama Radian' ),
	2 => array( 'sundayreads-08.jpg', 'Sunday Reads Club', 'Bahan journaling ditata dari atas meja' ),
	3 => array( 'wardah-09.jpg', 'Wardah', 'Kit alat tulis Wardah di atas meja' ),
	4 => array( 'pasar-jakal-06.jpg', 'Pasar Jakal', 'Tangan peserta menempel bahan di halaman jurnal' ),
);

$tjr_foto = array();
$tjr_alt  = array();
$tjr_teks = array();

foreach ( $tjr_bento as $tjr_n => $tjr_bawaan ) {
	$tjr_foto[ $tjr_n ] = tjr_v5_foto( 'bento_' . $tjr_n, $tjr_bawaan[0] );
	$tjr_alt[ $tjr_n ]  = tjr_v5_foto_alt( 'bento_' . $tjr_n, $tjr_bawaan[2] );
	$tjr_teks[ $tjr_n ] = tjr_v5_isi( 'bento_' . $tjr_n . '_teks', $tjr_bawaan[1] );
}

// Kalimat pengantar menyebut jumlah, jadi angkanya ikut isi CMS.
$tjr_jml = tjr_v5_jumlah_acara( 'lewat' );
$tjr_lead = ( 0 === $tjr_jml )
	? 'Dokumentasi dari workshop journaling yang sudah kami gelar di Yogyakarta.'
	: 'Dokumentasi dari ' . strtolower( tjr_v5_angka_kata( $tjr_jml ) ) . ' workshop journaling yang sudah kami gelar di Yogyakarta.';
?>
<!-- wp:group {"tagName":"section","className":"seksi","anchor":"galeri","layout":{"type":"default"}} -->
<section class="wp-block-group seksi" id="galeri">

<!-- wp:group {"className":"kepala","layout":{"type":"default"}} -->
<div class="wp-block-group kepala">
<!-- wp:group {"layout":{"type":"default"}} -->
<div class="wp-block-group">
<!-- wp:paragraph {"className":"lbl"} --><p class="lbl">Dokumentasi</p><!-- /wp:paragraph -->
<!-- wp:heading {"level":2,"className":"d d-xl","style":{"spacing":{"margin":{"top":"var:preset|spacing|jarak-2"}}}} --><h2 class="wp-block-heading d d-xl" style="margin-top:var(--wp--preset--spacing--jarak-2)">Apa yang tertinggal di meja</h2><!-- /wp:heading -->
<!-- wp:paragraph {"className":"lead","style":{"spacing":{"margin":{"top":"var:preset|spacing|jarak-2"}}}} --><p class="lead" style="margin-top:var(--wp--preset--spacing--jarak-2)"><?php echo esc_html( $tjr_lead ); ?></p><!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:buttons -->
<div class="wp-block-buttons">
<!-- wp:button {"className":"is-style-pil"} --><div class="wp-block-button is-style-pil"><a class="wp-block-button__link wp-element-button" href="#arsip">Semua kolaborasi</a></div><!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:group -->

<!-- wp:group {"className":"bento naik","layout":{"type":"default"}} -->
<div class="wp-block-group bento naik">

<!-- wp:image {"className":"t1 is-style-strip-zaitun","sizeSlug":"full","linkDestination":"none"} -->
<figure class="wp-block-image size-full t1 is-style-strip-zaitun"><img src="<?php echo esc_url( $tjr_foto[1] ); ?>" alt="<?php echo esc_attr( $tjr_alt[1] ); ?>"/><figcaption class="wp-element-caption"><?php echo esc_html( $tjr_teks[1] ); ?></figcaption></figure>
<!-- /wp:image -->

<!-- wp:image {"className":"t2 is-style-strip-kraft","sizeSlug":"full","linkDestination":"none"} -->
<figure class="wp-block-image size-full t2 is-style-strip-kraft"><img src="<?php echo esc_url( $tjr_foto[2] ); ?>" alt="<?php echo esc_attr( $tjr_alt[2] ); ?>"/><figcaption class="wp-element-caption"><?php echo esc_html( $tjr_teks[2] ); ?></figcaption></figure>
<!-- /wp:image -->

<!-- wp:image {"className":"t3 is-style-strip-rose","sizeSlug":"full","linkDestination":"none"} -->
<figure class="wp-block-image size-full t3 is-style-strip-rose"><img src="<?php echo esc_url( $tjr_foto[3] ); ?>" alt="<?php echo esc_attr( $tjr_alt[3] ); ?>"/><figcaption class="wp-element-caption"><?php echo esc_html( $tjr_teks[3] ); ?></figcaption></figure>
<!-- /wp:image -->

<!-- wp:image {"className":"t4 is-style-strip-burgundy","sizeSlug":"full","linkDestination":"none"} -->
<figure class="wp-block-image size-full t4 is-style-strip-burgundy"><img src="<?php echo esc_url( $tjr_foto[4] ); ?>" alt="<?php echo esc_attr( $tjr_alt[4] ); ?>"/><figcaption class="wp-element-caption"><?php echo esc_html( $tjr_teks[4] ); ?></figcaption></figure>
<!-- /wp:image -->

</div>
<!-- /wp:group -->

</section>
<!-- /wp:group -->
