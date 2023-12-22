<?php

class Sections extends DB\SQL\Mapper {

/* only these db fields are allowed to be changed */
	protected $allowed_fields = array(
		
	);

	private function sanitizeInput(array $data, array $fieldNames) 
	{ //sanitize input - with thanks to richgoldmd
	   return array_intersect_key($data, array_flip($fieldNames));
	}

	private function getCurrentdate()
	{
		return date("Y-m-d H:i:s");
	}

	public function __construct(DB\SQL $db,$table) 
	{
		parent::__construct($db,$table);
	}

	public function all() 
	{ //get all records
		$this->load();
		return $this->query;
	}

	public function add( $unsanitizeddata )
	{
		//$data=$this->sanitizeInput($unsanitizeddata, $this->allowed_fields);
		//check if name already exists in db
		/*$this->load(array('_section=?',$data['_section']));
		if(!$this->dry())
		{
			return 10;
		}*/
		$data['created_at']=$this->getCurrentdate();
		$data['updated_at']=$this->getCurrentdate();
		$this->copyFrom($data);
		$this->save();
		return 1;
	}

	public function x_getByName($name)
	{
		$this->load(array('_section=?', $name));
	}

	public function getById($id) 
	{
		$this->load(array('id=?',$id));
		$this->copyTo('POST');
	}

	public function edit($id, $unsanitizeddata)
	{
		//$data=$this->sanitizeInput($unsanitizeddata, $this->allowed_fields);
		$data=$unsanitizeddata;
		$data['updated_at']=$this->getCurrentdate();
		$this->load(array('id=?',$id));
		$this->copyFrom($data);
		$this->update();
	}
	public function editupd($id)
	{
		$data['updated_at']=$this->getCurrentdate();
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
