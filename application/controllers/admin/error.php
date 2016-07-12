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
class error extends MY_Controller {

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
//        $array_p = array("brand" => $id);
        $this->user_model->del_error($array);
        redirect("admin/error");
    }

    function lists() {
        redirect("admin/error/");
    }

   
    function index() {
        $data['page_limit'] = $this->config->item('default_pagination');

        $data['key'] = (!$this->input->post('key') ? ($this->input->get('key') ? $this->input->get('key') : '') : $this->input->post('key'));
        $data['status'] = (!$this->input->post('status') ? ($this->input->get('status') ? $this->input->get('status') : '') : $this->input->post('status'));
//        print_r($data['status']);
//        exit;
        $data['limit'] = (!$this->input->post('limit') ? ($this->input->get('limit') ? $this->input->get('limit') : $data['page_limit']) : $this->input->post('limit'));
        $config['per_page'] = $data['limit'] == 'all' ? $config['total_rows'] : $data['limit'];
        $_REQUEST['per_page'] = $_REQUEST['per_page'] ? $_REQUEST['per_page'] : 0;
        $data['status'] = $data['status'] ? $data['status'] : 'Y';
        $config['total_rows'] = $this->user_model->getErrorCount($data['status'], $data['key']);
        $data['userlist'] = $this->user_model->getErrorList($data['status'], $config['per_page'], $_REQUEST['per_page'], $data['key']);

        //$data['userlist']=$this->user_model->get_all();

        $params = '?t=1';
        if ($data['limit'] != '')
            $params .= '&limit=' . $data['limit'];
        if ($data['key'] != '')
            $params .= '&key=' . $data['key'];
        if ($data['status'] != '')
            $params .= '&status=' . $data['status'];
        $this->load->library('pagination');

        $config['base_url'] = site_url("admin/error/lists") . "/" . $params;

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
        $output['output'] = $this->load->view('admin/error/lists', $data, true);
        $this->_render_output($output);
    }

}
