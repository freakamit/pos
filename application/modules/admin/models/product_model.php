<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Product_model extends CI_Model {

    var $_table = 'products';

    function get_all($id) {
        $this->db->select('p.id,pi.image_id,p.name,p.sku,c.category_name,p.active')
                ->from($this->_table . ' as p')
                ->join('products_images as pi', 'p.id = pi.product_id', 'left')
                ->join('products_categories as pc', 'pc.product_id = p.id', 'LEFT')
                ->join('category as c', 'c.id = pc.category_id', 'LEFT');
        if ($id):
            $this->db->where('pc.category_id', $id);
        endif;
        $sql = $this->db->group_by('p.id')
                ->get()
                ->result();

        $array = array();
        foreach ($sql as $s):
            $sql = $this->db->get_where('products_images', array('status' => 1, 'product_id' => $s->id))->row();
            if ($sql):
                $s->image_id = $sql->image_id;
            endif;
            $array[] = $s;
        endforeach;

        return $array;
    }

    function get($id) {
        $product_array = $this->db->get_where($this->_table, array('id' => $id))->row();
        $product_array->categories = $this->fetch_category($id);
        $product_array->image = $this->fetch_image($id);
        return $product_array;
    }

    function fetch_verification($id) {
        $sql = $this->db->get_where('products_verification', array('product_id' => $id))->row();
        if ($sql):
            return $sql->verification;
        else:
            return FALSE;
        endif;
    }

    function get_vendor_email($id) {
        return $this->db->select('u.email,up.first_name,pi.image_id,p.sku,p.cost')
                        ->from('products_verification as pv')
                        ->join('products as p', 'p.id = pv.product_id', 'LEFT')
                        ->join('users as u', 'u.id = pv.vendor_id', 'LEFT')
                        ->join('users_profile as up', 'u.id = up.user_id', 'LEFT')
                        ->join('products_images as pi', 'pi.product_id = pv.product_id', 'LEFT')
                        ->where(array('pv.product_id' => $id, 'pv.verification' => '0'))
                        ->get()->row();
    }

    function fetch_category($id) {
        $categories = $this->db->select('category_id')->get_where('products_categories', array('product_id' => $id))->result();
        foreach ($categories as $k => $v):
            $category[$v->category_id] = $v->category_id;
        endforeach;
        return $category;
    }

    function fetch_addon($id) {
        $addon = $this->db->select('addonproduct_id')->get_where('products_addon', array('product_id' => $id))->result();
        foreach ($addon as $k => $v):
            $addon[$v->addonproduct_id] = $v->addonproduct_id;
        endforeach;
        return $addon;
    }

    function fetch_image($id) {
        $images = $this->db->select('image_id,status')->get_where('products_images', array('product_id' => $id))->result();
        return $images;
    }

    function fetch_attribute($product_id, $attr_id) {
        $sql = $this->db->get_where('products_attr_set', array('id' => $attr_id))->row();

        $attr = array();
	
	if($sql):
	
	        if ($sql->attributes != 'null'):
	            $attr = $this->db->select('a.attribute_code,a.slug,a.id,a.is_required,a.is_price')
	                            ->from('products_attr as a')
	                            ->where_in('a.id', json_decode($sql->attributes))
	                            ->get()->result();
			
	            if ($attr):
	                $i = 0;
	                foreach ($attr as $a):
	                    $value = $this->db->select('value')->from('products_attr_pviot')->where(array('product_id' => $product_id, 'attr_id' => $a->id))->get()->row();
	                    if ($value):
	                        $attr[$i]->value = $value->value;
	                    else:
	                        $attr[$i]->value = '';
	                    endif;
	                    $i++;
	                endforeach;
	            endif;
	        endif;
	endif;

        return $attr;
    }

    function add_product_image($product_id, $s) {
        $product_images = multi_upload('uploads/products');
        if(isset($product_images['error'])):
            return array(
                'status' => FALSE,
                'msg' =>  $product_images['error']['error']
            );
            else:
                $img_status = $s;
                foreach ($product_images['files'] as $img):
                    $product_image_data = array(
                        'product_id' => $product_id,
                        'image_id' => $img,
                        'status' => $img_status
                    );
                    $img_status = 0;
                    save('products_images', $product_image_data);
                endforeach;
                endif;

    }

    function check_primary_image($id) {
        $sql = $this->db->get_where('products_images', array('product_id' => $id, 'status' => 1))->row();
        if ($sql):
            return '0';
        else:
            return '1';
        endif;
    }

    function set_primary_product_image($data, $id) {
        //changing all the product status to 0
        $con = array('key' => 'product_id', 'value' => $id);
        update('products_images', $con, array('status' => 0));

        // setting default image
        $con = array('key' => 'image_id', 'value' => $data);
        update('products_images', $con, array('status' => 1));

        return;
    }

    function remove_image($data) {
        foreach ($data as $d):
            remove_image($d);
            $con = array('key' => 'image_id', 'value' => $d);
            delete('products_images', $con);
        endforeach;
        return;
    }

    function remove_product_categories($id) {
        $con = array('key' => 'product_id', 'value' => $id);
        delete('products_categories', $con);
        return;
    }

    function add_product_categories($data, $product_id) {
        foreach ($data as $category):
            $product_category_data = array(
                'product_id' => $product_id,
                'category_id' => $category
            );
            save('products_categories', $product_category_data);
        endforeach;
    }

    function remove_product_attr($id) {
        $con = array('key' => 'product_id', 'value' => $id);
        delete('products_attr_pviot', $con);
        return;
    }

    function add_product_attr($data, $product_id) {
        $product_price_attr = '';
        foreach ($data as $k => $v):
            if (is_array($v)):
                $count = count($v['option']);
                for ($i = 0; $i < $count; $i++):
                    if ($i <= $count - 2):
                        $product_price_attr .= trim($v['option'][$i]) . ' + $' . trim($v['value'][$i]) . ';';
                    else:
                        $product_price_attr .= trim($v['option'][$i]) . ' + $' . trim($v['value'][$i]);
                    endif;
                endfor;
                $prodcut_attr_data = array(
                    'product_id' => $product_id,
                    'attr_id' => $k,
                    'value' => $product_price_attr
                );
            else:
                $prodcut_attr_data = array(
                    'product_id' => $product_id,
                    'attr_id' => $k,
                    'value' => $v
                );
            endif;

            save('products_attr_pviot', $prodcut_attr_data);
        endforeach;
    }

    function remove_product_tags($id) {
        $con = array('key' => 'product_id', 'value' => $id);
        delete('products_tags', $con);
        return;
    }

    function add_product_tags($data, $product_id) {
        $tags = explode(',', $data);
        foreach ($tags as $tag):
            $product_tag_data = array(
                'product_id' => $product_id,
                'tags' => $tag
            );
            save('products_tags', $product_tag_data);
        endforeach;
        return;
    }

    function add_addon_product($product_id, $data) {
        foreach ($data as $addon):
            $product_addon_data = array(
                'product_id' => $product_id,
                'addonproduct_id' => $addon
            );
            save('products_addon', $product_addon_data);
        endforeach;
    }

    function remove_product_addon($id) {
        $con = array('key' => 'product_id', 'value' => $id);
        delete('products_addon', $con);
        return;
    }

    function get_review_list($id) {
        $sql = $this->db->select('name,sku')
                        ->from('products')
                        ->where('id', $id)
                        ->get()->row();

        $sql->review = $this->db->select('id,name,rating,review,created_on,status')
                        ->from('products_rating')
                        ->where('product_id', $id)
                        ->get()->result();

        return $sql;
    }

    function get_review($id) {
        return $this->db->get_where('products_rating', array('id' => $id))->row();
    }

    function check_review($email, $id) {
        $sql = $this->db->get_where('products_rating', array('email' => $email, 'product_id' => $id));

        $status = FALSE;

        if ($sql->num_rows > 0):
            $status = TRUE;
        endif;

        return $status;
    }

}
