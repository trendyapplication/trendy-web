<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of crone
 *
 * @author aneesh
 */
class crone extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('users_model');
    }

    function rewards() {
        $data = $this->users_model->get_reward_data();

        foreach ($data as $dat) {
            $user_id = $dat['user_id'];
            $product_id = $dat['id'];
            $flag = $this->users_model->check_log_reward($user_id, $product_id, "product_post");
            if ($flag == 0) {
                $point = 3;
                $arr = array("user_id" => $user_id, "reward" => $point, "reward_type" => "product_post", "product_id" => $product_id);
                $array = array("reward_flag" => "Y");
                $cond = array('id' => $product_id);
                $this->users_model->update_rewards($arr);
                $this->users_model->update_reward_pdt($array, $cond);
            }
        }

        $review_data = $this->users_model->get_review_reward_data();
        foreach ($review_data as $dat) {
//            if ($dat['reward'] == 1) {
            $rev_id = $dat['review_id'];
            $user_id = $dat['user_id'];
            $product_id = $dat['post_id'];
            $point = 1;
            $arrr=array("updated_on"=>date("Y-m-d H:i:s"));
            $coo=array("review_id"=>$rev_id);
            $array = array("reward" => $point, "reward_type" => "review", "product_id" => $product_id, "user_id" => $user_id, "updated_on" => date("Y-m-d H:i:s"));
//            $condition = array("user_id" => $user_id, "product_id" => $product_id, "review_id" => $dat['review_id']);
            $this->users_model->up_reward_review($array,$arrr, $coo);
//            $this->users_model->up_reex();
//            }
        }
    }

}
