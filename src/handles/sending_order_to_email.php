<?php
// Bản cũ không dùng Namespace, cứ require trực tiếp là xong
require_once '../../PHPMailer/class.phpmailer.php';
require_once '../../PHPMailer/class.smtp.php';

function sendConfirmationEmail($to_email, $name, $order_id, $total)
{
    // Khởi tạo kiểu cũ, không có "use PHPMailer..." gì hết
    $mail = new PHPMailer();

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'nguyentiendinh3322@gmail.com';
        $mail->Password = 'dehdljsrexhhqppy';
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;
        $mail->CharSet = 'UTF-8';

        $mail->setFrom('no-reply@rabu.com', 'RABU Store');
        $mail->addAddress($to_email, $name);

        $mail->isHTML(true);
        $mail->Subject = "Order Confirmed - #$order_id";
        $mail->Body = "<div style='max-width: 600px; margin: auto; border: 1px solid #eee; padding: 20px; font-family: sans-serif;'>
                <h2 style='text-align: center; color: #333;'>THANKS FOR YOUR ORDER!</h2>
                <p>Hi <b>$name</b>, we've received your order and are getting it ready for shipment.</p>
                <div style='background: #f9f9f9; padding: 15px; border-radius: 8px;'>
                    <p style='margin: 0;'><b>Order ID:</b> $order_id</p>
                    <p style='margin: 0;'><b>Total:</b> " . number_format($total, 0, ',', '.') . " ₫</p>
                </div>
                <p style='margin-top: 20px;'>You can track your order status at any time on our website.</p>
                <div style='text-align: center; margin-top: 20px;'>
                    <a href='http://localhost/web-ban-phim/src/index.php?page=order_status' 
                       style='background: #000; color: #fff; padding: 12px 25px; text-decoration: none; border-radius: 50px; display: inline-block;'>
                       Track My Order
                    </a>
                </div>
            </div>";

        $mail->send();
    } catch (Exception $e) {
        error_log("Mail Error: {$mail->ErrorInfo}");
    }
}

function sendWelcomeEmail($to_email, $name)
{
    $mail = new PHPMailer(); // Dùng PHPMailer() nếu ông dùng bản cũ v5.2
    try {
        // Cấu hình SMTP (Dùng chung cấu hình như hàm gửi đơn hàng)
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'nguyentiendinh3322@gmail.com';
        $mail->Password = 'dehdljsrexhhqppy';
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;
        $mail->CharSet = 'UTF-8';

        // Người gửi & Người nhận
        $mail->setFrom('no-reply@rabu.com', 'RABU Keyboard Store');
        $mail->addAddress($to_email, $name);

        // Nội dung Email
        $mail->isHTML(true);
        $mail->Subject = "Welcome to RABU Store - Let's build your dream keyboard!";

        $mail->Body = "
            <div style='max-width: 600px; margin: auto; border: 1px solid #eee; padding: 20px; font-family: sans-serif; border-radius: 15px;'>
                <div style='text-align: center;'>
                    <h2 style='color: #333; text-transform: uppercase;'>Welcome Abroad, $name!</h2>
                    <p style='color: #666;'>Your account has been successfully created.</p>
                </div>
                <hr style='border: 0; border-top: 1px solid #eee; margin: 20px 0;'>
                <p>Hi <b>$name</b>,</p>
                <p>Thank you for joining <b>RABU Keyboard Store</b>. We are excited to have you as a member of our mechanical keyboard community.</p>
                <p>Now you can log in to save your favorite items, track your orders, and get the latest updates on new arrivals.</p>
                
                <div style='text-align: center; margin: 30px 0;'>
                    <a href='http://localhost/web-ban-phim/index.php?page=login' 
                       style='background: #000; color: #fff; padding: 12px 30px; text-decoration: none; border-radius: 50px; font-weight: bold; display: inline-block;'>
                       START EXPLORING
                    </a>
                </div>
                
                <p style='font-size: 12px; color: #999; text-align: center;'>
                    If you didn't create this account, please ignore this email.
                </p>
            </div>
        ";

        $mail->send();
    } catch (Exception $e) {
        error_log("Welcome Mail Error: " . $mail->ErrorInfo);
    }
}