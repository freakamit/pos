<?php

class Dashboard extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        check_user_session();

        $this->load->model('dashboard_model');
    }

    public function index()
    {
        $data['title'] = 'Dashboard';

        $data['customers'] = $this->dashboard_model->get_customer_no();
        $data['items'] = $this->dashboard_model->get_item_no();
        $data['sales'] = $this->dashboard_model->get_sales();

        $sales_chart = $this->dashboard_model->get_sales_chart(date('w'));

        if ($sales_chart):
            $i = 0;
            foreach ($sales_chart as $s):
                $sales_chart[$i]['date'] = date('l', strtotime($s['date']));
                $i++;
            endforeach;
        endif;
        $data['sales_chart'] = $sales_chart;

        template('dashboard', $data);
    }

}
