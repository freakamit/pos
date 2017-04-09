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

            $i = 0;
            foreach ($fields as $f):
                $fields[$i] = ucwords(str_replace('_', ' ', $f));
                $i++;
            endforeach;
            $fields[1] = 'Customer Name';

            $result['field'] = $fields;
            $result['result'] = $sql->result_array();

            $i = 0;
            foreach ($result['result'] as $r):
                if ($r['customer_type'] == 1):
                    $query = "SELECT CONCAT (`first_name`,' ',`middle_name`,' ',`last_name`) as `fullname`
                        FROM default_users_profile
                        WHERE `user_id` = " . $r['customer_id'];
                    $sql = $this->db->query($query)->row();

                    $fullname = $sql->fullname;

                    $result['result'][$i]['customer_type'] = 'Registered Customer';

                elseif ($r['customer_type'] == 2):
                    $query = "SELECT `name`
                        FROM default_unregister_customer
                        WHERE `id` = " . $r['customer_id'];
                    $sql = $this->db->query($query)->row();

                    $fullname = $sql->name;

                    $result['result'][$i]['customer_type'] = 'Not Registered Customer';
                elseif ($r['customer_type'] == 3):
                    $fullname = 'Guest Customer';
                    $result['result'][$i]['customer_type'] = 'None';
                endif;

                $result['result'][$i]['customer_id'] = $fullname;
                $result['result'][$i]['date'] = user_format_date(strtotime($r['date']));
                $result['result'][$i]['grand_total'] = show_price(format_price($r['grand_total']));
                
                $i++;
            endforeach;

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