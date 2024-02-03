<?php

class Menus extends DB\SQL\Mapper {

	public function __construct(DB\SQL $db) 
	{
		parent::__construct($db,'menus');
	}

    public function allSections() {
		$this->load(null, array('group'=>'section'));
        return $this->query;
	}
	public function allItems() {
		$this->load(null, array('group'=>'item','order'=>'section, _order asc'));
		return $this->query;
	}
	
	public function usertype($user_type) 
	{   
		//get all records except for 'menus'
		if($user_type<100) {
			$this->load(array('_section!=?','menus'),array('order'=>'_section asc, _order asc'));
		} 
		else 
		{
			$this->load();
		}
		return $this->query;
	}

	
}
