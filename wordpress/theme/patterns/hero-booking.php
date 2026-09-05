<?php
/**
 * Title: Hero dengan kartu sesi terdekat
 * Slug: tjr/hero-booking
 * Categories: tjr
 * Description: Judul besar, angka kepercayaan, logo partner, dan kartu sesi terdekat yang ditempel selotip di sisi kanan.
 * Keywords: hero, beranda, sesi terdekat, booking
 * Viewport Width: 1400
 */
?>
<!-- wp:group {"tagName":"section","className":"hero","style":{"spacing":{"padding":{"top":"var:preset|spacing|7","bottom":"var:preset|spacing|6"}}},"layout":{"type":"constrained","contentSize":"1160px"}} -->
<section class="wp-block-group hero" style="padding-top:var(--wp--preset--spacing--7);padding-bottom:var(--wp--preset--spacing--6)">

<!-- wp:columns {"className":"hero-grid","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|6"}}}} -->
<div class="wp-block-columns hero-grid">

<!-- wp:column {"width":"66%"} -->
<div class="wp-block-column" style="flex-basis:66%">

<!-- wp:paragraph {"className":"script","fontFamily":"script","textColor":"rose-text"} -->
<p class="script has-rose-text-color has-text-color has-script-font-family">Your kind, journaling companions</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":1,"fontSize":"display"} -->
<h1 class="wp-block-heading has-display-font-size">Menulis lebih enak <em>bareng</em></h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"lead","fontSize":"md","textColor":"ink-soft"} -->
<p class="lead has-ink-soft-color has-text-color has-md-font-size">Sesi journaling bersama di Yogyakarta. Datang tanpa pengalaman, tanpa alat, tanpa harus ngomong. Semua yang kamu butuh sudah ada di meja.</p>
<!-- /wp:paragraph -->

<!-- wp:group {"className":"trust","style":{"spacing":{"margin":{"top":"var:preset|spacing|5"},"blockGap":"var:preset|spacing|5"}},"layout":{"type":"flex","flexWrap":"wrap"}} -->
<div class="wp-block-group trust" style="margin-top:var(--wp--preset--spacing--5)">
<!-- wp:group {"className":"trust-item","layout":{"type":"constrained"}} --><div class="wp-block-group trust-item"><!-- wp:paragraph {"className":"n"} --><p class="n">22</p><!-- /wp:paragraph --><!-- wp:paragraph {"className":"l"} --><p class="l">sesi berjalan</p><!-- /wp:paragraph --></div><!-- /wp:group -->
<!-- wp:group {"className":"trust-item","layout":{"type":"constrained"}} --><div class="wp-block-group trust-item"><!-- wp:paragraph {"className":"n"} --><p class="n">240</p><!-- /wp:paragraph --><!-- wp:paragraph {"className":"l"} --><p class="l">peserta</p><!-- /wp:paragraph --></div><!-- /wp:group -->
<!-- wp:group {"className":"trust-item","layout":{"type":"constrained"}} --><div class="wp-block-group trust-item"><!-- wp:paragraph {"className":"n"} --><p class="n">3</p><!-- /wp:paragraph --><!-- wp:paragraph {"className":"l"} --><p class="l">kota</p><!-- /wp:paragraph --></div><!-- /wp:group -->
<!-- wp:group {"className":"trust-item","layout":{"type":"constrained"}} --><div class="wp-block-group trust-item"><!-- wp:paragraph {"className":"n"} --><p class="n">9</p><!-- /wp:paragraph --><!-- wp:paragraph {"className":"l"} --><p class="l">brand partner</p><!-- /wp:paragraph --></div><!-- /wp:group -->
</div>
<!-- /wp:group -->

<!-- wp:group {"className":"logos","style":{"spacing":{"margin":{"top":"var:preset|spacing|5"}}},"layout":{"type":"flex","flexWrap":"wrap"}} -->
<div class="wp-block-group logos" style="margin-top:var(--wp--preset--spacing--5)">
<!-- wp:paragraph --><p>Wardah</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p>Artotel</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p>AMCO Bakehouse</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p>Kupiku Coffee</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p>Copenhagen</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p>Heejaz</p><!-- /wp:paragraph -->
</div>
<!-- /wp:group -->

</div>
<!-- /wp:column -->

<!-- wp:column {"width":"34%"} -->
<div class="wp-block-column" style="flex-basis:34%">

<!-- wp:group {"tagName":"aside","className":"book","layout":{"type":"constrained"}} -->
<aside class="wp-block-group book">

<!-- wp:paragraph {"className":"lbl","textColor":"burgundy"} -->
<p class="lbl has-burgundy-color has-text-color">Sesi terdekat</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2,"fontSize":"lg"} -->
<h2 class="wp-block-heading has-lg-font-size">Tracing Shadows, Mapping Stars</h2>
<!-- /wp:heading -->

<!-- wp:image {"className":"ph-slot"} -->
<figure class="wp-block-image ph-slot"><img src="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/assets/placeholder.svg" alt="Foto sesi terdekat"/></figure>
<!-- /wp:image -->

<!-- wp:group {"className":"kv","layout":{"type":"flex","justifyContent":"space-between"}} --><div class="wp-block-group kv"><!-- wp:paragraph --><p>Tanggal</p><!-- /wp:paragraph --><!-- wp:paragraph {"className":"v"} --><p class="v">Sabtu, 20 Sep</p><!-- /wp:paragraph --></div><!-- /wp:group -->
<!-- wp:group {"className":"kv","layout":{"type":"flex","justifyContent":"space-between"}} --><div class="wp-block-group kv"><!-- wp:paragraph --><p>Waktu</p><!-- /wp:paragraph --><!-- wp:paragraph {"className":"v"} --><p class="v">15.00 sampai 18.00</p><!-- /wp:paragraph --></div><!-- /wp:group -->
<!-- wp:group {"className":"kv","layout":{"type":"flex","justifyContent":"space-between"}} --><div class="wp-block-group kv"><!-- wp:paragraph --><p>Lokasi</p><!-- /wp:paragraph --><!-- wp:paragraph {"className":"v"} --><p class="v">Kupiku Coffee</p><!-- /wp:paragraph --></div><!-- /wp:group -->
<!-- wp:group {"className":"kv","layout":{"type":"flex","justifyContent":"space-between"}} --><div class="wp-block-group kv"><!-- wp:paragraph --><p>Harga</p><!-- /wp:paragraph --><!-- wp:paragraph {"className":"v price"} --><p class="v price">75K</p><!-- /wp:paragraph --></div><!-- /wp:group -->

<!-- wp:html -->
<div class="slotbar" role="img" aria-label="11 dari 15 slot terisi"><i style="width:73%"></i></div>
<!-- /wp:html -->

<!-- wp:paragraph {"className":"note"} -->
<p class="note">11 dari 15 slot sudah terisi</p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"className":"is-full"} -->
<div class="wp-block-buttons is-full"><!-- wp:button {"backgroundColor":"burgundy","textColor":"paper","width":100} --><div class="wp-block-button has-custom-width wp-block-button__width-100"><a class="wp-block-button__link has-paper-color has-burgundy-background-color has-text-color has-background wp-element-button" href="#">Ambil slot lewat WhatsApp</a></div><!-- /wp:button --></div>
<!-- /wp:buttons -->

<!-- wp:paragraph {"className":"note","align":"center"} -->
<p class="note has-text-align-center">Dibalas 09.00 sampai 21.00</p>
<!-- /wp:paragraph -->

</aside>
<!-- /wp:group -->

</div>
<!-- /wp:column -->

</div>
<!-- /wp:columns -->

</section>
<!-- /wp:group -->
