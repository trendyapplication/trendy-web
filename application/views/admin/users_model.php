<?php

ini_set("display_errors", "on");

class Users_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    # function to check whether the email already created by the user

    function checkEmailExist($email, $user_id = '') {
        $sql = "select  user_email from user_master where user_email = '$email' and user_type != '1'  ";
        $query = $this->db->query($sql);
        $result = $query->row_array();
        if ($result['user_email'])
            return true;
        else
            return false;
    }

    function save_contact($arr) {
        return $this->db->insert("contact", $arr);
    }

    function revw_rate($arr, $id) {
        $this->db->insert("review_rate", $arr);
        $cou = $this->db->query("select count(*) as count_rate from review_rate where review_id='$id'");
        $count = $cou->row_array();
        if ($count['count_rate'] >= 3) {
            $this->db->delete("trend_review", array("review_id" => $id));
        }
        return $count['count_rate'];
    }

    function get_trackingCount($user_id) {
        $track = $this->db->query("select count(*) as tracked_by from tracking_user where followed_by='$user_id'");
        $track_by = $track->row_array();
        $tra = $this->db->query("select count(*) as tracking_count from tracking_user where following='$user_id'");
        $tracking = $tra->row_array();
        $result['tracking'] = $track_by['tracked_by'];
        $result['tracked_by'] = $tracking['tracking_count'];
        return $result;
    }

    function get_profile($id) {
        $result = $this->db->query("select * from user_master where user_id='$id'");
        $data = $result->row_array();
        $set = $this->db->query("select profile from user_settings where user_id='$id'");
        $settings = $set->row_array();
        $data['profile'] = $settings['profile'];
        $data['img_extension'] = $data['img_extension'] . "?" . uniqid();
        return $data;
    }

    function get_user_list($id) {
        $result = $this->db->query("select * from user_master where active='Y' and user_id !='$id'");
        $data = $result->result_array();
        foreach ($data as $key => $dat) {
            $user_id = $dat['user_id'];
            $track = $this->db->query("select tracking_user where followed_by='$id' and following='$user_id'");
            $tracking = $track->row_array();
            if (sizeof($tracking) > 0) {
                $data[$key]['tracking'] = 'Yes';
            } else {
                $data[$key]['tracking'] = 'No';
            }
        }
        return $data;
    }

    function get_user_review($id) {
        $result = $this->db->query("select * from trend_review where user_id='$id'");
        $data = $result->result_array();
        foreach ($data as $key => $dat) {
            $product_id = $dat['post_id'];
            $pdt = $this->db->query("select * from trend_post where id='$product_id'");
            $product = $pdt->row_array();
            $data[$key]['product'] = $product;
        }
        return $data;
    }

    function get_country() {
        $data = $this->db->query("select * from trendy_country order by id desc");
        return $data->result_array();
    }

    function get_city($id) {
        $data = $this->db->query("select * from trendy_city where country='$id' order by id desc");
        return $data->result_array();
    }

    function get_adminMail() {
        $email = $this->db->query("select * from user_master");
    }

    function get_posted_items($id) {
        $result = $this->db->query("select * from trend_post where user_id='$id'");
        $data = $result->result_array();
        foreach ($data as $key => $dat) {
            $data[$key]['post_id'] = $dat['id'];
            $data[$key]['product'] = $dat;
        }
        return $data;
    }

    function get_user_saved_items($id) {
        $result = $this->db->query("select * from user_product_child where user_id='$id'");
        $data = $result->result_array();
        foreach ($data as $key => $dat) {
            $product_id = $dat['product_id'];
            $pdt = $this->db->query("select * from trend_post where id='$product_id'");
            $product = $pdt->row_array();
            $data[$key]['product'] = $product;
//            $data[$key]['file_path']=  base_url()."uploads/thumbs/post/";
        }
        return $data;
    }

    function track_response($arr, $up) {
        return $this->db->update("tracking_user", $up, $arr);
    }

    function create_ocassion_byApp($id) {
        $this->db->query("insert into occasion_master(`name`) values('$id')");
        return $this->db->insert_id();
    }

    function getOccasionCount($status, $user_id = '') {
        $sql = "select  count(*) from occasion_master  where status = '$status'   ";
        $query = $this->db->query($sql);
        $result = $query->row_array();
        if ($result['user_email'])
            return true;
        else
            return false;
    }

    function getCategoryCount($status, $user_id = '') {
        $sql = "select  count(*) from category  where visible = '$status'   ";


        $query = $this->db->query($sql);
        $result = $query->row_array();
        if ($result['user_email'])
            return true;
        else
            return false;
    }

    function new_post($array) {
        $this->db->insert("trend_post", $array);
        return $this->db->insert_id();
    }

    function getPackagebyid($id) {
        $pack = $this->db->query("select * from package_master where package_id='" . $id . "'");
        return $pack->row_array();
    }

    function get_full_reviews($id, $user_id) {
        $data = $this->db->query("select T1.*,T2.name as username from trend_review T1 left join user_master T2 on T1.user_id = T2.user_id where T1.post_id='" . $id . "' order by T1.review_id desc");
        $rev_details = $data->result_array();
        foreach ($rev_details as $revKey => $revd) {
            $revs_id = $revd['review_id'];
            $revStatus = $this->db->query("select count(*) as count_s from review_rate where user_id= '$user_id' and review_id='$revs_id'");
            $statC = $revStatus->row_array();
            if ($statC['count_s'] > 0) {
                $rev_details[$revKey]['vote_status'] = "YES";
            } else {
                $rev_details[$revKey]['vote_status'] = "NO";
            }
            $revCount = $this->db->query("select count(*) as count_s1 from review_rate where  review_id='$revs_id'");
            $statCou = $revCount->row_array();
            $rev_details[$revKey]['reviewRateCount'] = $statCou['count_s1'];
        }
        return $rev_details;
    }

    function save_review($array) {
//        print_r($array);
        $this->db->insert("trend_review", $array);
        return $this->db->insert_id();
    }

    function clear_filter($user_id) {
        $cond = array("user_id" => $user_id);
        $this->db->delete("filter_brand_child", $cond);
        $this->db->delete("filter_type_child", $cond);
        $this->db->delete("filter_settings", $cond);
        return TRUE;
    }

    function get_recent_post1($gender, $lattitude, $longitude, $user_id) {
        $current_time = strtotime("Y-m-d H:i:s");
        $radius = $this->get_radius();
        $lat_range = $radius / 69.172;
        $lon_range = abs($radius / (cos($longitude) * 69.172));
        $min_lat = $lattitude - $lat_range; # $mile_radius would be 5 for our example, $lat is the fixed lattitude for our zip code
        $max_lat = $lattitude + $lat_range;
        $min_lon = $longitude - $lon_range;
        $max_lon = $longitude + $lon_range;
        if ($gender != 'both') {
            $qry = "select id,price,fileName,( 3959 * acos( cos( radians($lattitude) ) * cos( radians( lat ) ) * cos( radians(`long`) - radians($longitude) ) + sin( radians($lattitude) ) * sin( radians( lat ) ) ) ) AS distance   from trend_post where gender='" . $gender . "' Having (distance <= '$radius' OR distance is null) order by id desc";
        } else {
            $qry = "select id,price,fileName,( 3959 * acos( cos( radians($lattitude) ) * cos( radians( lat ) ) * cos( radians(`long`) - radians($longitude) ) + sin( radians($lattitude) ) * sin( radians( lat ) ) ) ) AS distance   from trend_post  Having (distance <= '$radius' OR distance is null) order by id desc";
        }
//        echo $qry;
//        $qry. "( 3959 * acos( cos( radians($lattitude) ) * cos( radians( lat ) ) * cos( radians( long ) - radians($longitude) ) + sin( radians($lattitude) ) * sin( radians( lat ) ) ) ) AS distance  ";
# here we do a little extra for longitude
        $post = $this->db->query($qry);
        $post_array = $post->result_array();
        $return_array = array();
        $kk = 0;
        foreach ($post_array as $key => $array) {
            $up = $this->db->query("select count(*) as upcount from trend_vote where vote='up' and post_id='" . $array['id'] . "'");
            $upv = $up->row_array();
            $up_vote = $upv['upcount'];
            $down = $this->db->query("select count(*) as upcount from trend_vote where vote='down' and post_id='" . $array['id'] . "'");
            $downc = $down->row_array();
            $down_vote = $downc['upcount'];
            $vote = $up_vote - $down_vote;
            if ($vote > -5) {
                $return_array[$kk] = $array;
                $vote_status = $this->get_vote_status($user_id, $array['id']);
                $return_array[$kk]['vote_status'] = $vote_status;
                $return_array[$kk]['vote_count'] = $vote;
                $rev = $this->db->query("select count(*) as review_count from trend_review where post_id='" . $array['id'] . "'");
                $rev_count = $rev->row_array();
                $return_array[$kk]['review_count'] = $rev_count['review_count'];
                $kk++;
            }
        }
        $array1 = array();
        $array2 = array();
        $array3 = array();
        foreach ($return_array as $return) {
            $posted_time = strtotime($return['created_on']);
            if ($current_time - $posted_time <= (3600 * 24)) {
                $array2[] = $return;
            } elseif (($current_time - $posted_time > (3600 * 48)) && ($current_time - $posted_time <= (3600 * 72))) {
                $up1 = $this->db->query("select count(*) as upcount from trend_vote where vote='up' and post_id='" . $return['id'] . "' AND created_on >= now() - INTERVAL 2 DAY");
                $upv1 = $up1->row_array();
                $up_vote1 = $upv1['upcount'];
                $down1 = $this->db->query("select count(*) as upcount from trend_vote where vote='down' and post_id='" . $return['id'] . "' AND created_on >= now() - INTERVAL 2 DAY");
                $downc1 = $down1->row_array();
                $down_vote1 = $downc1['upcount'];
                $vote1 = $up_vote1 - $down_vote1;
                if ($vote1 >= 25) {
                    $array1[] = $return;
                } else {
                    $array3[] = $return;
                }
            } elseif (($current_time - $posted_time > (3600 * 24)) && ($current_time - $posted_time <= (3600 * 48))) {
                $up1 = $this->db->query("select count(*) as upcount from trend_vote where vote='up' and post_id='" . $return['id'] . "' AND created_on >= now() - INTERVAL 1 DAY");
                $upv1 = $up1->row_array();
                $up_vote1 = $upv1['upcount'];
                $down1 = $this->db->query("select count(*) as upcount from trend_vote where vote='down' and post_id='" . $return['id'] . "' AND created_on >= now() - INTERVAL 1 DAY");
                $downc1 = $down1->row_array();
                $down_vote1 = $downc1['upcount'];
                $vote1 = $up_vote1 - $down_vote1;
                if ($vote1 >= 10) {
                    $array1[] = $return;
                } else {
                    $array3[] = $return;
                }
            } else {
                $array3[] = $return;
            }
        }
        $array4 = array_merge($array1, $array2, $array3);
//        print_r($array4);
        return $array4;
    }

    function get_recent_post($gender, $lattitude, $longitude, $user_id) {
        $current_time = strtotime("Y-m-d H:i:s");
        $radius = "5"; //'50';google_map_search_radius;gmap_srch_radius
        //$radius = $key  = $this->get_google_search_radius(); 
        $lat_range = $radius / 69.172;
        $lon_range = abs($radius / (cos($longitude) * 69.172));
        $min_lat = $lattitude - $lat_range; # $mile_radius would be 5 for our example, $lat is the fixed lattitude for our zip code
        $max_lat = $lattitude + $lat_range;
        $min_lon = $longitude - $lon_range;
        $max_lon = $longitude + $lon_range;
        if ($gender != 'both') {
            $qry = "select id,price,fileName,( 3959 * acos( cos( radians($lattitude) ) * cos( radians( lat ) ) * cos( radians(`long`) - radians($longitude) ) + sin( radians($lattitude) ) * sin( radians( lat ) ) ) ) AS distance   from trend_post where gender='" . $gender . "' Having (distance <= '$radius' OR distance is null) order by id desc";
        } else {
            $qry = "select id,price,fileName,( 3959 * acos( cos( radians($lattitude) ) * cos( radians( lat ) ) * cos( radians(`long`) - radians($longitude) ) + sin( radians($lattitude) ) * sin( radians( lat ) ) ) ) AS distance   from trend_post  Having (distance <= '$radius' OR distance is null) order by id desc";
        }
//        echo $qry;
//        $qry. "( 3959 * acos( cos( radians($lattitude) ) * cos( radians( lat ) ) * cos( radians( long ) - radians($longitude) ) + sin( radians($lattitude) ) * sin( radians( lat ) ) ) ) AS distance  ";
# here we do a little extra for longitude
        $post = $this->db->query($qry);
        $post_array = $post->result_array();
        $return_array = array();
        $kk = 0;
        foreach ($post_array as $key => $array) {
            $up = $this->db->query("select count(*) as upcount from trend_vote where vote='up' and post_id='" . $array['id'] . "'");
            $upv = $up->row_array();
            $up_vote = $upv['upcount'];
            $down = $this->db->query("select count(*) as upcount from trend_vote where vote='down' and post_id='" . $array['id'] . "'");
            $downc = $down->row_array();
            $down_vote = $downc['upcount'];
            $vote = $up_vote - $down_vote;
            if ($vote > -5) {
                $return_array[$kk] = $array;
                $vote_status = $this->get_vote_status($user_id, $array['id']);
                $return_array[$kk]['vote_status'] = $vote_status;
                $return_array[$kk]['vote_count'] = $vote;
                $rev = $this->db->query("select count(*) as review_count from trend_review where post_id='" . $array['id'] . "'");
                $rev_count = $rev->row_array();
                $return_array[$kk]['review_count'] = $rev_count['review_count'];
                $kk++;
            }
        }
        $array1 = array();
        $array2 = array();
        $array3 = array();
        foreach ($return_array as $return) {
            $posted_time = strtotime($return['created_on']);
            if (($current_time - $posted_time > (3600 * 24)) && ($current_time - $posted_time <= (3600 * 48))) {
                $up1 = $this->db->query("select count(*) as upcount from trend_vote where vote='up' and post_id='" . $return['id'] . "' AND created_on >= now() - INTERVAL 1 DAY");
                $upv1 = $up1->row_array();
                $up_vote1 = $upv1['upcount'];
                $down1 = $this->db->query("select count(*) as upcount from trend_vote where vote='down' and post_id='" . $return['id'] . "' AND created_on >= now() - INTERVAL 1 DAY");
                $downc1 = $down1->row_array();
                $down_vote1 = $downc1['upcount'];
                $vote1 = $up_vote1 - $down_vote1;
                if ($vote1 >= 25) {
                    $array1[] = $return;
                } else {
                    $array3[] = $return;
                }
            } elseif (($current_time - $posted_time > (3600 * 48)) && ($current_time - $posted_time <= (3600 * 72))) {
                $up1 = $this->db->query("select count(*) as upcount from trend_vote where vote='up' and post_id='" . $return['id'] . "' AND created_on >= now() - INTERVAL 2 DAY");
                $upv1 = $up1->row_array();
                $up_vote1 = $upv1['upcount'];
                $down1 = $this->db->query("select count(*) as upcount from trend_vote where vote='down' and post_id='" . $return['id'] . "' AND created_on >= now() - INTERVAL 2 DAY");
                $downc1 = $down1->row_array();
                $down_vote1 = $downc1['upcount'];
                $vote1 = $up_vote1 - $down_vote1;
                if ($vote1 >= 25) {
                    $array1[] = $return;
                } else {
                    $array3[] = $return;
                }
            } elseif ($current_time - $posted_time <= (3600 * 24)) {
                $up1 = $this->db->query("select count(*) as upcount from trend_vote where vote='up' and post_id='" . $return['id'] . "' AND voted_on >= now() - INTERVAL 1 DAY");
                $upv1 = $up1->row_array();
                $up_vote1 = $upv1['upcount'];
                $down1 = $this->db->query("select count(*) as upcount from trend_vote where vote='down' and post_id='" . $return['id'] . "' AND voted_on >= now() - INTERVAL 1 DAY");
                $downc1 = $down1->row_array();
                $down_vote1 = $downc1['upcount'];
                $vote1 = $up_vote1 - $down_vote1;
                if ($vote1 >= 10) {
                    $array1[] = $return;
                } else {
                    $array2[] = $return;
                }
            } else {
                $array3[] = $return;
            }
        }
        $array4 = array_merge($array1, $array2, $array3);
//        print_r($array4);
        return $array4;
    }

    function note_del($arr){
        return $this->db->delete("trendy_notifications",$arr);
    }
            
    function get_trend_post($gender, $lattitude, $longitude, $user_id) {
        $radius = "5"; //'50';google_map_search_radius;gmap_srch_radius
        //$radius = $key  = $this->get_google_search_radius(); 
        $lat_range = $radius / 69.172;
        $lon_range = abs($radius / (cos($longitude) * 69.172));
        $min_lat = $lattitude - $lat_range; # $mile_radius would be 5 for our example, $lat is the fixed lattitude for our zip code
        $max_lat = $lattitude + $lat_range;
        $min_lon = $longitude - $lon_range;
        $max_lon = $longitude + $lon_range;
        if ($gender != 'both') {
            $qry = "select id,price,fileName,( 3959 * acos( cos( radians($lattitude) ) * cos( radians( lat ) ) * cos( radians(`long`) - radians($longitude) ) + sin( radians($lattitude) ) * sin( radians( lat ) ) ) ) AS distance   from trend_post where gender='" . $gender . "' Having (distance <= '$radius' OR distance is null) order by id desc";
        } else {
            $qry = "select id,price,fileName,( 3959 * acos( cos( radians($lattitude) ) * cos( radians( lat ) ) * cos( radians(`long`) - radians($longitude) ) + sin( radians($lattitude) ) * sin( radians( lat ) ) ) ) AS distance   from trend_post  Having (distance <= '$radius' OR distance is null) order by id desc";
        }
//        echo $qry;
//        $qry. "( 3959 * acos( cos( radians($lattitude) ) * cos( radians( lat ) ) * cos( radians( long ) - radians($longitude) ) + sin( radians($lattitude) ) * sin( radians( lat ) ) ) ) AS distance  ";
# here we do a little extra for longitude
        $post = $this->db->query($qry);
        $post_array = $post->result_array();
        $return_array = array();
        $kk = 0;
        foreach ($post_array as $key => $array) {
            $up = $this->db->query("select count(*) as upcount from trend_vote where vote='up' and post_id='" . $array['id'] . "'");
            $upv = $up->row_array();
            $up_vote = $upv['upcount'];
            $down = $this->db->query("select count(*) as upcount from trend_vote where vote='down' and post_id='" . $array['id'] . "'");
            $downc = $down->row_array();
            $down_vote = $downc['upcount'];
            $vote = $up_vote - $down_vote;
            if ($vote >= 50) {
                $return_array[$kk] = $array;
                $vote_status = $this->get_vote_status($user_id, $array['id']);
                $return_array[$kk]['vote_status'] = $vote_status;
                $return_array[$kk]['vote_count'] = $vote;
                $rev = $this->db->query("select count(*) as review_count from trend_review where post_id='" . $array['id'] . "'");
                $rev_count = $rev->row_array();
                $return_array[$kk]['review_count'] = $rev_count['review_count'];
                $kk++;
            }
        }
        return $return_array;
    }

    function check_notification($arr) {
        return $this->db->update("trendy_notifications", array("count_status" => "YES"), $arr);
    }

    function get_notification_unread($user_id) {
        $data = $this->db->query("select count(*) as count from trendy_notifications where count_status='NO' and to_user_id='$user_id'");
        $cou = $data->row_array();
        return $cou['count'];
    }

    function get_user($user_id) {
        $user = $this->db->query("select * from user_master where user_id='" . $user_id . "'");
        return $user->row_array();
    }

    function get_vote_status($user_id, $post_id) {
        $qry = $this->db->query("select * from trend_vote where user_id='$user_id' and post_id='" . $post_id . "' order by id desc limit 1");
        $data = $qry->row_array();
        if (sizeof($data) > 0) {
            $vote_status = $data['vote'];
        } else {
            $vote_status = 'N';
        }
        return $vote_status;
    }

    function get_post_onwer($post_id) {
        $user = $this->db->query("select user_id from trend_post where id='$post_id'");
        $users = $user->row_array();
        return $users['user_id'];
    }

    function get_myNotifications($user_id) {
        $note = $this->db->query("select t1.*, t2.name as user_name ,t2.img_extension, t3.name as from_userName , t4.product_name as product_name from trendy_notifications t1 left join user_master t2 on t1.to_user_id=t2.user_id left join user_master t3 on t1.from_user_id = t3. user_id left join trend_post t4 on t4.id=t1.post_id where t1.to_user_id='$user_id'");
        return $note->result_array();
    }

    function note_read($arr, $cond) {
        return $this->db->update("trendy_notifications", $arr, $cond);
    }

    function post_device_tocken($uid) {
        $data = $this->db->query("select device_id from user_master where user_id='$uid'");
        $re = $data->row_array();
        return $re['device_id'];
    }

    function trend_vote($arr) {
        $vote = $arr['vote'];
        $user_id = $arr['user_id'];
        $post_id = $arr['post_id'];
        $delete_array = array("user_id" => $user_id, "post_id" => $post_id);
        $this->db->delete("trend_vote", $delete_array);
        $array = array("user_id" => $user_id, "post_id" => $post_id, "vote" => $vote);
        $this->db->insert("trend_vote", $array);
        $post_user_id = $this->get_post_onwer($post_id);
        $post_device_tocken = $this->post_device_tocken($post_user_id);
        $message = "your product got a new vote";
        $type = "Like";
        $badge_count = 0;
        $this->sendPushNotification($message, $post_device_tocken, $badge_count, $arr['post_id'], $type);
        $not_array = array("message" => $message, "to_user_id" => $post_user_id, "from_user_id" => $user_id, "inserted_on" => date("Y-m-d H:i:s"), "type" => "vote", "post_id" => $post_id);
        $this->save_notifications($not_array);
        $up = $this->db->query("select count(*) as upcount from trend_vote where vote='up' and post_id='" . $post_id . "'");
        $upv = $up->row_array();
        $up_vote = $upv['upcount'];
        $down = $this->db->query("select count(*) as upcount from trend_vote where vote='down' and post_id='" . $post_id . "'");
        $downc = $down->row_array();
        $down_vote = $downc['upcount'];
        $vote_count = $up_vote - $down_vote;
        return $vote_count;
    }

    function sendPushNotification($message, $deviceToken, $badge_count, $activity_id, $type) {
        $development_mode = 'Y';
        $passphrase = 'newage';
//        $deviceToken="4bac17994d549adb50f2f7933da2d660676ff3bd3d3782128bd4ce2749fb0318";
//        echo $message;
        $ctx = stream_context_create();
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
        $body['aps'] = array(
            'alert' => $message,
            'badge' => intval($badge_count),
            'id' => $activity_id,
            'type' => $type,
            'sound' => 'default'
        );
        $payload = json_encode($body);
        $msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
        $result = fwrite($fp, $msg, strlen($msg));
//        var_dump($result);
        fclose($fp);
    }

    function get_category_recursive() {
        $qry = "select * from category ";
        $array = $this->db->query($qry);
        $result = $array->result_array();
        $return[] = $this->formatTree($result, 0);
        return $return;
    }

    function formatTree($tree, $parent = 0) {
        $tree2 = array();
        foreach ($tree as $i => $item) {
            $p_id = $item['parent_id'];
            if ($p_id == $parent) {
                $tree2[$item['category_id']] = $item;
                $tree2[$item['category_id']]['submenu'] = $this->formatTree($tree, $item['category_id']);
            }
        }
        return $tree2;
    }

    function get_category_recursive1($parent_id) {
        $qry = "select * from category where parent_id='$parent_id'";
        echo $qry;
        $array = $this->db->query($qry);
        $result = $array->result_array();
        return $result;
    }

    function check_post_occasion($post_id, $occasion_id) {
        $qry = $this->db->query("select * from post_occasion_child where post_id='" . $post_id . "' and occasion_id='" . $occasion_id . "'");
        $data = $qry->row_array();
        if (sizeof($data) > 0) {
            return 'Y';
        } else {
            return 'N';
        }
    }

    function save_pdt_user($arr) {
        return $this->db->insert("user_product_child", $arr);
    }

    function get_social($id, $latitude, $longitude) {
        $data = $this->db->query("select T1.*,T2.name as user_name1 from tracking_user T1 left join user_master T2 on T1.following=T2.user_id where T1.followed_by='$id' order by T1.id desc");
        $social = $data->result_array();
        $ret_array = array();
        foreach ($social as $key => $soc) {
            $uid = $soc['following'];
            $user = $this->db->query("select *,id as post_id from trend_post where user_id= $uid");
            $udata = $user->result_array();
            foreach ($udata as $key2 => $uda) {
                $up = $this->db->query("select count(*) as upcount from trend_vote where vote='up' and post_id='" . $uda['id'] . "'");
                $upv = $up->row_array();
                $up_vote = $upv['upcount'];
                $down = $this->db->query("select count(*) as upcount from trend_vote where vote='down' and post_id='" . $uda['id'] . "'");
                $downc = $down->row_array();
                $down_vote = $downc['upcount'];
                $vote = $up_vote - $down_vote;
                $vote_status = $this->get_vote_status($id, $uda['id']);
                $udata[$key2]['vote_status'] = $vote_status;
                $udata[$key2]['vote_count'] = $vote;
                $udata[$key2]['user_name'] = $soc['user_name1'];
                $earthRadius = 6371000;
                $pdt_lat = $uda['lat'];
                $pdt_long = $uda['long'];
                $latFrom = deg2rad($latitude);
                $lonFrom = deg2rad($longitude);
                $latTo = deg2rad($pdt_lat);
                $lonTo = deg2rad($pdt_long);

//                $lonDelta = $lonTo - $lonFrom;
                $latDelta = $latTo - $latFrom;
                $lonDelta = $lonTo - $lonFrom;

                $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
                                        cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));

//                $angle = atan2(sqrt($a), $b);
                $pdt_distance = ($angle * $earthRadius) / 1000;
                if ($pdt_distance < 50) {
                    $udata[$key2]["current_location"] = "Y";
                } else {
                    $udata[$key2]["current_location"] = "N";
                }

                $udata[$key2]["pdt_distance"] = $pdt_distance;
            }
            $social[$key]['post_array'] = $udata;
            $ret_array[] = $udata;
        }
        return $ret_array;
    }

    function del_post($arr) {
        return $this->db->delete("trend_post", $arr);
    }

    function update_ext($arr, $cond) {
        return $this->db->update("user_master", $arr, $cond);
    }

    function get_ext($uid) {
        $q = $this->db->query("select img_extension from user_master where user_id='$uid'");
        $img = $q->row_array();
        return $img['img_extension'];
    }

    function create_brand_byApp($brand) {
        $this->db->insert("brand_master", array("brand" => $brand));
        return $this->db->insert_id();
    }

    function get_brand_full() {
        $data = $this->db->query("select * from brand_master order by id desc");
        return $data->result_array();
    }

    function check_settings_filter($id) {
        $data = $this->db->query("select count(*) as count from filter_settings where user_id='$id'");
        $cou = $data->row_array();
        return $cou['count'];
    }

    function save_filter($arr) {
        return $this->db->insert("filter_settings", $arr);
    }

    function update_filter($arr, $cond) {
        return $this->db->update("filter_settings", $arr, $cond);
    }

    function check_brand($brand) {
        $chk = $this->db->query("select * from brand_master where brand='$brand'");
        $brand1 = $chk->row_array();
        if (sizeof($brand1) > 0) {
            $this->db->query("insert into brand_master(brand) values('$brand') ");
        }
        return TRUE;
    }

    function get_occ_id($id) {
        $data = $this->db->query("select * from occasion_master where occasion_id='" . $id . "'");
        return $data->row_array();
    }

    function get_radius() {
        $data = $this->db->query("select `value` from general_config where field='radius_area'");
        $ret = $data->row_array();
        return $ret['value'];
    }

    function pdt_details($pdt_id, $user_id) {
        $pdt = $this->db->query("select T1.*,T2.name as occasion_name,T3.brand from trend_post T1 left join occasion_master T2 on T1.occasion_id=T2.occasion_id left join brand_master T3 on  T3.id=T1.brand where T1.id='" . $pdt_id . "'");
        $data = $pdt->row_array();
        $pdt_gender = $data['gender'];
        if (strcasecmp("male", $pdt_gender)) {
            $vote_gender = "Female";
        } else {
            $vote_gender = 'male';
        }
        $total_voc = $this->db->query("select count(*) as count from trend_vote");
        $total_voc_1 = $total_voc->row_array();
        $total_voteCount = $total_voc_1['count'];
        $oppo_vote = $this->db->query("select count(*) as count from trend_vote where post_id='$pdt_id' and gender='$vote_gender' and vote='up'");
        $oppo_count_up = $oppo_vote->row_array();
        $oppo_up_count = $oppo_count_up['count'];
        $oppo_vote_down = $this->db->query("select count(*) as count from trend_vote where post_id='$pdt_id' and gender='$vote_gender' and vote='up'");
        $oppo_count_down = $oppo_vote_down->row_array();
        $oppo_down_count = $oppo_count_down['count'];
        $opposite_vote = $oppo_up_count - $oppo_down_count;
        $per_oppovote = ($opposite_vote / $total_voteCount) * 10;
        $data['opposite_vote'] = $per_oppovote;
        $up = $this->db->query("select count(*) as upcount from trend_vote where vote='up' and post_id='$pdt_id'");
        $upv = $up->row_array();
        $up_vote = $upv['upcount'];
        $vote_status = $this->get_vote_status($user_id, $pdt_id);
        $data['vote_status'] = $vote_status;
        $down = $this->db->query("select count(*) as upcount from trend_vote where vote='down' and post_id='$pdt_id'");
        $downc = $down->row_array();
        $down_vote = $downc['upcount'];
        $vote = $up_vote - $down_vote;
        $data['vote_count'] = $vote;
        $rev = $this->db->query("select count(*) as review_count from trend_review where post_id='$pdt_id'");
        $rev_count = $rev->row_array();
        $data['review_count'] = $rev_count['review_count'];
        $rev1 = $this->db->query("select T1.*,T2.name as username  from trend_review T1 left join user_master T2 on T1.user_id= T2.user_id where post_id='$pdt_id' order by review_id desc limit 3 ");
        $rev_details = $rev1->result_array();
        foreach ($rev_details as $revKey => $revd) {
            $revs_id = $revd['review_id'];
            $revStatus = $this->db->query("select count(*) as count_s from review_rate where user_id= '$user_id' and review_id='$revs_id'");
            $statC = $revStatus->row_array();
            if ($statC['count_s'] > 0) {
                $rev_details[$revKey]['vote_status'] = "YES";
            } else {
                $rev_details[$revKey]['vote_status'] = "NO";
            }
            $revCount = $this->db->query("select count(*) as count_s1 from review_rate where  review_id='$revs_id'");
            $statCou = $revCount->row_array();
            $rev_details[$revKey]['reviewRateCount'] = $statCou['count_s1'];
        }
        $data['reviews'] = $rev_details;
        $user_data = $this->db->query("select * from user_master where user_id='" . $data['user_id'] . "'");
        $user = $user_data->row_array();
        $data['posted_by'] = $user['name'];
        $posted_on = $data['created_on'];
        $ago = $this->currentStatus($posted_on);
        $data['posted_on'] = $ago;
        $pdt_det = $this->db->query("select * from user_product_child where user_id='" . $user_id . "' and product_id='" . $pdt_id . "' ");
        $save_status = $pdt_det->row_array();
        $save_st = 'N';
        if (sizeof($save_status) > 0) {
            $save_st = 'Y';
        } else {
            $save_st = 'N';
        }
        $data['save_status'] = $save_st;
        return $data;
    }

    function check_track($id, $user_id) {
        $cio = $this->db->query("select count(*) as count from tracking_user where following='$id' and followed_by ='$user_id'");
        $coun = $cio->row_array();
//        echo $this->db->last_query();
        $count = $coun['count'];
        if ($count > 0) {
            $cio1 = $this->db->query("select * from tracking_user where following='$id' and followed_by ='$user_id'");
            $coun1 = $cio1->row_array();
            $re = $coun1['request_status'];
        } else {
            $re = "Not Tracking";
        }
        return $re;
    }

    function del_rev($array) {
        return $this->db->delete("trend_review", $array);
    }

    function update_pdt_occasion($array, $condition) {
        $this->db->update("trend_post", $array, $condition);
        return TRUE;
    }

    function get_search($key_word, $user_id) {
        $return = $this->db->query("select * from user_master where name LIKE '%$key_word%' ");
        $data = $return->result_array();
        foreach ($data as $key => $user) {
            $tra = $this->db->query("select count(*) as count from tracking_user where following='" . $user['user_id'] . "' and followed_by='$user_id'");
            $tracking = $tra->row_array();
            $tracking_count = $tracking['count'];
            if ($tracking_count > 0) {
                $data[$key]['tracking_status'] = "Yes";
            } else {
                $data[$key]['tracking_status'] = "No";
            }
        }
        return $data;
    }

    function save_report($arr) {
        return $this->db->insert("report_master", $arr);
    }

    function get_product_occasion($id, $gender) {
        if ($gender != 'both') {
            $data = $this->db->query("select * from trend_post where occasion_id='" . $id . "' and gender='$gender'");
        } else {
            $data = $this->db->query("select * from trend_post where occasion_id='$id'");
        }
        return $data->result_array();
    }

    function suggested_items($id, $oc_id, $gender) {
        if ($gender != 'both') {
            $data = $this->db->query("select * from trend_post where id!='$id' and occasion_id='$oc_id' and gender='$gender'");
        } else {
            $data = $this->db->query("select * from trend_post where id!='$id' and occasion_id='$oc_id'");
        }
        return $data->result_array();
    }

    function get_settings($user_id) {
        $data = $this->db->query("select * from user_settings where user_id='$user_id'");
        $result = $data->row_array();
        if ($result['gender'] != '') {
            $gender = $result['gender'];
        } else {
            $gender = 'both';
        }
        return $gender;
    }

    function get_filter($user_id) {
        $data = $this->db->query("select T1.* from filter_settings T1 where T1.user_id='$user_id'");
        $return = $data->row_array();
        if (sizeof($return) <= 0) {
            $return['pop_start'] = 0;
            $return['pop_end'] = 0;
            $return['price_start'] = 0;
            $return['price_end'] = 0;
        }
        $data_type = $this->db->query("select T1.*,T2.name as type_name from filter_type_child T1 left join category T2 on T1.type_id=T2.category_id where T1.user_id='$user_id'");
        $type = $data_type->result_array();
        $return['type_array'] = $type;
        $data_brand = $this->db->query("select T1.*,T2.brand as brand_name from filter_brand_child T1 left join brand_master T2 on T1.brand_id=T2.id where T1.user_id='$user_id'");
        $brand = $data_brand->result_array();
        $return['brand_array'] = $brand;

        return $return;
    }

    function pop_price_status() {
        $price_max = $this->db->query("select price from trend_post order by price desc limit 1");
        $primax = $price_max->row_array();
        $return['max_price'] = $primax['price'];
        $price_max1 = $this->db->query("select price from trend_post order by price asc limit 1");
        $primax1 = $price_max1->row_array();
        $return['min_price'] = $primax1['price'];
        $pop_mi = $this->db->query("SELECT *,(maxcount-num) as maxCountss from (SELECT COUNT(*) as maxcount, T1.post_id,T1.vote,(SELECT COUNT(*) as maxcount FROM trend_vote WHERE vote='up' AND post_id=T1.post_id ) as num 
FROM trend_vote T1 WHERE vote='down' GROUP BY post_id,vote) as max_counts ORDER BY maxCountss ASC limit 1 ");
        $popMin = $pop_mi->row_array();
        $return['pop_min'] = $popMin['maxCountss'];
        $pop = $this->db->query("SELECT *,(maxcount-num) as maxCountss from (SELECT COUNT(*) as maxcount, T1.post_id,T1.vote,(SELECT COUNT(*) as maxcount FROM trend_vote WHERE vote='down' AND post_id=T1.post_id ) as num 
FROM trend_vote T1 WHERE vote='up' GROUP BY post_id,vote) as max_counts ORDER BY maxCountss DESC limit 1");
        $pop_array = $pop->row_array();
        $return['pop_max'] = $pop_array['maxCountss'];
        return $return;
    }

    function save_filter_child($user_id, $type_arr, $brand_arr) {
        $this->db->delete("filter_brand_child", array("user_id" => $user_id));
        $this->db->delete("filter_type_child", array("user_id" => $user_id));
        foreach ($type_arr as $type) {
            $this->db->insert("filter_type_child", array("user_id" => $user_id, "type_id" => $type));
        }
        foreach ($brand_arr as $brand) {
            $this->db->insert("filter_brand_child", array("user_id" => $user_id, "brand_id" => $brand));
        }
        return TRUE;
    }

    function currentStatus($date) {
        $datas = $this->db->query("select NOW() as time_now");
        $dat = $datas->row_array();
        $time = strtotime($dat['time_now']);
        $granularity = 1;
        $date = strtotime($date);
        $difference = $time - $date;
        $periods = array('decade' => 315360000,
            'year' => 31536000,
            'month' => 2628000,
            'week' => 604800,
            'day' => 86400,
            'hour' => 3600,
            'minute' => 60,
            'second' => 1);

        if ($difference < 15) { // less than 5 seconds ago, let's say "just now"
            $retval = "just now";
        } else {
            foreach ($periods as $key => $value) {
                if ($difference >= $value) {
                    $time = floor($difference / $value);
                    $difference %= $value;
                    $retval .= ($retval ? ' ' : '') . $time . ' ';
                    $retval .= (($time > 1) ? $key . 's' : $key);
                    $granularity--;
                }
                if ($granularity == '0') {
                    break;
                }
            }


            $retval = $retval . ' ago';
        }
        return $retval;
    }

    function save_to_occasion($array) {
        return $this->db->insert("post_occasion_child", $array);
    }

    function get_occasion() {
        $data = $this->db->query("select occasion_id,name,image from occasion_master where status='Y' and is_block='N' order by occasion_id desc");
        return $data->result_array();
    }

    function checkEmailExistCurrent($email, $user_id) {
        $sql = "select  user_email from user_master where user_email = '$email' and user_type != '1' and user_id !='" . $user_id . "'  ";
        $query = $this->db->query($sql);
        $result = $query->row_array();
        if ($result['user_email'])
            return true;
        else
            return false;
    }

