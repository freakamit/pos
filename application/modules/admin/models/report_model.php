<?php

class Report_model extends CI_Model
{
    public function get_sales_report($from, $to){
        $query = "SELECT bill_no, customer_id, customer_type, date, time, grand_total
                    FROM default_order
                    WHERE date BETWEEN '$from' AND '$to'";

        $sql = $this->db->query($query)->result();

        return $sql;
    }
}