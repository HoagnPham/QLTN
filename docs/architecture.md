# Kiến trúc tổng thể hệ thống quản lý khu trọ

## 1. Kiến trúc project (3 tầng)

### Presentation Layer
- Flutter App (Admin/Tenant)
- Gọi REST API qua Dio
- State management: Riverpod/BLoC

### Application Layer (Laravel API)
- Controllers: nhận/trả request
- Requests: validation
- Services: business logic (Auth/JWT)
- Middleware: JWT + Role (admin/tenant)
- Modules: Auth, Hostel/Room, Tenant, Contract, Invoice, Payment, Maintenance, Notification, Report

### Data Layer
- MySQL
- Eloquent models + migrations + SQL schema
- FK/index đảm bảo toàn vẹn

## 2. Tích hợp module

- Auth cấp JWT cho mọi module.
- Contract liên kết Room + Tenant, ràng buộc 1 phòng chỉ có 1 hợp đồng active.
- Invoice sinh theo Contract với công thức tiền điện/nước/dịch vụ.
- Payment ghi nhận chứng từ thanh toán, Admin xác nhận `paid/rejected`.
- Maintenance xử lý workflow `pending -> processing -> done`.
- Notification gửi từ Admin tới nhiều Tenant.
- Report tổng hợp doanh thu + tỉ lệ phòng trống cho dashboard.

## 3. Flow hoạt động chính

1. Admin tạo hostel/room.
2. Admin tạo tenant và contract active.
3. Mỗi tháng admin nhập chỉ số điện nước để tạo invoice.
4. Tenant upload bằng chứng thanh toán.
5. Admin xác nhận thanh toán.
6. Dashboard cập nhật doanh thu theo dữ liệu invoice paid.
7. Tenant gửi maintenance request, admin cập nhật trạng thái.

## 4. API structure
- Prefix: `/api/v1`
- Public: `/auth/register`, `/auth/login`
- Admin APIs:
  - `/hostels`, `/rooms`, `/tenants`
  - `/contracts`, `/contracts/{id}/renew`, `/contracts/{id}/terminate`
  - `/invoices`
  - `/payments/{id}/confirm`, `/payments/history`
  - `/maintenance-requests` (list/update)
  - `/notifications/send`
  - `/reports/dashboard`
- Tenant APIs:
  - `/payments/upload-proof`
  - `/maintenance-requests` (create)
  - `/notifications/me`

## 5. Hướng dẫn chạy project

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```

> Nếu chưa có Laravel full skeleton trong môi trường hiện tại, dùng repo này như blueprint code + schema để tích hợp vào project Laravel chính thức.
