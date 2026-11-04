# Skenario Demo UAS - Aplikasi Perpustakaan Digital Kampus

Dokumen ini berisi panduan dan skenario langkah demi langkah untuk mendemokan aplikasi **Perpustakaan Digital Kampus** (Laravel 12) dalam rangka Ujian Akhir Semester (UAS).

---

## 1. Prasyarat & Persiapan Lingkungan

Pastikan database MySQL dan web server lokal sudah siap sebelum demo dimulai:

### A. Pastikan MySQL Aktif
Pastikan service **MySQL** di XAMPP Control Panel telah berjalan di port `3306`.

### B. Setup Database & Seeding
Jalankan perintah berikut untuk mereset dan mengisi database dengan data realistis:
```bash
php artisan migrate:fresh --seed
```
*Hasil seeding:*
- **Users (Petugas & Admin):**
  - Admin: `admin@pens.ac.id` | Password: `password` (Nama: Bambang Sutrisno)
  - Petugas 1: `petugas1@pens.ac.id` | Password: `password` (Nama: Siti Rahmawati)
  - Petugas 2: `petugas2@pens.ac.id` | Password: `password` (Nama: Ahmad Fauzi)
- **Kategori:** 5 kategori tetap (Novel, Komik, Sains, Teknologi, Sejarah).
- **Buku:** 20 buku dengan judul, penulis, dan penerbit Indonesia.
- **Anggota:** 15 anggota mahasiswa dengan email domain `@pens.ac.id`.
- **Peminjaman:** 10 transaksi peminjaman dengan variasi status (`dipinjam`, `dikembalikan`, `terlambat`).

### C. Jalankan Dua Server (Mencegah Deadlock Consume API)
Karena PHP built-in web server (`php artisan serve`) bekerja secara *single-threaded*, konsumsi API internal (`Http::get()`) dari controller membutuhkan server kedua:

1. **Terminal 1 (Server Utama untuk Browser & Postman):**
   ```bash
   php artisan serve --port=8000
   ```
2. **Terminal 2 (Server Khusus Panggilan API Internal):**
   ```bash
   php artisan serve --port=8011
   ```

---

## 2. Urutan Skenario Demo

### Langkah 1: Autentikasi / Login Petugas & Admin
1. Buka browser dan akses: `http://127.0.0.1:8000/login`
2. Masukkan kredensial admin:
   - **Email:** `admin@pens.ac.id`
   - **Password:** `password`
3. Klik tombol **Login**.
4. **Poin Penilaian:**
   - Redirect otomatis diarahkan ke halaman **Dashboard** (`/`), bukan lagi `/books`.
   - Muncul flash alert: `Login berhasil, selamat datang Bambang Sutrisno.`
   - Di pojok kanan navbar tampil nama dan role: `Bambang Sutrisno (Admin)`.

---

### Langkah 2: Tampilkan Dashboard (Konsumsi `GET /api/stats`)
1. Berada di halaman utama (`http://127.0.0.1:8000/`).
2. **Poin Penilaian:**
   - Tampil 3 kartu ringkasan statistik:
     - **Total Buku:** Menampilkan jumlah total buku (misal: 20).
     - **Total Anggota:** Menampilkan jumlah total anggota (misal: 15).
     - **Peminjaman Aktif:** Menampilkan jumlah transaksi berstatus `dipinjam`.
   - Terdapat tombol shortcut **"Lihat Laporan Peminjaman"**.
   - *Penjelasan Teknis:* Angka-angka ini diperoleh `DashboardController@index` melalui Laravel HTTP Client (`Http::get()`) ke endpoint internal `GET /api/stats` di port 8011.

---

### Langkah 3: Manajemen Kategori Buku (Khusus Admin)
1. Klik menu **Kategori** di navbar (`http://127.0.0.1:8000/categories`).
2. Klik tombol **+ Tambah Kategori**.
3. Masukkan data:
   - **Nama Kategori:** `Filsafat`
   - **Deskripsi:** `Buku kumpulan pemikiran, logika, dan etika manusia.`
