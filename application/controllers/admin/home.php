<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class home extends MY_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */	
	public function __construct()
	{
		parent::__construct();
		$this->_setAsAdmin();
		$this->user 	= $this->session->userdata('user');
		if($this->user=='')
			redirect('admin');	
	}
	 public function index()
	{	
		$this->load->model('user_model');		
		$data['template_url']   = $this->template_url ;
		$data['userscount']	 = $this->user_model->count_all();
		$this->user_model->setTable('general_config');		
		$data['config_count']	 = $this->user_model->count_all();
		$ouput['output']		= $this->load->view('admin/'.$this->admin_theme.'/home',$data,true);		
		$this->_render_output($ouput);
	}
		
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */