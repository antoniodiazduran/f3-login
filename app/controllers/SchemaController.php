<?php

class SchemaController extends Controller {
	protected $f3;
	protected $db;

	public function schema()
	{
        $schema = new Schema($this->schema);
		$this->f3->set('breadcrumbs','/admin/schema');
		$this->f3->set('schema',$schema->all());
		$this->f3->set('view','schema/schema.htm');
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
    
	public function modify_schema() {
		$schema = new Schema($this->schema);
		if($this->f3->exists('POST.new')) {
			// adding form to database
			$schema_added=$schema->add($this->f3->get('POST'));
			//$this->f3->set('pass_msg','Added');
			$this->f3->set('breadcrumbs','/admin/schema');
			$this->f3->set('schema',$schema->all());
			$this->f3->set('view','schema/schema.htm');
		} else {
			$this->f3->set('section_dd',$schema->getByGroup('_section'));
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
            $this->f3->set('schema','');
			$this->f3->set('breadcrumbs','/admin/schema');
			$this->f3->set('view','schema/schemadetails.htm');
		}

		
	}

	public function delete_schema() {
		$id = $this->f3->get('PARAMS.id');
		$schema = new Schema($this->schema);
		$schema->delete($id);
		$this->f3->set('breadcrumbs','/admin/schema');
		$this->f3->set('schema',$schema->all());
		$this->f3->set('view','schema/schema.htm');
	}

	public function show_schema() 
	{
		$id = $this->f3->get('PARAMS.id'); 
		if($this->f3->exists('POST.edit'))
                {
			$schema = new Schema($this->schema);
			$schema->edit($id, $this->f3->get('POST'));
			$this->f3->set('pass_msg','Updated');
		}
		else
		{
			$schema = new Schema($this->schema);
			$schema->getById($id);

			if($schema->dry()) { //throw a 404, order does not exist
				$this->f3->error(404);
			}
		}
		$this->f3->set('breadcrumbs','/admin/schema');
		$this->f3->set('section_dd',$schema->getByGroup('_section'));
		$this->f3->set('schema',$schema->all());
		$this->f3->set('view','schema/schemadetails.htm');
	}
}
