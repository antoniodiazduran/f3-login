<?php

class Apps extends DB\SQL\Mapper {

/* only these db fields are allowed to be changed */
	protected $allowed_fields = array(
		"Name",
		"Company",
	);

    public function __construct(DB\SQL $db) 
	{
		parent::__construct($db,'apps');
	}

	private function sanitizeInput(array $data, array $fieldNames) 
	{ //sanitize input - with thanks to richgoldmd
	   return array_intersect_key($data, array_flip($fieldNames));
	}

	private function getCurrentdate()
	{
		return date("Y-m-d H:i:s");
	}

	public function all() 
	{ //get all records
		$this->load();
		return $this->query;
	}

	public function add( $unsanitizeddata )
	{
		$data=$this->sanitizeInput($unsanitizeddata, $this->allowed_fields);
		//check if name already exists in db
		/*$this->load(array('_section=?',$data['_section']));
		if(!$this->dry())
		{
			return 10;
		}*/
        $data['_user_type']=$this->f3->get('SESSION.user_type');
        //$data['_company']=$this->f3->get('SESSION.company');
		$data['_created_at']=$this->getCurrentdate();
		$data['_updated_at']=$this->getCurrentdate();
		$this->copyFrom($data);
		$this->save();
		return 1;
	}

	public function getByGroup($grp){
		$this->load(null,array('group'=>$grp));
		return $this->query;

	}
	public function getBySection($name)
	{
		$this->load(array('_section=?', $name),array('order'=>'_order asc'));
		return $this->query;
	}

	public function getById($id) 
	{
		$this->load(array('id=?',$id));
		$this->copyTo('POST');
	}

	public function edit($id, $unsanitizeddata)
	{
		$data=$this->sanitizeInput($unsanitizeddata, $this->allowed_fields);
		$data['_updated_at']=$this->getCurrentdate();
		$this->load(array('id=?',$id));
		$this->copyFrom($data);
		$this->update();
	}
	public function editupd($id)
	{
		$data['_updated_at']=$this->getCurrentdate();
		$this->load(array('id=?',$id));
		$this->copyFrom($data);
		$this->update();
	}

	public function delete($id) 
	{
		$this->load(array('id=?',$id));
		$this->erase();
	}
}