#Update User Details

    function updateProfile($arr) {
        $user_id = $arr['user_id'];
        $data = array(
            'user_type' => 2,
            'name' => ucfirst($arr['name'])
        );
        $this->db->where('user_id', $user_id);
        $this->db->update('user_master', $data);
        return true;
    }

    function get_tracking_list($id, $uid) {
        $data = $this->db->query("select * from tracking_user where followed_by=$id");
        $return = $data->result_array();
        foreach ($return as $key => $ret) {
            $fid = $ret['following'];
            $track = $this->db->query("select * from tracking_user where following='$fid' and followed_by='$uid'");
            $track_status = $track->row_array();
            if (sizeof($track_status) > 0) {
                $return[$key]['request_status'] = $track_status['request_status'];
            } else {
                $return[$key]['request_status'] = "Not Tracking";
            }
            $user = $this->db->query("SELECT T1.* , T2.profile FROM user_master T1 LEFT JOIN user_settings T2 ON T1.user_id=T2.user_id where T1.user_id='$fid'");
            $user_data = $user->row_array();
            if ($user_data['profile'] == '') {
                $user_data['profile'] = "public";
            }
            $return[$key]['following_user'] = $user_data;
        }
        return $return;
    }

    function apprv_track($arr, $con) {
        return $this->db->update("tracking_user", $arr, $con);
    }

    function save_notifications($arr) {
        return $this->db->insert("trendy_notifications", $arr);
    }

    function get_trackers_list($id, $uid) {
        $data = $this->db->query("select * from tracking_user where following=$id");
        $return = $data->result_array();
        foreach ($return as $key => $ret) {
            $fid = $ret['followed_by'];
            $track = $this->db->query("select * from tracking_user where following='$fid' and followed_by='$uid'");
            $track_status = $track->row_array();
            if (sizeof($track_status) > 0) {
                $return[$key]['request_status'] = $track_status['request_status'];
            } else {
                $return[$key]['request_status'] = "Not Tracking";
            }
            $user = $this->db->query("SELECT T1.* , T2.profile FROM user_master T1 LEFT JOIN user_settings T2 ON T1.user_id=T2.user_id where T1.user_id='$fid'");
            $user_data = $user->row_array();
            if ($user_data['profile'] == '') {
                $user_data['profile'] = "public";
            }
            $return[$key]['following_user'] = $user_data;
        }
        return $return;
    }

    function save_track($arr) {
        return $this->db->insert("tracking_user", $arr);
    }

    function get_settings1($id) {
        $data = $this->db->query("select * from user_settings where user_id='$id'");
        return $data->row_array();
    }

    function checkUserpwdExist($pwd, $uid) {
        $sql = "select password from user_master where password = '" . md5($pwd) . "' and user_id='" . $uid . "'";
        $query = $this->db->query($sql);
        $result = $query->row_array();
        if ($result['password'])
            return true;
        else
            return false;
    }

    function update_coach($array, $cond) {
        return $this->db->update("user_master", $array, $cond);
    }

    function getPackage() {
        $data = $this->db->query("select * from package_master where active='Y' and paid='Y'");
        $result = $data->result_array();
        return $result;
    }

    function getUserExpiration($user_id) {
        $data = $this->db->query("select * from user_subscription_master where NOW()>=subscr_date and NOW()<=next_subscr_date  and user_id=" . $user_id . " and cancelled='N'");
        $result = $data->result_array();
        return $result;
    }

    function update_pwd($pwd, $uid) {
        return $this->db->update("user_master", array("password" => md5($pwd)), array("user_id" => $uid));
    }

    function get_ext1($user_id) {
        $qry = $this->db->query("select img_extension from user_master where user_id='$user_id'");
        $ext = $qry->row_array();
        return $ext['img_extension'];
    }

    function check_setiings_user($id) {
        $data = $this->db->query("select * from user_settings where user_id='$id'");
        $return = $data->row_array();
        if (sizeof($return) > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function up_settings($ar, $co) {
        return $this->db->update("user_settings", $ar, $co);
    }

    function save_settings($arr) {
        return $this->db->insert("user_settings", $arr);
    }

    function getUserPackage($user_id) {
        $qry = $this->db->query("select img_extension from user_master where user_id='$user_id'");
        $ext = $qry->row_array();
        return $ext['img_extension'];
    }

    function updateQuickBlox($arr) {
        $user_id = $arr['user_id'];
        $data = array(
            'quickblox_id' => $arr['quickblox_id']
        );
        $this->db->where('user_id', $user_id);
        $this->db->update('user_master', $data);
        return true;
    }

#User registration function

    function Registration($arr) {
        $password = md5($arr['password']);
        $data = array(
            'user_type' => 2,
            'name' => ucfirst($arr['name']),
            'user_email' => $arr['user_email'],
            'password' => $password,
            'register_date' => date("Y-m-d H:i:s"),
            'username' => $arr['username'],
            'facebook_id' => $arr['fb_unique_id'],
            'device_id' => $arr['device_id'],
            'gender' => $arr['user_gender'],
            'email_verified' => 'Y',
            'active' => 'Y'
        );

        $this->db->insert('user_master', $data);
        $user_id = $this->db->insert_id();
        $array = array("user_id" => $user_id, "gender" => $arr['user_gender']);
        $this->db->insert("user_settings", $array);
        $arr = array("user_id" => $user_id);
        $this->db->insert("filter_settings", $arr);
        return $user_id;
    }

#function to register common profile data

    function getUserFullDetails($id) {
        //   echo $id."<br/>";
        $qry = "select T1.* from user_master T1 
						
						where T1.user_id='$id'";
//        echo $qry;
        $res = $this->db->query($qry);
        //   echo $this->db->last_query();
        $row = $res->row_array();
        return $row;
    }

#function To Update Member Image Extension

    function updateMemberImage($img_extension, $user_id) {
        $data = array(
            'img_extension' => $img_extension
        );

        $this->db->where('user_id', $user_id);
        $id = $this->db->update('user_master', $data);
        return true;
    }

#function To Update Email verified

    function emailVerify($user_id) {
        $data = array(
            'email_verified' => 'Y'
        );

        $this->db->where('user_id', $user_id);
        $id = $this->db->update('user_master', $data);
        return true;
    }

# function login 

    function userLogin($arr) {
        $username = $arr['username'];
        if ($arr['automatic'] == 'Y')
            $password = $arr['password'];
        else
            $password = md5($arr['password']);
        $sql = "select * from user_master where username ='$username' and password ='$password' ";


        $query = $this->db->query($sql);
        $result = $query->row_array();

        if ($result) {

            $msg = 'Login success';
        } else {
            $msg = 'Invalid username or password';
        }
        return $msg;
    }

# function get user details using username and password

    function getUserDetailsByUsernameandPassword($arr) {
        $username = $arr['username'];

        if ($arr['automatic'] == 'Y')
            $password = $arr['password'];
        else
            $password = md5($arr['password']);

        $sql = "select * from user_master where username = '$username' and password = '$password' ";

        $query = $this->db->query($sql);
        $result = $query->row_array();
        return $result;
    }

# function to check whether the username already created by the user

    function checkUserExist($username, $user_id = '') {
        $sql = "select username from user_master where username = '$username' ";
        if ($user_id)
            $sql .= " and user_id != $user_id ";

        $query = $this->db->query($sql);
        $result = $query->row_array();
        if ($result['username'])
            return true;
        else
            return false;
    }

# function to change user password 

    function changePassword($password, $user_id) {
        $data = array('password' => $password);
        $this->db->where('user_id', $user_id);
        $id = $this->db->update('user_master', $data);
    }

# function to check whether the fb account already created by the user

    function checkFacebookExist($fb_unique_id, $user_id = '') {
        $sql = "select facebook_id from user_master where facebook_id = '$fb_unique_id'  ";
        if ($user_id)
            $sql .= " and user_id = $user_id ";

        $query = $this->db->query($sql);
        $result = $query->row_array();
//        echo $result['facebook_id'];
        if ($result['facebook_id'])
            return true;
        else
            return false;
    }

    function getUserFullDetailsByFB($fb_unique_id) {
        $sql = "SELECT * FROM user_master where facebook_id='$fb_unique_id' ";
        $query = $this->db->query($sql);
        $result = $query->row_array();
        return $result;
    }

# function to check whether the fb account already created by the user

    function checkGooglePlusExist($google_id, $user_id = '') {
        $sql = "select  google_id from user_master where google_id = '$google_id'  ";
        if ($user_id)
            $sql .= " and user_id = $user_id ";

        $query = $this->db->query($sql);
        $result = $query->row_array();
        if ($result['google_id'])
            return true;
        else
            return false;
    }

    function getBadgeCount($device_tocken) {

        $badgesql = "update user_master set badge_count=badge_count+1 where device_id ='$device_tocken'";
        $this->db->query($badgesql);
        $sql = "SELECT badge_count FROM user_master where device_id='$device_tocken' ";
        $query = $this->db->query($sql);
        $result = $query->row_array();
        return $result;
    }

    function getPreferences() {
        $sql = "select *  from  preferences where 1";
        $query = $this->db->query($sql);
        $result = $query->row_array();
        return $result;
    }

    function updateSessionID($dev_id, $session_id) {
        $data = array(
            'device_id' => $dev_id,
            'session_key' => $session_id,
        );
        $this->db->insert('session_master', $data);
        return $this->db->insert_id();
    }

# function to check whether the facebook id is already registered

    function validateFacebookId($fb_id) {
        $sql = "SELECT * FROM user_master WHERE facebook_id  = '$fb_id' ";
        $query = $this->db->query($sql);
        $result = $query->row_array();
        return $result;
    }

    function check_active_user($fb_id) {
        $sql = "SELECT * FROM user_master WHERE facebook_id  = '$fb_id' ";
        $query = $this->db->query($sql);
        $result = $query->row_array();
        $active = $result['active'];
        if ($active == 'Y') {
            $res = 'Y';
        } else {
            $res = 'N';
        }
        return $res;
    }

# function to check whether the googlre plus id is already registered

    function validateGoogleId($g_id) {
        $sql = "SELECT * FROM user_master WHERE google_id  = '$g_id' ";
        $query = $this->db->query($sql);
        $result = $query->row_array();
        return $result;
    }

    function checkSessionID($ses_id) {
        $sql = "select id from session_master  where session_key = '$ses_id'";
        $query = $this->db->query($sql);
        $result = $query->row_array();
        if ($result['id'])
            return 'Y';
        else
            return 'N';
    }

    function deleteSessionEntry($dev_id) {
        $sql = "delete from session_master where device_id ='$dev_id'";
        $this->db->query($sql);
        return true;
    }

    function clearBadgeCount($dev_id) {
        $sql = "update user_master set badge_count ='' where device_id ='$dev_id'";
        $this->db->query($sql);
        return true;
    }

//ed3a5c0d07129e21057c9ac51d45abf5b823b3b589193084e0162cc4f303936b
    function updateBadgeCount($dev_id) {
        $sql = "update user_master set badge_count=badge_count+1 where device_id ='$dev_id'";
        $this->db->query($sql);
        return true;
    }

### function to send push notification

    function get_emailByid($id) {
//        echo $id;
        $this->db->where('email_id', $id);
        $result = $this->db->get('email_config');
//        echo $this->db->last_query();
//        print_r($result);
//        exit;
        return $result->row_array();
    }

    function getClient_answered_qns($user_id) {
        /* $sql = "select T1.question_id,T2.profile_question from profile_answers T1
          left join config_questions T2
          on T1.question_id = T2.profile_qn_id
          where T1.user_id ='$user_id' and T1.question_id not in(10,11) group by  T1.question_id "; */
        $sql = "select T1.profile_qn_id as question_id,T1.qn_coach_view as profile_question  from  config_questions T1
					
			where 1 and T1.profile_qn_id not in(10,11) group by  T1.profile_qn_id ";

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function getQuestion($qn_id) {
        $sql = "select profile_question from config_questions where profile_qn_id ='$qn_id'";
        $query = $this->db->query($sql);
        $records = $query->row_array();
        return $records['profile_question'];
    }

## function update user device tocken

    function updateUserDeviceTocken($user_id) {
        $this->db->query("update user_master set device_id = '' where user_id = '$user_id' ");
        //	$this->db->query("update user_master set device_id = '$device_tocken' where user_id = $user_id ");	
    }

    function updateDevice_Token($device_toekn, $user_id) {
        $this->db->query("update user_master set device_id = '$device_toekn' where user_id = '$user_id' ");
    }

    function calculate_age($user_id) {
        $sql = "SELECT TIMESTAMPDIFF( YEAR, user_dob, CURDATE( ) ) AS age from user_profile_com where user_id ='$user_id'";
        $query = $this->db->query($sql);
        $result = $query->row_array();
        return $result['age'];
    }

# function to check the email address is registerd in the app

    function checkUserEmail($arr) {
        $email = $arr['useremail'];
//        echo $email;
        $sql = "select user_id from user_master where user_email = '$email' ";
        $query = $this->db->query($sql);
        $result = $query->row_array();
        if ($result)
            return "Y";
        else
            return "N";
    }

#function to generate random Password

    function randomPassword() {
        $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }

# function to get userID from email address

    function getUserIDFromEmail($email) {
        $sql = "select user_id from user_master where user_email = '$email' ";
        $query = $this->db->query($sql);
        $result = $query->row_array();
        return $result['user_id'];
    }

# function to update user password

    function updateUserPassword($password, $user_id) {
        $data = array('password' => md5($password));
        $this->db->where('user_id', $user_id);
        $id = $this->db->update('user_master', $data);

        return true;
    }

    # function to get admin email address

    public
            function getAdminEmail() {
        $sql = "SELECT *  FROM user_master WHERE `user_type` = '1'";
        $query = $this->db->query($sql);
        return $query->row_array();
    }

    function generate_random_string() {

        $alphabet = "ABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string*/
    }

}

?>	