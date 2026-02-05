# Delivery API (Admin Backend)

Dokumentasi singkat instalasi dan fitur.

## Fitur (fase 1 - Admin Backend)
- Authentication: Laravel Sanctum
- CRUD: Users (customer, mitra, driver), Mitra, Driver, Produk Mitra
- Order system: orders, order_items, distance_km, delivery_fee, admin_fee
- Payment QRIS (Duitku): create payment endpoint & callback handler
- Dashboard admin
- Settings: admin_fee, price_per_km, max_radius_km
- Push notification FCM (simpan token, kirim notif saat order masuk)
- WhatsApp notification (abstracted service)

## Setup singkat
1. Copy `.env.example` menjadi `.env` dan sesuaikan.
2. composer install
3. php artisan key:generate
4. Buat database dan sesuaikan `DB_*` di `.env`
5. php artisan migrate
6. (Optional) Seed Admin user: jalankan `php artisan db:seed --class=AdminUserSeeder` atau buat manual di DB atau factory.
7. Jalankan: php artisan serve

## Duitku QRIS
- Gunakan `PaymentService::createQrisPayment` untuk membuat request ke Duitku.
- Atur `DUITKU_*` di `.env` dan endpoint callback ke `/api/admin/payment/duitku/callback`.

## FCM
- Set `FCM_SERVER_KEY` di `.env`.
- Simpan token via `POST /api/admin/fcm/token` (authed).
- Gunakan `FcmService` untuk mengirim notifikasi.

## Wallet & Order Flow (production)
- Tabel `wallets` dan `wallet_histories` ditambahkan untuk menampung saldo dan transaksi.
- Setelah status `delivered` tercapai, sistem otomatis mendistribusikan earnings:
  - Mitra mendapat `subtotal` (masuk wallet mitra)
  - Admin mendapat `admin_fee` (masuk wallet user role=admin)
  - Driver mendapat `delivery_fee - admin_fee` (masuk wallet driver)
- Order flow: `pending` -> `paid` -> `accepted` -> `on_delivery` -> `delivered`.
- Driver assignment: otomatis mencari driver online terdekat menggunakan Haversine, atau admin dapat assign manual.

## Middleware & Role
- `RoleMiddleware` mendukung multiple roles: `role:admin,driver`.
- Daftarkan middleware di `app/Http/Kernel.php` jika belum:

```php
protected $routeMiddleware = [
    // ...
    'role' => \App\Http\Middleware\RoleMiddleware::class,
];
```

## Seed & Dummy Data
- Seeder `AdminUserSeeder` dan `DummyDataSeeder` disediakan.
- Jalankan: `php artisan db:seed --class=AdminUserSeeder` dan `php artisan db:seed --class=DummyDataSeeder`.

## Policies
- Terdapat `OrderPolicy` (app/Policies/OrderPolicy.php). Daftarkan di `app/Providers/AuthServiceProvider.php`:

```php
protected $policies = [
    \App\Models\Order::class => \App\Policies\OrderPolicy::class,
];
```

## Step-by-step install (update)
1. Copy `.env.example` -> `.env`, isi DB, DUITKU, FCM
2. composer install
3. composer require laravel/sanctum
4. php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
5. php artisan key:generate
6. php artisan migrate
7. php artisan db:seed --class=AdminUserSeeder
8. php artisan db:seed --class=DummyDataSeeder
9. php artisan serve

## ERD sederhana (text)
- users (id) -> mitras.user_id
- users (id) -> drivers.user_id
- mitras.id -> products.mitra_id
- users (id) -> orders.customer_id
- mitras.id -> orders.mitra_id
- drivers.id -> orders.driver_id
- orders.id -> order_items.order_id
- products.id -> order_items.product_id
- orders.id -> payments.order_id
- users.id -> fcm_tokens.user_id
- wallets.owner (morph) -> users (admin|mitra|driver|customer)
- wallets.id -> wallet_histories.wallet_id

---

## Setup Laravel Sanctum & Middleware
1. Install Sanctum: `composer require laravel/sanctum`.
2. Publish & migrate: `php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"` lalu `php artisan migrate`.
3. Di `app/Http/Kernel.php`, tambahkan middleware route: inside `$middlewareGroups['api']` tambahkan `'\Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class'` jika perlu.
4. Daftarkan `RoleMiddleware` di `app/Http/Kernel.php`:

```php
protected $routeMiddleware = [
    // ...
    'role' => \App\Http\Middleware\RoleMiddleware::class,
];
```

## Setup Duitku QRIS (ringkasan)
1. Daftar merchant di Duitku dan dapatkan `merchant_code` + `api_key` + `private_key`.
2. Set environment di `.env`:
```
DUITKU_MERCHANT_CODE=your_merchant_code
DUITKU_API_KEY=your_api_key
DUITKU_PRIVATE_KEY=your_private_key
DUITKU_CALLBACK_URL=https://yourdomain.com/api/admin/payment/duitku/callback
```
3. Implementasi: gunakan `PaymentService::createQrisPayment($orderId)` untuk membuat request, lalu simpan record di tabel `payments`.
4. Buat route callback publik: `POST /api/admin/payment/duitku/callback` yang menangani notifikasi pembayaran dari Duitku dan meng-update `payments` dan `orders`.

## Setup FCM
1. Buat project di Firebase Console -> Cloud Messaging -> Ambil Server Key.
2. Set `FCM_SERVER_KEY` di `.env`.
3. Simpan token perangkat via endpoint `POST /api/admin/fcm/token` (authed).
4. Gunakan `FcmService::sendToUser($userId, $title, $body)` untuk mengirim notifikasi.

---

Jika mau saya bisa tambahkan seeder `AdminUserSeeder`, unit tests, atau integrasi lebih lengkap untuk Duitku/FCM.
