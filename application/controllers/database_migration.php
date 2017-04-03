<?php

class Database_migration extends CI_Controller {

    public function index() {
        $category_list = $this->category_list();
        echo '<pre>';
        print_r($category_list);
        echo '</pre>';
    }
    
    public function category_list(){
    $sql = "Select * from `namaste_category`";
    $this->db->query($sql);
    $query = $this->db->get();
    return $query->result();
    }

}
