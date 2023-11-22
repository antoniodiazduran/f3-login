<?php

namespace enc\mat;

class DueDate extends \Controller {

    public function edit() {
                $this->f3->set('breadcrumbs','enc/mat/buyers');
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
		$this->f3->set('rowcount','');
                $this->f3->set('mode','upd');
                $this->f3->set('epoch',$rids);
//                $this->f3->set('epoch',$this->f3->get('PARAMS.id'));
                $this->f3->set('record',$record);
                $this->f3->set('duedate',$duedate[0]);
                $this->f3->set('view','enc/mat/buyers/edit.htm');
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
                $this->f3->set('result','Record Updated !');
                $this->f3->set('view','enc/mat/status.htm');
    }

}


?>
