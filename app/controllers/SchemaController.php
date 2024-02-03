<?php

class SchemaController extends Controller {
	protected $f3;
	protected $db;

	public function schema()
	{
        $schema = new Schema($this->schema);
		$this->f3->set('breadcrumbs','/admin/schema');
		$this->f3->set('schema',$schema->all($this->f3->get('SESSION.user_type')));
		$this->f3->set('view','schema/schema.htm');
	}

	public function show_table_schema() {
		$app = $this->f3->get('PARAMS.table');
		$schema = new Schema($this->schema);
		$structure = $schema->table_schema($app);
		$table_sql = "CREATE TABLE IF NOT EXISTS ".$app."  (\n";
		foreach($structure as $key=>$value){
			if ($value['_name']=='id') {
				$table_sql .= "id INTEGER PRIMARY KEY AUTOINCREMENT,\n";
			} else {
				if($value['_sql_type']=='REAL'){
					$table_sql .= $value['_name']." ".$value['_sql_type']." DEFAULT 0.00,\n";
				}
				if($value['_sql_type']=='INTEGER'){
					$table_sql .= $value['_name']." ".$value['_sql_type']." DEFAULT 0,\n";
				}
				if($value['_sql_type']=='TEXT' OR $value['_sql_type']=='BLOB'){
					$table_sql .= $value['_name']." ".$value['_sql_type']." DEFAULT NULL,\n";
				}
			}
		}
		$table_sql .= "_created_at TEXT DEFAULT NULL,\n";
		$table_sql .= "_updated_at TEXT DEFAULT NULL\n";
		$table_sql .= ");";
		$this->f3->set('sqlstring',$table_sql);
		$this->f3->set('view','schema/script.htm');
	}

	
	public function modify_schema() {
		$schema = new Schema($this->schema);
		if($this->f3->exists('POST.new')) {
			// adding form to database
			$schema_added=$schema->add($this->f3->get('POST'));
			//$this->f3->set('pass_msg','Added');
			$this->f3->set('breadcrumbs','/admin/schema');
			$this->f3->set('schema',$schema->all($this->f3->get('SESSION.user_type')));
			$this->f3->set('joins',$schema->joins());
			$this->f3->set('view','schema/schema.htm');
		} else {
			$this->f3->set('section_dd',$schema->getByGroup('_section'));
			$this->f3->set('POST.new',"new");
			$this->f3->set('POST.id',"_");
            $this->f3->set('POST._section',"");
			$this->f3->set('POST._name',"");
			$this->f3->set('POST._type',"");
			$this->f3->set('POST._sql_type',"");
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
			$this->f3->set('POST._joins',"");
            $this->f3->set('schema','');
			$this->f3->set('joins',$schema->joins());
			$this->f3->set('breadcrumbs','/admin/schema');
			$this->f3->set('view','schema/schemadetails.htm');
		}

		
	}

	public function delete_schema() {
		$id = $this->f3->get('PARAMS.id');
		$schema = new Schema($this->schema);
		$schema->delete($id);
		$this->f3->set('breadcrumbs','/admin/schema');
		$this->f3->set('schema',$schema->all($this->f3->get('SESSION.user_type')));
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
		$this->f3->set('schema',$schema->all($this->f3->get('SESSION.user_type')));
		$this->f3->set('joins',$schema->joins());
		$this->f3->set('view','schema/schemadetails.htm');
	}
}
