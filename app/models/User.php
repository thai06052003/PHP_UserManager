<?php
// User Model
class User extends Model{
    public function tableFill() {
        return 'users';
    }
    public function fieldFill() {
        return '*';
    }
    function primaryKey() {
        return 'id';
    }
    function getUsers($filters = [], $keyword = '', $limit) {
        $users = $this->db
            ->table($this->tableFill())
            ->select('users.*, groups.name as group_name')
            ->orderBy('users.created_at', 'DESC')
            ->join('groups', 'users.group_id=groups.id');
        if (!empty($filters)) {
            foreach ($filters as $key => $value) {
                $users->where($key, '=', $value);
            }
        }
        if (!empty($keyword)) {
            $users->where(function ($query) use ($keyword) {
                $query
                ->where('users.name', 'LIKE',"%$keyword%")
                ->orWhere('users.email', 'LIKE',"%$keyword%");
            });
        }
        $users = $users->paginate($limit);
        
        return $users;
    }
    public function deletes($ids) {
        return $this->db->table($this->tableFill())->whereIn('id', $ids)->delete();
    }
    public function addUser($data) {
        return $this->db->table($this->tableFill())->insert($data);
    }
    //
    public function getUser ($value, $field='id') {
        return $this->db->table($this->tableFill())->where($field, '=', $value)->first();
    }
    //
    public function updateUser($data, $id) {
        return $this->db->table($this->tableFill())->where('id', '=', $id)->update($data);
    }
    //
    public function getLastUserId() {
        return $this->db->lastId();
    }
}
