<?php
/**
 * Title: Pengantar dan kutipan
 * Slug: tjr-v5/pengantar-kutipan
 * Categories: tjr, tjr-beranda
 * Description: Dua kolom pengantar tentang ruang ini, disusul sepasang foto lebar dan kotak kutipan rose, plus satu cetakan polaroid yang diselipkan di antaranya.
 * Keywords: tentang, pengantar, kutipan, cerita
 * Viewport Width: 1400
 */

// Isi dari halaman depan. Bawaannya ada di tjr_v5_bawaan_teks() dan
// tjr_v5_bawaan_foto(), termasuk paragraf pertama yang menyebut angka tadi.
$tjr_p1      = tjr_v5_isi( 'pengantar_1' );
$tjr_p2      = tjr_v5_isi( 'pengantar_2' );
$tjr_kutip   = tjr_v5_isi( 'kutipan' );
$tjr_foto    = tjr_v5_foto( 'pengantar_foto' );
$tjr_alt     = tjr_v5_foto_alt( 'pengantar_foto' );

// Kartu G-4: alih ke .webp lewat tjr_v5_gambar_tag(), lihat inc/isi-beranda.php.
// pengantar_foto ikut sekarang juga (artotel-16, dulu sengaja dilewati kartu
// P-3 karena tampil hampir selebar section di desktop dan webp-nya cuma
// 300x533 px, jadi bisa terlihat agak lembek waktu diregangkan ke ~900px di
// layar lebar). <picture> di sini tetap menyertakan .jpg asli sebagai
// fallback, jadi ini soal ketajaman saat webp-nya lolos, bukan risiko gagal
// muat baru. Diikutkan karena kartu G-4 secara eksplisit meminta artotel-16.
$tjr_cetak   = tjr_v5_foto( 'pengantar_cetakan' );
// Sama seperti cetakan di hero: ada figcaption bermakna, jadi alt="" salah.
$tjr_cetak_a = tjr_v5_foto_alt( 'pengantar_cetakan' );
$tjr_cetak_t = tjr_v5_isi( 'pengantar_cetakan_teks' );
?>
<!-- wp:group {"tagName":"section","className":"duo seksi","anchor":"tentang","layout":{"type":"default"}} -->
<section class="wp-block-group duo seksi" id="tentang">

<!-- wp:group {"className":"naik","layout":{"type":"default"}} -->
<div class="wp-block-group naik">
<!-- wp:paragraph {"className":"lbl"} --><p class="lbl">Tentang ruang ini</p><!-- /wp:paragraph -->
<!-- wp:heading {"level":2,"className":"d d-xl","style":{"spacing":{"margin":{"top":"var:preset|spacing|jarak-2"}}}} --><h2 class="wp-block-heading d d-xl" style="margin-top:var(--wp--preset--spacing--jarak-2)">Yang tumbuh di meja panjang</h2><!-- /wp:heading -->
</div>
<!-- /wp:group -->

<!-- wp:group {"className":"duo-kanan naik","layout":{"type":"default"}} -->
<div class="wp-block-group duo-kanan naik">
<!-- wp:paragraph {"className":"lead"} --><p class="lead"><?php echo esc_html( $tjr_p1 ); ?></p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"lead"} --><p class="lead"><?php echo esc_html( $tjr_p2 ); ?></p><!-- /wp:paragraph -->
<!-- wp:buttons -->
<div class="wp-block-buttons">
<!-- wp:button {"className":"is-style-pil"} --><div class="wp-block-button is-style-pil"><a class="wp-block-button__link wp-element-button" href="#kolaborator">Para Kolaborator</a></div><!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:group -->

</section>
<!-- /wp:group -->

<!-- wp:group {"className":"pasangan naik","layout":{"type":"default"}} -->
<div class="wp-block-group pasangan naik">

<!-- wp:image {"sizeSlug":"full","linkDestination":"none"} -->
<figure class="wp-block-image size-full"><?php echo tjr_v5_gambar_tag( $tjr_foto, $tjr_alt ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></figure>
<!-- /wp:image -->

<!-- wp:group {"className":"kutipan","layout":{"type":"default"}} -->
<div class="wp-block-group kutipan">
<!-- wp:paragraph {"className":"qm"} --><p class="qm">&ldquo;</p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"d d-lg"} --><p class="d d-lg"><?php echo esc_html( $tjr_kutip ); ?></p><!-- /wp:paragraph -->
</div>
<!-- /wp:group -->

<!-- wp:image {"className":"cetakan selip selip-pasangan","sizeSlug":"full","linkDestination":"none"} -->
<figure class="wp-block-image size-full cetakan selip selip-pasangan"><?php echo tjr_v5_gambar_tag( $tjr_cetak, $tjr_cetak_a ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><figcaption class="wp-element-caption"><?php echo esc_html( $tjr_cetak_t ); ?></figcaption></figure>
<!-- /wp:image -->

</div>
<!-- /wp:group -->
