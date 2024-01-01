<?php

class PaymentsController extends Controller {
	protected $f3;
	protected $db;

	public function aptName($id) {
		$apartment = new Apartments($this->bpllc);
        $apartment->getRecord($id);
        return $apartment->Name;
	}

	public function all()
	{
        $payment = new Payments($this->bpllc);
        $this->f3->set('apartment','::');
        $this->f3->set('apartmentName','');
		$this->f3->set('payments',$payment->all());
		$this->f3->set('view','payments/payments.htm');
	}
    
	public function add_payments() {
        $apt = $this->f3->get('PARAMS.id');
		if($this->f3->exists('POST.new')) 
        {
        	$payment = new Payments($this->bpllc);
			$payment_added=$payment->add($this->f3->get('POST'));
            $apt = $this->f3->get('POST.Apartment');
            echo $apt;
            $this->f3->set('apartment',$apt);
			$this->f3->set('apartmentName',$this->aptName($apt));
            $this->f3->set('payments',$payment->getByApartment($apt));
            $this->f3->set('view','payments/payments.htm');
		} else {
			$this->f3->set('POST.new',"new");
			$this->f3->set('POST.id',"_");
			$this->f3->set('POST.Apartment',$apt);
			$this->f3->set('apartmentName',$this->aptName($apt));
			$this->f3->set('POST.TransactionDate',"");
			$this->f3->set('POST.Amount',"0");
			$this->f3->set('POST.LateFee',"0");
            $this->f3->set('POST.Notes',"");
            $this->f3->set('apartment',$apt);
            $this->f3->set('view','payments/paymentsdetails.htm');
		}
       
	}

	public function delete_payments() {
		$id = $this->f3->get('PARAMS.id');
        $apt = $this->f3->get('PARAMS.apt');
		$payment = new Payments($this->bpllc);
		$payment->delete($id);
        $this->f3->set('apartment',$apt);
		$this->f3->set('apartmentName',$this->aptName($apt));
		$this->f3->set('payments',$payment->getByApartment($apt));
		$this->f3->set('view','payments/payments.htm');
	}

    public function edit_payments() {
        $id = $this->f3->get('PARAMS.id'); 
		$payment = new Payments($this->bpllc);
        if($this->f3->exists('POST.edit')) {
            $payment->edit($id, $this->f3->get('POST'));
        }
        $this->f3->set('POST.edit',"edit");
        $this->f3->set('apartment',$id);
		$this->f3->set('payments',$payment->getById($id));
		$this->f3->set('view','payments/paymentsdetails.htm');
    }

	public function show_payments() 
	{
		$id = $this->f3->get('PARAMS.id'); 
		$payment = new Payments($this->bpllc);
        $this->f3->set('apartment',$id);
		$this->f3->set('apartmentName',$this->aptName($id));
		$this->f3->set('payments',$payment->getByApartment($id));
		$this->f3->set('view','payments/payments.htm');
	}
}
