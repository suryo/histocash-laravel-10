
# HistoCash Server (Laravel Backend)

HistoCash Server adalah backend sinkronisasi data untuk aplikasi HistoCash (Flutter). Server ini dikembangkan menggunakan Laravel dan mendukung prinsip **offline-first** dengan fitur **manual/auto sync** menggunakan API berbasis JWT Auth.

---

## 📦 Fitur Utama

- ✅ Autentikasi pengguna menggunakan JWT (`register`, `login`, `me`, `logout`)
- ✅ Sinkronisasi dua arah:
  - `POST /api/sync/upload` – kirim data dari lokal ke server
  - `GET /api/sync/download` – ambil data server ke lokal
- ✅ Ringkasan sinkronisasi via `GET /api/sync/summary`
- ✅ Aman dari duplikasi dengan `updateOrCreate`
- ✅ Mendukung soft-delete dengan `is_deleted`

---

## ⚙️ Instalasi

1. Clone repository:
   ```bash
   git clone https://github.com/your-username/histocash-server.git
   cd histocash-server
   ```

2. Install dependensi:
   ```bash
   composer install
   ```

3. Copy file `.env`:
   ```bash
   cp .env.example .env
   ```

4. Buat database dan sesuaikan konfigurasi di `.env`:
   ```
   DB_DATABASE=histocash
   DB_USERNAME=root
   DB_PASSWORD=yourpassword
   ```

5. Generate key:
   ```bash
   php artisan key:generate
   ```

6. Generate JWT secret:
   ```bash
   php artisan jwt:secret
   ```

7. Jalankan migrasi:
   ```bash
   php artisan migrate
   ```

---

## 🔐 Autentikasi

| Endpoint       | Method | Deskripsi             |
|----------------|--------|------------------------|
| `/api/register`| POST   | Daftar akun            |
| `/api/login`   | POST   | Login dan dapatkan token |
| `/api/me`      | GET    | Ambil data pengguna    |
| `/api/logout`  | POST   | Logout (revoke token)  |

---

## 🔄 API Sinkronisasi

### Upload (kirim data ke server)

```
POST /api/sync/upload
Authorization: Bearer <token>
Content-Type: application/json
```

Body (contoh data JSON):
```json
{
  "books": [...],
  "accounts": [...],
  "categories": [...],
  "transactions": [...]
}
```

- Setiap entitas wajib menyertakan: `id`, `updated_at`, dan `is_deleted`
- Duplikasi dicegah dengan `updateOrCreate` dan perbandingan `updated_at`

---

### Download (ambil data dari server)

```
GET /api/sync/download
Authorization: Bearer <token>
```

- Hanya mengembalikan data dengan `is_deleted = false`

---

### Summary (status sinkronisasi)

```
GET /api/sync/summary
Authorization: Bearer <token>
```

Hasil:
```json
{
  "total": { "books": 2, ... },
  "last_updated": { "books": "...", ... }
}
```

---

## 📦 Tabel Database

| Tabel        | Keterangan                      |
|--------------|----------------------------------|
| `users`      | Data user & login                |
| `books`      | Buku keuangan                    |
| `accounts`   | Akun keuangan (cash, bank, dll)  |
| `categories` | Kategori pemasukan/pengeluaran   |
| `transactions`| Transaksi (income, expense, transfer) |

---

## 📌 Catatan

- UUID digunakan sebagai primary key di semua entitas untuk menjamin sinkronisasi lintas perangkat
- Semua entitas mendukung `softDeletes` dan flag `is_deleted` untuk mengatur penghapusan aman
- Disarankan menambahkan cronjob backup server harian

---

## 🧑‍💻 Developer

- Dibuat oleh: Suryo (HistoCash Project)
- Framework: Laravel 10.x
- Auth: tymon/jwt-auth

---

## 📝 Lisensi

MIT License
