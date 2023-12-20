<?php

class AutoformController extends Controller {
	protected $f3;
	protected $db;

	public function autoform()
	{
		// gathering apps name
		$app = $this->f3->get('PARAMS.apps');
        $structure = new Structure($this->stru);
        $grp= $structure->getBySection($app);
        
        $this->f3->set('groupdata',$grp);
		$this->f3->set('sectionName',$app);
        $this->f3->set('breadcrumbs','/admin/autoform');
        //$autoform = new Autoform($this->stru);
		//$this->f3->set('autoform',$autoform->all());
		$this->f3->set('view','autoform/autoformdetails.htm');
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
    
	public function modify_autoform() {
		if($this->f3->exists('POST.new')) {
			$autoform = new Autoform($this->stru);
			$autoform_added=$autoform->add($this->f3->get('POST'));
			//$this->f3->set('pass_msg','Added');
			$this->f3->set('autoform',$autoform->all());
			$this->f3->set('view','autoform/autoform.htm');
		} else {
			
            
			$this->f3->set('view','autoform/autoformdetails.htm');
		}

		
	}

	public function delete_autoform() {
		$id = $this->f3->get('PARAMS.id');
		$autoform = new Autoform($this->stru);
		$autoform->delete($id);
		$this->f3->set('autoform',$autoform->all());
		$this->f3->set('view','autoform/autoform.htm');
	}

	public function show_autoform() 
	{
		$id = $this->f3->get('PARAMS.id'); 
		if($this->f3->exists('POST.edit'))
                {
			$autoform = new Autoform($this->stru);
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
			$autoform->edit($id, $this->f3->get('POST'));
			$this->f3->set('pass_msg','Updated');
		}
		else
		{
			$autoform = new Autoform($this->stru);
			$autoform->getById($id);

			if($autoform->dry()) { //throw a 404, order does not exist
				$this->f3->error(404);
			}
		}
		$this->f3->set('autoform',$autoform->all());
		$this->f3->set('view','autoform/autoformdetails.htm');
	}
}
