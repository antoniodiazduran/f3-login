<?php

class SectionsController extends Controller {  

	public function list_sections()
	{
		$sections = new Sections($this->schema,$this->f3->get('PARAMS.schema'));
		$this->f3->set('sectionName',$this->f3->get('PARAMS.schema'));
		//$this->f3->set('i',1);
		$this->f3->set('breadcrumbs','/sections/'.$this->f3->get('PARAMS.schema'));
		$this->f3->set('groupdata',$sections->all());;
		$this->f3->set('headers',array_keys($sections->schema()));
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
        $link = new Schema($this->schema);
		
		if($this->f3->exists('POST.new')) {
			$sections = new Sections($this->schema,$this->f3->get('POST.schema'));
			$sections_added=$sections->add($this->f3->get('POST'));
			$schema = $this->f3->get('POST.schema');
			$this->f3->set('sectionName',$schema);
			$this->f3->set('breadcrumbs','/sections/'.$schema);
			$this->f3->set('groupdata',$sections->all());;
			$this->f3->set('headers',array_keys($sections->schema()));
			$this->f3->set('view','sections/sections.htm');
		} else {
			$schema = $this->f3->get('PARAMS.schema');
			$grp= $structure->getBySection($schema);
					
			$this->f3->set('joinFields',$this->join_fields($grp));
			$this->f3->set('sectionName',$schema);
			$this->f3->set('groupdata',$grp);
			$this->f3->set('sectionName',$schema);
			$this->f3->set('breadcrumbs','/sections/'.$schema);
			$this->f3->set('POST.new','new');
			$this->f3->set('view','sections/sectionsdetails.htm');	
		} 
	}

	public function delete_sections() {
		$id = $this->f3->get('PARAMS.id');
		$schema = $this->f3->get('PARAMS.schema');
		$sections = new Sections($this->schema,$schema);
		$sections->delete($id);
		$this->f3->set('sectionName',$schema);
		$this->f3->set('breadcrumbs','/sections/'.$schema);
		$this->f3->set('groupdata',$sections->all());;
		$this->f3->set('headers',array_keys($sections->schema()));
		$this->f3->set('view','sections/sections.htm');
	}

	public function edit_sections() 
	{
		// Gathering parameters from uri
		$schema = $this->f3->get('PARAMS.schema');
		$id = $this->f3->get('PARAMS.id'); 
		$sections = new Sections($this->schema,$schema);
		//echo $schema;
		if($this->f3->exists('POST.edit'))
        {
			$sections->edit($id, $this->f3->get('POST'));
			$this->f3->set('pass_msg','Updated');
		} 
		
		//$structure = new Schema($this->schema);
		//$grp= $structure->getBySection($schema);
		$schemaObj = new Schema($this->schema);
		$grp = $schemaObj->getBySection($schema);
		
		$this->f3->set('groupdata',$grp);
		$this->f3->set('joinFields',$this->join_fields($grp));
		$this->f3->set('sections',$sections->getById($id));
		$this->f3->set('json',json_encode($this->f3->get('POST')));
		if($sections->dry()) { //throw a 404, order does not exist
			$this->f3->error(404);
		}
		$this->f3->set('breadcrumbs','/sections/'.$schema);
		$this->f3->set('sectionName',$schema);
		$this->f3->set('POST.id',$id);
		$this->f3->set('POST.edit','edit');
		$this->f3->set('view','sections/sectionsdetails.htm');
	}
}
