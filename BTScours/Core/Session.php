<?php

class Session{
    public static function Set($key,$value){
        $_SESSION[$key] = $value;
    }
    public static function Get($key){
        return $_SESSION[$key];//return Session::exists($key)?$_SESSION[$key]:false;
    }
    public static function Avoid($key){
        unset($_SESSION[$key]);
    }
    public static function exists($key){
        return isset($_SESSION[$key]);
    }
}

?>