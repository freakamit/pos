<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    var $_table = 'users';

    function get_all($type) {
        if($type == 'Customers'):
            $select = 'u.id, up.first_name, up.middle_name, up.last_name,l.name as groups,u.active,u.created_on,u.last_login';
        else:
            $select = 'u.id,u.username,u.email,l.name as groups,u.active,u.created_on,u.last_login';
        endif;
        $sql =  $this->db->select($select)
                ->from($this->_table.' as u')
                ->join($this->_table.'_profile as up','up.user_id = u.id','LEFT')
                ->join('groups as l', 'u.group_id = l.id', 'LEFT')
                ->join('groups_type as gt', 'l.type = gt.id', 'LEFT')
                ->where('gt.name',$type)
                ->where('l.name !=', 'Super Admin')
                ->group_by('u.id')
                ->get()
                ->result();
        return $sql;
    }

    function get($id) {
        return $this->db->select('*')
                        ->from($this->_table . ' as u')
                        ->join('users_profile as p', 'u.id = p.user_id', 'LEFT')
                        ->where(array('u.id' => $id))
                        ->get()->row();
    }

}
