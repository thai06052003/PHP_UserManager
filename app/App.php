<?php
class App
{
    private $__controller, $__action, $__params, $__routes, $__db, $__request;

    static public $app;

    function __construct()
    {
        global $routes, $config;

        self::$app = $this;

        $this->__routes = new Route();

        if (!empty($routes['default_controller'])) {
            $this->__controller = $routes['default_controller'];
        }
        $this->__action = 'index';
        $this->__params = [];
        if (class_exists(class: 'DB')) {
            $dbObject = new DB();
            $this->__db = $dbObject->db;
        }

        
        $this->handleUrl();
        
    }
    //
    function getUrl()
    {
        if (!empty($_SERVER['PATH_INFO'])) $url = $_SERVER['PATH_INFO'];
        else $url = '/';
        return $url;
    }
    //
    public function handleUrl()
    {
        $url = $this->getUrl();
        $url = $this->__routes->handleRoute($url);

        // Middle App
        $this->handleGlobalMiddleWare($this->__db);
        $this->handleRouteMiddleWare($this->__routes->getUri(), $this->__db);

        // App Service Provider
        $this->handleAppServiceProvider($this->__db);

        $urlArr = array_filter(explode('/', $url));
        $urlArr = array_values($urlArr);

        $urlCheck = '';
        if (!empty($urlArr)) {
            foreach ($urlArr as $key => $item) {
                $urlCheck .= $item . '/';
                $fileCheck = rtrim($urlCheck, '/');
                $fileArr = explode('/', $fileCheck);
                $fileArr[count($fileArr) - 1] = ucfirst($fileArr[count($fileArr) - 1]);
                $fileCheck = implode('/', $fileArr);

                if (!empty($urlArr[$key - 1])) unset($urlArr[$key - 1]);

                if (file_exists('app/controllers/' . $fileCheck . '.php')) {
                    $urlCheck = $fileCheck;
                    break;
                }
            }

            $urlArr = array_values($urlArr);
        }
        // xử lý controller
        if (!empty($urlArr[0])) {
            $this->__controller = ucfirst($urlArr[0]);
        } else {
            $this->__controller = ucfirst($this->__controller);
        }

        // Xử lý khi $urlCheck rỗng
        if (empty($urlCheck)) $urlCheck = $this->__controller;

        if (file_exists('app/controllers/' . $urlCheck . '.php')) {
            require_once 'controllers/' . $urlCheck . '.php';

            // Kiểm tra class $this->controller tồn tại
            if (class_exists($this->__controller)) {
                $this->__controller = new $this->__controller;
                unset($urlArr[0]);
                if (!empty($this->__db)) {
                    $this->__controller->db = $this->__db;
                }
            } else {
                $this->loadError();
            }
        } else {
            $this->loadError();
        }

        // xử lý action
        if (!empty($urlArr[1])) {
            $this->__action = ucfirst($urlArr[1]);
            unset($urlArr[1]);
        }

        // xử lý param
        $this->__params = array_values($urlArr);
        // kiểm tra method tồn tại
        if (method_exists($this->__controller, $this->__action)) {
            call_user_func_array([$this->__controller, $this->__action], $this->__params);
        } else {
            $this->loadError();
        }
    }
    //
    public function getCurrentController() {
        return $this->__controller;
    }
    // Hiển thị lỗi 404
    public function loadError($name = '404', $data =[])
    {
        extract($data);
        require_once 'errors/' . $name . '.php';
    }
    public function handleRouteMiddleWare($routeKey, $db) {
        global $config;
        $routeKey = trim($routeKey);
        if (!empty($config['app']['routeMiddleWare'])) {
            $routeMiddleWareArr = $config['app']['routeMiddleWare'];
            foreach ($routeMiddleWareArr as $key=>$middleWareItem) {
                if ($routeKey==$key && file_exists('app/middleWares/'.$middleWareItem.'.php')) {
                    require_once 'app/middleWares/'.$middleWareItem.'.php';
                    if (class_exists($middleWareItem)) {
                        $middleWareObject = new $middleWareItem();
                        if (!empty($db)) {
                            $middleWareObject->db = $db;
                        }
                        $middleWareObject->handle();
                    }
                }
            }
        }
    }
    public function handleGlobalMiddleWare($db) {
        global $config;
        if (!empty($config['app']['globalMiddleWare'])) {
            $globalMiddleWareArr = $config['app']['globalMiddleWare'];
            foreach ($globalMiddleWareArr as $middleWareItem) {
                if (file_exists('app/middleWares/'.$middleWareItem.'.php')) {
                    require_once 'app/middleWares/'.$middleWareItem.'.php';
                    if (class_exists($middleWareItem)) {
                        $middleWareObject = new $middleWareItem();
                        if (!empty($db)) {
                            $middleWareObject->db = $db;
                        }
                        $middleWareObject->handle();
                    }
                }
            }
        }
    }
    public function handleAppServiceProvider($db) {
        global $config;
        if (!empty($config['app']['boot'])) {
            $serviceProviderArr = $config['app']['boot'];
            foreach ($serviceProviderArr as $serviceName) {
                if (file_exists('app/core/'.$serviceName.'.php')) {
                    require_once 'app/core/'.$serviceName.'.php';
                    if (class_exists($serviceName)) {
                        $serviceObject = new $serviceName();
                        if (!empty($db)) {
                            $serviceObject->db = $db;
                        }
                        $serviceObject->boot();
                    }
                }
            }
        }
    }
}
