<?php
/**
 * Title: Jadwal, tiga sore terakhir
 * Slug: tjr-v5/jadwal-kartu
 * Categories: tjr, tjr-beranda
 * Description: Kepala seksi Recently plus tiga kartu potret untuk acara yang paling baru selesai. Kartunya menautkan ke arsip, bukan ke WhatsApp. Query Loop-nya sudah dikunci ke tiga acara dengan tanggal yang sudah lewat.
 * Keywords: jadwal, arsip, kartu, baru lewat, query loop
 * Viewport Width: 1400
 */

// Seksi ini tidak boleh dirender kalau belum ada acara yang lewat sama sekali.
// Tanpa penjagaan ini, judul "Sesi yang lalu" dan tombol "Semua arsip" tetap
// tampil di atas daftar kosong, dan itu persis yang terjadi di beranda setelah
// 15 acara lama dibuang pada 2026-09-07.
//
// Syaratnya sengaja dicerminkan dari tjr_v5_query_acara() di functions.php
// (namespace tjr/baru-lewat): lewat berarti TJR_FIELD_MULAI lebih kecil dari
// waktu sekarang. Kalau syarat di sana berubah, ubah juga di sini.
$tjr_v5_ada_yang_lewat = get_posts(
	array(
		'post_type'      => 'acara',
		'post_status'    => 'publish',
		'posts_per_page' => 1,
		'fields'         => 'ids',
		'no_found_rows'  => true,
		'meta_query'     => array(
			array(
				'key'     => TJR_FIELD_MULAI,
				'value'   => current_datetime()->format( 'Y-m-d H:i:s' ),
				'compare' => '<',
				'type'    => 'DATETIME',
			),
		),
	)
);

if ( empty( $tjr_v5_ada_yang_lewat ) ) {
	return;
}
?>
<!-- wp:group {"tagName":"section","className":"jarak-atas-besar","anchor":"baru-lewat","layout":{"type":"default"}} -->
<section class="wp-block-group jarak-atas-besar" id="baru-lewat">

<!-- wp:group {"className":"kepala","layout":{"type":"default"}} -->
<div class="wp-block-group kepala">
<!-- wp:group {"layout":{"type":"default"}} -->
<div class="wp-block-group">
<!-- wp:paragraph {"className":"lbl"} --><p class="lbl">Recently</p><!-- /wp:paragraph -->
<!-- wp:heading {"level":2,"className":"d d-lg","style":{"spacing":{"margin":{"top":"var:preset|spacing|jarak-2"}}}} --><h2 class="wp-block-heading d d-lg" style="margin-top:var(--wp--preset--spacing--jarak-2)">Sesi yang lalu</h2><!-- /wp:heading -->
</div>
<!-- /wp:group -->
<!-- wp:buttons -->
<div class="wp-block-buttons">
<!-- wp:button {"className":"is-style-pil"} --><div class="wp-block-button is-style-pil"><a class="wp-block-button__link wp-element-button" href="/jadwal/">Semua jadwal</a></div><!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:group -->

<!-- wp:query {"queryId":12,"query":{"perPage":3,"pages":1,"offset":0,"postType":"acara","order":"desc","orderBy":"date","search":"","exclude":[],"sticky":"","inherit":false},"namespace":"tjr/baru-lewat","className":"kartu-kartu-query"} -->
<div class="wp-block-query kartu-kartu-query">

<!-- wp:post-template {"className":"kartu-kartu"} -->

<!-- wp:group {"className":"kartu naik","layout":{"type":"default"}} -->
<div class="wp-block-group kartu naik">

<!-- wp:post-featured-image {"isLink":false,"className":"gbr-kartu"} /-->
<!-- wp:post-title {"level":3,"isLink":true,"className":"kartu-judul"} /-->

<!-- wp:group {"className":"kaki-kartu","layout":{"type":"default"}} -->
<div class="wp-block-group kaki-kartu">
<!-- wp:post-date {"format":"j F Y","className":"lbl ik-kalender","isLink":false} /-->
<!-- wp:post-excerpt {"excerptLength":16,"showMoreOnNewLine":false} /-->
<!-- wp:post-terms {"term":"kota","className":"mini ik-pin"} /-->
</div>
<!-- /wp:group -->

</div>
<!-- /wp:group -->

<!-- /wp:post-template -->

<!-- wp:query-no-results -->
<!-- wp:paragraph {"className":"lead"} --><p class="lead">Arsip acaranya belum terisi. Tambahkan acara lewat menu Acara di dasbor.</p><!-- /wp:paragraph -->
<!-- /wp:query-no-results -->

</div>
<!-- /wp:query -->

</section>
<!-- /wp:group -->
