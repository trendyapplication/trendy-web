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

    function find_city($id) {
        $city = $this->db->query("select city from trendy_city where id='$id'");
        $cit = $city->row_array();
        return $cit['city'];
    }

    function check_bot($user_id) {
        $data = $this->db->query("select * from user_master where user_id='$user_id'");
        return $data->row_array();
//        $bot['bot'];
    }

    function logout($user_id) {
        return $this->db->update("user_master", array("device_id" => ''), array("user_id" => $user_id));
    }

    function get_trackingCount($user_id) {
        $track = $this->db->query("select count(*) as tracked_by from tracking_user where followed_by='$user_id' and request_status='accept'");
        $track_by = $track->row_array();
        $tra = $this->db->query("select count(*) as tracking_count from tracking_user where following='$user_id' and request_status='accept'");
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
        $result = $this->db->query("select T1.*,T2.* from trend_review T1 left join trend_post T2 on T1.post_id=T2.id where T1.user_id='$id' order by T1.reviewed_on desc");
        $data = $result->result_array();
        return $data;
    }

    function get_country() {
        $data = $this->db->query("select * from trendy_country order by name asc");
        return $data->result_array();
    }

    function get_city($id) {
        $data = $this->db->query("select T1.*,T2.name as country_name from trendy_city T1 left join trendy_country T2 on T1.country=T2.id  where T1.country='$id' order by T1.city asc");
        return $data->result_array();
    }

    function get_adminMail() {
        $email = $this->db->query("select * from user_master");
    }

    function get_posted_items($id) {
        $result = $this->db->query("select * from trend_post where user_id='$id' order by created_on desc ");
        $data = $result->result_array();

        return $data;
    }

    function get_user_saved_items($id) {
        $result = $this->db->query("select T1.*,T2.* from user_product_child T1 left join trend_post T2 on T1.product_id=T2.id where T1.user_id='$id' order by T1.saved_on desc");
        $data = $result->result_array();
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

    function new_post($array, $im_array) {
        $this->db->insert("trend_post", $array);
        $ins_id = $this->db->insert_id();
        foreach ($im_array as $image) {
            $this->db->insert("post_image", array("image_name" => $image, "post_id" => $ins_id, "inserted_on" => date("Y-m-d H:i:s")));
        }
        return TRUE;
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

    function clear_filter_trend($user_id) {
        $cond = array("user_id" => $user_id);
        $this->db->delete("filter_brand_child_trend", $cond);
        $this->db->delete("filter_type_child_trend", $cond);
        $this->db->delete("filter_settings_trend", $cond);
        return TRUE;
    }

    function clear_filter_occasion($user_id) {
        $cond = array("user_id" => $user_id);
        $this->db->delete("filter_brand_child_occasion", $cond);
        $this->db->delete("filter_type_child_occasion", $cond);
        $this->db->delete("filter_settings_occasion", $cond);
        return TRUE;
    }

    function get_state($country_id) {
        $state = $this->db->query("select state from trendy_city where country='$country_id' GROUP BY state order by state asc");
        return $state->result_array();
    }

    function find_Loc($lattitude, $longitude) {
        $qry = " select *, ( 3959 * acos( cos( radians($lattitude) ) * cos( radians( lat ) ) * cos( radians(`long`) - radians($longitude) ) + sin( radians($lattitude) ) * sin( radians( lat ) ) ) ) AS distance from trendy_city order by distance asc limit 1";
        $data = $this->db->query($qry);
        $row = $data->row_array();
        return $row['city'];
    }
 function find_Loc_id($lattitude, $longitude) {
        $qry = " select *, ( 3959 * acos( cos( radians($lattitude) ) * cos( radians( lat ) ) * cos( radians(`long`) - radians($longitude) ) + sin( radians($lattitude) ) * sin( radians( lat ) ) ) ) AS distance from trendy_city order by distance asc limit 1";
        $data = $this->db->query($qry);
        $row = $data->row_array();
        return $row['id'];
    }

    function get_all_type($city, $gender) {
        $data = array();
        $ret_array = array();
        $super_parent_array = array();
        $type_child_arrray = array();
        $parent_array = array();
        $i = 0;
        $type = $this->db->query("select * from category where parent_id=0 and gender='$gender'");
        //  echo $this->db->last_query()."<br/>";
        $return = $type->result_array();
        foreach ($return as $key => $ret) {
            $cat_id = $ret['category_id'];
            $second = $this->db->query("select * from category where parent_id='$cat_id' and gender='$gender'");
            //echo $this->db->last_query()."<br/>";
            $sec_array = $second->result_array();
            if (sizeof($sec_array) > 0) {
                $j = 0;
                foreach ($sec_array as $key3 => $sec) {
                    $cat_id3 = $sec['category_id'];
                    $third = $this->db->query("select * from category where parent_id='$cat_id3' and gender='$gender'");
                    //   echo $this->db->last_query()."<br/>";
                    $th_array = $third->result_array();
                    if (sizeof($th_array) > 0) {
                        $k = 0;
                        $type_child_arrray = array();
                        foreach ($th_array as $key2 => $third_array) {
                            $thid = $third_array['category_id'];
                            $count3 = $this->db->query("select count(*) as count3 from trend_post where product_type='$thid' and posted_locatn='$city' and gender='$gender'");
                            //         echo $this->db->last_query()."<br/>";
                            $coun3 = $count3->row_array();
                            $cou3 = $coun3['count3'];
                            if ($cou3 > 0) {
                                $type_child_arrray[$k] = $third_array;
                                $k++;
                            }
                        }
                    } else {
                        $count3 = $this->db->query("select count(*) as count3 from trend_post where product_type='$cat_id3' and posted_locatn='$city' and gender='$gender'");
                        //           echo $this->db->last_query()."<br/>";
                        $coun3 = $count3->row_array();
                        $cou3 = $coun3['count3'];
                        if ($cou3 > 0) {
                            $parent_array[$j] = $sec;
                            $parent_array[$j]['type_array'] = array();
                            $j++;
                        }
                    }
                    if (sizeof($type_child_arrray) > 0) {
                        $parent_array[$j] = $sec;
                        $parent_array[$j]['type_array'] = $type_child_arrray;
                        unset($type_child_arrray);
                        $type_child_arrray = array();
                        $j++;
                    }
                }
            } else {
                $count3 = $this->db->query("select count(*) as count3 from trend_post where product_type='$cat_id' and posted_locatn='$city' and gender='$gender'");
                //    echo $this->db->last_query()."<br/>";
                $coun3 = $count3->row_array();
//                  echo $this->db->last_query() . "<br/>";
                $cou3 = $coun3['count3'];
                if ($cou3 > 0) {
                    $super_parent_array[$i] = $ret;
                    $super_parent_array[$i]['parent_array'] = array();
                    $i++;
                }
            }
            if (sizeof($parent_array) > 0) {
                $super_parent_array[$i] = $ret;
                $super_parent_array[$i]['parent_array'] = $parent_array;
                unset($parent_array);
                $parent_array = array();
                $i++;
            }
        }
        return $super_parent_array;
    }

    function get_all_type_occasion($city, $gender, $occasion_id) {
        $data = array();
        $ret_array = array();
        $super_parent_array = array();
        $type_child_arrray = array();
        $parent_array = array();
        $i = 0;
        $type = $this->db->query("select * from category where parent_id=0 and gender='$gender'");
        //  echo $this->db->last_query()."<br/>";
        $return = $type->result_array();
        foreach ($return as $key => $ret) {
            $cat_id = $ret['category_id'];
            $second = $this->db->query("select * from category where parent_id='$cat_id' and gender='$gender'");
            //echo $this->db->last_query()."<br/>";
            $sec_array = $second->result_array();
            if (sizeof($sec_array) > 0) {
                $j = 0;
                foreach ($sec_array as $key3 => $sec) {
                    $cat_id3 = $sec['category_id'];
                    $third = $this->db->query("select * from category where parent_id='$cat_id3' and gender='$gender'");
                    //   echo $this->db->last_query()."<br/>";
                    $th_array = $third->result_array();
                    if (sizeof($th_array) > 0) {
                        $k = 0;
                        $type_child_arrray = array();
                        foreach ($th_array as $key2 => $third_array) {
                            $thid = $third_array['category_id'];
                            $count3 = $this->db->query("select count(*) as count3 from trend_post where product_type='$thid' and posted_locatn='$city' and gender='$gender' and occasion_id='$occasion_id'");
                            //         echo $this->db->last_query()."<br/>";
                            $coun3 = $count3->row_array();
                            $cou3 = $coun3['count3'];
                            if ($cou3 > 0) {
                                $type_child_arrray[$k] = $third_array;
                                $k++;
                            }
                        }
                    } else {
                        $count3 = $this->db->query("select count(*) as count3 from trend_post where product_type='$cat_id3' and posted_locatn='$city' and gender='$gender' and occasion_id='$occasion_id'");
                        //           echo $this->db->last_query()."<br/>";
                        $coun3 = $count3->row_array();
                        $cou3 = $coun3['count3'];
                        if ($cou3 > 0) {
                            $parent_array[$j] = $sec;
                            $parent_array[$j]['type_array'] = array();
                            $j++;
                        }
                    }
                    if (sizeof($type_child_arrray) > 0) {
                        $parent_array[$j] = $sec;
                        $parent_array[$j]['type_array'] = $type_child_arrray;
                        unset($type_child_arrray);
                        $type_child_arrray = array();
                        $j++;
                    }
                }
            } else {
                $count3 = $this->db->query("select count(*) as count3 from trend_post where product_type='$cat_id' and posted_locatn='$city' and gender='$gender'");
                //    echo $this->db->last_query()."<br/>";
                $coun3 = $count3->row_array();
//                  echo $this->db->last_query() . "<br/>";
                $cou3 = $coun3['count3'];
                if ($cou3 > 0) {
                    $super_parent_array[$i] = $ret;
                    $super_parent_array[$i]['parent_array'] = array();
                    $i++;
                }
            }
            if (sizeof($parent_array) > 0) {
                $super_parent_array[$i] = $ret;
                $super_parent_array[$i]['parent_array'] = $parent_array;
                unset($parent_array);
                $parent_array = array();
                $i++;
            }
        }
        return $super_parent_array;
    }

    function get_all_type_trend($city, $gender) {
        $data = array();
        $ret_array = array();
        $super_parent_array = array();
        $type_child_arrray = array();
        $parent_array = array();
        $i = 0;
        $type = $this->db->query("select * from category where parent_id=0 and gender='$gender'");
        $return = $type->result_array();
        foreach ($return as $key => $ret) {
            $cat_id = $ret['category_id'];
            $second = $this->db->query("select * from category where parent_id='$cat_id' and gender='$gender'");
            $sec_array = $second->result_array();
            if (sizeof($sec_array) > 0) {
                $j = 0;
                foreach ($sec_array as $key3 => $sec) {
                    $cat_id3 = $sec['category_id'];
                    $third = $this->db->query("select * from category where parent_id='$cat_id3' and gender='$gender'");
                    $th_array = $third->result_array();
                    if (sizeof($th_array) > 0) {
                        $k = 0;
                        $type_child_arrray = array();
                        foreach ($th_array as $key2 => $third_array) {
                            $thid = $third_array['category_id'];
                            $count3 = $this->db->query("SELECT * FROM (select *,count(*) as count3,((SELECT COUNT(*) FROM trend_vote WHERE trend_post.id=post_id AND vote='up')  - 
(SELECT COUNT(*) FROM trend_vote WHERE trend_post.id=post_id AND vote='down') ) as vote_count from trend_post where product_type='$thid' and posted_locatn='$city' and gender='$gender') as test WHERE vote_count>=20 ");

                            $coun3 = $count3->row_array();
                            $cou3 = $coun3['count3'];
                            if ($cou3 > 0) {
                                $type_child_arrray[$k] = $third_array;
                                $k++;
                            }
                        }
                    } else {
                        $count3 = $this->db->query("SELECT * FROM (select *,count(*) as count3,((SELECT COUNT(*) FROM trend_vote WHERE trend_post.id=post_id AND vote='up')  - 
(SELECT COUNT(*) FROM trend_vote WHERE trend_post.id=post_id AND vote='down') ) as vote_count from trend_post where product_type='$cat_id3' and posted_locatn='$city' and gender='$gender' ) as test WHERE vote_count>=20");
                        $coun3 = $count3->row_array();
//                             echo $this->db->last_query();
                        $cou3 = $coun3['count3'];
//                        echo "<br/>count ".$cou3;
                        if ($cou3 > 0) {
                            $parent_array[$j] = $sec;
                            $parent_array[$j]['type_array'] = array();
                            $j++;
                        }
                    }
                    if (sizeof($type_child_arrray) > 0) {
                        $parent_array[$j] = $sec;
                        $parent_array[$j]['type_array'] = $type_child_arrray;
                        unset($type_child_arrray);
                        $type_child_arrray = array();
                        $j++;
                    }
                }
            } else {
                $count3 = $this->db->query("SELECT * FROM (select *,count(*) as count3,((SELECT COUNT(*) FROM trend_vote WHERE trend_post.id=post_id AND vote='up')  - 
(SELECT COUNT(*) FROM trend_vote WHERE trend_post.id=post_id AND vote='down') ) as vote_count from trend_post where product_type='$cat_id' and posted_locatn='$city' and gender='$gender') as test WHERE vote_count>=20 ");
                $coun3 = $count3->row_array();
                //  echo $this->db->last_query() . "<br/>";
                $cou3 = $coun3['count3'];
                if ($cou3 > 0) {
                    $super_parent_array[$i] = $ret;
                    $super_parent_array[$i]['parent_array'] = array();
                    $i++;
                }
            }
//            echo sizeof($parent_array);
            if (sizeof($parent_array) > 0) {
                $super_parent_array[$i] = $ret;
                $super_parent_array[$i]['parent_array'] = $parent_array;
                unset($parent_array);
                $parent_array = array();
                $i++;
            }
        }
        return $super_parent_array;
    }

    function get_userStatus($id) {
        $data = $this->db->query("select active from user_master where user_id='$id'");
        $return = $data->row_array();
        if ($return['active'] == "Y") {
            $status = "Y";
        } else if ($return['active'] == "N") {
            $status = "N";
        }
        if (sizeof($return) < 1) {
            $status = "E";
        }
        return $status;
    }

    function save_error($arr) {
        return $this->db->insert("error_reporting", $arr);
    }

    function get_city_state($id) {
        $state = $this->db->query("select T1.*,T2.name as country_name from trendy_city T1 left join trendy_country T2 on T1.country=T2.id  where T1.state='$id' order by T1.city asc");
        return $state->result_array();
    }

    function get_image_array($post_id) {
        $data = $this->db->query("select image_name from post_image where post_id='$post_id'");
        return $data->result_array();
    }

    function save_radius($arr, $cond) {
        return $this->db->update("user_settings", $arr, $cond);
    }

    function get_recent_post1($gender, $latti1, $longitude1, $user_id) {
        $city = $this->find_Loc($latti1, $longitude1);
        $lat_long = $this->get_bot_loc($city);
        $lattitude = $lat_long['lat'];
        $longitude = $lat_long['long'];
        $current_time = strtotime("Y-m-d H:i:s");
        $radius = $this->get_radius($user_id); //'50';google_map_search_radius;gmap_srch_radius
        //$radius = $key  = $this->get_google_search_radius(); 
        $lat_range = $radius / 69.172;
        $lon_range = abs($radius / (cos($longitude) * 69.172));
        $min_lat = $lattitude - $lat_range; # $mile_radius would be 5 for our example, $lat is the fixed lattitude for our zip code
        $max_lat = $lattitude + $lat_range;
        $min_lon = $longitude - $lon_range;
        $max_lon = $longitude + $lon_range;
        $settings = $this->get_all_settings($user_id);

        if ($gender != 'both') {
            $qry = "SELECT * FROM (select test.*,price,fileName,( 3959 * acos( cos( radians($lattitude) ) * cos( radians( lat ) ) * cos( radians(`long`) - radians($longitude) ) + sin( radians($lattitude) ) * sin( radians( lat ) ) ) ) AS distance   from (SELECT trend_post.id,((SELECT COUNT(*) FROM trend_vote WHERE trend_post.id=post_id AND vote='up')  - 
(SELECT COUNT(*) FROM trend_vote WHERE trend_post.id=post_id AND vote='down') ) as vote_count FROM trend_post) AS test LEFT JOIN trend_post ON trend_post.id=test.id  where gender='" . $gender . "'";
        } else {
            $qry = "SELECT * FROM (select test.*,price,fileName,( 3959 * acos( cos( radians($lattitude) ) * cos( radians( lat ) ) * cos( radians(`long`) - radians($longitude) ) + sin( radians($lattitude) ) * sin( radians( lat ) ) ) ) AS distance   from (SELECT trend_post.id,((SELECT COUNT(*) FROM trend_vote WHERE trend_post.id=post_id AND vote='up')  - 
(SELECT COUNT(*) FROM trend_vote WHERE trend_post.id=post_id AND vote='down') ) as vote_count FROM trend_post) AS test LEFT JOIN trend_post ON trend_post.id=test.id  where 1  ";
        }
        $b1 = 0;
