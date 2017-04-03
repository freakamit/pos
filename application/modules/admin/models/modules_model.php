<?php

class Modules_model extends CI_Model {

    protected $table = 'modules';

    public function get_modules() {
        $sql = $this->db->get_where($this->table, array('parent_id' => '0'))->result();

        $i = 0;
        foreach ($sql as $v):
            $sql[$i]->child = $this->db->get_where($this->table, array('parent_id' => $v->id))->result();
            $i++;
        endforeach;

        return $sql;
    }

    public function get_modules_nav() {
        return $this->db->get_where($this->table, array('enabled' => '1'))->result();
    }

    public function check_child($id) {
        return $this->db->get_where($this->table, array('parent_id' => $id))->result();
    }

    public function check_status($id) {
        return $this->db->get_where($this->table, array('id' => $id))->row();
    }

    public function get_parent() {
        $sql = $this->db->get_where('modules', array('parent_id' => 0))->result();

        $array[NULL] = 'Select Parent Module';
        foreach ($sql as $s):
            $array[$s->id] = $s->name;
        endforeach;

        return $array;
    }

}
