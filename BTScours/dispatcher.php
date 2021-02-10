<?php

class Dispatcher
{
    private $request;
    function __construct(){
        if(!isset($_SESSION)) session_start();
    }
    public function dispatch()
    {
        $this->request = new Request();
        Router::parse($this->request->url, $this->request);
        Hook::Check($this->request);
        $controller = $this->loadController();
        $class_methods = get_class_methods($controller);
        if(in_array($this->request->action,$class_methods)) call_user_func_array([$controller, $this->request->action], $this->request->params);
        else {
            if(AuthUser::IsAthenticated()) $controller->e404("This page doesn't exit !!!");
            else Func::redirect("auths/login");
            /*if(in_array("index",$class_methods)) Func::Redirect($this->request->controller);
            else Func::Redirect("home");*/
        }
    }

    public function loadController()
    {
        $name = $this->request->controller . "Controller";
        $file = DIR_ROOT . 'Controllers/' . $name . '.php';
        if(file_exists($file) === true) require($file);
        else Func::Redirect("home");
        $controller = new $name();
        return $controller;
    }
}
?>