<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of brand
 *
 * @author aneesh
 */
class State extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->_setAsAdmin();

        $this->load->model('admin/admin_model');

        $this->load->model('email_model');
        $this->load->model('country_model');

        //  $this->load->model(' 	 ');
//        date_default_timezone_set("Asia/Riyadh");
        $this->user = $this->session->userdata('user');
        if ($this->user == '')
            redirect('admin');
    }

    function city_list() {

        $data['page_limit'] = $this->config->item('default_pagination');
        $data['key'] = (!$this->input->post('key') ? ($this->input->get('key') ? $this->input->get('key') : '') : $this->input->post('key'));
        $data['status'] = (!$this->input->post('status') ? ($this->input->get('status') ? $this->input->get('status') : '') : $this->input->post('status'));
        $data['limit'] = (!$this->input->post('limit') ? ($this->input->get('limit') ? $this->input->get('limit') : $data['page_limit']) : $this->input->post('limit'));
        $config['per_page'] = $data['limit'] == 'all' ? $config['total_rows'] : $data['limit'];
        $_REQUEST['per_page'] = $_REQUEST['per_page'] ? $_REQUEST['per_page'] : 0;

        $data['status'] = $data['status'] ? $data['status'] : 'Y';
        $config['total_rows'] = $this->country_model->getstateCount($data['key'], $config['per_page'], $_REQUEST['per_page']);
        $data['userlist'] = $this->country_model->getstateLists($data['status'], $config['per_page'], $_REQUEST['per_page'], $data['key']);
        $params = '?t=1';

        if ($data['limit'] != '')
            $params .= '&limit=' . $data['limit'];
        if ($data['key'] != '')
            $params .= '&key=' . $data['key'];
        if ($data['status'] != '')
            $params .= '&status=' . $data['status'];
        $this->load->library('pagination');

        $config['base_url'] = site_url("admin/state/city_list") . "/" . $params;

        //--------------------------------------------------
        // load pagination class
        $config['page_query_string'] = TRUE;
        $config['first_link'] = 'First';
        $config['last_link'] = 'Last';
        $config['full_tag_open'] = "<ul class='pagination'>";
        $config['full_tag_close'] = "</ul>";
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
        $config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
        $config['next_tag_open'] = "<li>";
        $config['next_tagl_close'] = "</li>";
        $config['prev_tag_open'] = "<li>";
        $config['prev_tagl_close'] = "</li>";
        $config['first_tag_open'] = "<li>";
        $config['first_tagl_close'] = "</li>";
        $config['last_tag_open'] = "<li>";
        $config['last_tagl_close'] = "</li>";
        $data['page'] = $_REQUEST['per_page'];
        $this->pagination->initialize($config);
//        print_r($data);
        $output['output'] = $this->load->view('admin/country/state_lists', $data, true);
        $this->_render_output($output);
    }

    function edit_statedetails($id) {
        $this->load->library('form_validation');
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		if(!$id)
		{
		$array = array("city" => $_POST['city_name'], "state" => $_POST['state'], "country" => $_POST['country_name']);
		
			$this->country_model->insert_state($array);
		}
		else
		{
		   
		   $url = "http://maps.google.com/maps/api/geocode/json?address=".$_POST['city_name'].",".$_POST['state']."&sensor=false&region=India";
       $ch = curl_init();
       curl_setopt($ch, CURLOPT_URL, $url);
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
       curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
       curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
       curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
       $response = curl_exec($ch);
       curl_close($ch);
       $response_a = json_decode($response);
 $lat = $response_a->results[0]->geometry->location->lat;
 $long = $response_a->results[0]->geometry->location->lng;
		   print_r($_POST);
		   exit;
		    $array_post = array("posted_locatn" => $_POST['city_name'],'lat' => $lat,'long'=>$long);
            $cond_post = array("posted_locatn" => $_POST['city_name_old']);
            $this->country_model->update_state_post($array, $cond);
		   
		   
            $array = array("city" => $_POST['city_name'], "state" => $_POST['state'], "country" => $_POST['country_name']);
            $cond = array("id" => $id);
            $this->country_model->update_state($array, $cond);
			}
            redirect("admin/state/city_list");
        } else {
            $data['brand'] = $this->country_model->get_state_details($id);
            $data['country_list'] = $this->country_model->getUserLists();

            $output['output'] = $this->load->view('admin/country/edit_state_details', $data, true);
            $this->_render_output($output);
        }
    }

    function delete_state($id) {
        $this->country_model->delete_sate($id);
        redirect("admin/state/city_list");
    }

}
