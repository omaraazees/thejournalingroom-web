<?php
/**
 * Title: Jadwal, daftar acara
 * Slug: tjr/jadwal
 * Categories: tjr
 * Description: Query Loop ke tipe konten Acara, diurut dari tanggal terdekat, plus tautan saring per format. Di beranda final section ini ditandai "query loop", bukan "pattern", jadi pattern ini isinya memang satu Query Loop siap pakai.
 * Keywords: jadwal, acara, query loop, daftar
 * Viewport Width: 1400
 */
?>
<!-- wp:group {"tagName":"section","style":{"spacing":{"padding":{"top":"var:preset|spacing|7","bottom":"var:preset|spacing|7"}}},"layout":{"type":"constrained","contentSize":"1160px"}} -->
<section class="wp-block-group" style="padding-top:var(--wp--preset--spacing--7);padding-bottom:var(--wp--preset--spacing--7)">

<!-- wp:group {"className":"head rise","layout":{"type":"flex","justifyContent":"space-between","flexWrap":"wrap"}} -->
<div class="wp-block-group head rise">
<!-- wp:group {"layout":{"type":"constrained"}} --><div class="wp-block-group"><!-- wp:paragraph {"className":"eyebrow","textColor":"ink-soft","fontSize":"xs"} --><p class="eyebrow has-ink-soft-color has-text-color has-xs-font-size">Jadwal</p><!-- /wp:paragraph --><!-- wp:heading {"level":2,"fontSize":"xxl"} --><h2 class="wp-block-heading has-xxl-font-size">Pilih yang paling pas</h2><!-- /wp:heading --></div><!-- /wp:group -->
<!-- wp:buttons --><div class="wp-block-buttons"><!-- wp:button {"className":"is-style-outline btn-ghost"} --><div class="wp-block-button is-style-outline btn-ghost"><a class="wp-block-button__link wp-element-button" href="/jadwal/">Arsip semua sesi</a></div><!-- /wp:button --></div><!-- /wp:buttons -->
</div>
<!-- /wp:group -->

<!-- wp:group {"className":"chips","layout":{"type":"flex","flexWrap":"wrap"}} -->
<div class="wp-block-group chips">
<!-- wp:paragraph {"className":"chip on"} --><p class="chip on"><a href="/jadwal/">Semua</a></p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"chip"} --><p class="chip"><a href="/format/journaling-workshop/">Workshop</a></p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"chip"} --><p class="chip"><a href="/format/brush-lettering-class/">Lettering</a></p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"chip"} --><p class="chip"><a href="/format/journaling-playdate/">Playdate</a></p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"chip"} --><p class="chip"><a href="/format/sunday-reads-club/">Kolaborasi</a></p><!-- /wp:paragraph -->
</div>
<!-- /wp:group -->

<!-- wp:query {"queryId":0,"query":{"perPage":5,"pages":0,"offset":0,"postType":"acara","order":"asc","orderBy":"date","search":"","exclude":[],"sticky":"","inherit":false},"className":"rows"} -->
<div class="wp-block-query rows">
<!-- wp:post-template {"className":"rows-list"} -->

<!-- wp:group {"className":"ev","layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between"}} -->
<div class="wp-block-group ev">

<!-- wp:group {"className":"cal","layout":{"type":"constrained"}} -->
<div class="wp-block-group cal">
<!-- wp:post-date {"format":"M","className":"m","isLink":false} /-->
<!-- wp:post-date {"format":"d","className":"dd","isLink":false} /-->
</div>
<!-- /wp:group -->

<!-- wp:group {"className":"ev-main","layout":{"type":"constrained"}} -->
<div class="wp-block-group ev-main">
<!-- wp:post-title {"level":3,"isLink":true,"fontSize":"md"} /-->
<!-- wp:post-excerpt {"className":"v","excerptLength":12,"showMoreOnNewLine":false} /-->
</div>
<!-- /wp:group -->

<!-- wp:group {"className":"ev-meta","layout":{"type":"constrained"}} -->
<div class="wp-block-group ev-meta">
<!-- wp:post-terms {"term":"kota","className":"v"} /-->
<!-- wp:post-terms {"term":"format-acara","className":"v tag"} /-->
</div>
<!-- /wp:group -->

</div>
<!-- /wp:group -->

<!-- /wp:post-template -->

<!-- wp:query-no-results -->
<!-- wp:paragraph {"textColor":"ink-soft"} --><p class="has-ink-soft-color has-text-color">Belum ada sesi yang dijadwalkan. Tanya lewat WhatsApp, biasanya jadwal berikutnya sudah disiapkan.</p><!-- /wp:paragraph -->
<!-- /wp:query-no-results -->

</div>
<!-- /wp:query -->

<!-- wp:paragraph {"className":"muted","textColor":"ink-soft","fontSize":"xs"} -->
<p class="muted has-ink-soft-color has-text-color has-xs-font-size">Harga dan sisa slot ditarik dari field ACF. Sampai lane CMS selesai, dua kolom itu belum muncul di sini.</p>
<!-- /wp:paragraph -->

</section>
<!-- /wp:group -->
