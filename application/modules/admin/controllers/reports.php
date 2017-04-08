<?php

class Reports extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        check_user_session();

        $this->load->model('report_model');
        $this->load->model('order_model');
    }

    public function form_array($data = array())
    {
        $form_array['date_range'] = array(
            array(
                'name' => 'date_range',
                'type' => 'text',
                'value' => '',
                'placeholder' => 'Select Date Range',
                'class' => 'form-control daterange',
                'id' => 'daterange',
                'label' => 'Date Range',
                'parsley-required' => 'true',
                'parsley-trigger' => "keyup"
            ));
        $form_array['buttons'] = array(
            array(
                'name' => 'submit',
                'type' => 'submit',
                'value' => 'send',
                'class' => 'btn btn-theme',
                'content' => 'Generate'
            ),
            array(
                'name' => 'reset',
                'type' => 'reset',
                'value' => 'Reset',
                'class' => 'btn',
                'onclick' => "$( 'form' ).parsley( 'destroy' )"
            )
        );
        return $form_array;
    }

    public function index()
    {
        $data['title'] = 'Sales Report';

        $data['form_action'] = 'admin/reports/generate_report';

        $form['form_size'] = '12';
        $form['label'] = '<strong>Date </strong>  Range';
        $form['sub_label'] = 'Select Date Range';
        $form['form_components'] = $this->form_array();
        $form['form_components'] = $form['form_components']['date_range'];
        $data['form'][] = $this->form_builder->build($form);

        $form['form_size'] = '12';
        $form['label'] = "";
        $form['sub_label'] = '';
        $form['form_components'] = $this->form_array($this->input->post());
        $form['form_components'] = $form['form_components']['buttons'];
        $data['form'][] = $this->form_builder->build($form);

        template('create', $data);
    }

    public function generate_report()
    {
        $data['title'] = 'Sales Report';
        $data['label'] = '<strong>Sales</strong> Report';

        $data['fields'] = array(
            'SN',
            'Bill No',
            'Name',
            'Customer Type',
            'Order Date',
            'Total'
        );

        if ($this->input->post()):
            $date_range = trim($this->input->post('date_range'));
            $date = explode('/', $date_range);
            $from = trim($date[0]);
            $to = trim($date[1]);

            $data['sub_label'] = 'Date Rage:' . $from . ' - ' . $to;

            $data['list'] = $this->report_model->get_sales_report($from, $to);

            $i = 0;
            foreach ($data['list'] as $d):
                $data['list'][$i]->bill_no = '#' . $d->bill_no;
                $data['list'][$i]->date = user_format_date(strtotime($d->date)) . ' - ' . $d->time;
                $data['list'][$i]->grand_total = show_price(format_price($d->grand_total));

                unset($data['list'][$i]->time);

                switch ($d->customer_type):
                    case '1':
                        $data['list'][$i]->customer_type = 'Registered';

                        $cust = $this->order_model->get_customer_detail($d->customer_id);
                        if ($cust):
                            $cust = get_fullname($cust->first_name, $cust->middle_name, $cust->last_name);
                        else:
                            $cust = 'Customer Detail Not Available';
                        endif;
                        break;
                    case '2':
                        $data['list'][$i]->customer_type = 'Not Registered';

                        $cust = $this->order_model->get_unregistered_cust($d->customer_id);
                        if ($cust):
                            $cust = $cust->name;
                        else:
                            $cust = 'Customer Detail Not Available';
                        endif;
                        break;
                    case '3':
                        $data['list'][$i]->customer_type = 'None';
                        $cust = 'Guest Customer';
                        break;
                endswitch;

                $data['list'][$i]->customer_id = $cust;
                $i++;
            endforeach;

            $data['buttons'] = array(
                array(
                    'title' => 'Export to Excel Sheet',
                    'url' => base_url('admin/reports/export/' . $from . '/' . $to),
                    'class' => 'primary',
                    'icon' => 'file'
                )
            );

            template('list', $data);

        else:
            redirect('admin/report');
        endif;
    }

    public function export($from, $to)
    {
        $res = $this->report_model->get_sales_report($from, $to, TRUE);

        $daterange = $from . ' - ' . $to;
        $this->report_model->create_csv($res, $daterange);
        $this->csv($res, $daterange);
    }

}