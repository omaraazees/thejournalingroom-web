<?php
/**
 * Title: Jadwal, sesi terdekat
 * Slug: tjr-v5/jadwal-sesi-terdekat
 * Categories: tjr, tjr-beranda
 * Description: Kepala seksi rata tengah plus satu kartu besar berisi sesi terdekat: foto di kiri, judul, ringkasan, enam baris fakta, bar sisa kursi, dan tombol WhatsApp. Query Loop-nya sudah dikunci ke satu acara terdekat yang tanggalnya belum lewat.
 * Keywords: jadwal, acara, sesi terdekat, query loop
 * Viewport Width: 1400
 */

$tjr_wa = esc_url( tjr_v5_link_wa_slot() );
?>
<!-- wp:group {"tagName":"section","className":"seksi","anchor":"jadwal","layout":{"type":"default"}} -->
<section class="wp-block-group seksi" id="jadwal">

<!-- wp:group {"className":"kepala kepala-tengah","layout":{"type":"default"}} -->
<div class="wp-block-group kepala kepala-tengah">
<!-- wp:paragraph {"className":"lbl"} --><p class="lbl">Jadwal</p><!-- /wp:paragraph -->
<!-- wp:heading {"level":2,"className":"d d-xl"} --><h2 class="wp-block-heading d d-xl">Sesi berikutnya</h2><!-- /wp:heading -->
<!-- wp:paragraph {"className":"lead"} --><p class="lead">Satu sesi dibuka dalam satu waktu, supaya persiapannya matang. Kursinya dibatasi agar semua mendapat tempat di meja, jadi tanyakan dulu ke kami.</p><!-- /wp:paragraph -->
</div>
<!-- /wp:group -->

<!-- wp:query {"queryId":11,"query":{"perPage":1,"pages":1,"offset":0,"postType":"acara","order":"asc","orderBy":"date","search":"","exclude":[],"sticky":"","inherit":false},"namespace":"tjr/sesi-terdekat","className":"naik"} -->
<div class="wp-block-query naik">

<!-- wp:post-template -->

<!-- wp:group {"tagName":"article","className":"sesi-terdekat","layout":{"type":"default"}} -->
<article class="wp-block-group sesi-terdekat">

<!-- wp:group {"className":"sesi-foto","layout":{"type":"default"}} -->
<div class="wp-block-group sesi-foto">
<!-- wp:paragraph {"className":"sesi-tag"} --><p class="sesi-tag">Sesi terdekat</p><!-- /wp:paragraph -->
<!-- wp:post-featured-image {"isLink":true} /-->
</div>
<!-- /wp:group -->

<!-- wp:group {"className":"sesi-isi","layout":{"type":"default"}} -->
<div class="wp-block-group sesi-isi">

<!-- wp:post-title {"level":3,"isLink":true,"className":"d d-lg"} /-->
<!-- wp:post-excerpt {"className":"lead","excerptLength":52,"showMoreOnNewLine":false} /-->

<?php /* Enam baris fakta. Yang bertanda kelas dd-waktu, dd-tempat, dd-kit, dan
   dd-kursi isinya ditukar tjr_v5_fakta_acara() dengan field acara yang sedang
   dirender. Teks yang tertulis di bawah cuma contoh yang tampil di editor. */ ?>
<!-- wp:group {"className":"fakta","layout":{"type":"default"}} -->
<div class="wp-block-group fakta">

<!-- wp:group {"layout":{"type":"default"}} -->
<div class="wp-block-group">
<!-- wp:paragraph {"className":"dt ik-kalender"} --><p class="dt ik-kalender">Tanggal</p><!-- /wp:paragraph -->
<!-- wp:post-date {"format":"l, j F Y","className":"dd","isLink":false} /-->
</div>
<!-- /wp:group -->

<!-- wp:group {"layout":{"type":"default"}} -->
<div class="wp-block-group">
<!-- wp:paragraph {"className":"dt ik-jam"} --><p class="dt ik-jam">Waktu</p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"dd dd-waktu"} --><p class="dd dd-waktu">15.00 sampai 18.00 WIB</p><!-- /wp:paragraph -->
</div>
<!-- /wp:group -->

<!-- wp:group {"layout":{"type":"default"}} -->
<div class="wp-block-group">
<!-- wp:paragraph {"className":"dt ik-pin"} --><p class="dt ik-pin">Tempat</p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"dd dd-tempat"} --><p class="dd dd-tempat"><a class="tempat" href="https://www.google.com/maps/search/?api=1&amp;query=Kupiku%20Coffee%2C%20Mantrijeron%2C%20Yogyakarta" target="_blank" rel="noopener">Kupiku Coffee, Mantrijeron, Yogyakarta</a></p><!-- /wp:paragraph -->
</div>
<!-- /wp:group -->

<!-- wp:group {"layout":{"type":"default"}} -->
<div class="wp-block-group">
<!-- wp:paragraph {"className":"dt ik-orang"} --><p class="dt ik-orang">Format</p><!-- /wp:paragraph -->
<!-- wp:post-terms {"term":"format-acara","className":"dd"} /-->
</div>
<!-- /wp:group -->

<!-- wp:group {"layout":{"type":"default"}} -->
<div class="wp-block-group">
<!-- wp:paragraph {"className":"dt ik-kotak"} --><p class="dt ik-kotak">Yang disediakan</p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"dd dd-kit"} --><p class="dd dd-kit">Jurnal, stiker, booklet prompt, deco station, satu minuman</p><!-- /wp:paragraph -->
</div>
<!-- /wp:group -->

<!-- wp:group {"layout":{"type":"default"}} -->
<div class="wp-block-group">
<!-- wp:paragraph {"className":"dt ik-tas"} --><p class="dt ik-tas">Yang perlu dibawa</p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"dd"} --><p class="dd">Tidak ada. Jurnal sendiri boleh dibawa kalau ingin</p><!-- /wp:paragraph -->
</div>
<!-- /wp:group -->

</div>
<!-- /wp:group -->

<?php /* Bar sisa kursi. Satu satunya HTML mentah di pattern ini, karena batang warna bukan konten teks. Angka persennya ditukar tjr_v5_fakta_acara() dari field kapasitas dan slot terisi. */ ?>
<!-- wp:html -->
<div class="slot" role="img" aria-label="11 dari 15 kursi sudah terisi"><i style="--p:73%"></i></div>
<!-- /wp:html -->

<!-- wp:paragraph {"className":"slot-catatan dd-kursi"} --><p class="slot-catatan dd-kursi">11 dari 15 kursi sudah terisi</p><!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons">
<!-- wp:button {"className":"is-style-pil-isi"} --><div class="wp-block-button is-style-pil-isi"><a class="wp-block-button__link wp-element-button" href="<?php echo $tjr_wa; ?>" target="_blank" rel="noopener">Tanyakan slotnya ke kami</a></div><!-- /wp:button -->
</div>
<!-- /wp:buttons -->

</div>
<!-- /wp:group -->

</article>
<!-- /wp:group -->

<!-- /wp:post-template -->

<!-- wp:query-no-results -->
<!-- wp:paragraph {"className":"lead","align":"center"} --><p class="lead has-text-align-center">Belum ada sesi yang dijadwalkan. Tanyakan ke kami, biasanya jadwal berikutnya sudah dirancang.</p><!-- /wp:paragraph -->
<!-- /wp:query-no-results -->

</div>
<!-- /wp:query -->

</section>
<!-- /wp:group -->
