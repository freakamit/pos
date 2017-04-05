<?php

class Order_model extends CI_Model
{
    public function get_all()
    {

        $sql = $this->db->select('id,bill_no, customer_id, date,time')
                        ->from('order')
                        ->get()->result();

        return $sql;
    }

    public function get_item($item)
    {
        $sql = $this->db->select('p.id, p.name,c.category_name,p.price')
            ->from('products as p')
            ->join('products_categories as pc', 'pc.product_id = p.id', 'LEFT')
            ->join('category as c', 'c.id = pc.category_id')
            ->or_like('c.category_name', $item)
            ->or_like('p.name', $item)
            ->or_like('p.sku', $item)
            ->get()
            ->result();

        return $sql;
    }

    public function get($id)
    {
        $sql = $this->db->get_where('products', array('id' => $id))->row();
        return $sql;
    }

    public function get_unique_id($name, $sku)
    {
        $up_id = $name . '__' . str_replace('/', '_', $sku);
        return $up_id;
    }

    public function get_customer($name)
    {
        $sql = $this->db->select('u.id, up.first_name, up.middle_name, up.last_name, up.mobile, gt.name, up.user_image')
            ->from('users as u')
            ->join('users_profile as up', 'up.user_id = u.id', 'LEFT')
            ->join('groups as g', 'u.group_id = g.id', 'LEFT')
            ->join('groups_type as gt', 'g.type = gt.id', 'LEFT')
            ->or_like('up.first_name', $name)
            ->or_like('up.middle_name', $name)
            ->or_like('up.last_name', $name)
            ->having('gt.name', 'Customers')
            ->get()->result();
        return $sql;

    }

    public function get_customer_detail($id)
    {
        $sql = $this->db->select('u.id, up.first_name, up.middle_name, up.last_name, up.mobile, up.user_image')
            ->from('users as u')
            ->join('users_profile as up', 'up.user_id = u.id', 'LEFT')
            ->where('u.id', $id)
            ->get()->row();

        return $sql;
    }

    function get_bill_no()
    {
        $query = 'SELECT bill_no FROM default_order ORDER BY id DESC LIMIT 1';
        $sql = $this->db->query($query)->row();

        if ($sql):
            return $sql->bill_no + 1;
        else:
            return 1;
        endif;
    }

    function get_order($id)
    {
        return $this->db->get_where('order', array('id' => $id))->row();
    }

    function order_list($id)
    {
        $sql = $this->db->select('ol.id, p.name, ol.price, ol.qty, ol.total')
            ->from('order_list as ol')
            ->join('products as p', 'p.id = ol.product_id', 'LEFT')
            ->where('order_id', $id)
            ->get()->result();

        return $sql;
    }
}