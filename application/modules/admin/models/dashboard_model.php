<?php

class Dashboard_model extends CI_Model
{
    public function get_customer_no()
    {
        $sql = $this->db->select('*')
            ->from('users as u')
            ->join('groups as g', 'g.id = u.group_id', 'LEFT')
            ->join('groups_type as gt', 'gt.id = g.type', 'LEFT')
            ->where('gt.name', 'Customers')
            ->get();

        return $sql->num_rows();
    }

    public function get_item_no()
    {
        $sql = $this->db->get('products');

        return $sql->num_rows();
    }

    public function get_sales()
    {
        $sql = $this->db->select('SUM(grand_total) AS total')
            ->from('order')
            ->where('date', date('Y-m-d'))
            ->get()
            ->row();

        if ($sql):
            return $sql->total;
        else:
            return FALSE;
        endif;
    }

    public function get_sales_chart($wk)
    {
        $start_date = $wk;
        $wk++;
        $end_date = 7 - ($wk * 1);
        $query = 'SELECT date, SUM(grand_total) AS total 
                  FROM default_order 
                  WHERE date BETWEEN DATE_ADD(CURDATE(), INTERVAL -' . $start_date . ' DAY) AND DATE_ADD(CURDATE(), INTERVAL ' . $end_date . ' DAY)
                  GROUP BY date
                  ORDER BY date';
        $sql = $this->db->query($query)->result_array();

        return $sql;
    }
}