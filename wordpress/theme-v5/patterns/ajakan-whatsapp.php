<?php
/**
 * Title: Ajakan WhatsApp
 * Slug: tjr-v5/ajakan-whatsapp
 * Categories: tjr, tjr-beranda
 * Description: Blok penutup rose dengan lengkung besar di belakang teks, foto bersama di kanan, dan dua cetakan polaroid yang menumpuk di batas keduanya.
 * Keywords: cta, ajakan, penutup, whatsapp
 * Viewport Width: 1400
 */

$tjr_wa = esc_url( tjr_v5_link_wa() );
?>
<!-- wp:group {"tagName":"section","className":"ajakan naik","layout":{"type":"default"}} -->
<section class="wp-block-group ajakan naik">

<!-- wp:group {"className":"ajakan-kiri","layout":{"type":"default"}} -->
<div class="wp-block-group ajakan-kiri">
<!-- wp:paragraph {"className":"lbl"} --><p class="lbl">Sampai bertemu di ruangan</p><!-- /wp:paragraph -->
<!-- wp:heading {"level":2,"className":"d d-xl"} --><h2 class="wp-block-heading d d-xl">Bawa dirimu saja</h2><!-- /wp:heading -->
<!-- wp:paragraph {"className":"lead"} --><p class="lead">Jurnal, alat tulis, dan bahan tempel sudah menunggu di meja. Kalau ingin membawa jurnal sendiri, silakan. Tanyakan slotnya ke kami kapan saja.</p><!-- /wp:paragraph -->
<!-- wp:buttons -->
<div class="wp-block-buttons">
<!-- wp:button {"className":"is-style-pil-isi"} --><div class="wp-block-button is-style-pil-isi"><a class="wp-block-button__link wp-element-button" href="<?php echo $tjr_wa; ?>" target="_blank" rel="noopener">Tanyakan slotnya ke kami</a></div><!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:group -->

<!-- wp:image {"className":"ajakan-kanan","sizeSlug":"full","linkDestination":"none"} -->
<figure class="wp-block-image size-full ajakan-kanan"><img src="<?php echo esc_url( get_theme_file_uri( '/assets/img/artotel-13.webp' ) ); ?>" alt="Foto bersama peserta workshop TJR di Artotel"<?php echo tjr_v5_sifat_gambar( get_theme_file_uri( '/assets/img/artotel-13.webp' ) ); ?>/></figure>
<!-- /wp:image -->

<!-- wp:image {"className":"cetakan ajakan-1","sizeSlug":"full","linkDestination":"none"} -->
<figure class="wp-block-image size-full cetakan ajakan-1"><img src="<?php echo esc_url( get_theme_file_uri( '/assets/img/sundayreads-27.webp' ) ); ?>" alt="Jurnal terbuka di atas meja dikelilingi washi tape dan stiker dekorasi"<?php echo tjr_v5_sifat_gambar( get_theme_file_uri( '/assets/img/sundayreads-27.webp' ) ); ?>/><figcaption class="wp-element-caption">Sunday Reads</figcaption></figure>
<!-- /wp:image -->

<!-- wp:image {"className":"cetakan ajakan-2","sizeSlug":"full","linkDestination":"none"} -->
<figure class="wp-block-image size-full cetakan ajakan-2"><img src="<?php echo esc_url( get_theme_file_uri( '/assets/img/radian-24.webp' ) ); ?>" alt="Peserta menulis di jurnal bersampul bunga merah"<?php echo tjr_v5_sifat_gambar( get_theme_file_uri( '/assets/img/radian-24.webp' ) ); ?>/><figcaption class="wp-element-caption">Radian</figcaption></figure>
<!-- /wp:image -->

</section>
<!-- /wp:group -->
