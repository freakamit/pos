<?php

class Navigation_model extends CI_Model {

    var $_table = 'navigation';

    public function get_group() {
        $this->db->select('*')
                ->from('navigation_groups');
        if ($this->session->userdata['userdata']['group_id'] != 4):
            $this->db->where('abbrev !=', 'admin_menu');
        endif;
        $sql = $this->db->get()->result();

        $array['choose_group'] = 'Choose Group';
        foreach ($sql as $k => $v):
            $array[$v->id] = $v->title;
        endforeach;
        return $array;
    }

    public function get_all($table, $id = '0') {
        if ($table == 'navigation_groups'):
            $this->db->select('id,title,abbrev');
            $this->db->from($table);
            if ($this->session->userdata['userdata']['group_id'] != 4):
                $this->db->where('abbrev !=', 'admin_menu');
            endif;
        endif;
        if ($table == 'navigation_links'):
            $this->db->select('l.id,l.title,g.title as nav_group,l.url');
            $this->db->from($table . ' as l');
            $this->db->join($this->_table . '_groups as g', 'l.navigation_group_id = g.id');
            $this->db->order_by('g.title');
            $this->db->order_by('l.position');
            $this->db->where('parent', $id);
            if ($this->session->userdata['userdata']['group_id'] != 4):
                $this->db->where('g.abbrev !=', 'admin_menu');
            endif;
        endif;
        return $this->db->get()->result();
    }

    public function get_where($module, $id) {
        return $this->db->get_where($module, array('id' => $id))->row();
    }

    function target() {
        return array(
            '_blank' => 'New Window (_blank)',
            '' => 'Default Window'
        );
    }

    public function get_child_list($id) {
        $sql = $this->db->get_where('navigation_links', array('navigation_group_id' => $id, 'parent' => '0'))->result();
        $array[NULL] = 'Choose Parent Nav';

        if ($sql):
            $i = 0;
            foreach ($sql as $v):
                $sql[$i]->child = $this->db->get_where('navigation_links', array('parent' => $v->id))->result();
                $i++;
            endforeach;
        endif;

        foreach ($sql as $k => $v):
            $array[$v->id] = $v->title;
            if (!empty($v->child)):
                foreach ($v->child as $c):
                    $array[$c->id] = '--' . $c->title;
                endforeach;
            endif;
        endforeach;
        return $array;
    }

    public function get_nav_title($id) {
        $sql = $this->db->select('title')
                ->from($this->_table . '_links')
                ->where('id', $id)
                ->get();

        if ($sql->num_rows() > 0):
            return $sql->row();
        else:
            return '';
        endif;
    }

}
