<?php
class homeController extends Controller
{
    function index(){
        $this->autoRender();
    }
    function info(){
        $this->autoRender();
    }
    function jsonInfos(){
        if( strpos($_POST['query'],'insert') !== false || 
            strpos($_POST['query'],'update') !== false ||
            strpos($_POST['query'],'delete') !== false) return ;
        if(!AuthUser::IsAthenticated()) return ;
        Func::ToJson($this->model->Get($_POST['query']));
    }
}
?>