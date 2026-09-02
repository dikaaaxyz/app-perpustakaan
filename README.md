# App Perpustakaan

Aplikasi manajemen perpustakaan berbasis web yang dibangun menggunakan framework **Laravel 12**.

## Tujuan

Project ini dikembangkan untuk memenuhi tugas praktikum pemgrograman web guna mempelajari dasar konfigurasi environment, manajemen database MySQL, struktur MVC, serta kontrol versi menggunakan Git & GitHub.

## Cara Menjalankan Project secara Lokal

1. Clone repository ini atau simpan di direktori `htdocs` XAMPP.
2. Pastikan service **Apache** dan **MySQL** aktif di XAMPP Control Panel.
3. Salin file `.env.example` menjadi `.env` lalu sesuaikan konfigurasi database (`DB_DATABASE=db_perpustakaan`).
4. Buka terminal di folder project, lalu jalankan perintah berikut:
    ```bash
    composer install
    php artisan key:generate
    php artisan migrate
    php artisan serve
    ```

---

### Tugas 1: Tambahkan Penjelasan MVC (Model, View, Controller)

Tugas ini meminta kamu menuliskan penjelasan singkat (2-3 kalimat) mengenai perbedaan Model, View, dan Controller. Kamu bisa menambahkannya di bagian bawah file `README.md` yang baru saja diedit tadi.

```markdown
## Konsep MVC (Model, View, Controller)

- **Model:** Berfungsi untuk mengelola logika data, aturan bisnis, serta berkomunikasi langsung dengan database.
- **View:** Berfungsi sebagai antarmuka atau tampilan visual yang dilihat langsung oleh pengguna di browser.
- **Controller:** Berfungsi sebagai penghubung (jembatan) antara Model dan View untuk memproses permintaan pengguna.
```

---

### Tugas Minggu 2 :

Dokumentasi untuk hasil screenshoot sebelum admin group dan selesai admin group

#### Sebelum

![Route List Sebelum diubah]
<img src="Minggu 2/sebelum.png">

#### Sesudah

![Route List Admin]
<img src="Minggu 2/sesudah.png">
