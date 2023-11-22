<?php

namespace enc\mat;

class Supplier extends \Controller {

   public function edit() {
                $this->f3->set('breadcrumbs','enc/mat/suppliers');
                $rids = $this->f3->get('PARAMS.id');
                if (strpos($rids,",")>0) {
                        $rids = rtrim($rids,",");
                        $sqlstrm = 'SELECT rid,Epoch,PartNumber,Description,DueDate,supplier FROM enc_matlog WHERE rid in ('.$rids.')';
                        $record  = $this->enc->exec($sqlstrm);
                } else {
                        $record  = $this->enc->exec('SELECT rid,Epoch,PartNumber,Description,DueDate,supplier FROM enc_matlog WHERE rid = ?',$this->f3->get('PARAMS.id'));
                }
                $this->f3->set('navs','no');
                $this->f3->set('nav_menu','enc/mat/nav/buyers.htm');
                $this->f3->set('mode','upd');
                $this->f3->set('epoch',$rids);
                $this->f3->set('record',$record);
                $this->f3->set('layout','admin.htm');
                $this->f3->set('view','enc/mat/buyers/supplier.htm');
    }
    public function upd() {
                // Getting POST variables, epoch and datetime for logs
                $reqs = $this->f3->get('POST');
                date_default_timezone_set('America/Los_Angeles');
                $datet = date('Y-m-d H:i:s',time());
                $rowv = array(
                        $reqs['supplier'],
                        );
                // Updating duedate material shortage
                $sql_update  = "UPDATE enc_matlog ";
                $sql_update .= "SET supplier=? ";
                $sql_update .= "WHERE rid in (".$reqs['epoch'].")";
                $this->enc->exec($sql_update,$rowv);

                // Setting up variables for the display
                $this->f3->set('nav_menu','enc/mat/nav/buyers.htm');
                $this->f3->set('result','Record Updated !');
                $this->f3->set('layout','admin.htm');
                $this->f3->set('view','enc/mat/status.htm');
    }

}


?>
