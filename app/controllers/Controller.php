<?php

class Controller {

	protected $f3;
	protected $usr;
	protected $schema;

    function beforeroute() {
		if($this->f3->get('SESSION.logged_in'))
		{
			if(time() - $this->f3->get('SESSION.timestamp') > $this->f3->get('auto_logout')) 
			{
				$this->f3->clear('SESSION');
				$this->f3->reroute('/login');
			} 
			else {
				$this->f3->set('SESSION.timestamp', time());
			}
		}
		$csrf_page = $this->f3->get('PARAMS.0'); //URL route !with preceding slash!
		//var_dump($this->f3->get('SESSION'));
		if( NULL === $this->f3->get('POST.session_csrf') )
		{
			$this->f3->CSRF = $this->f3->session->csrf();
			//$this->f3->copy('CSRF','SESSION.'.$csrf_page.'.csrf');  // used for same Controller without mixing GET & POST
			$this->f3->copy('CSRF','SESSION.session_csrf');
		}

		if ($this->f3->VERB==='POST')
		{
			//if(  $this->f3->get('POST.session_csrf') ==  $this->f3->get('SESSION.'.$csrf_page.'.csrf') ) 
			if(  $this->f3->get('POST.session_csrf') ==  $this->f3->get('SESSION.session_csrf') ) 
			{	// Things check out! No CSRF attack was detected.
				$this->f3->set('CSRF', $this->f3->session->csrf()); // Reset csrf token for next post request
				//$this->f3->copy('CSRF','SESSION.'.$csrf_page.'.csrf');  // copy the token to the variable POST only
				$this->f3->copy('CSRF','SESSION.session_csrf');  // Used for GET and POST mixed
			}
			else
			{	// DANGER: CSRF attack!
				$this->f3->error(403); 
			}
		}
		// Menu creation based on database
		//echo $this->f3->get('SESSION.user_type');
		$this->f3->set('menurows',$this->menu_sections());
		$this->f3->set('menuitem',$this->menu_items());
		$this->f3->set('datarows',$this->data_sections());

		// Access to files by permission
		$access=Access::instance();
		$access->policy('allow'); // allow access to all routes by default
		$access->deny('/admin*');
		$access->deny('/enc*');

		// admin routes
		$access->allow('/admin*','100'); //100 = admin ; 10 = superuser ; 1 = user
		$access->allow('/admin/company/*');
		$access->deny('/user*');
		// superuser routes
		$access->allow('/enc*',['100','10']);
		// user login routes
		$access->allow('/user*',['100','10','1']);

		// Granting access to routes
		$access->authorize($this->f3->exists('SESSION.user_type') ? $this->f3->get('SESSION.user_type') : 0 );

    }

	public function menu_sections() {
		$menu = new Menus($this->schema);
		return $menu->allSections($this->f3->get('SESSION.company'));
	}
	public function data_sections() {
		$menu = new Menus($this->schema);
		return $menu->alldatabase();
	}
	public function menu_items() {
		$menu = new Menus($this->schema);
		return $menu->allItems($this->f3->get('SESSION.company'));
	}
	public function afterroute() {
		$this->f3->set('isMobile',$this->isMobile());
		echo Template::instance()->render('layout.htm');
	}

	public function isMobile()  {
		return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
        }

	function __construct() {
		// creating instance
		$f3=Base::instance();
		// setting global variables based on DNS
		$usr=new DB\SQL($f3->get('usr_dns'));
		$schema=new DB\SQL($f3->get('schema_dns'));
		// assigning global variables
		$this->f3=$f3;
		$this->usr=$usr;
		$this->schema=$schema;
	}

}
