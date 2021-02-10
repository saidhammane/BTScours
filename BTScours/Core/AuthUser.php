<?php 

class AuthUser{
    public static function IsAthenticated(){
        return Session::exists("user") ;
    }
    public static function Set($user){
        Session::Set("user",$user);
    }
    public static function Get(){
        return Session::Get("user");
    }
    public static function IsAdministrator(){
        return intval(AuthUser::Get()["role"]) === 0;
    }
}

?>