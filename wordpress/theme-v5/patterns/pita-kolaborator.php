<?php
/**
 * Title: Pita kolaborator
 * Slug: tjr-v5/pita-kolaborator
 * Categories: tjr, tjr-beranda
 * Description: Petak logo kolaborator dengan garis pemisah setipis satu piksel. Logonya redup sampai kursor lewat, lalu penuh. Satu cetakan polaroid diselipkan di sudut kanan kepala seksi.
 * Keywords: kolaborator, logo, partner, brand
 * Viewport Width: 1400
 */

$tjr_logo = array(
	array( 'sundayreads', 'Sunday Reads Club' ),
	array( 'radian', 'Radian' ),
	array( 'kupiku', 'Kupiku Coffee' ),
	array( 'wardah', 'Wardah' ),
	array( 'artotel', 'Artotel' ),
	array( 'hanasui', 'Hanasui' ),
	array( 'heejaz', 'Heejaz' ),
	array( 'statement-beauty', 'Statement Beauty' ),
	array( 'amco', 'AMCO Bakehouse' ),
	array( 'pasar-jakal', 'Pasar Jakal' ),
	array( 'snapobox', 'Snapobox' ),
	array( 'tjr-mark', 'The Journaling Room' ),
);

// Judulnya menghitung nama kolaborator saja, logo TJR sendiri tidak ikut.
// Angkanya dihitung dari daftar di atas supaya tidak perlu diingat waktu
// menambah atau mengurangi logo.
$tjr_kolab = 0;
foreach ( $tjr_logo as $tjr_satu ) {
	if ( 'tjr-mark' !== $tjr_satu[0] ) {
		$tjr_kolab++;
	}
}
// Kalau sudah ada entri di menu Kolaborator, itu yang dipakai. Daftar di atas
// cuma cadangan supaya pita tidak pernah kosong sebelum diisi.
$tjr_dari_cms = tjr_v5_kolaborator();

if ( $tjr_dari_cms ) {
	$tjr_kolab = count( $tjr_dari_cms );
	$tjr_petak = $tjr_dari_cms;
} else {
	$tjr_petak = array();
	foreach ( $tjr_logo as $tjr_satu ) {
		if ( 'tjr-mark' === $tjr_satu[0] ) {
			continue;
		}
		$tjr_petak[] = array(
			'nama' => $tjr_satu[1],
			'logo' => get_theme_file_uri( '/assets/img/' . $tjr_satu[0] . '.png' ),
		);
	}
}

// Logo TJR sendiri selalu jadi petak terakhir, dan tidak ikut dihitung.
$tjr_petak[] = array(
	'nama' => 'The Journaling Room',
	'logo' => get_theme_file_uri( '/assets/img/tjr-mark.png' ),
);

$tjr_judul = tjr_v5_angka_kata( $tjr_kolab ) . ' nama di meja';
?>
<!-- wp:group {"tagName":"section","className":"seksi","anchor":"kolaborator","layout":{"type":"default"}} -->
<section class="wp-block-group seksi" id="kolaborator">

<!-- wp:group {"className":"kepala","layout":{"type":"default"}} -->
<div class="wp-block-group kepala">
<!-- wp:group {"layout":{"type":"default"}} -->
<div class="wp-block-group">
<!-- wp:paragraph {"className":"lbl"} --><p class="lbl">Yang pernah bersama</p><!-- /wp:paragraph -->
<!-- wp:heading {"level":2,"className":"d d-xl","style":{"spacing":{"margin":{"top":"var:preset|spacing|jarak-2"}}}} --><h2 class="wp-block-heading d d-xl" style="margin-top:var(--wp--preset--spacing--jarak-2)"><?php echo esc_html( $tjr_judul ); ?></h2><!-- /wp:heading -->
</div>
<!-- /wp:group -->

<!-- wp:image {"className":"cetakan selip selip-kolaborator","sizeSlug":"full","linkDestination":"none"} -->
<figure class="wp-block-image size-full cetakan selip selip-kolaborator"><img src="<?php echo esc_url( get_theme_file_uri( '/assets/img/artotel-16.jpg' ) ); ?>" alt=""<?php echo tjr_v5_sifat_gambar( get_theme_file_uri( '/assets/img/artotel-16.jpg' ) ); ?>/><figcaption class="wp-element-caption">Artotel, Apr 2026</figcaption></figure>
<!-- /wp:image -->

</div>
<!-- /wp:group -->

<!-- wp:group {"className":"logos naik","layout":{"type":"default"}} -->
<div class="wp-block-group logos naik">
<?php foreach ( $tjr_petak as $tjr_satu ) : ?>
<!-- wp:image {"sizeSlug":"full","linkDestination":"none"} -->
<figure class="wp-block-image size-full"><img src="<?php echo esc_url( $tjr_satu['logo'] ); ?>" alt="<?php echo esc_attr( $tjr_satu['nama'] ); ?>"<?php echo tjr_v5_sifat_gambar( $tjr_satu['logo'] ); ?>/></figure>
<!-- /wp:image -->
<?php endforeach; ?>
</div>
<!-- /wp:group -->

</section>
<!-- /wp:group -->
