<?php


use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class CustomController extends Controller {  

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

	public function add_api() 
	{
		$area = $this->f3->get('PARAMS.area');
		$uid  = $this->f3->get('PARAMS.uid');
		$sections = new Sections($this->schema,'moves');
	
		$last_id = $sections->add(array('area'=>$area,'unit_id'=>$uid));
		$this->f3->set('pass_msg','Succesfully loaded...'.$last_id);
		$this->f3->set('view','custom/apidetails.htm');
	}
	public function show_barcodes() 
	{
		// Gathering parameters from uri
		$section = $this->f3->get('PARAMS.schema');
		$id = $this->f3->get('PARAMS.id'); 
		$sections = new Sections($this->schema,$section);

		$revo = $sections->getById($id);
		$this->createCode39(str_replace("*","",$_POST['revo']));
		$this->createQR('https://rev.diaz.works/api/units/prep/'.$_POST['id'],'qrcode_prep.png');
		$this->createQR('https://rev.diaz.works/api/units/sand/'.$_POST['id'],'qrcode_sand.png');
		$this->createQR('https://rev.diaz.works/api/units/paint/'.$_POST['id'],'qrcode_paint.png');
		$this->createQR('https://rev.diaz.works/api/units/buff/'.$_POST['id'],'qrcode_buff.png');

		$this->f3->set('breadcrumbs','/sections/'.$section);
		$this->f3->set('wonumber',$_POST['units']);
		$this->f3->set('sapnumber',$_POST['sap']);
		$this->f3->set('sectionName',$section);
		$this->f3->set('view','custom/barcodedetails.htm');
	}
}
