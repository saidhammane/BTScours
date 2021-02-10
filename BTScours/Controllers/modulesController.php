<?php
class modulesController extends Controller
{
    function jsonModules(){
        $data = $_POST;
        $req = "SELECT * FROM `modulesDetail`";
        if(isset($data['cond']) && $data['cond'] !== '') $req .= ' WHERE '.$data['cond'];
        else{
            $req .= (count($data) > 0?' WHERE ':'');
            foreach($data as $k => $v) {
                if($k !== "type"){
                    $req .= $k." = ".Form::SecureInput($v).' '.(isset($data["type"])?$data["type"]:' AND ').' ';
                }
            }
            $req = rtrim(rtrim($req,' '),(isset($data["type"])?$data["type"]:'AND'));
        }
        $res = $this->model->Get($req);
        Func::ToJson($res);
    }
    function jsonModulesByBranch($branchId){
        Func::ToJson($this->model->Get("SELECT * FROM `modules` WHERE branch_id = ".$branchId));
    }
}
?>