<?php

//error_reporting(0);
ini_set("display_errors", "on");
/*
 * **********************************************************************************
 * @name       Client
 * @version    1.0
 * @author     Jinson PL
 * @copyright  2014 Newagesmb (http://www.newagesmb.com),  All rights reserved.
 * Created on  06-Aug-2015
 * 
 * This script is a part of NewageSMB Framework. This Framework is not a free software.
 * Copying, Modifying or Distributing this software and its documentation (with or 
 * without modification, for any purpose, with or without fee or royality) is not
 * permitted.
 * 
 * ********************************************************************************* */

class Client extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('users_model');
    }

    public function index() {

        if (isset($HTTP_RAW_POST_DATA)) {
            $json = $HTTP_RAW_POST_DATA;
        } else {
            $json = implode("\r\n", file('php://input'));
        }

//        $json = '{"function":"add_to_occation","parameters": {"v": "1.0","apv": "I-1.0" ,"authKey": "9c833a407cbbf0e2ab7c9ca9fca744a3","sessionKey": "QcKKPNelnPtQ5h5ZqTum","user_id": "42","product_id": "54","occasion_id": "kitchen","custom": "Y"},"token":""}';
//        $json = '{"function":"social","parameters": {"v": "1.0","apv": "I-1.0","authKey": "9c833a407cbbf0e2ab7c9ca9fca744a3","sessionKey": "f0xz7UZ4fIm7MCY3Orz3","user_id": "45","product_id": "48","content": "Nonfunctional URL","report_description": "vgvchhhbv hhhh"},"token":""}';
//        $json='{"function":"add_to_occation","parameters": {"v": "1.0","apv": "I-1.0","authKey": "9c833a407cbbf0e2ab7c9ca9fca744a3","sessionKey": "nt4gKRT44brbqJoB3HqX","user_id": "45","product_id": "48","occasion_id": "6"},"token":""}';
//        $json='{"function":"product_details","parameters": {"v": "1.0","apv": "I-1.0","authKey": "9c833a407cbbf0e2ab7c9ca9fca744a3","sessionKey": "nJTSb1FumDaBLqiSsDd5","user_id": "45","lat": "10.008852","pdt_id": "42"},"token":""}';
//        $json='{"function":"trendy_save_link","parameters": {"v": "1.0","apv": "I-1.0","authKey": "9c833a407cbbf0e2ab7c9ca9fca744a3","sessionKey": "puhl2HZhczEqYrjDMwJC","link": "http://www.faballey.com/sweet-girl-blouse-54?gclid=CKOY96Ss58cCFQ0njgodV-wEwQ"},"token":""}';
//        $json = '{"function":"trendy_save_link","parameters": {"v": "1.0","apv": "I-1.0","authKey": "9c833a407cbbf0e2ab7c9ca9fca744a3","sessionKey": "HjsZkBw48CJuRlcFZE3X","link": "http://www.amazon.com/Levis-Mens-Slim-Straight-Jean/dp/B00D2KSI0E/ref=redir_mobile_desktop?ie=UTF8&ref_=s9_simh_gw_d0_g193_i1"},"token":""}';
//        $json = '{"function":"trendy_save_link","parameters": {"v": "1.0","apv": "I-1.0","authKey": "9c833a407cbbf0e2ab7c9ca9fca744a3","sessionKey": "HjsZkBw48CJuRlcFZE3X","link": "http://www.m.snapdeal.com/product/lakshya-printed-regular-dress-material/665855598533"},"token":""}';
//        $json = '{"function":"trendy_save_link","parameters": {"v": "1.0","apv": "I-1.0","authKey": "9c833a407cbbf0e2ab7c9ca9fca744a3","sessionKey": "ci5GFsB3A8eA2JGcyD3x","link": "http://m.jabong.com/Asics-Gel-Cumulus-17-White-Running-Shoes-1560757.html?pos=1"},"token":""}';
//       $json='{"function":"trendy_trends","parameters": {"v": "1.0","apv": "I-1.0","authKey": "9c833a407cbbf0e2ab7c9ca9fca744a3","sessionKey": "UwkO18nKzfhZQZuCd0Hb","user_id": "13","lat": "10.008852","long": "76.315568"},"token":""}';
//      $json ='{"function":"fb_registration","parameters": {"v": "1.0","apv": "I-1.0","authKey": "9c833a407cbbf0e2ab7c9ca9fca744a3","sessionKey": "eSGzzsQ0fkm8REPTlZrI","fb_unique_id": "1632176873733383","name": "Anamika Anamika","user_email": "umesh@newagesmb.com","user_gender": "male","fburl": "http://graph.facebook.com/711763718929108/picture??width=320&height=320"},"token":""}';
//        $json = '{"function":"get_occasion","parameters": {"v": "1.0","apv": "I-1.0","authKey": "9c833a407cbbf0e2ab7c9ca9fca744a3","sessionKey": "kUzeccSkUTIkrplrzzbo"},"token":""}';
//        $json=' {"function":"fb_registration","parameters": {"v": "1.0","apv": "I-1.0","authKey": "9c833a407cbbf0e2ab7c9ca9fca744a3","sessionKey": "M0Ro8zAz6GRuLDOp7UHk","fb_unique_id": "1632176873733383","name": "Anamika Anamika","user_email": "umesh@newagesmb.com","user_gender": "male","fburl": "http://graph.facebook.com/1632176873733383/picture??width=320&height=320","device_id": "4bac17994d549adb50f2f7933da2d660676ff3bd3d3782128bd4ce2749fb0318"},"token":""}';
        $array = json_decode($json, TRUE);

        if ($array['function'] != 'openSession') {

            $this->auth_url($array);
        }

        #check session iD
        $token = $array['token'];
        $function_name = $array['function'];
        if ($array['action'])
            $function_name = $array['action'];
        $this->$function_name($array['parameters']);
    }

    #Authentication function to check by v(version of API),apv - version of application for statistical purposes,authentication key,session key
    #Format example: A-1.0 (A for Android, I fro iOS, W for web))

    function auth_url($arr) {

        $this->load->model('users_model');
        if ($arr['parameters']['v'] == '') {
            $ar = array('result' => 'null', 'errorMessage' => 'missing API version', 'errorCode' => '1790');
            echo(json_encode($ar));
            exit;
        } else if ($arr['parameters']['apv'] == '') {
            $ar = array('result' => 'null', 'errorMessage' => 'missing API version', 'errorCode' => '1790');
            echo(json_encode($ar));
            exit;
        } else if ($arr['parameters']['authKey'] == '') {
            $ar = array('result' => 'null', 'errorMessage' => ' unauthorized to use API (wrong authKey)', 'errorCode' => '1810 ');
            echo(json_encode($ar));
            exit;
        } else if ($arr['parameters']['function'] != 'openSession' && $arr['parameters']['sessionKey'] == "") {
            $ar = array('result' => 'null', 'errorMessage' => 'Session has expired!', 'errorCode' => '1080');
            echo(json_encode($ar));
            exit;
        }
        /* else if($arr['function']!='openSession' && $arr['parameters']['sessionKey'] !=""){
          $status = $this->users_model->checkSessionID($arr['parameters']['sessionKey']);
          if($status =='N')
          {
          $ar=array('result'=>'null','errorMessage'=>'Permission denied!','errorCode'=>'1090');
          echo(json_encode($ar)); exit;
          }
          } */


        return;
    }

    #function to get session key

    function openSession($arr) {
        $this->load->model('users_model');
        #delete entry with the device token
        $this->users_model->deleteSessionEntry($arr['device_id']);
        $preferences = $this->users_model->getPreferences();
        if ($preferences['version'] == '1')
            $preferences['version'] = '1.0';
        else
            $preferences['version'] = '1';
        // $preferences['validate'] = '1';
        $preferences['session_id'] = $this->randomSessionId();
        #update session ID
        $this->users_model->updateSessionID($arr['device_id'], $preferences['session_id']);
        echo json_encode($preferences);
    }

    #function to generate random NUMBER

    function randomSessionId() {
        $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 20; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }

    # function login with facebook

    function test_view() {
        $this->load->view("test");
    }

    function treand_new_post() {
//        $this->load->model('users_model');
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

        $user_id = $_POST['user_id'];
        if ($_POST['flag'] == "Y") {
            if ($_FILES['file']['name'] <> "") {
                #################  Upload ############
                $tempFile = $_FILES['file']['tmp_name'];
                $fileParts = pathinfo($_FILES['file']['name']);

                $imagename = "postIMAGE_" . strtotime(date("Y-m-d H:i:s")) . uniqid() . "." . $fileParts['extension'];
                $foldername = "uploads/post/$imagename";
                $targetFolder = 'uploads/post';
                $targetFile = realpath($targetFolder) . '/' . $imagename;
                //echo $targetFile;
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
                $fileNAME = $image_thumb_name;
            }
        } else {
            $image_url = $_POST['img_url'];

            $extension = "jpg";
            $imagename = "postIMAGE_" . strtotime(date("Y-m-d H:i:s")) . uniqid() . '.' . $extension;
            $image = file_get_contents($image_url); // sets $image to the contents of the url
            file_put_contents('uploads/post/' . $imagename, $image);
            chmod(base_url() . 'uploads/post/' . $imagename, 757);
            $targetFolder = 'uploads/post';
            $targetFile = realpath($targetFolder) . '/' . $imagename;
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
            $fileNAME = $image_thumb_name;
        }
        $this->users_model->check_brand($brand);
        $insert_array = array("occasion_id" => $occasion_id, "product_type" => $product_type, "product_name" => $product_name, "price" => $price, "brand" => $brand, "gender" => $gender, "description" => $description, "fileNAME" => $fileNAME, "user_id" => $user_id, "lat" => $_POST['lat'], 'long' => $_POST['long'], 'product_url' => $site_url);
        $this->users_model->new_post($insert_array);
        $result = array('status' => 'true', 'user_id' => $user_id, 'message' => 'post is submitted  successfully');
        echo json_encode($result);
    }

    function trendy_trends($arr) {
        $user_id = $arr['user_id'];
        $lat = $arr['lat'];
        $long = $arr['long'];
        $user_gender = $this->users_model->get_settings($user_id);
        $data = $this->users_model->get_trend_post($user_gender, $lat, $long);
        $result = array('status' => 'true', 'recent_posts' => $data, 'vote_status' => $vote_status, 'filePath' => base_url() . "uploads/thumbs/post/");
        echo json_encode($result);
    }

    function trendy_save_link($arr) {
        require_once 'simplehtmldom_1_5/simple_html_dom.php';
        $request_html = $arr['link'];
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
                foreach ($html->find('img') as $element) {
                    if ($element->src != '') {
                        $image[] = $element->src;
                    } elseif ($element->ng - src != '') {
                        $image[] = $element->ng - src;
                    }
                }
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
        echo json_encode($result);
    }

    function social($arr) {
        $user_id = $arr['user_id'];
        $social = $this->users_model->get_social($user_id);
        $result = array('status' => 'true', 'social_list' => $social, 'filePath' => base_url() . "uploads/thumbs/post/");
        echo json_encode($result);
    }

    function trendy_report($arr) {
        $user_id = $arr['user_id'];
        $product_id = $arr['product_id'];
        $content = $arr['content'];
        $report_description = $arr['report_description'];
        $array = array("product_id" => $product_id, "user_id" => $user_id, "content" => $content, 'desc' => $report_description);
        $this->users_model->save_report($array);
        $result = array('status' => 'true', 'message' => 'Report submitted succesfully');
        echo json_encode($result);
    }

    function trendy_recent($arr) {
        $this->load->model('users_model');
        $user_id = $arr['user_id'];
        $lat = $arr['lat'];
        $long = $arr['long'];
        $user_gender = $this->users_model->get_settings($user_id);
        $data = $this->users_model->get_recent_post($user_gender, $lat, $long, $user_id);
        $result = array('status' => 'true', 'recent_posts' => $data, 'filePath' => base_url() . "uploads/thumbs/post/");
        echo json_encode($result);
    }

    function trend_track($arr) {
        $user_id = $arr['user_id'];
        $following_by = $arr['following'];
        $array = array("following" => $following_by, "followed_by" => $user_id);
        $this->users_model->save_track($array);
        $result = array('status' => 'true', 'message' => "success");
        echo json_encode($result);
    }

    function tacking_list($arr) {
        $data = $this->users_model->get_tracking_list($arr['user_id']);
        $result = array('status' => 'true', 'list' => $data);
        echo json_encode($result);
    }

    function get_reviews_full($arr) {
        $product_id = $arr['product_id'];
        $review = $this->users_model->get_full_reviews($product_id);
        $result = array('status' => 'true', 'review' => $review);
        echo json_encode($result);
    }

    function save_review($arr) {
        $this->load->model('users_model');
        $post_id = $arr['post_id'];
        $user_id = $arr['user_id'];
        $review = $arr['review'];
        $ins_array = array("review" => $review, "post_id" => $post_id, "user_id" => $user_id);
        $this->users_model->save_review($ins_array);
        $result = array('status' => 'true', 'message' => 'successfully posted your review');
        echo json_encode($result);
    }

    function get_occasion() {
        $this->load->model('test_user');
        $data = $this->users_model->get_occasion();
        $asd = 0;
        $array_cat = array();
        $category = $this->test_user->get_category_recursive($asd);
        for ($i = 0; $i < count($category); $i++) {
            $category[$i][submenu] = $this->test_user->get_category_recursive($category[$i]['category_id']);
            $cat[$i] = count($category[$i][submenu]);
        }
        for ($i = 0; $i < count($category); $i++) {
            for ($a = 0; $a < $cat[$i]; $a++) {
                $category[$i][submenu][$a][submenu] = $this->test_user->get_category_recursive($category[$i][submenu][$a]['category_id']);
            }
        }
        $brand = $this->users_model->get_brand_full();
        $result = array('status' => 'true', 'product_list' => $category, 'occasion_list' => $data, 'brand_list' => $brand);
        echo json_encode($result);
    }

    function trend_vote($arr) {
        $count = $this->users_model->trend_vote($arr);
        $result = array('status' => 'true', 'vote_count' => $count, 'message' => 'success');
        echo json_encode($result);
    }

    function get_occasion_list() {
        $occasion = $this->users_model->get_occasion();
        $result = array('status' => 'true', 'occasion_list' => $occasion, 'filePath' => base_url() . "uploads/occasion/", 'message' => 'success');
        echo json_encode($result);
    }

    function get_product_occasion($arr) {
        $occasion_id = $arr['occasion_id'];
        $data = $this->users_model->get_product_occasion($occasion_id);
        $result = array('status' => 'true', 'product_list' => $data, 'filePath' => base_url() . "uploads/thumbs/post/", 'message' => 'success');
        echo json_encode($result);
    }

    function login_with_fb($arr) {
        $this->load->model('users_model');
        $user_id = $this->users_model->validateFacebookId($arr['fb_unique_id']);
        #check whether the user already registerd
        if ($user_id) { # if the user already connected to facebook
            $is_active = $this->users_model->check_active_user($arr['fb_unique_id']);
            if ($is_active == 'Y') {
                $userdet = $this->users_model->validateFacebookId($arr['fb_unique_id']);
                $userDetails = $this->users_model->getUserFullDetails($userdet['user_id']);
                $device_tkn = $this->users_model->updateDevice_Token($arr['devicetoken'], $userdet['user_id']);
                if ($userDetails['img_extension']) {
                    $thumb_image_url = base_url() . "members/" . $userDetails['user_id'] . "_thumb." . $userDetails['img_extension'] . '?' . date("his");
                    $image_url = base_url() . "members/" . $userDetails['user_id'] . "." . $userDetails['img_extension'] . '?' . date("his");
                    $userDetails['image'] = $thumb_image_url;
                    $userDetails['original_image'] = $image_url;
                } else {
                    $thumb_image_url = base_url() . "upload/no_img.png";
                    $userDetails['image'] = $thumb_image_url;
                    $userDetails['original_image'] = $thumb_image_url;
                }
                $result = array('status' => 'true', 'active' => 'Y', 'user_id' => $userdet['user_id'], 'fb_unique_id' => $userdet['facebook_id'], 'profile_image' => $userDetails['original_image'], 'password' => $userdet['password'], 'message' => 'Your account is already Connected ');
                echo json_encode($result);
            } else {
                $result = array('status' => 'false', 'fb_unique_id' => $arr['fb_unique_id'], 'active' => 'N', 'message' => 'This user is blocked by admin ');
                echo json_encode($result);
            }
        } else {
            $result = array('status' => 'false', 'active' => 'Y', 'message' => 'You are not registered');
            echo json_encode($result);
        }
    }

    function get_userList($arr) {
        $user_id = $arr['user_id'];
        $data = $this->users_model->get_user_list($user_id);
        $result = array('status' => 'false', 'data' => $data);
        echo json_encode($result);
    }

    function get_occasion_byid($arr) {
        $id = $arr['id'];
        $data = $this->users_model->get_occ_id($id);
        $result = array('status' => 'true', 'occasion' => $data);
        echo json_encode($result);
    }

    function save_product_user($arr) {
        $user_id = $arr['user_id'];
        $product_link = $arr['link'];
        $array = array("user_id" => $user_id, "product_link" => $product_link, "product_id" => $arr['product_id']);
        $this->users_model->save_pdt_user($array);
        $result = array('status' => 'true', 'message' => 'successfully saved');
        echo json_encode($result);
    }

    function user_settings($arr) {
        $user_id = $arr['user_id'];
        $profile = $arr['profile'];
        $gender = $arr['gender'];
        $notifications = $arr['notifications'];
        $array = array("user_id" => $user_id, "profile" => $profile, "gender" => $gender, "notifications" => $notifications, "updated_on" => date("Y-m-d H:i:s"));
        if ($this->users_model->check_setiings_user($user_id) == FALSE) {
            $this->users_model->save_settings($array);
        } else {
            $cond = array("user_id" => $user_id);
            $this->users_model->up_settings($array, $cond);
        }
        $result = array('status' => 'true', 'message' => 'successfully saved');
        echo json_encode($result);
    }

    function product_details($arr) {
        $pdt_id = $arr['pdt_id'];
        $user_id = $arr['user_id'];
        $user_gender = $this->users_model->get_settings($user_id);
        $details = $this->users_model->pdt_details($pdt_id, $user_id);
        if ($details['occasion_id'] != '') {
            $suggested_items = $this->users_model->suggested_items($details['id'], $details['occasion_id'], $user_gender);
        } else {
            $suggested_items = array();
        }
        $result = array('status' => 'true', 'product_details' => $details, 'suugested_items' => $suggested_items, 'filePath' => base_url() . "uploads/thumbs/post/");
        echo json_encode($result);
    }

    function get_settings($arr) {
        $data = $this->users_model->get_settings1($arr['user_id']);
        $result = array('status' => 'true', 'settings' => $data);
        echo json_encode($result);
    }

    function add_to_occation($arr) {
        $user_id = $arr['user_id'];
        $post_id = $arr['product_id'];
        $custom = $arr['custom'];
        if ($custom == 'Y') {
            $occasion_id = $this->users_model->create_ocassion_byApp($arr['occasion_id']);
//            echo $occasion_id;
//            exit;
        } else {
            $occasion_id = $arr['occasion_id'];
        }
        $update_array = array("occasion_id" => $occasion_id);
        $cond = array("user_id" => $user_id, "id" => $post_id);
        $this->users_model->update_pdt_occasion($update_array, $cond);
        $result = array('status' => 'true', 'message' => 'successfully saved');
        echo json_encode($result);
    }

    public function delete_review($arr) {
        $user_id = $arr['user_id'];
        $review_id = $arr['review_id'];
        $array = array("user_id" => $user_id, "review_id" => $review_id);
        $this->users_model->del_rev($array);
        $result = array('status' => 'true', 'message' => 'successfully deleted');
        echo json_encode($result);
    }

    function profile($arr) {
        $user_id = $arr['user_id'];
        $data = $this->users_model->get_profile($user_id);
        $reviewd_items = $this->users_model->get_user_review($user_id);
        $tracker = $this->users_model->get_trackingCount($user_id);
        $saved_items = $this->users_model->get_user_saved_items($user_id);
        $posted_items = $this->users_model->get_posted_items();
        $result = array('status' => 'true', 'user_detais' => $data, 'tracking_details' => $tracker, 'reviewed_items' => $reviewd_items, 'saved_items' => $saved_items, 'posted_items' => $posted_items, 'post_pic_path' => base_url() . "uploads/thumbs/post/", 'profile_pic_path' => base_url() . "uploads/members/", 'message' => 'user details');
        echo json_encode($result);
    }

    function contact($arr) {
        $name = $arr['name'];
        $email_id_from = $arr['from_email'];
        $message = $arr['message'];
        $array = array("name" => $name, "from" => $email_id_from, "message" => $message);
        $this->users_model->save_contact($array);
        $result = array('status' => 'true', 'message' => 'successfully submitted');
        echo json_encode($result);
    }

    #function to do registration via facebook

    function fb_registration($arr) {
        $this->load->model('users_model');
        if ($this->users_model->checkFacebookExist(mysql_escape_string($arr['fb_unique_id']), '')) {
            $result = array('status' => 'false', 'message' => 'Username already exists');
            echo json_encode($result);
            exit;
        } else {
            if (!$this->users_model->checkUserExist(mysql_escape_string($arr['username']), '')) {
                if (!$this->users_model->checkEmailExist(mysql_escape_string($arr['user_email']), '')) {
                    // $arr['wp_user_id']=   $this->CreateUserinWp($arr);
                    $user_id = $this->users_model->Registration($arr);

                    if ($arr['fburl']) {
                        $image_url = $arr['fburl'];
                        $extension = "jpg";

                        $completefileName = $user_id . '.' . $extension;
                        $image = file_get_contents($image_url); // sets $image to the contents of the url
                        file_put_contents('uploads/members/' . $completefileName, $image);
                        $this->users_model->updateMemberImage($extension, $user_id);
                    }
                    $thumb_image_url = base_url() . "uploads/members/" . $user_id . "_thumb." . $userDetails['img_extension'] . '?' . date("his");

                    //--------------------------
                    $userDetails = $this->users_model->getUserFullDetails($user_id);
                    $this->load->model('preferences_model', 'pm');
                    $admin_settings = $this->pm->getAdminSettings();
                    $config['mailtype'] = 'html';
                    $config['charset'] = 'utf-8';
                    $config['crlf'] = PHP_EOL;
                    $config['newline'] = PHP_EOL;
                    $this->load->library('email');
                    $this->email->initialize($config);
                    $userDetails = $this->users_model->getUserFullDetails($user_id);
                    $email_user = $this->users_model->get_emailByid(71);
                    $message1 = $email_user['email_template'];
                    $baseurl = base_url();
                    $message1 = str_replace('#BASE_URL#', $baseurl, $message1);
                    $message1 = str_replace('#FIRST_NAME#', $userDetails["name"], $message1);
                    $message1 = str_replace('#EMAIL#', $userDetails["user_email"], $message1);
                    $message1 = str_replace('#USER_NAME#', $userDetails["username"], $message1);
                    $message1 = str_replace('#PASSWORD#', '', $message1);
                    $this->email->reply_to('support@trendyservices.com', 'Trendy Services');
                    $this->email->from($this->config->item('email_from'));
                    $this->email->to($userDetails["user_email"]);
                    $this->email->subject("Welcome to Trendy Services");
                    $this->email->message($message1);
                    $this->email->send();

                    $email_user = $this->users_model->get_emailByid(72);
                    /* $new_password = $this->users_model->randomPassword(); # generate random password
                      $this->users_model->updateUserPassword($new_password, $user_id); */
                    $message1 = $email_user['email_template'];
                    $this->email->reply_to('support@trendyservices.com', 'Trendy Services');
                    $this->email->from($this->config->item('email_from'));
                    $this->email->to($admin_settings["user_email"]);
                    $this->email->subject("New User Registered!");
                    $this->email->message($message);
                    $this->email->send();
                    // $this->users_model->register_common_profile_data($arr, $user_id);
                    $result = array('status' => 'true', 'message' => 'Thank you for registering with Trendy Services. Your login details have been sent to your email address', 'user_id' => $user_id, 'email' => $arr['user_email'], 'name' => $arr['name'], 'password' => md5($arr['password']), 'gender' => $arr['user_gender'], 'fb_unique_id' => $arr['fb_unique_id'], 'image' => $thumb_image_url);
                    echo json_encode($result);
                } else {
                    $result = array('status' => 'false', 'message' => 'Email Address already exist ');
                    echo json_encode($result);
                }
            } else {
                $result = array('status' => 'false', 'message' => 'Username already exists, Please choose an alternate one ');
                echo json_encode($result);
            }
        }
    }

    # function to change password 

    function changePassword($arr) {
        $this->load->model('users_model');
        $userDetails = $this->users_model->getUserFullDetails($arr['user_id']);
        if ($userDetails['password'] != md5($arr['old_password'])) {
            $result = array('status' => 'false', 'message' => 'Incorrect old password');
            echo json_encode($result);
        } else {
            $this->users_model->changePassword(md5($arr['new_password']), $arr['user_id']); # change user password
//            $userDetails = $this->users_model->getUserFullDetails(arr['user_id']);
            $config['mailtype'] = 'html';
            $config['charset'] = 'utf-8';
            $config['crlf'] = PHP_EOL;
            $config['newline'] = PHP_EOL;
            $this->load->library('email');
            $this->email->initialize($config);
            $email = $this->users_model->get_emailByid(87);
            $message = $email['email_template'];
            $message = str_replace("#BASE_URL#", base_url(), $message);
            $message = str_replace('#FIRST_NAME#', ucfirst($userDetails["name"]), $message);
            $this->email->reply_to('support@trendyservices.com', 'Trendy Services');
            $this->email->from($this->config->item('email_from'));
            $this->email->to($userDetails['user_email']);
            $this->email->subject("Your password has been reset");
            $this->email->message($message);
            $this->email->send();
            $result = array('status' => 'true', 'password' => md5($arr['new_password']), 'message' => 'Password changed successfully');
            echo json_encode($result);
        }
    }

    #login function

    function Login($arr) {
        $this->load->model('users_model');
        $msg = $this->users_model->userLogin($arr);
        if ($msg == 'Login success') {
            $userdet = $this->users_model->getUserDetailsByUsernameandPassword($arr);
            $user_id = $userdet['user_id'];
            $email = $userdet['user_email'];
            $uname = $arr['username'];
            $device_tkn = $this->users_model->updateDevice_Token($arr['devicetoken'], $user_id);
            if ($userdet['img_extension']) {
                $thumb_image_url = base_url() . "members/" . $userdet['user_id'] . "_thumb." . $userdet['img_extension'] . '?' . date("his");
                $image_url = base_url() . "members/" . $userdet['user_id'] . "." . $userdet['img_extension'] . '?' . date("his");
                $userDetails['image'] = $thumb_image_url;
                $userDetails['original_image'] = $image_url;
            } else {
                $thumb_image_url = base_url() . "upload/no_img.png";
                $userDetails['image'] = $thumb_image_url;
                $userDetails['original_image'] = $thumb_image_url;
            }
            $result = array('status' => 'true', 'message' => 'Login Successful', 'user_id' => $userdet['user_id'], 'email' => $userdet['user_email'], 'password' => $userdet['password'], 'profile_image' => $userdet['original_image'], 'name' => $userdet['name'], "profile_full_image" => $userdet['original_image'], "active" => $userdet['active']);
            //print_r($result);exit;
            echo json_encode($result);
        } else {
            $result = array('status' => 'false', 'message' => "Incorrect Username or Password");
            echo json_encode($result);
        }
    }

}

?>
