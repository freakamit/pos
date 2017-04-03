<?php

class Admin_model extends CI_Model {

    public function login($username, $password) {
        $this->db->select('u.*,up.*');
        $this->db->from('users as u');
        $this->db->join('users_profile as up', 'u.id = up.user_id');
        $this->db->join('groups as g', 'g.id = u.group_id', 'LEFT');
        $this->db->join('groups_type as gt', 'gt.id = g.type', 'LEFT');
        $this->db->where('gt.name', 'Core Users');
        $this->db->where('u.username', $username);
        $this->db->or_where('u.email', $username);
        $this->db->limit(1);
        $query = $this->db->get();
        
        if ($query->num_rows() == 1):
            $this->load->helper('common');
            $user = $query->result();
            $hash = make_hash(array($password, $user[0]->salt));
            if ($hash == $user[0]->password):
                return $user;
            else:
                return false;
            endif;
        else:
            return false;
        endif;
    }

}
