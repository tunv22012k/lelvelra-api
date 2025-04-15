<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Đăng ký người dùng</title>
</head>
<body>
    <h2>Chào {{ $first_name . ' ' . $last_name }},</h2>
    <p>Bạn đã tạo tài khoản người dùng thành công!</p>

    <p><strong>Email:</strong> {{ $email }}</p>
    <p><strong>Mật khẩu:</strong> {{ $password }}</p>

    <p>Vui lòng nhấn vào link bên dưới để kích hoạt tài khoản:</p>
    <a href="https://translate.google.com">KÍCH HOẠT TÀI KHOẢN TẠI ĐÂY</a>

    <p style="margin-top: 20px;">Cảm ơn bạn đã sử dụng dịch vụ Booking Sport!</p>
</body>
</html>
