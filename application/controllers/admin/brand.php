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
class brand extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->_setAsAdmin();
//echo "df";
        $this->load->model('admin/admin_model');
        $this->load->model('email_model');
        $this->load->model('user_model');
//        date_default_timezone_set("Asia/Riyadh");
        $this->user = $this->session->userdata('user');
        if ($this->user == '')
            redirect('admin');
    }

    function delete($id) {
        $array = array("id" => $id);
        $array_p = array("brand" => $id);
        $this->user_model->del_brand($array, $array_p, $id);
        redirect("admin/brand");
    }

    function lists() {
        redirect("admin/brand/");
    }

    function editdetails($id) {
        $this->load->library('form_validation');
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $array = array("brand" => $_POST['name'], "update_on" => date("Y-m-d H:i:s"));
            $cond = array("id" => $id);
            $this->user_model->update_brand($array, $cond);
            redirect("admin/brand/");
        } else {
            $data['brand'] = $this->user_model->get_brand_details($id);
            $output['output'] = $this->load->view('admin/brand/edit_details', $data, true);
            $this->_render_output($output);
        }
    }

    function create() {
        $this->load->library('form_validation');
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $array = array("brand" => $_POST['name']);
//            $cond=array("id"=>$id);
            $this->user_model->ins_brand($array);
            redirect("admin/brand/");
        } else {

            $output['output'] = $this->load->view('admin/brand/create', '', true);
            $this->_render_output($output);
        }
    }

    function details($id) {
        $this->load->library('form_validation');
        $data['brand'] = $this->user_model->get_brand_details($id);
        $output['output'] = $this->load->view('admin/brand/add_details', $data, true);
        $this->_render_output($output);
    }

    function index() {
        $data['page_limit'] = $this->config->item('default_pagination');
        $data['key'] = (!$this->input->post('key') ? ($this->input->get('key') ? $this->input->get('key') : '') : $this->input->post('key'));
        $data['status'] = (!$this->input->post('status') ? ($this->input->get('status') ? $this->input->get('status') : '') : $this->input->post('status'));
        $data['limit'] = (!$this->input->post('limit') ? ($this->input->get('limit') ? $this->input->get('limit') : $data['page_limit']) : $this->input->post('limit'));
        $config['per_page'] = $data['limit'] == 'all' ? $config['total_rows'] : $data['limit'];
        $_REQUEST['per_page'] = $_REQUEST['per_page'] ? $_REQUEST['per_page'] : 0;
        $data['status'] = $data['status'] ? $data['status'] : 'Y';
        $config['total_rows'] = $this->user_model->getBrandCount($data['status'], $data['key']);
        $data['userlist'] = $this->user_model->getBrandList($data['status'], $config['per_page'], $_REQUEST['per_page'], $data['key']);
        $params = '?t=1';
        if ($data['limit'] != '')
            $params .= '&limit=' . $data['limit'];
        if ($data['key'] != '')
            $params .= '&key=' . $data['key'];
        if ($data['status'] != '')
            $params .= '&status=' . $data['status'];
        $this->load->library('pagination');
       $config['base_url'] = site_url("admin/brand") . "/" . $params;
	   

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
        $output['output'] = $this->load->view('admin/brand/lists', $data, true);
        $this->_render_output($output);
    }

}
