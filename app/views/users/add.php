<h3>{{$pageTitle}}</h3>
<form action="<?php echo _WEB_ROOT ?>/users/store" method="post">
    <div class="row">
        <div class="col-6">
            <div class="mb-3">
                <label for="">Tên</label>
                <input class="form-control" type="text" name="name" placeholder="Tên...">
            </div>
        </div>
        <div class="col-6">
            <div class="mb-3">
                <label for="">Email</label>
                <input class="form-control" type="email" name="email" placeholder="Email...">
            </div>
        </div>
        <div class="col-6">
            <div class="mb-3">
                <label for="">Mật khẩu</label>
                <input class="form-control" type="password" name="password" placeholder="Mật khẩu...">
            </div>
        </div>
        <div class="col-6">
            <div class="mb-3">
                <label for="">Nhập lại mật khẩu</label>
                <input class="form-control" type="password" name="confirm_password" placeholder="Nhập lại mật khẩu...">
            </div>
        </div>
        <div class="col-6">
            <div class="mb-3">
                <label for="">Trạng thái</label>
                <select class="form-select" name="status" id="">
                    <option value="0">Chưa kích hoạt</option>
                    <option value="1">Kích hoạt</option>
                </select>
            </div>
        </div>
        <div class="col-6">
            <div class="mb-3">
                <label for="">nhóm</label>
                <select name="group_id" id="" class="form-select">
                    <option value="0">Chọn nhóm</option>
                    @if ($groups)
                        @foreach ($groups as $item)
                            <option value="{{$item['id']}}">{{$item['name']}}</option>
                        @endforeach
                    @endif
                </select>
            </div>
        </div>

        <div class="col-12">
            <button class="btn btn-primary">Lưu</button>
            <a href="<?php echo _WEB_ROOT ?>/users" class="btn btn-danger">Hủy</a>
        </div>
    </div>
</form>