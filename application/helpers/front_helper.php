<?php

/*
  | -------------------------------------------------------------------
  | Front Helper
  | -------------------------------------------------------------------
  | This file includes every necessary function that can minimize the code
  | and make the programming faster.
 */

//functio to load the front view files
function front_template($templete, $data) {
    $ci = & get_instance();
    $temp = array(
        'frontend/template/head',
        'frontend/template/header',
        'frontend/template/header_nav',
        $templete,
        'frontend/template/ads',
        'frontend/login/login',
        'frontend/template/footer'
    );
    $uri_segment = $ci->uri->segment(1);
    if ($uri_segment == 'checkout'):
        unset($temp[4]);
    endif;
    //dumparray($temp);
    foreach ($temp as $t):
        $ci->load->view($t, $data);
    endforeach;
}

//function to load the front view customer files
function customer_template($templete, $data) {
    $ci = & get_instance();
    $temp = array(
        'frontend/template/head',
        'frontend/template/header',
        'frontend/template/header_nav',
        'customer/template/dashboard_panel',
        $templete,
        'frontend/template/footer'
    );
    foreach ($temp as $t):
        $ci->load->view($t, $data);
    endforeach;
}

//function to load the front view customer files
function vendor_template($templete, $data) {
    $ci = & get_instance();
    $temp = array(
        'frontend/template/head',
        'frontend/template/header',
        'frontend/template/header_nav',
        'vendor/template/dashboard_panel',
        $templete,
        'frontend/template/footer'
    );
    foreach ($temp as $t):
        $ci->load->view($t, $data);
    endforeach;
}

//function to load navigation
function get_navigation($group) {
    $ci = & get_instance();
    $sql = $ci->db->select('*')
            ->from('navigation_groups as ng')
            ->join('navigation_links as n', 'n.navigation_group_id = ng.id')
            ->order_by('n.position')
            ->where(array('ng.abbrev' => $group, 'parent' => 0))
            ->get()
            ->result();

    if ($sql):
        $array = array();
        $i = 0;
        foreach ($sql as $s):
            $array[$i] = $s;
            $array[$i]->child_menu = get_child_menu($s->id);
            $i++;
        endforeach;
        return $array;
    else:
        return FALSE;
    endif;
}

function get_child_menu($id) {
    $ci = & get_instance();
    $sql = $ci->db->get_where('navigation_links', array('parent' => $id))->result();

    return $sql;
}

//function to get categories
function categories($type) {
    $ci = & get_instance();

    $sql = $ci->db->select('*')
                    ->order_by('ordering', 'ASC')
                    ->from('category')
                    ->where(array('type' => $type, 'parent_id' => 0, 'active' => '1'))
                    ->get()->result();

    if ($sql):
        $array = array();
        $i = 0;
        foreach ($sql as $s):
            $array[$i] = $s;
            $array[$i]->child_category = get_child_categories($s->id);
            $i++;
        endforeach;
        return $array;
    else:
        return FALSE;
    endif;
}

//funciton to get child categories
function get_child_categories($s) {
    $ci = & get_instance();
    $sql = $ci->db->get_where('category', array('parent_id' => $s, 'active' => '1'))->result();

    if ($sql):
        $array = array();
        $i = 0;
        foreach ($sql as $s):
            $array[$i] = $s;
            $array[$i]->child_category = get_child_categories($s->id);
            $i++;
        endforeach;
        //dumparray($array);
        return $array;
    else:
        return FALSE;
    endif;
}

function show_price($price, $curreny = '') {
    if ($curreny == ''):
        return settings('currency') . $price;
    else:
        return $curreny . '.' . $price;
    endif;
}

function tax_amount() {
    $ci = & get_instance();
    $totalcartamount = $ci->cart->total();
    $tax = settings('tax');

    $calculation = $totalcartamount * $tax / 100;

    return round($calculation, 2);
}

