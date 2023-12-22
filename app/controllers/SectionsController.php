<?php

class SectionsController extends Controller {  

	public function list_sections(){
		$sections = new Sections($this->stru,$this->f3->get('PARAMS.apps'));
		$this->f3->set('sectionName',$this->f3->get('PARAMS.apps'));
		$this->f3->set('i',1);
		$this->f3->set('breadcrumbs','/sections/'.$this->f3->get('PARAMS.apps'));
		$this->f3->set('groupdata',$sections->all());;
		$this->f3->set('headers',array_keys($sections->schema()));
		$this->f3->set('view','sections/sections.htm');
	}

	public function add_sections(){
		
		$structure = new Structure($this->stru);
        
		
		if($this->f3->exists('POST.new')) {
			$sections = new Sections($this->stru,$this->f3->get('POST.app'));
			$sections_added=$sections->add($this->f3->get('POST'));
			$app = $this->f3->get('POST.app');
			$this->f3->set('sectionName',$app);
			$this->f3->set('breadcrumbs','/sections/'.$app);
			$this->f3->set('groupdata',$sections->all());;
			$this->f3->set('headers',array_keys($sections->schema()));
			$this->f3->set('view','sections/sections.htm');
		} else {
			$app = $this->f3->get('PARAMS.apps');
			$grp= $structure->getBySection($app);
			$this->f3->set('sectionName',$app);
			$this->f3->set('groupdata',$grp);
			$this->f3->set('sectionName',$app);
			$this->f3->set('breadcrumbs','/sections/'.$app);
			$this->f3->set('POST.new','new');
			$this->f3->set('view','sections/sectionsdetails.htm');	
		} 
	}

	public function delete_sections() {
		$id = $this->f3->get('PARAMS.id');
		$app = $this->f3->get('PARAMS.apps');
		$sections = new Sections($this->stru,$app);
		$sections->delete($id);
		$this->f3->set('sectionName',$app);
		$this->f3->set('breadcrumbs','/sections/'.$app);
		$this->f3->set('groupdata',$sections->all());;
		$this->f3->set('headers',array_keys($sections->schema()));
		$this->f3->set('view','sections/sections.htm');
	}

	public function edit_sections() 
	{
		// Gathering parameters from uri
		$app = $this->f3->get('PARAMS.apps');
		$id = $this->f3->get('PARAMS.id'); 
		$sections = new Sections($this->stru,$app);
		echo $app;
		if($this->f3->exists('POST.edit'))
        {
			$sections->edit($id, $this->f3->get('POST'));
			$this->f3->set('pass_msg','Updated');
		} 
		
		$structure = new Structure($this->stru);
		$grp= $structure->getBySection($app);
		
		$this->f3->set('groupdata',$grp);
		$this->f3->set('sections',$sections->getById($id));
		$this->f3->set('json',json_encode($this->f3->get('POST')));
		if($sections->dry()) { //throw a 404, order does not exist
			$this->f3->error(404);
		}
		$this->f3->set('breadcrumbs','/sections/'.$app);
		$this->f3->set('sectionName',$app);
		$this->f3->set('POST.id',$id);
		$this->f3->set('POST.edit','edit');
		$this->f3->set('view','sections/sectionsdetails.htm');
	}
}
