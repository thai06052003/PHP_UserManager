<?php
$routes['default_controller'] = 'homeController';

$routes['users'] = 'userController';
// Đăng nhập
$routes['auth'] = 'authController';
// Xử lí đăng nhập
$routes['auth/do-login'] = 'authController/handleLogin';
// Xử lí đăng ký
$routes['auth/do-register'] = 'authController/handleRegister';
// Hiển thị trang kích hoạt
$routes['auth/active-account'] = 'authController/showActive';
// Xử lí gửi lại email active account
$routes['auth/resend-active'] = 'authController/resendActive';
// Hiển thị trang quên mật khẩu
$routes['auth/forgot-password'] = 'authController/forgotPassword';
// Xử lí yêu cầu lấy lại mật khẩu
$routes['auth/do-forgot-password'] = 'authController/handleForgotPassword';
//
$routes['auth/reset'] = 'authController/resetPassword';
//
$routes['auth/update-password'] = 'authController/updatePassword';