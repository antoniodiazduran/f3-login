<?php

class ApartmentsController extends Controller {
	protected $f3;
	protected $db;

	public function apartments()
	{
        $apartment = new Apartments($this->bpllc);
		$this->f3->set('apartments',$apartment->all());
		$this->f3->set('view','apartments/apartments.htm');
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
    
	public function modify_apartments() {
		if($this->f3->exists('POST.new')) {
			$apartment = new Apartments($this->bpllc);
			$apartment_added=$apartment->add($this->f3->get('POST'));
			//$this->f3->set('message','Added');
		} else {
			$this->f3->set('POST.new',"new");
			$this->f3->set('POST.id',"_");
			$this->f3->set('POST.Name',"");
			$this->f3->set('POST.Address',"");
			$this->f3->set('POST.State',"");
			$this->f3->set('POST.Zipcode',"");
		}

		$this->f3->set('view','apartments/apartmentsdetails.htm');
	}

	public function delete_apartments() {
		$id = $this->f3->get('PARAMS.id');
		$apartment = new Apartments($this->bpllc);
		$apartment->delete($id);
		$this->f3->set('apartments',$apartment->all());
		$this->f3->set('view','apartments/apartments.htm');
	}

	public function show_apartments() 
	{
		$id = $this->f3->get('PARAMS.id'); 
		if($this->f3->exists('POST.edit'))
        {
			$apartment = new Apartments($this->bpllc);
			$apartment->edit($id, $this->f3->get('POST'));
		}
		else
		{
			$apartment = new Apartments($this->bpllc);
			$apartment->getById($id);

			if($apartment->dry()) { //throw a 404, order does not exist
				$this->f3->error(404);
			}
		}
		$this->f3->set('apartments',$apartment->all());
		$this->f3->set('view','apartments/apartmentsdetails.htm');
	}
}
