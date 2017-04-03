<?php

/*
  | -------------------------------------------------------------------
  | General Helper
  | -------------------------------------------------------------------
  | This file includes every necessary function that can minimize the code
  | and make the programming faster.
 */

function input_type()
{
    return array(
        'text' => 'Text',
        'upload' => 'File/Image',
        'textarea' => 'Textarea',
        'dropdown' => 'Dropdown',
        'multiselect' => 'Multiselect',
        'checkbox' => 'Checkbox',
        'radio' => 'Radio',
        'datetime' => 'Datetime');
}

function product_ribbon_label()
{
    $ci = &get_instance();
    $sql = $ci->db->select('*')->from('products_label')->get()->result();
    $array[] = 'Choose One';
    foreach ($sql as $k => $v):
        $array[$v->id] = $v->name;
    endforeach;
    return $array;
}

function addonproductlist()
{
    $ci = &get_instance();
    $sql = $ci->db->get_where('addonproducts', array('active' => '1'))->result();

    if ($sql):
        foreach ($sql as $k => $v):
            $array[$v->id] = $v->name;
        endforeach;
        return $array;
    else:
        return FALSE;
    endif;
}

function category_list($for = 'backend')
{
    $ci = &get_instance();
    if ($for == 'vendor'):
        $sql = $ci->db->get_where('category', array('parent_id' => 0, 'type' => 1))->result();
    else:
        $sql = $ci->db->get_where('category', array('parent_id' => 0))->result();
    endif;

    if ($sql):

        $i = 0;

        foreach ($sql as $s):
            $array[$i] = $s;
            $array[$i]->child_category = get_child_categories($s->id);
            $i++;
        endforeach;
        return prepare_category_array($array, $for);
    else:
        return FALSE;
    endif;
}

function prepare_category_array($array, $for)
{
    if ($for == 'forntend'):
        $category[] = 'All Categories';
    else:
        $category[] = 'No Parent Category';
    endif;

    foreach ($array as $a):
        $category[$a->id] = $a->category_name;
        if (!empty($a->child_category)):
            foreach ($a->child_category as $b):
                $category[$b->id] = '- ' . $b->category_name;
                if (!empty($b->child_category)):
                    foreach ($b->child_category as $c):
                        $category[$c->id] = '-- ' . $c->category_name;
                    endforeach;
                endif;
            endforeach;
        endif;
    endforeach;

    return $category;
}

function category_type_list()
{
    $ci = &get_instance();
    $sql = $ci->db->select('*')->from('category_type')->get()->result();
    $array[NULL] = 'Choose One';
    foreach ($sql as $k => $v):
        $array[$v->id] = $v->name;
    endforeach;
    return $array;
}

function display_category_type($id)
{
    $ci = &get_instance();
    $sql = $ci->db->get_where('category_type', array('id' => $id))->row();

    if ($sql):
        return $sql->name;
    else:
        return FALSE;
    endif;
}

function product_attr_list()
{
    $ci = &get_instance();
    $sql = $ci->db->select('*')->from('products_attr')->get()->result();
    $array = array();
    foreach ($sql as $k => $v):
        $array[$v->id] = $v->attribute_code;
    endforeach;
    return $array;
}

function product_attr_set_list()
{
    $ci = &get_instance();
    $sql = $ci->db->select('*')->from('products_attr_set')->get()->result();
    $array[NULL] = 'Select Product Attribute Set';
    foreach ($sql as $k => $v):
        $array[$v->id] = $v->name;
    endforeach;
    return $array;
}

function home_page_slider_option()
{
    $ci = &get_instance();
    $sql = $ci->db->select('*')
        ->from('category_type')->get()->result();

    $i = 0;
    foreach ($sql as $k => $v):
        $sql[$i]->slider_item = $ci->db->select('c.id,c.category_name')
            ->from('category as c')
            ->join('category_home as ch', 'c.id = ch.category_id', 'RIGHT')
            ->order_by('ch.position', 'ASC')
            ->where(array('c.type' => $v->id))->get()->result();
        $i++;
    endforeach;

    return $sql;
}

function advertisement($position, $limit = '')
{
    if ($position == 'side'):
        $position = 1;
    elseif ($position == 'top'):
        $position = 2;
    elseif ($position == 'bottom'):
        $position = 3;
    elseif ($position == 'body'):
        $position = 4;
    endif;
    $ci = &get_instance();
    $sql = $ci->db->select('*')
        ->from('advertisement')
        ->where('position', $position)
        ->where('status', '1');
    if ($limit != ''):
        $sql = $ci->db->limit($limit);
    endif;
    $sql = $ci->db->get()
        ->result();
    if ($sql):
        return $sql;
    else:
        return FALSE;
    endif;
}

//function to send email
function email($d = array(), $datas = '')
{
    $ci = &get_instance();

    $msg = $ci->load->view($d['message'], $datas, TRUE);

    $config = array(
        'mailtype' => 'html',
        'charset' => 'utf-8',
        'priority' => '1'
    );

    $ci->email->initialize($config);

    $ci->email->from(settings('store_email_address'), settings('site_name'));
    $ci->email->to($d['to']);

    $ci->email->subject($d['subject']);
    $ci->email->message($msg);

    //  dumparray($d['attach']);
    if (isset($d['attach'])):
        $ci->email->attach($d['attach']);
    endif;

    $res = $ci->email->send();

    return $res;
}

function format_price($price)
{
    return number_format($price, 2, '.', '');
}

function payment_type()
{
    return array(
        'CASH' => 'CASH',
        'CARD' => 'CARD',
        'PAYTM' => 'PAYTM'
    );
}