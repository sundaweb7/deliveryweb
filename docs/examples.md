# Contoh Request & Response

## Login Admin
POST /api/admin/login

Request:
{
  "email": "admin@example.com",
  "password": "password"
}

Response 200:
{
  "token": "plain_text_token",
  "user": {"id":1,"name":"Admin","email":"admin@example.com","role":"admin"}
}

---

## Create User (Admin)
POST /api/admin/users
Headers: Authorization: Bearer <token>

Request:
{
  "name":"John Doe",
  "email":"john@example.com",
  "phone":"081234567",
  "role":"customer",
  "password":"secret123"
}

Response 201:
{
  "id": 2,
  "name": "John Doe",
  "email": "john@example.com",
  "phone": "081234567",
  "role": "customer",
  "created_at": "2026-02-04T..."
}

---

## Create Order (Admin)
POST /api/admin/orders

Request:
{
  "customer_id": 2,
  "mitra_id": 1,
  "pickup_lat": -6.200000,
  "pickup_lng": 106.816666,
  "drop_lat": -6.210000,
  "drop_lng": 106.820000,
  "items": [ {"product_id": 1, "qty": 2} ]
}

Response 200:
{
  "id": 1,
  "order_no": "ORD1610000000",
  "customer_id": 2,
  "mitra_id": 1,
  "distance_km": 1.25,
  "delivery_fee": 3750,
  "admin_fee": 375,
  "subtotal": 50000,
  "status": "pending",
  "items": [ {"id":1,"product_id":1,"qty":2,"price":25000} ]
}

---

## Create Payment Duitku (Admin)
POST /api/admin/payments/duitku
{
  "order_id": 1
}

Response 200:
{
  "payment_id": 1,
  "pay_url": "https://duitku.example/qris/1"
}

---

## Payment Callback (Duitku -> Your App)
POST /api/admin/payment/duitku/callback
{
  "order_no":"ORD1610000000",
  "payment_id":"D123",
  "status":"PAID"
}

Response 200:
{
  "message":"ok"
}
