<?php
class authsController extends Controller
{
    function index(){
        $this->layout = "auth";
        $this->autoRender();
    }

    function login(){
        $this->layout = "auth";
        if(isset($_POST["submit"])){
            $email = Form::SecureInput($_POST["email"]);
            $password = Form::SecureInput($_POST["password"]);
            $res = (new Model())->Get("SELECT * FROM users WHERE email = \"".$email."\" AND password = \"".md5($password)."\"");
            if(count($res) > 0){
                $user = $res[0];
                if(intval($user["activated"]) === 0){
                    Notify::Set("Your account is not activated !!!","warning");
                }
                else{
                    $path = WEBROOT."img/avatars/".$user["id"].".png";
                    if(!file_exists($path)) $path = WEBROOT."img/".$user["gender"].".png";
                    $user["avatar"] = $path;
                    AuthUser::Set($user);
                    Func::Redirect(ROOT."home");
                }
            }
            else Notify::Set("User not found !!!","warning");
            $d['email'] = $email;
            $this->set($d);
        }
        $this->autoRender();
    }
    function register(){
        $this->layout = "auth";
        $d['establishments'] = $this->model->Get("SELECT * FROM establishment");
        $d['semesters'] = $this->model->Get("SELECT * FROM semester");
        $this->set($d);
        $this->autoRender();
    }
    function logout(){
        Session::Avoid("user");
        Func::Redirect(ROOT."/auths");
    }
    function jsonCheckEmail($email){
        $res = new stdClass();
        $res->message = "success";
        $email = base64_decode($email);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $res->message = "Invalid email format";
        else{
            $dt = $this->model->Get("SELECT * FROM user WHERE email = \"".$email."\"");
            if(count($dt) !== 0) $res->message = "Email exist";
        }
        header('Content-Type: application/json');
        echo json_encode($res);
        die();
    }
    function jsonRegister(){
        $res = new stdClass();
        $res->message = "success";
        try{
            $data = $_POST;
            $cols = "";$vals = "";
            foreach($data as $k => $v){
                if($k != "establishment" && $k != "branch" && $k != "module" && $k != "semester" && $k != "passwordVer"){
                    $cols .=$k.",";
                    $val = ($k == "password")?md5(Form::SecureInput($data[$k])):Form::SecureInput($data[$k]);
                    $vals .=  "\"".$val."\",";
                }
            }
            if($data["type"] !== "guest"){
                $cols .= "establishment_id,";
                $vals .= "\"".Form::SecureInput($data["establishment"])."\",";
                if($data["type"] === "student"){
                    $cols .= "lineBranch_id,";
                    $vals .= "\"".Form::SecureInput($data["branch"])."\",";
                    $cols .= "semester_id,";
                    $vals .= "\"".Form::SecureInput($data["semester"])."\",";
                }
            }
            $user_id = $this->model->Exec("INSERT INTO user (".rtrim($cols,',').") values (".rtrim($vals,',').")");
            if($data["type"] === "professor"){
                foreach($data["module"] as $k){
                    $resT = $this->model->Get("SELECT * FROM lineTeaching WHERE user_id = ".$user_id." AND lineModule_id = ".$k." AND establishment_id = ".$data["establishment"]);
                    if(count($resT) <= 0){
                        $this->model->Exec("INSERT INTO lineTeaching (user_id,lineModule_id,establishment_id) values 
                        (".$user_id.",".$k.",".$data["establishment"].")");
                    }
                }
            }
            Notify::Set("Successfully registered, you have to wait until the admin confirm your information","success");
        }
        catch (Exception $e) {
            $res->message = $e->getMessage();
        }
        Func::ToJson($res);
    }
}
?>