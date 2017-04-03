<?php

/*
  | -------------------------------------------------------------------
  | Common Helper
  | -------------------------------------------------------------------
  | This file includes every necessary function that can minimize the code
  | and make the programming faster.
 */

//function to print array
function dumparray($array) {
    echo '<pre>';
    print_r($array);
    echo '</pre>';
    die();
}

//function to print last SQL query
function printQuery() {
    $ci = & get_instance();

    echo $ci->db->last_query();
    die();
}

//function to get the last insert data id
function insert_id() {
    $ci = & get_instance();

    return $ci->db->insert_id();
}

//function to encrypt string
function make_hash($datas = array()) {
    $string = 'FGSbgNwln1AvBUDr';
    foreach ($datas as $data):
        $string .= $data;
    endforeach;
    return sha1($string);
}

//function to generate random alpha numeric number
function generate_rand($length = 6) {
    return random_string('alnum', $length);
}

//function to unset unnecessary array 
function filter_array($datas, $filter = array()) {
    $array = $datas;
    //dumparray($array[2]);
    foreach ($filter as $f):
        unset($array[$f]);
    endforeach;
    return $array;
}

//functio to load the admin view files
function template($templete, $data) {
    $ci = & get_instance();
    $temp = array(
        'template/header',
        'template/header_nav',
        'template/left_nav',
        'template/search',
        'template/breadcrumb',
        $templete,
        'template/model',
        'template/left_nav_2',
        'template/right_nav',
        'template/modal',
        'template/footer'
    );
    foreach ($temp as $t):
        $ci->load->view($t, $data);
    endforeach;
}

//function to load the publish status
function status() {
    return array(
        '0' => 'Disable',
        '1' => 'Enable'
    );
}

function display_status($status) {
    if ($status == 1):
        return '<div class="label bg-success">Active</div>';
    else:
        return '<div class="label bg-danger">In-Active</div>';
    endif;
}

function display_discount_type($status) {
    if ($status == 1):
        return '<div class="label bg-success">Percentage</div>';
    else:
        return '<div class="label bg-danger">Amount</div>';
    endif;
}

//function to set the alert dialog box
function set_flashdata($data) {
    $ci = & get_instance();
    $msg = '<div class="alert bg-' . $data['status'] . '">' . $data['message'] . '</div>';
    $ci->session->set_flashdata('alert', $msg);
    return true;
}

//functio to save data in database
function save($table, $data) {
    $ci = & get_instance();
    $ci->db->insert($table, $data);
    return $ci->db->insert_id();
}

//functio to update data in database
function update($table, $con, $data) {
    $ci = & get_instance();
    $update = $ci->db->where($con['key'], $con['value'])->update($table, $data);
    return true;
}

//functio to delete data from database
function delete($table, $con) {
    $ci = & get_instance();
    $delete = $ci->db->where($con['key'], $con['value'])->delete($table);
    return true;
}

//function to retrieve data from database
function get_by($select = '', $table, $where = array(), $return = FALSE, $order = '', $limit = '', $start = 0) {
    $ci = & get_instance();
    $ci->load->database();

    if ($order != ''):
        $ci->db->order_by($order);
    endif;
    if ($limit != ''):
        $ci->db->limit($limit, $start);
    endif;
    if ($select != ''):
        $ci->db->select($select);
    endif;
    if (!empty($where)):
        $sql = $ci->db->get_where($table, $where);
    else:
        $sql = $ci->db->get($table);
    endif;
    if ($return == TRUE):
        return $sql->result();
    else:
        return $sql->row();
    endif;
}

//function validation
function check_duplication($table, $where, $group_id = '') {
    $ci = & get_instance();
    $ci->load->database();
    if ($group_id == ''):
        $sql = $ci->db->get_where($table, $where)->num_rows();
    else:
        $sql = $ci->db->select('t.*')
                        ->from($table . ' as t')
                        ->join('groups as g', 't.group_id = g.id')
                        ->where('g.type', $group_id)
                        ->where($where)
                        ->get()->num_rows();
    endif;

    if ($sql < 1):
        return TRUE;
    else:
        return FALSE;
    endif;
}

//function to convert date into database format
function db_format_date($date) {
    return date('Y-m-d', strtotime($date));
}

//function to convert date into user format
function user_format_date($date) {
    return date(settings('date_format'), $date);
}

//function to generate slug
function get_slug($value) {
    $slug = strtolower(str_replace(' ', '_', $value));
    $slug = strtolower(str_replace("'", '_', $slug));
    $slug = strtolower(str_replace("-", '_', $slug));

    return $slug;
}

function settings($slug, $return = FALSE) {
    $ci = & get_instance();

    $sql = $ci->db->get_where('settings', array('slug' => $slug))->row();
    if ($sql):
        if ($return == FALSE):
            return $sql->value;
        else:
            return $sql;
        endif;
    else:
        return false;
    endif;
}

function slug($str, $con = '_') {
    return url_title($str, $con, TRUE);
}

function unslug($slug, $con = '_') {
    return ucfirst(str_replace($con, ' ', $slug));
}
