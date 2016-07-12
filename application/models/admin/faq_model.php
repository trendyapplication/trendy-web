<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class faq_model extends MY_Model {

    public $_table = 'promocodes';

    public function setTable($table) {
        $this->_table = $table;
    }

    public function adddetails($table, $data) {

        $this->db->insert($table, $data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }
	public function upddata($faq_id,$data) {
	$this->db->where('faq_id', $faq_id);
	$this->db->update('cms_master', $data);
    return;
	}
    public function getPromoCount($key ) {
        $sql = "SELECT COUNT(*) as num FROM cms_master WHERE 1=1";
        if ($key != '') {
            $sql .= " AND (Question LIKE '%$key%')";
        }

        $query = $this->db->query($sql);
        $result = $query->row_array();
        return $result['num'];
    }

    public function getAllFaq($status, $key='', $num, $offset) {


        $sql = "SELECT * FROM  cms_master WHERE 1=1 ";
        if ($key!='') {
		$sql .= " AND (Question LIKE '%$key%')";
        }

        $sql.=" ORDER BY faq_id DESC ";
        if ($offset)
            $sql.=" limit $offset,$num ";
        else
            $sql.=" limit $num ";
        //echo $sql;exit;
        $query = $this->db->query($sql);
		
        return $query->result_array();
    }

    public function get_faq_data($faq_id) {
        $sql = "SELECT * FROM cms_master WHERE faq_id='$faq_id'";
        //echo $sql;exit;
        $result = $this->db->query($sql);
        $results = $result->row_array();
        return $results;
    }
	public function bulkDelete($faq_id){
		
		$sql = "DELETE FROM cms_master 
				WHERE faq_id='$faq_id'";
		$query = $this->db->query($sql);
         return;
}
public function UpdateDetails($table,$data,$where){

			$this->db->where($where);
			$this->db->update($table, $data);
			return true;
			
}


}

?>