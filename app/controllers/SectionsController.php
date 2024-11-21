<?php

class SectionsController extends Controller {  

	public function list_sections()
	{
		$sections = new Sections($this->schema,$this->f3->get('PARAMS.schema'));
		$this->f3->set('sectionName',$this->f3->get('PARAMS.schema'));
		//$this->f3->set('i',1);
		$this->f3->set('breadcrumbs','/sections/'.$this->f3->get('PARAMS.schema'));
		$this->f3->set('groupdata',$sections->x_all( $this->f3->get('PARAMS.schema') ));
		//$this->f3->set('groupdata',$sections->all());
		//$this->f3->set('headers',array_keys($sections->schema()));
		$this->f3->set('headers',$sections->arrayHeaders($this->f3->get('PARAMS.schema')) );
		$this->f3->set('view','sections/sections.htm');
	}

	public function join_fields($section) 
	{
		foreach($section as $x) 
			{
				//echo $x['_section'].':'.$x['_name'].'>'.$x['_joins'].'::'.$x['_type'].'<br>';
				if($x['_type']=='join') 
				{
					$sch = explode('.',$x['_joins']);
					$sql = "SELECT id,".$sch[1]." AS linked FROM $sch[0] ORDER BY ".$sch[1];
					//echo $sql;
					return $this->schema->exec($sql);
				} 
			}
	}

	public function add_sections()
	{
		$structure = new Schema($this->schema);
		$upld = new Upload($this->schema);
	    	$link = new Schema($this->schema);
		$section = $this->f3->get('POST.schema')==''?$this->f3->get('PARAMS.schema'):$this->f3->get('POST.schema');

		// Set today's date for date input type
		$this->f3->set('today_mdY',date('Y-m-d'));

		if($this->f3->exists('POST.new')) {
			$sections = new Sections($this->schema,$section);
			$last_id=$sections->add($this->f3->get('POST'));

			// Uploading the file
            		$upload = $upld->fileUpload($last_id,$section);
			$this->f3->set('params',$this->f3->get('PARAMS'));
			$this->f3->set('sectionName',$section);
			$this->f3->set('breadcrumbs','/sections/'.$section);
			$this->f3->set('groupdata',$sections->x_all($this->f3->get('PARAMS.schema')));;
			$this->f3->set('headers',$sections->arrayHeaders($this->f3->get('PARAMS.schema')) );
			$this->f3->set('view','sections/sections.htm');
		} else {
			$grp = $structure->getBySection($section);
			$this->f3->set('params',$this->f3->get('PARAMS'));
			$this->f3->set('joinFields',$this->join_fields($grp));
			$this->f3->set('sectionName',$section);
			$this->f3->set('groupdata',$grp);
			$this->f3->set('sectionName',$section);
			$this->f3->set('breadcrumbs','/sections/'.$section);
			$this->f3->set('POST.new','new');
			$this->f3->set('view','sections/sectionsdetails.htm');	
		} 
	}

	public function delete_sections() {
		$id = $this->f3->get('PARAMS.id');
		$section = $this->f3->get('PARAMS.schema');
		$sections = new Sections($this->schema,$section);
		$sections->delete($id);
		$this->f3->set('sectionName',$section);
		$this->f3->set('breadcrumbs','/sections/'.$section);
		$this->f3->set('groupdata',$sections->x_all( $this->f3->get('PARAMS.schema') ));
		$this->f3->set('headers',$sections->arrayHeaders($this->f3->get('PARAMS.schema')) );
		//$this->f3->set('headers',array_keys($sections->schema()));
		$this->f3->set('view','sections/sections.htm');
	}

	public function edit_sections() 
	{
		// Gathering parameters from uri
		$section = $this->f3->get('PARAMS.schema');
		$id = $this->f3->get('PARAMS.id'); 
		$sections = new Sections($this->schema,$section);
		$uplds = new Upload($this->schema);

		//echo $section;
       		//echo $this->f3->get('POST.edit');
		if($this->f3->exists('POST.edit'))
        	{
			$sections->edit($id, $this->f3->get('POST'));
			// Uploading the file
            		$upload = $uplds->fileUpload($id,$section);
			$this->f3->set('pass_msg','Updated');
		} 
		//$structure = new Schema($this->schema);
		//$grp= $structure->getBySection($schema);
		$schemaObj = new Schema($this->schema);
		$grp = $schemaObj->getBySection($section);

		$this->f3->set('groupdata',$grp);
		$this->f3->set('joinFields',$this->join_fields($grp));
		$this->f3->set('uploadFiles',$uplds->getByUId($id));
		$this->f3->set('sections',$sections->getById($id));
		$this->f3->set('json',json_encode($this->f3->get('POST')));
		if($sections->dry()) { //throw a 404, order does not exist
			$this->f3->error(404);
		}
		$this->f3->set('breadcrumbs','/sections/'.$section);
		$this->f3->set('sectionName',$section);
		$this->f3->set('POST.id',$id);
		$this->f3->set('POST.edit','edit');
		//echo "2".$this->f3->get('POST.edit');
		$this->f3->set('view','sections/sectionsdetails.htm');
	}
}
