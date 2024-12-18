<?php
// AuthController Controller
class AuthController extends Controller
{
    private $data = [], $userModel;

    public function __construct()
    {
        // construct
        $this->userModel = $this->model('User');
    }
    // Đăng nhập
    public function login()
    {
        $this->data['body'] = 'auth/login';
        $this->data['dataView']['pageTitle'] = 'Đăng nhập hệ thống';
        $this->data['msg'] = Session::flash('msg');
        $this->data['msgType'] = Session::flash('msg_type');

        $this->render('layouts/auth', $this->data);
    }
    // Xử lý đăng nhập
    public function handleLogin()
    {
        $request = new Request();
        $response = new Response();
        if ($request->isPost()) {
            // Lấy ra giá trị đã post
            $body = $request->getFields();
            if (empty($body['email']) || empty($body['password'])) {
                // Error
                Session::flash('msg', 'Vui lòng nhập email và mật khẩu');
                Session::flash('msg_type', 'error');
            } else {
                $user = $this->userModel->getUser($body['email'], 'email');
                if (!$user || !$user['status']) {
                    // Error
                    Session::flash('msg', 'Email hoặc mật khẩu không chính xác');
                    Session::flash('msg_type', 'error');
                } else {
                    $passwordHash = $user['password'];
                    $verifyStatus = Hash::check($body['password'], $passwordHash);
                    $request->setOld($body);
                    if (!$verifyStatus) {
                        // Error
                        Session::flash('msg', 'Email hoặc mật khẩu không chính xác');
                        Session::flash('msg_type', 'error');
                    } else {
                        // Success
                        Session::data('user_login', $user);
                        $this->userModel->updateUser([
                            'session_id' => Session::id(),
                        ], $user['id']);
                        return $response->redirect('/');
                    }
                }
            }
            return $response->redirect('/auth/login');
        }
        echo "Method " . strtoupper($request->getMethod()) . " not support.";
    }
    // Đăng xuất
    public function logout()
    {
        Session::delete('user_login');
        $response = new Response();
        Session::flash('msg', 'Đăng xuất thành công');
        Session::flash('msg_type', 'success');
        return $response->redirect('/auth/login');
    }
    // Đăng ký
    public function register()
    {
        $this->data['body'] = 'auth/register';
        $this->data['dataView']['pageTitle'] = 'Đăng ký tài khoản';
        $this->data['msg'] = Session::flash('msg');
        $this->data['msgType'] = Session::flash('msg_type');
        $this->render('layouts/auth', $this->data);
    }
    // Xử lý đăng ký
    public function handleRegister()
    {
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
        ];
        $request->rules($rules);
        $request->message($message);


        $check = $request->validate();
        if (!$request->validate()) {
            Session::flash('msg', 'Vui lòng kiểm tra lại thông tin');
            Session::flash('msg_type', 'error');
            return (new Response())->redirect('/auth/register');
        }

        $body = $request->getFields();
        unset($body['confirm_password']);
        $body['password'] = Hash::make($body['password']);
        $body['group_id'] = 3;
        $status = $this->userModel->addUser($body);

