<?php

function total_sales() {
    $ci = & get_instance();
    $ci->db->select('SUM(prodcut_qty) as total_sale');
    $ci->db->from('order_products as op');
    $ci->db->join('checkout as c', 'c.id = op.checkout_id');
    $ci->db->where_in('c.status', array('DELIVERED', 'PENDING'));
    $ci->db->group_by('op.product_id');
    $query = $ci->db->get();
    if ($query->num_rows() > 0) {
        return $query->row('total_sale');
    } else {
        return '';
    }
}

//function to display registered customers
function registered_customers() {
    $ci = & get_instance();
    $query = $ci->db->select('id')->from('users')->where(array('active' => '1', 'group_id' => 3))->get();
    return $query->num_rows();
}

//function to display registered vendors
function registered_vendors() {
    $ci = & get_instance();
    $query = $ci->db->select('id')->from('users')->where(array('active' => '1', 'group_id' => 2))->get();
    return $query->num_rows();
}

function new_order() {
    $ci = & get_instance();
    $ci->db->select('id');
    $ci->db->from('checkout');
    $ci->db->where('status', 'PENDING');
    $query = $ci->db->get();
    return $query->num_rows();
}

function sales_revenue() {
    $ci = & get_instance();
    $from = date('Y-m-d', strtotime('-1 year'));
    $to = date('Y-m-d');
    $sql = "SELECT DATE_FORMAT(c.datefield, '%Y') AS year,DATE_FORMAT(c.datefield, '%m') AS month, DATE_FORMAT(c.datefield, '%b') AS month_abbr, IFNULL(SUM(dc.order_total_price),0) AS total";
    $sql.= " FROM default_calendar as c";
    $sql.= " LEFT JOIN default_checkout as dc on dc.created_at = c.datefield";
    $sql.= " WHERE c.datefield >= '$from' AND c.datefield <= '$to'";
    $sql.= " group by month";
    $query = $ci->db->query($sql);
    return $query->result();
}

function weekly_sales() {
    $ci = & get_instance();
    $from = date('Y-m-d', strtotime('-1 week'));
    $to = date('Y-m-d');
    $sql = "SELECT SUM(dc.order_total_price) AS total";
    $sql.= " FROM default_checkout as dc";
    $sql.= " WHERE dc.created_at >= '$from' AND dc.created_at <= '$to'";
    $query = $ci->db->query($sql);
    return $query->row('total');
}

function weekly_sales_items() {
    $ci = & get_instance();
    $from = date('Y-m-d', strtotime('-1 week'));
    $to = date('Y-m-d');
    $sql = "SELECT SUM(op.prodcut_qty) AS total";
    $sql.= " FROM default_order_products as op";
    $sql.= " LEFT JOIN default_checkout as dc on dc.id = op.checkout_id";
    $sql.= " WHERE dc.created_at >= '$from' AND dc.created_at <= '$to'";
    $query = $ci->db->query($sql);
    return $query->row('total');
}

function get_side_navigation() {
    $ci = & get_instance();

    $query = $ci->db->select('nl.id,nl.title,nl.class,m.slug,m.name')
            ->from('navigation_links as nl')
            ->join('modules as m', 'm.id = nl.module', 'LEFT')
            ->join('navigation_groups as ng', 'ng.id = nl.navigation_group_id', 'LEFT')
            ->order_by('nl.position')
            ->where('ng.abbrev', 'admin_menu')
            ->where('m.enabled', '1')
            ->where('nl.parent', '0')
            ->get()
            ->result();

    $i = 0;
    foreach ($query as $v):
        $query[$i]->child = $ci->db->select()
                ->from('navigation_links as nl')
                ->join('modules as m', 'm.id = nl.module', 'LEFT')
                ->join('navigation_groups as ng', 'ng.id = nl.navigation_group_id', 'LEFT')
                ->order_by('nl.position')
                ->where('ng.abbrev', 'admin_menu')
                ->where('m.enabled', '1')
                ->where('nl.parent', $v->id)
                ->get()
                ->result();
        $i++;
    endforeach;
    return $query;
}


?>
