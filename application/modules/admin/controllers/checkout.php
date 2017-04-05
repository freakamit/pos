<?php

class Checkout extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        check_user_session();

    }

    public function success()
    {
        $insert_data = $this->input->post();

        if (!isset($insert_data['tax_checkbox'])):
            $insert_data['tax_amount'] = '0';
        endif;
        if (!isset($insert_data['service_checkbox'])):
            $insert_data['service_charge'] = '0';
        endif;
        $order_table = array(
            'bill_no' => $insert_data['bill_no'],
            'customer_id' => isset($insert_data['customer_id']) ? $insert_data['customer_id'] : '',
            'tax_amount' => $insert_data['tax_amount'],
            'service_charge' => $insert_data['service_charge'],
            'discount_amount' => $insert_data['discount_amount'],
            'delivery_charge' => $insert_data['delivery_charge'],
            'grand_total' => $insert_data['grand_total'],
            'payment_type' => $insert_data['payment_type'],
            'payment_type_ref' => isset($insert_data['payment_type_ref']) ? $insert_data['payment_type_ref'] : '',
            'date' => db_format_date('now'),
            'time' => db_format_time('now'),
            'created_by' => active_user_id()
        );

        $insert_id = save('order', $order_table);

        $i = 0;
        foreach ($insert_data['product_id'] as $pid):
            $order_item = array(
                'order_id' => $insert_id,
                'product_id' => $pid,
                'price' => $insert_data['price'][$i],
                'qty' => $insert_data['qty'][$i],
                'total' => $insert_data['total'][$i]
            );

            save('order_list', $order_item);
            $i++;
        endforeach;

        set_flashdata(array(
            'status' => 'success',
            'message' => '<strong>Success!</strong> Record save successfully'
        ));

        $this->cart->destroy();

        echo $insert_id;
    }
}