        if ($status) {
            $userId = $this->userModel->getLastUserId();
            Session::data('user_active', $userId);

            // Tạo active token
            $activeToken = md5(uniqid());   // 32 ký tự
            // Update active token vào bảng user
            $this->userModel->updateUser([
                'active_token' => $activeToken,
                'updated_at' => date('Y-m-d H:i:s'),
            ], $userId);
            // Tạo link kích hoạt
            $linkActive = _WEB_ROOT . '/auth/active?token=' . $activeToken;
            // Gửi email
            $name = $body['name'];
            $subject = "$name hãy kích hoạt tài khoản";
            $content = "
                <p>Chào bạn: $name</p>
                <p>Cảm ơn bạn đã đăng ký tài khoản trên Website của chúng tôi</p>
                <p>Để tiếp tực sử dụng. Vui lòng click vào link dưới đây để kích hoạt tài khoản</p>
                <p>$linkActive</p>
                <p>DXT</p>
            ";
            Mail::send($body['email'], $subject, $content);
            return (new Response())->redirect('/auth/active-account');
        } else {
            Session::flash('msg', 'Lỗi máy chủ vui lòng thử lại sau');
            Session::flash('msg_type', 'error');
            return (new Response())->redirect('/auth/register');
        }
    }
    // Hiển thị kích hoạt
    public function showActive()
    {
        if (!session::data('user_active')) {
            return (new Response)->redirect('/auth/register');
        }
        $this->data['body'] = 'auth/active-notice';
        $this->data['dataView']['pageTitle'] = 'Kích hoạt tài khoản';
        $this->data['msg'] = Session::flash('msg');
        $this->data['msgType'] = Session::flash('msg_type');
        $this->render('layouts/auth', $this->data);
    }
    // kích hoạt tài khoản
    public function active()
    {
        $request = new Request();
        $query = $request->getFields();
        if (!empty($query['token'])) {
            $token = $query['token'];
            $user = $this->userModel->getUser($token, 'active_token');

            if (empty($user)) {
                $this->data['dataView']['message'] = 'Liên kết không tồn tại hoặc đã hết hạn';
                $this->data['dataView']['type'] = 'danger';
            } else {
                $this->userModel->updateUser([
                    'status' => 1,
                    'active_token' => null,
                    'updated_at' => date('Y-m-d H:i:s'),
                ], $user['id']);
                $this->data['dataView']['message'] = 'Kích hoạt tài khoản thành công';
                $this->data['dataView']['type'] = 'success';
            }

            $this->data['body'] = 'auth/active-result';
            $this->data['dataView']['pageTitle'] = 'Kích hoạt tài khoản';
            Session::delete('user_active');
            $this->render('layouts/auth', $this->data);
        }
    }
    // Gửi lại email kích hoạt
    public function resendActive()
    {
        $request = new Request();

        if ($request->isPost()) {
            $userId = Session::data('user_active');
            $user = $this->userModel->getUser($userId);
            // Tạo active token
            $activeToken = md5(uniqid());   // 32 ký tự
            // Update active token vào bảng user
            $this->userModel->updateUser([
                'active_token' => $activeToken,
                'updated_at' => date('Y-m-d H:i:s'),
            ], $userId);
            // Tạo link kích hoạt
            $linkActive = _WEB_ROOT . '/auth/active?token=' . $activeToken;
            // Gửi email
            $name = $user['name'];
            $subject = "$name hãy kích hoạt tài khoản";
            $content = "
                <p>Chào bạn: $name</p>
                <p>Cảm ơn bạn đã đăng ký tài khoản trên Website của chúng tôi</p>
                <p>Để tiếp tực sử dụng. Vui lòng click vào link dưới đây để kích hoạt tài khoản</p>
                <p>$linkActive</p>
                <p>DXT</p>
            ";
            Mail::send($user['email'], $subject, $content);

            Session::flash('msg', 'Đã gửi lại email kích hoạt thành công');
            Session::flash('msg_type', 'success');
            return (new Response)->redirect('/auth/active-account');
        } else {
            echo 'Method ' . strtoupper($request->getMethod()) . ' not support';
        }
    }
    // Quên mật khẩu
    public function forgotPassword()
    {
        $this->data['body'] = 'auth/forgot-password';
        $this->data['dataView']['pageTitle'] = 'Lấy lại mật khẩu';
        $this->data['msg'] = Session::flash('msg');
        $this->data['msgType'] = Session::flash('msg_type');
        $this->render('layouts/auth', $this->data);
    }
    //
    public function handleForgotPassword()
    {
        $request = new Request();
        $response = new Response();
        if ($request->isPost()) {
            // Lấy ra giá trị đã post
            $body = $request->getFields();
            if (empty($body['email'])) {
                // Error
                Session::flash('msg', 'Vui lòng nhập email để lấy lại mật khẩu');
                Session::flash('msg_type', 'error');
            } else {
                $user = $this->userModel->getUser($body['email'], 'email');
                if (!$user || !$user['status']) {
                    // Error
                    Session::flash('msg', 'Email không tồn tại trên hệ thống');
                    Session::flash('msg_type', 'error');
                    $request->setOld($body);
                } else {
                    // Success
                    $userId = $user['id'];
                    // Tạo reset token
                    $resetToken = md5(uniqid());   // 32 ký tự
                    // Update reset token vào bảng user
                    $this->userModel->updateUser([
                        'reset_token' => $resetToken,
                        'updated_at' => date('Y-m-d H:i:s'),
                    ], $userId);
                    // Tạo link kích hoạt
                    $linkReset = _WEB_ROOT . '/auth/reset?token=' . $resetToken;
                    // Gửi email
                    $name = $user['name'];
                    $subject = "Yêu cầu đặt lại mật khẩu";
                    $content = "
                        <p>Chào bạn: $name</p>
                        <p>Chúng tôi có nhận được yêu cầu đặt lại mật khẩu</p>
                        <p>Để xác nhận đây là yêu cầu của bạn. Vui lòng click vào link dưới đây để kích hoạt tài khoản</p>
                        <p>$linkReset</p>
                        <p>DXT</p>
                    ";
                    Mail::send($user['email'], $subject, $content);
                    Session::flash('msg', 'Vui lòng kiêm tra email để đặt lại mật khẩu');
                    Session::flash('msg_type', 'success');
                }
            }
            return $response->redirect('/auth/forgot-password');
        }
        echo "Method " . strtoupper($request->getMethod()) . " not support.";
    }
    //
    public function resetPassword()
    {

        $this->data['dataView']['pageTitle'] = 'Đặt lại mật khẩu';
        $this->data['msg'] = Session::flash('msg');
        $this->data['msgType'] = Session::flash('msg_type');
        $this->data['body'] = 'auth/confirm-reset';


        $request = new Request();
        $query = $request->getFields();
        if (!empty($query['token'])) {
            $token = $query['token'];
            $user = $this->userModel->getUser($token, 'reset_token');
            if (!empty($user)) {
                $this->data['body'] = 'auth/reset-password';
                Session::data('reset_token', $token);
                Session::data('user_id', $user['id']);
            }
        }

        $this->render('layouts/auth', $this->data);
    }
    public function updatePassword()
    {
        $request = new Request();
        // validate
        $rules = [
            'password' => 'required|min:6',
            'confirm_password' => 'required|callback_checkSamePassword',
        ];

        $message = [
            'password.required' => 'Mật khẩu bắt buộc phải nhập',
            'password.min' => 'Mật khẩu phải từ :min ký tự',
            'confirm_password.required' => 'Nhập lại mật khẩu không được để trống',
            'confirm_password.callback_checkSamePassword' => 'Mật khẩu không khớp',
        ];
        $request->rules($rules);
        $request->message($message);

        $check = $request->validate();
        if (!$request->validate()) {
            Session::flash('msg', 'Vui lòng kiểm tra lại thông tin');
            Session::flash('msg_type', 'error');
            return (new Response())->redirect('/auth/reset?token=' . Session::data('reset_token'));
        }
        $body = $request->getFields();
        $password = Hash::make($body['password']);

        $this->userModel->updateUser([
            'reset_token' => null,
            'password' => $password,
            'updated_at' => date('Y-m-d H:i:s'),
        ], Session::data('user_id'));
        Session::delete('user_id');
        Session::delete('reset_token');
        Session::flash('msg', 'Đặt lại mật khẩu thành công');
        Session::flash('msg_type', 'success');
        return (new Response())->redirect('/auth/login');
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
}
