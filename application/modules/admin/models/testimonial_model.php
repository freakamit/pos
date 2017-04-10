<?php

class Testimonial_model extends CI_Model {

    var $_table = 'testimonial';

    public function get($id) {
        return $this->db->select('*')
                        ->from($this->_table)
                        ->where(array('id' => $id))
                        ->get()->row();
    }

    public function get_all() {
        return $this->db->select('id,name,image,posted_on,status')
                        ->get($this->_table)->result();
    }

}
