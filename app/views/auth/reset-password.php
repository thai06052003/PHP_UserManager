<h2 class="text-center">{{$pageTitle}}</h2>
<form action="{{_WEB_ROOT.'/auth/update-password'}}" method="post">
    <div class="mb-3">
        <label for="password">Mật khẩu</label>
        <input type="password" name="password" id="password" class="form-control" placeholder="Nhập mật khẩu...">
        {! form_error('password', '<span class="text-danger">', '</span>') !}
    </div>

    <div class="mb-3">
        <label for="confirm_password">Nhập lại mật khẩu</label>
        <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Nhập lại mật khẩu...">
        {! form_error('confirm_password', '<span class="text-danger">', '</span>') !}
    </div>

    <div class="d-grid">
        <button type="submit" class="btn btn-primary">Cập nhật mật khẩu</button>
    </div>
    <hr>
    <p class="text-center">
        <a href="{{_WEB_ROOT.'/auth/login'}}">Đăng nhập ngay</a>
    </p>
</form>