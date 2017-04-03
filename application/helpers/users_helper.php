<?php

/*
  | -------------------------------------------------------------------
  | Users Helper
  | -------------------------------------------------------------------
  | This file includes every necessary function that can minimize the code
  | and make the programming faster.
 */

//function to display logged in user id

function active_user_id() {
    $ci = & get_instance();
    return $ci->session->userdata['userdata']['user_id'];
}

//function to print array
function check_user_session() {
    $ci = & get_instance();
    if (!isset($ci->session->userdata['userdata'])):
        redirect('admin?redirect=' . uri_string());
    else:
        check_permission();
    endif;
}

//function group permission
function check_permission() {
    $ci = & get_instance();

    //get user group id
    $group_id = $ci->session->userdata['userdata']['group_id'];

    //grant access if id is 4 which is super admin
    if ($group_id == 4):
        return true;
    else:
        //get current module and function name
        $module = $ci->uri->segment(2);
        $function = $ci->uri->segment(3);

        //if module is of order than get 2nd parameter too 
        if ($function == 'delivery'):
            $function = $function . '/' . $ci->uri->segment(4);
        endif;
        if ($function == 'assign'):
            $function = $module . '/' . $function;
        endif;

        //get whole current url.. this is done for query string module like navigation and settings
        $current_url = $_SERVER['REQUEST_URI'];

        //list out all module slug from db
        $db_module = $ci->db->select('slug')->from('modules')->get()->result();

        //if module is of query string, this is done to assign the current type of url {example if current url is of navigation?module=group} this is done to define the current module is navigation?module=group
        foreach ($db_module as $m):
            $modul = substr($current_url, 12);

            if ($modul == $m->slug):
                $module = $m->slug;
            endif;
        endforeach;

        // to indicate that listing and '' is for index page
        if ($function == '' || $function == 'listing'):
            $function = 'index';
        endif;

        //list out all the function available to check permission from modules_function_list
        $function_list = $ci->db->select('function')->from('modules_function_list')->get()->result();

        //this is done to check for only those functions that are listed in from modules_function_list
        //set counter to 0
        $count = 0;
        foreach ($function_list as $fl):
            //if the function to be done is listed on modules_function_list that check permission else return true
            //this is done so that the linnk sent from ajax and any other such as while redirecting from edit to update function 
            if ($fl->function == $function):
                //check if permission is allowed or not
                $sql = $ci->db->get_where('permissions', array('module' => $module, 'roles' => $function, 'group_id' => $group_id))->row();
            
                //grant permission if allowed
                if ($sql):
                    return true;
                else:
                    set_flashdata(array(
                        'status' => 'danger',
                        'message' => '<strong>Permission!</strong> You have no authority to use <strong>"' . ucwords($module) . '"</strong> module'
                    ));

                    redirect('admin/settings/error');
                endif;
            endif;
            $count++;
        endforeach;

        //if count is equal to 0 grant access as for those function permission is not to be send
        if ($count == 0):
            return true;
        endif;

    endif;
}

function gender() {
    return array('' => 'Choose One', 'm' => 'Male', 'f' => 'Female');
}

function groups_type() {
    $ci = & get_instance();
    $sql = $ci->db->get('groups_type')->result();
    $array[''] = 'Choose One';
    foreach ($sql as $k => $v):
        $array[$v->id] = $v->name;
    endforeach;
    return $array;
}

function groups($type) {
    $ci = & get_instance();
    $sql = $ci->db->select('*')
                    ->from('groups')
                    ->where('type', $type)
                    ->where('name !=', 'Super Admin')
                    ->get()->result();
    $array[''] = 'Choose One';
    foreach ($sql as $k => $v):
        $array[$v->id] = $v->name;
    endforeach;
    return $array;
}

function country($id = '') {
    $ci = & get_instance();
    if ($id == ''):
        $sql = $ci->db->select('id,name')
                        ->get('countries')->result();

        $array = array('' => 'Choose Country');
        foreach ($sql as $value):
            $array[$value->id] = $value->name;
        endforeach;

        return $array;
    else:
        return $ci->db->select('name')
                        ->from('countries')
                        ->where('id', $id)->get()->row()->name;
    endif;
}

function state($id, $return = FALSE) {
    $ci = & get_instance();
    if ($return == FALSE):
        $sql = $ci->db->select('id,name')
                        ->from('states')
                        ->where('country_id', $id)->get()->result();

        $array = array('' => 'Choose State');
        foreach ($sql as $value):
            $array[$value->id] = $value->name;
        endforeach;

        return $array;
    else:
        $sql = $ci->db->select('name')
                        ->from('states')
                        ->where('id', $id)->get()->row();

        if ($sql):
            return $sql->name;
        else:
            return FALSE;
        endif;
    endif;
}

function city($id, $return = FALSE) {
    $ci = & get_instance();
    if ($return == FALSE):
        $sql = $ci->db->select('id,name')
                        ->from('cities')
                        ->where('state_id', $id)->get()->result();

        $array = array('' => 'Choose City');
        foreach ($sql as $value):
            $array[$value->id] = $value->name;
        endforeach;

        return $array;
    else:
        $sql = $ci->db->select('name')
                        ->from('cities')
                        ->where('id', $id)->get()->row();

        if ($sql):
            return $sql->name;
        else:
            return FALSE;
        endif;
    endif;
}
//
//function user_list($group_id) {
//    $ci = & get_instance();
//    $sql = $ci->db->select('*')->get_where('users', array('group_id' => $group_id))->result();
//    $array[] = 'Select Vender';
//    foreach ($sql as $k => $v):
//        $array[$v->id] = $v->username;
//    endforeach;
//    return $array;
//}
