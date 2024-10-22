<?php
// UserController Controller
class UserController extends Controller
{
    private $data = [];
    private $userModel;
    private $groupModel;
    private $config = [];


    public function __construct()
    {
        global $config;
        $this->config = $config['app'];
        $this->userModel = $this->model('User');
        $this->groupModel = $this->model('Group');
    }

    public function index()
    {
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
        $this->data['msg'] = Session::flash('msg');
        $this->data['msgType'] = Session::flash('msg_type');

        $this->render('layouts/layout', $this->data);
    }
    public function deletes()
    {
        $request = new Request();
        $response = new Response();
        if ($request->isPost()) {
            $body = $request->getFields();
            if (empty($body['ids'])) {
                // Gắn thông báo vào flash
                Session::flash('msg', 'Vui lòng chọn người dùng cần xóa');
                Session::flash('msg_type', 'error');
                return $response->redirect('/users');
            }
            $ids = explode(',', $body['ids']);
            $this->userModel->deletes($ids);
            Session::flash('msg', 'Xóa người dùng thành công');
            Session::flash('msg_type', 'success');
            return $response->redirect('/users');
        }
        else {
            Session::flash('msg', 'Vui lòng chọn người dùng cần xóa');
                Session::flash('msg_type', 'error');
            return $response->redirect('/users');
        }
    }
}
