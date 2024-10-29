<?php
// AuthMiddleWare MiddleWare

class AuthMiddleware extends Middleware {
    public function handle(){
        // Handel Method
        $request = new Request();
        $response = new Response;
        $path = $request->getPath();
        $exclude = [
            '/auth/login',
            '/auth/register',
            '/auth/do-login',
        ];
        if (!in_array($path,$exclude)) {
            if (!Session::data('user_login')) {
                $response->redirect('/auth/login');
            }
        }

    }
}