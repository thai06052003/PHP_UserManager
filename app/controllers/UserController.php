<?php
// UserController Controller
class UserController extends Controller {
    private $data = [];
    private $userModel;
    private $groupModel;
    private $config = [];


    public function __construct(){
        global $config;
        $this->config = $config['app'];
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

            if (!empty($status) && ($status == 'active' || $status == 'inactive')) {
                $filters['status'] = $status == 'active' ? 1 : 0;
            }
            if (!empty($group_id)) {
                $filters['group_id'] = $group_id;
            }
        }

        $userPaginate = $this->userModel->getUser($filters, $keyword ?? '', $this->config['page_limit']);
        $users = $userPaginate['data'];
        $links = $userPaginate['link'];

        $groups = $this->groupModel->getGroup();

        $this->data['dataView']['users'] = $users;
        $this->data['dataView']['links'] = $links;
        $this->data['dataView']['groups'] = $groups;
        $this->data['dataView']['request'] = $request;

        $this->render('layouts/layout', $this->data);
    }
    public function deletes() {
        $request = new Request();
        if ($request->isPost()) {
            $body = $request->getFields();
            if (!empty($body['ids'])) {
                $ids = explode(',', $body['ids']);
                echo '<pre>';
                print_r($ids);
                echo '</pre>';
            }
            return;
        }
        echo 'Method not support';
    }
    public function delete() {
        
        echo 'Delete View';
    }
}