<?php
abstract class Middleware{
    public $db;
    abstract function handle();
}