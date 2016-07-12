<?php

class Client_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    public function validateLogin($username, $password, $device_id, $remember_me) {

        $pswd = ($password);
        $sql = "select * from member_master where BINARY  email='$username' and password='$pswd'  and status='Y'  ";
        $query = $this->db->query($sql);
        $result = $query->row_array();
//echo $this->db->last_query();
        if ($result['member_id'] != '') {
            $sql = "update member_master SET device_id=''  where device_id='$device_id' ";
            $query = $this->db->query($sql);
            $sql = "update member_master SET device_id='$device_id' , remember_me ='$remember_me' where email='$username' and password='$pswd'  ";
            $query = $this->db->query($sql);
            $result['reg_status'] = 'Y';
        } else {
            $result['reg_status'] = 'N';
        }
        return $result;
    }

    public function usernameExits($username) {

        $pswd = md5($password);
        $sql = "select * from member_master where BINARY email='$username' and status <> 'T' ";
        $query = $this->db->query($sql);
        $result = $query->row_array();

        return $result;
    }

    function addUser($arr) {

        $device_id = $arr['device_id'];
        $sql = "update member_master SET device_id=''  where device_id='$device_id' ";
        $query = $this->db->query($sql);


        $this->db->insert('member_master', $arr);
        $last_id = $this->db->insert_id();


        $sql = "select * from member_master where  member_id='$last_id'  ";
        $query = $this->db->query($sql);
        $result = $query->row_array();

        $to_email = $result['email'];
        $name = $result['first_name'] . ' ' . $result['last_name'];

        $config['mailtype'] = 'html';
        $config['charset'] = 'utf-8';

        $this->load->library('email');
        $this->email->initialize($config);

        $email = $this->get_emailByid(35);
        $message = $email['email_template'];
        $message = str_replace('#FULL_NAME#', $name, $message);



        $this->email->from($this->config->item('email_from'));
        $this->email->to($to_email);
        $this->email->subject($email['email_subject']);
        $this->email->message($message);
        $this->email->send();

        return $result;
    }

    function logout($arr) {

        $device_id = $arr['device_id'];
        $user_id = $arr['user_id'];
        $sql = "update member_master SET device_id='' , remember_me='N' where device_id='$device_id' and member_id ='$user_id' ";
        $query = $this->db->query($sql);



        return;
    }

    function getuserDetails($device_id) {

        $sql = "select * from member_master where  device_id='$device_id' and status='Y' and is_block='N'  ";
        $query = $this->db->query($sql);
        $result = $query->row_array();
        if ($result['member_id'] != '') {

            $result['reg_status'] = 'Y';
        } else {
            $result['reg_status'] = 'N';
        }
        return $result;
    }

    public function forgotPassword($arr) {

        $query = $this->db->get_where('member_master', array('email' => $arr['email']));

        $to_email = $arr['email'];

        if ($query->num_rows() > 0) {
            $result = $query->row();

            $name = $result->first_name . ' ' . $result->last_name;
            $uname = $result->email;
            $pwd = $result->password;

            $config['mailtype'] = 'html';
            $config['charset'] = 'utf-8';
            //$config['crlf'] 		 = 		    PHP_EOL;
            //$config['newline']    	 = 		    PHP_EOL;
            $this->load->library('email');
            $this->email->initialize($config);

            $email = $this->get_emailByid(36);
            $message = $email['email_template'];
            $message = str_replace('#FULL_NAME#', $name, $message);
            $message = str_replace('#USERNAME#', $uname, $message);
            $message = str_replace('#PASSWORD#', $pwd, $message);

            $this->email->from($this->config->item('email_from'));
            $this->email->to($to_email);
            $this->email->subject($email['email_subject']);
            $this->email->message($message);
            if ($this->email->send())
                $status['status'] = 'true';
            else
                $status['status'] = 'false';
            $status['message'] = 'An email is sent to ' . $to_email . '. Please check your email.';
            return $status;
        }
        else {
            $status['message'] = 'No account found with that email address. Please try again.';
            $status['status'] = 'false';
            return $status;
        }
    }

    function get_emailByid($id) {
        $this->db->where('email_id', $id);
        $result = $this->db->get('general_emails');
        return $result->row_array();
    }

    function addBookmark($arr) {
        $member_id = $arr['member_id'];
        $place_id = $arr['place_id'];
        $opening_hours = array();
        if ($arr['opening_hours'])
            $opening_hours = explode(',', $arr['opening_hours']);

        unset($arr['opening_hours']);
        $sql = "select * from bookmarks where place_id='$place_id' AND member_id='$member_id' ";
        $query = $this->db->query($sql);
        $result = $query->row_array();
        if (!$result['id']) {

            $this->db->insert('bookmarks', $arr);
            $bookmark_id = $this->db->insert_id();

            $status['message'] = 'Bookmarked';
            $status['status'] = 'Y';
        } else {
            $bookmark_id = $result['id'];
            $status['message'] = 'You have already bookmarked this place.';
            $status['status'] = 'N';
            $this->db->update('bookmarks', $arr, array('place_id' => $place_id, 'member_id' => $member_id));
        }
//        print_r($opening_hours);
        unset($arr);
//        echo ($opening_hours);
        if (count($opening_hours) > 0) {
            for ($i = 0; $i < count($opening_hours); $i++) {

                $day = explode(':', $opening_hours[$i]);


//            if ($day[0] == 'Monday') {
//                $arr['day'] = 1;
//            } else if ($day[0] == 'Tuesday') {
//                $arr['day'] = 2;
//            } else if ($day[0] == 'Wednesday') {
//                $arr['day'] = 3;
//            } else if ($day[0] == 'Thursday') {
//                $arr['day'] = 4;
//            } else if ($day[0] == 'Friday') {
//                $arr['day'] = 5;
//            } else if ($day[0] == 'Saturday') {
//                $arr['day'] = 6;
//            } else if ($day[0] == 'Sunday') {
//                $arr['day'] = 7;
//            }
                $arr['day'] = trim($day[0]);
                $arr['bookmark_id'] = $bookmark_id;
                unset($day[0]);
                $arr['hours'] = implode(':', array_values($day));
                $sql = "select * from opening_hours where  bookmark_id='$bookmark_id' AND day='" . $arr['day'] . "'";
                $query = $this->db->query($sql);
                $result = $query->row_array();
                if (!$result['id']) {
                    $this->db->insert('opening_hours', $arr);
                } else {
                    $this->db->update('opening_hours', $arr, array('bookmark_id' => $bookmark_id, 'day' => $arr['day']));
                }
                unset($arr);
                unset($day);
            }
        }
//        echo $this->db->last_query();
        $sql = "select * from bookmarks where place_id='$place_id' AND member_id='$member_id' ";
        $query = $this->db->query($sql);
        $bookmarks[] = $query->row_array();
        $status['bookmarks'] = $bookmarks;
        return $status;
    }

    function addBookmarkNote($arr) {

        $this->db->insert('bookmarks_notes', $arr);
        $status['message'] = 'Note saved successfully';
        $status['status'] = 'Y';
        $status['note_id'] = $this->db->insert_id();

        return $status;
    }

    function getBookmarkCount($place_id) {

        $sql = "select count(*) from bookmarks where place_id='$place_id' ";
        $query = $this->db->query($sql);
        $result = $query->row_array();
        return $result['count(*)'];
    }

    function getMyBookmarks($arr) {
        $member_id = $arr['member_id'];
        $sort_order = $arr['sort_order'];
        $latitude = $arr['latitude'];
        $longitude = $arr['longitude'];
        $friend_id = $arr['friend_id'];
        $all_friends = $arr['all_friends'];

        $distance = '';
        if ($latitude != '') {
            $sql = "update member_master SET prev_lat='$latitude' , prev_lon='$longitude' where  member_id ='$member_id' ";
            $query = $this->db->query($sql);

            $distance = " , ( 3959 * ACOS( COS( RADIANS( $latitude ) ) * COS( RADIANS( latitude ) ) * COS( RADIANS( longitude ) - RADIANS( $longitude ) ) + SIN( RADIANS( $latitude ) ) * SIN( RADIANS( latitude ) ) ) ) AS distance  ";
        }
        $day = date('l');
        $sql = "select  B.* $distance from bookmarks B  where 1 ";

        if ($friend_id == 0) {
            if ($all_friends == 'Y') {
                $sql.= " AND  member_id IN (select friend_id from member_friends  where member_id = '$member_id' ) ";
            } else {
                $sql.=" AND member_id ='$member_id' ";
            }
        } else {

            if ($sort_order == '')
                $sql.=" AND member_id ='$member_id' ";
            else {
                $sql.= " AND member_id = '$friend_id' ";
            }
        }
//        $sql.= "
//                AND ( CASE 
//                WHEN OH.hours !=''
//                  THEN ( OH.day='$day' )
//
//        END )
//                
//                ";
        $sql.=" group by place_id  ";
        if ($sort_order == 'name')
            $sql.=" order by name ASC ";
        else if ($sort_order == 'rating')
            $sql.=" order by rating DESC ";
        else if ($sort_order == 'distance' && $latitude != '')
            $sql.=" order by distance ASC ";
        else if ($sort_order == 'prox' && $latitude != '')
            $sql.=" order by distance ASC , rating DESC ";
        else
            $sql.=" order by id DESC ";

        $query = $this->db->query($sql);
        $result = $query->result_array();

//        echo $this->db->last_query();
        foreach ($result as $val) {
            $sql = "select  *   from opening_hours  where bookmark_id ='$bookmark_id' AND day='$day' ";
            $query = $this->db->query($sql);
            $hrs = $query->row_array();
            if ($hrs['id'] != '') {
                $val['hours'] = $hrs['hours'];
                $val['day'] = $hrs['day'];
            } else {
                $val['hours'] = '';
                $val['day'] = '';
            }
        }

        return $result;
    }

    function getBookmarksDetail($bookmark_id) {
        $day = date('l');
        $sql = "select * from bookmarks where id='$bookmark_id' ";
        $query = $this->db->query($sql);
        $result = $query->row_array();

        $sql = "select  *   from opening_hours  where bookmark_id ='$bookmark_id' AND day='$day' ";
        $query = $this->db->query($sql);
        $hrs = $query->row_array();
        if ($hrs['id'] != '') {
            $result['hours'] = $hrs['hours'];
            $result['day'] = $hrs['day'];
        } else {
            $result['hours'] = '';
            $result['day'] = '';
        }

//        print_r($result);
        return $result;
    }

    function deleteBookmarkNote($note_id) {

        $sql = "delete from bookmarks_notes where id='$note_id' ";
        $this->db->query($sql);

        return;
    }

    function getMyBookmarks_Note($bookmark_id, $limit, $page) {

        $sql = "select * from bookmarks_notes where bookmark_id='$bookmark_id'  order by id DESC limit $page ,$limit";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        return $result;
    }

    function getAll_MyBookmarks_Note($bookmark_id) {

        $sql = "select * from bookmarks_notes where bookmark_id='$bookmark_id'  order by id DESC";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        return $result;
    }

    function getMyBookmarks_Note_Count($bookmark_id) {

        $sql = "select * from bookmarks_notes where bookmark_id='$bookmark_id'  order by id DESC ";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        return count($result);
    }

    function saveBookmarkRating($arr) {
        $id = $arr['bookmark_id'];
        unset($arr['bookmark_id']);
        $this->db->update('bookmarks', $arr, array('id' => $id));
        return;
    }

    public function searchFriends($search_key, $user_id) {
        $key = mysql_real_escape_string($search_key);
        $sql = "select * from member_master M where M.member_id <> '$user_id' ";
        $sql.= " AND  M.member_id NOT IN (select friend_id from member_friends F where member_id = '$user_id') ";
        if ($key != '')
            $sql.=" AND first_name LIKE '%$key%' ";

        $query = $this->db->query($sql);
        $result = $query->result_array();
        return $result;
    }

    public function getFriends($search_key, $user_id) {
        $key = mysql_real_escape_string($search_key);
        $sql = "select MM.*, count(B.id) as bookmark_count from member_master MM left join bookmarks B on B.member_id = MM.member_id ";
        $sql.= " where MM.member_id <> '$user_id' ";
        $sql.= " AND MM.member_id NOT IN (select friend_id from member_friends F where member_id = '$user_id' ) ";
        if ($key != '')
            $sql.=" AND MM.first_name LIKE '%$key%' ";

        $sql.=" GROUP BY MM.member_id ";
        $sql.=" limit 0,20 ";

        $query = $this->db->query($sql);
        $result = $query->result_array();
//        echo $this->db->last_query();
        return $result;
    }

    public function addFriend($arr) {

        $this->db->insert('member_friends', $arr);
        return;
    }

    public function getUserFriends($user_id) {
        $sql = "select MM.*, count(B.id) as bookmark_count from member_friends F
                left join member_master MM on MM.member_id = F.friend_id 
                left join bookmarks B on B.member_id = F.friend_id ";

        $sql.= " where F.member_id = '$user_id' ";
        $sql.= " GROUP BY F.friend_id  ";
        $sql.= " order by MM.first_name ";


        $query = $this->db->query($sql);
        $result = $query->result_array();
//                echo $this->db->last_query();

        return $result;
    }

    public function getUserData($user_id) {

        $sql = "select MM.*, count(B.id) as bookmark_count from member_master MM
                left join bookmarks B on B.member_id = MM.member_id ";

        $sql.= " where MM.member_id = '$user_id' ";
        $sql.= " GROUP BY MM.member_id  ";


        $query = $this->db->query($sql);
        $result = $query->row_array();
        return $result;
    }

    public function deleteFriend($arr) {

        $member_id = $arr['user_id'];
        $friend_id = $arr['friend_id'];

        $sql = "delete from member_friends where member_id = '$member_id' AND friend_id='$friend_id' ";

        $query = $this->db->query($sql);
        return true;
    }

    public function saveUserLocation($arr) {
        $member_id = $arr['member_id'];
        $latitude = $arr['latitude'];
        $longitude = $arr['longitude'];
        $sql = "update member_master SET prev_lat='$latitude' , prev_lon='$longitude' where  member_id ='$member_id' ";
    }

    public function updateUser($arr) {
        $member_id = $arr['member_id'];
        $email = $arr['email'];

        if ($arr['password'] == '')
            unset($arr['password']);

        $sql = "select * from member_master where  email='$email' and status <> 'T' and member_id <> '$member_id'";
        $query = $this->db->query($sql);
        $result = $query->row_array();
        if ($result['member_id'] == '') {

            $this->db->update('member_master', $arr, array('member_id' => $member_id));

            $result['message'] = 'Details updated sucessfully';
            $result['register_status'] = 'Y';
            $sql = "select *   from member_master MM where member_id = '$member_id'";

            $query = $this->db->query($sql);
            $result['user_data'] = $query->row_array();
        } else {
            $result['message'] = 'Email already exists';
            $result['register_status'] = 'N';
        }
        return $result;
    }

    public function DeleteProfileImage($arr) {

        $member_id = $arr['member_id'];
        $sql = "select * from member_master where member_id = '$member_id'";
        $query = $this->db->query($sql);
        $result = $query->row_array();

        if ($result['member_id'] == '') {

            $img_src = FCPATH . "uploads/";
            if ($result['picture_url'] != '' && file_exists($img_src . $result['picture_url'])) {
                unlink($img_src . $result['picture_url']);
            }
        }
        $arr['picture_url'] = '';
        $this->db->update('member_master', $arr, array('member_id' => $member_id));
        return;
    }

}
?>	