//        echo'<pre>';
        foreach ($settings['brand'] as $brand) {
            if ($b1 == 0) {
                $qry.=" and ( trend_post.brand='" . $brand['brand_id'] . "'";
                $b1++;
            } else {
                $qry.=" or  trend_post.brand='" . $brand['brand_id'] . "'";
            }
        }
        if ($b1 > 0) {
            $qry.=" )";
        }
        $ty1 = 0;
        foreach ($settings['type'] as $type) {
            if ($ty1 == 0) {
                $qry.=" and ( trend_post.product_type='" . $type['type_id'] . "'";
                $ty1++;
            } else {
                $qry.=" or  trend_post.product_type='" . $type['type_id'] . "'";
            }
        }
        if ($ty1 > 0) {
            $qry.=" )";
        }
        if (isset($settings['price_start']) && isset($settings['price_end']) && $settings['price_end'] != '' && $settings['price_start'] != '') {
            $qry.=" and (  trend_post.price between " . $settings['price_start'] . " and " . $settings['price_end'] . " )";
        }
//        print_r($settings);
        if (isset($settings['pop_start']) && isset($settings['pop_end']) && $settings['pop_start'] != '' && $settings['pop_end'] != '') {
            $qry.=" and vote_count between " . $settings['pop_start'] . " and " . $settings['pop_end'];
        }
        $qry.=" Having (distance <= '$radius' OR distance is null) order by trend_post.id desc ) as table1 GROUP BY id ORDER BY id DESC";

        echo $qry;
