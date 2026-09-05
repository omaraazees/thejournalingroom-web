<?php
/**
 * Title: Pengantar dan kutipan
 * Slug: tjr-v5/pengantar-kutipan
 * Categories: tjr, tjr-beranda
 * Description: Dua kolom pengantar tentang ruangnya, disusul sepasang foto lebar dan kotak kutipan rose, plus satu cetakan polaroid yang diselipkan di antaranya.
 * Keywords: tentang, pengantar, kutipan, cerita
 * Viewport Width: 1400
 */

// Dua tempat di bawah menyebut berapa kali TJR sudah duduk bersama. Yang
// dihitung cuma acara yang tanggalnya sudah lewat, karena sesi yang belum
// terjadi tidak bisa disebut sudah duduk bersama.
$tjr_n = tjr_v5_jumlah_acara( 'lewat' );
// Kalimatnya dimulai dengan angka, jadi hurufnya besar. Kalau arsipnya masih
// kosong, klausa angkanya dibuang seluruhnya supaya kalimatnya tetap utuh.
$tjr_kali   = ( 0 === $tjr_n )
	? 'Dari kedai kopi sampai pendopo tua'
	: tjr_v5_angka_kata( $tjr_n ) . ' kali, dari kedai kopi sampai pendopo tua';
$tjr_tombol = ( 0 === $tjr_n ) ? 'Lihat arsip' : tjr_v5_angka_kata( $tjr_n ) . ' kolaborasi';
?>
<!-- wp:group {"tagName":"section","className":"duo seksi","anchor":"tentang","layout":{"type":"default"}} -->
<section class="wp-block-group duo seksi" id="tentang">

<!-- wp:group {"className":"naik","layout":{"type":"default"}} -->
<div class="wp-block-group naik">
<!-- wp:paragraph {"className":"lbl"} --><p class="lbl">Tentang ruangnya</p><!-- /wp:paragraph -->
<!-- wp:heading {"level":2,"className":"d d-xl","style":{"spacing":{"margin":{"top":"var:preset|spacing|jarak-2"}}}} --><h2 class="wp-block-heading d d-xl" style="margin-top:var(--wp--preset--spacing--jarak-2)">Yang tumbuh di meja panjang</h2><!-- /wp:heading -->
</div>
<!-- /wp:group -->

<!-- wp:group {"className":"duo-kanan naik","layout":{"type":"default"}} -->
<div class="wp-block-group duo-kanan naik">
<!-- wp:paragraph {"className":"lead"} --><p class="lead">The Journaling Room menggelar workshop journaling dan kelas menulis jurnal di Yogyakarta sejak November 2025. <?php echo esc_html( $tjr_kali ); ?>, selalu dengan pola yang sama: satu meja panjang, bahan yang sudah ditata rapi, dan waktu yang tidak diburu.</p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"lead"} --><p class="lead">Tidak ada sesi perkenalan yang membuat kaku. Kamu boleh menulis, menempel, atau hanya memegang gunting sambil menonton orang lain bekerja. Sorenya selesai ketika kamu merasa selesai.</p><!-- /wp:paragraph -->
<!-- wp:buttons -->
<div class="wp-block-buttons">
<!-- wp:button {"className":"is-style-pil"} --><div class="wp-block-button is-style-pil"><a class="wp-block-button__link wp-element-button" href="#arsip"><?php echo esc_html( $tjr_tombol ); ?></a></div><!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:group -->

</section>
<!-- /wp:group -->

<!-- wp:group {"className":"pasangan naik","layout":{"type":"default"}} -->
<div class="wp-block-group pasangan naik">

<!-- wp:image {"sizeSlug":"full","linkDestination":"none"} -->
<figure class="wp-block-image size-full"><img src="<?php echo esc_url( get_theme_file_uri( '/assets/img/artotel-16.jpg' ) ); ?>" alt="Jurnal peserta digelar berjajar di lantai setelah sesi"/></figure>
<!-- /wp:image -->

<!-- wp:group {"className":"kutipan","layout":{"type":"default"}} -->
<div class="wp-block-group kutipan">
<!-- wp:paragraph {"className":"qm"} --><p class="qm">&ldquo;</p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"d d-lg"} --><p class="d d-lg">Halaman kosong tidak pernah menuntut apa apa</p><!-- /wp:paragraph -->
</div>
<!-- /wp:group -->

<!-- wp:image {"className":"cetakan selip selip-pasangan","sizeSlug":"full","linkDestination":"none"} -->
<figure class="wp-block-image size-full cetakan selip selip-pasangan"><img src="<?php echo esc_url( get_theme_file_uri( '/assets/img/radian-24.jpg' ) ); ?>" alt=""/><figcaption class="wp-element-caption">Pendopo Radian</figcaption></figure>
<!-- /wp:image -->

</div>
<!-- /wp:group -->
