<?php
class User extends Model{
    function activated($id){
        $res = intval($this->Get("SELECT * FROM user WHERE id = ".$id)[0]["activated"]);
        $this->Exec("UPDATE user SET activated = ".($res === 0?1:0)." WHERE id = ".$id);
    }
    function FindByEmail($email){
        return $this->Get("SELECT * FROM ".get_class($this)+" WHERE email = '".$email."'");
    }
}
?>