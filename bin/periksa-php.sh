#!/bin/bash
# Periksa sintaks seluruh berkas PHP tema sebelum dikirim ke server.
#
# Kenapa skrip ini ada. Deploy tema itu FTP, bukan build, jadi satu salah ketik
# PHP langsung jadi layar putih di situs yang tayang. Mesin kerja di sini TIDAK
# punya biner `php`, jadi `php -l` tidak bisa dipakai dan selama ini berkas PHP
# dikirim tanpa diperiksa sama sekali.
#
# Jalan keluarnya `php-parser` di npm, parser PHP murni JavaScript. Node ada di
# mesin ini walau PHP tidak. Kalau suatu saat `php` benar-benar terpasang,
# skrip ini memakai `php -l` karena itu yang paling sahih.
#
# Batasnya: yang tertangkap kesalahan SINTAKS, bukan fatal saat jalan seperti
# fungsi yang tidak ada atau jumlah argumen yang salah. Jadi pemeriksaan
# sesudah deploy tetap wajib, ini cuma menutup kelas kesalahan yang paling
# murah ditangkap sebelum berkasnya berangkat.
#
#   bin/periksa-php.sh                     # periksa wordpress/theme-v5
#   bin/periksa-php.sh path/ke/folder      # periksa folder lain
set -e
cd "$(dirname "$0")/.."

TARGET="${1:-wordpress/theme-v5}"

if [ ! -d "$TARGET" ]; then
  echo "Folder tidak ada: $TARGET"
  exit 1
fi

# 1. Kalau ada php asli, itu yang dipakai. Tidak ada yang mengalahkan php -l.
if command -v php >/dev/null 2>&1; then
  echo "Memakai php -l ($(php -r 'echo PHP_VERSION;'))"
  gagal=0
  while IFS= read -r berkas; do
    if php -l "$berkas" >/dev/null 2>&1; then
      echo "OK    ${berkas#"$TARGET"/}"
    else
      gagal=$((gagal + 1))
      echo "GAGAL ${berkas#"$TARGET"/}"
      php -l "$berkas" 2>&1 | sed 's/^/      /'
    fi
  done < <(find "$TARGET" -name '*.php' -not -path '*/node_modules/*' | sort)
  echo "---"
  echo "$gagal berkas gagal"
  [ "$gagal" -eq 0 ] || exit 1
  exit 0
fi

# 2. Tanpa php, pakai parser JavaScript.
if ! command -v node >/dev/null 2>&1; then
  echo "Nol biner php DAN nol node. Tidak ada cara memeriksa sintaks PHP di mesin ini."
  echo "Pasang salah satunya, atau kirim tanpa pemeriksaan dan sadari risikonya."
  exit 1
fi

CACHE="bin/.cache-lint"

if [ ! -d "$CACHE/node_modules/php-parser" ]; then
  echo "Mengambil php-parser sekali ke $CACHE ..."
  mkdir -p "$CACHE"
  # --cache wajib diarahkan keluar dari ~/.npm, karena sandbox agent memblokir
  # tulis ke sana dan npm gagal dengan EPERM, bukan dengan pesan jaringan.
  ( cd "$CACHE" && npm install php-parser --cache "${TMPDIR:-/tmp}/npm-cache-tjr" --no-audit --no-fund --silent )
fi

node bin/periksa-php.js "$TARGET" "$CACHE"
