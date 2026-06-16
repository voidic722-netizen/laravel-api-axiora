# Axiora - Academic Management REST API

REST API untuk manajemen akademik berbasis Laravel 13 dengan sistem role-based access control (Admin, Lecturer, Student).

## Fitur

- **Autentikasi** — Login/logout menggunakan Laravel Sanctum (Bearer token)
- **Manajemen Fakultas & Departemen** — CRUD dengan relasi berjenjang
- **Manajemen Semester & Mata Kuliah** — Data akademik dasar
- **Manajemen Kelas** — CRUD kelas
- **Manajemen Jadwal** — Penjadwalan perkuliahan
- **Manajemen Tugas (Assignment)** — Buat, kumpulkan, dan nilai tugas dengan unggahan file via Cloudinary
- **Manajemen Ujian (Exam)** — Buat, kumpulkan, dan nilai ujian
- **Role-based Access** — Tiga role: `admin`, `lecturer`, `student`

## Tech Stack

| Teknologi | Versi |
|-----------|-------|
| PHP | ^8.4 |
| Laravel | ^13.8 |
| PostgreSQL | - |
| Sanctum | ^4.0 |
| Cloudinary PHP SDK | ^3.1 |
| Pest | ^4.7 |
| Scribe (API docs) | ^5.11 |

## Arsitektur

- **Repository Pattern** — Pemisahan logika akses data via interface dan repository
- **Service Layer** — Logika bisnis dipisahkan ke service class
- **ApiResponse Trait** — Response JSON konsisten (`success`, `message`, `data`, `errors`, `meta`)
- **Role Middleware** — Middleware kustom `role:admin,lecturer` untuk otorisasi

## Struktur Database

Migrasi tersedia untuk tabel-tabel berikut:

- `faculties` — Fakultas
- `departments` — Departemen (berelasi ke fakultas)
- `semesters` — Semester
- `subjects` — Mata kuliah
- `classrooms` — Kelas
- `users` — User dengan role, relasi ke fakultas/departemen/kelas/mata kuliah
- `schedules` — Jadwal perkuliahan
- `assignments` / `assignment_modules` / `assignment_submissions` — Tugas dan pengumpulan
- `exams` / `exam_submissions` — Ujian dan pengumpulan

## API Endpoints

Semua endpoint (kecuali login) membutuhkan header `Authorization: Bearer {token}`.

### Public

| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| POST | `/api/auth/login` | Login user |

### Auth

| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| POST | `/api/auth/logout` | Logout |
| GET | `/api/auth/me` | Profile user saat ini |
| PATCH | `/api/auth/profile` | Update profile |

### Users (Admin)

| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| GET | `/api/users` | List users (admin/lecturer) |
| POST | `/api/users` | Create user |
| PUT | `/api/users/{user}` | Update user |
| DELETE | `/api/users/{user}` | Delete user |

### Faculties

| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| GET | `/api/faculties` | List faculties |
| GET | `/api/faculties/available-for-dean` | Faculties tanpa dean |
| GET | `/api/faculties/{faculty}` | Detail faculty |
| POST | `/api/faculties` | Create faculty (admin) |
| PUT | `/api/faculties/{faculty}` | Update faculty (admin) |
| DELETE | `/api/faculties/{faculty}` | Delete faculty (admin) |

### Departments

| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| GET | `/api/departments` | List departments |
| GET | `/api/departments/{department}` | Detail department |
| POST | `/api/departments` | Create department (admin) |
| PUT | `/api/departments/{department}` | Update department (admin) |
| DELETE | `/api/departments/{department}` | Delete department (admin) |

### Semesters

| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| GET | `/api/semesters` | List semesters |
| POST | `/api/semesters` | Create semester (admin) |
| PUT | `/api/semesters/{semester}` | Update semester (admin) |
| DELETE | `/api/semesters/{semester}` | Delete semester (admin) |

### Subjects

| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| GET | `/api/subjects` | List subjects |
| GET | `/api/subjects/{subject}` | Detail subject |
| POST | `/api/subjects` | Create subject (admin) |
| PUT | `/api/subjects/{subject}` | Update subject (admin) |
| DELETE | `/api/subjects/{subject}` | Delete subject (admin) |

### Classrooms

| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| GET | `/api/classrooms` | List classrooms |
| GET | `/api/classrooms/{classroom}` | Detail classroom |
| POST | `/api/classrooms` | Create classroom (admin) |
| PUT | `/api/classrooms/{classroom}` | Update classroom (admin) |
| DELETE | `/api/classrooms/{classroom}` | Delete classroom (admin) |

### Schedules

| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| GET | `/api/schedules` | List schedules |
| POST | `/api/schedules` | Create schedule (admin/lecturer) |
| PUT | `/api/schedules/{schedule}` | Update schedule (admin/lecturer) |
| DELETE | `/api/schedules/{schedule}` | Delete schedule (admin/lecturer) |

### Assignments

| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| GET | `/api/assignments` | List assignments |
| GET | `/api/assignments/{assignment}` | Detail assignment |
| POST | `/api/assignments` | Create assignment (admin/lecturer) |
| PUT | `/api/assignments/{assignment}` | Update assignment (admin/lecturer) |
| DELETE | `/api/assignments/{assignment}` | Delete assignment (admin/lecturer) |
| DELETE | `/api/assignment-modules/{module}` | Delete module file (admin/lecturer) |
| GET | `/api/assignments/{assignment}/submissions` | Lihat submissions (admin/lecturer) |
| POST | `/api/assignment-submissions/{submission}/grade` | Beri nilai (admin/lecturer) |
| POST | `/api/assignments/{assignment}/submit` | Kumpulkan tugas (student) |
| GET | `/api/assignments/{assignment}/my-submission` | Lihat submission sendiri (student) |

### Exams

| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| GET | `/api/exams` | List exams |
| GET | `/api/exams/{exam}` | Detail exam |
| POST | `/api/exams` | Create exam (admin/lecturer) |
| PUT | `/api/exams/{exam}` | Update exam (admin/lecturer) |
| DELETE | `/api/exams/{exam}` | Delete exam (admin/lecturer) |
| GET | `/api/exams/{exam}/submissions` | Lihat submissions (admin/lecturer) |
| POST | `/api/exams/{exam}/submit` | Kumpulkan ujian (student) |
| GET | `/api/exams/{exam}/my-submission` | Lihat submission sendiri (student) |

## Instalasi

```bash
# Clone repositori
git clone <repo-url>
cd laravel-api-axiora-master

# Install dependensi PHP
composer install

# Copy environment
cp .env.example .env
# Edit .env sesuai konfigurasi database PostgreSQL dan Cloudinary

# Generate key
php artisan key:generate

# Jalankan migrasi
php artisan migrate

# (Opsional) Generate API docs
php artisan scribe:generate

# Jalankan development server
php artisan serve
```

Atau gunakan script `setup`:

```bash
composer setup
```

## Menjalankan Dev Server

```bash
composer dev
```

Perintah di atas menjalankan secara bersamaan:
- `php artisan serve`
- `php artisan queue:listen`
- `php artisan pail` (log viewer)
- `npm run dev` (Vite)

## Testing

```bash
composer test
```

## Format Response

Semua response mengikuti format standar:

```json
{
  "success": true,
  "message": "",
  "data": {}
}
```

Untuk error:
```json
{
  "success": false,
  "message": "Error message",
  "errors": {}
}
```

Untuk pagination, ditambahkan key `meta`:
```json
{
  "success": true,
  "message": "",
  "data": [],
  "meta": {
    "current_page": 1,
    "per_page": 15,
    "total": 100
  }
}
```
