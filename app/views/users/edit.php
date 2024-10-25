<h3>{{$pageTitle}}</h3>
<form action="<?php echo _WEB_ROOT ?>/users/update/{{$user['id']}}" method="post">
    <div class="row">
        <div class="col-6">
            <div class="mb-3">
                <label for="">Tên</label>
                <input class="form-control" type="text" name="name" placeholder="Tên..." value="{{old('name', $user['name']) ?? ''}}">
                {! form_error('name', '<span class="text-danger">', '</span>') !}
            </div>
        </div>
        <div class="col-6">
            <div class="mb-3">
                <label for="">Email</label>
                <input class="form-control" type="email" name="email" placeholder="Email..." value="{{old('email', $user['email'])}}">
                {! form_error('email', '<span class="text-danger">', '</span>') !}
            </div>
        </div>
        <div class="col-6">
            <div class="mb-3">
                <label for="">Mật khẩu</label>
                <input class="form-control" type="password" name="password" placeholder="Mật khẩu...">
                {! form_error('password', '<span class="text-danger">', '</span>') !}
            </div>
        </div>
        <div class="col-6">
            <div class="mb-3">
                <label for="">Nhập lại mật khẩu</label>
                <input class="form-control" type="password" name="confirm_password" placeholder="Nhập lại mật khẩu...">
                {! form_error('confirm_password', '<span class="text-danger">', '</span>') !}
            </div>
        </div>
        <div class="col-6">
            <div class="mb-3">
                <label for="">Trạng thái</label>
                <select class="form-select" name="status" id="">
                    <option value="0" {{old('status', $user['status']) == 0 ? 'selected' : ''}}>Chưa kích hoạt</option>
                    <option value="1" {{old('status', $user['status']) == 1 ? 'selected' : ''}}>Kích hoạt</option>
                </select>
                {! form_error('status', '<span class="text-danger">', '</span>') !}
            </div>
        </div>
        <div class="col-6">
            <div class="mb-3">
                <label for="">Nhóm</label>
                <select name="group_id" id="" class="form-select">
                    <option value="0">Chọn nhóm</option>
                    @if ($groups)
                        @foreach ($groups as $item)
                            <option value="{{$item['id']}}"  {{old('group_id', $user['group_id']) == $item['id'] ? 'selected' : ''}}>{{$item['name']}}</option>
                        @endforeach
                    @endif
                </select>
                {! form_error('group_id', '<span class="text-danger">', '</span>') !}
            </div>
        </div>

        <div class="col-12">
            <button class="btn btn-success">Lưu</button>
            <a href="<?php echo _WEB_ROOT ?>/users" class="btn btn-primary">Quay lại</a>
        </div>
    </div>
</form>