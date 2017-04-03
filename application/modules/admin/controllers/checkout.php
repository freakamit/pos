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

        dumparray($insert_data);

        if (!isset($insert_data['tax_checkbox'])):
            $insert_data['tax_amount'] = '0';
        endif;
        if (!isset($insert_data['service_checkbox'])):
            $insert_data['service_charge'] = '0';
        endif;

    }
}