function final_cart_price() {
    $ci = & get_instance();
    $totalcartamount = $ci->cart->total();
    $tax = settings('tax');

    $calculation = $totalcartamount * $tax / 100 + $totalcartamount;

    if (isset($ci->session->userdata('cart_info')['packaging'])):
        if ($ci->session->userdata('cart_info')['packaging'] == 1):
            $packaging_cost = settings('packaging_cost');
            $calculation = $calculation + $packaging_cost;
        endif;
    endif;
    if (isset($ci->session->userdata('coupon_data')['discount'])):
        $discount = $ci->session->userdata('coupon_data')['discount'];
        $calculation = $calculation - $discount;
    endif;

    return round($calculation, 2);
}

function caterogy_url($id) {
    $ci = & get_instance();
    $sql = $ci->db->get_where('category', array('id' => $id))->result();

    if ($sql):
        $array = array();
        foreach ($sql as $s):
            $array[$s->parent_id]['slug'] = $s->category_slug;
            if ($s->parent_id > 0):
                $array[$s->parent_id]['parent'] = get_parent_categories($s->parent_id);
            endif;
        endforeach;

        $final_array = array();
        foreach ($array as $a):
            $final_array[] = $a['slug'];
            if (isset($a['parent'])):
                foreach ($a['parent'] as $b):
                    $final_array[] = $b['slug'];
                    if (isset($b['parent'])):
                        foreach ($b['parent'] as $c):
                            $final_array[] = $c['slug'];
                        endforeach;
                    endif;
                endforeach;
            endif;
        endforeach;


        return final_cat_url($final_array);
    else:
        return FALSE;
    endif;
}

function get_parent_categories($id) {
    $ci = & get_instance();
    $sql = $ci->db->get_where('category', array('id' => $id))->result();

    if ($sql):
        $array = array();
        foreach ($sql as $s):
            $array[$s->parent_id]['slug'] = $s->category_slug;
            if ($s->parent_id > 0):
                $array[$s->parent_id]['parent'] = get_parent_categories($s->parent_id);
            endif;
        endforeach;

        return $array;
    else:
        return FALSE;
    endif;
}

function final_cat_url($final_array) {
    $html = '';
    foreach (array_reverse($final_array) as $fa):
        $html.= $fa . '/';
    endforeach;
    return $html;
}

//function to get label of an item
function show_label($id, $return = FALSE) {
    $ci = & get_instance();
    $sql = $ci->db->select('name')
                    ->from('products_label')
                    ->where('id', $id)
                    ->get()
                    ->row()->name;

    if ($return == FALSE):
        return '<div class="ribbon">' . $sql . '</div>';
    else:
        return '<span class="new">' . $sql . '</span>';
    endif;
}

//function to manage breadcrumbs
function breadcrumbs() {
    $ci = & get_instance();
    $url_segment = $ci->uri->segment_array();

    $html = '<ul class="breadcum">';
    $html.='<li><a href="' . base_url() . '">Home</a></li>';
    $url = base_url();

    foreach ($url_segment as $segment):
        $url.= $segment . '/';
        $html.= '<li><a href="' . $url . '">' . unslug($segment) . '</a></li>';
    endforeach;

    $html.='</ul>';
    echo $html;
}

//function to get product image
function product_image($product_id) {
    $ci = & get_instance();
    $sql = $ci->db->get_where('products_images', array('product_id' => $product_id))->row();
    if ($sql):
        return $sql->image_id;
    else:
        return FALSE;
    endif;
}

//function to get nepali date
function nepali_date() {
    $time_zone = 'Asia/Katmandu';
    return date('F d, Y ');
}

//function to check if customer/vendor is logged in 
function check_session($type) {
    $ci = & get_instance();
    if (!isset($ci->session->userdata['client_session']) || $ci->session->userdata['client_session']['session_type'] != $type):
        redirect('frontend');
    endif;
}

//function to get full name
function get_fullname($first, $middle, $last) {
    $name = ucfirst(trim($first)) . ' ';
    if ($middle != ''):
        $name .= ucfirst(trim($middle)) . ' ';
    endif;
    $name .= ucfirst(trim($last));

    return $name;
}

