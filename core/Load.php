<?php
class Load{
    static public function model($model) {
        if (file_exists(__DIR_ROOT.'/app/models/'.$model.'.php')) {
            require_once __DIR_ROOT.'/app/models/'.$model.'.php';
            if (class_exists($model)) {
                $model = new $model();
                return $model;
            }
        }
        return false;
    }
    static public function view ($view, $data=[]) {
        extract($data); // Đổi key của mảng thành biến
        if (file_exists(__DIR_ROOT.'/app/views/'.$view.'.php')) {
            require_once __DIR_ROOT.'/app/views/'.$view.'.php';
        }
        return false;
    }
}