# 🎓 Axiora - Academic Management REST API

> **Sistem manajemen akademik (LMS) modern berbasis REST API dengan arsitektur tangguh dan otorisasi multi-peran.**

REST API backend untuk aplikasi Axiora, dibangun menggunakan **Laravel 13** dan **PHP 8.4**. Sistem ini dirancang dengan prinsip *Repository Pattern* dan *Service Layer* untuk memastikan kode yang modular, dapat diuji, dan mudah dipelihara.

---

## 🚀 Fitur Utama

- **🛡️ Autentikasi Aman:** Menggunakan **Laravel Sanctum** untuk otentikasi berbasis *Bearer Token*.
- **👥 Role-based Access Control (RBAC):** Hierarki akses ketat untuk 3 entitas utama:
  - `Admin`: Akses penuh ke seluruh konfigurasi master data dan pengguna.
  - `Lecturer` (Dosen): Manajemen kelas, tugas, ujian, dan penilaian.
  - `Student` (Mahasiswa): Akses materi, pengumpulan tugas, dan partisipasi ujian.
- **📚 Manajemen Akademik Komprehensif:**
  - Struktur Organisasi: *Fakultas* & *Departemen*
  - Kurikulum: *Semester* & *Mata Kuliah*
  - Perkuliahan: *Kelas* & *Jadwal*
- **📝 Evaluasi Belajar:**
  - **Tugas (Assignments):** Pembuatan tugas terstruktur dengan unggahan lampiran multi-file (terintegrasi dengan **Cloudinary**), serta sistem pengumpulan dan penilaian.
  - **Ujian (Exams):** Penjadwalan dan manajemen ujian berbasis tenggat waktu.

---

## 🛠️ Tech Stack

| Kategori | Teknologi | Versi |
|---|---|---|
| **Core** | PHP | `^8.4` |
| **Framework** | Laravel | `^13.8` |
| **Database** | PostgreSQL | `Latest` |
| **Authentication**| Laravel Sanctum | `^4.0` |
| **File Storage** | Cloudinary PHP SDK | `^3.1` |
| **Testing** | Pest PHP | `^4.7` |
| **API Docs** | Scribe | `^5.11` |

---

## 🏗️ Arsitektur & Pola Desain

Aplikasi ini menerapkan standar *enterprise-level* Laravel:
1. **Repository Pattern:** Logika interaksi database dipisahkan ke dalam kelas-kelas *Repository* yang diikat melalui antarmuka (*Interface*), memudahkan *mocking* dan pertukaran *driver* database.
2. **Service Layer:** Semua logika bisnis (validasi kompleks, pemrosesan file, transaksi DB) diisolasi di dalam kelas *Service*. *Controller* dijaga agar tetap sangat tipis (hanya menangani *request/response*).
3. **ApiResponse Trait:** Makro untuk standarisasi format respons JSON API secara menyeluruh (menyediakan *blueprint* konsisten untuk format `success`, `message`, `data`, `errors`, `meta`).
4. **Form Requests:** Validasi input tersentralisasi menggunakan kelas *FormRequest* khusus.

---

## 📦 Skema Database (Migrasi)

- `users` — Data pengguna utama dengan kolom peran (admin, lecturer, student).
- `faculties` & `departments` — Hierarki tingkat fakultas dan program studi.
- `semesters` & `subjects` — Konfigurasi akademik dan mata kuliah.
- `classrooms` — Data kelas pembelajaran.
- `schedules` — Sinkronisasi jadwal antara mata kuliah, kelas, dan waktu.
- `assignments`, `assignment_modules`, & `assignment_submissions` — Ekosistem penugasan.
- `exams` & `exam_submissions` — Ekosistem evaluasi ujian.

---

## 💻 Panduan Instalasi & Setup

### Prasyarat
- PHP >= 8.4
- Composer
- PostgreSQL
- Akun Cloudinary (untuk *file upload*)

### Langkah Instalasi

1. **Clone Repositori**
   ```bash
   git clone <repo-url>
   cd laravel-api-axiora
   ```

2. **Instalasi Dependensi**
   ```bash
   composer install
   ```

3. **Konfigurasi Environment**
   ```bash
   cp .env.example .env
   ```
   *Buka file `.env` dan sesuaikan pengaturan koneksi database (PostgreSQL) serta kredensial API Cloudinary (`CLOUDINARY_URL`).*

4. **Generate Application Key**
   ```bash
   php artisan key:generate
   ```

5. **Migrasi & Seeding Database**
   Menyiapkan struktur tabel beserta data dummy default:
   ```bash
   php artisan migrate:fresh --seed
   ```

6. **Jalankan Development Server**
   ```bash
   php artisan serve
   ```
   *(Server akan berjalan di `http://127.0.0.1:8000`)*

> **Tips:** Gunakan perintah `composer dev` untuk menjalankan *serve*, *queue*, dan *Vite* secara paralel!

---

## 🌐 Format Respons API

Semua *endpoint* akan mengembalikan struktur JSON yang baku dan konsisten.

**Respons Berhasil (`200 OK` / `201 Created`):**
```json
{
  "success": true,
  "message": "Data berhasil diambil.",
  "data": { ... }
}
```

**Respons Error (Contoh: `422 Unprocessable Entity`):**
```json
{
  "success": false,
  "message": "Validasi gagal.",
  "errors": {
    "email": ["Email sudah digunakan."]
  }
}
```

**Respons dengan Pagination (`meta`):**
```json
{
  "success": true,
  "message": "Daftar pengguna",
  "data": [ ... ],
  "meta": {
    "current_page": 1,
    "per_page": 15,
    "total": 50,
    "last_page": 4
  }
}
```

---

## 🔐 Akun Demo / Seed Default

Gunakan kredensial berikut untuk menguji *endpoint* otentikasi (`/api/auth/login`). Password untuk semua akun default adalah **`password123`** atau **`password`**.

| Role | Nama / Jabatan | Email | Password |
|---|---|---|---|
| **Admin** | Administrator | `admin@axiora.com` | `password123` |
| **Lecturer** | Dosen Biasa | `teacher@axiora.com` | `password` |
| **Lecturer** | Ketua Program Studi | `teacher2@axiora.com` | `password` |
| **Lecturer** | Dekan Fakultas | `teacher3@axiora.com` | `password` |
| **Student** | Mahasiswa 1 | `student@axiora.com` | `password` |
| **Student** | Mahasiswa 2 | `student2@axiora.com` | `password` |

*(Tambahan Mahasiswa 3, 4, 5 juga tersedia dengan email `student3...5@axiora.com` dan password `password`)*
