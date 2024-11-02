<h2 class="text-center">{{$pageTitle}}</h2>
<form action="{{_WEB_ROOT.'/auth/do-login'}}" method="post">
    <div class="mb-3">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" class="form-control" placeholder="Nhập email..." autofocus>
    </div>

    <div class="mb-3">
        <label for="password">Mật khẩu</label>
        <input type="password" name="password" id="password" class="form-control" placeholder="Nhập mật khẩu...">
    </div>

    <div class="d-grid">
        <button type="submit" class="btn btn-primary">Đăng nhập</button>
    </div>
    <hr>
    <p class="text-center">
        <a href="{{_WEB_ROOT.'/auth/register'}}">Đăng ký tài khoản</a>
    </p>
    <p class="text-center">
        <a href="{{_WEB_ROOT.'/auth/forgot-password'}}">Quên mật khẩu</a>
    </p>
</form>