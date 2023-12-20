<?php

class ExpensesController extends Controller {
	protected $f3;
	protected $db;

	public function aptName($id) {
		$apartment = new Apartments($this->bpllc);
        $apartment->getRecord($id);
        return $apartment->Name;
	}

    public function all()
	{
        $expense = new Expenses($this->bpllc);
        $this->f3->set('apartment','::');
        $this->f3->set('apartmentName','');
		$this->f3->set('expenses',$expense->all());
		$this->f3->set('view','expenses/expenses.htm');
	}
    
	public function add_expenses() {
        $apt = $this->f3->get('PARAMS.id');
		if($this->f3->exists('POST.new')) 
        {
        	$expense = new Expenses($this->bpllc);
			$expense_added=$expense->add($this->f3->get('POST'));
            $apt = $this->f3->get('POST.Apartment');
            echo "add".$apt;

        	$this->f3->set('apartmentName',$this->aptName($apt));
            $this->f3->set('apartment',$apt);
            $this->f3->set('expenses',$expense->getByApartment($apt));
            $this->f3->set('view','expenses/expenses.htm');
		} else {
			$this->f3->set('POST.new',"new");
			$this->f3->set('POST.id',"_");
			$this->f3->set('POST.Apartment',$apt);
			$this->f3->set('POST.TransactionDate',"");
			$this->f3->set('POST.Amount',"0.00");
			$this->f3->set('POST.Supplier',"");
            $this->f3->set('POST.Notes',"");
            $this->f3->set('apartment',$apt);
            $this->f3->set('view','expenses/expensesdetails.htm');
		}
       
	}

	public function delete_expenses() {
		$id = $this->f3->get('PARAMS.id');
        $apt = $this->f3->get('PARAMS.apt');
		$expense = new Expenses($this->bpllc);
		$expense->delete($id);
        $this->f3->set('apartment',$apt);
        $this->f3->set('apartmentName',$this->aptName($apt));
		$this->f3->set('expenses',$expense->getByApartment($apt));
		$this->f3->set('view','expenses/expenses.htm');
	}

    public function edit_expenses() {
        $id = $this->f3->get('PARAMS.id'); 
		$expense = new Expenses($this->bpllc);
        if($this->f3->exists('POST.edit')) {
            $expense->edit($id, $this->f3->get('POST'));
        }
     echo "edit".$id;
        $this->f3->set('POST.edit',"edit");
        $this->f3->set('apartment',$id);
		$this->f3->set('expenses',$expense->getById($id));
		$this->f3->set('view','expenses/expensesdetails.htm');
    }

	public function show_expenses() 
	{
		$id = $this->f3->get('PARAMS.id'); 
		$expense = new Expenses($this->bpllc);
        
        $this->f3->set('apartment',$id);
        $this->f3->set('apartmentName',$this->aptName($id));
		$this->f3->set('expenses',$expense->getByApartment($id));
		$this->f3->set('view','expenses/expenses.htm');
	}
}
