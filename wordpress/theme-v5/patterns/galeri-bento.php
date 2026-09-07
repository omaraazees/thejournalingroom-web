<?php
/**
 * Title: Galeri bento
 * Slug: tjr-v5/galeri-bento
 * Categories: tjr, tjr-beranda
 * Description: Empat foto dokumentasi dalam petak bento dengan tinggi baris terkunci, masing masing bertanda strip judul berwarna di sudut kiri bawah.
 * Keywords: galeri, dokumentasi, bento, foto
 * Viewport Width: 1400
 */

// Empat petak, isinya dari tab Galeri di halaman depan. Foto dan tulisan
// bawaannya ada di tjr_v5_bawaan_foto() dan tjr_v5_bawaan_teks().
$tjr_foto = array();
$tjr_alt  = array();
$tjr_teks = array();

// Kartu G-4: alih ke .webp (kalau sibling-nya ada) dikerjakan tjr_v5_gambar_tag()
// lewat <picture>, bukan menukar src di sini. $tjr_foto tetap URL asli (jpg),
// jadi tetap tersedia sebagai fallback kalau <source webp>-nya sendiri gagal.
for ( $tjr_n = 1; $tjr_n <= 4; $tjr_n++ ) {
	$tjr_foto[ $tjr_n ] = tjr_v5_foto( 'bento_' . $tjr_n );
	$tjr_alt[ $tjr_n ]  = tjr_v5_foto_alt( 'bento_' . $tjr_n );
	$tjr_teks[ $tjr_n ] = tjr_v5_isi( 'bento_' . $tjr_n . '_teks' );
}

$tjr_lead = 'Dokumentasi dari beberapa workshop yang sudah kami gelar di Yogyakarta.';
?>
<!-- wp:group {"tagName":"section","className":"seksi","anchor":"galeri","layout":{"type":"default"}} -->
<section class="wp-block-group seksi" id="galeri">

<!-- wp:group {"className":"kepala","layout":{"type":"default"}} -->
<div class="wp-block-group kepala">
<!-- wp:group {"layout":{"type":"default"}} -->
<div class="wp-block-group">
<!-- wp:paragraph {"className":"lbl"} --><p class="lbl">Dokumentasi</p><!-- /wp:paragraph -->
<!-- wp:heading {"level":2,"className":"d d-xl","style":{"spacing":{"margin":{"top":"var:preset|spacing|jarak-2"}}}} --><h2 class="wp-block-heading d d-xl" style="margin-top:var(--wp--preset--spacing--jarak-2)">Merangkai memori bersama</h2><!-- /wp:heading -->
<!-- wp:paragraph {"className":"lead","style":{"spacing":{"margin":{"top":"var:preset|spacing|jarak-2"}}}} --><p class="lead" style="margin-top:var(--wp--preset--spacing--jarak-2)"><?php echo esc_html( $tjr_lead ); ?></p><!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:buttons -->
<div class="wp-block-buttons">
<!-- wp:button {"className":"is-style-pil"} --><div class="wp-block-button is-style-pil"><a class="wp-block-button__link wp-element-button" href="/jadwal/">Semua kolaborasi</a></div><!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:group -->

<!-- wp:group {"className":"bento naik","layout":{"type":"default"}} -->
<div class="wp-block-group bento naik">

<!-- wp:image {"className":"t1 is-style-strip-zaitun","sizeSlug":"full","linkDestination":"none"} -->
<figure class="wp-block-image size-full t1 is-style-strip-zaitun"><?php echo tjr_v5_gambar_tag( $tjr_foto[1], $tjr_alt[1] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><figcaption class="wp-element-caption"><?php echo esc_html( $tjr_teks[1] ); ?></figcaption></figure>
<!-- /wp:image -->

<!-- wp:image {"className":"t2 is-style-strip-kraft","sizeSlug":"full","linkDestination":"none"} -->
<figure class="wp-block-image size-full t2 is-style-strip-kraft"><?php echo tjr_v5_gambar_tag( $tjr_foto[2], $tjr_alt[2] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><figcaption class="wp-element-caption"><?php echo esc_html( $tjr_teks[2] ); ?></figcaption></figure>
<!-- /wp:image -->

<!-- wp:image {"className":"t3 is-style-strip-rose","sizeSlug":"full","linkDestination":"none"} -->
<figure class="wp-block-image size-full t3 is-style-strip-rose"><?php echo tjr_v5_gambar_tag( $tjr_foto[3], $tjr_alt[3] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><figcaption class="wp-element-caption"><?php echo esc_html( $tjr_teks[3] ); ?></figcaption></figure>
<!-- /wp:image -->

<!-- wp:image {"className":"t4 is-style-strip-burgundy","sizeSlug":"full","linkDestination":"none"} -->
<figure class="wp-block-image size-full t4 is-style-strip-burgundy"><?php echo tjr_v5_gambar_tag( $tjr_foto[4], $tjr_alt[4] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><figcaption class="wp-element-caption"><?php echo esc_html( $tjr_teks[4] ); ?></figcaption></figure>
<!-- /wp:image -->

</div>
<!-- /wp:group -->

</section>
<!-- /wp:group -->
