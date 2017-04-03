<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Settings_model extends CI_Model {

    var $_table = 'settings';

    public function get($module) {
        $sql = $this->db->order_by('order', 'asc')->get_where($this->_table, array('module' => $module))->result();
//       return $this->db->order_by('order','asc')->get_where($this->_table,array('module'=>$module))->result();

        if ($sql):
            return $sql;
        else:
            return FALSE;
        endif;
    }

}
