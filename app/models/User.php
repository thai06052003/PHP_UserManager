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
    function getUser() {
        return $this->db
            ->table($this->tableFill())
            ->select('users.*, groups.name as group_name')
            ->orderBy('users.created_at', 'DESC')
            ->join('groups', 'users.created_at', 'DESC')
            ->get();
    }
}
