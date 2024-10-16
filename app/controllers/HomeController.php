<?php
// HomeController Controller
class HomeController extends Controller {
    public $data = [], $model = [];

    public function __construct(){
        // construct
    }
    
    public function index(){
        // Index
        if (Hash::check(123456, '$2y$10$IZvh/xceaWofrlWDly0c6OyHqCnKvvOc7JgEMUOJNXjPM7ZUJgC2S')) {
            echo 'Hop le';
        }
        else echo 'khong hop le';
        $this->data['body'] = 'welcome';
        $this->data['dataView']['pageTitle'] = 'Trang chủ';
        $this->render('layouts/layout', $this->data);
    }
}