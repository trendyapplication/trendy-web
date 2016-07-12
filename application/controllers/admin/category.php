<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of category
 *
 * @author aneesh
 */
ini_set("display_errors", "on");

class Category extends MY_Controller {

    public function __construct() {
//       
        parent::__construct();
        $this->_setAsAdmin();
//         echo "dss";
//        $this->user_model->setTable("occasion_master");
        $this->load->model('admin/admin_model');
        $this->load->model('email_model');
        $this->load->model('user_model');
        $this->user = $this->session->userdata('user');
        if ($this->user == '')
            redirect('admin');
    }

    function index() {
//        echo "adsd";
//        exit;
        redirect("admin/category/lists");
    }

    public function lists() {
//        die("sads");
//exit;
        $data['page_limit'] = $this->config->item('default_pagination');

        $data['key'] = (!$this->input->post('key') ? ($this->input->get('key') ? $this->input->get('key') : '') : $this->input->post('key'));
        $data['status'] = (!$this->input->post('status') ? ($this->input->get('status') ? $this->input->get('status') : '') : $this->input->post('status'));
//        print_r($data['status']);
//        exit;
        $data['limit'] = (!$this->input->post('limit') ? ($this->input->get('limit') ? $this->input->get('limit') : $data['page_limit']) : $this->input->post('limit'));
        $config['per_page'] = $data['limit'] == 'all' ? $config['total_rows'] : $data['limit'];
        $_REQUEST['per_page'] = $_REQUEST['per_page'] ? $_REQUEST['per_page'] : 0;
        $data['status'] = $data['status'] ? $data['status'] : '1';
//        $config['total_rows'] = $this->user_model->getCategoryCount($data['status'], $data['key']);
        $data['userlist'] = $this->user_model->getCategoryLists($data['status'], $config['per_page'], $_REQUEST['per_page'], $data['key']);

        //$data['userlist']=$this->user_model->get_all();

        $params = '?t=1';
        if ($data['limit'] != '')
            $params .= '&limit=' . $data['limit'];
        if ($data['key'] != '')
            $params .= '&key=' . $data['key'];
        if ($data['status'] != '')
            $params .= '&status=' . $data['status'];
        $this->load->library('pagination');

        $config['base_url'] = site_url("admin/category/lists") . "/" . $params;

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

        $output['output'] = $this->load->view('admin/category/lists', $data, true);
        $this->_render_output($output);
    }

    public function bulkAction($bulkaction_list = '', $category_id) {
//        echo "ds";
//        exit;
        $this->user_model->setTable("category");
        $occasion_id = $this->input->post('sel');
        $bulkaction = $this->input->post('bulkAction');

        $id = $this->uri->segment(5);
        $category_id = $this->input->post('sel') ? $this->input->post('sel') : $id;
//        print_r($user_id);
//        exit;
        if ($bulkaction == '') {
            $bulkaction = 'delete';
        }
//        echo $bulkaction;
//        exit;
        if ($bulkaction) {
//            echo $category_id;
//            exit;
            if ($category_id) {
                switch ($bulkaction) {
                    case 'delete':
//                        foreach ($category_id as $userid) {
//                            echo($userid);
//                            exit;
                        $delete_id = $this->user_model->delete_category(array('category_id' => $category_id), $category_id);
                        echo $delete_id;
//                            $delete_id = $this->user_model->delete_userdata($occasion_id);
//                        }
//						$update_id = $this->user_model->update_by(array('member_id'=>$user_id), array('status'=>'T'));				
                        $this->session->set_flashdata('message', 'Category(s) Successfully Deleted ');
                        break;
                    case 'inactive':
                        $update_id = $this->user_model->update_by(array('category_id' => $category_id), array('visible' => '0'));
                        if ($update_id) {
                            if (sizeof($category_id) == 1)
                                $msg = 'Category details updated successfully';
                            else
                                $msg = sizeof($category_id) . ' Category details Successfully Updated.!';
                            $this->session->set_flashdata('message', $msg, 'SUCCESS');
                        }
                        break;
                    case 'active':
                        $update_id = $this->user_model->update_by(array('category_id' => $category_id), array('visible' => '1'));
                        if ($update_id) {
                            if (sizeof($occasion_id) == 1)
                                $msg = 'Occasion details updated successfully';
                            else
                                $msg = sizeof($occasion_id) . ' Category details Successfully Updated.!';
                            $this->session->set_flashdata('message', $msg, 'SUCCESS');
                        }
                        break;
                }
            }
            else {
                $this->session->set_flashdata('message', 'Please select at least one Category.! ', 'ERROR');
            }
        }
        redirect('admin/category/lists');
    }

    function editdetails($id) {
        $this->load->library('form_validation');
        if ($id != '') {
            if (isset($_POST['sub'])) {
                $this->user_model->setTable("category");
                $update_id = $this->user_model->update_by(array('category_id' => $id), array('name' => $_POST['name'], 'gender' => $_POST['gender'], 'description' => $_POST['desc'], 'parent_id' => $_POST['parent'], 'updated_on' => date("Y-m-d H:i:s")));
                redirect("admin/category/lists");
//            echo "s";    
            } else {
                $data['occasion'] = $this->user_model->get_categoryListsByid($id);
//                print_r($data);
//                exit;
                $data['parent'] = $this->user_model->get_parent();
                $output['output'] = $this->load->view("admin/category/edit_details", $data, true);
                $this->_render_output($output);
            }
        } else {
            redirect("admin/category/lists");
        }
    }

    function create_cat() {
        if (isset($_POST)) {
            $data = array("name" => $_POST['ocname'], 'parent_id' => $_POST['parent'], 'gender' => $_POST['gender'], 'description' => $_POST['desc']);
            $cat_id = $this->user_model->insertCat($data);
            $this->user_model->save_filter_cat($cat_id,$_POST['parent']);
            redirect("admin/category/lists");
        } else {
            redirect("admin/category/lists");
        }
    }

    function gender_cat(){
        $gender=$_POST['gender'];
      $data=  $this->user_model->get_parent_gender($gender);
   
        echo'  <option value="0">None</option>';
                            foreach($data as $parent1){
                              echo '  <option value="'. $parent1['category_id'].' ">'. $parent1['name'].'</option>';
                   
      }
    }
            
    function create() {
        $data['parent'] = $this->user_model->get_parent();
        $output['output'] = $this->load->view("admin/category/create", $data, true);
        $this->_render_output($output);
    }

    function details($id) {
        $this->load->library('form_validation');
        if ($id != '') {

            $data['occasion'] = $this->user_model->get_categoryListsByid($id);
            $data['parent'] = $this->user_model->get_categoryListsByid($data['occasion']->parent_id);
//                print_r($data);
//                exit;
            $output['output'] = $this->load->view("admin/category/add_details", $data, true);
            $this->_render_output($output);
        } else {
            redirect("admin/category/lists");
        }
    }

}
