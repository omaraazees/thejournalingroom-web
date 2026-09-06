#!/usr/bin/env python3
"""Kirim wordpress/theme-v5 ke server lewat FTP.

Kredensial dibaca dari ~/.tjr-ftp (format: host, user, pass, satu per baris)
atau dari env TJR_FTP_HOST / TJR_FTP_USER / TJR_FTP_PASS.

Hanya menyentuh folder tema. Tidak bisa menghapus instalasi WordPress.

  python3 bin/kirim-tema-ftp.py            # kirim perubahan
  python3 bin/kirim-tema-ftp.py --hapus    # plus hapus file di server yang sudah tidak ada di lokal
  python3 bin/kirim-tema-ftp.py --coba     # tampilkan rencana, tidak mengirim apa pun
  python3 bin/kirim-tema-ftp.py --teliti   # abaikan manifes, unduh dan hash isi asli di server

CARA SKRIP INI MEMUTUSKAN SEBUAH BERKAS SUDAH SAMA

Dulu perbandingannya cuma UKURAN berkas. Dua berkas yang isinya beda tapi
panjangnya kebetulan sama dilaporkan 'sama' dan tidak pernah terkirim, tanpa
peringatan. Itu bukan kasus langka di sini: perbaikan CSS satu karakter
(`jarak-5` jadi `jarak-2`, tukar satu nama font) panjangnya persis sama.

Server ini tidak menyediakan hash. FEAT-nya cuma punya SIZE, MDTM, dan MLST,
tidak ada XMD5/XSHA256/HASH. Jadi isi berkas di server tidak bisa ditanyakan,
cuma bisa diunduh. Mengunduh 61 berkas tiap kali jalan menaikkan waktu dari
~4 detik ke ~20 detik, dan kecepatan itu bagian dari kenapa orang mau memakai
skrip ini.

Jalan tengahnya manifes: tiap berkas yang berhasil dikirim dicatat sha256 isinya
BESERTA stempel server (size + modify dari MLST) tepat sesudah kirim. Lain kali,
sebuah berkas dianggap sama hanya kalau ketiganya cocok:

  1. sha256 berkas lokal == sha256 yang tercatat di manifes, DAN
  2. size di server sekarang == size yang tercatat, DAN
  3. modify di server sekarang == modify yang tercatat

Syarat 1 menutup bug ukuran. Syarat 2 dan 3 menutup lubang manifes: kalau ada
yang mengubah berkas di server di luar skrip ini, stempelnya bergeser dan
berkasnya dikirim ulang. Semua yang ragu dikirim, tidak pernah dilewati.

Kalau manifes belum ada sama sekali, skrip pindah sendiri ke mode teliti sekali
itu: isi server diunduh dan di-hash, jadi yang naik cuma yang benar-benar beda.
Itu lebih lambat sekali jalan, tapi lebih baik daripada mengapalkan ulang 5,8 MB
yang sudah benar. `--teliti` memaksa mode itu kapan saja, misalnya waktu manifes
dicurigai bohong dan kamu ingin bukti, bukan pembukuan.
"""
import ftplib, hashlib, json, os, pathlib, ssl, sys, time

AKAR = pathlib.Path(__file__).resolve().parent.parent
LOKAL = AKAR / "wordpress" / "theme-v5"
# Akar FTP itu home akun, bukan public_html. Situsnya ada di bawah domains/.
REMOTE = "/domains/thejournalingroom.id/public_html/wp-content/themes/tjr-v5"
LEWATI = {".DS_Store", "CATATAN.md"}
# Manifes bukan sumber kebenaran, cuma catatan kiriman terakhir, jadi tidak masuk
# git: hilang pun skrip masih benar, cuma sekali jalan lebih lambat.
MANIFES = pathlib.Path(os.environ.get("TJR_MANIFES") or (AKAR / "bin" / ".cache-kirim" / "manifes.json"))


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


def sidik(berkas):
    h = hashlib.sha256()
    with open(berkas, "rb") as f:
        for potong in iter(lambda: f.read(131072), b""):
            h.update(potong)
    return h.hexdigest()


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
    """rel -> {'size': int, 'modify': str}. Stempel inilah yang menjaga manifes jujur."""
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
                hasil[penuh[len(akar) + 1:]] = {
                    "size": int(fakta.get("size", -1)),
                    "modify": str(fakta.get("modify", "")),
                }

    telusur(akar)
    return hasil


