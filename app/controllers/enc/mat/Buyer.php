<?php

namespace enc\mat;

class Buyer extends \Controller {

    public function api() {
                $sqlstr  = "SELECT *, (SELECT substr(customer,1,3) FROM enc_so WHERE m.UnitID = AX) AS Customer,(SELECT ship FROM enc_so WHERE m.UnitID = AX) AS ship ";
                $sqlstr .= "FROM enc_matlog m ";
//              $sqlstr .= "WHERE arriveddate is null or arriveddate = '' ";
                $sqlstr .= "ORDER BY DateTime ";
                $data[] = $this->enc->exec($sqlstr);
//              $json_data = json_encode($data);
//              echo $json_data;
                $this->f3->set('details',$data);
                $this->f3->set('layout','plain.htm');
                $this->f3->set('content','enc/mat/api.htm');
//              exit;
    }
    public function list() {
                $fld = $this->f3->get('PARAMS.field');
                $val = $this->f3->get('PARAMS.value');
                if ($fld == '') {
                $data[] = $this->enc->exec("SELECT *, (SELECT substr(customer,1,3) FROM enc_so WHERE m.UnitID = AX) AS Customer,(SELECT ship FROM enc_so WHERE m.UnitID = AX) AS ship FROM enc_matlog m WHERE arriveddate is null ORDER BY rid DESC LIMIT 150");
                } else {
                $data[] = $this->enc->exec("SELECT *, (SELECT substr(customer,1,3) FROM enc_so WHERE m.UnitID = AX) AS Customer,(SELECT ship FROM enc_so WHERE m.UnitID = AX) AS ship FROM enc_matlog m WHERE $fld = ? AND arriveddate is null ORDER BY rid DESC",$val);
                }
                $this->f3->set('ourip',$_SERVER['REMOTE_ADDR']);
                $this->f3->set('details',$data);
		$this->f3->set('rowcount',count($data[0]));
                $this->f3->set('breadcrumbs','enc/mat');
                $this->f3->set('field','all');
                $this->f3->set('navs','yes');
                $this->f3->set('columns','[1,2,3,4,5,6,7,8,9,10,11,12]');
                $this->f3->set('bgcolor','red');
                $this->f3->set('nav_menu','enc/mat/nav/buyers.htm');
                $this->f3->set('headers','enc/mat/buyers/headers.htm');
                $this->f3->set('fields','enc/mat/buyers/fields.htm');
//                $this->f3->set('view','layout.htm');
//                $this->f3->set('layout','layout.htm');
                $this->f3->set('view','enc/mat/list.htm');
    }

}


?>
