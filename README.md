# QLTN Backend Blueprint (Laravel API)

## Module đã tích hợp
- Authentication (Register/Login/Logout, JWT, role middleware)
- Quản lý khu trọ & phòng
- Quản lý khách thuê
- Quản lý hợp đồng (tạo, gia hạn, kết thúc, xem)
- Tạo hóa đơn (nhập điện nước + tính tiền)
- Thanh toán hóa đơn (upload proof, admin confirm, lịch sử)
- Yêu cầu bảo trì
- Thông báo
- Báo cáo dashboard (doanh thu, doanh thu theo tháng, tỉ lệ phòng trống)

## Tài liệu
- `docs/architecture.md`
- `docs/database-design.md`
- `docs/api-examples.md`
- `database/schema.sql`

## Chạy project
```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```
