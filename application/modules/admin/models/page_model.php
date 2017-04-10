<?php

class Page_model extends CI_Model {

    var $_table = 'pages';

    public function get_all() {
        return $this->db->select('p.id,p.title,ng.title as nav_group_title,p.slug,p.status')
                        ->from($this->_table . ' as p')
                        ->join('navigation_links as n', 'p.id = n.page_id')
                        ->join('navigation_groups as ng', 'n.navigation_group_id = ng.id')
                        ->get()->result();
    }

    public function get($id) {
        $sql = $this->db->select('*')
                        ->from('pages as p')
                        ->join('navigation_links as n', 'p.id = n.page_id')
                        ->where(array('p.id' => $id))
                        ->get()->row();

        return $sql;
    }

    public function remove_temp_image($id) {
        $sql = $this->db->select('image_id')
                        ->from($this->_table . '_temp_fields')
                        ->where('page_id', $id)
                        ->get()->result();
        foreach ($sql as $s):
            if (isset($s->image_id) && $s->image_id != '0'):
                remove_image($s->image_id);
            endif;
        endforeach;
    }

}
