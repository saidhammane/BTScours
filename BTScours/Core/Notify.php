<?php

class Notify{
    private $message;
    private $type;
    function __construct($message,$type){
        $this->message = $message;
        $this->type = $type;
    }
    public function toString(){
        return "<div class='alert alert-".$this->type." p-2'>".$this->message."</div>";
    }
    public static function getHTML(){
        $tmp =Notify::Get();
        if($tmp != null && isset($tmp)){
            return $tmp->toString();    
        }
        return "";
    }
    public static function Get(){
        if(Session::exists("notification")){
            $tmp = Session::Get("notification");
            $n = new Notify($tmp->message,$tmp->type);
            Session::Avoid("notification");
            return $n;
        }
    }
    public static function Set($message,$type){
        $tmp = new Notify($message,$type);
        Session::Set("notification",$tmp);
    }
}

?>