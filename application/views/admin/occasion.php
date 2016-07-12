<?php

error_reporting(1);
ini_set("display_errors", "on");

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Occasion extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->_setAsAdmin();
//        $this->user_model->setTable("occasion_master");
        $this->load->model('admin/admin_model');
        $this->load->model('email_model');
        $this->load->model('user_model');
        $this->user = $this->session->userdata('user');
        if ($this->user == '')
            redirect('admin');
    }

    function index() {
        redirect("admin/occasion/lists");
    }

    function editdetails($id) {
        $this->load->library('form_validation');
        if ($id != '') {
            if (isset($_POST['sub'])) {
                $this->user_model->setTable("occasion_master");
                $update_id = $this->user_model->update_by(array('occasion_id' => $id), array('image' => $_POST['imageName'], 'name' => $_POST['name'], 'updated_on' => date("Y-m-d H:i:s")));
                redirect("admin/occasion/lists");
//            echo "s";    
            } else {
                $data['occasion'] = $this->user_model->getOccasionListsByid($id);
                $output['output'] = $this->load->view("admin/occasion/edit_details", $data, true);
                $this->_render_output($output);
            }
        } else {
            redirect("admin/occasion/lists");
        }
    }

    function uploadImage() {
        if ($_FILES['ocimage']['name'] != '') {
            $ext = explode('.', $_FILES['ocimage']['name']);

            $new_file_name = md5(uniqid()) . md5(round(microtime(true) * 1000) . uniqid()) . '.' . $ext[1];
            echo $new_file_name;
            move_uploaded_file($_FILES['ocimage']['tmp_name'], 'uploads/occasion/' . $new_file_name);
            $this->gallery_path = realpath(APPPATH . '../uploads'); //fetching path
            $config1 = array(
                'source_image' => $this->gallery_path . '/occasion/' . $new_file_name, //get original image
                'new_image' => $this->gallery_path . '/thumbs/occasion/' . $new_file_name, //save as new image //need to create thumbs first
                'maintain_ratio' => true,
                'width' => 165,
                'height' => 120
            );
            $this->load->library('image_lib', $config1); //load library
            $this->image_lib->resize();
            $this->user_model->setTable("occasion_master");
            $update_id = $this->user_model->update_by(array('occasion_id' => $_POST['id']), array('image' => $new_file_name, 'updated_on' => date("Y-m-d H:i:s")));
//            echo $update_id;
        }
    }

    function create() {
        $output['output'] = $this->load->view("admin/occasion/create", '', true);
        $this->_render_output($output);
    }

    function ajaxblock() {
        $this->user_model->setTable("occasion_master");
        $user_id = $this->input->get('id');
        $block = $this->input->get('block') == 'Y' ? 'N' : 'Y';
        $update_id = $this->user_model->update_by(array('occasion_id' => $user_id), array('is_block' => $block));
        if ($block == 'Y') {
            $this->session->set_flashdata('message', "  User Blocked ");
        } else {
            $this->session->set_flashdata('message', "  User is  Unblocked ");
        }
    }

    function create_occasion() {
        if (isset($_POST)) {
            if ($_FILES['ocimage']['name'] != '') {
                $ext = explode('.', $_FILES['ocimage']['name']);
                $new_file_name = md5(uniqid()) . md5(round(microtime(true) * 1000) . uniqid()) . '.' . $ext[1];
                move_uploaded_file($_FILES['ocimage']['tmp_name'], 'uploads/occasion/' . $new_file_name);
                $this->gallery_path = realpath(APPPATH . '../uploads'); //fetching path
                $config1 = array(
                    'source_image' => $this->gallery_path . '/occasion/' . $new_file_name, //get original image
                    'new_image' => $this->gallery_path . '/thumbs/occasion/' . $new_file_name, //save as new image //need to create thumbs first
                    'maintain_ratio' => true,
                    'width' => 165,
                    'height' => 120
                );

                $this->load->library('image_lib', $config1); //load library
                $this->image_lib->resize();
            }

            $data = array("name" => $_POST['ocname'], 'image' => $new_file_name);
            $this->user_model->insertOccasion($data);
            redirect("admin/occasion/lists");
        } else {
            redirect("admin/occasion/lists");
        }
    }

    public function bulkAction($bulkaction_list = '', $occasion_id) {
        $this->user_model->setTable("occasion_master");
        $occasion_id = $this->input->post('sel');
        $bulkaction = $this->input->post('bulkaction');
        $id = $this->uri->segment(5);
        $occasion_id = $this->input->post('sel') ? $this->input->post('sel') : $id;
//        echo $occasion_id;
        $delete_id = $this->user_model->delete_by(array('occasion_id' => $occasion_id));
        $msg = ' Occasion details Successfully Deleted.!';
        $this->session->set_flashdata('message', $msg, 'SUCCESS');
        redirect('admin/occasion/lists');
    }

    public function details($id = '') {

        $this->load->model('user_model');

        $this->load->library('form_validation');

        if ($id != '') {
            $data['occasion'] = $this->user_model->getOccasionListsByid($id);
            $output['output'] = $this->load->view('admin/occasion/add_details', $data, true);
        } else {

            $output['output'] = $this->load->view('admin/occasion/add_details', '', true);
        }
        $this->_render_output($output);
    }

    public function lists() {
        $data['page_limit'] = $this->config->item('default_pagination');
        $data['key'] = (!$this->input->post('key') ? ($this->input->get('key') ? $this->input->get('key') : '') : $this->input->post('key'));
        $data['status'] = (!$this->input->post('status') ? ($this->input->get('status') ? $this->input->get('status') : '') : $this->input->post('status'));
        $data['limit'] = (!$this->input->post('limit') ? ($this->input->get('limit') ? $this->input->get('limit') : $data['page_limit']) : $this->input->post('limit'));
        $config['per_page'] = $data['limit'] == 'all' ? $config['total_rows'] : $data['limit'];
        $_REQUEST['per_page'] = $_REQUEST['per_page'] ? $_REQUEST['per_page'] : 0;
        $data['status'] = $data['status'] ? $data['status'] : 'Y';
        $config['total_rows'] = $this->user_model->getOccasionCount($data['status'], $data['key']);
        $data['userlist'] = $this->user_model->getOccasionLists($data['status'], $config['per_page'], $_REQUEST['per_page'], $data['key']);
        $params = '?t=1';
        if ($data['limit'] != '')
            $params .= '&limit=' . $data['limit'];
        if ($data['key'] != '')
            $params .= '&key=' . $data['key'];
        if ($data['status'] != '')
            $params .= '&status=' . $data['status'];
        $this->load->library('pagination');
        $config['base_url'] = site_url("admin/occasion/lists") . "/" . $params;
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
        $output['output'] = $this->load->view('admin/occasion/lists', $data, true);
        $this->_render_output($output);
    }

}

?>