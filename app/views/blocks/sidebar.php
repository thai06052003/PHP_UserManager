<!-- <aside>
    <h3>Menu</h3>
    <ul class="nav flex-column">
        <li><a href="{{_WEB_ROOT}}">Trang chủ</a></li>
        <li><a href="{{_WEB_ROOT}}/users">Quản lý người dùng</a></li>
    </ul>
    <hr>
    <ul class="nav flex-column">
        <li>
            Chào bạn: {{$auth['name'] ?? ''}}
        </li>
        <li><a href="{{_WEB_ROOT}}/auth/logout">Đăng xuất</a></li>
    </ul>
</aside> -->
<div class="d-flex flex-column flex-shrink-0 p-3 bg-light">
    <a href="/" class="d-flex text-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none">
        <span class="fs-4">Menu</span>
    </a>
    <hr>
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
            <a href="{{_WEB_ROOT}}" class="nav-link link-dark">
                <i class="fas fa-home"></i>
                Trang chủ
            </a>
        </li>
        <li>
            <a href="{{_WEB_ROOT}}/users" class="nav-link link-dark">
                <i class="fas fa-user"></i>
                Quản lý người dùng
            </a>
        </li>
    </ul>
    <hr>
    <ul class="nav flex-column">
        <li>
            Chào bạn: {{$auth['name'] ?? ''}}
        </li>
        <li class="nav-item"><a href="{{_WEB_ROOT}}/auth/logout">Đăng xuất</a></li>
    </ul>
</div>