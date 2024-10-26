<h3>{{$pageTitle}}</h3>

<hr>
<div class="mb-3">
    <a href="<?php echo _WEB_ROOT ?>/users/create" class="btn btn-primary">Thêm mới</a>
</div>
<form action="" method="get" class="mb-3">
    <div class="row">
        <div class="col-3">
            <select name="status" id="" class="form-select">
                <option value="all">Tất cả trạng thái</option>
                <option value="active" {{!empty($request->getFields()['status']) && $request->getFields()['status'] == 'active' ? 'selected' : ''}}>Kích hoạt</option>
                <option value="inactive" {{!empty($request->getFields()['status']) && $request->getFields()['status'] == 'inactive' ? 'selected' : ''}}>Chưa kích hoạt</option>
            </select>
        </div>
        <div class="col-3">
            <select name="group_id" id="" class="form-select">
                <option value="0">Tất cả nhóm</option>
                @if ($groups)
                @foreach ($groups as $group)
                <option value="{{$group['id']}}" {{!empty($request->getFields()['group_id']) && $request->getFields()['group_id'] == $group['id'] ? 'selected' : ''}}>{{$group['name']}}</option>
                @endforeach
                @endif
            </select>
        </div>
        <div class="col-4">
            <input type="search" name="keyword" class="form-control" placeholder="Từ khóa..." value="{{!empty($request->getFields()['keyword']) ? $request->getFields()['keyword'] : ''}}">
        </div>
        <div class="col-2 d-grid">
            <button type="submit" class="btn btn-primary">Tìm kiếm</button>
        </div>
    </div>
</form>
<table class="table table-bordered">
    <thead>
        <tr>
            <th width="5%">
                <input class="check-all" type="checkbox" name="" id="">
            </th>
            <th>Tên</th>
            <th>Email</th>
            <th>Nhóm</th>
            <th width="15%">Trạng thái</th>
            <th width="15%">Thời gian</th>
            <th width="5%">Sửa</th>
            <th width="5%">Xóa</th>
        </tr>
    </thead>
    <tbody>
        @if ($users)
        @foreach ($users as $user)
        <tr>
            <td>
                <input class="check-item" type="checkbox" name="" id="" value="{{$user['id']}}">
            </td>
            <td>{{$user['name']}}</td>
            <td>{{$user['email']}}</td>
            <td>{{$user['group_name']}}</td>
            <td>
                {! $user['status'] == 1 ? '<span class="badge bg-success">Kích hoạt</span>' : '<span class="badge bg-danger">Chưa kích hoạt</span>' !}

            </td>
            <td>
                {{getDateFormat($user['created_at'], 'd/m/Y')}}
                <br>
                {{getDateFormat($user['created_at'], 'H:i:s')}}
            </td>
            <td>
                <a href="{{_WEB_ROOT.'/users/edit/'.$user['id']}}" class="btn btn-success btn-sm">Sửa</a>
            </td>
            <td>
                <form class="deletes-form" action="{{_WEB_ROOT.'/users/delete/'.$user['id']}}" method="post">
                    <button class="btn btn-danger btn-sm">Xóa</button>
                </form>
            </td>
        </tr>
        @endforeach
        @endif
    </tbody>
</table>

<div class="row">
    <div class="col-6">
        <form class="deletes-form" action="{{_WEB_ROOT.'/users/deletes'}}" method="post">
            <button class="btn btn-danger disabled delete-selection">Xóa đã chọn (<span>0</span>)</button>
            <input type="hidden" name="ids" class="ids">
        </form>
    </div>
    <div class="col-6">
        <nav class="d-flex justify-content-end ">
            {! $links !}
        </nav>
    </div>
</div>