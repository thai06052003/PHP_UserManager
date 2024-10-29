<?php
class AppServiceProvider extends ServiceProvider {
    public function boot() {
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
        View::share([
            'auth' => $auth
        ]);
    }
}