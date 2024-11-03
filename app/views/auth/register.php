<h2 class="text-center">{{$pageTitle}}</h2>
<form action="{{_WEB_ROOT.'/auth/do-register'}}" method="post">
    <div class="mb-3">
        <label for="name">Tên</label>
        <input type="name" name="name" id="name" class="form-control" placeholder="Nhập tên..." autofocus>
    </div>

    <div class="mb-3">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" class="form-control" placeholder="Nhập email...">
    </div>

    <div class="mb-3">
        <label for="password">Mật khẩu</label>
        <input type="password" name="password" id="password" class="form-control" placeholder="Nhập mật khẩu...">
    </div>

    <div class="mb-3">
        <label for="confirm_password">Nhập lại mật khẩu</label>
        <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Nhập lại mật khẩu...">
    </div>

    <div class="d-grid">
        <button type="submit" class="btn btn-primary">Đăng ký</button>
    </div>
    <hr>
    <p class="text-center">
        <a href="{{_WEB_ROOT.'/auth/login'}}">Đăng nhập hệ thống</a>
    </p>
    <p class="text-center">
        <a href="{{_WEB_ROOT.'/auth/login'}}">Đăng nhập</a>
    </p>
</form>