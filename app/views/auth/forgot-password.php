<h2 class="text-center">{{$pageTitle}}</h2>
<form action="{{_WEB_ROOT.'/auth/do-forgot-password'}}" method="post">
    <div class="mb-3">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" class="form-control" placeholder="Nhập email..." value="{{old('email') ?? ''}}" autofocus>
    </div>
    <div class="d-grid">
        <button type="submit" class="btn btn-primary">Xác nhận</button>
    </div>
    <hr>
    <p class="text-center">
        <a href="{{_WEB_ROOT.'/auth/login'}}">Đăng nhập hệ thống</a>
    </p>
    <p class="text-center">
        <a href="{{_WEB_ROOT.'/auth/register'}}">Đăng ký tài khoản</a>
    </p>
</form>