//function to convert rating into star rating
function get_rating($d, $return = FALSE) {

    if ($return == TRUE):
        $html = '<div class = "rating">';
    else:
        $html = '<span class = "review-rating">';
    endif;
    for ($i = 0; $i < 5; $i++):
        if ($i < $d):
            $html.='<i class = "fa fa-star"></i>';
        else:
            $html.='<i class="fa fa-star-o"></i>';
        endif;

    endfor;
    if ($return == TRUE):
        $html.='</div>';
    else:
        $html.='</span>';
    endif;

    return $html;
}

//function convert rating into stars
function get_avg_rating($id) {
    $array = array();

    $ci = & get_instance();
    $ci->load->database();

    $sql = $ci->db->select_sum('rating')
                    ->from('products_rating')
                    ->where('product_id', $id)
                    ->get()->row();

    $total_rating = $sql->rating;

    $sql = $ci->db->select('rating')
            ->from('products_rating')
            ->where('product_id', $id)
            ->get();

    $num_rows = $sql->num_rows;

    if ($num_rows != 0):
        $res = round($total_rating / $num_rows);
    else:
        $res = 3;
    endif;

    return $res;
}

//function to get number of products for vendor
function get_product() {
    $ci = & get_instance();
    $ci->load->database();

    $sql = $ci->db->get_where('products', array('vendor_id' => $ci->session->userdata('client_session')['user_id']));

    return $sql->num_rows;
}

//display list of products for occasional products
function get_occasional_products($category_id, $category_name) {
    $ci = & get_instance();
//    $sql = $ci->db->select('*')
//            ->from('products_categories as pc')
//            ->join('products as p', 'p.id = pc.product_id', 'LEFT')
//            ->join('products_images as pi', 'p.id = pi.product_id', 'LEFT')
//            //->join('files as f', 'f.id = pi.image_id', 'LEFT')
//            ->where('pc.category_id', $category_id)
//            ->where('pi.status', '1')
//            ->limit(5)
//            ->get();

    $sql = $ci->db->select('p.id, p.name, p.price, p.description, p.short_description,p.url, pc.category_id')
            ->from('products as p')
//            ->join('products_images as pi', 'p.id = pi.product_id')
//            ->join('files as f', 'f.id = pi.image_id')
            ->join('products_categories as pc', 'p.id = pc.product_id')
//            ->where('pi.status', '1')
            ->where('pc.category_id', $category_id)
            ->limit(5)
            ->get();
    
    if ($sql->result()) {
        $html = '';
        $html .= '<div class = "mega-menu">';
        $html .= '<div class = "menu-slider">';
        $html .= '<h3>' . $category_name . '</h3>';
        $html .= '<div class ="mega-menu-slider owl-carousel owl-theme" style = "opacity: 1; display: block;">';
        $html .= '<div class ="owl-wrapper-outer">';
        $html .= '<div class = "owl-wrapper" style = "width: 880px; left: 0px; display: block;">';

        foreach ($sql->result() as $p) {
            $product_url = base_url() . caterogy_url($category_id) . $p->url;
            $html .= '<div class = "owl-item" style = "width: 220px;">';
            $html .= '<a href = "' . $product_url . '">';
            $html .= '<div class = "item new-item">';
            $html .= '  <div class = "item-image">';

            $image = $ci->db->get_where('products_images', array('product_id' => $p->id, 'status' => 1))->row();
            if ($image):
                $image_id = $image->image_id;
            else:
                $image_id = 0;
            endif;
            $html .= show_thumb_image($image_id, '', '$return');
//            $html.= '<img src="uploads/products/thumbs/' . $p->filename . '" alt="' . $p->name . '" width="220">';
//            $html .= show_thumb_image($)
            $html .= '</div>';
            $html .= '  <div class = "item-desc">';
            $html .= '  <p class = "item-name">' . $p->name . '</p>';
            $html .= '  <p class = "item-rate">' . show_price($p->price) . '</p>';
            $html .= '<div class = "buy-now">';
            $html .= '<button type = "button" class = "btn btn-blue">Buy Now <i class = "fa fa-shopping-cart"></i></button>';
            $html .= ' </div>';
            $html .= '</div>';
            $html .= ' </div>';
            $html .= '</a>';
            $html .= ' </div>';
        }

        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
    }
    // echo $html;
    // die();
    return $html;
}

?>