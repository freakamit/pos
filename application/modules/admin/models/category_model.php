<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Category_model extends CI_Model {

    var $_table = 'category';

    function get_all($parent_id) {
        $sql = $this->db->select('id,category_name,category_slug,active')
            ->from($this->_table)
            ->where(array('parent_id' => $parent_id))
            ->get()->result();

        return $sql;
    }

    function get($id) {
        return $this->db->get_where($this->_table,array('id'=>$id))->row();
    }

}
