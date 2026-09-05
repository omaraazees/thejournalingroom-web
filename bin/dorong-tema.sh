#!/bin/bash
# Dorong isi wordpress/theme-v5 jadi akar repo tjr-v5-theme.
# Repo itu yang ditarik Hostinger ke wp-content/themes/tjr-v5.
#
# Kenapa perlu repo terpisah: WordPress mencari style.css persis di akar folder
# tema. Kalau repo utama di-clone apa adanya, style.css-nya terkubur dua tingkat
# di dalam dan temanya tidak terdeteksi.
set -e
cd "$(dirname "$0")/.."

if ! git diff --quiet || ! git diff --cached --quiet; then
  echo "Ada perubahan yang belum di-commit. Commit dulu, baru dorong tema."
  exit 1
fi

echo "Mendorong wordpress/theme-v5 ke repo tema..."
git subtree push --prefix=wordpress/theme-v5 tema main
echo
echo "Selesai. Kalau Hostinger sudah tersambung, klik Deploy di hPanel,"
echo "atau nyalakan Auto deployment supaya jalan sendiri tiap kali didorong."
