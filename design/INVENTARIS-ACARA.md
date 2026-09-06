# Inventaris Acara — The Journaling Room

## Ringkasan Eksekutif

- **Total acara**: 16
- **Tampil di beranda**: 4
- **TIDAK tampil di beranda**: 12

---

## 1. Acara yang TAMPIL di Beranda (4 acara)

### Sesi Terdekat (1 acara — nearest upcoming)

| ID | Judul | Tanggal | Venue | Status ACF |
|---|---|---|---|---|
| 12 | Embracing Growth: A Full-day Journaling Activ | 2026-09-27 | Villa Pondok Joglo Y | 4/5 fields |

### Sesi Baru Lewat (3 acara — most recent past)

| ID | Judul | Tanggal | Venue | Status ACF |
|---|---|---|---|---|
| 107 | Writing Your Way Back to Yourself | 2026-08-30 | Snapobox | 2/5 fields |
| 106 | Write &#038; Reflect | 2026-08-29 | Pasar Jakal | 2/5 fields |
| 17 | Between the Pages | 2026-08-23 | Heejaz | 3/5 fields |

---

## 2. Acara yang TIDAK TAMPIL di Beranda (12 acara)

Ini adalah acara yang tidak muncul di halaman depan dan bisa dievaluasi untuk penghapusan:

| ID | Judul | Tanggal | Venue | Status |
|---|---|---|---|---|
| 16 | Wardah x The Journaling Room | 2026-11-08 | Copenhagen | publish |
| 15 | A Moment Between Chapters: 2026 Half-year Ref | 2026-10-18 | Kopi Kalandjana | publish |
| 14 | Journaling Playdate | 2026-10-04 | Kopi Kalandjana | publish |
| 13 | Much Between the Lines | 2026-09-28 | ROCCA Artotel | publish |
| 105 | About Myself | 2026-08-02 | AMCO Bakehouse | publish |
| 104 | TJR x Kolondjono | 2026-07-04 | Kolondjono | publish |
| 103 | TJR x Statement Beauty | 2026-06-18 | Statement Beauty | publish |
| 102 | TJR x Artotel | 2026-04-11 | Artotel | publish |
| 101 | TJR x Wardah | 2026-02-21 | Wardah | publish |
| 100 | TJR x Kupiku Coffee | 2026-01-11 | Kupiku Coffee | publish |
| 99 | TJR x Radian | 2025-11-30 | Radian | publish |
| 98 | TJR x Sunday Reads Club | 2025-11-09 | Sunday Reads Club | publish |

---

## 3. Status Field ACF Semua Acara

Tabel lengkap menunjukkan field mana yang terisi:

| ID | Judul | tanggal | venue_nama | venue_alamat | harga | catatan | isi_kit | gambaran |
|---|---|---|---|---|---|---|---|---|
| 107 | Writing Your Way Back to Yourself | ✓ | ✓ |  |  |  |  | 2/6 |
| 106 | Write &#038; Reflect | ✓ | ✓ |  |  |  |  | 2/6 |
| 105 | About Myself | ✓ | ✓ |  |  |  |  | 2/6 |
| 104 | TJR x Kolondjono | ✓ | ✓ |  |  |  |  | 2/6 |
| 103 | TJR x Statement Beauty | ✓ | ✓ |  |  |  |  | 2/6 |
| 102 | TJR x Artotel | ✓ | ✓ |  |  |  |  | 2/6 |
| 101 | TJR x Wardah | ✓ | ✓ |  |  |  |  | 2/6 |
| 100 | TJR x Kupiku Coffee | ✓ | ✓ |  |  |  |  | 2/6 |
| 99 | TJR x Radian | ✓ | ✓ |  |  |  |  | 2/6 |
| 98 | TJR x Sunday Reads Club | ✓ | ✓ |  |  |  |  | 2/6 |
| 17 | Between the Pages | ✓ | ✓ |  |  | ✓ | ✓ | 4/6 |
| 16 | Wardah x The Journaling Room | ✓ | ✓ |  | ✓ |  | ✓ | 4/6 |
| 15 | A Moment Between Chapters: 2026 Half-yea | ✓ | ✓ |  | ✓ | ✓ | ✓ | 5/6 |
| 14 | Journaling Playdate | ✓ | ✓ |  | ✓ | ✓ | ✓ | 5/6 |
| 13 | Much Between the Lines | ✓ | ✓ |  | ✓ |  | ✓ | 4/6 |
| 12 | Embracing Growth: A Full-day Journaling  | ✓ | ✓ |  | ✓ |  | ✓ | 4/6 |

---

## 4. Konfigurasi Beranda — Query Criteria Acara yang Ditampilkan

### Lokasi Kode

1. **Jadwal Sesi Terdekat** (Hero section + main schedule)
   - File: `wordpress/theme-v5/patterns/jadwal-sesi-terdekat.php`
   - Baris: 24
   - Query: `{"perPage":1, "postType":"acara", "order":"asc", "orderBy":"date"}`
   - Aturan: Menampilkan **1 acara** dengan tanggal terdekat yang masih di masa depan

2. **Sesi Baru Lewat** (Recently section)
   - File: `wordpress/theme-v5/patterns/jadwal-kartu.php`
   - Baris: 30
   - Query: `{"perPage":3, "postType":"acara", "order":"desc", "orderBy":"date"}`
   - Aturan: Menampilkan **3 acara** paling baru yang sudah lewat

### Data yang Disertakan di Query
- Semua acara status `publish` (hanya acara yang dipublikasikan)
- Diurutkan berdasarkan `tanggal_mulai` dari ACF field
- Filter otomatis: hanya acara dengan tanggal ≥ (untuk future) atau < (untuk past) dari hari ini

