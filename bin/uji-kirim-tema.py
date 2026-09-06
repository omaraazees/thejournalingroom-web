#!/usr/bin/env python3
"""Uji jebakan untuk bin/kirim-tema-ftp.py: dua berkas beda isi, sama panjang.

Kenapa uji ini ada. Skrip kirim dulu memutuskan sebuah berkas 'sama dengan yang
di server' cuma dari UKURAN. Perbaikan CSS satu karakter (`jarak-5` jadi
`jarak-2`) panjangnya persis sama, jadi perbaikannya diam-diam tidak pernah naik
dan orang mengira kodenya yang salah. Bug diam itu tidak akan ketahuan dari
membaca keluaran skrip, karena skripnya melaporkan 'sama'. Jadi harus diuji.

Ujinya tidak menyentuh server sungguhan. Ada FTP palsu di memori yang meniru
mlsd/MLST/STOR/RENAME/RETR, dan skrip kirim dijalankan apa adanya di atasnya.
Yang diuji logika perbandingannya, bukan jaringannya.

Versi lama diambil dari commit 29ed074 lewat `git show`, jadi kontrasnya nyata,
bukan tiruan.

  python3 bin/uji-kirim-tema.py
"""
import hashlib, importlib.util, io, json, pathlib, subprocess, sys, tempfile

AKAR = pathlib.Path(__file__).resolve().parent.parent
SEKARANG = AKAR / "bin" / "kirim-tema-ftp.py"
SEBELUM = "29ed074"  # commit terakhir sebelum perbandingan berbasis isi

# Tiga berkas. Yang tengah itu jebakannya: isinya beda, panjangnya sama persis.
DI_SERVER = {
    "style.css": b"body{margin:0}\n",
    "assets/rapi.css": b".kartu{gap:var(--jarak-5)}\n",
    # 'lama.css' ada di server tapi tidak ada di lokal
    "lama.css": b"/* sisa */\n",
}
DI_LOKAL = {
    "style.css": b"body{margin:0}\n",
    "assets/rapi.css": b".kartu{gap:var(--jarak-2)}\n",  # <- 5 jadi 2, panjang sama
    "baru.css": b".baru{}\n",
}
JEBAKAN = "assets/rapi.css"


class FtpPalsu:
    """FTP di memori. Cuma perintah yang dipakai skrip kirim."""

    def __init__(self, akar, isi):
        self.akar = akar
        self.berkas = {f"{akar}/{rel}": bytes(b) for rel, b in isi.items()}
        self.folder = set()
        self.waktu = {jalur: "20260101000000" for jalur in self.berkas}
        self.jam = 20260906120000

    # --- pembantu ---
    def _stempel_baru(self):
        self.jam += 1
        return str(self.jam)

    def _anak(self, jalur):
        depan = jalur.rstrip("/") + "/"
        langsung = {}
        for penuh in list(self.berkas) + sorted(self.folder):
            if not penuh.startswith(depan):
                continue
            sisa = penuh[len(depan):]
            nama = sisa.split("/")[0]
            if "/" in sisa:
                langsung[nama] = "dir"
            else:
                langsung[nama] = "dir" if penuh in self.folder else "file"
        return langsung

    # --- permukaan ftplib ---
    def mlsd(self, jalur):
        for nama, jenis in sorted(self._anak(jalur).items()):
            penuh = f"{jalur}/{nama}"
            if jenis == "file":
                yield nama, {"type": "file", "size": str(len(self.berkas[penuh])),
                             "modify": self.waktu[penuh]}
            else:
                yield nama, {"type": "dir"}

    def sendcmd(self, perintah):
        if perintah.startswith("MLST "):
            jalur = perintah[5:]
            if jalur not in self.berkas:
                raise __import__("ftplib").error_perm("550 tidak ada")
            return ("250-Start of list for %s\n"
                    " modify=%s;perm=adfrw;size=%d;type=file; %s\n"
                    "250 End of list") % (jalur, self.waktu[jalur],
                                          len(self.berkas[jalur]), jalur)
        raise __import__("ftplib").error_perm("500 tidak dimengerti")

    def mkd(self, jalur):
        self.folder.add(jalur.rstrip("/"))

    def storbinary(self, perintah, f):
        jalur = perintah[5:]
        self.berkas[jalur] = f.read()
        self.waktu[jalur] = self._stempel_baru()

    def retrbinary(self, perintah, tampung):
        jalur = perintah[5:]
        if jalur not in self.berkas:
            raise __import__("ftplib").error_perm("550 tidak ada")
        tampung(self.berkas[jalur])

    def delete(self, jalur):
        if jalur not in self.berkas:
            raise __import__("ftplib").error_perm("550 tidak ada")
        del self.berkas[jalur]
        self.waktu.pop(jalur, None)

    def rename(self, dari, ke):
        self.berkas[ke] = self.berkas.pop(dari)
        self.waktu[ke] = self.waktu.pop(dari)

    def quit(self):
        pass


