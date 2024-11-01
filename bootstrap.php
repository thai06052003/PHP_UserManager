<?php
define('__DIR_ROOT', __DIR__);

// Xử lý http root
if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
    $link_web_root = 'https://' . $_SERVER['HTTP_HOST'];
} else {
    $link_web_root = 'http://' . $_SERVER['HTTP_HOST'];
}

$folder = str_replace('\\', '/', strtolower(__DIR_ROOT));
$folder = str_replace(strtolower($_SERVER['DOCUMENT_ROOT']), '', $folder);
$link_web_root = $link_web_root . $folder;
define('_WEB_ROOT', $link_web_root);

// Tự động load configs
$configs_dir = scandir('configs');
if (!empty($configs_dir)) {
    foreach ($configs_dir as $item) {
        if ($item != '.' && $item != '..' && file_exists('configs/' . $item)) {
            require_once './configs/' . $item;
        }
    }
}
// Load all Service
if (!empty($config['app']['service'])) {
    $allServices = $config['app']['service'];
    if (!empty($allServices)) {
        foreach ($allServices as $serviceName) {
            if (file_exists('app/core/'.$serviceName.'.php')) {
                require_once './app/core/'.$serviceName.'.php';
            }
        }
    }
}

// Load Lib
require_once './core/Hash.php';

// Load PHP Mailer
require_once './core/mailer/Exception.php';
require_once './core/mailer/PHPMailer.php';
require_once './core/mailer/SMTP.php';
require_once './core/Mail.php';

require_once './core/Paginate.php';

// Load Service provider Class
require_once './core/serviceProvider.php';

// Load View Class
require_once './core/View.php';

require_once './core/Load.php';
// Middleware
require_once './core/Middleware.php';

require_once './core/Route.php';  // Load Route
require_once './core/Session.php'; // Load Session

// Kiểm tra config và load Database
if (!empty($config['database'])) {
    $db_config = $config['database'];
    
    require_once './core/Connection.php';
    require_once './core/QueryBuilder.php';
    require_once './core/Database.php';
    require_once './core/DB.php';
}
// load core Helpers
require_once './core/Helper.php';
// load all helpers
$allHelpers = scandir('app/helpers');
if (!empty($allHelpers)) {
    foreach ($allHelpers as $item) {
        if ($item != '.' && $item != '..' && file_exists('app/helpers/' . $item)) {
            require_once './app/helpers/' . $item;
        }
    }
}

require_once './app/App.php'; // Load app

require_once './core/Model.php'; // Load Base Model
require_once './core/Template.php'; // Load Template Class
require_once './core/Controller.php';   // Load base Controller
require_once './core/Request.php';   // Load Request
require_once './core/Response.php';   // Load Response