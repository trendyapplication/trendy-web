<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of post
 *
 * @author aneesh
 */
class post extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->_setAsAdmin();
//        $this->user_model->setTable("occasion_master");
        $this->load->model('admin/admin_model');
        $this->load->model('email_model');
        $this->load->model('users_model');
        $this->load->model('user_model');
        $this->user = $this->session->userdata('user');
        if ($this->user == '')
            redirect('admin');
    }

    function index() {
        redirect("admin/post/lists");
    }

    function delete($id) {
        $array = array("id" => $id);
        $this->user_model->del_post($array, $id);

        redirect("admin/post");
    }

    function editdetails($id) {
        $this->load->model('user_model');
        $this->load->library('form_validation');
        if (isset($_POST['category'])) {

            $array = array("occasion_id" => $_POST['occasion'], "product_type" => $_POST['category'], "product_name" => $_POST['product_name'], "price" => $_POST['price'], "brand" => $_POST['brand'], "gender" => $_POST['gender'], "description" => $_POST['desc']);
            $cond = array("id" => $id);
            $this->user_model->updatePost($array, $cond);
            redirect("admin/post");
        } else {
            $data['brand'] = $this->user_model->getBrand_List();
            $data['datas'] = $this->user_model->getPostListsByid($id);
            $data['occasion'] = $this->user_model->get_occasion_post();
            $data['category'] = $this->user_model->get_cat_list();
            $output['output'] = $this->load->view('admin/post/edit_details', $data, true);
            $this->_render_output($output);
        }
    }

    function uploadImage() {
        if ($_FILES['ocimage']['name'] != '') {
            $ext = explode('.', $_FILES['ocimage']['name']);

            $new_file_name = md5(uniqid()) . md5(round(microtime(true) * 1000) . uniqid()) . '.' . $ext[1];
            echo $new_file_name;
            move_uploaded_file($_FILES['ocimage']['tmp_name'], 'uploads/post/' . $new_file_name);
            $this->gallery_path = realpath(APPPATH . '../uploads'); //fetching path
            $config1 = array(
                'source_image' => $this->gallery_path . '/post/' . $new_file_name, //get original image
                'new_image' => $this->gallery_path . '/thumbs/post/' . $new_file_name, //save as new image //need to create thumbs first
                'maintain_ratio' => true,
                'width' => 165,
                'height' => 120
            );
              $this->load->library('image_lib', $config1); //load library
            $this->image_lib->resize();
            $targetFile = realpath('uploads/thumbs/post') . "/" . $new_file_name;
            shell_exec("/usr/local/bin/s3cmd  --config=/var/www/html/trend/.s3cfg     --acl-public put " . $targetFile . "    s3://trendyservice/post/");
          
            $this->user_model->setTable("trend_post");
            $update_id = $this->user_model->update_by(array('id' => $_POST['id']), array('fileNAME' => $new_file_name, 'updated_on' => date("Y-m-d H:i:s")));
        }
    }

    function details($id) {
        $this->load->model('user_model');
        $this->load->library('form_validation');
        if ($id != '') {
            $data['datas'] = $this->user_model->getPostListsByid($id);
            $output['output'] = $this->load->view('admin/post/add_details', $data, true);
        } else {
            $output['output'] = $this->load->view('admin/post/add_details', '', true);
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
        if (isset($_POST['gender'])) {
            $gender = $_POST['gender'];
        } else {
            $gender = '';
        }
        $data['gender'] = $gender;
        $config['total_rows'] = $this->user_model->getPostCount($data['status'], $data['key'], $gender);
        $data['userlist'] = $this->user_model->getPostLists($data['status'], $config['per_page'], $_REQUEST['per_page'], $data['key'], $gender);
        $params = '?t=1';
        if ($data['limit'] != '')
            $params .= '&limit=' . $data['limit'];
        if ($data['key'] != '')
            $params .= '&key=' . $data['key'];
        if ($data['status'] != '')
            $params .= '&status=' . $data['status'];
        $this->load->library('pagination');
        $config['base_url'] = site_url("admin/post/lists") . "/" . $params;
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
        $output['output'] = $this->load->view('admin/post/lists', $data, true);
        $this->_render_output($output);
    }

    #############################

    function treand_new_post() {


        if ($_POST['custom'] == "Y") {
            $occasion_id = $this->users_model->create_ocassion_byApp($_POST['occasion_id']);
        } else {
            $occasion_id = $_POST['occasion_id'];
        }
        if ($_POST['brand_custom'] == "Y") {
            $brand = $this->users_model->create_brand_byApp($_POST['brand']);
        } else {
            $brand = $_POST['brand'];
        }
        $product_type = $_POST['product_type'];
        $price = $_POST['price'];
        $product_name = $_POST['product_name'];
        $gender = $_POST['gender'];
        $description = $_POST['description'];
        $site_url = $_POST['product_url'];
        $fileNAME = '';
        $image_array = array();
        $user_id = $_POST['user_id'];

        $count = $_POST['count'];
        for ($c = 1; $c <= $count; $c++) {
            if ($_FILES['file' . $c]['name'] <> "") {
                #################  Upload ############
//                    $ctest_name = $_FILES['file' . $c]['name'];
                $tempFile = $_FILES['file' . $c]['tmp_name'];
//                    list($width, $height, $type, $attr) = getimagesize($tempFile);
                $fileParts = pathinfo($_FILES['file' . $c]['name']);
                $imagename = "postIMAGE_" . strtotime(date("Y-m-d H:i:s")) . uniqid() . "." . $fileParts['extension'];
                $foldername = "uploads/post/$imagename";
                $targetFolder = 'uploads/post';
                $targetFile = realpath($targetFolder) . '/' . $imagename;
                move_uploaded_file($tempFile, $targetFile);
                chmod(base_url() . 'uploads/post/' . $imagename, 757);
                $image_thumb_name = "thumb_post_640_" . $imagename;
                $imagethumb = "uploads/thumbs/post/" . "thumb_post_640_" . $imagename;
                $configThumb = array();
                $configThumb['image_library'] = 'gd2';
                $configThumb['source_image'] = $targetFile;
                $configThumb['new_image'] = $imagethumb;
                $configThumb['create_thumb'] = false;
                $configThumb['maintain_ratio'] = TRUE;
                $configThumb['width'] = 640;
                $configThumb['height'] = 316;
                $this->load->library('image_lib');
                $this->image_lib->initialize($configThumb);
                $this->image_lib->resize();
                chmod(base_url() . $imagethumb, 757);
                if ($c == 1) {
                    $fileNAME = $image_thumb_name;
                }
                $image_array[] = $image_thumb_name;
                $targetFile = realpath('uploads/thumbs/post') . "/" . $image_thumb_name;
                shell_exec("/usr/local/bin/s3cmd  --config=/var/www/html/trend/.s3cfg     --acl-public put " . $targetFile . "    s3://trendyservice/post/");
            }
        }

        $this->users_model->check_brand($brand);
        $latti = $_POST['lat'];
        $longitude = $_POST['long'];
        $bot = $this->users_model->check_bot($user_id);
        if ($bot['bot'] == "NO") {
            $city = $this->users_model->find_Loc($latti, $longitude);
        } else {
            $city = $this->users_model->find_city($bot['location']);
        }
        $insert_array = array("occasion_id" => $occasion_id, "product_type" => $product_type, "product_name" => $product_name, "price" => $price, "brand" => $brand, "gender" => $gender, "description" => $description, "fileNAME" => $fileNAME, "user_id" => $user_id, "lat" => $_POST['lat'], 'long' => $_POST['long'], 'product_url' => $site_url, 'posted_locatn' => $city);
        $insert_id = $this->users_model->new_post($insert_array, $image_array);
        $result = array('status' => 'true', 'image_array' => $image_array, 'user_id' => $user_id, 'message' => 'post is submitted  successfully');
        echo json_encode($result);
    }

    function create_post1() {
        $output['output'] = $this->load->view('admin/post/create_post', '', true);
        $this->_render_output($output);
    }

    function trendy_save_link1() {
        require_once 'simplehtmldom_1_5/simple_html_dom.php';
        print_r($_POST);
        $request_html = $_POST['post_name'];
        $html = file_get_html($request_html);
        $strlen_html = strlen($html);
        if ($strlen_html > 0) {
            if (strpos($request_html, 'jabsdsdasdsadasdsddddong.com') !== false) {
                foreach ($html->find('[itemprop]') as $el) {
                    if ($el->itemprop == "price") {
                        $price = $el->content;
                    } else if ($el->itemprop == "image") {
                        $img = $el->src;
                    } else if ($el->itemprop == "description") {
                        $desc = $el->plaintext;
                    } else if ($el->itemprop == "name") {
                        $brandName = $el->plaintext;
                    }
                    $image_array = array();
                    if (strlen($img) > 5 && !(stripos($img, 'null') !== false)) {
                        $image_array[] = $img;
                    }
                }
            } else {
                $brandName = '';
                $price = '';
                $img = '';
                $desc = '';
                $brand = '';
                $img_array = array();
                $loop_c = 1;
//                echo "<pre>";
//                print_r($html);exit;
                foreach ($html->find('img') as $element) {
                    echo $loop_c++ . "<br/>";
                    if ($loop_c > 2) {
                        break;
                    }
                    echo "";
                    print_r($element);
                    if ($element->src != '') {
                        $image[] = $element->src;
                    } elseif ($element->ng - src != '') {
                        $image[] = $element->ng - src;
                    }
                }exit;
                foreach ($html->find('[itemprop]') as $el) {
                    if ($el->itemprop == "image") {
                        $imgage[] = $el->src;
                    } elseif ($el->itemprop == "description") {
                        $desc = strip_tags(html_entity_decode($el->plaintext));
                    } elseif ($el->itemprop == "name") {
                        $brandName = strip_tags(html_entity_decode($el->plaintext));
                    } elseif ($el->itemprop == "price") {
                        $price = strip_tags(html_entity_decode($el->content));
                    } elseif ($el->itemprop == "brand") {
                        $brand = strip_tags(html_entity_decode($el->content));
                    }
                }
                foreach ($image as $key => $ima) {
                    if (strlen($ima) > 5 && !(stripos($ima, '.gif') !== false) && !(stripos($img, 'null') !== false) && !(stripos($ima, 'ico') !== false) && !(stripos($ima, 'logo') !== false) && !(stripos($ima, 'arrow') !== false)) {

                        list($width, $height) = getimagesize($ima);
                        if ($width >= 150 && $height >= 200) {
                            $img_array[] = trim($ima);
                        }
                    }
                }
                $image_array = $img_array;
            }
            $result = array('status' => 'true', 'string_length' => $strlen_html, 'product_name' => $brandName, 'image_array' => $image_array, 'brand_name' => $brand, 'image' => $img, 'price' => $price, 'description' => $desc);
        } else {
            $image_array = array();
            $brandName = '';
            $price = '';
            $img = '';
            $desc = '';
            $result = array('status' => 'false', 'string_length' => $strlen_html, 'product_name' => $brandName, 'image_array' => $image_array, 'brand_name' => $brandName, 'image' => $img, 'price' => $price, 'description' => $desc);
        }
        print_r($result);
        $output['output'] = $this->load->view('admin/post/post_details', $result, true);
        $this->_render_output($output);
    }

    function change_gender() {
        $gender = $_POST['gender'];
        $category = $this->user_model->change_cat_gender($gender);
        foreach ($category as $cnt_data) {
            echo '<option value="' . $cnt_data['category_id'] . ' ">' . $cnt_data['name'] . ' </option> ';
        }
    }

    function create_post() {

        $this->load->model('user_model');
        $trend_data['user'] = $this->user_model->get_all_data_table('user_master', 'bot', 'YES');
        $trend_data['category'] = $this->user_model->get_all_data_table('category', 'gender', 'male');
        $trend_data['occasion'] = $this->user_model->get_all_data_table('occasion_master', 'status', 'Y');
        $trend_data['brand'] = $this->user_model->get_all_data_table('brand_master');
        $trend_data['city'] = $this->user_model->get_all_data_table('trendy_city');


        $output['output'] = $this->load->view('admin/post/post_details', $trend_data, true);
        $this->_render_output($output);
    }

    function trendy_save_post() {

//Array ( [user_id] => 169 [occasion] => 52 [product_type] => 48 [product_url] => [ocname] => [brand] => 1 [gender] => male [country_name] => 1 [description] => [submit] => ) Array ( [status] => false [string_length] => 0 [product_name] => [image_array] => Array ( ) [brand_name] => [image] => [price] => [description] => ) 
        $this->load->model('user_model');
        $user_id = $_POST['user_id'];
        $product_type = $_POST['product_type'];
        $product_url = $_POST['product_url'];
        $ocname = $_POST['occasion'];
        $brand = $_POST['brand'];
        $gender = $_POST['gender'];
        $price = $_POST['price'];
        $country_name = $_POST['country_name'];
        $description = $_POST['description'];
        $bot = $this->users_model->check_bot($user_id);
        $city_id = $bot['location'];
        $latlong = $this->user_model->get_lat_long($country_name);

        if ($_FILES['file']['name'] <> "") {

#################  Upload ############
//                    $ctest_name = $_FILES['file' . $c]['name'];
            $tempFile = $_FILES['file']['tmp_name'];
//                    list($width, $height, $type, $attr) = getimagesize($tempFile);
            $fileParts = pathinfo($_FILES['file']['name']);
            $imagename = "postIMAGE_" . strtotime(date("Y-m-d H:i:s")) . uniqid() . "." . $fileParts['extension'];

            $foldername = "uploads/post/$imagename";
            $targetFolder = 'uploads/post';
            $targetFile = realpath($targetFolder) . '/' . $imagename;
            move_uploaded_file($tempFile, $targetFile);
            chmod(base_url() . 'uploads/post/' . $imagename, 757);
            $image_thumb_name = "thumb_post_640_" . $imagename;
            $imagethumb = "uploads/thumbs/post/" . "thumb_post_640_" . $imagename;
            $configThumb = array();
            $configThumb['image_library'] = 'gd2';
            $configThumb['source_image'] = $targetFile;
            $configThumb['new_image'] = $imagethumb;
            $configThumb['create_thumb'] = false;
            $configThumb['maintain_ratio'] = TRUE;
            $configThumb['width'] = 640;
            $configThumb['height'] = 316;
            $this->load->library('image_lib');
            $this->image_lib->initialize($configThumb);
            $this->image_lib->resize();
            chmod(base_url() . $imagethumb, 757);
            $targetFile11 = realpath('uploads/thumbs/post') . "/" . $image_thumb_name;
            shell_exec("/usr/local/bin/s3cmd  --config=/var/www/html/trend/.s3cfg     --acl-public put " . $targetFile11 . "    s3://trendyservice/post/");
        }


        $post_data = array('occasion_id' => $ocname, 'product_type' => $product_type, 'product_name' => '', 'price' => $price, 'brand' => $brand, 'gender' => $gender, 'description' => $description, 'fileNAME' => $image_thumb_name, 'created_on' => date('Y-m-d H:i'), 'lat' => $latlong['lat'], 'long' => $latlong['long'], 'user_id' => $user_id, 'product_url' => $product_url, 'updated_on' => '', 'posted_locatn' => $country_name, 'posted_locatn_id' => $city_id);
        $post_id = $this->user_model->inser_post_values($post_data);
        $post_data_image = array('image_name' => $image_thumb_name, 'post_id' => $post_id, 'inserted_on' => date('Y-m-d H:i'));
        $this->user_model->inser_post_image($post_data_image);
        redirect("admin/post/lists");
    }

    function upload() {

        $output_dir = "uploads/";

        if (isset($_FILES["myfile"])) {
            $ret = array();

            $error = $_FILES["myfile"]["error"];
            {

                if (!is_array($_FILES["myfile"]['name'])) { //single file
                    $RandomNum = time();

                    $ImageName = str_replace(' ', '-', strtolower($_FILES['myfile']['name']));
                    $ImageType = $_FILES['myfile']['type']; //"image/png", image/jpeg etc.

                    $ImageExt = substr($ImageName, strrpos($ImageName, '.'));
                    $ImageExt = str_replace('.', '', $ImageExt);
                    $ImageName = preg_replace("/\.[^.\s]{3,4}$/", "", $ImageName);
                    $NewImageName = $ImageName . '-' . $RandomNum . '.' . $ImageExt;

                    move_uploaded_file($_FILES["myfile"]["tmp_name"], $output_dir . $NewImageName);
//echo "<br> Error: ".$_FILES["myfile"]["error"];

                    $ret[$fileName] = $output_dir . $NewImageName;
                } else {
                    $fileCount = count($_FILES["myfile"]['name']);
                    for ($i = 0; $i < $fileCount; $i++) {
                        $RandomNum = time();

                        $ImageName = str_replace(' ', '-', strtolower($_FILES['myfile']['name'][$i]));
                        $ImageType = $_FILES['myfile']['type'][$i]; //"image/png", image/jpeg etc.

                        $ImageExt = substr($ImageName, strrpos($ImageName, '.'));
                        $ImageExt = str_replace('.', '', $ImageExt);
                        $ImageName = preg_replace("/\.[^.\s]{3,4}$/", "", $ImageName);
                        $NewImageName = $ImageName . '-' . $RandomNum . '.' . $ImageExt;

                        $ret[$NewImageName] = $output_dir . $NewImageName;
                        move_uploaded_file($_FILES["myfile"]["tmp_name"][$i], $output_dir . $NewImageName);
                    }
                }
            }
            echo json_encode($ret);
        }
    }

}
