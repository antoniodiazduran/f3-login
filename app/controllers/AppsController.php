<?php

class AppsController extends Controller {
	protected $f3;
	protected $db;

	public function apps()
	{
        $apps = new Apps($this->schema);
		$this->f3->set('breadcrumbs','/admin/apps');
		$this->f3->set('apps',$apps->all());
		$this->f3->set('view','apps/apps.htm');
	}
    
	public function modify_apps() {
		$apps = new Apps($this->schema);
        $company = new Company($this->schema);

        $this->f3->set('breadcrumbs','/admin/apps');
		if($this->f3->exists('POST.new')) {
            $companyAll = $company->getCompany($this->f3->get('POST._company'));
            $this->f3->set('POST.Company',$companyAll[0]['fullname']);
            
			// adding form to database			
            $apps_added=$apps->add($this->f3->get('POST'));
            $this->f3->set('pass_msg','Added');
			$this->f3->set('apps',$apps->all());
			$this->f3->set('view','apps/apps.htm');
		} else {
			$this->f3->set('apps_dd',$apps->getByGroup('Name'));
            $this->f3->set('company',$company->getByGroup('fullname'));
            $this->f3->set('apps','Apps');
    
			$this->f3->set('POST.new',"new");
			$this->f3->set('POST.Name','');
			$this->f3->set('POST.Company','');
            $this->f3->set('POST._company','');
            $this->f3->set('POST.id','_');
			$this->f3->set('view','apps/appsdetails.htm');
		}

		
	}

	public function delete_apps() {
		$id = $this->f3->get('PARAMS.id');
		$apps = new Apps($this->schema);
        $company = new Company($this->schema);

		$apps->delete($id);
		$this->f3->set('breadcrumbs','/admin/apps');
		$this->f3->set('apps',$apps->all());
		$this->f3->set('view','apps/apps.htm');
	}

	public function show_apps() 
	{
        $apps = new Apps($this->schema);
        $company = new Company($this->schema);
		$id = $this->f3->get('PARAMS.id'); 

		if($this->f3->exists('POST.edit'))
        {	
            $companyAll = $company->getCompany($this->f3->get('POST._company'));
            $this->f3->set('POST.Company',$companyAll[0]['fullname']);

			$apps->edit($id, $this->f3->get('POST'));
			$this->f3->set('pass_msg','Updated');
		}
		else
		{
			$apps->getById($id);
			if($apps->dry()) { //throw a 404, order does not exist
				$this->f3->error(404);
			}
		}
        
        $this->f3->set('apps','Apps');
		$this->f3->set('breadcrumbs','/admin/apps');
        $this->f3->set('apps_dd',$apps->getByGroup('Name'));
        $this->f3->set('company',$company->getByGroup('fullname'));
		$this->f3->set('view','apps/appsdetails.htm');
	}
}
