<?php
class Route {
    private $__keyRoute = null;
    //
    public function handleRoute($url) {
        global $routes;
        unset($routes['default_controller']);
        

        $url = trim($url, '/');
        
        if (empty($url)) $url = '/';

        $handleUrl = $url;

        if (!empty($routes)) {
            foreach($routes as $key=>$value) {
                if (preg_match('~^'.$key.'$~is', $url)) {
                    $handleUrl = preg_replace('~'.$key.'~is', $value, $url);
                    $this->__keyRoute = $key;
                }
            }
        }
        return $handleUrl;
    }
    function getUri() {
        return $this->__keyRoute;
    }
    static function getFullUrl() {
        $uri = App::$app->getUrl();
        $url = _WEB_ROOT.$uri;
        return $url;
    }
}