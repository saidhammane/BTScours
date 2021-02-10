<?php 

    class Hook{
        public static function Check($request){
            if(strpos($request->url,"json") === false){
                //Func::debug($request);
                if($request->controller != 'auths'){
                    if(!AuthUser::IsAthenticated()){
                        Func::Redirect("auths");
                    }
                }
            }
        }
    }

?>