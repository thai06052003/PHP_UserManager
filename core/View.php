<?php
class View {
    static public $dataShare = [];
    static public function share($data) {
        self::$dataShare = $data;
        /* echo '<pre>';
        print_r(self::$dataShare);
        echo '</pre>'; */
    }
}