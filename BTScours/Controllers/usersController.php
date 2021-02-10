<?php
class usersController extends Controller
{
    function index($type=null){
        $type = ($type === "student" || $type === "professor" || $type === "guest")?$type :"professor";
        $res = $this->model->Get("SELECT * FROM users WHERE type = \"".$type."\" ".(AuthUser::IsAdministrator()?"":" AND activated = 1"));
        $d['type'] = $type;
        $d['users'] = $res;
        $this->set($d);
        $this->autoRender();
    }
    function profile($id=null){
        $id = ($id==null || !is_numeric($id))?$id = AuthUser::Get()["id"]:$id;
        $user = $this->model->Get("SELECT * FROM `users` WHERE id = ".$id.(AuthUser::IsAdministrator()?'':' AND activated = 1'));
        if(count($user) > 0){
            $user = $user[0];
            if($user["type"] == "professor") $user["modules"] = $this->model->Get("SELECT * FROM teachs WHERE user_id = ".$id);
            $tmp = WEBROOT."img/avatars/".$user["id"].".png";
            $user["avatar"] = file_exists($tmp)?$tmp:WEBROOT."img/".$user["gender"].".png";
            $user["uploads"] = $this->model->Get("SELECT * FROM files WHERE user_id = ".$id.(AuthUser::IsAdministrator()?'':' AND confirmed = 1'));
            $d['user'] = $user;
            $this->set($d);
            $this->autoRender();
        }
        else $this->e404("User doesn't exist !!!");
    }
    function customize()
    {
        if(!AuthUser::IsAdministrator()) Func::Redirect("home");
        $d['users'] = $this->model->Get("SELECT * FROM user WHERE activated = 0");
        $this->set($d);
        $this->autoRender();
    }
    function delete($id){
        $this->model->delete($id);
    }
    function activated($id){
        $this->model->activated($id);
    }
    function edit(){
        if(count($_POST) > 0){
            $msg = new stdClass();
            $msg->message = 'success';
            try{
                $req = "UPDATE user SET firstName = \"".Form::SecureInput($_POST['firstName'])."\", lastName = \"".Form::SecureInput($_POST['lastName'])."\"";
                $password = Form::SecureInput($_POST['password']);
                if($password !== '') $req .= ", password = \"".md5($password)."\"";
                if(AuthUser::Get()["type"] !== 'guest') $req .= ", establishment_id = ".$_POST["establishment"];
                if(AuthUser::Get()["type"] === 'student') $req .= ", semester_id = ".$_POST['semester'].", lineBranch_id = ".$_POST['branch'];
                $req = $req." WHERE id = ".AuthUser::Get()["id"];
                $this->model->Exec($req);
                $user = AuthUser::Get();
                $user["firstName"] = Form::SecureInput($_POST['firstName']);
                $user["lastName"] = Form::SecureInput($_POST['lastName']);
                if(AuthUser::Get()["type"] !== 'guest') $user["establishment_id"] = $_POST["establishment"];
                if(AuthUser::Get()["type"] === 'student'){
                    $user["semester_id"] = $_POST['semester'];
                    $user["lineBranch_id"] = $_POST['branch'];
                }
                AuthUser::Set($user);
                if(AuthUser::Get()["type"] === 'professor'){
                    $this->model->Exec("DELETE FROM lineTeaching WHERE user_id = ".AuthUser::Get()["id"]);
                    foreach($_POST["module"] as $k){
                        $resT = $this->model->Get("SELECT * FROM lineTeaching WHERE user_id = ".AuthUser::Get()["id"]." AND lineModule_id = ".$k." AND establishment_id = ".$_POST["establishment"]);
                        if(count($resT) <= 0){
                            $this->model->Exec("INSERT INTO lineTeaching (user_id,lineModule_id,establishment_id) values 
                            (".AuthUser::Get()["id"].",".$k.",".$_POST["establishment"].")");
                        }
                    }
                }
            }
            catch(Exception $ex){
                $msg->message = $ex->getMessage();
            }
            Func::ToJson($msg);
        }
        if(AuthUser::Get()["type"] !== 'guest'){
            $d["establishments"] = $this->model->Get("SELECT * FROM establishment");
            if(AuthUser::Get()["type"] === 'student') $d["semesters"] = $this->model->Get("SELECT * FROM semester");
            $this->set($d);
        }
        $this->autoRender();
    }
}
?>