<?php
$routes['default_controller'] = 'homeController';

$routes['users'] = 'userController';

$routes['auth'] = 'authController';
$routes['auth/do-login'] = 'authController/handleLogin';
$routes['auth/do-register'] = 'authController/handleRegister';
$routes['auth/active-account'] = 'authController/showActive';

// Đường dẫn ảo => đường dẫn thật

$user = 'dinhxuanthai';