def tulis_pohon(akar, isi):
    for rel, b in isi.items():
        p = akar / rel
        p.parent.mkdir(parents=True, exist_ok=True)
        p.write_bytes(b)


def muat(jalur_skrip, lokal, remote_akar, manifes):
    spec = importlib.util.spec_from_file_location("kirim_diuji_%d" % id(jalur_skrip), jalur_skrip)
    mod = importlib.util.module_from_spec(spec)
    spec.loader.exec_module(mod)
    mod.LOKAL = lokal
    mod.REMOTE = remote_akar
    mod.kredensial = lambda: ("host", "user", "sandi")
    if hasattr(mod, "MANIFES"):
        mod.MANIFES = manifes
    return mod


def jalankan(mod, ftp, argumen):
    mod.sambung = lambda *a, **k: ftp
    asli = sys.argv
    keluaran = io.StringIO()
    sys.argv, asli_stdout = ["kirim"] + argumen, sys.stdout
    sys.stdout = keluaran
    try:
        mod.main()
    finally:
        sys.argv, sys.stdout = asli, asli_stdout
    return keluaran.getvalue()


def periksa(nomor, judul, syarat, keterangan):
    tanda = "OK   " if syarat else "GAGAL"
    print("%s %d. %s" % (tanda, nomor, judul))
    if not syarat:
        print("      " + keterangan)
    return syarat


