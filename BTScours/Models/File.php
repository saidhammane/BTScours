<?php
class File extends Model{
    function create($path,$module_id){
        $user_id = AuthUser::Get()["id"];
        $this->Exec("INSERT INTO file (path,lineModule_id,user_id) value(\"".$path."\",".$module_id.",".$user_id.")");
    }
    function confirmed($id){
        $res = intval($this->Get("SELECT * FROM file WHERE id = ".$id)[0]["confirmed"]);
        $this->Exec("UPDATE file SET confirmed = ".($res === 0?1:0)." WHERE id = ".$id);
    }
}
?>