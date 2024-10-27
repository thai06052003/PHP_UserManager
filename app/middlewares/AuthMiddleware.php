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
        ];
        if (!in_array($path,$exclude)) {
            $response->redirect('/auth/login');
        }

    }
}