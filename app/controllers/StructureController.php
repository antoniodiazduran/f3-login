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
		if($this->f3->exists('POST.new')) {
			$structure = new Structure($this->stru);
			$structure_added=$structure->add($this->f3->get('POST'));
			//$this->f3->set('message','Added');
		} else {
			$this->f3->set('POST.new',"new");
			$this->f3->set('POST.id',"_");
            $this->f3->set('POST._section',"");
			$this->f3->set('POST._name',"");
			$this->f3->set('POST._type',"");
			$this->f3->set('POST._name',"");
			$this->f3->set('POST._id',"");
            $this->f3->set('POST._length',"");
            $this->f3->set('POST._required',"");
            $this->f3->set('POST._value',"");
            $this->f3->set('POST._class',"");
            $this->f3->set('POST._function',"");
            $this->f3->set('structure','');
		}

		$this->f3->set('view','structure/structuredetails.htm');
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
			$pw = '';
			if(strlen($pw)===0)
			{ //do not change password, reset to hash in database
				$this->f3->set('POST.password',$this->f3->get('POST.pw'));
			}
			else
			{
				$pwcheck = $this->check_password( $pw , $this->f3->get('POST.confirm'));
				if (strlen($pwcheck) > 0)
				{
					$this->f3->set('message', $pwcheck);
				}
				else
				{
					$crypt = \Bcrypt::instance();
					$password = $crypt->hash($this->f3->get('POST.password'));

					$this->f3->set('message', "Password changed");
					$this->f3->set('POST.password', $password);
				}
			}
			$structure->edit($id, $this->f3->get('POST'));
		}
		else
		{
			$structure = new Structure($this->stru);
			$structure->getById($id);

			if($structure->dry()) { //throw a 404, order does not exist
				$this->f3->error(404);
			}
		}
		$this->f3->set('structure',$structure->all());
		$this->f3->set('view','structure/structuredetails.htm');
	}
}