//        $qry. "( 3959 * acos( cos( radians($lattitude) ) * cos( radians( lat ) ) * cos( radians( long ) - radians($longitude) ) + sin( radians($lattitude) ) * sin( radians( lat ) ) ) ) AS distance  ";
# here we do a little extra for longitude
        $post = $this->db->query($qry);
        $post_array = $post->result_array();
        $return_array = array();
        $kk = 0;
        foreach ($post_array as $key => $array) {
            $vote = $array['vote_count'];
            if ($vote > -5) {
                $return_array[$kk] = $array;
                $vote_status = $this->get_vote_status($user_id, $array['id']);
                $return_array[$kk]['vote_status'] = $vote_status;
                $return_array[$kk]['vote_count'] = $vote;
                $return_array[$kk]['image_array'] = $this->get_image_array($array['id']);
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
//        sort($array1['vote_count']);
        $array4 = array_merge($array1, $array2, $array3);
//        print_r($array4);
        return $array4;
    }

    function get_all_settings($user_id) {
        $data = $this->db->query("select * from filter_settings where user_id='$user_id'");
        $return = $data->row_array();
        $brand = $this->db->query("select * from filter_brand_child where user_id='$user_id'");
        $return['brand'] = $brand->result_array();
        $type = $this->db->query("select * from filter_type_child where user_id='$user_id'");
        $return['type'] = $type->result_array();
        return $return;
    }

    function get_all_settings_occasion($user_id) {
        $data = $this->db->query("select * from filter_settings_occasion where user_id='$user_id'");
        $return = $data->row_array();
        $brand = $this->db->query("select * from filter_brand_child_occasion where user_id='$user_id'");
        $return['brand'] = $brand->result_array();
        $type = $this->db->query("select * from filter_type_child_occasion where user_id='$user_id'");
        $return['type'] = $type->result_array();
        return $return;
    }

    function get_all_settings_trend($user_id) {
        $data = $this->db->query("select * from filter_settings_trend where user_id='$user_id'");
        $return = $data->row_array();
        $brand = $this->db->query("select * from filter_brand_child_trend where user_id='$user_id'");
        $return['brand'] = $brand->result_array();
        $type = $this->db->query("select * from filter_type_child_trend where user_id='$user_id'");
        $return['type'] = $type->result_array();
        return $return;
    }

    function get_bot_loc($loc) {
        $location = $this->db->query("select * from trendy_city where city='$loc'");
        return $location->row_array();
    }

    function get_recent_post($gender, $latti1, $longitude1, $user_id) {
        $city = $this->find_Loc($latti1, $longitude1);
        $lat_long = $this->get_bot_loc($city);
        $lattitude = $lat_long['lat'];
        $longitude = $lat_long['long'];
        $current_time = strtotime("Y-m-d H:i:s");
        $radius = $this->get_radius($user_id); //'50';google_map_search_radius;gmap_srch_radius
        //$radius = $key  = $this->get_google_search_radius(); 
        $lat_range = $radius / 69.172;
        $lon_range = abs($radius / (cos($longitude) * 69.172));
        $min_lat = $lattitude - $lat_range; # $mile_radius would be 5 for our example, $lat is the fixed lattitude for our zip code
        $max_lat = $lattitude + $lat_range;
        $min_lon = $longitude - $lon_range;
        $max_lon = $longitude + $lon_range;
        $settings = $this->get_all_settings($user_id);
//        print_r($settings);
//        //        exit;


        if ($gender != 'both') {
            $qry = "SELECT * FROM (select test.*,price,fileName,( 3959 * acos( cos( radians($lattitude) ) * cos( radians( lat ) ) * cos( radians(`long`) - radians($longitude) ) + sin( radians($lattitude) ) * sin( radians( lat ) ) ) ) AS distance   from (SELECT trend_post.id,((SELECT COUNT(*) FROM trend_vote WHERE trend_post.id=post_id AND vote='up')  - 
(SELECT COUNT(*) FROM trend_vote WHERE trend_post.id=post_id AND vote='down') ) as vote_count FROM trend_post) AS test LEFT JOIN trend_post ON trend_post.id=test.id  where gender='" . $gender . "'";
        } else {
            $qry = "SELECT * FROM (select test.*,price,fileName,( 3959 * acos( cos( radians($lattitude) ) * cos( radians( lat ) ) * cos( radians(`long`) - radians($longitude) ) + sin( radians($lattitude) ) * sin( radians( lat ) ) ) ) AS distance   from (SELECT trend_post.id,((SELECT COUNT(*) FROM trend_vote WHERE trend_post.id=post_id AND vote='up')  - 
(SELECT COUNT(*) FROM trend_vote WHERE trend_post.id=post_id AND vote='down') ) as vote_count FROM trend_post) AS test LEFT JOIN trend_post ON trend_post.id=test.id  where 1  ";
        }
        $b1 = 0;
//        echo'<pre>';
        foreach ($settings['brand'] as $brand) {
            if ($b1 == 0) {
                $qry.=" and ( trend_post.brand='" . $brand['brand_id'] . "'";
                $b1++;
            } else {
                $qry.=" or  trend_post.brand='" . $brand['brand_id'] . "'";
            }
        }
        if ($b1 > 0) {
            $qry.=" )";
        }
        $ty1 = 0;
        foreach ($settings['type'] as $type) {
            if ($ty1 == 0) {
                $qry.=" and ( trend_post.product_type='" . $type['type_id'] . "'";
                $ty1++;
            } else {
                $qry.=" or  trend_post.product_type='" . $type['type_id'] . "'";
            }
        }
        if ($ty1 > 0) {
            $qry.=" )";
        }
        if (isset($settings['price_start']) && isset($settings['price_end']) && $settings['price_end'] != '' && $settings['price_start'] != '') {
            $qry.=" and (  trend_post.price between " . $settings['price_start'] . " and " . $settings['price_end'] . " )";
        }
