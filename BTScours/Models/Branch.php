<?php
class Branch extends Model{
    public function getBranchsByEstablishment($establishmentId){
        return $this->Get("SELECT l.*,b.name branch,b.abbreviated,e.name establishment
        FROM establishment e,lineBranch l, branch b 
        WHERE e.id = l.establishment_id and b.id = l.branch_id and e.id = ".$establishmentId);
    }
}
?>