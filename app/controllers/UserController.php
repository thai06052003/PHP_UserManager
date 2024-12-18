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
    // Danh sách người dùng
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

        $userPaginate = $this->userModel->getUsers($filters, $keyword ?? '', $this->config['page_limit']);
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
    // Thêm người dùng
    public function create() {
        $groups = $this->groupModel->getGroup();

        $this->data['body'] = 'users/add';
        $this->data['dataView']['pageTitle'] = 'Thêm người dùng';
        $this->data['dataView']['groups'] = $groups;
        $this->data['msg'] = Session::flash('msg');
        $this->data['msgType'] = Session::flash('msg_type');

        $this->render('layouts/layout', $this->data);
    }
    //
    public function edit($id) {
        $groups = $this->groupModel->getGroup();
        $user = $this->userModel->getUser($id);
        if (!$user) {
            Session::flash('msg', 'Người dùng không tồn tại');
            Session::flash('msg_type', 'error');
            return (new Response)->redirect('/users');
        }
        (new Request())->setOld($user);
        $this->data['body'] = 'users/edit';
        
        $this->data['dataView']['pageTitle'] = 'Cập nhật người dùng';
        $this->data['dataView']['groups'] = $groups;
        $this->data['dataView']['user'] = $user;

        $this->data['msg'] = Session::flash('msg');
        $this->data['msgType'] = Session::flash('msg_type');

        $this->render('layouts/layout', $this->data);
    }
    //
    public function update($id) {
        $request = new Request();
        if (!$request->isPost()) {
            echo 'Not Alow Method';
            return;
        }
        $body = $request->getFields();

        // Validate form
        $rules = [
            'name' => 'required',
            'email' => 'required|email|unique:users:email:id='.$id,
            //'password' => 'required|min:6',
            //'confirm_password' => 'required|callback_checkSamePassword',
            'status' => 'callback_checkStatus',
            'group_id' => 'callback_checkGroup'
        ];

        $message = [
            'name.required' => 'Tên bắt buộc phải nhập',
            'email.required' => 'Email bắt buộc phải nhập',
            'email.email' => 'Email Không đúng định dạng',
            'email.unique' => 'Email đã tồn tại trên hệ thống',
            //'password.required' => 'Mật khẩu bắt buộc phải nhập',
            'password.min' => 'Mật khẩu phải từ :min ký tự',
            'confirm_password.required' => 'Nhập lại mật khẩu không được để trống',
            'confirm_password.callback_checkSamePassword' => 'Mật khẩu không khớp',
            'status.callback_checkStatus' => 'Trạng thái không hợp lệ',
            'group_id.callback_checkGroup' => 'Nhóm không hợp lệ',
        ];
        if (!empty($body['password'])) {
            $rules['password'] = 'min:6';
            $rules['confirm_password'] = 'required|callback_checkSamePassword';
        }

        $request->rules($rules);
        $request->message($message);
        

        $check = $request->validate();
        if (!$request->validate()) {
            Session::flash('msg', 'Vui lòng kiểm tra lại thông tin');
            Session::flash('msg_type', 'error');
            return (new Response())->redirect('/users/edit/'.$id);
        }
        // xử lý update
        if (!empty($body['password'])) {
            $body['password'] = Hash::make($body['password']);
        }else {
            unset($body['password']);
        }
        
        unset($body['confirm_password']);
        $status = $this->userModel->updateUser($body, $id);
        if ($status) {
            Session::flash('msg', 'Cập nhật người dùng thành công');
            Session::flash('msg_type', 'success');
        }
        else {
            Session::flash('msg', 'Lỗi máy chủ vui lòng thử lại sau');
            Session::flash('msg_type', 'error');
        }
        return (new Response())->redirect('/users/edit/'.$id);
    }
    // Xử lý thêm người dùng
    public function store() {
        $request = new Request();
        if (!$request->isPost()) {
            echo 'Not Allow Method';
            return;
        }
        
        // validate
        $rules = [
            'name' => 'required',
            'email' => 'required|email|unique:users:email',
            'password' => 'required|min:6',
            'confirm_password' => 'required|callback_checkSamePassword',
            'status' => 'callback_checkStatus',
            'group_id' => 'callback_checkGroup'
        ];

        $message = [
            'name.required' => 'Tên bắt buộc phải nhập',
            'email.required' => 'Email bắt buộc phải nhập',
            'email.email' => 'Email Không đúng định dạng',
            'email.unique' => 'Email đã tồn tại trên hệ thống',
            'password.required' => 'Mật khẩu bắt buộc phải nhập',
            'password.min' => 'Mật khẩu phải từ :min ký tự',
            'confirm_password.required' => 'Nhập lại mật khẩu không được để trống',
            'confirm_password.callback_checkSamePassword' => 'Mật khẩu không khớp',
            'status.callback_checkStatus' => 'Trạng thái không hợp lệ',
            'group_id.callback_checkGroup' => 'Nhóm không hợp lệ',
        ];
        $request->rules($rules);
        $request->message($message);
        

        $check = $request->validate();
        if (!$request->validate()) {
            Session::flash('msg', 'Vui lòng kiểm tra lại thông tin');
            Session::flash('msg_type', 'error');
            return (new Response())->redirect('/users/create');
        }

        $body = $request->getFields();
        unset($body['confirm_password']);
        $body['password'] = Hash::make($body['password']);
        $status = $this->userModel->addUser($body);
        if ($status) {
            Session::flash('msg', 'Thêm thành công');
            Session::flash('msg_type', 'success');
            return (new Response())->redirect('/users');
        }
        else {
            Session::flash('msg', 'Lỗi máy chủ vui lòng thử lại sau');
            Session::flash('msg_type', 'error');
            return (new Response())->redirect('/users/create');
        }
    }
    // Kiểm tra validate confirm_password === password
    public function checkSamePassword($value) {
        $request = new Request();
        $body = $request->getFields();
        if ($body['password'] == $value) {
            return true;
        }
        return false;
    }
    // Kiểm tra validate status
    public function checkStatus($value) {
        return $value == 0 || $value == 1;
    }
    // Kiểm tra validate Group
    public function checkGroup($value) {
        return filter_var($value, FILTER_VALIDATE_INT, [
        'options' => ['min_range' => 1]
        ]);
    }
    // Xóa nhiều người dùng
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
        }
        else {
            Session::flash('msg', 'Vui lòng chọn người dùng cần xóa');
            Session::flash('msg_type', 'error');
        }
        return $response->redirect('/users');
    }
    // Xóa 1 người dùng
    public function delete($id) {
        $request = new Request();
        $response = new Response;
        if ($request->isPost()) {
            // Xử lí --> csrf
            // model
            $status = $this->userModel->deletes([$id]);
            if ($status) {
                Session::flash('msg', 'Xóa người dùng thành công');
                Session::flash('msg_type', 'success');
            }
            else {
                Session::flash('msg', 'Vui lòng chọn người dùng cần xóa');
                Session::flash('msg_type', 'error');
            }
            return $response->redirect('/users');
        }
        echo "Method GET not support";
    }
}
