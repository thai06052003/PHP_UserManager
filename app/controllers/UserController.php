<?php
// UserController Controller
class UserController extends Controller {
    private $data = [];
    private $userModel;
    private $groupModel;


    public function __construct(){
        $this->userModel = $this->model('User');
        $this->groupModel = $this->model('Group');
    }
    
    public function index(){
        $request = new Request();
        $query = $request->getFields();

        $this->data['body'] = 'users/index';
        $this->data['dataView']['pageTitle'] = 'Quản lý người dùng';

        $filters = [];
        $keyword = '';

        if (!empty($query)) {
            extract($query);
            if ($status == 'active' || $status == 'inactive') {
                $filters['status'] = $status == 'active' ? 1 : 0;
            }
            if ($group_id) {
                $filters['group_id'] = $group_id;
            }
            /* echo '<pre>';
            print_r($filters);
            echo '</pre>'; */
        }

        $users = $this->userModel->getUser($filters, $keyword ?? '');
        $groups = $this->groupModel->getGroup();

        $this->data['dataView']['users'] = $users;
        $this->data['dataView']['groups'] = $groups;
        $this->data['dataView']['request'] = $request;

        $this->render('layouts/layout', $this->data);
    }
}