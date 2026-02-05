# Mobile API - Contoh Request & Response

## Register (Customer)
POST /api/admin/mobile/register

Request:
{
  "name":"Budi",
  "phone":"081234567",
  "password":"secret"
}

Response 200:
{
  "token":"plain_text_token",
  "user":{...}
}

---

## List Mitra Nearby
GET /api/admin/mobile/mitras/nearby?lat=-6.200000&lng=106.816666&radius=5
Header: Authorization: Bearer <token>

Response 200:
[
  {"id":1,"name":"Mitra A","distance_km":0.5, ...}
]

---

## Create Order (Customer)
POST /api/admin/mobile/orders
Header: Authorization: Bearer <token>
Request:
{
  "mitra_id":1,
  "pickup_lat":-6.200000,
  "pickup_lng":106.816666,
  "drop_lat":-6.210000,
  "drop_lng":106.820000,
  "items":[{"product_id":1,"qty":2}]
}

Response 200:
{
  "id": 1,
  "order_no":"ORD1610...",
  "status":"pending",
  "subtotal":50000,
  "delivery_fee":3750,
  "admin_fee":375,
  "items":[...]
}

---

## Driver go Online
POST /api/admin/mobile/driver/online
Header: Authorization: Bearer <driver_token>
Request: {"is_online":true,"lat":-6.2010,"lng":106.8168}

Response 200:
{ "message":"status updated","driver":{...} }

---

## Driver Accept Order
POST /api/admin/mobile/driver/orders/1/accept
Header: Authorization: Bearer <driver_token>

Response 200:
{ "message":"Order accepted","order":{...} }
