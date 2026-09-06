<?php
/**
 * Title: Jadwal, tiga sore terakhir
 * Slug: tjr-v5/jadwal-kartu
 * Categories: tjr, tjr-beranda
 * Description: Kepala seksi Baru saja lewat plus tiga kartu potret untuk acara yang paling baru selesai. Kartunya menautkan ke arsip, bukan ke WhatsApp. Query Loop-nya sudah dikunci ke tiga acara dengan tanggal yang sudah lewat.
 * Keywords: jadwal, arsip, kartu, baru lewat, query loop
 * Viewport Width: 1400
 */

// Query Loop di bawah dibatasi tiga acara yang tanggalnya sudah lewat. Judulnya
// menyebut angka, jadi angkanya ikut berapa yang benar benar tampil.
$tjr_lewat = min( 3, tjr_v5_jumlah_acara( 'lewat' ) );
$tjr_judul = ( 1 === $tjr_lewat )
	? 'Sore terakhir'
	: tjr_v5_angka_kata( $tjr_lewat ) . ' sore terakhir';
?>
<!-- wp:group {"tagName":"section","className":"jarak-atas-besar","anchor":"baru-lewat","layout":{"type":"default"}} -->
<section class="wp-block-group jarak-atas-besar" id="baru-lewat">

<!-- wp:group {"className":"kepala","layout":{"type":"default"}} -->
<div class="wp-block-group kepala">
<!-- wp:group {"layout":{"type":"default"}} -->
<div class="wp-block-group">
<!-- wp:paragraph {"className":"lbl"} --><p class="lbl">Baru saja lewat</p><!-- /wp:paragraph -->
<!-- wp:heading {"level":2,"className":"d d-lg","style":{"spacing":{"margin":{"top":"var:preset|spacing|jarak-2"}}}} --><h2 class="wp-block-heading d d-lg" style="margin-top:var(--wp--preset--spacing--jarak-2)"><?php echo esc_html( $tjr_judul ); ?></h2><!-- /wp:heading -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->

<!-- wp:query {"queryId":12,"query":{"perPage":3,"pages":1,"offset":0,"postType":"acara","order":"desc","orderBy":"date","search":"","exclude":[],"sticky":"","inherit":false},"namespace":"tjr/baru-lewat","className":"kartu-kartu-query"} -->
<div class="wp-block-query kartu-kartu-query">

<!-- wp:post-template {"className":"kartu-kartu"} -->

<!-- wp:group {"className":"kartu naik","layout":{"type":"default"}} -->
<div class="wp-block-group kartu naik">

<!-- wp:post-featured-image {"isLink":false} /-->
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
