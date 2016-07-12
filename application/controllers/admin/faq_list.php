<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//error_reporting(E_ALL);
class faq_list extends MY_Controller {

	/**
	 * Location Page for this controller.
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
	 
	 * $this->load->library('crud');
	 * load crud library if u want to perform basic listing/add/edit and
	 * other similar stuffs
	 * $this->load->library('preferences');
	 * for showing a configuration table where users can only update fields
	 */	
public function __construct(){
	
				parent::__construct();
				$this->_setAsAdmin();
				$this->load->model('admin/faq_model');
				$this->user 	= $this->session->userdata('user');
				if($this->user=='')
					redirect('admin');		
}
public function index(){
	redirect('admin/faq_list/lists');		
	
}
public function add($faq_id){


			if($_SERVER['REQUEST_METHOD']=='POST'){
				$Question=$_POST['Question'];	 
				$Answer=$_POST['Answer'];	 
      
        $arr = array("Question" => $Question,
            "Answer" => $Answer,
        );
		        if($_POST['faq_id']!='')
				{
		        $update_id = $this->faq_model->upddata($_POST['faq_id'],$arr);
				}
				else
				{
				$update_id = $this->faq_model->adddetails('cms_master',$arr);
				}
				$this->session->set_flashdata('message', 'Details Added Successfully','SUCCESS');
				redirect('admin/faq_list/lists');
				
			}else{
			if($faq_id!='')
			{
			$data['faq_ans']                  = $this->faq_model->get_faq_data($faq_id);
			$page						      =	($_REQUEST['pageno'])?$_REQUEST['pageno']:'';
			$output['output']                 = $this->load->view('admin/faq/add',$data,true);//loading success view
			}
			else
			{
			$page						      =	($_REQUEST['pageno'])?$_REQUEST['pageno']:'';
			$output['output']                 = $this->load->view('admin/faq/add',$data,true);//loading success view
			}
		     $this->_render_output($output);
			}
			
}

public function lists(){

             //for pagination
			$config					= array();
			$config					= $this->pagination();
			$this->load->library('pagination');
			$data['total_rows']= getConfigValue('default_pagination');
			//$data['total_rows'] 	= 2;

			$_REQUEST['limit'] 		= (!$_POST['limit'] ? ($_GET['limit'] ? $_GET['limit'] :$data['total_rows']):$_POST['limit']);
			$_REQUEST['key'] 		= (!$_POST['key'] ? ($_GET['key'] ? $_GET['key'] :''):$_POST['key']);
			$_REQUEST['status'] 		= (!$_POST['status'] ? ($_GET['status'] ? $_GET['status'] :''):$_POST['status']);
			$params = '?t=1';
				if($_REQUEST['limit']) $params .= '&limit='.$_REQUEST['limit'];
				if($_REQUEST['key']) $params .= '&key='.$_REQUEST['key'];
				if($_REQUEST['status']) $params .= '&status='.$_REQUEST['status'];
			$config['base_url'] 	= site_url($this->user->root."/faq_list/lists")."/".$params;
            $config['total_rows']	= $this->faq_model->getPromoCount($_REQUEST['key']);
		    $config['per_page']   	= $_REQUEST['limit'] == 'all' ? $config['total_rows']:$_REQUEST['limit'];
		    $data['page'] 			= $_REQUEST['per_page'];
		    $data['limit'] 			= $_REQUEST['limit'];
			$data['key'] 			= $_REQUEST['key'];
			$data['status'] 		= $_REQUEST['status'];
			
		    $this->pagination->initialize($config);	

		    $data['promolist']   = $this->faq_model->getAllFaq($_REQUEST['status'],$_REQUEST['key'],$config['per_page'],$_REQUEST['per_page']);
			
		//----------------------------------------------------------
	   //echo "here";exit;
       $output['SUB_TITLE'] = 'promocodes List';               
       $output['output']=$this->load->view('admin/faq/lists',$data, true);
	   $this->_render_output($output);
}	
	

public function bulkAction($bulkaction_list='',$location_id){	
	
			$bulkaction =  $this->input->post('bulkaction');
			$location_id=$this->uri->segment(5);
			$location_id = $this->input->post('sel')?$this->input->post('sel'):$location_id;
			
		if($bulkaction=='')
			$bulkaction	=	'delete';
			
		if($bulkaction){
			if($location_id){
				switch($bulkaction){
					case 'delete':
					
						$delete_id = $this->location_model->bulkDelete($location_id);					
						$this->session->set_flashdata('message', 'Restaurant(s) Successfully Deleted ');
						
						break;
					case 'inactive':
						$update_id = $this->restaurant_model->bulkUpdate(array('restaurant_id'=>$res_id), array('status'=>'N'));	
							//echo "<pre>";  print_r(COUNT($owner_id)); echo "</pre>"; exit;
							if((COUNT($location_id)) == 1)
								$msg = 'User details updated successfully' ;
							else
								$msg = COUNT($location_id).' Restaurant details Successfully Updated.!' ;
							$this->session->set_flashdata('message', $msg ,'SUCCESS');	
						break;
					case 'active':
						$update_id = $this->restaurant_model->bulkUpdate(array('restaurant_id'=>$res_id), array('status'=>'Y'));						
						
							if(COUNT($location_id) == 1)
								$msg = 'User details updated successfully' ;
							else
								$msg = COUNT($location_id).' Restaurant details Successfully Updated.!' ;
							$this->session->set_flashdata('message', $msg ,'SUCCESS');	
						
						break;
				}
			  }  
			 else{
				$this->session->set_flashdata('message', 'Please select at least one member.! ','ERROR');	
			  }
		}
		redirect('admin/location/lists');
}



public function pagination(){
		
			$config['page_query_string'] = TRUE;
			$config['first_link'] = 'First';
			$config['last_link'] = 'Last';

			$config['full_tag_open'] = "<ul class='pagination'>";
			$config['full_tag_close'] ="</ul>";
			$config['num_tag_open'] = '<li>';
			$config['num_tag_close'] = '</li>';
			$config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#' class='btn-info btn'>";
			$config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
			$config['next_tag_open'] = "<li>";
			$config['next_tagl_close'] = "</li>";
			$config['prev_tag_open'] = "<li>";
			$config['prev_tagl_close'] = "</li>";
			$config['first_tag_open'] = "<li>";
			$config['first_tagl_close'] = "</li>";
			$config['last_tag_open'] = "<li>";
			$config['last_tagl_close'] = "</li>";			
			
			return $config;		
		
		
}

public function generatePromo(){
	
		   $this->load->helper('string');
		   $promo= random_string('alpha',8);              
		   echo $promo;
	          
}
public function check_exist(){
	            
       	 $promocode=$_POST['promocode'];
		 $restaurant_id=$_POST['restaurant_id'];
		// echo $promo_code = $this->faq_model->checkExist($promocode,$restaurant_id);			
	          
}
public function delete($faq_id){
	//$member_id	    = $member_id?$member_id:($this->input->get('member_id')?$this->input->get('member_id'):0);	
            $this->faq_model->bulkDelete($faq_id);
		    $this->session->set_flashdata('message', 'User Details successfully deleted.!' ,'SUCCESS');	
		    redirect($this->user->root.'admin/faq_list/lists?limit='.$_REQUEST['limit'].'& per_page='.$_REQUEST['per_page']);
} 

public function ajaxblock(){
  
			$id 		=	$this->input->post('id');
			$block		=	$this->input->post('is_block') == 'Y' ? 'N':'Y';
			$this->faq_model->UpdateDetails('cms_master',array('status'=>$block),array('faq_id'=>$id));					
				if($block=='Y')
				{						
					$this->session->set_flashdata('message', "  User Unblocked ");
				}
				else{
					$this->session->set_flashdata('message', "  User Blocked ");
				}
			$this->session->set_flashdata('class', "success");
			
			
}	
}

	
?>