//        print_r($settings);
        if (isset($settings['pop_start']) && isset($settings['pop_end']) && $settings['pop_start'] != '' && $settings['pop_end'] != '') {
            $qry.=" and vote_count between " . $settings['pop_start'] . " and " . $settings['pop_end'];
        }
        $qry.=" Having (distance <= '$radius' OR distance is null) order by trend_post.id desc ) as table1 GROUP BY id ORDER BY id DESC";

//        echo $qry;
//        $qry. "( 3959 * acos( cos( radians($lattitude) ) * cos( radians( lat ) ) * cos( radians( long ) - radians($longitude) ) + sin( radians($lattitude) ) * sin( radians( lat ) ) ) ) AS distance  ";
# here we do a little extra for longitude
        $post = $this->db->query($qry);
        $post_array = $post->result_array();
        $return_array = array();
        $kk = 0;
        foreach ($post_array as $key => $array) {
            $vote = $array['vote_count'];
            if ($vote > -10) {
                $return_array[$kk] = $array;
                $vote_status = $this->get_vote_status($user_id, $array['id']);
                $return_array[$kk]['vote_status'] = $vote_status;
                $return_array[$kk]['vote_count'] = $vote;
                $return_array[$kk]['image_array'] = $this->get_image_array($array['id']);
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
//        sort($array1['vote_count']);
        $array4 = array_merge($array1, $array2, $array3);
//        print_r($array4);
        return $array4;
    }

    function note_del($arr) {
        return $this->db->delete("trendy_notifications", $arr);
    }

    function get_trend_post($gender, $latti1, $longitude1, $user_id) {
        $city = $this->find_Loc($latti1, $longitude1);
        $lat_long = $this->get_bot_loc($city);
        $lattitude = $lat_long['lat'];
        $longitude = $lat_long['long'];
        $current_time = strtotime("Y-m-d H:i:s");
        $radius = $this->get_radius($user_id); //'50';google_map_search_radius;gmap_srch_radius
        //$radius = $key  = $this->get_google_search_radius(); 
        $lat_range = $radius / 69.172;
        $lon_range = abs($radius / (cos($longitude) * 69.172));
        $min_lat = $lattitude - $lat_range; # $mile_radius would be 5 for our example, $lat is the fixed lattitude for our zip code
        $max_lat = $lattitude + $lat_range;
        $min_lon = $longitude - $lon_range;
        $max_lon = $longitude + $lon_range;
        $settings = $this->get_all_settings_trend($user_id);
        if ($gender != 'both') {
            $qry = "SELECT * FROM (select test.*,price,fileName,( 3959 * acos( cos( radians($lattitude) ) * cos( radians( lat ) ) * cos( radians(`long`) - radians($longitude) ) + sin( radians($lattitude) ) * sin( radians( lat ) ) ) ) AS distance   from (SELECT trend_post.id,((SELECT COUNT(*) FROM trend_vote WHERE trend_post.id=post_id AND vote='up')  - 
(SELECT COUNT(*) FROM trend_vote WHERE trend_post.id=post_id AND vote='down') ) as vote_count FROM trend_post) AS test LEFT JOIN trend_post ON trend_post.id=test.id  where gender='" . $gender . "' and vote_count >=20";
        } else {
            $qry = "SELECT * FROM (select test.*,price,fileName,( 3959 * acos( cos( radians($lattitude) ) * cos( radians( lat ) ) * cos( radians(`long`) - radians($longitude) ) + sin( radians($lattitude) ) * sin( radians( lat ) ) ) ) AS distance   from (SELECT trend_post.id,((SELECT COUNT(*) FROM trend_vote WHERE trend_post.id=post_id AND vote='up')  - 
(SELECT COUNT(*) FROM trend_vote WHERE trend_post.id=post_id AND vote='down') ) as vote_count FROM trend_post) AS test LEFT JOIN trend_post ON trend_post.id=test.id  where vote_count >=20  ";
        }
        $b1 = 0;
//        echo'<pre>';
        foreach ($settings['brand'] as $brand) {
            if ($b1 == 0) {
                $qry.=" and ( trend_post.brand='" . $brand['brand_id'] . "'";
                $b1++;
            } else {
                $qry.=" or  trend_post.brand='" . $brand['brand_id'] . "'";
            }
        }
        if ($b1 > 0) {
            $qry.=" )";
        }
        $ty1 = 0;
        foreach ($settings['type'] as $type) {
            if ($ty1 == 0) {
                $qry.=" and ( trend_post.product_type='" . $type['type_id'] . "'";
                $ty1++;
            } else {
                $qry.=" or  trend_post.product_type='" . $type['type_id'] . "'";
            }
        }
        if ($ty1 > 0) {
            $qry.=" )";
        }
        if (isset($settings['price_start']) && isset($settings['price_end']) && $settings['price_end'] != '' && $settings['price_start'] != '') {
            $qry.=" and (  trend_post.price between " . $settings['price_start'] . " and " . $settings['price_end'] . " )";
        }
//        print_r($settings);
        if (isset($settings['pop_start']) && isset($settings['pop_end']) && $settings['pop_start'] != '' && $settings['pop_end'] != '') {
            $qry.=" and vote_count between " . $settings['pop_start'] . " and " . $settings['pop_end'];
        }
        $qry.=" Having (distance <= '$radius' OR distance is null) order by trend_post.id desc ) as table1 GROUP BY id ORDER BY id DESC";

//        $qry. "( 3959 * acos( cos( radians($lattitude) ) * cos( radians( lat ) ) * cos( radians( long ) - radians($longitude) ) + sin( radians($lattitude) ) * sin( radians( lat ) ) ) ) AS distance  ";
# here we do a little extra for longitude
        $post = $this->db->query($qry);
        $post_array = $post->result_array();
        $return_array = array();
        $kk = 0;
        foreach ($post_array as $key => $array) {
            $vote = $array['vote_count'];
            if ($vote >= 20) {
                $return_array[$kk] = $array;
                $return_array[$kk]['image_array'] = $this->get_image_array($array['id']);
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

    function get_trend_post1($gender, $lattitude, $longitude, $user_id) {
//        exit;
        $current_time = strtotime("Y-m-d H:i:s");
        $radius = $this->get_radius($user_id); //'50';google_map_search_radius;gmap_srch_radius
        //$radius = $key  = $this->get_google_search_radius(); 
        $lat_range = $radius / 69.172;
        $lon_range = abs($radius / (cos($longitude) * 69.172));
        $min_lat = $lattitude - $lat_range; # $mile_radius would be 5 for our example, $lat is the fixed lattitude for our zip code
        $max_lat = $lattitude + $lat_range;
        $min_lon = $longitude - $lon_range;
        $max_lon = $longitude + $lon_range;
        $settings = $this->get_all_settings($user_id);
//        echo $this->db->last_query();
//        print_r($settings);
//        exit;
        if ($gender != 'both') {
            $qry = "SELECT * FROM (select test.*,price,fileName,( 3959 * acos( cos( radians($lattitude) ) * cos( radians( lat ) ) * cos( radians(`long`) - radians($longitude) ) + sin( radians($lattitude) ) * sin( radians( lat ) ) ) ) AS distance   from (SELECT trend_post.id,((SELECT COUNT(*) FROM trend_vote WHERE trend_post.id=post_id AND vote='up')  - 
(SELECT COUNT(*) FROM trend_vote WHERE trend_post.id=post_id AND vote='down') ) as vote_count FROM trend_post) AS test LEFT JOIN trend_post ON trend_post.id=test.id  where gender='" . $gender . "' and vote_count >=50";
        } else {
            $qry = "SELECT * FROM (select test.*,price,fileName,( 3959 * acos( cos( radians($lattitude) ) * cos( radians( lat ) ) * cos( radians(`long`) - radians($longitude) ) + sin( radians($lattitude) ) * sin( radians( lat ) ) ) ) AS distance   from (SELECT trend_post.id,((SELECT COUNT(*) FROM trend_vote WHERE trend_post.id=post_id AND vote='up')  - 
(SELECT COUNT(*) FROM trend_vote WHERE trend_post.id=post_id AND vote='down') ) as vote_count FROM trend_post) AS test LEFT JOIN trend_post ON trend_post.id=test.id  where vote_count >=50  ";
        }
        $b1 = 0;