def stempel(ftp, jalur):
    """Tanya server size + modify satu berkas lewat MLST. Cuma jalur kendali, murah.

    Balikannya None kalau server menolak. Tanpa stempel, entri manifes tidak
    ditulis, jadi berkasnya dikirim ulang lain kali. Salah ke arah yang aman.
    """
    try:
        jawab = ftp.sendcmd("MLST " + jalur)
    except (ftplib.error_perm, ftplib.error_temp):
        return None
    for baris in jawab.splitlines():
        baris = baris.strip()
        if ";" not in baris:
            continue
        fakta = {}
        for potong in baris.split(" ")[0].split(";"):
            if "=" in potong:
                k, _, v = potong.partition("=")
                fakta[k.lower()] = v
        if "size" in fakta:
            return {"size": int(fakta["size"]), "modify": str(fakta.get("modify", ""))}
    return None


def sidik_remote(ftp, jalur):
    """Unduh satu berkas dan hash isinya. Mahal, cuma dipakai mode teliti."""
    h = hashlib.sha256()
    try:
        ftp.retrbinary("RETR " + jalur, h.update)
    except (ftplib.error_perm, ftplib.error_temp):
        return None
    return h.hexdigest()


def baca_manifes():
    try:
        isi = json.loads(MANIFES.read_text())
    except (OSError, ValueError):
        return {}
    if isi.get("remote") != REMOTE:
        # Manifes milik tujuan lain. Jangan dipercaya sedikit pun.
        return {}
    return isi.get("berkas") or {}


def tulis_manifes(berkas):
    MANIFES.parent.mkdir(parents=True, exist_ok=True)
    sementara = MANIFES.with_suffix(".tmp")
    sementara.write_text(json.dumps(
        {"versi": 1, "remote": REMOTE, "berkas": berkas}, indent=1, sort_keys=True))
    sementara.replace(MANIFES)


def main():
    hapus = "--hapus" in sys.argv
    coba = "--coba" in sys.argv
    teliti = "--teliti" in sys.argv

    if not LOKAL.is_dir():
        sys.exit(f"Folder tema tidak ada: {LOKAL}")

    lokal = {}
    for p in LOKAL.rglob("*"):
        if p.is_file() and p.name not in LEWATI:
            lokal[str(p.relative_to(LOKAL))] = p

    sidik_lokal = {rel: sidik(p) for rel, p in lokal.items()}

    host, user, sandi = kredensial()
    ftp = sambung(host, user, sandi)
    pastikan_folder(ftp, REMOTE)
    remote = daftar_remote(ftp, REMOTE)

    manifes = baca_manifes()
    if not manifes and remote and not teliti:
        print("manifes belum ada, sekali ini isi server diunduh dan di-hash")
        teliti = True

    kirim, lewat = [], 0
    for rel in sorted(lokal):
        di_server = remote.get(rel)
        if di_server is None:
            kirim.append(rel)
            continue

        if teliti:
            # Kebenaran, bukan pembukuan: hash isi asli yang ada di server.
            sama = sidik_remote(ftp, f"{REMOTE}/{rel}") == sidik_lokal[rel]
            if sama:
                # Sudah dibuktikan sama, jadi stempelnya boleh masuk manifes.
                manifes[rel] = dict(di_server, sha256=sidik_lokal[rel])
        else:
            catatan = manifes.get(rel)
            sama = bool(catatan) \
                and catatan.get("sha256") == sidik_lokal[rel] \
                and catatan.get("size") == di_server["size"] \
                and catatan.get("modify") == di_server["modify"]

        if sama:
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
        # --coba tidak menulis apa pun, termasuk manifes.
        return

    try:
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

            baru = stempel(ftp, tujuan)
            if baru:
                manifes[rel] = dict(baru, sha256=sidik_lokal[rel])
            else:
                manifes.pop(rel, None)
            print("  + " + rel)

        for rel in buang:
            try:
                ftp.delete(f"{REMOTE}/{rel}")
                manifes.pop(rel, None)
                print("  - " + rel)
            except ftplib.error_perm as e:
                print("  ! gagal hapus " + rel + ": " + str(e))
    finally:
        # Ditulis walau di tengah jalan ada yang gagal, supaya yang sudah naik
        # tidak ikut dikirim ulang.
        manifes = {rel: v for rel, v in manifes.items() if rel in lokal}
        tulis_manifes(manifes)

    ftp.quit()
    print("selesai")


if __name__ == "__main__":
    mulai = time.time()
    main()
    print("%.1f detik" % (time.time() - mulai))
