<?php
    class Controller
    {
        protected $request;
        var $vars = [];
        public $layout = "default";
        protected $model;

        function __construct()
        {
            $this->request = new Request();
            Router::parse($this->request->url, $this->request);
            $tmp = rtrim(ucfirst($this->request->controller),"s");
            require(DIR_ROOT . 'Models/'.$tmp.'.php');
            $this->model = new $tmp($this);
        }

        function set($d)
        {
            $this->vars = array_merge($this->vars, $d);
        }

        function render($view)
        {
            extract($this->vars);
            ob_start();
            if(strpos($view,'/')===0) $view = DIR_ROOT.'Views'.$view.'.php' ; 
            else $view = DIR_ROOT . "Views/" . ucfirst($this->request->controller) . '/' . $view . '.php' ;
            require($view);
            $content_for_layout = ob_get_clean();
            if ($this->layout == false) $content_for_layout;
            else require(DIR_ROOT . "Views/Layouts/" . $this->layout . '.php');
        }

        function autoRender()
        {
            $this->render($this->request->action);
        }

        function e404($message){
            header('HTTP/1.0 404 Not Found') ;
            $d["message"] = $message;
            $this->set($d) ;
            $this->render('/errors/404') ;
            die() ;
        }
        function first(){
            echo "sdfv";
        }

    }
?>