<?php
class branchsController extends Controller
{
    function jsonBranchsByEstablishment($establishmentId){
        $res = $this->model->getBranchsByEstablishment($establishmentId);
        header('Content-Type: application/json');
        echo json_encode($res);
        die();
    }
}
?>