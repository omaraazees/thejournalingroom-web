<?php
/**
 * Title: Hero panggung
 * Slug: tjr-v5/hero-panggung
 * Categories: tjr, tjr-beranda
 * Description: Judul besar rata kanan, dua pil aksi, foto panggung lebar dengan teks di kiri, dan kartu sesi terdekat bertempel selotip washi di sudut kanan bawah foto.
 * Keywords: hero, beranda, panggung, sesi terdekat
 * Viewport Width: 1400
 */

$tjr_wa_slot = esc_url( tjr_v5_link_wa_slot() );
?>
<!-- wp:group {"tagName":"section","className":"hero","layout":{"type":"default"}} -->
<section class="wp-block-group hero">

<!-- wp:group {"className":"hero-atas","layout":{"type":"default"}} -->
<div class="wp-block-group hero-atas">

<!-- wp:buttons {"className":"hero-aksi"} -->
<div class="wp-block-buttons hero-aksi">
<!-- wp:button {"className":"is-style-pil"} --><div class="wp-block-button is-style-pil"><a class="wp-block-button__link wp-element-button" href="#jadwal">Jadwal terdekat</a></div><!-- /wp:button -->
<!-- wp:button {"className":"is-style-pil"} --><div class="wp-block-button is-style-pil"><a class="wp-block-button__link wp-element-button" href="#galeri">Lihat dokumentasi</a></div><!-- /wp:button -->
</div>
<!-- /wp:buttons -->

<!-- wp:group {"className":"hero-judul","layout":{"type":"default"}} -->
<div class="wp-block-group hero-judul">
<!-- wp:paragraph {"className":"lbl"} --><p class="lbl">Workshop journaling &middot; Yogyakarta</p><!-- /wp:paragraph -->
<!-- wp:heading {"level":1,"className":"d d-xxl"} --><h1 class="wp-block-heading d d-xxl">Your kind journaling companion</h1><!-- /wp:heading -->
</div>
<!-- /wp:group -->

</div>
<!-- /wp:group -->

<!-- wp:image {"className":"cetakan selip selip-hero","sizeSlug":"full","linkDestination":"none"} -->
<figure class="wp-block-image size-full cetakan selip selip-hero"><img src="<?php echo esc_url( get_theme_file_uri( '/assets/img/sundayreads-27.jpg' ) ); ?>" alt=""/><figcaption class="wp-element-caption">Nov 2025</figcaption></figure>
<!-- /wp:image -->

<!-- wp:group {"className":"panggung","layout":{"type":"default"}} -->
<div class="wp-block-group panggung">

<!-- wp:image {"sizeSlug":"full","linkDestination":"none"} -->
<figure class="wp-block-image size-full"><img src="<?php echo esc_url( get_theme_file_uri( '/assets/img/snapobox-11.jpg' ) ); ?>" alt="Peserta workshop The Journaling Room memegang jurnal masing masing di bawah lampion"/></figure>
<!-- /wp:image -->

<!-- wp:group {"className":"panggung-teks","layout":{"type":"default"}} -->
<div class="wp-block-group panggung-teks">
<!-- wp:heading {"level":2} --><h2 class="wp-block-heading">Kelas journaling di Jogja untuk yang belum tahu mau menulis apa</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>Kertasnya kosong, jamnya pelan. Alat tulis sudah kami siapkan, dan tidak ada giliran bercerita di depan orang.</p><!-- /wp:paragraph -->
</div>
<!-- /wp:group -->


</div>
<!-- /wp:group -->

<!-- wp:group {"className":"kartu-sesi","layout":{"type":"default"}} -->
<div class="wp-block-group kartu-sesi">
<!-- wp:paragraph {"className":"lbl"} --><p class="lbl"><span class="titik"></span>Sesi terdekat</p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"kartu-sesi-judul"} --><p class="kartu-sesi-judul">Tracing Shadows, Mapping Stars</p><!-- /wp:paragraph -->

<!-- wp:group {"className":"baris","layout":{"type":"default"}} -->
<div class="wp-block-group baris">
<!-- wp:paragraph {"className":"ik-kalender"} --><p class="ik-kalender">Sabtu, 20 September</p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"ik-jam"} --><p class="ik-jam">15.00 sampai 18.00</p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"ik-pin"} --><p class="ik-pin"><a class="tempat" href="https://www.google.com/maps/search/?api=1&amp;query=Kupiku%20Coffee%2C%20Mantrijeron%2C%20Yogyakarta" target="_blank" rel="noopener">Kupiku Coffee, Jogja</a></p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"ik-orang"} --><p class="ik-orang">11 dari 15 kursi terisi</p><!-- /wp:paragraph -->
</div>
<!-- /wp:group -->

<!-- wp:buttons -->
<div class="wp-block-buttons">
<!-- wp:button {"className":"aksi","width":100} --><div class="wp-block-button aksi has-custom-width wp-block-button__width-100"><a class="wp-block-button__link wp-element-button" href="<?php echo $tjr_wa_slot; ?>" target="_blank" rel="noopener" aria-label="Tanyakan slotnya ke kami lewat WhatsApp">Tanyakan slotnya ke kami</a></div><!-- /wp:button -->
</div>
<!-- /wp:buttons -->

</div>
<!-- /wp:group -->

</section>
<!-- /wp:group -->