//        echo'<pre>';
        foreach ($settings['brand'] as $brand) {
            if ($b1 == 0) {
                $qry.=" and ( trend_post.brand='" . $brand['brand_id'] . "'";
                $b1++;
            } else {
                $qry.=" or  trend_post.brand='" . $brand['brand_id'] . "'";
            }
        }
        if ($b1 > 0) {
            $qry.=" )";
        }
        $ty1 = 0;
        foreach ($settings['type'] as $type) {
            if ($ty1 == 0) {
                $qry.=" and ( trend_post.product_type='" . $type['type_id'] . "'";
                $ty1++;
            } else {
                $qry.=" or  trend_post.product_type='" . $type['type_id'] . "'";
            }
        }
        if ($ty1 > 0) {
            $qry.=" )";
        }
        if (isset($settings['price_start']) && isset($settings['price_end']) && $settings['price_end'] != '' && $settings['price_start'] != '') {
            $qry.=" and (  trend_post.price between " . $settings['price_start'] . " and " . $settings['price_end'] . " )";
        }
//        print_r($settings);
        if (isset($settings['pop_start']) && isset($settings['pop_end']) && $settings['pop_start'] != '' && $settings['pop_end'] != '') {
            $qry.=" and vote_count between " . $settings['pop_start'] . " and " . $settings['pop_end'];
        }
        $qry.=" Having (distance <= '$radius' OR distance is null) order by trend_post.id desc ) as table1 GROUP BY id ORDER BY table1.vote_count DESC";

        echo $qry;
