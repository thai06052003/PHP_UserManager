<?php
$routes['default_controller'] = 'homeController';

$routes['users'] = 'userController';

$routes['auth'] = 'authController';
$routes['auth/do-login'] = 'authController/handleLogin';
$routes['auth/do-register'] = 'authController/handleRegister';
$routes['auth/active-account'] = 'authController/showActive';
$routes['auth/resend-active'] = 'authController/resendActive';

// Đường dẫn ảo => đường dẫn thật

$user = 'dinhxuanthai';