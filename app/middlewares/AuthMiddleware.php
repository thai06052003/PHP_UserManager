<?php
// AuthMiddleWare MiddleWare

class AuthMiddleware extends Middleware {
    public function handle(){
        // Handel Method
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
    public function checkAuth() {
        $userLogin = Session::data('user_login');
        $auth = [];
        if (!empty($userLogin)) {
            $userModel = Load::model('User');
            $user = $userModel->getUser($userLogin['id']);
            if (!empty($user) && $user['status']) {
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