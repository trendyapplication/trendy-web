<?php
class Preferences_model extends CI_Model {

	public function __construct()
	{
		$this->load->database();

	}
			
	#get admin preferences details
	function getAdminSettings()
	{
		$sql = "select * from  user_master where user_type='1'";
		$query = $this->db->query($sql);
		return $query->row_array();
	}
	
	
	function getAdminpreference()
	{
		$sql = "select * from  EE_preferences where id='1'";
		$query = $this->db->query($sql);
		return $query->row_array();
	}
	
	#add new email address 
	function add_new_email()
	{
			
		$id		    =			$_REQUEST['id'];
		
		$arr 		= array(
							'user_email' => $_REQUEST['email_id']
							);
		
		
		if($id){
			
			 $this->db->where('user_id',$id);
			 $id = $this->db->update('EE_user_master',$arr);
			 
					 
		}
			
		return $id;
	}
	
	
	
	
	
	function change_password()
	{
		
		$session_data 	=	 $this->session->userdata('logged_in');
			
		$uname 			= 	$session_data['username'];
		
		
		$old_password					=	mysql_escape_string($_POST['old_password']);
		
		$new_password					=	mysql_escape_string($_POST['new_password']);
		
		$confirm_password				=	mysql_escape_string($_POST['confirm_password']);
		
		$query_sel		=	"select * from EE_user_master  where username='$uname' and password='$old_password'";
	   
		$res_sel		=	mysql_query($query_sel);
		
		$dd				=	mysql_fetch_array($res_sel);
		
		$un				=	$dd['username'];
		
		//echo $un.'<br/>'.$uname.'<br/>'.$new_password.'<br/>'.$confirm_password;exit;
	
		
		if($un==$uname && $new_password==$confirm_password)
		{
			
			$query	=	"update EE_user_master set password='$new_password' where username='$uname' ";
			
			$res	=	mysql_query($query);
			
			return true;
		}
		else
		{
			return false;
		}
	}
	
	
	function change_preference()
	{
		
		
		$authkey					=	mysql_escape_string($_POST['authkey']);
		
		$version					=	mysql_escape_string($_POST['version']); 
		
		
		
	//	if($version=='1'){$version='1';}
		
		//else{$version='1.0';}
			
			$query	=	"update EE_preferences set authkey='$authkey' , version='$version' where id='1' ";
			//echo $query;
			$res	=	mysql_query($query); 
			return true;
		
	}
	
	
	function getPreferences()
	{
		$sql = "select * from  cp_preferences where 1";
		$query = $this->db->query($sql);
		return $query->row_array();
	}
	
	
	
	#add new radius  
	function add_new_radius()
	{
			
		$id		    =			$_REQUEST['id'];
		
		$arr 		= array(
							'radius' => $_REQUEST['radius']
							);
		
		
		if($id){
			
			 $this->db->where('id',$id);
			 $id = $this->db->update('cp_preferences',$arr);
			 			 
		}
		
		return $id;
	}
	
	

	

	
} //end of class Category_model


	
	
