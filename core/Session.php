<?php
class Session
{
    /* 
    data(key, value) => set session
    data(key) => get session
    */
    static public function data($key='', $value = '')
    {
        $sessionkey = self::isInvalid();
        //echo $sessionkey;
        if (!empty($value) && !empty($key)) {
            $_SESSION[$sessionkey][$key] = $value;  // set session
            return true;
        } else {
            if (empty($key)) {
                if (isset($_SESSION[$sessionkey])) {
                    return $_SESSION[$sessionkey];
                }
            }
            if (isset($_SESSION[$sessionkey][$key])) {
                return $_SESSION[$sessionkey][$key];    // get session
            }
        }
    }
    /* 
    delete(key) => xóa session với key
    delete() => xóa hết session
    */
    static public function delete($key = '')
    {
        $sessionKey = self::isInvalid();
        if (!empty($key)) {
            if (isset($_SESSION[$sessionKey][$key])) {
                unset($_SESSION[$sessionKey][$key]);
                return true;
            }
            return false;
        } else {
            unset($_SESSION[$sessionKey]);
        }
        return false;
    }
    /* 
    Flash Data
    - set flash data => giống như set session 
    - get flash data => giống như get session, xóa luôn session sau khi get
    */
    static public function flash($key='', $value='') {
        $dataFlash = self::data($key, $value);
        if (empty($value)) {
            self::delete($key);
        }
        return $dataFlash;
    }
    static public function showError($message)
    {
        $data = ['message' => $message];
        App::$app->loadError('exception', $data);
        die;
    }
    static public function isInvalid()
    {
        global $config;
        if (!empty($config['session'])) {
            $session_config = $config['session'];
            if (!empty($session_config['session_key'])) {
                $sessionKey = $session_config['session_key'];
                return $sessionKey;
            } else {
                self::showError('Thiếu cấu hình session. Vui lòng kiểm tra file: configs/session.php');
            }
        } else {
            self::showError('Thiếu cấu hình session. Vui lòng kiểm tra file: configs/session.php');
        }
    }
}
