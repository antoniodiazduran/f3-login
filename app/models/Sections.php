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
		date_default_timezone_set('America/New_York');
		return date("Y-m-d H:i:s");
	}

	public function __construct(DB\SQL $db,$table) 
	{
		parent::__construct($db,$table);
	}
	function strHeader($tbl) 
	{
		return "SELECT _name, _label FROM schema WHERE _section = '$tbl' AND _visible = 'YES' AND _name <> 'id' ORDER BY _order";
	}
    	public function arrayHeaders($tbl) 
	{
		// getting the array headers
		$res = $this->db->exec($this->strHeader($tbl));
		$res_array = [];
		$res_array[] = 'id';
		// loading into simple array
		foreach($res as &$value) {
			$res_array[] = $value["_label"];
		}
		return $res_array;
	}
	public function stringHeaders($tbl) 
	{
		$res = $this->db->exec($this->strHeader($tbl));
		$fields = "id,";
		// creating string with fields
		foreach($res as &$value) {
			$fields .= $value["_name"].",";
		}
		$fields = rtrim($fields,",");
		return $fields;
	}
	public function x_all($tbl) 
	{ //get some fields
		$fields = $this->stringHeaders($tbl);
		return $this->db->exec('SELECT '.$fields.' FROM '.$tbl.' ORDER BY id');
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
		$data = $unsanitizeddata;
		$data['_created_at']=$this->getCurrentdate();
		$data['_updated_at']=$this->getCurrentdate();
		$this->copyFrom($data);
		$this->save();
		return $this->id;
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
	public function getByUid($id) 
	{
echo "getbyuid:".$id;
		$this->load(array('uid=?',$id));
		return $this->query;
	}

	public function edit($id, $unsanitizeddata)
	{
		//$data=$this->sanitizeInput($unsanitizeddata, $this->allowed_fields);
		$data=$unsanitizeddata;
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
