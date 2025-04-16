<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Quên mật khẩu</title>
</head>
<body>
    <h2>Chào {{ $first_name . ' ' . $last_name }},</h2>

    <p>Bạn đã yêu cầu đặt lại mật khẩu. Nhấn vào liên kết sau:</p>

    <a href="https://translate.google.com">ĐẶT LẠI MẬT KHẨU TẠI ĐÂY</a>

    <P>Thời gian đặt lại mật khẩu của bạn có hạn tới {{ \Carbon\Carbon::parse($email_verified_at)->translatedFormat('Y-m-d H:i') }}</P>

    <p style="margin-top: 20px;">Cảm ơn bạn đã sử dụng dịch vụ Booking Sport!</p>
</body>
</html>