//        $qry. "( 3959 * acos( cos( radians($lattitude) ) * cos( radians( lat ) ) * cos( radians( long ) - radians($longitude) ) + sin( radians($lattitude) ) * sin( radians( lat ) ) ) ) AS distance  ";
# here we do a little extra for longitude
        $post = $this->db->query($qry);
        $post_array = $post->result_array();
        $return_array = array();
        $kk = 0;
        foreach ($post_array as $key => $array) {
            $vote = $array['vote_count'];
            if ($vote >= 50) {
                $return_array[$kk] = $array;
                $return_array[$kk]['image_array'] = $this->get_image_array($array['id']);
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
        $note = $this->db->query("select t1.*, t2.name as user_name ,t2.img_extension, t3.name as from_userName , t4.product_name as product_name from trendy_notifications t1 left join user_master t2 on t1.to_user_id=t2.user_id left join user_master t3 on t1.from_user_id = t3. user_id left join trend_post t4 on t4.id=t1.post_id where t1.to_user_id='$user_id' order by t1.id desc");
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

    function get_user_name($uid) {
        $data = $this->db->query("select name from user_master where user_id='$uid'");
        $return = $data->row_array();
        return $return['name'];
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
        $post_user_id = $this->get_post_onwer($post_id);
        $up1 = $this->db->query("select count(*) as upcount from trend_vote where vote='up' and post_id='" . $post_id . "' and user_id !='" . $post_user_id . "' ");
        $upv1 = $up1->row_array();
        $up_vote1 = $upv1['upcount'];
        $down1 = $this->db->query("select count(*) as upcount from trend_vote where vote='down' and post_id='" . $post_id . "' and user_id !='" . $post_user_id . "'");
        $downc1 = $down1->row_array();
        $down_vote1 = $downc1['upcount'];
        $vote_count1 = $up_vote1 - $down_vote1;
        if ($vote_count1 == 5 || $vote_count1 == 10 || $vote_count1 == 20) {

            if ($post_user_id != $user_id) {
                $post_device_tocken = $this->post_device_tocken($post_user_id);
                if ($vote_count1 == 5 || $vote_count1 == 10) {
                    $message = "Your item has reached " . $vote_count . "  net votes!";
                } else {
                    $message = "Your item has become a trend!";
                }
                $type = "Like";
                $badge_count = 0;
                $notification_status = $this->notofication_status($post_user_id);
                if ($notification_status == "on") {
                    $this->sendPushNotification($message, $post_device_tocken, $badge_count, $arr['post_id'], $type);
                }
                $not_array = array("message" => $message, "to_user_id" => $post_user_id, "from_user_id" => $user_id, "inserted_on" => date("Y-m-d H:i:s"), "type" => "vote", "post_id" => $post_id);
                $this->save_notifications($not_array);
            }
        }
        return $vote_count;
    }

    function notofication_status($uid) {
        $st = $this->db->query("select notifications from user_settings where user_id='" . $uid . "'");
        $status = $st->row_array();
        if (sizeof($status) <= 0) {
            $return = "on";
        } else {
            $return = $status['notifications'];
        }
        return $return;
    }

    function sendPushNotification($message, $deviceToken, $badge_count, $activity_id, $type) {
        $development_mode = 'N';
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
            $fp = stream_socket_client('ssl://gateway.sandbox.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);
        } else {
            $fp = stream_socket_client('ssl://gateway.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);
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
        $data = $this->db->query("select T1.*,T2.name as user_name,T3.product_name,T3.id as post_id,T3.fileNAME,T3.lat,T3.long,T3.posted_locatn  from tracking_user T1 left join user_master T2 on T1.following=T2.user_id left join trend_post T3 on T3.user_id=T1.following where T1.followed_by='$id' and T3.id is not null and T1.request_status='accept' group by T3.id order by T3.id desc");
        $social = $data->result_array();
        $ret_array = array();
        foreach ($social as $key => $soc) {
            $uid = $soc['following'];
//            $user = $this->db->query("select *,id as post_id from trend_post where user_id= $uid");
//            $udata = $user->result_array();
//            foreach ($udata as $key2 => $uda) {
            $up = $this->db->query("select count(*) as upcount from trend_vote where vote='up' and post_id='" . $soc['post_id'] . "'");
            $upv = $up->row_array();
            $up_vote = $upv['upcount'];
            $down = $this->db->query("select count(*) as upcount from trend_vote where vote='down' and post_id='" . $soc['post_id'] . "'");
            $downc = $down->row_array();
            $down_vote = $downc['upcount'];
            $vote = $up_vote - $down_vote;
//                $return_array[$kk]['image_array'] = $this->get_image_array($uda['id']);
            $vote_status = $this->get_vote_status($id, $soc['post_id']);
            $social[$key]['vote_status'] = $vote_status;
            $social[$key]['vote_count'] = $vote;
//                $social[$key]['user_name'] = $soc['user_name1'];
            $earthRadius = 6371000;
            $pdt_lat = $soc['lat'];
            $pdt_long = $soc['long'];
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
            $pdt_distance1 = ($angle * $earthRadius);
            $pdt_distance = $pdt_distance1 / 1000;
//                echo $pdt_distance." ,".$pdt_distance/1000 ." <br/>";
            if ($pdt_distance < 50) {
                $social[$key]["current_location"] = "Y";
            } else {
                $social[$key]["current_location"] = "N";
            }

            $social[$key]["pdt_distance"] = $pdt_distance;

//            $social[$key]['post_array'] = $udata;
//            $ret_array[] = $social;
        }
        return $social;
    }

    function get_social1($id, $latitude, $longitude) {
        $data = $this->db->query("select T1.*,T2.name as user_name1,T3.product_name,T3.id as post_id,T3.fileNAME,T3.lat,T3.long,T3.posted_locatn  from tracking_user T1 left join user_master T2 on T1.following=T2.user_id left join trend_post T3 on T3.user_id=T1.following where T1.followed_by='$id' and T3.id is not null order by T3.id desc");
        $social = $data->result_array();
        echo $this->db->last_query();
        $ret_array = array();
        $jk = 0;
        foreach ($social as $key => $soc) {
            echo $jk++ . "<br/>";
            $uid = $soc['following'];
//            $user = $this->db->query("select *,id as post_id from trend_post where user_id= $uid");
//            $udata = $user->result_array();
//            foreach ($udata as $key2 => $uda) {
            $up = $this->db->query("select count(*) as upcount from trend_vote where vote='up' and post_id='" . $soc['post_id'] . "'");
            $upv = $up->row_array();
            $up_vote = $upv['upcount'];
            $down = $this->db->query("select count(*) as upcount from trend_vote where vote='down' and post_id='" . $soc['post_id'] . "'");
            $downc = $down->row_array();
            $down_vote = $downc['upcount'];
            $vote = $up_vote - $down_vote;
//                $return_array[$kk]['image_array'] = $this->get_image_array($uda['id']);
            $vote_status = $this->get_vote_status($id, $soc['post_id']);
            $social[$key]['vote_status'] = $vote_status;
            $social[$key]['vote_count'] = $vote;
            $social[$key]['user_name'] = $soc['user_name1'];
            $earthRadius = 6371000;
            $pdt_lat = $soc['lat'];
            $pdt_long = $soc['long'];
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
            $pdt_distance1 = ($angle * $earthRadius);
            $pdt_distance = $pdt_distance1 / 1000;
//                echo $pdt_distance." ,".$pdt_distance/1000 ." <br/>";
            if ($pdt_distance < 50) {
                $social[$key]["current_location"] = "Y";
            } else {
                $social[$key]["current_location"] = "N";
            }

            $social[$key]["pdt_distance"] = $pdt_distance;

//            $social[$key]['post_array'] = $udata;
//            $ret_array[] = $social;
        }
        return $social;
    }

    function del_post($arr, $id) {
        $this->db->delete("trend_review", array("post_id" => $id));
        $this->db->delete("trendy_notifications", array("post_id" => $id));
        $this->db->delete("trend_vote", array("post_id" => $id));
        $this->db->delete("post_image", array("post_id" => $id));
        $this->db->delete("report_master", array("product_id" => $id));
        $this->db->delete("user_product_child", array("product_id" => $id));
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

    function get_brand_full_LIST() {
        $data = $this->db->query("select * from brand_master order by brand asc");
        return $data->result_array();
    }

    function get_brand_full($city, $gender) {
        $data = $this->db->query("SELECT * FROM (select T1.*,(SELECT count(*) AS num  FROM trend_post WHERE brand=T1.id and posted_locatn='$city' and gender='$gender' ) AS count_num 
from brand_master T1 left join trend_post T2 on T1.id=T2.brand  GROUP BY T1.brand order by T1.brand asc) as brand_array WHERE count_num!=0");
        return $data->result_array();
    }

    function get_brand_full_trend($city, $gender) {
        $data = $this->db->query("SELECT * FROM (select T1.*,(SELECT count(*) AS num  FROM trend_post WHERE brand=T1.id and posted_locatn='$city' and gender='$gender' ) AS count_num 
from brand_master T1 left join trend_post T2 on T1.id=T2.brand  GROUP BY T1.brand order by T1.brand asc) as brand_array WHERE count_num!=0");
        $result = $data->result_array();
        $ret_array = array();
        //   echo $this->db->last_query();
        foreach ($result as $res) {
            $count3 = $this->db->query("SELECT * FROM (select *,count(*) as count3,((SELECT COUNT(*) FROM trend_vote WHERE trend_post.id=post_id AND vote='up')  - 
(SELECT COUNT(*) FROM trend_vote WHERE trend_post.id=post_id AND vote='down') ) as vote_count from trend_post where brand='" . $res['id'] . "' and posted_locatn='$city' and gender='$gender') as test WHERE vote_count>=20 ");
//           echo $this->db->last_query();
            $coun3 = $count3->row_array();
            $cou3 = $coun3['count3'];
            if ($cou3 > 0) {
                $ret_array[] = $res;
//                $k++;
            }
        }
//        print_r($ret_array);
        return $ret_array;
    }

    function get_brand_full_occasion($city, $gender, $occasion_id) {
        $data = $this->db->query("SELECT * FROM (select T1.*,(SELECT count(*) AS num  FROM trend_post WHERE brand=T1.id and posted_locatn='$city' and gender='$gender' and occasion_id='" . $occasion_id . "' ) AS count_num 
from brand_master T1 left join trend_post T2 on T1.id=T2.brand  GROUP BY T1.brand order by T1.brand asc) as brand_array WHERE count_num!=0");
        $result = $data->result_array();

//        print_r($ret_array);
        return $result;
    }

    function check_settings_filter($id) {
        $data = $this->db->query("select count(*) as count from filter_settings where user_id='$id'");
        $cou = $data->row_array();
        return $cou['count'];
    }

    function check_settings_filter_occasion($id) {
        $data = $this->db->query("select count(*) as count from filter_settings_occasion where user_id='$id'");
        $cou = $data->row_array();
        return $cou['count'];
    }

    function check_settings_filter_trend($id) {
        $data = $this->db->query("select count(*) as count from filter_settings_trend where user_id='$id'");
        $cou = $data->row_array();
        return $cou['count'];
    }

    function save_filter($arr) {
        return $this->db->insert("filter_settings", $arr);
    }

    function save_filter_trend($arr) {
        return $this->db->insert("filter_settings_trend", $arr);
    }

    function save_filter_occasion($arr) {
        return $this->db->insert("filter_settings_occasion", $arr);
    }

    function update_filter($arr, $cond) {
        return $this->db->update("filter_settings", $arr, $cond);
    }

    function update_filter_trend($arr, $cond) {
        return $this->db->update("filter_settings_trend", $arr, $cond);
    }

//    function update_filter_occasion($arr, $cond) {
//        return $this->db->update("filter_settings_occasion", $arr, $cond);
//    }

    function update_filter_occasion($arr, $cond) {
        return $this->db->update("filter_settings_occasion", $arr, $cond);
    }

    function update_filter_occasion_trend($arr, $cond) {
        return $this->db->update("filter_settings_trend", $arr, $cond);
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

    function get_radius($id) {
        $data = $this->db->query("select `radius` from user_settings where user_id='$id'");
        $ret = $data->row_array();
        return $ret['radius'];
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
        $data['image_array'] = $this->get_image_array($pdt_id);
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
        $count = $coun['count'];
        if ($count > 0) {
            $cio1 = $this->db->query("select * from tracking_user where following='$id' and followed_by ='$user_id' order by id desc limit 1");
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

    function get_product_occasion($id, $gender, $lattitude, $longitude, $user_id) {
        $radius = $this->get_radius($user_id);
        $lat_range = $radius / 69.172;
        $lon_range = abs($radius / (cos($longitude) * 69.172));
        $min_lat = $lattitude - $lat_range; # $mile_radius would be 5 for our example, $lat is the fixed lattitude for our zip code
        $max_lat = $lattitude + $lat_range;
        $min_lon = $longitude - $lon_range;
        $max_lon = $longitude + $lon_range;
        $settings = $this->get_all_settings_occasion($user_id);
        if ($gender != 'both') {
            $data = "select *,( 3959 * acos( cos( radians($lattitude) ) * cos( radians( lat ) ) * cos( radians(`long`) - radians($longitude) ) + sin( radians($lattitude) ) * sin( radians( lat ) ) ) ) AS distance   from trend_post where occasion_id='" . $id . "' and gender='$gender'";
        } else {
            $data = "select *,( 3959 * acos( cos( radians($lattitude) ) * cos( radians( lat ) ) * cos( radians(`long`) - radians($longitude) ) + sin( radians($lattitude) ) * sin( radians( lat ) ) ) ) AS distance   from trend_post where occasion_id='$id' ";
        }
        $b1 = 0;
//        echo'<pre>';
        foreach ($settings['brand'] as $brand) {
            if ($b1 == 0) {
                $data.=" and ( trend_post.brand='" . $brand['brand_id'] . "'";
                $b1++;
            } else {
                $data.=" or  trend_post.brand='" . $brand['brand_id'] . "'";
            }
        }
        if ($b1 > 0) {
            $data.=" )";
        }
        $ty1 = 0;
        foreach ($settings['type'] as $type) {
            if ($ty1 == 0) {
                $data.=" and ( trend_post.product_type='" . $type['type_id'] . "'";
                $ty1++;
            } else {
                $data.=" or  trend_post.product_type='" . $type['type_id'] . "'";
            }
        }
        if ($ty1 > 0) {
            $data.=" )";
        }
        if (isset($settings['price_start']) && isset($settings['price_end']) && $settings['price_end'] != '' && $settings['price_start'] != '') {
            $data.=" and ( trend_post.price between " . $settings['price_start'] . " and " . $settings['price_end'] . " )";
        }
        $data.=" Having (distance <= '$radius' OR distance is null)";
//        echo $data;
        $qry = $this->db->query($data);
        return $qry->result_array();
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

    function get_filter_occasion($user_id) {
        $data = $this->db->query("select T1.* from filter_settings_occasion T1 where T1.user_id='$user_id'");
        $return = $data->row_array();
        if (sizeof($return) <= 0) {
            $return['pop_start'] = 0;
            $return['pop_end'] = 0;
            $return['price_start'] = 0;
            $return['price_end'] = 0;
        }
        $data_type = $this->db->query("select T1.*,T2.name as type_name from filter_type_child_occasion T1 left join category T2 on T1.type_id=T2.category_id where T1.user_id='$user_id'");
        $type = $data_type->result_array();
        $return['type_array'] = $type;
        $data_brand = $this->db->query("select T1.*,T2.brand as brand_name from filter_brand_child_occasion T1 left join brand_master T2 on T1.brand_id=T2.id where T1.user_id='$user_id'");
        $brand = $data_brand->result_array();
        $return['brand_array'] = $brand;
        return $return;
    }

    function get_filter_trend($user_id) {
        $data = $this->db->query("select T1.* from filter_settings_trend T1 where T1.user_id='$user_id'");
        $return = $data->row_array();
        if (sizeof($return) <= 0) {
            $return['pop_start'] = 0;
            $return['pop_end'] = 0;
            $return['price_start'] = 0;
            $return['price_end'] = 0;
        }
        $data_type = $this->db->query("select T1.*,T2.name as type_name from filter_type_child_trend T1 left join category T2 on T1.type_id=T2.category_id where T1.user_id='$user_id'");
        $type = $data_type->result_array();
        $return['type_array'] = $type;
        $data_brand = $this->db->query("select T1.*,T2.brand as brand_name from filter_brand_child_trend T1 left join brand_master T2 on T1.brand_id=T2.id where T1.user_id='$user_id'");
        $brand = $data_brand->result_array();
        $return['brand_array'] = $brand;
        return $return;
    }

    function pop_price_status_occasion($latti1, $longitude1, $gender, $user_id, $id) {

        $radius = $this->get_radius($user_id);
        $lat_range = $radius / 69.172;
        $city = $this->find_Loc($latti1, $longitude1);
        $lat_long = $this->get_bot_loc($city);
        $latti1 = $lat_long['lat'];
        $longitude1 = $lat_long['long'];
        $lon_range = abs($radius / (cos($longitude1) * 69.172));
        $min_lat = $latti1 - $lat_range; # $mile_radius would be 5 for our example, $lat is the fixed lattitude for our zip code
        $max_lat = $latti1 + $lat_range;
        $min_lon = $latti1 - $lon_range;
        $max_lon = $longitude1 + $lon_range;
        $settings = $this->get_all_settings_occasion($user_id);
        if ($gender != 'both') {
            $data = "SELECT T1.price, ( 3959 * acos( cos( radians($latti1) ) * cos( radians( lat ) ) * cos( radians(`long`) - radians($longitude1) ) + sin( radians($latti1) ) * sin( radians( lat ) ) ) ) AS distance FROM trend_post T1 WHERE T1.occasion_id=$id and T1.gender='$gender' Having  (distance <= '$radius' OR distance is null) ORDER BY T1.price desc LIMIT 1";
        } else {
            $data = "SELECT T1.price ,( 3959 * acos( cos( radians($latti1) ) * cos( radians( lat ) ) * cos( radians(`long`) - radians($longitude1) ) + sin( radians($latti1) ) * sin( radians( lat ) ) ) ) AS distance   
FROM trend_post T1 
  
   WHERE T1.occasion_id=$id   Having (distance <= '$radius' OR distance is null) ORDER BY T1.price desc LIMIT 1 ";
        }

//        $data.="";
//        $data.=" Having (distance <= '$radius' OR distance is null)";
//        echo $data;
        $post = $this->db->query($data);
//        echo $this->db->last_query();
//        return $qry->result_array();
//         = $this->db->query($qry);
        $post_array = $post->row_array();

        $return['max_price'] = $post_array['price'];

        if ($gender != 'both') {
            $data = "SELECT T1.price, ( 3959 * acos( cos( radians($latti1) ) * cos( radians( lat ) ) * cos( radians(`long`) - radians($longitude1) ) + sin( radians($latti1) ) * sin( radians( lat ) ) ) ) AS distance FROM trend_post T1 WHERE T1.occasion_id=$id and T1.gender='$gender' Having  (distance <= '$radius' OR distance is null) ORDER BY T1.price asc LIMIT 1";
        } else {
            $data = "SELECT T1.price ,( 3959 * acos( cos( radians($latti1) ) * cos( radians( lat ) ) * cos( radians(`long`) - radians($longitude1) ) + sin( radians($latti1) ) * sin( radians( lat ) ) ) ) AS distance   
FROM trend_post T1 
  
   WHERE T1.occasion_id=$id   Having (distance <= '$radius' OR distance is null) ORDER BY T1.price asc LIMIT 1 ";
        }

//        $data.="";
//        $data.=" Having (distance <= '$radius' OR distance is null)";
//        echo $data;
        $post1 = $this->db->query($data);
//        echo $this->db->last_query();
//        return $qry->result_array();
//         = $this->db->query($qry);
        $post_array1 = $post1->row_array();
//        print_r($post_array);
        $return['min_price'] = $post_array1['price'];
//        $return['pop_min'] = $post_array['min_vote'];
//        $return['pop_max'] = $post_array['max_vote'];
//        if ($return['pop_min'] < -10) {
//            $return['pop_min'] = -10;
//        }
//        return $return_array;
        return $return;
    }

    function pop_price_status($lat, $long, $city, $gender) {
        $price_max = $this->db->query("select price from trend_post where posted_locatn='$city' and gender='$gender' order by price desc limit 1");
        $primax = $price_max->row_array();
        $return['max_price'] = $primax['price'];
        $price_max1 = $this->db->query("select price from trend_post where posted_locatn='$city' and gender='$gender' order by price asc limit 1");
        $primax1 = $price_max1->row_array();
        $return['min_price'] = $primax1['price'];
        $pop_mi = $this->db->query("SELECT *, (SELECT COUNT(vote) FROM trend_vote WHERE vote='up' AND post_id=T2.post_id ) -(SELECT COUNT(vote) FROM trend_vote WHERE vote='down' AND post_id=T2.post_id ) as maxCountss
FROM (SELECT id as id1 FROM trend_post WHERE posted_locatn='$city' and gender='$gender') as T1 LEFT JOIN trend_vote T2 ON T1.id1=T2.post_id GROUP BY T2.post_id ORDER BY maxCountss asc limit 1");
        $popMin = $pop_mi->row_array();
        if ($popMin['maxCountss'] > -10) {
            $return['pop_min'] = $popMin['maxCountss'];
        } else {
            $return['pop_min'] = -10;
        }
        $pop = $this->db->query("SELECT *, (SELECT COUNT(vote) FROM trend_vote WHERE vote='up' AND post_id=T2.post_id ) -(SELECT COUNT(vote) FROM trend_vote WHERE vote='down' AND post_id=T2.post_id ) as maxCountss
FROM (SELECT id as id1 FROM trend_post WHERE posted_locatn='$city' and gender='$gender') as T1 LEFT JOIN trend_vote T2 ON T1.id1=T2.post_id GROUP BY T2.post_id ORDER BY maxCountss desc limit 1");
        $pop_array = $pop->row_array();
        $return['pop_max'] = $pop_array['maxCountss'];
        return $return;
    }

    function pop_price_status_trend($latti1, $longitude1, $city, $gender, $user_id) {
        $city = $this->find_Loc($latti1, $longitude1);
        $lat_long = $this->get_bot_loc($city);
        $lattitude = $lat_long['lat'];
        $longitude = $lat_long['long'];
        $current_time = strtotime("Y-m-d H:i:s");
        $radius = $this->get_radius($user_id); //'50';google_map_search_radius;gmap_srch_radius
        //$radius = $key  = $this->get_google_search_radius(); 
        $lat_range = $radius / 69.172;
        $lon_range = abs($radius / (cos($longitude) * 69.172));
        $min_lat = $lattitude - $lat_range; # $mile_radius would be 5 for our example, $lat is the fixed lattitude for our zip code
        $max_lat = $lattitude + $lat_range;
        $min_lon = $longitude - $lon_range;
        $max_lon = $longitude + $lon_range;
        $settings = $this->get_all_settings_trend($user_id);
        if ($gender != 'both') {
            $qry = "SELECT min(price) as min_price,max(price) as max_price,min(vote_count) as min_vote,max(vote_count) as max_vote FROM (select test.*,price,fileName,( 3959 * acos( cos( radians($lattitude) ) * cos( radians( lat ) ) * cos( radians(`long`) - radians($longitude) ) + sin( radians($lattitude) ) * sin( radians( lat ) ) ) ) AS distance   from (SELECT trend_post.id,((SELECT COUNT(*) FROM trend_vote WHERE trend_post.id=post_id AND vote='up')  - 
(SELECT COUNT(*) FROM trend_vote WHERE trend_post.id=post_id AND vote='down') ) as vote_count FROM trend_post) AS test LEFT JOIN trend_post ON trend_post.id=test.id  where gender='" . $gender . "' and vote_count >=20";
        } else {
            $qry = "SELECT min(price) as min_price,max(price) as max_price,min(vote_count) as min_vote,max(vote_count) as max_vote FROM (select test.*,price,fileName,( 3959 * acos( cos( radians($lattitude) ) * cos( radians( lat ) ) * cos( radians(`long`) - radians($longitude) ) + sin( radians($lattitude) ) * sin( radians( lat ) ) ) ) AS distance   from (SELECT trend_post.id,((SELECT COUNT(*) FROM trend_vote WHERE trend_post.id=post_id AND vote='up')  - 
(SELECT COUNT(*) FROM trend_vote WHERE trend_post.id=post_id AND vote='down') ) as vote_count FROM trend_post) AS test LEFT JOIN trend_post ON trend_post.id=test.id  where vote_count >=20  ";
        }
        $b1 = 0;
//        echo'<pre>';
//        foreach ($settings['brand'] as $brand) {
//            if ($b1 == 0) {
//                $qry.=" and ( trend_post.brand='" . $brand['brand_id'] . "'";
//                $b1++;
//            } else {
//                $qry.=" or  trend_post.brand='" . $brand['brand_id'] . "'";
//            }
//        }
//        if ($b1 > 0) {
//            $qry.=" )";
//        }
//        $ty1 = 0;
//        foreach ($settings['type'] as $type) {
//            if ($ty1 == 0) {
//                $qry.=" and ( trend_post.product_type='" . $type['type_id'] . "'";
//                $ty1++;
//            } else {
//                $qry.=" or  trend_post.product_type='" . $type['type_id'] . "'";
//            }
//        }
//        if ($ty1 > 0) {
//            $qry.=" )";
//        }
//        if (isset($settings['price_start']) && isset($settings['price_end']) && $settings['price_end'] != '' && $settings['price_start'] != '') {
//            $qry.=" and (  trend_post.price between " . $settings['price_start'] . " and " . $settings['price_end'] . " )";
//        }
////        print_r($settings);
//        if (isset($settings['pop_start']) && isset($settings['pop_end']) && $settings['pop_start'] != '' && $settings['pop_end'] != '') {
//            $qry.=" and vote_count between " . $settings['pop_start'] . " and " . $settings['pop_end'];
//        }
        $qry.=" Having (distance <= '$radius' OR distance is null) order by trend_post.id desc ) as table1   ";

//        $qry. "( 3959 * acos( cos( radians($lattitude) ) * cos( radians( lat ) ) * cos( radians( long ) - radians($longitude) ) + sin( radians($lattitude) ) * sin( radians( lat ) ) ) ) AS distance  ";
# here we do a little extra for longitude
        $post = $this->db->query($qry);
        $post_array = $post->row_array();
//        print_r($post_array);
        $return['min_price'] = $post_array['min_price'];
        $return['pop_min'] = $post_array['min_vote'];
        $return['pop_max'] = $post_array['max_vote'];
        if ($return['pop_min'] < 20) {
            $return['pop_min'] = 20;
        } if ($return['pop_max'] < 20) {
            $return['pop_max'] = 21;
        }
        $return['max_price'] = $post_array['max_price'];
//        return $return_array;
        return $return;
    }

    function save_filter_child($user_id, $type_arr, $brand_arr) {
        $this->db->delete("filter_brand_child", array("user_id" => $user_id));
        $this->db->delete("filter_type_child", array("user_id" => $user_id));
        if (sizeof($brand_arr) > 0) {
            foreach ($brand_arr as $brand) {
                if ($brand != '') {
                    $this->db->insert("filter_brand_child", array("user_id" => $user_id, "brand_id" => $brand));
                }
            }
        }
        if (sizeof($type_arr) > 0) {
            foreach ($type_arr as $type) {
                if ($type != '') {
                    $this->db->insert("filter_type_child", array("user_id" => $user_id, "type_id" => $type));
                }
            }
        }
        return TRUE;
    }

    function save_filter_child_trend($user_id, $type_arr, $brand_arr) {
        $this->db->delete("filter_brand_child_trend", array("user_id" => $user_id));
        $this->db->delete("filter_type_child_trend", array("user_id" => $user_id));
        if (sizeof($brand_arr) > 0) {
            foreach ($brand_arr as $brand) {
                if ($brand != '') {
                    $this->db->insert("filter_brand_child_trend", array("user_id" => $user_id, "brand_id" => $brand));
                }
            }
        }
        if (sizeof($type_arr) > 0) {
            foreach ($type_arr as $type) {
                if ($type != '') {
                    $this->db->insert("filter_type_child_trend", array("user_id" => $user_id, "type_id" => $type));
                }
            }
        }
        return TRUE;
    }

    function save_filter_child_occasion($user_id, $type_arr, $brand_arr) {
        $this->db->delete("filter_brand_child_occasion", array("user_id" => $user_id));
        $this->db->delete("filter_type_child_occasion", array("user_id" => $user_id));
        if (sizeof($brand_arr) > 0) {
            foreach ($brand_arr as $brand) {
                if ($brand != '') {
                    $this->db->insert("filter_brand_child_occasion", array("user_id" => $user_id, "brand_id" => $brand));
                }
            }
        }
        if (sizeof($type_arr) > 0) {
            foreach ($type_arr as $type) {
                if ($type != '') {
                    $this->db->insert("filter_type_child_occasion", array("user_id" => $user_id, "type_id" => $type));
                }
            }
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
            'Year' => 31536000,
            'Month' => 2628000,
            'Week' => 604800,
            'Day' => 86400,
            'Hour' => 3600,
            'Minute' => 60,
            'Second' => 1);

        if ($difference < 10) { // less than 10 seconds ago, let's say "just now"
            $retval = "Just now";
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
        $data = $this->db->query("select * from tracking_user where followed_by='$id' and request_status='accept' ");
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

    function update_track($arr, $cond) {
        return $this->db->delete("tracking_user", $cond);
    }

    function get_trackers_list($id, $uid) {
        $data = $this->db->query("select * from tracking_user where following='$id' and request_status='accept'");
        $return = $data->result_array();
        foreach ($return as $key => $ret) {
            $fid = $ret['followed_by'];
//            echo $fid."<br>";
            $track = $this->db->query("select * from tracking_user where following='$fid' and followed_by='$uid'");
            $track_status = $track->row_array();
            if (sizeof($track_status) > 0) {
                $return[$key]['request_status'] = $track_status['request_status'];
            } else {
                $return[$key]['request_status'] = "Not Tracking";
            }
            $user = $this->db->query("SELECT T1.* , T2.profile FROM user_master T1 LEFT JOIN user_settings T2 ON T1.user_id=T2.user_id where T1.user_id='$fid'");
//           echo $this->db->last_query()."<br/>";
            $user_data = $user->row_array();
//            if(sizeof($var, $mode))
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
        $result = $data->row_array();
        return $result;
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

    function delete_review($array) {
        return $this->db->delete("trend_review", $array);
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
        $this->db->where('email_id', $id);
        $result = $this->db->get('email_config');
        return $result->row_array();
    }

    function getClient_answered_qns($user_id) {
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

    public function getAdminEmail() {
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