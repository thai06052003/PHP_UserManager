<?php
// AuthMiddleWare MiddleWare

class AuthMiddleware extends Middleware {
    public function handle(){
        // Handle Method
        $request = new Request();
        $response = new Response;
        $path = $request->getPath();
        $path = rtrim(explode('?', $path)[0], '/');
        $exclude = [
            '/auth/login',
            '/auth/register',
            '/auth/do-login',
            '/auth/do-register',
            '/auth/active-account',
            '/auth/active',
            '/auth/resend-active',
            '/auth/forgot-password',
            '/auth/do-forgot-password',
            '/auth/reset',
            '/auth/update-password',
        ];

        $auth = $this->checkAuth();
        View::share([
            'auth' => $auth
        ]);

        if (!in_array($path,$exclude)) {
            if (!$auth) {
                $response->redirect('/auth/login');
            }
        }
    }
    // Kiểm tra đăng nhập
    public function checkAuth() {
        $userLogin = Session::data('user_login');
        $auth = [];
        if (!empty($userLogin)) {
            $userModel = Load::model('User');
            // Lấy thông tin user đăng nhập
            $user = $userModel->getUser($userLogin['id']);

            // Kiểm tra
            if (!empty($user) && $user['status'] && Session::id() == $user['session_id']) {
                // Hoạt động
                $auth = $user;
            }
            else {
                Session::delete('user_login');
                //return (new Response)->redirect('/auth/login');
            }
        }
        return $auth;
    }
}