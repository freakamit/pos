<?php

class Permission_model extends CI_Model {

    public function get_modules() {
        $sql = $this->db->get_where('modules', array('parent_id' => '0'))->result();

        $i = 0;
        foreach ($sql as $v):
            $sql[$i]->child = $this->db->get_where('modules', array('parent_id' => $v->id, 'enabled' => '1'))->result();
            $i++;
        endforeach;

        return $sql;
    }

    public function get_group($id) {
        return $this->db->get_where('groups', array('id' => $id))->row();
    }

    public function get_selected($slug, $group_id) {
        if (strpos($slug, '/q/')):
            $slug = str_replace('/q/', '?', $slug);
            $slug = str_replace('/m/', '=', $slug);
        endif;
        
        $sql = $this->db->get_where('permissions', array('module' => $slug, 'group_id' => $group_id))->result();
        if ($sql):
            foreach ($sql as $s):
                $array[$s->roles] = $s->roles;
            endforeach;
            return $array;
        else:
            return false;
        endif;
    }

}
