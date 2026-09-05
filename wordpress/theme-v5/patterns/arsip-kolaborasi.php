<?php
/**
 * Title: Arsip kolaborasi
 * Slug: tjr-v5/arsip-kolaborasi
 * Categories: tjr, tjr-beranda
 * Description: Daftar baris acara yang sudah lewat, diurut dari yang paling baru. Tiap baris berisi tanggal, nama kolaborasi, kota, dan tautan lihat foto yang bergeser waktu kursor lewat.
 * Keywords: arsip, kolaborasi, daftar, riwayat, query loop
 * Viewport Width: 1400
 */
?>
<!-- wp:group {"tagName":"section","className":"seksi","anchor":"arsip","layout":{"type":"default"}} -->
<section class="wp-block-group seksi" id="arsip">

<!-- wp:group {"className":"kepala","layout":{"type":"default"}} -->
<div class="wp-block-group kepala">
<!-- wp:group {"layout":{"type":"default"}} -->
<div class="wp-block-group">
<!-- wp:paragraph {"className":"lbl"} --><p class="lbl">Sudah lewat</p><!-- /wp:paragraph -->
<!-- wp:heading {"level":2,"className":"d d-xl","style":{"spacing":{"margin":{"top":"var:preset|spacing|jarak-2"}}}} --><h2 class="wp-block-heading d d-xl" style="margin-top:var(--wp--preset--spacing--jarak-2)">Sepuluh kali duduk bersama</h2><!-- /wp:heading -->
</div>
<!-- /wp:group -->
<!-- wp:paragraph {"className":"lead lead-sempit"} --><p class="lead lead-sempit">Dari November 2025 sampai hari ini. Tempatnya berpindah, orangnya berganti, mejanya tetap satu.</p><!-- /wp:paragraph -->
</div>
<!-- /wp:group -->

<!-- wp:query {"queryId":13,"query":{"perPage":10,"pages":1,"offset":0,"postType":"acara","order":"desc","orderBy":"date","search":"","exclude":[],"sticky":"","inherit":false},"className":"arsip"} -->
<div class="wp-block-query arsip">

<!-- wp:post-template {"className":"arsip-daftar"} -->

<!-- wp:group {"className":"arsip-baris","layout":{"type":"default"}} -->
<div class="wp-block-group arsip-baris">
<!-- wp:post-date {"format":"j M Y","className":"arsip-tahun","isLink":false} /-->
<!-- wp:post-title {"level":3,"isLink":true,"className":"arsip-nama"} /-->
<!-- wp:post-terms {"term":"kota","className":"arsip-jumlah ik-foto"} /-->
<!-- wp:paragraph {"className":"arsip-buka"} --><p class="arsip-buka">Lihat dokumentasi</p><!-- /wp:paragraph -->
</div>
<!-- /wp:group -->

<!-- /wp:post-template -->

<!-- wp:query-no-results -->
<!-- wp:paragraph {"className":"lead"} --><p class="lead">Arsipnya masih kosong. Setiap acara yang tanggalnya sudah lewat otomatis pindah ke sini.</p><!-- /wp:paragraph -->
<!-- /wp:query-no-results -->

</div>
<!-- /wp:query -->

</section>
<!-- /wp:group -->
