<?php
class filesController extends Controller
{
    function index(){
        $this->autoRender();
    }
    function upload(){
        $errors = array();
        if(isset($_POST["module"])){
            if(isset($_FILES["file"])){
                $maxsize = 8 * 1024 * 1024;
                $files = Func::getFiles($_FILES);
                $folder=$_POST["module"]."/";
                $dir = UPLOADS_DIR.$folder;
                Func::make_dir($dir);
                foreach($files as $k => $v){
                    if(intval($v->error) == 0){
                        $allowed = array('gif', 'png', 'jpeg', 'jpg','pdf','rar');
                        $ext = pathinfo(strtolower($v->name), PATHINFO_EXTENSION);
                        if (!in_array($ext, $allowed)) $errors[$k] = "<strong>".$v->name."</strong> : Extension not allowed  !!!";
                        else{
                            if(intval($v->size) > $maxsize) $errors[$k] = "<strong>".$v->name."</strong> : Your file is too large !!!";
                            else{
                                $filename = pathinfo($v->name, PATHINFO_FILENAME);
                                $extension =  pathinfo($v->name, PATHINFO_EXTENSION);
                                $FinalFilename = $filename.'.'.$extension;
                                $FileCounter = 0;
                                while (file_exists($dir.$FinalFilename)) $FinalFilename = $filename . '_' . $FileCounter++ . '.' . $extension;
                                if(move_uploaded_file($v->tmp_name, $dir.$FinalFilename)) $this->model->create($folder.$FinalFilename,$_POST["module"]);
                                else $errors[$k] = " - <strong>".$v->name."</strong> : Upload error !!!";
                            }
                        }
                    }
                    else $errors[$k] = "<strong>".$v->name."</strong> : Undefined error !!!";
                }
                if(count($errors) <= 0) Notify::Set("Uploaded successfully.","success");
            }
        }
        if(count($errors) > 0) Notify::Set(implode("</br>",$errors),"warning");
        $d['branchs'] = $this->model->Get("SELECT * FROM branch");
        $this->set($d);
        $this->autoRender();
    }
    function download(){
        $d['files'] = $this->model->Get('SELECT * FROM files'.(AuthUser::IsAdministrator()?'':' WHERE confirmed = 1'));
        $this->set($d);
        $this->autoRender();
    }
    function delete($id){
        $f = $this->model->FindOne($id);
        $this->model->delete($id);
        $path = UPLOADS_DIR.$f["path"];
        if(file_exists($path)) unlink($path);
    }
    function confirmed($id){
        $this->model->confirmed($id);
    }
    function jsonAll(){
        $res = $this->model->FindAll();
        Func::ToJson($res);
    }
    function jsonChartAll($size=20){
        $res = $this->model->Get("SELECT count(id) Number, DATE(uploadedAt) Date FROM `file` group by DATE(uploadedAt) order by date asc limit ".$size);
        Func::ToJson($res);
    }
}
?>