4. Klik **Simpan**.
5. **Poin Penilaian:**
   - Muncul alert sukses penambahan kategori.
   - Kategori `Filsafat` tampil di tabel daftar kategori.
   - Menu kategori hanya dapat diakses oleh user ber-role `admin`.

---

### Langkah 4: Tambah Data Buku Baru & Cek Pagination Bersih
1. Klik menu **Buku** di navbar (`http://127.0.0.1:8000/books`).
2. Perhatikan pagination di bawah tabel:
   - Tampil pagination berupa link/teks rapi (`« Sebelumnya`, `1`, `2`, `Berikutnya »`) menggunakan view custom tanpa CSS Tailwind yang rusak.
3. Klik tombol **+ Tambah Buku**.
4. Isi form buku baru:
   - **Judul:** `Madilog`
   - **Penulis:** `Tan Malaka`
   - **Penerbit:** `Balai Pustaka`
   - **Tahun Terbit:** `1943`
   - **ISBN:** `9786020000001`
   - **Stok:** `7`
   - **Kategori:** Pilih `Filsafat`
5. Klik **Simpan**.
6. **Poin Penilaian:**
   - Buku berhasil disimpan dan data tampil di tabel buku.

---

### Langkah 5: Tambah Anggota Perpustakaan
1. Klik menu **Anggota** di navbar (`http://127.0.0.1:8000/members`).
2. Perhatikan daftar anggota hasil seeding: nama khas Indonesia dan email resmi mahasiswa berdomain `@pens.ac.id`.
3. Klik tombol **+ Tambah Anggota**.
4. Masukkan data anggota baru:
   - **Nama:** `Achmad Maulana`
   - **NIM:** `3125600099`
   - **Email:** `achmad.maulana99@pens.ac.id`
   - **Nomor Telepon:** `081234567899`
   - **Alamat:** `Jl. Raya ITS Sukolilo, Surabaya`
   - **Status:** `aktif`
5. Klik **Simpan**.
6. **Poin Penilaian:**
   - Anggota baru tersimpan dan validasi form berjalan dengan baik.

---

### Langkah 6: Buat Transaksi Peminjaman Buku
1. Klik menu **Peminjaman** di navbar (`http://127.0.0.1:8000/loans`).
2. Klik tombol **+ Tambah Peminjaman**.
3. Isi data transaksi:
   - **Anggota:** Pilih `Achmad Maulana`
   - **Tanggal Pinjam:** (Tanggal hari ini)
   - **Tanggal Kembali:** (7 hari ke depan)
   - **Pilih Buku:** Centang minimal satu buku (misal: `Madilog`).
4. Klik **Simpan Peminjaman**.
5. **Poin Penilaian:**
   - Transaksi peminjaman baru berhasil dibuat dengan status awal `Dipinjam` (badge kuning).
   - Petugas yang login (`Bambang Sutrisno`) otomatis dicatat sebagai petugas penanggung jawab (`user_id`).

---

### Langkah 7: Proses Pengembalian Buku
1. Pada tabel transaksi peminjaman (`/loans`), cari transaksi yang baru saja dibuat atau transaksi berstatus `Dipinjam`.
2. Klik tombol aksi **Kembalikan**.
3. **Poin Penilaian:**
   - Status peminjaman berubah menjadi `Dikembalikan` dengan badge hijau.
   - Kolom tanggal dikembalikan otomatis terisi tanggal hari ini.
   - Peminjaman aktif di Dashboard berkurang secara otomatis.

---

