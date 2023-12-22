<?php

class StructureController extends Controller {
	protected $f3;
	protected $db;

	public function structure()
	{
        $structure = new Structure($this->stru);
		$this->f3->set('structure',$structure->all());
		$this->f3->set('view','structure/structure.htm');
	}

	private function check_password($pw, $confirm)
	{
		if(strlen($pw) < 8)
		{
			return $this->f3->get('i18n_password_too_short');
		}
		else if($pw != $confirm)
		{
			return $this->f3->get('i18n_user_wrong_confirm');
		}
		else 
		{
			return "";
		}
	}
    
	public function modify_structure() {
		$structure = new Structure($this->stru);
		if($this->f3->exists('POST.new')) {
			// adding form to database
			$structure_added=$structure->add($this->f3->get('POST'));
			//$this->f3->set('pass_msg','Added');
			$this->f3->set('structure',$structure->all());
			$this->f3->set('view','structure/structure.htm');
		} else {
			$this->f3->set('section_dd',$structure->getByGroup('_section'));
			$this->f3->set('POST.new',"new");
			$this->f3->set('POST.id',"_");
            $this->f3->set('POST._section',"");
			$this->f3->set('POST._name',"");
			$this->f3->set('POST._type',"");
			$this->f3->set('POST._label',"");
			$this->f3->set('POST._name',"");
			$this->f3->set('POST._idx',"");
            $this->f3->set('POST._length',"");
            $this->f3->set('POST._required',"");
            $this->f3->set('POST._value',"");
            $this->f3->set('POST._class',"");
			$this->f3->set('POST._style',"");
            $this->f3->set('POST._function',"");
			$this->f3->set('POST._order',"");
			$this->f3->set('POST._placeholder',"");
			$this->f3->set('POST._event',"");
            $this->f3->set('structure','');
			$this->f3->set('view','structure/structuredetails.htm');
		}

		
	}

	public function delete_structure() {
		$id = $this->f3->get('PARAMS.id');
		$structure = new Structure($this->stru);
		$structure->delete($id);
		$this->f3->set('structure',$structure->all());
		$this->f3->set('view','structure/structure.htm');
	}

	public function show_structure() 
	{
		$id = $this->f3->get('PARAMS.id'); 
		if($this->f3->exists('POST.edit'))
                {
			$structure = new Structure($this->stru);
			$structure->edit($id, $this->f3->get('POST'));
			$this->f3->set('pass_msg','Updated');
		}
		else
		{
			$structure = new Structure($this->stru);
			$structure->getById($id);

			if($structure->dry()) { //throw a 404, order does not exist
				$this->f3->error(404);
			}
		}
		$this->f3->set('section_dd',$structure->getByGroup('_section'));
		$this->f3->set('structure',$structure->all());
		$this->f3->set('view','structure/structuredetails.htm');
	}
}
