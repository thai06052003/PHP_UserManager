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
}
