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
// dipakai aset dan kalimat bawaan di tjr_v5_bawaan_teks() dan
// tjr_v5_bawaan_foto(), bukan yang diketik ulang di sini.
$tjr_label   = tjr_v5_isi( 'hero_label' );
$tjr_judul   = tjr_v5_isi( 'hero_judul' );
$tjr_foto    = tjr_v5_foto( 'hero_foto' );
$tjr_alt     = tjr_v5_foto_alt( 'hero_foto' );
$tjr_f_judul = tjr_v5_isi( 'hero_foto_judul' );
$tjr_f_isi   = tjr_v5_isi( 'hero_foto_isi' );
$tjr_cetak   = tjr_v5_foto( 'hero_cetakan' );
$tjr_cetak_t = tjr_v5_isi( 'hero_cetakan_teks' );

// Kartu sesi terdekat mengambil acara yang tanggalnya paling dekat dan belum
// lewat. Kalau belum ada acara sama sekali, kartunya tidak dicetak.
$tjr_sesi = tjr_v5_acara_terdekat();

if ( $tjr_sesi ) {
	$tjr_sid    = $tjr_sesi->ID;
	$tjr_s_mul  = get_post_meta( $tjr_sid, TJR_FIELD_MULAI, true );
	$tjr_s_cap  = $tjr_s_mul ? strtotime( $tjr_s_mul ) : false;
	$tjr_s_tgl  = $tjr_s_cap ? tjr_v5_tanggal_id( 'l, j F', $tjr_s_cap ) : '';
	$tjr_s_jam  = str_replace( ' WIB', '', tjr_v5_jam_acara( $tjr_sid ) );
	$tjr_s_tpt  = tjr_v5_tempat_acara( $tjr_sid );
	$tjr_kursi  = tjr_v5_kursi_acara( $tjr_sid );
	$tjr_s_krs  = $tjr_kursi ? $tjr_kursi['terisi'] . ' dari ' . $tjr_kursi['kapasitas'] . ' kursi terisi' : '';
	// Memakai fungsi yang sama dengan kartu jadwal dan halaman acara, jadi
	// penjagaannya ikut: cuma dirender kalau harga lebih dari nol.
	$tjr_s_hrg  = tjr_v5_harga_acara( $tjr_sid );
}
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
<figure class="wp-block-image size-full cetakan selip selip-hero"><img src="<?php echo esc_url( $tjr_cetak ); ?>" alt=""<?php echo tjr_v5_sifat_gambar( $tjr_cetak ); ?>/><figcaption class="wp-element-caption"><?php echo esc_html( $tjr_cetak_t ); ?></figcaption></figure>
<!-- /wp:image -->

<!-- wp:group {"className":"panggung","layout":{"type":"default"}} -->
<div class="wp-block-group panggung">

<!-- wp:image {"sizeSlug":"full","linkDestination":"none"} -->
<figure class="wp-block-image size-full"><img src="<?php echo esc_url( $tjr_foto ); ?>" alt="<?php echo esc_attr( $tjr_alt ); ?>"<?php echo tjr_v5_sifat_gambar( $tjr_foto, true ); ?>/></figure>
<!-- /wp:image -->

<!-- wp:group {"className":"panggung-teks","layout":{"type":"default"}} -->
<div class="wp-block-group panggung-teks">
<!-- wp:heading {"level":2} --><h2 class="wp-block-heading"><?php echo esc_html( $tjr_f_judul ); ?></h2><!-- /wp:heading -->
<!-- wp:paragraph --><p><?php echo esc_html( $tjr_f_isi ); ?></p><!-- /wp:paragraph -->
</div>
<!-- /wp:group -->


</div>
<!-- /wp:group -->

<?php if ( $tjr_sesi ) : ?>
<!-- wp:group {"className":"kartu-sesi","layout":{"type":"default"}} -->
<div class="wp-block-group kartu-sesi">
<!-- wp:paragraph {"className":"lbl"} --><p class="lbl"><span class="titik"></span>Sesi terdekat</p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"kartu-sesi-judul"} --><p class="kartu-sesi-judul"><?php echo esc_html( get_the_title( $tjr_sid ) ); ?></p><!-- /wp:paragraph -->

<!-- wp:group {"className":"baris","layout":{"type":"default"}} -->
<div class="wp-block-group baris">
<?php if ( $tjr_s_tgl ) : ?>
<!-- wp:paragraph {"className":"ik-kalender"} --><p class="ik-kalender"><?php echo esc_html( $tjr_s_tgl ); ?></p><!-- /wp:paragraph -->
<?php endif; ?>
<?php if ( $tjr_s_jam ) : ?>
<!-- wp:paragraph {"className":"ik-jam"} --><p class="ik-jam"><?php echo esc_html( $tjr_s_jam ); ?></p><!-- /wp:paragraph -->
<?php endif; ?>
<?php if ( $tjr_s_tpt ) : ?>
<!-- wp:paragraph {"className":"ik-pin"} --><p class="ik-pin"><?php echo $tjr_s_tpt; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p><!-- /wp:paragraph -->
<?php endif; ?>
<?php if ( $tjr_s_hrg ) : ?>
<!-- wp:paragraph {"className":"ik-tag"} --><p class="ik-tag"><?php echo $tjr_s_hrg; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p><!-- /wp:paragraph -->
<?php endif; ?>
<?php if ( $tjr_s_krs ) : ?>
<!-- wp:paragraph {"className":"ik-orang"} --><p class="ik-orang"><?php echo esc_html( $tjr_s_krs ); ?></p><!-- /wp:paragraph -->
<?php endif; ?>
</div>
<!-- /wp:group -->

<!-- wp:buttons -->
<div class="wp-block-buttons">
<!-- wp:button {"className":"aksi","width":100} --><div class="wp-block-button aksi has-custom-width wp-block-button__width-100"><a class="wp-block-button__link wp-element-button" href="<?php echo $tjr_wa_slot; ?>" target="_blank" rel="noopener" aria-label="Tanyakan slotnya ke kami lewat WhatsApp">Tanyakan slotnya ke kami</a></div><!-- /wp:button -->
</div>
<!-- /wp:buttons -->

</div>
<!-- /wp:group -->
<?php endif; ?>

</section>
<!-- /wp:group -->
