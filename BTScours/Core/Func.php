<?php 

class Func{
    public static function redirect($url,$code = null){
       if($code == 301) header("HTTP/1.1 301 Moved Permanently") ;
       header("Location: ".ROOT.$url) ;
    }
    public static function getFiles($file,$name = "file"){
        $res = $file[$name];
        $files = array();
        foreach($res as $k => $v){
            foreach($v as $tk=>$tv){
                if(!isset($files[$tk])) $files[$tk] = new stdClass();
                $files[$tk]->$k = $tv;
            }
        }
        return $files;
    }
    public static function ToJson($obj){
        header('Content-Type: application/json');
        echo json_encode($obj);
        die();
    }
    public static function VerifyFiles($file,$name = "file"){
        $maxsize = 8 * 1024 * 1024;
        $files = Func::getFiles($file,$name);
        $errors = array();
        foreach($files as $k => $v){
            if(intval($v->error) == 0){
                $allowed = array('gif', 'png', 'jpeg', 'jpg','pdf','rar');
                $ext = pathinfo(strtolower($v->name), PATHINFO_EXTENSION);
                if (!in_array($ext, $allowed)) {
                    $errors[$k] = $v;
                    $errors[$k]->message = "Extension not allowed !!!";
                }
                else{
                    if(intval($v->size) > $maxsize){
                        $errors[$k] = $v;
                        $errors[$k]->message = "Your file is too large !!!";
                    }
                }
            }
            else {
                $errors[$k] = $v;
                $errors[$k]->message = "Undefined error !!!";
            }
        }
        return $errors;
    }
    public static function make_dir( $path, $permissions = 0777 ) {
        return is_dir( $path ) || mkdir( $path, $permissions, true );
    }
    public static function debug($var){
        $debug = debug_backtrace() ;
        echo '<p>&nbsp;</p>
        <p>
          <a href="#" onclick="$(this).parent().next(\'ol\').slideToggle(); return false;">
            <strong>'.$debug[0]['file'].'</strong>l.'.$debug[0]['line'].'
          </a>
        </p>' ;
        echo '<ol style="display:none;">' ;
        foreach($debug as $k=>$v){
          if($k>0){
            echo '<li><strong>'.$v['file'].'</strong> l . '.$v['line'].'</li>' ;
          }
        }
        echo '</ol>' ;
        echo '<pre>' ;
        print_r($var) ;
        echo '</pre>' ;
        die() ;
    }
    
    public static function dateNow(){
      date_default_timezone_set('Africa/Casablanca') ;
      setlocale(LC_TIME, 'French_Monaco') ;
      return ucfirst(strftime("%A")).' le '.Date('d/m/Y').', à '.Date('H:i:s') ;
    }
}

?>