def main():
    assert len(DI_SERVER[JEBAKAN]) == len(DI_LOKAL[JEBAKAN]), "jebakan harus sama panjang"
    print("jebakan: %s, %d byte di server dan di lokal, isinya beda"
          % (JEBAKAN, len(DI_LOKAL[JEBAKAN])))
    print("         server %r" % DI_SERVER[JEBAKAN])
    print("         lokal  %r" % DI_LOKAL[JEBAKAN])
    print()

    lulus = []
    with tempfile.TemporaryDirectory() as td:
        td = pathlib.Path(td)
        lokal = td / "tema"
        tulis_pohon(lokal, DI_LOKAL)
        akar_remote = "/remote/tema"
        manifes = td / "manifes.json"

        # --- versi lama, dari git ---
        lama_py = td / "kirim-lama.py"
        lama_py.write_bytes(subprocess.check_output(
            ["git", "show", "%s:bin/kirim-tema-ftp.py" % SEBELUM], cwd=str(AKAR)))
        ftp_lama = FtpPalsu(akar_remote, DI_SERVER)
        keluaran_lama = jalankan(muat(lama_py, lokal, akar_remote, manifes), ftp_lama, [])
        print("--- versi lama (%s) ---" % SEBELUM)
        print(keluaran_lama.rstrip())
        print()
        lulus.append(periksa(
            1, "versi lama MELEWATI berkas jebakan (bug-nya terbukti ada)",
            ftp_lama.berkas["%s/%s" % (akar_remote, JEBAKAN)] == DI_SERVER[JEBAKAN]
            and "sama 2" in keluaran_lama,
            "versi lama ternyata mengirimnya, jebakannya tidak menjebak"))

        # --- versi sekarang ---
        ftp_baru = FtpPalsu(akar_remote, DI_SERVER)
        mod = muat(SEKARANG, lokal, akar_remote, manifes)
        rencana = jalankan(mod, ftp_baru, ["--coba"])
        print()
        print("--- versi sekarang, --coba ---")
        print(rencana.rstrip())
        print()
        lulus.append(periksa(
            2, "--coba menyebut berkas jebakan dan tidak mengirim apa pun",
            ("+ " + JEBAKAN) in rencana
            and ftp_baru.berkas["%s/%s" % (akar_remote, JEBAKAN)] == DI_SERVER[JEBAKAN],
            "--coba tidak jujur"))
        lulus.append(periksa(
            3, "--coba tidak menulis manifes",
            not manifes.exists(), "manifes ditulis padahal cuma coba"))

        kirim = jalankan(mod, ftp_baru, [])
        print("--- versi sekarang, kirim ---")
        print(kirim.rstrip())
        print()
        lulus.append(periksa(
            4, "berkas jebakan NAIK ke server dengan isi yang benar",
            ftp_baru.berkas["%s/%s" % (akar_remote, JEBAKAN)] == DI_LOKAL[JEBAKAN],
            "isi di server masih yang lama"))
        lulus.append(periksa(
            5, "berkas yang benar-benar sama tidak ikut dikirim",
            "sama 1" in kirim, "berkas identik ikut terkirim, boros"))
        lulus.append(periksa(
            6, "berkas baru ikut naik",
            ftp_baru.berkas["%s/baru.css" % akar_remote] == DI_LOKAL["baru.css"],
            "baru.css tidak naik"))

        ulang = jalankan(mod, ftp_baru, [])
        lulus.append(periksa(
            7, "jalan kedua tidak mengirim apa pun (manifes dipakai)",
            "kirim 0" in ulang, "masih mengirim padahal tidak ada yang berubah"))

        # Server diubah dari luar, panjangnya tetap sama. Stempel MLST harus menangkapnya.
        ftp_baru.berkas["%s/style.css" % akar_remote] = b"body{margin:9}\n"
        ftp_baru.waktu["%s/style.css" % akar_remote] = "20260907000000"
        luar = jalankan(mod, ftp_baru, ["--coba"])
        lulus.append(periksa(
            8, "perubahan di server dari luar skrip ini ikut tertangkap",
            "+ style.css" in luar, "manifes dipercaya buta, drift server lolos"))

        # Manifes bohong: isinya benar menurut catatan, tapi server sudah beda.
        # --teliti harus mengabaikan catatan dan mengunduh isi aslinya.
        ftp_baru.waktu["%s/style.css" % akar_remote] = json.loads(
            manifes.read_text())["berkas"]["style.css"]["modify"]
        tel = jalankan(mod, ftp_baru, ["--coba", "--teliti"])
        lulus.append(periksa(
            9, "--teliti menangkap isi server yang beda walau manifes bilang sama",
            "+ style.css" in tel, "--teliti masih percaya manifes"))

        # --hapus tetap jalan
        ftp_hapus = FtpPalsu(akar_remote, DI_SERVER)
        mod2 = muat(SEKARANG, lokal, akar_remote, td / "manifes2.json")
        h = jalankan(mod2, ftp_hapus, ["--hapus"])
        lulus.append(periksa(
            10, "--hapus membuang berkas server yang sudah tidak ada di lokal",
            "%s/lama.css" % akar_remote not in ftp_hapus.berkas and "- lama.css" in h,
            "lama.css masih di server"))

    print()
    print("%d dari %d lulus" % (sum(lulus), len(lulus)))
    return 0 if all(lulus) else 1


if __name__ == "__main__":
    sys.exit(main())
