# API Documentation (v1)

## 1) Authentication
### Register
`POST /api/v1/auth/register`
```json
{
  "name": "Admin A",
  "email": "admin@hostel.vn",
  "password": "Password@123",
  "password_confirmation": "Password@123",
  "role": "admin"
}
```

### Login
`POST /api/v1/auth/login`
```json
{ "email": "admin@hostel.vn", "password": "Password@123" }
```

## 2) Contract module
### Tạo hợp đồng
`POST /api/v1/contracts`
```json
{
  "tenant_id": 5,
  "room_id": 10,
  "start_date": "2026-01-01",
  "end_date": "2026-12-31",
  "deposit_amount": 3000000,
  "monthly_rent": 3500000,
  "terms": "Điện nước tính riêng"
}
```
Business rule: mỗi phòng chỉ được có 1 hợp đồng `active`.

### Gia hạn hợp đồng
`PATCH /api/v1/contracts/12/renew`
```json
{ "end_date": "2027-12-31", "monthly_rent": 3700000 }
```

### Kết thúc hợp đồng
`PATCH /api/v1/contracts/12/terminate`

## 3) Invoice module
### Tạo hóa đơn
`POST /api/v1/invoices`
```json
{
  "contract_id": 12,
  "billing_month": "2026-03",
  "due_date": "2026-03-10",
  "rent": 3500000,
  "electricity_previous": 1200,
  "electricity_current": 1300,
  "electricity_price": 3500,
  "water_previous": 250,
  "water_current": 260,
  "water_price": 15000,
  "service_fee": 200000
}
```
Formula:
`total = rent + electricityUsage * electricityPrice + waterUsage * waterPrice + serviceFee`

### Xem hóa đơn
`GET /api/v1/invoices/{id}`

## 4) Payment module
### Tenant upload bill/proof
`POST /api/v1/payments/upload-proof`
```json
{
  "invoice_id": 120,
  "tenant_id": 5,
  "proof_image_url": "https://cdn.domain/proofs/p120.jpg",
  "amount": 4400000,
  "note": "CK Vietcombank"
}
```

### Admin confirm payment
`PATCH /api/v1/payments/{id}/confirm`
```json
{ "status": "paid", "note": "Đã đối soát" }
```

### Payment history
`GET /api/v1/payments/history`

## 5) Maintenance module
### Tenant tạo yêu cầu
`POST /api/v1/maintenance-requests`
```json
{ "room_id": 10, "tenant_id": 5, "title": "Hỏng máy lạnh", "description": "Không lạnh" }
```

### Admin xem yêu cầu
`GET /api/v1/maintenance-requests`

### Admin cập nhật trạng thái
`PATCH /api/v1/maintenance-requests/{id}/status`
```json
{ "status": "processing" }
```

## 6) Notification & Report
### Admin gửi thông báo
`POST /api/v1/notifications/send`
```json
{
  "user_ids": [2, 3, 4],
  "title": "Thông báo thu tiền phòng",
  "message": "Vui lòng thanh toán trước ngày 10 hàng tháng",
  "type": "billing"
}
```

### Tenant nhận thông báo của mình
`GET /api/v1/notifications/me`

### Dashboard báo cáo
`GET /api/v1/reports/dashboard`
Response gồm:
- `total_revenue`
- `monthly_revenue`
- `vacancy_rate`
- `total_rooms`
- `available_rooms`
