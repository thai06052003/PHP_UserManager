<aside>
    <h3>Menu</h3>
    <ul class="nav flex-column">
        <li><a href="{{_WEB_ROOT}}">Trang chủ</a></li>
        <li><a href="{{_WEB_ROOT}}/users">Quản lý người dùng</a></li>
    </ul>
    <hr>
    <ul class="nav flex-column">
        <li>
            Chào bạn: @if ($_SESSION['dxt_session']['user_login']['name'])
            {{$_SESSION['dxt_session']['user_login']['name']}}
            @endif
        </li>
        <li><a href="{{_WEB_ROOT}}/auth/logout">Đăng xuất</a></li>
    </ul>
</aside>