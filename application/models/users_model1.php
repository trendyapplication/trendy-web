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

    function get_full_reviews($id) {
        $data = $this->db->query("select * from trend_review where post_id='" . $id . "' order by review_id desc");
        return $data->result_array();
    }

    function save_review($array) {
        print_r($array);
        $this->db->insert("trend_review", $array);
        return $this->db->insert_id();
    }

    function get_recent_post($gender, $lattitude, $longitude, $user_id) {
        $radius = "5"; //'50';google_map_search_radius;gmap_srch_radius
        //$radius = $key  = $this->get_google_search_radius(); 
        $lat_range = $radius / 69.172;
        $lon_range = abs($radius / (cos($longitude) * 69.172));
        $min_lat = $lattitude - $lat_range; # $mile_radius would be 5 for our example, $lat is the fixed lattitude for our zip code
        $max_lat = $lattitude + $lat_range;
        $min_lon = $longitude - $lon_range;
        $max_lon = $longitude + $lon_range;
        $qry = "select id,price,fileName,( 3959 * acos( cos( radians($lattitude) ) * cos( radians( lat ) ) * cos( radians(`long`) - radians($longitude) ) + sin( radians($lattitude) ) * sin( radians( lat ) ) ) ) AS distance   from trend_post where gender='" . $gender . "' Having (distance <= '$radius' OR distance is null) order by id desc";
//        echo $qry;
//        $qry. "( 3959 * acos( cos( radians($lattitude) ) * cos( radians( lat ) ) * cos( radians( long ) - radians($longitude) ) + sin( radians($lattitude) ) * sin( radians( lat ) ) ) ) AS distance  ";
# here we do a little extra for longitude
        $post = $this->db->query($qry);
        $post_array = $post->result_array();
        foreach ($post_array as $key => $array) {
            $up = $this->db->query("select count(*) as upcount from trend_vote where vote='up' and post_id='" . $array['id'] . "'");
            $upv = $up->row_array();
            $up_vote = $upv['upcount'];
//            echo $up_vote;
//           exit;
            $vote_status = $this->get_vote_status($user_id, $array['id']);
            $post_array[$key]['vote_status'] = $vote_status;
            $down = $this->db->query("select count(*) as upcount from trend_vote where vote='down' and post_id='" . $array['id'] . "'");
            $downc = $down->row_array();
            $down_vote = $downc['upcount'];
//           echo 
            $vote = $up_vote - $down_vote;
            $post_array[$key]['vote_count'] = $vote;
            $rev = $this->db->query("select count(*) as review_count from trend_review where post_id='" . $array['id'] . "'");
            $rev_count = $rev->row_array();
            $post_array[$key]['review_count'] = $rev_count['review_count'];
        }
        return $post_array;
    }

    function get_user($user_id) {
        $user = $this->db->query("select * from user_master where user_id='" . $user_id . "'");
        return $user->row_array();
    }

    function get_vote_status($user_id, $post_id) {
        $qry = $this->db->query("select * from trend_vote where user_id='$user_id' and post_id='" . $post_id . "' order by id desc limit 1");

        $data = $qry->row_array();
//        echo $this->db->last_query();
        if (sizeof($data) > 0) {
            $vote_status = $data['vote'];
        } else {
            $vote_status = 'N';
        }
        return $vote_status;
    }

    function trend_vote($arr) {
        $vote = $arr['vote'];
        $user_id = $arr['user_id'];
        $post_id = $arr['post_id'];
        $delete_array = array("user_id" => $user_id, "post_id" => $post_id);
        $this->db->delete("trend_vote", $delete_array);
        $array = array("user_id" => $user_id, "post_id" => $post_id, "vote" => $vote);
        $this->db->insert("trend_vote", $array);
        $up = $this->db->query("select count(*) as upcount from trend_vote where vote='up' and post_id='" . $post_id . "'");
        $upv = $up->row_array();
        $up_vote = $upv['upcount'];
        $down = $this->db->query("select count(*) as upcount from trend_vote where vote='down' and post_id='" . $post_id . "'");
        $downc = $down->row_array();
        $down_vote = $downc['upcount'];
        $vote_count = $up_vote - $down_vote;
        return $vote_count;
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

    function get_occ_id($id) {
        $data = $this->db->query("select * from occasion_master where occasion_id='" . $id . "'");
        return $data->row_array();
    }

    function pdt_details($pdt_id, $user_id) {
        $pdt = $this->db->query("select T1.*,T2.name as occasion_name from trend_post T1 left join occasion_master T2 on T1.occasion_id=T2.occasion_id where T1.id='" . $pdt_id . "'");
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
        $data['reviews'] = $rev_details;
        $user_data = $this->db->query("select * from user_master where user_id='" . $data['user_id'] . "'");
        $user = $user_data->row_array();
        $data['posted_by'] = $user['name'];
        $posted_on = $data['created_on'];
        $ago = $this->currentStatus($posted_on);
        $data['posted_on'] = $ago;
        return $data;
    }

    function suggested_items($id, $oc_id) {
        $data = $this->db->query("select * from trend_post where id!='$id' and occasion_id='$oc_id'");
        return $data->result_array();
    }

    function currentStatus($date) {
        $granularity = 1;
        $date = strtotime($date);
        $difference = time() - $date;
        $periods = array('decade' => 315360000,
            'year' => 31536000,
            'month' => 2628000,
            'week' => 604800,
            'day' => 86400,
            'hour' => 3600,
            'minute' => 60,
            'second' => 1);

        if ($difference < 5) { // less than 5 seconds ago, let's say "just now"
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

    function getPackageUserStatus($user_id) {
        $qry = $this->db->query("select subscr_id from user_subscription_master where user_id='" . $user_id . "' and txn_id !=''");
        return $qry->result_array();
    }

    function checkUserpwdExist($pwd, $uid) {
        $sql = "select password from user_master where password = '" . md5($pwd) . "' and user_id='" . $uid . "'";
//        if ($user_id)
//            $sql .= " and user_id != $user_id ";

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

    function get_ext($user_id) {
        $qry = $this->db->query("select img_extension from user_master where user_id='$user_id'");
        $ext = $qry->row_array();
        return $ext['img_extension'];
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

    #function to update common profile Info

    function update_Common_Profile($arr) {
        $user_id = $arr['user_id'];
        $data = array(
            'user_dob' => date("Y-m-d", strtotime($arr['user_dob'])),
            'user_phone' => $arr['user_phone'],
        );
        $this->db->where('user_id', $user_id);
        $this->db->update('user_profile_com', $data);
        return true;
    }

    #function to get data from packages

    function getPackageDetails7() {
        $data = $this->db->query("select * from package_master where active='Y' and paid='Y'");
        $result = $data->result_array();
        return $result;
    }

    #function to update common profile advanced

    function update_profile_advanced($arr) {
        $user_id = $arr['user_id'];

        $sql = "SELECT * FROM  user_profile_adv WHERE user_id  = '$user_id' ";
        $query = $this->db->query($sql);
        $result = $query->row_array();
        $data = array(
            'relationship_status' => $arr['relationship_status'],
            'have_kids' => $arr['have_kids'],
            'occupation' => $arr['occupation'],
            'travel_often_work' => $arr['travel_often_work'],
            'height' => $arr['height'],
            'weight' => $arr['weight'],
            'health_concerns' => $arr['health_concerns'],
            'food_allergies' => $arr['food_allergies'],
            'do_you_excercise' => $arr['do_you_excercise']
        );

        if ($result['id']) {
            $this->db->where('user_id', $user_id);
            $this->db->update('user_profile_adv', $data);
        } else {
            $this->db->insert('user_profile_adv', $data);
        }
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
            'device_id' => $arr['devicetoken'],
            'gender' => $arr['user_gender'],
            'email_verified' => 'Y',
            'active' => 'Y'
        );

        $this->db->insert('user_master', $data);

        return $this->db->insert_id();
    }

    #subscrpion insert function

    function insertSubscription($arr) {

        $data = array(
            'txn_id' => $arr['txn_id'],
            'user_id' => $arr['user_id'],
            'subscr_date' => $arr['subscr_date'],
            'next_subscr_date' => $arr['next_subscr_date'],
            'amount' => $arr['amount'],
            'package_id' => $arr['package_id']
        );

        $this->db->insert('user_subscription_master', $data);

        return $this->db->insert_id();
    }

    #subscrpion insert function

    function insertAssigns($arr) {

        $data = array(
            'subscription_id' => $arr['subscription_id'],
            'user_id' => $arr['user_id'],
            'assign_date' => $arr['assign_date'],
            'child_id' => $arr['child_id'],
            'package_start_date' => $arr['pack_start_date'],
            'pack_end_date' => $arr['pack_end_date'],
        );

        $this->db->insert('admin_assign_master', $data);

        return $this->db->insert_id();
    }

    #function to register common profile data

    function register_common_profile_data($arr, $user_id) {
        if ($arr['user_dob'] != '') {
            $data = array(
                'user_dob' => date("Y-m-d", strtotime($arr['user_dob'])),
                'user_gender' => $arr['user_gender'],
                'user_phone' => $arr['user_phone'],
                'user_id' => $user_id
            );
        } else {
            $data = array(
                'user_dob' => '',
                'user_gender' => $arr['user_gender'],
                'user_phone' => $arr['user_phone'],
                'user_id' => $user_id
            );
        }


        $this->db->insert('user_profile_com', $data);
        return $this->db->insert_id();
    }

    public function getCoachDetails($id) {
        $qry = "select T1.quickblox_id,T1.* from user_master T1  where T1.user_id='$id'";
        $res = $this->db->query($qry);
        $row = $res->row_array();
        return $row;
    }

    public function get_assignedPack($id, $packid) {
        $dat = $this->db->query("select T1.*,T2.title,T3.*,(SELECT `code` FROM promocode_master WHERE id=T3.code_id) as `code`  from admin_assign_master T1 
left join  package_master T2 on T1.subscription_id=T2.package_id 
LEFT JOIN promo_user_child T3 ON T1.child_id=T3.id
where T1.user_id='" . $id . "'");

        return $dat->result_array();
    }

    public function updateAssignSubscription($user_id, $package_id, $txn_id) {
        $query = "UPDATE admin_assign_master AS target INNER JOIN (SELECT ta.assign_id FROM admin_assign_master AS ta where user_id='" . $user_id . "' and subscription_id='" . $package_id . "'
        ORDER BY ta.assign_id DESC LIMIT 1) AS source ON source.assign_id = target.assign_id SET paid = 'Y',paid_date='" . date('Y-m-d H:i:s') . "',txn_id='" . $txn_id . "'";
        return $this->db->query($query);
    }

    public function check_prvs($user_id) {
        $qry = "select txn_id from admin_assign_master where user_id='" . $user_id . "'";
        $data = $this->db->query($qry);
        $result = $data->row_array();
        $flag = "N";
        foreach ($result as $re) {
            if ($re['txn_id'] != '') {
                $flag = "Y";
            }
        }
        return $flag;
    }

    public function getUserFullDetails($id) {
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

    function getUserFullDetailsByGooglePlus($google_id) {
        $sql = "SELECT * FROM user_master where google_id='$google_id' ";
        $query = $this->db->query($sql);
        $result = $query->row_array();
        return $result;
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

    # function to get questions

    function getQuestions() {
        $sql = "select * from config_questions where 1 ORDER BY profile_qn_id";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    #function to get options

    function getOptions($question_id) {
        $sql = "select * from config_answers where question_id='$question_id'";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    #function to save ansers by user

    function saveMyAnswer($user_id, $answer_id, $question_id) {

        $arr = array(
            'user_id' => $user_id,
            'question_id' => $question_id,
            'answer_id' => $answer_id,
        );

        $id = $this->db->insert('profile_answers', $arr);
        $id = $this->db->insert_id();
        return $id;
    }

    function update_profile_step_1($arr) {
        $user_id = $arr['user_id'];
        $data = array(
            'user_email' => $arr['user_email'],
            'coach_instructions' => $arr['coach_instructions'],
            'name' => ucfirst($arr['name']),
            'about_me' => $arr['about_me'],
            'location' => $arr['location'],
            'specialities' => $arr['specialities'],
            'interests' => $arr['interests'],
        );
        $this->db->where('user_id', $user_id);
        $this->db->update('user_master', $data);

        $data1 = array(
            'user_phone' => $arr['user_phone'],
            'user_dob' => $arr['user_dob'],
            'user_city' => $arr['user_city'],
            'user_state' => $arr['user_state'],
            'user_country' => $arr['user_country']
        );

        $this->db->where('user_id', $user_id);
        $this->db->update('user_profile_com', $data1);
        return true;
    }

    function getCountryList() {
        $sql = "select T1.* from config_country T1 where T1.country_id IN(select country_id from config_state)order by T1.country_name asc";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function getStateListByCountryID($country_id) {
        $sql = "select state_id,state_code as state_name ,country_id from config_state where country_id='$country_id' order by state_name asc ";
        $query = $this->db->query($sql);
        $state = $query->result_array();
        foreach ($state as $key => $sta) {
            if ($sta['state_name'] == '') {
                $qry = $this->db->query("select state_name from config_state where state_id='" . $sta['state_id'] . "'");
                $stat = $qry->row_array();
                $state_name = $stat['state_name'];
                $state[$key]['state_name'] = $state_name;
            }
        }
        return $state;
    }

    function getStateListByCountryID2($country_id) {
        $sql = "select state_id, state_name ,country_id from config_state where country_id='$country_id' order by state_name asc ";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function getCountry($user_state) {
        $qry = $this->db->query("select country_id from config_state where state_id='" . $user_state . "'");
        $cou = $qry->row_array();
        $country = $cou['country_id'];
        return $country;
    }

    function update_profile_step_2($arr) {
        $user_id = $arr['user_id'];
        $country = $this->getCountry($arr['user_state']);
        $data = array(
            'user_city' => $arr['user_city'],
            'user_state' => $arr['user_state'],
            'user_country' => $country
        );
        $this->db->where('user_id', $user_id);
        $this->db->update('user_profile_com', $data);


        $data_1 = array(
            'relationship_status' => $arr['relationship_status'],
            'have_kids' => $arr['have_kids'],
            'occupation' => $arr['occupation'],
            'travel_often_work' => $arr['travel_often_work'],
            'user_id' => $user_id
        );

        $sql = "select user_adv_profile_id  from  user_profile_adv  where user_id = '$user_id'";
        $query = $this->db->query($sql);
        $result = $query->row_array();
        if ($result['user_adv_profile_id']) {
            $this->db->where('user_id', $user_id);
            $this->db->update('user_profile_adv', $data_1);
        } else {
            $id = $this->db->insert('user_profile_adv', $data_1);
            $id = $this->db->insert_id();
            return $id;
        }

        return true;
    }

    function update_profile_step_3($arr) {
        $user_id = $arr['user_id'];
        $data = array(
            'height' => $arr['height'],
            'weight' => $arr['weight'],
            'target_weight' => $arr['target_weight'],
            'health_concerns' => $arr['health_concerns'],
            'food_allergies' => $arr['food_allergies'],
            'do_you_excercise' => $arr['do_you_excercise'],
        );
        $this->db->where('user_id', $user_id);
        $this->db->update('user_profile_adv', $data);


        return true;
    }

    ### function to send push notification

    function sendPushNotification($message, $devicetocken, $badge_count, $coach_id, $type) {

        //   $this->updateBadgeCount($devicetocken);
        // Put your device token here (without spaces):
        $deviceToken = $devicetocken;

        // Put your private key's passphrase here:
        //$passphrase = 'team2win';

        $passphrase = 'newage';



        ////////////////////////////////////////////////////////////////////////////////

        $ctx = stream_context_create();
        stream_context_set_option($ctx, 'ssl', 'local_cert', 'apns-prod.pem');
        // stream_context_set_option($ctx, 'ssl', 'local_cert', 'apns-dev.pem');
        stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

        // Open a connection to the APNS server
        //gateway.push.apple.com 
        ################# use this for test server ###############################

        /* $fp = stream_socket_client(
          'ssl://gateway.sandbox.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);
         */
        ################# use this for test server ###############################
        ################# use this for live server ###############################

        $fp = stream_socket_client(
                'ssl://gateway.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);

        ################# use this for live server ###############################

        if (!$fp) {
            //  exit("Failed to connect: $err $errstr" . PHP_EOL);
        }

        //echo 'Connected to APNS' . PHP_EOL;
        // Create the payload body
        $body['aps'] = array(
            'alert' => $message,
            'badge' => intval($badge_count),
            'sound' => 'default'
        );


        $body['coach_id'] = $coach_id;
        $body['type'] = $type;

        /* echo "<pre>";
          print_r($body);
          exit; */
        // Encode the payload as JSON
        $payload = json_encode($body);

        // Build the binary notification
        $msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;

        // Send it to the server
        $result = fwrite($fp, $msg, strlen($msg));

        //print_r($body);
        /* if (!$result)
          echo 'Message not delivered' . PHP_EOL;
          else
          echo 'Message successfully delivered' . "--->" . $deviceToken . PHP_EOL;exit; */
        //exit;
        // Close the connection to the server
        fclose($fp);
    }

    function delete_answers($question_id, $user_id) {
        $sql = "delete from profile_answers where question_id ='$question_id' and user_id ='$user_id'";
        $this->db->query($sql);
        return true;
    }

    function getClients($coach_id) {
        $sql = "select * from  user_master where user_type='2' and user_coach ='$coach_id'";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function get_emailByid($id) {
//        echo $id;
        $this->db->where('email_id', $id);
        $result = $this->db->get('email_config');
//        echo $this->db->last_query();
//        print_r($result);
//        exit;
        return $result->row_array();
    }

    function get_package_byuserid($user_id) {
//        echo "select T1.*,T2.title from user_subscription_master T1 left join package_master on T1.package_id=T2.package_id where T1.user_id='" . $user_id . "' order by subscr_id desc limit 1";
        $plan = $this->db->query("select T1.*,T2.number_weeks from user_subscription_master T1 left join package_master T2 on T1.package_id=T2.package_id where T1.user_id='" . $user_id . "' order by subscr_id desc limit 1");
//       echo $this->db->last_query();
//        print_r($plan->row_array());
        return $plan->row_array();
    }

    public function gatPackDetails($pack_id) {
        $qry = $this->db->query("select * from package_master where package_id='" . $pack_id . "'");
        $row = $qry->row_array();
        return $row;
    }

    function addTransactionDetails($newarr) {
        foreach ($newarr as $key => $value) {

            $msg.=$key . '-' . $value;
            $msg.="~~~~";
        }
        //mail('jilu@newagesmb.com', 'before query', $msg . $newarr['subscr_id'] . '///' . $newarr['txn_id']);



        $subscr_id = $newarr['subscr_id'];
        $wp_user_id = $newarr['custom'];
        $txn_id = $newarr['txn_id'];
        $subscr_date = date("Y-m-d H:i:s");
        $amount = $newarr['payment_gross'];
        $payment_date = $newarr['payment_date'];


        $sql = "INSERT INTO `subscription_master` (`subscr_id`, `wp_user_id`, `txn_id`, `amount`,`subscr_date`, `payment_date`) VALUES ('$subscr_id', '$wp_user_id', '$txn_id', '$amount' ,'$subscr_date', '$payment_date' )";
        $query = $this->db->query($sql);
        $sql = "UPDATE  `user_master` SET 
              `active` = 'Y' where wp_user_id = '$wp_user_id'";
        $query = $this->db->query($sql);
        echo $this->db->last_query();
        // mail('jilu@newagesmb.com', 'after query', $this->db->last_query());
        return true;
    }

    function updateTransactionPeriod($newarr) {

        $subscr_id = trim($newarr['subscr_id']);
        $period3 = $newarr['period3'];

        $days = explode(' ', $newarr['period3']);
        if ($days[1] == 'D')
            $period = 'days';
        if ($days[1] == 'W')
            $period = 'week';
        if ($days[1] == 'M')
            $period = 'month';
        if ($days[1] == 'Y')
            $period = 'years';
        $nextdate = "+" . $days[0] . " " . $period;

        $next_subscr_date = date("Y-m-d H:i:s", strtotime($nextdate, strtotime(date("Y-m-d H:i:s"))));



//        $sql = "UPDATE  `subscription_master` SET 
//             `period` = '52 W' , `next_subscr_date` = '$next_subscr_date'
//                  where subscr_id = '10' ";
//        $query = $this->db->query($sql);

        $sql = "UPDATE  `subscription_master` SET 
             `period` = '$period3' , `next_subscr_date` = '$next_subscr_date'  where `subscr_id` = '$subscr_id' ";
        //  mail('jilu@newagesmb.com', 'period update after query', $sql);
        $query = $this->db->query($sql);

        return true;
    }

    function updateTransactionDetails($newarr) {

        $subscr_id = $newarr['subscr_id'];
//                $wp_user_id = $newarr['custom'];
        $txn_id = $newarr['txn_id'];
        $subscr_date = date("Y-m-d H:i:s");
        $amount = $newarr['payment_gross'];
        $payment_date = $newarr['payment_date'];

        $days = explode(' ', $newarr['period3']);
        if ($days[1] == 'D')
            $period = 'days';
        if ($days[1] == 'W')
            $period = 'week';
        if ($days[1] == 'M')
            $period = 'month';
        if ($days[1] == 'Y')
            $period = 'years';
        $nextdate = "+" . $days[0] . " " . $period;

        $next_subscr_date = date("Y-m-d H:i:s", strtotime($nextdate, strtotime(date("Y-m-d H:i:s"))));


        $sql = "UPDATE  `subscription_master` SET 
             `subscr_date` = '$subscr_date' ,`next_subscr_date` = '$next_subscr_date' 
                  where subscr_id = '$subscr_id' ";
        $query = $this->db->query($sql);
        //  mail('jilu@newagesmb.com', 'automatic after upadting query', $this->db->last_query());
        return true;
    }

    function get_profile_step2_details($user_id) {
        $sql = "select T1.user_city,T1.user_state,
				T1.user_country,T2.relationship_status,T2.have_kids,
				T2.occupation,T2.travel_often_work,
				IF(T3.state_code!='',T3.state_code,T3.state_name) state_name,
				T3.state_code,T4.country_name
				from user_profile_com T1
				left join user_profile_adv T2
				on T1.user_id = T2.user_id
				left join config_state T3
				on T1.user_state = T3.state_id
				left join config_country T4
				on T1.user_country = T4.country_id 	
				where T1.user_id ='$user_id'";


        $query = $this->db->query($sql);
        return $query->row_array();
    }

    function getclient_answers($user_id) {
        $sql = "select T1.*,T2.*,T3.* from profile_answers T1
					left join config_questions T2
					on T2.profile_qn_id = T1.question_id
					left join  config_answers T3
					on T3.question_id = T1.question_id
					where T1.user_id = '$user_id' and T2.profile_qn_id !='10' and T2.profile_qn_id!='11' ";
        $query = $this->db->query($sql);
        return $query->result_array();
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

    function getAnswers($qn_id, $user_id) {
        $sql = "select T1.* from config_answers T1
					left join profile_answers T2
					on T1.option_id =T2.answer_id
					 where T2.question_id ='$qn_id' and T2.user_id = '$user_id' and T2.answer_id 
					 IN(select answer_id from profile_answers where user_id ='$user_id');";


        $query = $this->db->query($sql);
        $records = $query->result_array();
        return $records;
    }

    function check_Coach_valid($client_id, $coach_id) {
        $sql = "select user_id from user_master where user_coach = '$coach_id' and  user_id = '$client_id'";
        $query = $this->db->query($sql);
        $records = $query->row_array();
        if ($records['user_id'])
            return 'Y';
        else
            return 'N';
    }

    function setSettings($arr) {
        $user_id = $arr['user_id'];

        $data = array(
            'new_messages' => $arr['new_messages'],
            'new_requests' => $arr['new_requests'],
            'vibrations' => $arr['vibrations'],
            'user_id' => $arr['user_id'],
        );

        $sql = "select id from settings where user_id = '$user_id'";
        $query = $this->db->query($sql);
        $records = $query->row_array();
        if ($records['id']) {
            $this->db->where('user_id', $user_id);
            $this->db->update('settings', $data);
        } else {
            $id = $this->db->insert('settings', $data);
        }
        return true;
    }

    function set_user_settings($user_id) {
        $data = array(
            'new_messages' => 1,
            'new_requests' => 1,
            'vibrations' => 1,
            'user_id' => $user_id,
        );
        $this->db->insert('settings', $data);
    }

    #function to get settings page details

    function getSettings($user_id) {
        $sql = "select * from settings where user_id ='$user_id'";
        $query = $this->db->query($sql);
        $records = $query->row_array();
        return $records;
    }

    #delete file from amazon bucket

    function deleteFile_S3($foldername, $bucket_name) {
        require_once("application/controllers/S3config.php");
        //include the S3 class
        if (!class_exists('S3'))
            require_once('application/controllers/S3.php');
        //instantiate the class
        $s3 = new S3(S3_ACCESS_KEY, S3_SECRET_KEY);
        $s3->deleteObject($bucket_name, $foldername);
    }

    function uploadFile_S3($temp_name, $foldername, $bucket_name, $create_bucket = false) {

        require_once("application/controllers/S3config.php");
        //include the S3 class
        if (!class_exists('S3'))
            require_once('application/controllers/S3.php');


        //instantiate the class
        $s3 = new S3(S3_ACCESS_KEY, S3_SECRET_KEY);


        $create_bucket = true;
        //create a new bucket
        if ($create_bucket == true) {

            $s3->putBucket($bucket_name, S3::ACL_PUBLIC_READ);
        }

        //echo $temp_name.'<br/>'.$bucket_name.'<br/>'.$foldername.'<br/>'.S3::ACL_PUBLIC_READ;exit;
        //move the file
        if ($s3->putObjectFile($temp_name, $bucket_name, $foldername, S3::ACL_PUBLIC_READ)) {
            // echo "We successfully uploaded your file";
            return "We successfully uploaded your file";
        } else {
            //  echo "Something went wrong while uploading your file";
            return "Something went wrong while uploading your file";
        }
    }

    #function To Update Member Image Extension

    function updateArchiveImage($img_extension, $id) {
        $data = array(
            'image_extension' => $img_extension
        );

        $this->db->where('archive_id', $id);
        $id = $this->db->update('archive_master', $data);
        return true;
    }

    function skip_profile($user_id, $skip_status) {
        $sql = "update user_master set profile_skip_status ='$skip_status' where user_id ='$user_id'";
        $this->db->query($sql);
        return true;
    }

    ## function update user device tocken

    function updateUserDeviceTocken($user_id) {
        $this->db->query("update user_master set device_id = '' where user_id = '$user_id' ");
        //	$this->db->query("update user_master set device_id = '$device_tocken' where user_id = $user_id ");	
    }

    function updateDevice_Token($device_toekn, $user_id) {
        $this->db->query("update user_master set device_id = '$device_toekn' where user_id = '$user_id' ");
    }

    function getEvolution_Archive($user_id) {
        $sql = "select T1.* from archive_master  T1  left join archive_categories T2
					on 	 T1.archive_category = T2.archive_cat_id
					";
    }

    function getDatelist($user_id, $month, $year, $from_date, $to_date, $coach_id) {

        if ($from_date) {
            $from_date1 = date("Y-m-d", strtotime($from_date));
        }
        if ($to_date) {
            $to_date1 = date("Y-m-d", strtotime($to_date));
        }

        $sql = "SELECT DATE_FORMAT(  archive_date,  '%Y-%m-%d' ) AS archive_date_1,
				DATE_FORMAT( archive_date,  '%m-%d-%Y' ) AS archive_date1, 
				DAYNAME( archive_date ) AS 
				DAY ,DATE_FORMAT(archive_date ,'%b')  as month_name,
				EXTRACT(DAY FROM archive_date) as date_part,EXTRACT(YEAR FROM archive_date) as year
				FROM  `archive_master` 
				WHERE 
				((client_id ='$user_id'  and   created_user  = '$coach_id')  
				or 
				(client_id ='$coach_id'  and   created_user  = '$user_id'))";

        if ($from_date1 != "" && $to_date1 != "") {
            $sql .= " AND archive_date BETWEEN '$from_date1' AND DATE_ADD('$to_date1', INTERVAL 1 DAY) ";
        }

        if ($from_date1 == "" && $to_date1 == "") {
            $sql.="
				and
				MONTH(archive_date) = '$month'  
				and
				YEAR(archive_date) ='$year' ";
        }

        $sql.="		GROUP BY archive_date_1";

        $query = $this->db->query($sql);
        $result = $query->result_array();
        return $result;
    }

    function EvolutionFeedList($archive_date, $user_id, $month, $year, $coach_id) {

        $sql = "SELECT T1.archive_id,
				T1.client_id,T1.created_date,T1.created_user,T1.archive_description,T1.image_extension,T1.archive_category,
				DATE_FORMAT(archive_date, '%Y-%m-%d %H:%i:%p' ) AS archive_date,
				DAYNAME('$archive_date') as day,T2.category_name,
				CONCAT('https://s3.amazonaws.com/evolution-eat/archive_master/',T1.archive_id,'.',T1.image_extension) as image

				";


        $sql .= " FROM `archive_master` T1   ";

        $sql .= " LEFT JOIN archive_categories T2";

        $sql .= " ON T1.archive_category = T2.archive_cat_id";

        $sql .= " WHERE (( T1.client_id ='$user_id'  and  T1.created_user  = '$coach_id')";

        $sql.= " or ( T1.client_id ='$coach_id'  and  T1.created_user  = '$user_id'))";

        //$sql.= " and T1.archive_category='$category_id'";
        //	$sql.= " and MONTH(archive_date) = '$month'  and YEAR(archive_date) ='$year'";

        $sql .= " and DATE_FORMAT(T1.archive_date, '%Y-%m-%d') = '$archive_date' ";


        $sql .= " order by T1.archive_date asc";


        $query = $this->db->query($sql);
        $result = $query->result_array();
        return $result;
    }

    function EvolutionFeedListNew($archive_date, $user_id, $month, $year, $from_date, $to_date, $coach_id) {
        if ($from_date) {
            $from_date1 = date("Y-m-d", strtotime($from_date));
        }
        if ($to_date) {
            $to_date1 = date("Y-m-d", strtotime($to_date));
        }
        $sql = "SELECT T1.archive_id,
				T1.client_id,T1.image_type,T1.created_date,T1.created_user,T1.archive_description,T1.image_extension,T1.archive_category,
				DATE_FORMAT(archive_date, '%Y-%m-%d %H:%i:%p' ) AS archive_date,
				DAYNAME('$archive_date') as day,T2.category_name,
				
				if(T1.image_extension <> '',
				CONCAT('https://s3.amazonaws.com/evolution-eat/archive_master/',T1.archive_id,'.',T1.image_extension,'?'," . date("his") . "),'" . base_url() . "upload/no_archive1.png') as image,
				
				
				DAYNAME( archive_date ) AS 
				DAY ,DATE_FORMAT(archive_date ,'%b')  as month_name,
				EXTRACT(DAY FROM archive_date) as date_part,EXTRACT(YEAR FROM archive_date) as year,
				CONCAT(DATE_FORMAT(archive_date ,'%b'),EXTRACT(DAY FROM archive_date),' ',EXTRACT(YEAR FROM archive_date)) as formatted_date

				";


        $sql .= " FROM `archive_master` T1   ";

        $sql .= " LEFT JOIN archive_categories T2";

        $sql .= " ON T1.archive_category = T2.archive_cat_id";

        $sql .= " WHERE (( T1.client_id ='$user_id'  and  T1.created_user  = '$coach_id')";

        $sql .= " or  ( T1.client_id ='$coach_id'  and  T1.created_user  = '$user_id'))";

        if ($from_date1 != "" && $to_date1 != "") {
            $sql .= " AND archive_date BETWEEN '$from_date1' AND DATE_ADD('$to_date1', INTERVAL 1 DAY) ";
        }

        if ($from_date1 == "" && $to_date1 == "") {
            $sql.="
				and
				MONTH(archive_date) = '$month'  
				and
				YEAR(archive_date) ='$year' ";
        }




        $sql .= " order by T1.archive_date DESC";


        $query = $this->db->query($sql);
        $result = $query->result_array();
        return $result;
    }

    function remove_archive_data($id) {
        $sql = "delete from  archive_master where archive_id 	='$id'";
        $this->db->query($sql);
        return true;
    }

    function getarchive_details($id) {
        $sql = "SELECT *  from archive_master where archive_id ='$id'";
        $query = $this->db->query($sql);
        $result = $query->row_array();
        return $result;
    }

    function get_excercise_categories() {
        $sql = "select * from exc_categories where 1";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        return $result;
    }

    function getarchive_full_details($arr) {
        $client_id = $arr['client_id'];
        $created_user = $arr['created_user'];

        $sql = "select T1.*,T2.username as created_username,T2.name as name,T3.category_name as archive_category_name
				from archive_master T1 
				left join user_master T2
				on T1.created_user =  T2.user_id
				left join archive_categories T3
				on T1.archive_category = T3.archive_cat_id
				where  (T1.created_user = '$created_user' and T1.client_id ='$client_id') 
			   or
			  (T1.created_user = '$client_id' and T1.client_id ='$created_user') ";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        return $result;
    }

    //AND DATE(pm.txn_date) BETWEEN '$date_from' AND DATE_ADD('$date_to', INTERVAL 1 DAY) ";

    function get_archives_filter_search($from_date, $to_date, $user_id) {
        $sql = "SELECT T1.archive_id,
				T1.client_id,T1.created_date,T1.created_user,T1.archive_description,T1.image_extension,T1.archive_category,
				DATE_FORMAT(archive_date, '%Y-%m-%d %H:%i:%p' ) AS archive_date,
				DAYNAME('$archive_date') as day,T2.category_name,
				CONCAT('https://s3.amazonaws.com/evolution-eat/archive_master/',T1.archive_id,'.',T1.image_extension) as image
				from archive_master T1 
				LEFT JOIN archive_categories T2
				ON   T1.archive_category = T2.archive_cat_id 
				WHERE 1 
				AND DATE(T1.archive_date) BETWEEN '$from_date' AND '$to_date' 
				AND ( T1.client_id ='$user_id'  or  T1.created_user  = '$user_id')";

        //DATE_ADD('$to_date', INTERVAL 1 DAY) 
        $query = $this->db->query($sql);
        $result = $query->result_array();
        return $result;
    }

    function update_archive_data($arr) {
        $archive_id = $arr['archive_id'];
        $data = array(
            'client_id' => $arr['client_id'],
            'created_user' => $arr['created_user'],
            'archive_description' => $arr['archive_description'],
            'archive_date' => $arr['archive_date'],
            'archive_category' => $arr['archive_category'],
        );
        $this->db->where('archive_id', $archive_id);
        $this->db->update('archive_master', $data);
    }

    function save_archive_details($arr) {

        $data = array(
            'archive_date' => $arr['archive_date'],
            'client_id' => $arr['client_id'],
            'created_user' => $arr['created_user'],
            'archive_description' => $arr['archive_description'],
            'created_date' => date("Y-m-d H:i:s"),
            'archive_category' => $arr['archive_category'],
            'message_id' => $arr['message_id']
        );

        $this->db->insert('archive_master', $data);
        return $this->db->insert_id();
    }

    function get_intensities() {
        $sql = "select * from exc_intensity_master where 1";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        return $result;
    }

    function getUsCountry() {
        $sql = "select * from config_country where country_id IN(840) order by country_name desc ";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function getCountryList_ExceptUs() {
        $sql = "select T1.* from config_country  T1 where T1.country_id != '840' and T1.country_id IN(select country_id from config_state)order by T1.country_name asc ";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function add_excercise_details($arr) {

        $exc_id = $arr['exc_log_id'];

        $data = array(
            'excercise_name' => $arr['excercise_name'],
            'exc_sets' => $arr['exc_sets'],
            'exc_reps' => $arr['exc_reps'],
            'exc_weight' => $arr['exc_weight'],
            'exc_notes' => $arr['exc_notes'],
            'created_date' => date("Y-m-d H:i:s"),
            'exc_date' => $arr['exc_date'],
            'client_id' => $arr['client_id'],
            'created_user_id' => $arr['created_user_id'],
            'exc_duration' => $arr['exc_duration'],
            'exc_distance' => $arr['exc_distance'],
            'exc_intensity_id' => $arr['exc_intensity_id'],
            'exc_sub_cat_id' => $arr['exc_sub_cat_id'],
            'exc_style' => $arr['exc_style'],
            'exc_sport' => $arr['exc_sport'],
            'exc_level' => $arr['exc_level']
        );

        if ($exc_id) {
            $this->db->where('exc_log_id', $exc_id);
            $this->db->update('exc_log', $data);
        } else {
            $this->db->insert('exc_log', $data);
        }
    }

    function get_excercise_details($exc_log_id) {
        $sql = "select T1.*,T2.exc_intensity,T3.sub_cat_name from exc_log T1 
				left join exc_intensity_master T2
				on T1.exc_intensity_id = T2.exc_intensity_id 
				left join exc_sub_categories T3
				on T1.exc_sub_cat_id=T3.exc_sub_cat_id
				where T1.exc_log_id ='$exc_log_id'";
        $query = $this->db->query($sql);
        $result = $query->row_array();
        return $result;
    }

    function getsub_categories_cardio() {
        $sql = "select T1.*,T2.exc_cat_id from exc_sub_categories T1
				left join exc_map_categories T2
				on T1.exc_sub_cat_id 	 = T2.exc_sub_cat_id 	
				where T2.exc_cat_id = '2'";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        return $result;
    }

    function getsub_categories_yoga() {
        $sql = "select T1.*,T2.exc_cat_id from exc_sub_categories T1
				left join exc_map_categories T2
				on T1.exc_sub_cat_id 	 = T2.exc_sub_cat_id 	
				where T2.exc_cat_id = '3'";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        return $result;
    }

    function delete_excercise($id) {
        $sql = "delete from exc_log where exc_log_id ='$id'";
        $this->db->query($sql);
        return true;
    }

    function view_excercise_list($arr) {
        $month = $arr['month'];
        $year = $arr['year'];
    }

    function getExcerciseDatelist($user_id, $month, $year, $coach_id) {

        $sql = "SELECT DATE_FORMAT(  exc_date,  '%Y-%m-%d' ) AS exc_date_1,
				DATE_FORMAT( exc_date,  '%m-%d-%Y' ) AS exc_date1, 
				DAYNAME( exc_date ) AS 
				DAY ,DATE_FORMAT(exc_date ,'%b')  as month_name,
				EXTRACT(DAY FROM exc_date) as date_part,EXTRACT(YEAR FROM exc_date) as year
				
				FROM  `exc_log` 
				WHERE  1 and 
				((client_id ='$user_id'  and   created_user_id  = '$coach_id')  
				or 
				(client_id ='$coach_id'  and   created_user_id  = '$user_id'))";

        $sql.="
				and
				MONTH(exc_date) = '$month'  
				and
				YEAR(exc_date) ='$year' ";


        $sql.="		GROUP BY exc_date_1";
        //
        $query = $this->db->query($sql);
        $result = $query->result_array();
        return $result;
    }

    function ExcerciseFeedList($exc_date, $user_id, $month, $year, $day) {

        $sql = "SELECT T1.*";


        $sql .= " FROM `exc_log` T1   ";

        //$sql .= " LEFT JOIN archive_categories T2";
        //$sql .= " ON T1.archive_category = T2.archive_cat_id";

        $sql .= " WHERE ( T1.client_id ='$user_id'  or  T1.created_user_id 	  = '$user_id')";

        //$sql.= " and T1.archive_category='$category_id'";

        $sql.= " and MONTH(exc_date) = '$month'  and YEAR(exc_date) ='$year' and EXTRACT(DAY FROM exc_date)  ='$day'";

        $sql .= " and DATE_FORMAT(T1.exc_date, '%Y-%m-%d') = '$exc_date' ";


        $sql .= " order by T1.exc_date asc";


        $query = $this->db->query($sql);
        $result = $query->result_array();
        return $result;
    }

    function get_archive_category_details($id) {
        $sql = "select * from archive_categories  where archive_cat_id ='$id'";
        $query = $this->db->query($sql);
        $result = $query->row_array();
        return $result;
    }

    function get_excercise_on_date($exc_date, $category_id, $client_id, $coach_id) {
        $sql = "select T1.*,T2.sub_cat_name  from exc_log T1 
				left join exc_sub_categories T2
				On T1.exc_sub_cat_id = T2.exc_sub_cat_id 	
				where T1.exc_cat_id ='$category_id' 
				and  
				DATE_FORMAT(T1.exc_date, '%Y-%m-%d') = '$exc_date' ";

        $sql .= " and ((created_user_id ='$client_id' and client_id ='$coach_id') or (created_user_id ='$coach_id' and client_id ='$client_id'))";

        $query = $this->db->query($sql);
        $result = $query->result_array();
        return $result;
    }

    function add_excercise_details_weight_training($arr) {

        $exc_id = $arr['exc_log_id'];

        $data = array(
            'excercise_name' => $arr['excercise_name'],
            'exc_sets' => $arr['exc_sets'],
            'exc_reps' => $arr['exc_reps'],
            'exc_weight' => $arr['exc_weight'],
            'exc_notes' => $arr['exc_notes'],
            'created_date' => date("Y-m-d H:i:s"),
            'exc_date' => $arr['exc_date'],
            'client_id' => $arr['client_id'],
            'created_user_id' => $arr['created_user_id'],
            'exc_cat_id' => $arr['exc_cat_id'],
        );

        if ($exc_id) {
            $this->db->where('exc_log_id', $exc_id);
            $this->db->update('exc_log', $data);
            return $exc_id;
        } else {
            $this->db->insert('exc_log', $data);
            return $this->db->insert_id();
        }
    }

    function add_excercise_details_cardiac($arr) {

        $exc_id = $arr['exc_log_id'];

        $data = array(
            'created_date' => date("Y-m-d H:i:s"),
            'exc_date' => $arr['exc_date'],
            'client_id' => $arr['client_id'],
            'created_user_id' => $arr['created_user_id'],
            'exc_duration' => $arr['exc_duration'],
            'exc_distance' => $arr['exc_distance'],
            'exc_intensity_id' => $arr['exc_intensity_id'],
            'exc_sub_cat_id' => $arr['exc_sub_cat_id'],
            'exc_cat_id' => $arr['exc_cat_id'],
            'exc_notes' => $arr['exc_notes']
        );

        if ($exc_id) {
            $this->db->where('exc_log_id', $exc_id);
            $this->db->update('exc_log', $data);
            return $exc_id;
        } else {
            $this->db->insert('exc_log', $data);
            return $this->db->insert_id();
        }
    }

    function add_excercise_details_yoga($arr) {

        $exc_id = $arr['exc_log_id'];

        $data = array(
            'exc_notes' => $arr['exc_notes'],
            'created_date' => date("Y-m-d H:i:s"),
            'exc_date' => $arr['exc_date'],
            'client_id' => $arr['client_id'],
            'created_user_id' => $arr['created_user_id'],
            'exc_duration' => $arr['exc_duration'],
            'exc_sub_cat_id' => $arr['exc_sub_cat_id'],
            'exc_style' => $arr['exc_style'],
            'exc_cat_id' => $arr['exc_cat_id'],
        );

        if ($exc_id) {
            $this->db->where('exc_log_id', $exc_id);
            $this->db->update('exc_log', $data);
            return $exc_id;
        } else {
            $this->db->insert('exc_log', $data);
            return $this->db->insert_id();
        }
    }

    function add_excercise_details_sports($arr) {

        $exc_id = $arr['exc_log_id'];

        $data = array(
            'exc_notes' => $arr['exc_notes'],
            'created_date' => date("Y-m-d H:i:s"),
            'exc_date' => $arr['exc_date'],
            'client_id' => $arr['client_id'],
            'created_user_id' => $arr['created_user_id'],
            'exc_duration' => $arr['exc_duration'],
            'exc_sport' => $arr['exc_sport'],
            'exc_level' => $arr['exc_level'],
            'exc_cat_id' => $arr['exc_cat_id'],
        );

        if ($exc_id) {
            $this->db->where('exc_log_id', $exc_id);
            $this->db->update('exc_log', $data);
            return $exc_id;
        } else {
            $this->db->insert('exc_log', $data);
            return $this->db->insert_id();
        }
    }

    function remove_excercise_data($id) {
        $sql = "delete from  exc_log where exc_log_id ='$id'";
        $this->db->query($sql);
        return true;
    }

    function get_excercise_details_by_category($client_id, $exc_date, $category_id, $coach_id) {
        $sql = "select *,(select exc_intensity from exc_intensity_master where exc_intensity_master.exc_intensity_id=exc_log.exc_intensity_id) as  exc_intensity,(select  sub_cat_name from exc_sub_categories where exc_sub_categories.exc_sub_cat_id=exc_log.exc_sub_cat_id) as  sub_cat_name ";

        if ($category_id == '1') {
            $sql .= " ,concat(exc_sets,' sets ',exc_sets,' reps ',round(exc_weight),' lbs') as weight_description ";
        }

        $sql .=" from exc_log where exc_date 	 = '$exc_date' 
				and exc_cat_id = '$category_id'";

        $sql .= " and ((created_user_id ='$client_id' and client_id ='$coach_id') or (created_user_id ='$coach_id' and client_id ='$client_id'))";
        //echo $sql;exit;
        $query = $this->db->query($sql);
        $result = $query->result_array();
        return $result;
    }

    function add_progress_details($arr) {

        $progress_id = $arr['progress_id'];

        $data = array(
            'progress_date' => $arr['progress_date'],
            'weight' => $arr['weight'],
            'waist_curcumference' => $arr['waist_curcumference'],
            'client_id' => $arr['client_id'],
            'created_user_id' => $arr['created_user_id'],
        );

        if ($progress_id) {
            $this->db->where('progress_id', $progress_id);
            $this->db->update('user_progress_master', $data);
        } else {
            $this->db->insert('user_progress_master', $data);
        }
    }

    function get_progress_details($arr) {
        $client_id = $arr['client_id'];
        $coach_id = $arr['coach_id'];

        if (!$coach_id) {
            $coach_id = $arr['created_user_id'];
        }

        $sql = "select *,TRIM(TRAILING '.' FROM TRIM(TRAILING '0' from weight)) as weight,TRIM(TRAILING '.' FROM TRIM(TRAILING '0' from waist_curcumference)) as waist_curcumference,DAYNAME(  	progress_date ) AS 
				DAY ,DATE_FORMAT( 	progress_date ,'%b')  as month_name,
				EXTRACT(DAY FROM  	progress_date) as date_part,EXTRACT(YEAR FROM  	progress_date) as year from user_progress_master where 1";
        $sql .= " and ((created_user_id ='$client_id' and client_id ='$coach_id') or (created_user_id ='$coach_id' and client_id ='$client_id'))";
        $sql .=" order by progress_date desc";


        $query = $this->db->query($sql);
        $result = $query->result_array();
        return $result;
    }

    function get_all_Dates($user_id, $month, $year, $coach_id) {

        $sql = "SELECT DATE_FORMAT(  exc_date,  '%Y-%m-%d' ) AS exc_date_1,
				DATE_FORMAT( exc_date,  '%m-%d-%Y' ) AS exc_date1, 
				DAYNAME( exc_date ) AS 
				DAY ,DATE_FORMAT(exc_date ,'%b')  as month_name,
				EXTRACT(DAY FROM exc_date) as date_part,EXTRACT(YEAR FROM exc_date) as year
				FROM  `exc_log` 
				WHERE  1 and 
				((client_id ='$user_id'  and   created_user_id  = '$coach_id')  
				or 
				(client_id ='$coach_id'  and   created_user_id  = '$user_id'))";

        $sql.="
				and
				MONTH(exc_date) = '$month'  
				and
				YEAR(exc_date) ='$year' ";


        $sql.="		GROUP BY exc_date_1";

        $query = $this->db->query($sql);
        $result = $query->result_array();
        return $result;
    }

    function add_edit_user_goals($arr) {

        $client_id = $arr['client_id'];
        $created_user_id = $arr['created_user_id'];

        $data = array(
            'client_id' => $arr['client_id'],
            'created_user_id' => $arr['created_user_id'],
            'current_weight' => $arr['current_weight'],
            'current_waist_circum' => $arr['current_waist_circum'],
            'weekly_goal' => $arr['weekly_goal'],
            'monthly_weight' => $arr['monthly_weight'],
            'monthly_waist_circumference' => $arr['monthly_waist_circumference'],
            'personal_goal' => $arr['personal_goal'],
            'target_weight' => $arr['target_weight'],
            'target_waist_circumference' => $arr['target_waist_circumference'],
            'target_body_fat' => $arr['target_body_fat'],
            'target_personal_goal' => $arr['target_personal_goal'],
            'current_bmi' => $arr['current_bmi'],
            'current_bodyfat' => $arr['current_bodyfat'],
            'target_bmi' => $arr['target_bmi']
        );

        $sql = "select goal_id from user_goals WHERE  1 and 
				((client_id ='$client_id'  and   created_user_id  = '$created_user_id')  
				or 
				(client_id ='$created_user_id'  and   created_user_id  = '$client_id'))";

        $query = $this->db->query($sql);
        $result = $query->row_array();

        if ($result['goal_id']) {
            $sql1 = "update user_goals set current_weight ='" . $arr['current_weight'] . "',current_bmi ='" . $arr['current_bmi'] . "',current_bodyfat ='" . $arr['current_bodyfat'] . "',current_waist_circum='" . $arr['current_waist_circum'] . "',weekly_goal='" . $arr['weekly_goal'] . "',monthly_weight='" . $arr['monthly_weight'] . "',monthly_waist_circumference='" . $arr['monthly_waist_circumference'] . "',personal_goal='" . $arr['personal_goal'] . "',target_weight='" . $arr['target_weight'] . "',target_waist_circumference='" . $arr['target_waist_circumference'] . "',target_body_fat='" . $arr['target_body_fat'] . "',target_personal_goal='" . $arr['target_personal_goal'] . "',target_bmi='" . $arr['target_bmi'] . "' WHERE  1 and 
				((client_id ='$client_id'  and   created_user_id  = '$created_user_id')  
				or 
				(client_id ='$created_user_id'  and   created_user_id  = '$client_id'))";
            $this->db->query($sql1);
        } else {
            $this->db->insert('user_goals', $data);
        }

        #update progress section
        $cur_date = date("Y-m-d");
        $sql = "select progress_id from user_progress_master 
		 		where  progress_date ='$cur_date' and 
				((client_id ='$client_id'  and  created_user_id  = '$created_user_id')  
				or 
				(client_id ='$created_user_id'  and   created_user_id  = '$client_id'))";

        $query = $this->db->query($sql);
        $result = $query->row_array();
        $id = $result['progress_id'];
        if ($result['progress_id']) {
            $weight = $arr['current_weight'];
            $waist_curcumference = $arr['current_waist_circum'];

            $sql = "update user_progress_master set weight = '$weight' , waist_curcumference ='$waist_curcumference'  where  progress_id ='$id'";
            $this->db->query($sql);
        } else {
            $data = array(
                'progress_date' => $cur_date,
                'weight' => $arr['current_weight'],
                'waist_curcumference' => $arr['current_waist_circum'],
                'client_id' => $arr['client_id'],
                'created_user_id' => $arr['created_user_id'],
            );
            $this->db->insert('user_progress_master', $data);
        }


        return true;
    }

    function get_user_goals($arr) {
        $user_id = $arr['client_id'];
        $coach_id = $arr['coach_id'];
        if (!$coach_id)
            $coach_id = $arr['created_user_id'];
        $sql = "SELECT T1.*,TRIM(TRAILING '.' FROM TRIM(TRAILING '0' from T1.current_weight)) as current_weight,
		TRIM(TRAILING '.' FROM TRIM(TRAILING '0' from T1.current_waist_circum)) as  current_waist_circum,
		TRIM(TRAILING '.' FROM TRIM(TRAILING '0' from T1.monthly_weight)) as  monthly_weight,
		TRIM(TRAILING '.' FROM TRIM(TRAILING '0' from T1.monthly_waist_circumference)) as  monthly_waist_circumference,
		TRIM(TRAILING '.' FROM TRIM(TRAILING '0' from T1.target_weight)) as  target_weight,
		TRIM(TRAILING '.' FROM TRIM(TRAILING '0' from T1.target_waist_circumference)) as  target_waist_circumference,
		TRIM(TRAILING '.' FROM TRIM(TRAILING '0' from T1.target_body_fat)) as  target_body_fat,
		TRIM(TRAILING '.' FROM TRIM(TRAILING '0' from T1.target_bmi)) as  target_bmi,
		
		T2.name
				FROM  `user_goals`  T1
				left join user_master T2
				on T1.client_id= T2.user_id
				WHERE  1 and 
				((T1.client_id ='$user_id'  and   T1.created_user_id  = '$coach_id')  
				or 
				(T1.client_id ='$coach_id'  and   T1.created_user_id  = '$user_id'))";
        //echo $sql;exit;
        $query = $this->db->query($sql);
        $result = $query->row_array();
        return $result;
    }

    function calculate_age($user_id) {
        $sql = "SELECT TIMESTAMPDIFF( YEAR, user_dob, CURDATE( ) ) AS age from user_profile_com where user_id ='$user_id'";
        $query = $this->db->query($sql);
        $result = $query->row_array();
        return $result['age'];
    }

    function add_edit_a_note($arr) {
        $client_id = $arr['client_id'];
        $created_user_id = $arr['created_user_id'];
        $note_id = $arr['note_id'];
        $data = array(
            'client_id' => $arr['client_id'],
            'created_user_id' => $arr['created_user_id'],
            'notes' => $arr['notes'],
            'added_date' => date("Y-m-d H:i:s"),
        );

        if ($note_id) {
            $this->db->where('note_id', $note_id);
            $this->db->update('notes', $data);
        } else {
            $this->db->insert('notes', $data);
        }
    }

    function get_user_notes($arr) {
        $client_id = $arr['client_id'];
        $created_user_id = $arr['created_user_id'];
        $sql = "select *,DATE_FORMAT(  added_date,  '%m/%d/%Y' ) AS added_date from notes 
		 		where  1 and 
				((client_id ='$client_id'  and  created_user_id  = '$created_user_id')  
				or 
				(client_id ='$created_user_id'  and   created_user_id  = '$client_id')) order by note_id desc";

        $query = $this->db->query($sql);
        $result = $query->result_array();
        return $result;
    }

    function get_note_details($note_id) {
        $sql = "select * from notes where note_id ='$note_id'";
        $query = $this->db->query($sql);
        $result = $query->row_array();
        return $result;
    }

    function get_profile_step3_details($user_id) {
        $sql = "select T2.relationship_status,T2.have_kids,
				T2.occupation,T2.travel_often_work,T2.health_concerns,
				TRIM(TRAILING '.' FROM TRIM(TRAILING '0' from T2.height)) as  height,
				TRIM(TRAILING '.' FROM TRIM(TRAILING '0' from T2.weight)) as  weight,
				TRIM(TRAILING '.' FROM TRIM(TRAILING '0' from T2.target_weight)) as  target_weight,T2.food_allergies,T2.do_you_excercise,T1.user_id
				from user_profile_com T1
				left join user_profile_adv T2
				on T1.user_id = T2.user_id
				where T1.user_id ='$user_id'";
        $query = $this->db->query($sql);
        return $query->row_array();
    }

    function get_Archive_categories_wth_date($date, $user_id, $coach_id) {
        $sql = "select T1.archive_id,T1.archive_category from archive_master T1
		
		where DATE_FORMAT(  T1.archive_date,  '%Y-%m-%d' ) = '$date' ";


        $sql .= " and(( T1.client_id ='$user_id'  and  T1.created_user  = '$coach_id')";

        $sql.= " or ( T1.client_id ='$coach_id'  and  T1.created_user  = '$user_id'))";


        $sql .=" group by T1.archive_category  order by T1.archive_category asc";

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function getEv_list($archive_date, $user_id, $coach_id, $arc_category_id) {

        $sql = "SELECT T1.archive_id,
				T1.client_id,T1.created_date,T1.created_user,T1.archive_description,T1.image_extension,T1.archive_category,
				DATE_FORMAT(archive_date, '%Y-%m-%d %H:%i:%p' ) AS archive_date,
				DAYNAME('$archive_date') as day,T2.category_name,
				CONCAT('https://s3.amazonaws.com/evolution-eat/archive_master/',T1.archive_id,'.',T1.image_extension) as image

				";

        $sql .= " FROM `archive_master` T1   ";

        $sql .= " LEFT JOIN archive_categories T2";

        $sql .= " ON T1.archive_category = T2.archive_cat_id";

        $sql .= " WHERE (( T1.client_id ='$user_id'  and  T1.created_user  = '$coach_id')";

        $sql.= " or ( T1.client_id ='$coach_id'  and  T1.created_user  = '$user_id'))";

        //$sql.= " and T1.archive_category='$category_id'";
        //	$sql.= " and MONTH(archive_date) = '$month'  and YEAR(archive_date) ='$year'";

        $sql .= " and DATE_FORMAT(T1.archive_date, '%Y-%m-%d') = '$archive_date' ";

        $sql.=" and T1.archive_category = '$arc_category_id'";


        $sql .= " order by T1.archive_date asc";


        $query = $this->db->query($sql);
        $result = $query->result_array();
        return $result;
    }

    function get_archive_categories() {
        $sql = "select * from archive_categories where 1 order by  archive_cat_id  asc	";
        $query = $this->db->query($sql);
        $records = $query->result_array();
        return $records;
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

    public function getAdminEmail() {
        $sql = "SELECT *  FROM user_master WHERE `user_type` = '1'";
        $query = $this->db->query($sql);
        return $query->row_array();
    }

    function EvolutionFeedList_new($archive_date, $user_id, $month, $year, $coach_id) {

        $sql = "SELECT T1.archive_id,
				T1.client_id,T1.image_type,T1.created_date,T1.created_user,T1.archive_description,T1.image_extension,T1.archive_category,
				DATE_FORMAT(archive_date, '%Y-%m-%d %H:%i:%p' ) AS archive_date,
				DAYNAME('$archive_date') as day,T2.category_name,
				if(T1.image_extension <> '',
				CONCAT('https://s3.amazonaws.com/evolution-eat/archive_master/',T1.archive_id,'.',T1.image_extension,'?'," . date("his") . "),'" . base_url() . "upload/no_archive1.png') as image
				";
        $sql .= " FROM `archive_master` T1   ";

        $sql .= " LEFT JOIN archive_categories T2";

        $sql .= " ON T1.archive_category = T2.archive_cat_id";

        $sql .= " WHERE (( T1.client_id ='$user_id'  and  T1.created_user  = '$coach_id')";

        $sql.= " or ( T1.client_id ='$coach_id'  and  T1.created_user  = '$user_id'))";

        //$sql.= " and T1.archive_category='$category_id'";
        //$sql.= " and MONTH(archive_date) = '$month'  and YEAR(archive_date) ='$year'";

        $sql .= " and DATE_FORMAT(T1.archive_date, '%Y-%m-%d') = '$archive_date' ";

        $sql .= "order by T1.archive_category 	 asc";

        $query = $this->db->query($sql);
        $result = $query->result_array();
        return $result;
    }

    function add_personal_info($arr) {

        $user_id = $arr['user_id'];

        $data = array(
            'user_email' => $arr['user_email']
        );


        $this->db->where('user_id', $user_id);
        $this->db->update('user_master', $data);

        $data1 = array(
            'user_phone' => $arr['user_phone'],
            'user_address' => $arr['user_address'],
        );


        $this->db->where('user_id', $user_id);
        $this->db->update('user_profile_com', $data1);
        return true;
    }

    function get_personal_info($user_id) {
        $sql = "select T1.user_email,T2.user_phone,T2.user_address
		from user_master T1
		left join user_profile_com T2
		on T1.user_id =T2.user_id
		where T1.user_id ='$user_id'";

        $query = $this->db->query($sql);
        $result = $query->result_array();
        return $result;
    }

    function generate_random_code() {
        $url_list = $this->db->query("SELECT `referal_code` FROM `client_refferals`");
        $i = 1;
        do {

            $random_string = $this->generate_random_string();
            return $random_string;
        } while (!in_array($random_string, $url_list));
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

    function get_Refer_code($user_id) {
        $sql = "select coach_refer_code from user_master
		where user_id ='$user_id'";

        $query = $this->db->query($sql);
        $result = $query->row_array();
        if ($result['coach_refer_code'])
            return $result['coach_refer_code'];
        else
            return 'N';
    }

    function update_refer_code($coach_id, $refer_code) {
        $sql = "update user_master set  coach_refer_code = '$refer_code' where user_id ='$coach_id'";
        $this->db->query($sql);
        return true;
    }

    function getCoach_id($code) {
        $sql = "select user_id from user_master
		 where coach_refer_code ='$code'";

        $query = $this->db->query($sql);
        $result = $query->row_array();
        return $result['user_id'];
    }

    function getCoach_id_by_referal_code($code, $email, $phone) {
        $sql = "select coach_id from client_refferals
		 where referal_code  ='$code' and (email_address ='$email' or contact_number='$phone')";

        $query = $this->db->query($sql);
        $result = $query->row_array();
        return $result['coach_id'];
    }

    function validate_referal_code($code, $email, $phone) {
        $sql = "select coach_id from client_refferals
		 where referal_code  ='$code'";
        $query = $this->db->query($sql);
        $result = $query->row_array();
        return $result['coach_id'];
    }

    function add_point($coach_id, $client_id) {
        $data = array(
            'coach_id' => $coach_id,
            'client_id' => $client_id,
        );

        $this->db->insert('point_master', $data);
        return $this->db->insert_id();
    }

    #function to save refferal details

    function save_referral_details($arr) {

        $data = array(
            'coach_id' => $arr['coach_id'],
            'email_address' => $arr['email_address'],
            'contact_number' => $arr['contact_number'],
            'refer_date' => date("Y-m-d H:i:s"),
            'referal_code' => $arr['referal_code'],
        );

        $this->db->insert('client_refferals', $data);
        return $this->db->insert_id();
    }

    function add_reffered_coach_id($coach_id, $user_id) {
        $sql = "update user_master set  reffered_coach = '$coach_id' where user_id ='$user_id'";
        $this->db->query($sql);
        return true;
    }

    function Update_referal_score($coach_id, $num) {
        $sql = "update  user_master set  no_of_referals='$num' where user_id ='$coach_id'";
        $this->db->query($sql);
        return true;
    }

    function Update_coach_referal_score($coach_id, $num) {
        $sql = "update  user_master set  no_of_sent_referals='$num' where user_id ='$coach_id'";
        $this->db->query($sql);
        return true;
    }

    function set_coach_experience($arr) {


        $data = array(
            'user_id' => $arr['user_id'],
            'coach_id' => $arr['coach_id'],
            'communication' => $arr['communication'],
            'supportiveness' => $arr['supportiveness'],
            'expertise' => $arr['expertise'],
            'relationship' => $arr['relationship'],
            'overall_experience' => $arr['overall_experience'],
            'added_date' => date("Y-m-d H:i:s")
        );


        $user_id = $arr['user_id'];
        $coach_id = $arr['coach_id'];


        $sql = "Select count(id) as row_count from coach_rating T1 where  T1.user_id ='$user_id' and T1.coach_id='$coach_id'";

        $result = $this->db->query($sql);
        $records = $result->row_array();


        if ($records["row_count"] > 0) {
            $added_date = date("Y-m-d H:i:s");
            $sql = "update coach_rating set communication='" . $arr['communication'] . "',supportiveness ='" . $arr['supportiveness'] . "',expertise='" . $arr['expertise'] . "',relationship='" . $arr['relationship'] . "',overall_experience='" . $arr['overall_experience'] . "',added_date='" . $added_date . "' where user_id ='$user_id' and coach_id='$coach_id' ";
            $query = $this->db->query($sql);
            return true;
        } else {
            $this->db->insert('coach_rating', $data);
            return $this->db->insert_id();
        }
    }

    function set_app_experience($arr) {


        $data = array(
            'user_id' => $arr['user_id'],
            'ease_of_use' => $arr['ease_of_use'],
            'costumer_support' => $arr['costumer_support'],
            'overall_experience' => $arr['overall_experience'],
            'quality' => $arr['quality'],
            'added_date' => date("Y-m-d H:i:s")
        );


        $user_id = $arr['user_id'];



        $sql = "Select count(id) as row_count from app_rating T1 where  T1.user_id ='$user_id' ";

        $result = $this->db->query($sql);
        $records = $result->row_array();


        if ($records["row_count"] > 0) {
            $added_date = date("Y-m-d H:i:s");
            $sql = "update app_rating set ease_of_use='" . $arr['ease_of_use'] . "',costumer_support ='" . $arr['costumer_support'] . "',overall_experience='" . $arr['overall_experience'] . "',quality='" . $arr['quality'] . "',added_date='" . $added_date . "' where user_id ='$user_id'  ";
            $query = $this->db->query($sql);
            return true;
        } else {
            $this->db->insert('app_rating', $data);
            return $this->db->insert_id();
        }
    }

    #function to get coach rating

    function get_coach_experience($user_id, $coach_id) {
        $sql = "select * from  coach_rating where coach_id ='$coach_id' and user_id='$user_id'";
        $query = $this->db->query($sql);
        $result = $query->row_array();
        return $result;
    }

    #function to get app rating

    function get_app_experience($user_id) {
        $sql = "select ease_of_use,costumer_support,quality,overall_experience from  app_rating where user_id='$user_id'";
        $query = $this->db->query($sql);
        $result = $query->row_array();
        return $result;
    }

    function get_progress_details_by_id($progress_id) {
        $sql = "select * from  user_progress_master where  progress_id ='$progress_id'";
        $query = $this->db->query($sql);
        $result = $query->row_array();
        return $result;
    }

    function remove_progress_data($id) {
        $sql = "delete from  user_progress_master where progress_id ='$id'";
        $this->db->query($sql);
        return true;
    }

    function get_archive_detail($id) {
        $sql = "select * from archive_master where archive_id='$id'";
        $query = $this->db->query($sql);
        $result = $query->row_array();
        return $result;
    }

    function update_imagetype($type, $id) {
        $data = array(
            'image_type' => $type
        );

        $this->db->where('archive_id', $id);
        $id = $this->db->update('archive_master', $data);
        return true;
    }

    function get_note_details_by_id($note_id) {
        $sql = "select * from  notes where   	note_id ='$note_id'";
        $query = $this->db->query($sql);
        $result = $query->row_array();
        return $result;
    }

    function remove_note_data($id) {
        $sql = "delete from  notes where note_id ='$id'";
        $this->db->query($sql);
        return true;
    }

}

?>	