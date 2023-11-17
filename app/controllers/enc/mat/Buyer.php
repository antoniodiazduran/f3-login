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
    public function edit() {
                $this->f3->set('breadcrumbs','mat/buyer');
                $rids = $this->f3->get('PARAMS.id');
                if (strpos($rids,",")>0) {
                        $rids = rtrim($rids,",");
                        $sqlstrm = 'SELECT rid,Epoch,PartNumber,Description,DueDate,Buyer FROM enc_matlog WHERE rid in ('.$rids.')';
                        $record  = $this->enc->exec($sqlstrm);
                        $sqlstrd = 'SELECT d.*,m.partnumber,m.description FROM enc_duedatelog d INNER JOIN enc_matlog m  WHERE  m.rid = d.relation AND d.relation in ('.$rids.')';
                        $duedate[] = $this->enc->exec($sqlstrd);
                } else {
                        $record  = $this->enc->exec('SELECT rid,Epoch,PartNumber,Description,DueDate,Buyer FROM enc_matlog WHERE rid = ?',$this->f3->get('PARAMS.id'));
                        $duedate[] = $this->enc->exec('SELECT rid, relation, DueDate, timeStamp FROM enc_duedatelog WHERE relation = ?',$this->f3->get('PARAMS.id'));
                }
                $this->f3->set('navs','yes');
                $this->f3->set('nav_menu','navmaterial.htm');
                $this->f3->set('mode','upd');
                $this->f3->set('epoch',$rids);
//                $this->f3->set('epoch',$this->f3->get('PARAMS.id'));
                $this->f3->set('record',$record);
                $this->f3->set('duedate',$duedate[0]);
                $this->f3->set('layout','admin.htm');
                $this->f3->set('content','materials/buyer.htm');
    }
    public function upd() {
                // Getting POST variables, epoch and datetime for logs
                $reqs = $this->f3->get('POST');
                date_default_timezone_set('America/Los_Angeles');
                $datet = date('Y-m-d H:i:s',time());
                $rowv = array(
                        $reqs['duedate'],
                        $reqs['buyer']
                        );
                $logv = array(
                        $reqs['duedate'],
                        $datet
                        );
                // Updating duedate material shortage
                $sql_update  = "UPDATE enc_matlog ";
                $sql_update .= "SET DueDate=?, Buyer=? ";
                $sql_update .= "WHERE rid in (".$reqs['epoch'].")";
                $this->enc->exec($sql_update,$rowv);

                // Inserting into duedate log
                $rows = explode(",",$reqs['epoch']);
                foreach ($rows as $rw) {
                        $sql_log  = "INSERT INTO enc_duedatelog ";
                        $sql_log .= "( relation, DueDate, timeStamp ) VALUES (".$rw.",?,?)";
                        $this->enc->exec($sql_log, $logv);
                }
                // Setting up variables for the display
                $this->f3->set('nav_menu','navbuyers.htm');
                $this->f3->set('result','Record Updated !');
                $this->f3->set('layout','admin.htm');
                $this->f3->set('content','materials/status.htm');
    }

}


?>