### Langkah 8: Tampilkan Halaman Laporan Peminjaman (Konsumsi `GET /api/loans`)
1. Klik menu **Laporan** di navbar (`http://127.0.0.1:8000/loans/report`).
2. **Poin Penilaian:**
   - Tampil tabel laporan seluruh transaksi peminjaman (anggota, petugas, daftar buku, tanggal pinjam, tanggal kembali, dan status).
   - Tampil kontrol navigasi pagination API (`« Sebelumnya`, `Halaman X dari Y`, `Selanjutnya »`).
   - *Penjelasan Teknis:* Halaman ini mengonsumsi data langsung dari endpoint internal `GET /api/loans` (port 8011) menggunakan `LoanController@report`, membuktikan konsumsi API paginated dalam Blade.
3. *(Uji Ketahanan / Fallback):* Jika server API (port 8011) dimatikan, halaman laporan tidak akan error 500 melainkan menampilkan tabel kosong secara aman berkat penanganan `try/catch (\Illuminate\Http\Client\ConnectionException $e)`.

---

### Langkah 9: Demo REST API di Postman / cURL

Buka Postman atau terminal cURL untuk menguji endpoint REST API secara independen:

*Header wajib untuk semua request:*
```http
Accept: application/json
```

#### 1. Endpoint Statistik Perpustakaan
- **Request:** `GET http://127.0.0.1:8000/api/stats`
- **Response:**
  ```json
  {
    "total_buku": 21,
    "total_anggota": 16,
    "peminjaman_aktif": 3
  }
  ```

#### 2. Endpoint Data Buku (Resource + Pagination)
- **Request:** `GET http://127.0.0.1:8000/api/books?page=1`
- **Response:**
  ```json
  {
    "data": [
      {
        "id": 1,
        "judul": "...",
        "penulis": "...",
        "penerbit": "...",
        "tahun_terbit": 2021,
        "isbn": "...",
        "stok": 5,
        "sampul": null,
        "category": {
          "id": 1,
          "nama_kategori": "Novel"
        }
      }
    ],
    "links": { ... },
    "meta": { ... }
  }
  ```

#### 3. Endpoint Data Anggota
- **Request:** `GET http://127.0.0.1:8000/api/members`
- **Response:**
  ```json
  {
    "data": [
      {
        "id": 1,
        "nama": "...",
        "nim": "...",
        "email": "...@pens.ac.id",
        "nomor_telepon": "08...",
        "alamat": "...",
        "status": "aktif"
      }
    ]
  }
  ```

#### 4. Endpoint Transaksi Peminjaman
- **Request:** `GET http://127.0.0.1:8000/api/loans`
- **Response:**
  ```json
  {
    "data": [
      {
        "id": 1,
        "member": { "id": 1, "nama": "..." },
        "petugas": { "id": 1, "name": "Bambang Sutrisno" },
        "tanggal_pinjam": "2026-10-25",
        "tanggal_kembali": "2026-11-01",
        "tanggal_dikembalikan": null,
        "status": "dipinjam",
        "books": [
          { "id": 1, "judul": "..." }
        ]
      }
    ]
  }
  ```

#### 5. Pengembalian Buku via API
- **Request:** `PUT http://127.0.0.1:8000/api/loans/1/kembalikan`
- **Response:**
  ```json
  {
    "data": {
      "id": 1,
      "status": "dikembalikan",
      "tanggal_dikembalikan": "2026-11-04"
    }
  }
  ```

---

## 3. Kesimpulan Demo

Aplikasi `app-perpustakaan` telah memenuhi seluruh capaian praktikum:
1. Arsitektur MVC terstruktur lengkap dengan relasi Eloquent (`One-to-Many`, `Many-to-Many`).
2. Autentikasi multi-role (Admin & Petugas) dengan middleware proteksi.
3. RESTful API lengkap dengan validasi dan API Resources.
4. Konsumsi API internal dari controller Blade menggunakan Laravel HTTP Client dengan penanganan fallback koneksi.
5. Seeding data realistis bergaya Indonesia dengan Factory & Seeder.
6. Tampilan antarmuka Blade yang bersih dan pagination kustom bebas glitch Tailwind.
