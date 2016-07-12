<?php

ini_set("display_errors", "on");

class Test_user extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    function get_category_recursive($parent_id, $gender) {
        $qry = "select * from category WHERE parent_id='$parent_id' and gender='$gender' order by name asc";
        $array = $this->db->query($qry);
        $result = $array->result_array();
        return $result;
    }

    function get_category_recursive1() {
        $qry = "select * from category ";
        $array = $this->db->query($qry);
        $result = $array->result_array();
        return $return;
    }

}

?>