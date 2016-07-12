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

//        $json='{"function":"get_product_occasion","parameters": {"v": "1.0","apv": "I-1.0","authKey": "9c833a407cbbf0e2ab7c9ca9fca744a3","sessionKey": "Q0C0en5nf4rO0Y9YTAfW","user_id": "137","lat": "10.074581","long": "76.278854","occasion_id": "60"},"token":""}';
//$json='{"function":"get_filter_trend","parameters": {"v": "1.0","apv": "I-1.0","authKey": "9c833a407cbbf0e2ab7c9ca9fca744a3","sessionKey": "PzjWARJuA7bQayZK9dhW","user_id": "185","lat": "10.008666","long": "76.318793"},"token":""}';
//$json='{"function":"trendy_recent","parameters": {"v": "1","apv": "I-1.0","authKey": "(null)","sessionKey": "TfSg2qUalAjkhhRdhotk","user_id": "175","lat": "10.015099","long": "76.277542"},"token":""}';
//        $json='{"function":"trendy_recent","parameters": {"v": "1.0","apv": "I-1.0","authKey": "9c833a407cbbf0e2ab7c9ca9fca744a3","sessionKey": "gr2M2kj0bW3kNaFdpTrB","user_id": "45","lat": "10.074581","long": "76.278854"},"token":""}';
//   $json = '{"function":"get_profile_details","parameters": {"v": "1.0","apv": "I-1.0","authKey": "9c833a407cbbf0e2ab7c9ca9fca744a3","sessionKey": "CWsrtfMM5oJ8Yy9TTKMp","logged_user_id": "64","user_id": "64"},"token":""}';
//        $json = '{"function":"add_to_occation","parameters": {"v": "1.0","apv": "I-1.0" ,"authKey": "9c833a407cbbf0e2ab7c9ca9fca744a3","sessionKey": "QcKKPNelnPtQ5h5ZqTum","user_id": "42","product_id": "54","occasion_id": "kitchen","custom": "Y"},"token":""}';
//        $json = '{"function":"social","parameters": {"v": "1.0","apv": "I-1.0","authKey": "9c833a407cbbf0e2ab7c9ca9fca744a3","sessionKey": "nTsJMBDWNNQDg0oJEukD","user_id": "45","lat": "10.008852","long": "76.315568"},"token":""}';
//        $json='{"function":"add_to_occation","parameters": {"v": "1.0","apv": "I-1.0","authKey": "9c833a407cbbf0e2ab7c9ca9fca744a3","sessionKey": "nt4gKRT44brbqJoB3HqX","user_id": "45","product_id": "48","occasion_id": "6"},"token":""}';
//        $json='{"function":"product_details","parameters": {"v": "1.0","apv": "I-1.0","authKey": "9c833a407cbbf0e2ab7c9ca9fca744a3","sessionKey": "nJTSb1FumDaBLqiSsDd5","user_id": "45","lat": "10.008852","pdt_id": "42"},"token":""}';
//        $json='{"function":"trendy_save_link","parameters": {"v": "1.0","apv": "I-1.0","authKey": "9c833a407cbbf0e2ab7c9ca9fca744a3","sessionKey": "puhl2HZhczEqYrjDMwJC","link": "https://paytm.com/shop/p/reebok-white-sports-shoes-size-8-FOOREEBOK-WHITEDREA259919E77719A?gclid=CMTJubGMjcgCFRcXjgod7E0BAw"},"token":""}';
//        $json = '{"function":"trendy_save_link","parameters": {"v": "1.0","apv": "I-1.0","authKey": "9c833a407cbbf0e2ab7c9ca9fca744a3","sessionKey": "HjsZkBw48CJuRlcFZE3X","link": "http://www.amazon.com/Levis-Mens-Slim-Straight-Jean/dp/B00D2KSI0E/ref=redir_mobile_desktop?ie=UTF8&ref_=s9_simh_gw_d0_g193_i1"},"token":""}';
//        $json = '{"function":"trendy_save_link","parameters": {"v": "1.0","apv": "I-1.0","authKey": "9c833a407cbbf0e2ab7c9ca9fca744a3","sessionKey": "HjsZkBw48CJuRlcFZE3X","link": "http://www.m.snapdeal.com/product/lakshya-printed-regular-dress-material/665855598533"},"token":""}';
//        $json = '{"function":"trendy_save_link","parameters": {"v": "1.0","apv": "I-1.0","authKey": "9c833a407cbbf0e2ab7c9ca9fca744a3","sessionKey": "ci5GFsB3A8eA2JGcyD3x","link": "http://m.jabong.com/Asics-Gel-Cumulus-17-White-Running-Shoes-1560757.html?pos=1"},"token":""}';
//       $json='{"function":"trendy_recent","parameters": {"v": "1.0","apv": "I-1.0","authKey": "9c833a407cbbf0e2ab7c9ca9fca744a3","sessionKey": "UwkO18nKzfhZQZuCd0Hb","user_id": "59","lat": "10.008852","long": "76.315568"},"token":""}';
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

    function post_delete($arr) {
        $post_id = $arr['post_id'];
        $user_id = $arr['user_id'];
        $array = array("id" => $post_id, "user_id" => $user_id);
        $this->users_model->del_post($array, $post_id);
        $result = array('status' => 'true', 'message' => "Item successfully deleted");
        echo json_encode($result);
    }

    function delete_review($arr) {
        $review_id = $arr['review_id'];
        $user_id = $arr['user_id'];
        $array = array("review_id" => $review_id, "user_id" => $user_id);
        $this->users_model->del_revw($array);
        $result = array('status' => 'true', 'message' => "Review successfully deleted");
        echo json_encode($result);
    }

    function upload_photo() {
        $user_id = $_POST['user_id'];
        $img = $user_id . "." . $this->users_model->get_ext1($user_id);
        unlink(base_url() . "uploads/members/" . $img);
        $width = '';
        $height = '';
        if ($_POST['flag'] == "Y") {
            if ($_FILES['file']['name'] <> "") {
                $tempFile = $_FILES['file']['tmp_name'];
                list($width, $height) = getimagesize($tempFile);
                $fileParts = pathinfo($_FILES['file']['name']);
                $extension = $fileParts['extension'];
                $imagename = $user_id . "." . $fileParts['extension'];
                $targetFolder = 'uploads/members';
                $targetFile = realpath($targetFolder) . '/' . $imagename;
                move_uploaded_file($tempFile, $targetFile);
                chmod(base_url() . 'uploads/members/' . $imagename, 757);
                $up_arr = array("img_extension" => $extension);
                $cond = array("user_id" => $user_id);
                $this->users_model->update_ext($up_arr, $cond);
            }
        } else {
            $image_url = $_POST['img_url'];
            $extension = "jpg";
            $imagename = $user_id . '.' . $extension;
            list($width, $height) = getimagesize($image_url);
            $image = file_get_contents($image_url); // sets $image to the contents of the url
            file_put_contents('uploads/members/' . $imagename, $image);
            chmod(base_url() . 'uploads/members/' . $imagename, 757);
        }
        $result = array('status' => 'true', 'width' => $width, 'height' => $height, 'message' => "image update successfully", 'img_url' => base_url() . "uploads/members/" . $imagename . "?" . uniqid());
        echo json_encode($result);
    }

    function listAllType() {
        $type = $this->users_model->get_all_type();
        $result = array('status' => 'true', 'type_list' => $type);
        echo json_encode($result);
    }

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
        if ($_POST['flag'] == "Y") {
            $count = $_POST['count'];
            for ($c = 1; $c <= $count; $c++) {
                if ($_FILES['file' . $c]['name'] <> "") {
                    #################  Upload ############
                    $tempFile = $_FILES['file' . $c]['tmp_name'];
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
                }
            }
        } else {
            $image_url1 = $_POST['img_url'];
            $img_url = explode("&&&###", $image_url1);
            $c = 1;
            foreach ($img_url as $image_url) {
                $extension = "jpg";
                $imagename = "postIMAGE_" . strtotime(date("Y-m-d H:i:s")) . uniqid() . '.' . $extension;
                $image = file_get_contents($image_url); // sets $image to the contents of the url
                file_put_contents('uploads/post/' . $imagename, $image);
                chmod(base_url() . 'uploads/post/' . $imagename, 757);
                $targetFolder = 'uploads/post';
                $targetFile = realpath($targetFolder) . '/' . $imagename;
                $image_thumb_name = "thumb_post_640_" . $imagename;
                list($width, $height, $type, $attr) = getimagesize($targetFile);
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
                    $c++;
                }
                $image_array[] = $image_thumb_name;
            }
        }
        $this->users_model->check_brand($brand);

        $bot = $this->users_model->check_bot($user_id);
        if ($bot['bot'] == "NO") {
            $latti1 = $_POST['lat'];
            $longitude1 = $_POST['long'];
            $city = $this->users_model->find_Loc($latti1, $longitude1);
            $lat_long = $this->users_model->get_bot_loc($city);
            $latti = $lat_long['lat'];
            $longitude = $lat_long['long'];
        } else {
            $latti = $bot['bot_lat'];
            $longitude = $bot['bot_long'];
            $city = $this->users_model->find_city($bot['location']);
        }
        $insert_array = array("occasion_id" => $occasion_id, "product_type" => $product_type, "product_name" => $product_name, "price" => $price, "brand" => $brand, "gender" => $gender, "description" => $description, "fileNAME" => $fileNAME, "user_id" => $user_id, "lat" => $latti, 'long' => $longitude, 'product_url' => $site_url, 'posted_locatn' => $city);
        $insert_id = $this->users_model->new_post($insert_array, $image_array);
        $result = array('status' => 'true', 'image_array' => $image_array, 'user_id' => $user_id, 'message' => 'post is submitted  successfully');
        echo json_encode($result);
    }

    #function for push notification 

    function sendPushNotification($message, $deviceToken, $badge_count, $activity_id, $type) {
        //    $deviceToken='4b1e69f6fe604a39d16640698d268e9928de8dfdfed20411e15f0a0d3361e22e';
        $development_mode = 'Y';
        $passphrase = 'newage';
        ////////////////////////////////////////////////////////////////////////////////
        $ctx = stream_context_create();
//        var_dump($ctx);
        if ($development_mode == 'Y') {
            stream_context_set_option($ctx, 'ssl', 'local_cert', 'apns-dev.pem');
        } else {
            stream_context_set_option($ctx, 'ssl', 'local_cert', 'apns-prod.pem');
        }
        if ($development_mode == 'Y') {
            $fp = stream_socket_client(
                    'ssl://gateway.sandbox.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);
        } else {
            $fp = stream_socket_client(
                    'ssl://gateway.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);
        }
        if (!$fp)
            exit("Failed to connect: $err $errstr" . PHP_EOL);
        //echo 'Connected to APNS' . PHP_EOL;
        // Create the payload body
        $body['aps'] = array(
            'alert' => $message,
            'badge' => intval($badge_count),
            'id' => $activity_id,
            'type' => $type,
            'sound' => 'default'
        );
        $payload = json_encode($body);
        // Build the binary notification
        $msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
        $result = fwrite($fp, $msg, strlen($msg));
        fclose($fp);
    }

    function trendy_trends($arr) {
        $user_id = $arr['user_id'];
        $user_status = $this->users_model->get_userStatus($user_id);
        if ($user_status == "Y") {
            $message = "Active";
        } elseif ($user_status == "N") {
            $message = "User is not active";
        } else {
            $message = "User account is deleted!!!";
            $user_status = "N";
        }
        $lat = $arr['lat'];
        $long = $arr['long'];
        $user_gender = $this->users_model->get_settings($user_id);
        $data = $this->users_model->get_trend_post($user_gender, $lat, $long, $user_id);
        $latti = $arr['lat'];
        $longitude = $arr['long'];
        $city = $this->users_model->find_Loc($latti, $longitude);
        $lat_long = $this->users_model->get_bot_loc($city);
        $lattitude2 = $lat_long['lat'];
        $longitude2 = $lat_long['long'];
        $result = array('status' => 'true', 'message' => $message, 'user_status' => $user_status, 'lat' => $lattitude2, 'longitude' => $longitude2, 'location' => $city, 'recent_posts' => $data, 'vote_status' => $vote_status, 'filePath' => base_url() . "uploads/thumbs/post/");
        echo json_encode($result);
    }

    function trendy_save_link($arr) {
        ini_set('max_execution_time', 40);
        require_once 'simplehtmldom_1_5/simple_html_dom.php';
        $request_html = $arr['link'];
        $html = file_get_html($request_html);
        $strlen_html = strlen($html);
        $url = $request_html;
        $pieces = parse_url($url);
        $domain = isset($pieces['host']) ? $pieces['host'] : '';
        if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)) {
            $domain1 = $regs['domain'];
        } else {
            $domain1 = $domain;
        }
//        echo $domain1;
//        exit;
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
//                        echo $domain1 . $ima . "   ; width :$width1, height : $height1<br/>";
                        if ($width >= 150 && $height >= 200) {
                            $img_array[] = trim($ima);
                        } else {
                            $new_im_link = "http://" . $domain1 . $ima;
                            list($width, $height) = getimagesize($new_im_link);
//                            echo $domain1 . $ima . "   ; width :$width1, height : $height1<br/>";
                            if ($width >= 150 && $height >= 200) {
                                $img_array[] = trim($new_im_link);
                            } else {
                                $new_im_link = "https://" . $domain1 . $ima;
                                list($width, $height) = getimagesize($new_im_link);
//                                echo $domain1 . $ima . "   ; width :$width1, height : $height1<br/>";
                                if ($width >= 150 && $height >= 200) {
                                    $img_array[] = trim($new_im_link);
                                } else {
                                    $new_im_link = "http://" . $ima;
                                    list($width, $height) = getimagesize($new_im_link);
//                            echo $new_im_link . "   ; width :$width1, height : $height1<br/>";
                                    if ($width >= 150 && $height >= 200) {
                                        $img_array[] = trim($ima);
                                    } else {
                                        $new_im_link = "https://" . $ima;
                                        list($width, $height) = getimagesize($new_im_link);
//                            echo $domain1 . $ima . "   ; width :$width1, height : $height1<br/>";
                                        if ($width >= 150 && $height >= 200) {
                                            $img_array[] = trim($ima);
                                        } else {
                                            if (substr($ima, 0, 2) === "//") {
                                                $ima = str_replace("//", "http://", $ima);
                                            }
                                            list($width, $height) = getimagesize($ima);
//                            echo $domain1 . $ima . "   ; width :$width1, height : $height1<br/>";
                                            if ($width >= 150 && $height >= 200) {
                                                $img_array[] = trim($ima);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        $test_image_arr[] = $ima;
                    }
                }
//                if (sizeof($img_array) == 0) {
//                    $img_array = $test_image_arr;
//                }
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
        $lattitude = $arr['lat'];
        $longitude = $arr['long'];
        $social = $this->users_model->get_social($user_id, $lattitude, $longitude);
        $return_array = array();
        foreach ($social as $ss1) {
            $return_array[] = $ss1;
        }
        $msg_count = $this->users_model->get_notification_unread($user_id);
        $result = array('status' => 'true', 'notification_unread_count' => $msg_count, 'social_list' => $return_array, 'filePath' => base_url() . "uploads/thumbs/post/");
        echo json_encode($result);
    }

    function check_notification($arr) {
        $user_id = $arr['user_id'];
        $this->users_model->check_notification(array("to_user_id" => $user_id));
        $result = array('status' => 'true', 'msg' => 'success');
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

    function asc_desc_recent($arr) {
        $flag = $arr['flag'];
        $order = $arr['order'];
        if ($flag == "P") {
            $user_id = $arr['user_id'];
            $user_status = $this->users_model->get_userStatus($user_id);
            if ($user_status == "Y") {
                $message = "Active";
            } elseif ($user_status == "N") {
                $message = "User is not active";
            } else {
                $message = "User account is deleted!!!";
                $user_status = "N";
            }
            $lat = $arr['lat'];
            $long = $arr['long'];
            $radius = $this->users_model->get_radius($user_id);
            $user_gender = $this->users_model->get_settings($user_id);
            $data = $this->users_model->get_recent_post($user_gender, $lat, $long, $user_id);
            $latti = $arr['lat'];
            $longitude = $arr['long'];
            $city = $this->users_model->find_Loc($latti, $longitude);
            $lat_long = $this->users_model->get_bot_loc($city);
            $lattitude2 = $lat_long['lat'];
            $longitude2 = $lat_long['long'];
            $result = array('status' => 'true', 'message' => $message, 'user_status' => $user_status, 'lat' => $lattitude2, 'longitude' => $longitude2, 'location' => $city, 'radius' => $radius, 'recent_posts' => $data, 'filePath' => base_url() . "uploads/thumbs/post/");
            echo json_encode($result);
        }
    }

    function trendy_recent($arr) {
        $this->load->model('users_model');
        $user_id = $arr['user_id'];
        $user_status = $this->users_model->get_userStatus($user_id);
        if ($user_status == "Y") {
            $message = "Active";
        } elseif ($user_status == "N") {
            $message = "User is not active";
        } else {
            $message = "User account is deleted!!!";
            $user_status = "N";
        }
        $lat = $arr['lat'];
        $long = $arr['long'];
        $radius = $this->users_model->get_radius($user_id);
        $user_gender = $this->users_model->get_settings($user_id);
        $data = $this->users_model->get_recent_post($user_gender, $lat, $long, $user_id);
        $latti = $arr['lat'];
        $longitude = $arr['long'];
        $city = $this->users_model->find_Loc($latti, $longitude);
        $lat_long = $this->users_model->get_bot_loc($city);
        $lattitude2 = $lat_long['lat'];
        $longitude2 = $lat_long['long'];
        $result = array('status' => 'true', 'message' => $message, 'user_status' => $user_status, 'lat' => $lattitude2, 'longitude' => $longitude2, 'location' => $city, 'radius' => $radius, 'recent_posts' => $data, 'filePath' => base_url() . "uploads/thumbs/post/");
        echo json_encode($result);
    }

    function trend_track($arr) {
        $user_id = $arr['user_id'];
        $following_by = $arr['following'];
        $settings = $this->users_model->get_settings1($following_by);
        $device_tkn = $this->users_model->post_device_tocken($following_by);
        $badge_count = '0';
        if (strcasecmp($arr['request_status'], "track") == 0) {
            if (strcasecmp($settings['profile'], "public") == 0) {
                $array = array("following" => $following_by, "followed_by" => $user_id, "request_status" => "accept");
                $this->users_model->save_track($array);
                $msg = $this->users_model->get_user_name($user_id) . "  is now tracking you";
                $not_array = array("message" => $msg, "to_user_id" => $following_by, "from_user_id" => $user_id, "inserted_on" => date("Y-m-d H:i:s"), "type" => "track_request_public");
                $type = 'track_request_public';
                $notification_status = $this->users_model->notofication_status($following_by);
                if ($notification_status == "on") {
                    $badge_count = '0';
                    $device_tkn = $this->users_model->post_device_tocken($following_by);
                    $this->users_model->sendPushNotification($msg, $device_tkn, $badge_count, $user_id, $type);
                }
                $result = array('status' => 'true', 'message' => "Successfully tracked");
            } else {
                $msg = $this->users_model->get_user_name($user_id) . " has requested to Track you";
//            echo $msg;
                $not_array = array("message" => $msg, "to_user_id" => $following_by, "from_user_id" => $user_id, "inserted_on" => date("Y-m-d H:i:s"), "type" => "track_request");

//            echo $this->db->last_query();
                $type = 'track_request';

//            echo $device_tkn;
                $notification_status = $this->users_model->notofication_status($following_by);
                if ($notification_status == "on") {
                    $badge_count = '0';
                    $device_tkn = $this->users_model->post_device_tocken($following_by);
                    $this->users_model->sendPushNotification($msg, $device_tkn, $badge_count, $following_by, $type);
                }
                $array = array("following" => $following_by, "followed_by" => $user_id);
                $this->users_model->save_track($array);
                $result = array('status' => 'true', 'message' => "Successfully Requested");
            }

            $this->users_model->save_notifications($not_array);
        } else {

            $array = array("following" => $following_by, "followed_by" => $user_id);
            $arr = array("request_status" => "un_track");
            $this->users_model->update_track($arr, $array);
            $result = array('status' => 'true', 'message' => "Successfully untracked");
        }

        echo json_encode($result);
    }

    function get_state($arr) {
        $country_id = $arr['country_id'];
        $state = $this->users_model->get_state($country_id);
        $result = array('status' => 'true', 'state' => $state, 'message' => "Successfully Listed");
        echo json_encode($result);
    }

    function get_city_state($arr) {
        $state_id = $arr['state'];
        $city = $this->users_model->get_city_state($state_id);
        $result = array('status' => 'true', 'city' => $city, 'message' => "Successfully Listed");
        echo json_encode($result);
    }

    function notification_read($arr) {
        $array_cond = array("id" => $arr['notification_id']);
        $array = array("status" => "read");
        $this->users_model->note_read($array, $array_cond);
    }

    function notification_delete($arr) {
        $array_cond = array("to_user_id" => $arr['logged_user_id'], "id" => $arr['notification_id']);
        $this->users_model->note_del($array_cond);
    }

    function notifications($arr) {
        $user_id = $arr['user_id'];
        $this->users_model->check_notification(array("to_user_id" => $user_id));
        $notifications = $this->users_model->get_myNotifications($user_id);
        $result = array('status' => 'true', 'message' => "success", 'notifications' => $notifications, 'filePath' => base_url() . "uploads/members/");
        echo json_encode($result);
    }

    function tacking_list($arr) {
        $data = $this->users_model->get_tracking_list($arr['user_id'], $arr['logged_user_id']);
        $result = array('status' => 'true', 'list' => $data, 'filePath' => base_url() . "uploads/members/");
        echo json_encode($result);
    }

    #

    function test_update_location() {
        $data = $this->db->query("select id,lat,`long` from trend_post where posted_locatn is null ");
        $res = $data->result_array();
//        print
        foreach ($res as $result) {
            $latti = $result['lat'];
            $longitude = $result['long'];
            $id = $result['id'];
            $file_contents = file_get_contents("http://maps.googleapis.com/maps/api/geocode/json?latlng=" . $latti . "," . $longitude . "&sensor=true");
            $json_decode = json_decode($file_contents);
            if (isset($json_decode->results[0])) {
                $response = array();
                foreach ($json_decode->results[0]->address_components as $addressComponet) {
                    if (in_array('political', $addressComponet->types)) {
                        $response[] = $addressComponet->long_name;
                    }
                }
                if (isset($response[0])) {
                    $first = $response[0];
                } else {
                    $first = 'null';
                }
                if (isset($response[1])) {
                    $second = $response[1];
                } else {
                    $second = 'null';
                }
                if (isset($response[2])) {
                    $third = $response[2];
                } else {
                    $third = 'null';
                }
                if (isset($response[3])) {
                    $fourth = $response[3];
                } else {
                    $fourth = 'null';
                }
                if (isset($response[4])) {
                    $fifth = $response[4];
                } else {
                    $fifth = 'null';
                }

                if ($first != 'null' && $second != 'null' && $third != 'null' && $fourth != 'null' && $fifth != 'null') {

                    $city = $second;
                } else if ($first != 'null' && $second != 'null' && $third != 'null' && $fourth != 'null' && $fifth == 'null') {
                    $city = $second;
                } else if ($first != 'null' && $second != 'null' && $third != 'null' && $fourth == 'null' && $fifth == 'null') {
                    $city = $first;
                } else if ($first != 'null' && $second != 'null' && $third == 'null' && $fourth == 'null' && $fifth == 'null') {
                    $city = $first;
                } else if ($first != 'null' && $second == 'null' && $third == 'null' && $fourth == 'null' && $fifth == 'null') {
                    $city = $first;
                }
            }
            $this->db->update("trend_post", array("posted_locatn" => $city), array("id" => $id));
        }
    }

    function arr_test() {
        $age = array("Alex" => "135", "Xavier" => "37", "Joe" => "43");
        ksort($age);
        print_r($age);
    }

    function trackers_list($arr) {
        $data = $this->users_model->get_trackers_list($arr['user_id'], $arr['logged_user_id']);
        $result = array('status' => 'true', 'list' => $data, 'filePath' => base_url() . "uploads/members/");
        echo json_encode($result);
    }

    function get_reviews_full($arr) {
        $product_id = $arr['product_id'];
        $uid = $arr['user_id'];
        $review = $this->users_model->get_full_reviews($product_id, $uid);
        $result = array('status' => 'true', 'review' => $review);
        echo json_encode($result);
    }

    function save_review($arr) {
        $this->load->model('users_model');
        $post_id = $arr['post_id'];
        $user_id = $arr['user_id'];
        $review = $arr['review'];
        $post_onwer = $this->users_model->get_post_onwer($post_id);
        if ($post_onwer != $user_id) {
            $message = "Your item received a new review!";
            $badge_count = 0;
            $type = "review";
            $notification_status = $this->users_model->notofication_status($post_onwer);
            if ($notification_status == "on") {
                $post_device_tocken = $this->users_model->post_device_tocken($post_onwer);
                $this->users_model->sendPushNotification($message, $post_device_tocken, $badge_count, $arr['post_id'], $type);
            }
            $not_array = array("message" => $message, "to_user_id" => $post_onwer, "from_user_id" => $user_id, "inserted_on" => date("Y-m-d H:i:s"), "type" => "review", "post_id" => $post_id);
            $this->users_model->save_notifications($not_array);
        }
        $ins_array = array("review" => $review, "post_id" => $post_id, "user_id" => $user_id);
        $this->users_model->save_review($ins_array);
        $result = array('status' => 'true', 'message' => 'successfully posted your review');
        echo json_encode($result);
    }

    function get_profile_details($arr) {
        $user_id = $arr['user_id'];
        $logged_id = $arr['logged_user_id'];
        $data = $this->users_model->get_profile($user_id);
        $posted_items = $this->users_model->get_posted_items($user_id);
        $tracker = $this->users_model->check_track($user_id, $logged_id);
        $reviewd_items = $this->users_model->get_user_review($user_id);
        $tracking_count = $this->users_model->get_trackingCount($user_id);
        $saved_items = $this->users_model->get_user_saved_items($user_id);
        $result = array('status' => 'true', 'reviewed_items' => $reviewd_items, 'tracking_count' => $tracking_count, 'saved_items' => $saved_items, 'user_detais' => $data, 'tracking_details' => $tracker, 'posted_items' => $posted_items, 'post_pic_path' => base_url() . "uploads/thumbs/post/", 'profile_pic_path' => base_url() . "uploads/members/", 'message' => 'user details');
        echo json_encode($result);
    }

    function track_response($arr) {
        $user_id = $arr['user_id'];
        $logged_id = $arr['logged_user_id'];
        $status = $arr['status'];

        $array_cond = array("following" => $logged_id, "followed_by" => $user_id);
        $up_array = array("request_status" => $status);
        $this->users_model->track_response($array_cond, $up_array);
        if ($status == "accept") {
            $not_array = array("message" => "Your Tracking request has been accepted", "to_user_id" => $user_id, "from_user_id" => $logged_id, "inserted_on" => date("Y-m-d H:i:s"), "type" => "track_accept");
            $this->users_model->save_notifications($not_array);
            $type1 = "track request approved";
            $message = $this->users_model->get_user_name($logged_id) . " has accepted your Track request!";
            $post_device_tocken = $this->users_model->post_device_tocken($user_id);
            $badge_count = 0;
            $this->users_model->sendPushNotification($message, $post_device_tocken, $badge_count, $logged_id, $type1);
        }
        $this->notification_delete($arr);
        $result = array('status' => 'true', 'message' => 'success', 'type' => $status);
        echo json_encode($result);
    }

    function get_occasion() {
        $this->load->model('test_user');
        $data = $this->users_model->get_occasion();
        $asd = 0;
        $array_cat = array();
        $category = $this->test_user->get_category_recursive($asd, "male");
        for ($i = 0; $i < count($category); $i++) {
            $category[$i][submenu] = $this->test_user->get_category_recursive($category[$i]['category_id'], "male");
            $cat[$i] = count($category[$i][submenu]);
        }
        for ($i = 0; $i < count($category); $i++) {
            for ($a = 0; $a < $cat[$i]; $a++) {
                $category[$i][submenu][$a][submenu] = $this->test_user->get_category_recursive($category[$i][submenu][$a]['category_id'], "male");
            }
        }
        $category_f = $this->test_user->get_category_recursive($asd, "Female");
        for ($i = 0; $i < count($category_f); $i++) {
            $category_f[$i][submenu] = $this->test_user->get_category_recursive($category_f[$i]['category_id'], "Female");
            $cat[$i] = count($category_f[$i][submenu]);
        }
        for ($i = 0; $i < count($category_f); $i++) {
            for ($a = 0; $a < $cat[$i]; $a++) {
                $category_f[$i][submenu][$a][submenu] = $this->test_user->get_category_recursive($category_f[$i][submenu][$a]['category_id'], "Female");
            }
        }
        $brand = $this->users_model->get_brand_full_LIST();
        $result = array('status' => 'true', 'product_list' => $category, 'male_cat' => $category, 'female_cat' => $category_f, 'occasion_list' => $data, 'brand_list' => $brand);
        echo json_encode($result);
    }

    function trend_vote($arr) {
        $count = $this->users_model->trend_vote($arr);
        $result = array('status' => 'true', 'vote_count' => $count, 'message' => 'success');
        echo json_encode($result);
    }

    function get_occasion_list($arr) {
        $latti = $arr['lat'];
        $longitude = $arr['long'];
        $city = $this->users_model->find_Loc($latti, $longitude);
        $occasion = $this->users_model->get_occasion();
        $result = array('status' => 'true', 'location' => $city, 'occasion_list' => $occasion, 'filePath' => base_url() . "uploads/occasion/", 'message' => 'success');
        echo json_encode($result);
    }

    function get_product_occasion($arr) {
        $occasion_id = $arr['occasion_id'];
        $user_id = $arr['user_id'];
        $latti1 = $arr['lat'];
        $longitude1 = $arr['long'];
        $city = $this->users_model->find_Loc($latti1, $longitude1);
        $lat_long = $this->users_model->get_bot_loc($city);
        $lat = $lat_long['lat'];
        $long = $lat_long['long'];
        $user_gender = $this->users_model->get_settings($user_id);
        $data = $this->users_model->get_product_occasion($occasion_id, $user_gender, $lat, $long, $user_id);
        $city = $this->users_model->find_Loc($lat, $long);
        $result = array('status' => 'true', 'location' => $city, 'product_list' => $data, 'filePath' => base_url() . "uploads/thumbs/post/", 'message' => 'success');
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
                $device_tkn = $this->users_model->updateDevice_Token($arr['device_id'], $userdet['user_id']);
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
//                $latti = $arr['lat'];
//                $longitude = $arr['long'];
//                $city = $this->users_model->find_Loc($latti, $longitude);
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

    function search_user($arr) {
        $key_word = $arr['key_word'];
        $user_id = $arr['user_id'];
        $user = $this->users_model->get_search($key_word, $user_id);
        $result = array('status' => 'true', 'message' => 'successfully searched', 'data' => $user, 'filePath' => base_url() . "uploads/members/");
        echo json_encode($result);
    }

    function save_radius($arr) {
        $user_id = $arr['user_id'];
        $radius = $arr['radius'];
        $ins_arr = array("radius" => $radius);
        $cond = array("user_id" => $user_id);
        $this->users_model->save_radius($ins_arr, $cond);
        $result = array('status' => 'true', 'message' => 'successfully updated',);
        echo json_encode($result);
    }

    function product_details($arr) {
        $pdt_id = $arr['pdt_id'];
        $user_id = $arr['user_id'];
        $user_gender = $this->users_model->get_settings($user_id);
        $radius = $this->users_model->get_radius($user_id);
        $details = $this->users_model->pdt_details($pdt_id, $user_id);
        if ($details['occasion_id'] != '') {
            $suggested_items = $this->users_model->suggested_items($details['id'], $details['occasion_id'], $user_gender);
        } else {
            $suggested_items = array();
        }
        $result = array('status' => 'true', 'radius' => $radius, 'product_details' => $details, 'suugested_items' => $suggested_items, 'filePath' => base_url() . "uploads/thumbs/post/");
        echo json_encode($result);
    }

    function clear_filter($arr) {
        $user_id = $arr['user_id'];
        $this->users_model->clear_filter($user_id);
        $result = array('status' => 'true', "message" => "clear");
        echo json_encode($result);
    }

    function clear_filter_trend($arr) {
        $user_id = $arr['user_id'];
        $this->users_model->clear_filter_trend($user_id);
        $result = array('status' => 'true', "message" => "clear");
        echo json_encode($result);
    }

    function clear_filter_occasion($arr) {
        $user_id = $arr['user_id'];
        $this->users_model->clear_filter_occasion($user_id);
        $result = array('status' => 'true', "message" => "clear");
        echo json_encode($result);
    }

    function filter($arr) {
        $user_id = $arr['user_id'];
        $type = $arr['type'];
        $type_array = explode(",", $type);
        $brand = $arr['brand'];
//        if($brand!=''){
        $brand_array = explode(",", $brand);
        $this->users_model->save_filter_child($user_id, $type_array, $brand_array);
        $popularity_start = $arr['pop_start'];
        $popularity_end = $arr['pop_end'];
        $price_start = $arr['price_start'];
        $price_end = $arr['price_end'];
        $array = array("user_id" => $user_id, "pop_start" => $popularity_start, "pop_end" => $popularity_end, "price_start" => $price_start, "price_end" => $price_end);
        $cond = array("user_id" => $user_id);
        $chk_exist = $this->users_model->check_settings_filter($user_id);
        if ($chk_exist > 0) {
            $this->users_model->update_filter($array, $cond);
        } else {
            $this->users_model->save_filter($array);
        }
        $result = array('status' => 'true', 'message' => "Succesfully updated");
        echo json_encode($result);
    }

    function filter_occasion($arr) {
        $user_id = $arr['user_id'];
        $type = $arr['type'];
        $type_array = explode(",", $type);
        $brand = $arr['brand'];
        $brand_array = explode(",", $brand);
        $this->users_model->save_filter_child_occasion($user_id, $type_array, $brand_array);
        $popularity_start = $arr['pop_start'];
        $popularity_end = $arr['pop_end'];
        $price_start = $arr['price_start'];
        $price_end = $arr['price_end'];
        $array = array("user_id" => $user_id, "pop_start" => $popularity_start, "pop_end" => $popularity_end, "price_start" => $price_start, "price_end" => $price_end);
        $cond = array("user_id" => $user_id);
        $chk_exist = $this->users_model->check_settings_filter_occasion($user_id);
        if ($chk_exist > 0) {
            $this->users_model->update_filter_occasion($array, $cond);
        } else {
            $this->users_model->save_filter_occasion($array);
        }
        $result = array('status' => 'true', 'message' => "Succesfully updated");
        echo json_encode($result);
    }

    function filter_trend($arr) {
        $user_id = $arr['user_id'];
        $type = $arr['type'];
        $type_array = explode(",", $type);
        $brand = $arr['brand'];
        $brand_array = explode(",", $brand);
        $this->users_model->save_filter_child_trend($user_id, $type_array, $brand_array);
        $popularity_start = $arr['pop_start'];
        $popularity_end = $arr['pop_end'];
        $price_start = $arr['price_start'];
        $price_end = $arr['price_end'];
        $array = array("user_id" => $user_id, "pop_start" => $popularity_start, "pop_end" => $popularity_end, "price_start" => $price_start, "price_end" => $price_end);
        $cond = array("user_id" => $user_id);
        $chk_exist = $this->users_model->check_settings_filter_trend($user_id);
        if ($chk_exist > 0) {
            $this->users_model->update_filter_trend($array, $cond);
        } else {
            $this->users_model->save_filter_trend($array);
        }
        $result = array('status' => 'true', 'message' => "Succesfully updated");
        echo json_encode($result);
    }

    function get_city($arr) {
        $data = $this->users_model->get_city($arr['country_id']);
        $result = array('status' => 'true', 'message' => "Succesfully updated", "city" => $data);
        echo json_encode($result);
    }

    function get_country($arr) {
        $data = $this->users_model->get_country();
        $result = array('status' => 'true', 'message' => "Succesfully updated", "country" => $data);
        echo json_encode($result);
    }

    function error_reporting($arr) {
        $user_id = $arr['user_id'];
        $link = $arr['link'];
        $array = array("link" => $link, "user_id" => $user_id);
        $this->users_model->save_error($array);
        $result = array('status' => 'true', 'message' => "Succesfully updated");
        echo json_encode($result);
    }

    function get_filter($arr) {
        $lat = $arr['lat'];
        $long = $arr['long'];
        $user_id = $arr['user_id'];
        $gender = $this->users_model->get_settings($user_id);
        $city = $this->users_model->find_Loc($lat, $long);
        $this->load->model('test_user');
        $data = $this->users_model->get_filter($user_id);
        $asd = 0;
        $category = $this->users_model->get_all_type($city, $gender);
        $brand = $this->users_model->get_brand_full($city, $gender);
        $max_min_PricePopularity = $this->users_model->pop_price_status($lat, $long, $city, $gender);
        $result = array('status' => 'true', 'filter_data' => $data, "brand_list" => $brand, 'min_max_price_popularity' => $max_min_PricePopularity, "type_list" => $category);
        echo json_encode($result);
    }

    function get_filter_occasion($arr) {
        $lat = $arr['lat'];
        $long = $arr['long'];
        $user_id = $arr['user_id'];
        $occasion_id = $arr['occasion_id'];
        $gender = $this->users_model->get_settings($user_id);
        $city = $this->users_model->find_Loc($lat, $long);
        $this->load->model('test_user');
        $data = $this->users_model->get_filter_occasion($user_id);
        $asd = 0;
        $category = $this->users_model->get_all_type_occasion($city, $gender, $occasion_id);
        $brand = $this->users_model->get_brand_full_occasion($city, $gender, $occasion_id);
        $max_min_PricePopularity = $this->users_model->pop_price_status_occasion($lat, $long, $gender, $user_id, $occasion_id);
        $result = array('status' => 'true', 'filter_data' => $data, "brand_list" => $brand, 'min_max_price_popularity' => $max_min_PricePopularity, "type_list" => $category);
        echo json_encode($result);
    }

    function get_filter_trend($arr) {
        $lat = $arr['lat'];
        $long = $arr['long'];
        $user_id = $arr['user_id'];
        $gender = $this->users_model->get_settings($user_id);
        $city = $this->users_model->find_Loc($lat, $long);
        $this->load->model('test_user');
        $data = $this->users_model->get_filter_trend($user_id);
        $asd = 0;
        $category = $this->users_model->get_all_type_trend($city, $gender);
        $brand = $this->users_model->get_brand_full_trend($city, $gender);
        $max_min_PricePopularity = $this->users_model->pop_price_status_trend($lat, $long, $city, $gender, $user_id);
        $result = array('status' => 'true', 'filter_data' => $data, "brand_list" => $brand, 'min_max_price_popularity' => $max_min_PricePopularity, "type_list" => $category);
        echo json_encode($result);
    }

    function get_settings($arr) {
        $data = $this->users_model->get_settings1($arr['user_id']);
        $result = array('status' => 'true', 'settings' => $data);
        echo json_encode($result);
    }

    function logout($arr) {
        $user_id = $arr['userID'];
        $this->users_model->logout($user_id);
        $result = array('status' => 'true', 'msg' => 'succesfully logout');
        echo json_encode($result);
    }

    function add_to_occation($arr) {
        $user_id = $arr['user_id'];
        $post_id = $arr['product_id'];
        $custom = $arr['custom'];
        if ($custom == 'Y') {
            $occasion_id = $this->users_model->create_ocassion_byApp($arr['occasion_id']);
        } else {
            $occasion_id = $arr['occasion_id'];
        }
        $update_array = array("occasion_id" => $occasion_id);
        $cond = array("id" => $post_id);
        $this->users_model->update_pdt_occasion($update_array, $cond);
        $result = array('status' => 'true', 'message' => 'successfully saved');
        echo json_encode($result);
    }

    public function delete_reviews($arr) {
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
        $posted_items = $this->users_model->get_posted_items($user_id);
        $result = array('status' => 'true', 'user_detais' => $data, 'tracking_details' => $tracker, 'reviewed_items' => $reviewd_items, 'saved_items' => $saved_items, 'posted_items' => $posted_items, 'post_pic_path' => base_url() . "uploads/thumbs/post/", 'profile_pic_path' => base_url() . "uploads/members/", 'message' => 'user details');
        echo json_encode($result);
    }

    function contact($arr) {
        $name = $arr['name'];
        $email_id_from = $arr['from_email'];
        $message1 = "Hi<br/>Name:" . $name . " <br/> email id: " . $email_id_from . " <br/> message : <br>" . $arr['message'];
        $message = $arr['message'];
        $config['mailtype'] = 'html';
        $config['charset'] = 'utf-8';
        $config['crlf'] = PHP_EOL;
        $config['newline'] = PHP_EOL;
        $this->load->library('email');
        $this->email->initialize($config);
        $array = array("name" => $name, "from" => $email_id_from, "message" => $message);
        $this->users_model->save_contact($array);
        $this->email->reply_to('support@trendyservices.com', 'Trendy Services');
        $this->email->from($this->config->item('email_from'));
        $this->email->to($this->config->item('admin_email'));
        $this->email->subject("contact from user");
        $this->email->message($message1);
        $this->email->send();
        $result = array('status' => 'true', 'message' => 'successfully submitted');
        echo json_encode($result);
    }

    function track_approval($arr) {
        $user_id = $arr['user_id'];
        $followed_by = $arr['followed_by'];
        $r_array = array("following" => $user_id, "followed_by" => $followed_by);
        $array = array("request_status" => "accept");
        $not_array = array("message" => "Your Tracking request has been accepted", "to_user_id" => $followed_by, "from_user_id" => $user_id, "inserted_on" => date("Y-m-d H:i:s"), "type" => "track_accept");
        $this->users_model->save_notifications($not_array);
        $this->users_model->apprv_track($r_array, $array);
        $result = array('status' => 'true', 'message' => 'successfully approved');
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
                        $image_url = $arr['fburl'] . "&" . uniqid();
                        $extension = "jpg";
                        $completefileName = $user_id . '.' . $extension;
//                        $image = file_get_contents($image_url); // sets $image to the contents of the url
//                        file_put_contents('uploads/members/' . $completefileName, $image);
                        $completefileName = $user_id . '.' . $extension;
//                        $image = file_get_contents($image_url); // sets $image to the contents of the url
                        $dest = 'uploads/members/' . $completefileName;
                        copy($image_url, $dest);
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


                    ////////

                    $array = array("user_id" => $user_id, "gender" => $arr['user_gender'], "updated_on" => date("Y-m-d H:i:s"));
                    if ($this->users_model->check_setiings_user($user_id) == FALSE) {
                        $this->users_model->save_settings($array);
                    } else {
                        $cond = array("user_id" => $user_id);
                        $this->users_model->up_settings($array, $cond);
                    }




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

    #review rating function 

    function review_rating($arr) {
        $review_id = $arr['review_id'];
        $user_id = $arr['user_id'];
        $array = array("user_id" => $user_id, "review_id" => $review_id);
        $count = $this->users_model->revw_rate($array, $review_id);
        $result = array('status' => 'true', 'message' => 'succesfully save your rating ', 'review_count' => $count);
        echo json_encode($result);
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
            $device_tkn = $this->users_model->updateDevice_Token($arr['device_id'], $user_id);
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