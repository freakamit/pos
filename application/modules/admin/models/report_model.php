<?php

class Report_model extends CI_Model
{
    public function get_sales_report($from, $to, $return = FALSE)
    {
        $query = "SELECT bill_no, customer_id, customer_type, date, time, grand_total
                    FROM default_order
                    WHERE date BETWEEN '$from' AND '$to'";

        $sql = $this->db->query($query);
        if ($return):
            $result = array();
            $fields = $sql->list_fields();
            $result['field'] = $fields;
            $result['result'] = $sql->result_array();

            return $result;
        else:
            return $sql->result();
        endif;
    }

    public function create_csv($res, $daterange)
    {
        $this->load->helper('download');
        $fp = fopen('php://output', 'w');
        fputcsv($fp, $res['field']);
        foreach ($res['result'] as $fields) {
            fputcsv($fp, $fields);
        }

        $data = file_get_contents('php://output');
        $filename = 'Sales Report: ' . $daterange;
        $name = ucfirst($filename) . '.csv';
        // Build the headers to push out the file properly.
        header('Pragma: public');     // required
        header('Expires: 0');         // no cache
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Cache-Control: private', false);
        header('Content-type: application/csv');
        header('Content-Disposition: attachment; filename="' . basename($name) . '"');  // Add the file name
        header('Content-Transfer-Encoding: binary');
        header('Connection: close');
        exit();
        force_download($name, $data);
        fclose($fp);
    }
}