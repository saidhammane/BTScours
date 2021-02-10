<?php
    class Model
    {
        public $bdd = null;
        //protected $controller ;
        function __construct() {
            $tmp = Conf::$conf[Conf::$name];
            $this->bdd = new PDO("mysql:host=".$tmp["host"].";dbname=".$tmp["dbname"]."",$tmp["username"], $tmp["password"],
            array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
            $this->bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            //if($ctr != null && isset($ctr)) $this->controller = $ctr ;
        }
        public function Get($query){
            //Func::debug($query);
            $req = $this->bdd->prepare($query);
            $req->execute();
            return $req->fetchAll(PDO::FETCH_ASSOC);
        }
        public function Exec($query){
            $stmt = $this->bdd->prepare($query);
            $stmt->execute();
            if(strpos(strtolower($query),"insert") !== false) return $this->bdd->lastInsertId();
            return 0;
        }
        public function Delete($id){
            if(AuthUser::IsAdministrator()) $this->Exec("DELETE FROM ".get_class($this)." WHERE id = ".$id);
        }
        public function FindAll(){
            return $this->Get("SELECT * FROM ".get_class($this));
        }
        public function FindOne($id){
            return $this->Get("SELECT * FROM ".get_class($this)." WHERE id = '".$id."'")[0];
        }
    }
?>