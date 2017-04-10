<?php

class Banner_model extends CI_Model {

    var $_table = 'banner';

    public function get($id) {
        return $this->db->select('*')
                        ->from($this->_table)
                        ->where(array('id' => $id))
                        ->get()->row();
    }

    public function get_all() {
        return $this->db->select('id,page_name,image,primary_tag,secondary_tag,type,created_on,status')
                        ->get($this->_table)->result();
    }

}
