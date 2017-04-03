<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Groups_model extends CI_Model {

    var $_table = 'groups';

    function get_all() {
        return $this->db->select('g.id,g.name,g.description,l.name as type')
                        ->from($this->_table . ' as g')
                        ->join('groups_type as l', 'g.type = l.id', 'LEFT')
                        ->where('g.name !=', 'Super Admin')
                        ->get()->result();
    }

    function get($id) {
        return $this->db->get_where($this->_table, array(
                    'id' => $id
                ))->row();
    }

}
