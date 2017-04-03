<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Filemanager_model extends CI_Model {

    var $_table = 'files_folders';

    public function get_parent_folders() {
        $sql = $this->db->get_where($this->_table, array('parent_id' => 0))->result();

        $i = 0;
        foreach ($sql as $s):
            $sql[$i]->sub_folder = $this->db->get_where($this->_table, array('parent_id' => $s->id))->result();
            $i++;
        endforeach;

        return $sql;
    }

    public function get_current_folder_media($path, $limit, $offset) {
        $sql['folders'] = $this->get_folders($path, $limit, $offset);

        $limit -= count($sql['folders']);

        $sql['files'] = $this->get_files($path, $limit, $offset);

        return $sql;
    }

    public function get_folders($path, $limit, $offset) {
        $sql = $this->db->get_where($this->_table, array('location' => $path))->row();

        $sql = $this->db->select('*')
                ->from($this->_table)
                ->limit($limit, $offset)
                ->where('parent_id', $sql->id)
                ->get()
                ->result();
        return $sql;
    }

    public function get_files($path, $limit, $offset) {
        $sql = $this->db->select('*')
                ->from($this->_table . ' as ff')
                ->join('files as f', 'f.folder_id = ff.id', 'INNER')
                ->limit($limit, $offset)
                ->where('ff.location', $path)
                ->get()
                ->result();

        $i = 0;
        foreach ($sql as $s):
            $sql[$i]->image = show_image($s->id, 'filemanager', '$return');
            $i++;
        endforeach;

        return $sql;
    }

}
