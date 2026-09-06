// Pemeriksa sintaks PHP tanpa biner PHP. Dipanggil bin/periksa-php.sh.
//
// Dua argumen: folder yang diperiksa, lalu folder tempat php-parser terpasang.

const fs = require('fs');
const path = require('path');

const target = process.argv[2];
const cache = process.argv[3];

const engine = require(path.resolve(cache, 'node_modules', 'php-parser'));

function buatParser() {
  return new engine({
    parser: { extractDoc: false, suppressErrors: false, version: 803 },
    ast: { withPositions: true },
  });
}

function periksa(kode, nama) {
  try {
    buatParser().parseCode(kode, nama);
    return null;
  } catch (err) {
    return err.message.split('\n')[0];
  }
}

// Uji parsernya sendiri sebelum mempercayainya.
//
// Ini bukan kehati-hatian berlebihan. Alat yang diam karena salah dikonfigurasi
// memberi laporan hijau yang persis sama dengan alat yang bekerja, dan hijau
// palsu di sini artinya berkas rusak berangkat ke situs yang tayang. Jadi
// parsernya wajib membuktikan dia bisa gagal dulu.
const rusak = '<?php\nfunction a() {\n  $x = 1\n  return $x;\n}\n';
const sehat = '<?php\nfunction b() { return 1; }\n';

if (!periksa(rusak, 'uji-rusak.php') || periksa(sehat, 'uji-sehat.php')) {
  console.log('Parsernya sendiri tidak bisa dipercaya: berkas rusak lolos, atau berkas sehat ditolak.');
  console.log('Jangan pakai hasilnya. Periksa pemasangan php-parser di ' + cache);
  process.exit(2);
}

const berkas = [];
(function sisir(dir) {
  for (const e of fs.readdirSync(dir, { withFileTypes: true })) {
    const p = path.join(dir, e.name);
    if (e.isDirectory()) {
      if (e.name !== 'node_modules') sisir(p);
    } else if (e.name.endsWith('.php')) {
      berkas.push(p);
    }
  }
})(target);

let gagal = 0;

for (const f of berkas.sort()) {
  const pesan = periksa(fs.readFileSync(f, 'utf8'), f);
  if (pesan) {
    gagal++;
    console.log('GAGAL ' + path.relative(target, f));
    console.log('      ' + pesan);
  } else {
    console.log('OK    ' + path.relative(target, f));
  }
}

console.log('---');
console.log(berkas.length + ' berkas PHP, ' + gagal + ' gagal');
console.log('Catatan: yang diperiksa sintaks, bukan fatal saat jalan. Tetap cek situsnya sesudah deploy.');

process.exit(gagal ? 1 : 0);
