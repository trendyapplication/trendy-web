<?php

error_reporting(1);
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Country_model extends MY_Model {


    public function setTable($table) {
        $this->_table = $table;
    }

    function get_LOCATION() {
        $data = $this->db->query("select * from trendy_city");
        return $data->result_array();
    }

    public function getUserCount($status = '', $key) {
        $sql = "SELECT COUNT(user_id) as num FROM user_master  where 1";
        if ($status)
            $sql .= " AND user_master.active='Y' ";

        if ($key != '') {
            $sql .= " AND (user_master.user_email LIKE '%$key%' )";
        }
        $result = $this->db->query($sql);
        $results = $result->result();
        $num = $results[0]->num ? $results[0]->num : 0;

        return $num;
    }

    function getErrorCount($status = '', $key) {
        $sql = "SELECT COUNT(id) as num FROM error_reporting  where 1";


        if ($key != '') {
            $sql .= " AND (error_reporting.link LIKE '%$key%' )";
        }
        $result = $this->db->query($sql);
        $results = $result->result();
        $num = $results[0]->num ? $results[0]->num : 0;

        return $num;
    }

    public function getBrandCount($status = '', $key) {
        $sql = "SELECT COUNT(id) as num FROM brand_master  where 1";


        if ($key != '') {
            $sql .= " AND (brand_master.brand LIKE '%$key%' )";
        }
        $result = $this->db->query($sql);
        $results = $result->result();
        $num = $results[0]->num ? $results[0]->num : 0;

        return $num;
    }

    function del_brand($id) {
   return  $this->db->delete("trendy_country", array("id" => $id));
    }
	function delete_sate($id) {
   return  $this->db->delete("trendy_city", array("id" => $id));
    }
	function delete_sate_gn($id) {
   return  $this->db->delete("trendy_city", array("state" => $id));
    }

    public function getOccasionCount($status = '', $key) {
        $sql = "SELECT COUNT(occasion_id) as num FROM occasion_master  where 1";
        if ($status)
            $sql .= " AND occasion_master.status='Y' ";

        if ($key != '') {
            $sql .= " AND (occasion_master.name LIKE '%$key%' )";
        }
        $result = $this->db->query($sql);
        $results = $result->result();
        $num = $results[0]->num ? $results[0]->num : 0;

        return $num;
    }

    function getErrorList($status = '', $limit, $start = 0, $key) {
        $start = $start ? $start : 0;
        $sql = "SELECT T1.*,T2.name as posted_by FROM error_reporting T1 left join user_master T2 on T1.user_id=T2.user_id  where 1";
        if ($key != '') {
            $sql .= " AND (T1.link LIKE '%$key%' or T2.name LIKE '%$key%' )";
        }
        $sql .= " GROUP BY T1.id";
        $sql .= " ORDER BY T1.id DESC";
        if ($limit)
            $sql .= " LIMIT $start, $limit";
        $result = $this->db->query($sql);
        $results = $result->result();
        return $results;
    }

    function del_error($arr) {
        return $this->db->delete("error_reporting", $arr);
    }

    function ins_brand($arr) {
        return $this->db->insert("brand_master", $arr);
    }

    function update_state($arr, $con) {

        return $this->db->update("trendy_city", $arr, $con);
		
    }
	  function update_state_post($arr, $con) {

        return $this->db->update("trend_post", $arr, $con);
		
    }
	 function update_state_gn($arr, $con) {
       return $this->db->update("trendy_city", $arr, $con);
	 
    }
	 function update_brand($arr, $con) {
        return $this->db->update("trendy_country", $arr, $con);
    }

    function getPostCount($status = '', $key, $gender) {
        $sql = "SELECT COUNT(T1.id) as num FROM trend_post T1  where 1";

        if ($key != '') {
            $sql .= " AND (T1.product_name LIKE '%$key%' or T1.brand LIKE '%$key%' )";
        }
        if ($gender != '') {
            $sql .= " AND (T1.gender = '$gender' )";
        }
        $result = $this->db->query($sql);
        $results = $result->result();
        $num = $results[0]->num ? $results[0]->num : 0;

        return $num;
    }

    function delete_category($arr, $category_id) {
        $data = $this->db->query("select id from trend_post where product_type='$category_id'");
        $trend_post = $data->result_array();
        foreach ($trend_post as $post) {
            $id = $post['id'];
            $this->db->delete("trend_review", array("post_id" => $id));
            $this->db->delete("trendy_notifications", array("post_id" => $id));
            $this->db->delete("trend_vote", array("post_id" => $id));
            $this->db->delete("post_image", array("post_id" => $id));
            $this->db->delete("report_master", array("product_id" => $id));
            $this->db->delete("trend_post", array("id" => $id));
        }
        return $this->db->delete("category", $arr);
    }

    function getCountryCount( $key) {
        $sql = "SELECT COUNT(id) as num FROM trendy_country  where 1=1 ";


        if ($key != '') {
            $sql .= " AND (name LIKE '%$key%')";
        }
		
        $result = $this->db->query($sql);
        $results = $result->result();
        $num = $results[0]->num ? $results[0]->num : 0;

        return $num;
    }
	 function getstateCount( $key) {
        $sql = "SELECT COUNT(id) as num FROM trendy_city  where 1=1 ";


        if ($key != '') {
            $sql .= " AND (city LIKE '%$key%')";
        }
		
        $result = $this->db->query($sql);
        $results = $result->result();
        $num = $results[0]->num ? $results[0]->num : 0;

        return $num;
    }
     function getstate_gnCount( $key) {
        $sql = "select COUNT(*) as num from (SELECT COUNT(id) as num FROM trendy_city  where state!='' and 1=1 ";


        if ($key != '') {
            $sql .= " AND (city LIKE '%$key%')";
        }
		$sql.=" GROUP BY state) as stat";
		
        $result = $this->db->query($sql);
        $results = $result->result();
        $num = $results[0]->num ? $results[0]->num : 0;

        return $num;
    }
    function get_brand_details($id) {
        $data = $this->db->query("select * from trendy_country where id='$id'");
//        echo $this->db->last_query();
        return $data->row_array();
    }
    function get_state_details($id) {
        $data = $this->db->query("SELECT * FROM trendy_city T1 LEFT JOIN trendy_country T2 ON T1.country = T2.id where T1.id='$id'");
//        echo $this->db->last_query();
        return $data->row_array();
    }
	 function get_state_gn_details($id) {
        $data = $this->db->query("SELECT * FROM trendy_city where id='$id'");
//        echo $this->db->last_query();
        return $data->row_array();
    }
    public function getReviewCount($status = '', $key) {
        $sql = "SELECT COUNT(review_id) as num FROM trend_review  where 1";


        if ($key != '') {
            $sql .= " AND (trend_review.review LIKE '%$key%' )";
        }
        $result = $this->db->query($sql);
        $results = $result->result();
        $num = $results[0]->num ? $results[0]->num : 0;

        return $num;
    }

    public function getReportCount($status = '', $key) {
        $sql = "SELECT COUNT(id) as num FROM report_master  where 1";
        if ($key != '') {
            $sql .= " AND (report_master.content LIKE '%$key%' or report_master.desc LIKE '%$key%' )";
        }
        $result = $this->db->query($sql);
        $results = $result->result();
        $num = $results[0]->num ? $results[0]->num : 0;
        return $num;
    }

    public function getUserLists($status = '', $limit, $start = 0, $key) {
        //echo $status.'==='.$limit.'==='. $start; exit;
        $start = $start ? $start : 0;
        $sql = "SELECT * FROM trendy_country  where 1=1 ";
       
        if ($key != '') {
            $sql .= " AND (name LIKE '%$key%' )";
        }
        $sql .= " GROUP BY id";
       
        if ($limit)
            $sql .= " LIMIT $start, $limit";
			
        $result = $this->db->query($sql);
        $results = $result->result();

        return $results;
    }
	 public function getstateLists($status = '', $limit, $start = 0, $key) {
        //echo $status.'==='.$limit.'==='. $start; exit;
        $start = $start ? $start : 0;
        $sql = "SELECT T1.*,T2.name  FROM trendy_city T1 LEFT JOIN trendy_country T2 ON T1.country = T2.id   where 1=1 ";
       
        if ($key != '') {
            $sql .= " AND (city LIKE '%$key%' or T2.name LIKE '%$key%')";
        }
        $sql .= " GROUP BY T1.id";
       
        if ($limit)
            $sql .= " LIMIT $start, $limit";
			
        $result = $this->db->query($sql);
        $results = $result->result();

        return $results;
    }
	public function getstate_gnLists($status = '', $limit, $start = 0, $key) {
        //echo $status.'==='.$limit.'==='. $start; exit;
        $start = $start ? $start : 0;
        $sql = "SELECT * FROM `trendy_city`  where state!='' and 1=1 ";
       
        if ($key != '') {
            $sql .= " AND (state_name LIKE '%$key%' or state LIKE '%$key%')";
        }
        $sql .= "  GROUP BY state";
       
        if ($limit)
            $sql .= " LIMIT $start, $limit";
			
        $result = $this->db->query($sql);
        $results = $result->result();

        return $results;
    }

    public function getOccasionLists($status = '', $limit, $start = 0, $key) {
        //echo $status.'==='.$limit.'==='. $start; exit;
        $start = $start ? $start : 0;
        $sql = "SELECT occasion_master.* FROM occasion_master where 1";

        if ($status)
            $sql .= " AND occasion_master.status='$status' ";

        if ($key != '') {
            $sql .= " AND (occasion_master.name LIKE '%$key%' )";
        }

        $sql .= " GROUP BY occasion_master.occasion_id";
        $sql .= " ORDER BY occasion_master.occasion_id DESC";

        if ($limit)
            $sql .= " LIMIT $start, $limit";
        $result = $this->db->query($sql);
        $results = $result->result();
        return $results;
    }

    public function getReviewLists($status = '', $limit, $start = 0, $key) {
        $start = $start ? $start : 0;
        $sql = "SELECT T1.*,T2.name as user_name,T3.product_name FROM trend_review T1 left join user_master T2 on T1.user_id=T2.user_id left join trend_post T3 on T1.post_id=T3.id where 1";
        if ($key != '') {
            $sql .= " AND (T1.review LIKE '%$key%' )";
        }
        $sql .= " GROUP BY T1.review_id";
        $sql .= " ORDER BY T1.review_id DESC";
        if ($limit)
            $sql .= " LIMIT $start, $limit";
        $result = $this->db->query($sql);
        $results = $result->result();
        return $results;
    }

    public function getBrandList($status = '', $limit, $start = 0, $key) {
        $start = $start ? $start : 0;
        $sql = "SELECT T1.* FROM brand_master T1  where 1";
        if ($key != '') {
            $sql .= " AND (T1.brand LIKE '%$key%' )";
        }
        $sql .= " GROUP BY T1.id";
        $sql .= " ORDER BY T1.id DESC";
        if ($limit)
            $sql .= " LIMIT $start, $limit";
        $result = $this->db->query($sql);
        $results = $result->result();
        return $results;
    }

    function del_post($arr, $id) {
        $this->db->delete("trend_review", array("post_id" => $id));
        $this->db->delete("trendy_notifications", array("post_id" => $id));
        $this->db->delete("trend_vote", array("post_id" => $id));
        $this->db->delete("post_image", array("post_id" => $id));
        $this->db->delete("report_master", array("product_id" => $id));
        return $this->db->delete("trend_post", $arr);
    }

    function getPostListsByid($id) {
        $sql = "SELECT T1.*,T2.name as user_name,T3.name as occasion_name,T4.name as category_name,T5.brand as brand_name FROM trend_post T1 left join user_master T2 on T1.user_id=T2.user_id left join occasion_master T3 on T1.occasion_id=T3.occasion_id left join category T4 on T1.product_type=T4.category_id left join brand_master T5 on T1.brand=T5.id where T1.id='$id'";
        $data = $this->db->query($sql);
        return $data->row_array();
    }

    function getBrand_List() {
        $data = $this->db->query("select id,brand from brand_master order by id desc");
        return $data->result_array();
    }

    public function get_occasion_post() {
        $data = $this->db->query("select occasion_id,name from occasion_master order by occasion_id desc");
        return $data->result_array();
    }

    function get_cat_list() {
        $data = $this->db->query("select category_id,name from category order by category_id desc");
        return $data->result_array();
    }

    function updatePost($arr, $con) {
        return $this->db->update("trend_post", $arr, $con);
    }

    public function getPostLists($status = '', $limit, $start = 0, $key, $gender) {
        $start = $start ? $start : 0;
        $sql = "SELECT T1.*,T2.name as user_name,T3.name as occasion_name,T4.name as category_name,T5.brand FROM trend_post T1 left join user_master T2 on T1.user_id=T2.user_id left join occasion_master T3 on T1.occasion_id=T3.occasion_id left join category T4 on T1.product_type=T4.category_id left join brand_master T5 on T1.brand=T5.id where 1";
        if ($key != '') {
            $sql .= " AND (T1.product_name LIKE '%$key%' or T1.brand LIKE '%$key%' )";
        }if ($gender != '') {
            $sql .= " AND (T1.gender = '$gender' )";
        }
        $sql .= " GROUP BY T1.id";
        $sql .= " ORDER BY T1.id DESC";
        if ($limit)
            $sql .= " LIMIT $start, $limit";
        $result = $this->db->query($sql);
        $results = $result->result();
        return $results;
    }

    public function getReportList($status = '', $limit, $start = 0, $key) {
        //echo $status.'==='.$limit.'==='. $start; exit;
        $start = $start ? $start : 0;
        $sql = "SELECT T1.*,T2.name as user_name,T3.product_name FROM report_master T1 left join user_master T2 on T1.user_id=T2.user_id left join trend_post T3 on T1.product_id=T3.id where 1";



        if ($key != '') {
            $sql .= " AND (T1.desc LIKE '%$key%' or T1.content LIKE '%$key%' )";
        }

        $sql .= " GROUP BY T1.id";
        $sql .= " ORDER BY T1.id DESC";

        if ($limit)
            $sql .= " LIMIT $start, $limit";
        $result = $this->db->query($sql);
//        echo $this->db->last_query();
        $results = $result->result();
        return $results;
    }

    function del_review($arr) {
        return $this->db->delete("trend_review", $arr);
    }

    function del_report($arr) {
        return $this->db->delete("report_master", $arr);
    }

    public function getCategoryLists($status = '', $limit, $start = 0, $key) {
        //echo $status.'==='.$limit.'==='. $start; exit;
        $start = $start ? $start : 0;
        $sql = "SELECT category.* FROM category where 1";

        if ($status)
            $sql .= " AND category.visible='$status' ";

        if ($key != '') {
            $sql .= " AND (category.name LIKE '%$key%' )";
        }

        $sql .= " GROUP BY category.category_id";
        $sql .= " ORDER BY category.category_id DESC";

        if ($limit)
            $sql .= " LIMIT $start, $limit";
        $result = $this->db->query($sql);
        $results = $result->result();
        return $results;
    }

    function insertOccasion($arr) {
        return $this->db->insert("occasion_master", $arr);
    }

    public function getUserListsByID($member_id) {
        $sql = "select * from member_master where member_id='$member_id' ";
        $result = $this->db->query($sql);
        $results = $result->row_array();
        return $results;
    }

    public function getOccasionListsByid($member_id) {
        $sql = "select * from occasion_master where occasion_id='$member_id' ";
        $result = $this->db->query($sql);
        $results = $result->row_array();
        return $results;
    }

    public function getPreferences($member_id) {
        $this->db->select("*");
        $this->db->from("member_preferences");
        $this->db->join("member_excluded_comapnies", "member_preferences.member_id=member_excluded_comapnies.member_id", "left");
        $this->db->where('member_preferences.member_id', $member_id);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getFeedbacks($member_id) {
        $this->db->select("*");
        $this->db->from("member_feedbacks");
        $this->db->join("member_master", "member_master.member_id=member_feedbacks.given_user");
        $this->db->where('member_feedbacks.member_id', $member_id);
        $result = $this->db->get();
        return $result->result_array();
    }

    public function getPoints($member_id) {
        $sql = "select member_points_log.*,
		m1.*
		from member_points_log 
		left join point_master m1 on m1.point_id = member_points_log.point_id
		where member_points_log.member_id='$member_id' ORDER BY member_points_log.created_date DESC";
        $result = $this->db->query($sql);
        $results = $result->result_array();
        return $results;
    }

    public function getUserData($cond) {
        $sql = "select member_master.*,user_types.type,user_details.telephone,user_details.address,user_details.zipcode,
		user_details.lat,user_details.long,user_details.state,user_details.country,
		(select count(*) from delivery_jobs where awarded_to = member_master.id and status = 'intransit') as transit_hauls
		from member_master 
		left join user_types on user_types.id = member_master.type_id
		left join user_details on user_details.user_id = member_master.id ";
        if (is_array($cond))
            $sql .= "where ";
        $i = 0;
        foreach ($cond as $key => $con) {
            if ($i > 0)
                $sql .= 'and ';
            $sql .= $key . " = '" . $con . "' ";
            $i++;
        }

        $result = $this->db->query($sql);
        return $result->row();
    }

    public function getuser_with_mobile($mobile) {
        $this->db->select("*");
        $this->db->from('member_master');
        $this->db->join('user_details', 'member_master.id=user_details.user_id');
        $this->db->where('member_master.mobile', $mobile);
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->row();
        }
        return null;
    }

    function getFieldNames($table) {
        return $this->db->list_fields($table);
    }

    function updateUser($data) {
        $user_id = $data['id'];
        $arr1 = array_flip($this->getFieldNames('member_master'));
        $member_master = array_intersect_key($data, $arr1);
        if ($this->update($user_id, $member_master)) {
            return $this->getUserData(array('member_master.id' => $user_id));
        }
    }

    public function getUserDetails($id) {
        $this->db->select('user_master.*');
        $this->db->from('user_master');
        $this->db->where('user_master.user_id', $id);
//		$this->db->join('member_profile', 'member_master.member_id = member_profile.member_id','left');
        $result = $this->db->get();
        $resu = $result->result_object();
        return $resu[0];
    }

    public function getUserDetailsFromDeviceId($id) {
        $this->db->select('member_master.*,member_profile.member_dob,member_profile.contact_number,member_profile.location,member_profile.country_code,member_profile.industry');
        $this->db->from('member_master');
        $this->db->where('member_master.device_id', $id);
        $this->db->join('member_profile', 'member_master.member_id = member_profile.member_id', 'left');
        $result = $this->db->get();
        $resu = $result->result_object();
        return $resu[0];
    }

    public function getMatchDetails($id) {
        $this->db->_protect_identifiers = false;
        $this->db->select("member_master.member_id,member_master.headline,member_master.formatted_name,member_master.picture_url,member_profile.contact_number,member_profile.location,member_profile.country_code,member_profile.industry,match_log_master.match_time_from,match_log_master.match_time_to,match_log_master.timezone,SUM(mpl.points) as score");
        $this->db->from('member_master');
        $this->db->where('match_log_master.match_logid', $id);
        $this->db->join('member_profile', 'member_master.member_id = member_profile.member_id', 'left');
        $this->db->join('match_log_master', 'member_master.member_id = match_log_master.member_id', 'left');
        $this->db->join('member_points_log mpl', 'mpl.member_id = member_master.member_id', 'left');
        $result = $this->db->get();
        $resu = $result->result_object();
        return $resu[0];
    }

    public function insertDetails($arr) {
        $this->db->insert('member_profile', $arr);
        return $this->db->insert_id();
    }

    public function updateDtetails($arr, $id) {
        $this->db->update('member_profile', $arr, array('member_id' => $id));
        return $this->db->insert_id();
    }

    public function check_user($id) {
        $this->db->select('*');
        $this->db->from('member_profile');
        $this->db->where('member_id', $id);
        $result = $this->db->get();
        return $result->num_rows();
    }

    public function get_field($table_name = '', $select_field = '', $where_string = '') {
        if (!$table_name || !$select_field)
            return false;
        $this->db->select($field_name);
        $this->db->from($table_name);

        if ($where_string)
            $this->db->where($where_string, NULL, FALSE);
        $result = $this->db->get();
        //echo $this->db->last_query();exit;
        return $result->row_array();
    }

    public function get_fields($table_name = '', $select_field = '', $where_string = '') {
        if (!$table_name || !$select_field)
            return false;
        $this->db->select($field_name);
        $this->db->from($table_name);

        if ($where_string)
            $this->db->where($where_string, NULL, FALSE);

        /* if($where_field && $where_value)		
          $this->db->where($where_field, $where_value); */
        $result = $this->db->get();
        return $result->result_array();
    }

    public function delete_row($table_name = '', $where_string = '') {
        if (!$table_name || !$where_string)
            return false;
        $this->db->where($where_string, NULL, FALSE);
        $result = $this->db->delete($table_name);
        /* if($where_field && $where_value)		
          $this->db->where($where_field, $where_value); */
        return $result;
    }

    public function insert_fields($table_name, $arr) {
        $this->db->insert($table_name, $arr);
        return $this->db->insert_id();
    }

    public function update_fields($table_name, $data) {
        $member_id = $data['member_id'];
        unset($data['member_id']);
        $arr1 = array_flip($this->getFieldNames($table_name));
        $arr = array_intersect_key($data, $arr1);

        $result = $this->db->update($table_name, $arr, array('member_id' => $member_id));
        return $result;
    }

    public function get_preference($id) {

        $sql = 'SELECT mm.gender,mp.contact_number,mr.* FROM member_master mm 
					LEFT JOIN member_profile mp ON mm.member_id = mp.member_id
					LEFT JOIN member_preferences mr ON mm.member_id = mr.member_id
				WHERE mm.member_id = ' . $id;

        $result = $this->db->query($sql);
        $results = $result->result_array();
        return $results[0];
    }

    public function insertEnquiryProfileDetails($arr) {
        $this->db->insert('member_profile', $arr);
        return $this->db->insert_id();
    }

    public function insertEnquiryDetails($arr) {
        $sql = "SELECT * from member_master where email='" . $arr['email'] . "'";
        $result = $this->db->query($sql);
        $results = $result->row_array();
        if (count($results) == 0) {
            $this->db->insert('member_master', $arr);
            return $this->db->insert_id();
        } else {
            return "error";
        }
    }

    public function updateDeviceDetails($profileid, $deviceDetails) {
        $this->removeExistingToken($deviceDetails['device_token']);

        $sql = "UPDATE member_master SET device_token='" . $deviceDetails['device_token'] . "',device_platform='" . $deviceDetails['device_platform'] . "' where member_id = " . $profileid;
        $result = $this->db->query($sql);

        return result;
    }

    public function updateDeviceDetailsbyDeviceId($deviceDetails) {
        $this->removeExistingToken($deviceDetails['device_token']);

        $sql = "UPDATE member_master SET device_token='" . $deviceDetails['deviceToken'] . "',device_platform='" . $deviceDetails['devicePlatform'] . "' where device_id = '" . $deviceDetails['deviceId'] . "'";
        $result = $this->db->query($sql);

        return result;
    }

    public function updatePrevLatLon($data) {
        $sql = "UPDATE member_master SET prev_lat='" . $data['match_latitude'] . "',prev_lon='" . $data['match_longitude'] . "' where member_id = " . $data['member_id'];
        $result = $this->db->query($sql);
        return result;
    }

    public function removeExistingToken($device_token) {
        $sql = "UPDATE member_master SET device_token='' AND device_platform='' where device_token = '" . $device_token . "'";
        $result = $this->db->query($sql);
        return result;
    }

    public function insertEnquiryDetailsLinked($arr) {
        //print_r($arr);exit;
        $sql = "SELECT * from member_master where auth_id='" . $arr['auth_id'] . "' or email='" . $arr['email'] . "'";
        $result = $this->db->query($sql);
        $results = $result->row_array();
        if (count($results) == 0) {
            $this->db->insert('member_master', $arr);
            return $this->db->insert_id();
            return 0;
        } else {
            $this->db->where('auth_id', $arr['auth_id']);
            $this->db->update('member_master', $arr);
            return "error";
        }
    }

    public function getHistory($id) {
        $sql = "SELECT ta.*,mm.formatted_name,mm.picture_url,mm.headline,mf.rating,mp.location,DATE_FORMAT(schedule_timefrom, '%d %b, %Y') matchdate FROM 
				(SELECT *, to_user as matchid FROM venue_sceduled_log WHERE from_user = $id
				UNION
				SELECT *, to_user as matchid FROM venue_sceduled_log WHERE to_user = $id) ta
				LEFT JOIN
				member_master mm ON ta.matchid = mm.member_id
				LEFT JOIN
				member_profile mp ON mp.member_id = ta.matchid
				LEFT JOIN
				member_feedbacks mf ON mf.scheduled_log_id = ta.log_id GROUP BY log_id
			  ";


        $result = $this->db->query($sql);
        return $result->result_array();
    }

    public function get_categoryListsByid($id) {
        $data = $this->db->query("select * from category where category_id='$id'");
        return $data->row();
    }

    public function delete_userdata($user_id) {
        $sql = "DELETE FROM user_master  where user_id = " . $user_id;
        $this->db->query($sql);
        $post = $this->db->query("select id from trend_post where user_id='$user_id'");
        $result = $post->result_array();
        foreach ($result as $res) {
            $post_id = $res['id'];
            $this->db->delete("trend_vote", array("post_id" => $post_id));
            $this->db->delete("user_product_child", array("product_id" => $$post_id));
            $this->db->delete("post_image", array("post_id" => $post_id));
//            $this->db->delete("user_product_child", array("product_id" => $post_idd));
            $this->db->delete("user_product_child", array("product_id" => $post_id));
            $this->db->delete("trend_review", array("post_id" => $post_id));
            $this->db->delete("trend_review", array("post_id" => $post_id));
            $this->db->delete("report_master", array("product_id" => $post_id));
        }
        $this->db->delete("trend_post", array("user_id" => $user_id));
        $this->db->delete("trend_review", array("user_id" => $user_id));
        $this->db->delete("trend_vote", array("user_id" => $user_id));
        $this->db->delete("filter_settings", array("user_id" => $user_id));
        $this->db->delete("user_product_child", array("user_id" => $user_id));
        $this->db->delete("user_settings", array("user_id" => $user_id));
        $this->db->delete("report_master", array("user_id" => $user_id));
        $this->db->delete("review_rate", array("user_id" => $user_id));
        $this->db->delete("tracking_user", array("following" => $user_id));
        $this->db->delete("tracking_user", array("followed_by" => $user_id));
        $this->db->delete("trendy_notifications", array("from_user_id" => $user_id));
        $this->db->delete("trendy_notifications", array("to_user_id" => $user_id));
        $this->db->delete("report_master", array("user_id" => $user_id));
//exit;
        return True;
    }

    function insertCat($data) {
        $this->db->insert("category", $data);
        return $this->db->insert_id();
    }

    function get_parent() {
        $data = $this->db->query("select * from category");
        $parent = $data->result_array();
        $return_array = array();
        $i = 0;
        foreach ($parent as $key => $par) {

            $parent_id = $par['parent_id'];
//            echo "ist ".$parent_id."<br/>";
            if ($parent_id == 0 || $parent_id == '0') {
                $return_array[$i] = $par;
                $i++;
            } else {
                $p1 = $this->db->query("select parent_id from category where category_id='$parent_id'");
//                echo "select parent_id from category where category_id='$parent_id'";
                $p2 = $p1->row_array();
//                print_r($p2);
                if ($p2['parent_id'] == '0' || $p2['parent_id'] == 0) {
//                    echo "second ".$p2['parent_id']."<br/><pre>";

                    $return_array[$i] = $par;
                    $i++;
                }
            }
        }
        return $return_array;
//        print_r( $return_array);
//        exit;
    }
	 function insert_country($arr) {
        return $this->db->insert("trendy_country", $arr);
    }
	 function insert_state($arr) {
        return $this->db->insert("trendy_city", $arr);
    }
	 function insert_state_gn($arr) {
        return $this->db->insert("trendy_city", $arr);
    }

}
