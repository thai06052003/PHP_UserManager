<div class="alert alert-info text-center">
    <p>Vui lòng kiểm tra email để kích hoạt tài khoản</p>
    <form name="resend-form" action="{{_WEB_ROOT.'/auth/resend-active'}}" method="post">
        <a href="#" onclick="event.preventDefault(); document['resend-form'].submit()">Gửi lại email kích hoạt</a>
    </form>
</div>