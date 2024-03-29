<?php

class CompanyController extends Controller {
	protected $f3;
	protected $db;

	public function company()
	{
        $company = new Company($this->schema);
		$this->f3->set('company',$company->all());
		$this->f3->set('view','company/company.htm');
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

	public function modify_company() {
		if($this->f3->exists('POST.new')) {
			$company = new Company($this->schema);
			$company_added=$company->add($this->f3->get('POST'));
			//$this->f3->set('message','Added');
		} else {
			$this->f3->set('POST.new',"new");
			$this->f3->set('POST.id',"_");
			$this->f3->set('POST.Name',"");
			$this->f3->set('POST.shortname',"");
			$this->f3->set('POST.slogan',"");
		}

		$this->f3->set('view','company/companydetails.htm');
	}

	public function delete_company() {
		$id = $this->f3->get('PARAMS.id');
		$company = new Company($this->schema);
		$company->delete($id);
		$this->f3->set('company',$company->all());
		$this->f3->set('view','company/company.htm');
	}

	public function show_company() 
	{
		$id = $this->f3->get('PARAMS.id'); 
		if($this->f3->exists('POST.edit'))
                {
			$company = new Company($this->schema);
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
			$company->edit($id, $this->f3->get('POST'));
			//$this->f3->set('message','Updateted');
		}
		else
		{
			$company = new Company($this->schema);
			$company->getById($id);

			if($company->dry()) { //throw a 404, order does not exist
				$this->f3->error(404);
			}
		}
		$company = new Company($this->schema);
		$company->getById($id);
		$this->f3->set('company',$company->all());
		$this->f3->set('view','company/companydetails.htm');
	}
}
