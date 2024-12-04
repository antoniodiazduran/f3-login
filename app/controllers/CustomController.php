<?php


use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class CustomController extends Controller {  

	public function moveTimeline() {
		$tl = new Sections($this->schema,'moves_timeline');
		$timeline = $tl->all();
		$strJSON = "";
		foreach($timeline as $rows){
			$strJSON .= "['".
				$rows['unit_id']."','".
				$rows['area']."',".
				$this->datetoJson($rows['_created_at']).",";
			if(is_null($rows['nextDate'])) {
				$strJSON .=$this->datetoJson($rows['_created_at'])."],";
			} else {
				$strJSON .=$this->datetoJson($rows['nextDate'])   ."],";
			}
		}
		$this->f3->set('dataJSON',$strJSON);
		$this->f3->set('view','custom/timelinedetails.htm');
	}

	public function datetoJson($dbdate) {
		$dateStr = "new Date(" . date('Y',strtotime($dbdate)) .",". ((date('m',strtotime($dbdate))*1)-1) .",". date('d',strtotime($dbdate)) .",". date('H',strtotime($dbdate)) .",". date('i', strtotime($dbdate)) .",". date('s',strtotime($dbdate)) . ")" ;
		return $dateStr;
	}
	public function createQR($content, $filename) {
		$renderer = new ImageRenderer(
		            new RendererStyle(400),
		            new ImagickImageBackEnd()
		);
		$writer = new Writer($renderer);
		$filepath = "ui/images/".$filename;
		$writer->writeFile($content, $filepath);
	}

	public function createCode39($text) {
		barcode::code39($text,80,2);
	}

	public function add_units() 
	{
		$area = $this->f3->get('PARAMS.area');
		$uid  = $this->f3->get('PARAMS.uid');
		$sections = new Sections($this->schema,'moves');
		date_default_timezone_set('America/New_York');
		$last_id = $sections->add(array('tdate'=>date("Y-m-d H:i:s"),'area'=>$area,'unit_id'=>$uid));
		$this->f3->set('pass_msg','Succesfully loaded...'.$last_id);
		$this->f3->set('view','custom/apidetails.htm');
	}
	public function add_areas() 
	{
		$sections = new Sections($this->schema,'moves');
		$uid = $_POST['uid'];
		$area = $_POST['area'];
		$last_id = $sections->add(array('tdate'=>date("Y-m-d H:i:s"),'area'=>$area,'unit_id'=>$uid)); 
		$this->f3->set('pass_msg','Succesfully loaded...'.$last_id); 
		$this->f3->set('view','custom/apidetails.htm');
	}
	public function show_areas() 
	{
		if(isset($_POST['adding'])) {
		  $sections = new Sections($this->schema,'moves');
		  $uid = $_POST['uid'];
		  $area = $_POST['area'];
		  $last_id = $sections->add(array('tdate'=>date("Y-m-d H:i:s"),'area'=>$area,'unit_id'=>$uid)); 
		  $this->f3->set('pass_msg','Succesfully loaded...'.$last_id); 
		  $this->f3->set('view','custom/apidetails.htm');
		} else {
		  $uid  = $this->f3->get('PARAMS.uid');
		  $sections = new Sections($this->schema,'moves');
		  date_default_timezone_set('America/New_York');
		  //$last_id = $sections->add(array('tdate'=>date("Y-m-d H:i:s"),'area'=>$area,'unit_id'=>$uid));
		  //$this->f3->set('pass_msg','Succesfully loaded...'.$last_id);
		  $this->f3->set('uid',$uid);
		  $this->f3->set('view','custom/apiareadetails.htm');
		}
	}
	public function show_barcodes() 
	{
		// Gathering parameters from uri
		$section = $this->f3->get('PARAMS.schema');
		$id = $this->f3->get('PARAMS.id'); 
		$sections = new Sections($this->schema,$section);
		$colors = new Sections($this->schema,'colors');

		$revo = $sections->getById($id);
		$this->createCode39(str_replace("*","",$_POST['revo']));
		$this->createQR('https://rev.diaz.works/api/units/Prep/'.$_POST['units'],'qrcode_prep.png');
		$this->createQR('https://rev.diaz.works/api/units/Sand/'.$_POST['units'],'qrcode_sand.png');
		$this->createQR('https://rev.diaz.works/api/units/Paint/'.$_POST['units'],'qrcode_paint.png');
		$this->createQR('https://rev.diaz.works/api/units/Buff/'.$_POST['units'],'qrcode_buff.png');
		$this->createQR('https://rev.diaz.works/api/units/Rework/'.$_POST['units'],'qrcode_rework.png');
		$this->createQR('https://rev.diaz.works/api/units/QC/'.$_POST['units'],'qrcode_qc.png');
		$this->createQR('https://rev.diaz.works/api/units/Rubber/'.$_POST['units'],'qrcode_rubber.png');
		$this->createQR('https://rev.diaz.works/api/units/Wrap/'.$_POST['units'],'qrcode_wrap.png');
		$this->createQR('https://rev.diaz.works/api/units/Mask/'.$_POST['units'],'qrcode_mask.png');

		$this->createQR('https://rev.diaz.works/api/areas/'.$_POST['units'],'qrcode_areas.png');

		$this->f3->set('breadcrumbs','/sections/'.$section);
		$this->f3->set('wonumber',$_POST['units']);
		$this->f3->set('sapnumber',$_POST['sap']);
		$this->f3->set('sectionName',$section);
		$this->f3->set('colors',$colors->getByUid($_POST['units']));
		$this->f3->set('view','custom/barcodedetails.htm');
	}
}
