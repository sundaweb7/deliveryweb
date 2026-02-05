Admin UI - Integrasi Theme & Setup

1) Dapatkan theme zip: `themeforest-aljLfaKX-gxon-hr-management-laravel-admin-dashboard-template.zip` dan unzip.
2) Salin folder assets (css, js, fonts, vendor) dari hasil unzip ke `public/vendor/theme/`.
   - Struktur: public/vendor/theme/Gxon_v1_22-Dec-2025/demo/public/assets/css/styles.css, public/vendor/theme/Gxon_v1_22-Dec-2025/demo/public/assets/js/main.js, dll.

**Catatan:** Saya sudah mengekstrak zip theme yang ada di workspace (`themeforest-aljLfaKX-gxon-hr-management-laravel-admin-dashboard-template.zip`) ke `public/vendor/theme/` dan menyalin `logoAd.png` serta `iconAD.png` ke `public/images/`.
- Layout Blade (`resources/views/admin/layouts/app.blade.php`) sudah diperbarui agar memuat `styles.css` dan `main.js` dari folder hasil ekstrak.
- Jika ingin menggunakan path berbeda, update link di `resources/views/admin/layouts/app.blade.php`.

3) Copy logo & icon ke `public/images/`:
   - Copy dari: `C:\xampp\htdocs\laravel\PROJEK APLIKASI\delivery\logoAd.png` -> `public/images/logoAd.png`
   - Copy dari: `C:\xampp\htdocs\laravel\PROJEK APLIKASI\delivery\iconAD.png` -> `public/images/iconAD.png`

4) Warna: saya sudah menambahkan `public/css/admin-custom.css` yang apply palette: PINK (#ff2d95), Blue (#007bff), White, Soft Pink.
   - Jika ingin ubah, edit `admin-custom.css` variabel di :root.

5) Pastikan `routes/web.php` telah berisi route admin (login/dashboard/users).
   - Login page: GET /admin/login
   - Admin dashboard: GET /admin (authed, role:admin)

6) Auth middleware: pastikan middleware `role` terdaftar di `app/Http/Kernel.php`:

protected $routeMiddleware = [
    // ...
    'role' => \App\Http\Middleware\RoleMiddleware::class,
];

7) Jika theme menggunakan npm build (Laravel Mix/Vite), jalankan:
   - npm install
   - npm run dev

8) Menjalankan web: `php artisan serve`, lalu akses http://localhost:8000/admin/login
   - Gunakan seeder admin: admin@example.com / password (jika sudah menjalankan seeder AdminUserSeeder)

9) Menyesuaikan menu & fungsionalitas:
   - List Users: /admin/users (menu sudah tersedia)
   - Tambah pages mitras/drivers/products/orders: buat controller dan view serupa (saya sudah menambahkan route/placeholder untuk pages tersebut)

Catatan teknis:
- Saya sudah mengekstrak file zip theme yang ada di workspace ke `public/vendor/theme/` dan menyesuaikan Blade. Jika Anda memiliki zip baru, cukup letakkan di root dan jalankan unzip manual atau hubungi saya untuk otomatisasi.
- Untuk memastikan semua menu dan ikon tampil: pastikan assets theme (css/js/fonts/icons) berada di `public/vendor/theme/Gxon_v1_22-Dec-2025/demo/public/assets/` dan `admin-custom.css` telah dimuat.

Verifikasi cepat (jalankan di root project):
```
php tools/verify-theme.php
```

Jika ada file yang "MISSING", salin file yang sesuai ke lokasi yang dinyatakan.

Butuh saya bantu lagi untuk: 1) menyesuaikan lebih jauh layout agar identik dengan demo theme, 2) menambahkan CRUD UI lengkap (mitra/products/orders) dengan DataTables, atau 3) membuat export/import data? Pilih salah satu.