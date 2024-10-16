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
    function getUser($filters = [], $keyword = '') {
        echo $keyword;
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
            $users->whereLike('users.name', "%$keyword%");
        }
        $users = $users->get();
        
        return $users;
    }
}
