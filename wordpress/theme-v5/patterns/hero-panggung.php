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

// Isi diambil dari halaman depan lewat ACF. Kalau kolomnya belum diisi, yang
// dipakai aset dan kalimat yang sekarang ada di tema.
$tjr_label   = tjr_v5_isi( 'hero_label', 'Workshop journaling' );
$tjr_judul   = tjr_v5_isi( 'hero_judul', 'Your kind journaling companion' );
$tjr_foto    = tjr_v5_foto( 'hero_foto', 'snapobox-11.jpg' );
$tjr_alt     = tjr_v5_foto_alt( 'hero_foto', 'Peserta workshop The Journaling Room memegang jurnal masing masing di bawah lampion' );
$tjr_f_judul = tjr_v5_isi( 'hero_foto_judul', 'Kelas journaling di Jogja untuk yang belum tahu mau menulis apa' );
$tjr_f_isi   = tjr_v5_isi( 'hero_foto_isi', 'Kertasnya kosong, jamnya pelan. Alat tulis sudah kami siapkan, dan tidak ada giliran bercerita di depan orang.' );
$tjr_cetak   = tjr_v5_foto( 'hero_cetakan', 'sundayreads-27.jpg' );
$tjr_cetak_t = tjr_v5_isi( 'hero_cetakan_teks', 'Nov 2025' );
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
<!-- wp:paragraph {"className":"lbl"} --><p class="lbl"><?php echo esc_html( $tjr_label ); ?></p><!-- /wp:paragraph -->
<!-- wp:heading {"level":1,"className":"d d-xxl"} --><h1 class="wp-block-heading d d-xxl"><?php echo esc_html( $tjr_judul ); ?></h1><!-- /wp:heading -->
</div>
<!-- /wp:group -->

</div>
<!-- /wp:group -->

<!-- wp:image {"className":"cetakan selip selip-hero","sizeSlug":"full","linkDestination":"none"} -->
<figure class="wp-block-image size-full cetakan selip selip-hero"><img src="<?php echo esc_url( $tjr_cetak ); ?>" alt=""/><figcaption class="wp-element-caption"><?php echo esc_html( $tjr_cetak_t ); ?></figcaption></figure>
<!-- /wp:image -->

<!-- wp:group {"className":"panggung","layout":{"type":"default"}} -->
<div class="wp-block-group panggung">

<!-- wp:image {"sizeSlug":"full","linkDestination":"none"} -->
<figure class="wp-block-image size-full"><img src="<?php echo esc_url( $tjr_foto ); ?>" alt="<?php echo esc_attr( $tjr_alt ); ?>"/></figure>
<!-- /wp:image -->

<!-- wp:group {"className":"panggung-teks","layout":{"type":"default"}} -->
<div class="wp-block-group panggung-teks">
<!-- wp:heading {"level":2} --><h2 class="wp-block-heading"><?php echo esc_html( $tjr_f_judul ); ?></h2><!-- /wp:heading -->
<!-- wp:paragraph --><p><?php echo esc_html( $tjr_f_isi ); ?></p><!-- /wp:paragraph -->
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
