#!/usr/bin/env python3
"""Kirim wordpress/theme-v5 ke server lewat FTP.

Kredensial dibaca dari ~/.tjr-ftp (format: host, user, pass, satu per baris)
atau dari env TJR_FTP_HOST / TJR_FTP_USER / TJR_FTP_PASS.

Hanya menyentuh folder tema. Tidak bisa menghapus instalasi WordPress.

  python3 bin/kirim-tema-ftp.py            # kirim perubahan
  python3 bin/kirim-tema-ftp.py --hapus    # plus hapus file di server yang sudah tidak ada di lokal
  python3 bin/kirim-tema-ftp.py --coba     # tampilkan rencana, tidak mengirim apa pun
"""
import ftplib, hashlib, os, pathlib, ssl, sys

LOKAL = pathlib.Path(__file__).resolve().parent.parent / "wordpress" / "theme-v5"
# Akar FTP itu home akun, bukan public_html. Situsnya ada di bawah domains/.
REMOTE = "/domains/thejournalingroom.id/public_html/wp-content/themes/tjr-v5"
LEWATI = {".DS_Store", "CATATAN.md"}


def kredensial():
    berkas = pathlib.Path.home() / ".tjr-ftp"
    if berkas.exists():
        baris = [b.strip() for b in berkas.read_text().splitlines() if b.strip()]
        if len(baris) >= 3:
            return baris[0], baris[1], baris[2]
    host = os.environ.get("TJR_FTP_HOST")
    user = os.environ.get("TJR_FTP_USER")
    sandi = os.environ.get("TJR_FTP_PASS")
    if host and user and sandi:
        return host, user, sandi
    sys.exit("Kredensial FTP belum ada. Isi ~/.tjr-ftp: baris 1 host, 2 user, 3 password.")


def sambung(host, user, sandi):
    try:
        ftp = ftplib.FTP_TLS(host, timeout=30)
        ftp.login(user, sandi)
        ftp.prot_p()
    except (ftplib.error_perm, ssl.SSLError):
        ftp = ftplib.FTP(host, timeout=30)
        ftp.login(user, sandi)
    ftp.set_pasv(True)
    return ftp


def pastikan_folder(ftp, jalur):
    bagian = [b for b in jalur.split("/") if b]
    jejak = ""
    for b in bagian:
        jejak += "/" + b
        try:
            ftp.mkd(jejak)
        except ftplib.error_perm:
            pass


def daftar_remote(ftp, akar):
    hasil = {}

    def telusur(jalur):
        try:
            isi = list(ftp.mlsd(jalur))
        except ftplib.error_perm:
            return
        for nama, fakta in isi:
            if nama in (".", ".."):
                continue
            penuh = f"{jalur}/{nama}"
            if fakta.get("type") == "dir":
                telusur(penuh)
            elif fakta.get("type") == "file":
                hasil[penuh[len(akar) + 1:]] = int(fakta.get("size", -1))

    telusur(akar)
    return hasil


def main():
    hapus = "--hapus" in sys.argv
    coba = "--coba" in sys.argv

    if not LOKAL.is_dir():
        sys.exit(f"Folder tema tidak ada: {LOKAL}")

    lokal = {}
    for p in LOKAL.rglob("*"):
        if p.is_file() and p.name not in LEWATI:
            lokal[str(p.relative_to(LOKAL))] = p

    host, user, sandi = kredensial()
    ftp = sambung(host, user, sandi)
    pastikan_folder(ftp, REMOTE)
    remote = daftar_remote(ftp, REMOTE)

    kirim, lewat = [], 0
    for rel, p in sorted(lokal.items()):
        if remote.get(rel) == p.stat().st_size:
            lewat += 1
        else:
            kirim.append(rel)

    buang = sorted(set(remote) - set(lokal)) if hapus else []

    print(f"lokal {len(lokal)} berkas, server {len(remote)} berkas")
    print(f"kirim {len(kirim)}, sama {lewat}, hapus {len(buang)}")

    if coba:
        for rel in kirim:
            print("  + " + rel)
        for rel in buang:
            print("  - " + rel)
        ftp.quit()
        return

    for rel in kirim:
        tujuan = f"{REMOTE}/{rel}"
        pastikan_folder(ftp, os.path.dirname(tujuan))

        # Unggah ke nama sementara lalu ditukar. STOR menimpa berkas di tempat,
        # dan selama beberapa ratus milidetik berkas PHP-nya separuh tertulis.
        # Kalau ada yang membuka halaman tepat saat itu, PHP gagal mengurai dan
        # halamannya blank. Rename di server sifatnya seketika.
        sementara = tujuan + ".tmp-kirim"
        with open(lokal[rel], "rb") as f:
            ftp.storbinary("STOR " + sementara, f)
        try:
            ftp.delete(tujuan)
        except ftplib.error_perm:
            pass
        ftp.rename(sementara, tujuan)
        print("  + " + rel)

    for rel in buang:
        try:
            ftp.delete(f"{REMOTE}/{rel}")
            print("  - " + rel)
        except ftplib.error_perm as e:
            print("  ! gagal hapus " + rel + ": " + str(e))

    ftp.quit()
    print("selesai")


if __name__ == "__main__":
    main()
