# T-27: efek filter gettext terhadap wp-admin

Status: **butir 1 dan 2 terjawab tanpa browser sama sekali. Butir 3 belum, dan
saya BELUM menyentuh browser** karena lingkupnya dipersempit Umar 86 detik
sebelum kartu ini ditulis. Rinciannya di bagian terakhir.

## Ringkasnya

Dugaan yang mendasari kartu ini, termasuk dugaan saya sendiri yang melahirkannya,
**tidak terbukti**. Filter `tjr_v5_string_inti_id()` mengubah **nol teks** di
wp-admin, jadi Caca dan Dhanty tidak akan melihat apa pun berubah.

Alasannya bukan karena filternya tidak jalan di wp-admin. Dia memang jalan di sana,
domain `default` berlaku di seluruh admin, dan saya tidak menarik kembali bagian itu.
Alasannya lebih sederhana: **ketiga kunci di peta itu tidak ada di wp-admin.**

## Bukti

### Ketiga kunci cuma punya satu tempat panggil masing-masing, semuanya di depan

Diambil dari sumber inti WordPress, bukan dari ingatan:

| Kunci | Berkas inti | Fungsi | Kapan jalan |
|---|---|---|---|
| `Skip to content` | `wp-includes/block-template.php:395` | `wp_enqueue_block_template_skip_link()` | tersambung ke `wp_footer`, depan saja |
| `Open menu` | `wp-includes/blocks/navigation.php:785` | `render_block_core_navigation()` | saat blok navigasi dirender di server |
| `Close menu` | `wp-includes/blocks/navigation.php:786` | `render_block_core_navigation()` | sama |

Ketiganya di `wp-includes/`, nol di `wp-admin/`.

### Sapuan yang menyeluruh, bukan tebakan berdasarkan berkas yang saya curigai

Grep berkas satu per satu cuma membuktikan yang saya cari, bukan yang tidak ada.
Untuk itu saya pakai translate.wordpress.org, yang memecah inti WordPress persis
menurut letak berkasnya: proyek **Development** berisi string dari `wp-includes/`,
proyek **Administration** berisi string dari `wp-admin/`.

| Term | Development | Administration |
|---|---|---|
| `Skip to content` | ada, 1 original | **nol** |
| `Open menu` | ada, 1 original | **nol** |
| `Close menu` | ada, 1 original | **nol** |

Nol itu diuji dulu sebelum dipercaya, karena nol bisa berarti "tidak ada" atau
"query saya salah". Kontrolnya: `Dashboard` di Administration mengembalikan 30
baris, `Screen Options` 30 baris. Mekanismenya jalan, jadi nolnya nol beneran.

Saya juga membaca isi baris yang cocok, bukan cuma menghitungnya. Ketiganya cocok
**persis satu original** masing-masing, berbunyi tepat `Skip to content`,
`Open menu`, `Close menu`. Nol varian jamak, nol string lain yang kebetulan
mengandung frasa yang sama. Jadi tidak ada string inti lain yang ikut tergeser.

## Batas yang tersisa, dinyatakan terbuka

**Satu.** Sumber yang saya baca adalah cermin resmi WordPress di GitHub, bukan
berkas yang benar-benar terpasang di server. Situs melaporkan dirinya WordPress 7.1.

**Dua.** `wp-includes/blocks/navigation.php` jalan di mana pun blok navigasi
dirender di sisi server. Di editor blok, navigasi dirender di sisi klien lewat
`wp.i18n`, dan itu **tidak** lewat filter `gettext` PHP. Tapi ada jalur server
lewat endpoint block-renderer REST, dan saya belum memastikan editor memakainya
untuk `core/navigation`. Ini sisa yang sebenarnya, dan kecil.

**Tiga, dan ini yang jelas belum terjawab.** Butir 3 kartu, nol galat konsol di
layar yang terpengaruh. Kalau nol layar terpengaruh, pertanyaannya jadi hampir
kosong, tapi "hampir" bukan "sudah". Ini butuh memuat layarnya.

## Kenapa saya berhenti di sini

Kartu ini menyuruh saya masuk lewat sesi Chrome yang sudah login. Urutannya begini:

- 09:25:00 siaran pertama, akses browser dibuka untuk "keperluan desain"
- 09:25:53 **koreksi: Umar mempersempit ke GP & FEF, chat lewat Rocket.Chat**
- 09:27:21 kartu T-27 terbit, menyetujui saya menyapu wp-admin TJR

Kartu ini menyetujui pesan saya yang **pertama**, yang saya tulis sebelum koreksi
itu sampai, dan menyilang dengan pesan saya yang menarik usulan itu. TJR bukan
GP & FEF. Yang mempersempit Umar, bukan god, jadi bukan god yang bisa melebarkannya
kembali. Saya kerjakan semua yang tidak bergantung pada jawaban itu, dan yang
bergantung saya tahan.

Nol tindakan browser dilakukan. Nol layar dibuka, nol auto-draft lahir, nol jejak
untuk dibersihkan.
