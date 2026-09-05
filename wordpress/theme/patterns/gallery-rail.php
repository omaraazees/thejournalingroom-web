<?php
/**
 * Title: Galeri geser samping
 * Slug: tjr/gallery-rail
 * Categories: tjr
 * Description: Deretan foto sesi yang digeser ke samping dengan snap, tiap foto sedikit miring.
 * Keywords: galeri, foto, rail, dokumentasi
 * Viewport Width: 1400
 */
?>
<!-- wp:group {"tagName":"section","align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|7","bottom":"var:preset|spacing|7"}}},"layout":{"type":"constrained","contentSize":"1160px"}} -->
<section class="wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--7);padding-bottom:var(--wp--preset--spacing--7)">

<!-- wp:group {"className":"head rise","layout":{"type":"flex","justifyContent":"space-between","flexWrap":"wrap"}} -->
<div class="wp-block-group head rise">
<!-- wp:group {"layout":{"type":"constrained"}} --><div class="wp-block-group"><!-- wp:paragraph {"className":"eyebrow","textColor":"ink-soft","fontSize":"xs"} --><p class="eyebrow has-ink-soft-color has-text-color has-xs-font-size">Dari sesi sebelumnya</p><!-- /wp:paragraph --><!-- wp:heading {"level":2,"fontSize":"xxl"} --><h2 class="wp-block-heading has-xxl-font-size">Yang terjadi di meja</h2><!-- /wp:heading --></div><!-- /wp:group -->
<!-- wp:buttons --><div class="wp-block-buttons"><!-- wp:button {"className":"is-style-outline btn-ghost"} --><div class="wp-block-button is-style-outline btn-ghost"><a class="wp-block-button__link wp-element-button" href="#">Buka galeri</a></div><!-- /wp:button --></div><!-- /wp:buttons -->
</div>
<!-- /wp:group -->

<!-- wp:group {"className":"rail","layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group rail">
<!-- wp:image {"className":"wipe"} --><figure class="wp-block-image wipe"><img src="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/assets/placeholder.svg" alt="Tangan sedang menulis di halaman jurnal"/><figcaption class="wp-element-caption">About Myself, Agustus</figcaption></figure><!-- /wp:image -->
<!-- wp:image {"className":"wipe"} --><figure class="wp-block-image wipe"><img src="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/assets/placeholder.svg" alt="Meja deco station berisi washi tape dan stempel"/><figcaption class="wp-element-caption">Deco station</figcaption></figure><!-- /wp:image -->
<!-- wp:image {"className":"wipe"} --><figure class="wp-block-image wipe"><img src="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/assets/placeholder.svg" alt="Halaman jurnal peserta yang sudah dihias"/><figcaption class="wp-element-caption">Halaman peserta</figcaption></figure><!-- /wp:image -->
<!-- wp:image {"className":"wipe"} --><figure class="wp-block-image wipe"><img src="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/assets/placeholder.svg" alt="Peserta duduk bersama sambil menulis"/><figcaption class="wp-element-caption">Between the Pages, Juli</figcaption></figure><!-- /wp:image -->
<!-- wp:image {"className":"wipe"} --><figure class="wp-block-image wipe"><img src="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/assets/placeholder.svg" alt="Isi kit workshop yang ditata di atas meja"/><figcaption class="wp-element-caption">Isi kit</figcaption></figure><!-- /wp:image -->
</div>
<!-- /wp:group -->

<!-- wp:paragraph {"className":"muted","textColor":"ink-soft","fontSize":"xs"} -->
<p class="muted has-ink-soft-color has-text-color has-xs-font-size">Geser ke samping</p>
<!-- /wp:paragraph -->

</section>
<!-- /wp:group -->
