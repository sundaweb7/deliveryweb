# Tambahan Contoh Request/Response

## Wallet - Get Balance
GET /api/admin/mobile/wallet
Header: Authorization: Bearer <token>

Response 200:
{
  "balance": 125000,
  "histories":[{"id":1,"type":"credit","amount":100000,"reference":"order_subtotal_ORD..."}, ...]
}

---

## Admin assign driver
POST /api/admin/orders/{order}/assign-driver
Header: Authorization: Bearer <admin_token>
Request: {"driver_id":3}

Response 200:
{ "message":"driver assigned","order":{...} }

---

## Payment Callback (Duitku)
POST /api/admin/payment/duitku/callback
Header: X-Duitku-Signature: <hmac>
Body:
{
  "order_no":"ORD1610...",
  "payment_id":"D123",
  "status":"PAID"
}

Response 200:
{ "message":"ok" }
