#!/usr/bin/env python3
"""Tulis field acara lewat WP REST, dengan gerbang nilai yang sungguhan.

Kenapa skrip ini ada. Enam kali malam 7 Sep 2026 saya menulis ke post acara
dengan pola baca, susun, kirim, lalu diff. Diff sesudah menulis itu DETEKTOR,
bukan gerbang: dia memberitahu apa yang terjadi, dia tidak menghentikan apa pun.

Kalimat Oscar yang dipakai lantai: gerbang yang cuma mengenali nilai yang kamu
HARAPKAN bukan gerbang, itu cuma penghindar pekerjaan ganda. Gerbang sungguhan
menolak SEMUA yang tidak dikenal, bukan cuma melewati yang sudah benar.

Bahayanya nyata khusus untuk ACF: REST menolak kiriman sebagian, jadi seluruh
objek `acf` harus dikirim ulang. Kalau bacaan kita basi, kita diam diam
MENGEMBALIKAN perubahan orang lain di field LAIN, bukan cuma di field yang kita
sentuh.

Urutannya:
  1. BACA baseline
  2. susun payload dari baseline
  3. BACA ULANG, bandingkan dengan baseline. Ada yang beda, BERHENTI.
  4. kirim
  5. BACA lagi, cetak diff terhadap baseline

Contoh:
  python3 bin/tulis-acara.py 12 --set acf.harga=265000
  python3 bin/tulis-acara.py 12 --set title='Judul baru'
  python3 bin/tulis-acara.py 12 --set 'acf.isi_kit=["A","B"]' --coba
"""
import json, os, sys, urllib.request, base64

# Field yang berubah sendiri tiap penyimpanan. Bukan isi, jadi tidak dipakai
# sebagai alasan berhenti. `modified` tetap DILAPORKAN karena dia tripwire
# termurah kalau ada orang lain menyentuh post yang sama.
ABAIKAN = {"_links", "generated_slug", "permalink_template", "modified", "modified_gmt"}


def kredensial():
    jalur = os.path.expanduser("~/.tjr-wp")
    nilai = {}
    with open(jalur) as f:
        for baris in f:
            baris = baris.strip()
            if "=" in baris and not baris.startswith("#"):
                k, v = baris.split("=", 1)
                nilai[k.strip()] = v.strip().strip("'\"")
    return nilai["WP_URL"], nilai["WP_USER"], nilai["WP_APP_PASSWORD"]


def panggil(url, user, sandi, data=None):
    rq = urllib.request.Request(url, method="POST" if data else "GET")
    sandi_b64 = base64.b64encode(f"{user}:{sandi}".encode()).decode()
    rq.add_header("Authorization", "Basic " + sandi_b64)
    if data is not None:
        rq.add_header("Content-Type", "application/json")
        data = json.dumps(data, ensure_ascii=False).encode()
    with urllib.request.urlopen(rq, data, timeout=60) as r:
        return json.loads(r.read().decode())


def ratakan(o, jalur="", keluar=None):
    """Objek bersarang jadi peta datar, supaya bisa dibandingkan per field."""
    keluar = {} if keluar is None else keluar
    if isinstance(o, dict):
        for k, v in o.items():
            ratakan(v, f"{jalur}.{k}" if jalur else k, keluar)
    elif isinstance(o, list):
        keluar[jalur] = json.dumps(o, ensure_ascii=False)
    else:
        keluar[jalur] = o
    return keluar


def bandingkan(a, b):
    ra, rb = ratakan(a), ratakan(b)
    beda = []
    for k in sorted(set(ra) | set(rb)):
        if k.split(".")[0] in ABAIKAN or k in ABAIKAN:
            continue
        if ra.get(k) != rb.get(k):
            beda.append((k, ra.get(k), rb.get(k)))
    return beda


def urai_nilai(teks):
    try:
        return json.loads(teks)
    except ValueError:
        return teks


def main():
    argv = sys.argv[1:]
    if not argv or argv[0] in ("-h", "--help"):
        print(__doc__)
        return 0
    pid = argv[0]
    coba = "--coba" in argv
    ubah = {}
    i = 1
    while i < len(argv):
        if argv[i] == "--set":
            jalur, _, nilai = argv[i + 1].partition("=")
            ubah[jalur] = urai_nilai(nilai)
            i += 2
        else:
            i += 1
    if not ubah:
        sys.exit("Tidak ada --set. Lihat --help.")

    url, user, sandi = kredensial()
    endpoint = f"{url}/wp-json/wp/v2/acara/{pid}?context=edit"

    # 1. baseline
    dasar = panggil(endpoint, user, sandi)
    print(f"baseline dibaca, modified {dasar.get('modified')}")

    # 2. payload
    muatan = {}
    for jalur, nilai in ubah.items():
        if jalur.startswith("acf."):
            # ACF menolak kiriman sebagian, jadi seluruh objeknya dikirim ulang.
            muatan.setdefault("acf", dict(dasar["acf"]))[jalur[4:]] = nilai
        else:
            muatan[jalur] = nilai
        print(f"  akan menulis {jalur} = {json.dumps(nilai, ensure_ascii=False)[:70]}")

    # 3. GERBANG: baca ulang, tolak apa pun yang bukan keadaan yang jadi dasar rencana
    ulang = panggil(endpoint, user, sandi)
    geser = bandingkan(dasar, ulang)
    if geser:
        print("\nBERHENTI. Post berubah antara bacaan dan penulisan:")
        for k, lama, baru in geser:
            print(f"  {k}: {lama!r} -> {baru!r}")
        print("Cari tahu siapa yang menyentuhnya sebelum menulis apa pun.")
        return 2
    if dasar.get("modified") != ulang.get("modified"):
        print(f"  catatan: modified bergeser ke {ulang.get('modified')} tapi nol field isi berubah")
    print("gerbang lolos, isi post sama dengan waktu rencana disusun")

    if coba:
        print("--coba, nol dikirim")
        return 0

    # 4. kirim
    hasil = panggil(endpoint, user, sandi, muatan)

    # 5. diff terhadap baseline
    beda = bandingkan(dasar, hasil)
    print(f"\nselesai. field isi yang berubah: {len(beda)}")
    for k, lama, baru in beda:
        print(f"  {k}: {json.dumps(lama, ensure_ascii=False)[:60]} -> {json.dumps(baru, ensure_ascii=False)[:60]}")
    return 0


if __name__ == "__main__":
    sys.exit(main())
