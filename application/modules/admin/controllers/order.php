<?php

class Order extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        check_user_session();

        $this->load->model('order_model');
    }

    public function index()
    {
        $data['title'] = 'Order History';

        $data['label'] = '<strong>Order</strong> History';
        $data['sub_label'] = 'Order History';
        $data['list'] = $this->order_model->get_all();

        $i = 0;
        foreach ($data['list'] as $d):
            $data['list'][$i]->bill_no = '#' . $d->bill_no;
            $data['list'][$i]->date = user_format_date(strtotime($d->date)) . ' - ' . $d->time;
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

        $data['fields'] = array(
            'SN',
            'Bill Number',
            'Customer Name',
            'Customer Type',
            'Order Date',
            'Action'
        );

        $data['action'] = array(
            array(
                'name' => 'View Order',
                'url' => base_url('admin/order/order_list'),
                'icon' => 'eye'
            ),
        );

        template('list', $data);
    }

    public function order_list($id)
    {
        $data['title'] = 'Order History';

        $d = $this->order_model->get_order($id);

        $data['label'] = '<strong>Order</strong> List';
        $data['sub_label'] = 'Bill Number: #' . $d->bill_no;
        $data['list'] = $this->order_model->order_list($id);

        $i = 0;
        foreach ($data['list'] as $ol):
            $data['list'][$i]->price = show_price(format_price($ol->price));
            $data['list'][$i]->total = show_price(format_price($ol->total));
            $i++;
        endforeach;

        $data['extra'] = array(
            array('Date' => user_format_date(strtotime($d->date)), 'Time' => $d->time),
            array('Tax Amount' => show_price(format_price($d->tax_amount)), 'Service Charge' => show_price(format_price($d->service_charge))),
            array('Discount Amount' => show_price(format_price($d->discount_amount)), 'Delivery Charge' => show_price(format_price($d->delivery_charge))),
            array('Grand Total' => '<strong style="color: red">' . show_price(format_price($d->grand_total)) . '</strong>'),
            array('Payment Type' => $d->payment_type, 'Payment Reference Number' => $d->payment_type_ref)
        );

        $data['fields'] = array(
            'SN',
            'Item',
            'Price',
            'Qty',
            'Total'
        );
        template('list', $data);


    }

    public function create()
    {
        $data['title'] = 'Order';

        $data['action'] = base_url('admin/checkout/success');
        $data['attributes'] = array('id' => 'order_form');
        $data['payment_type'] = form_dropdown('payment_type', payment_type(), '', 'class="form-control selectpicker payment_type"');
        $data['bill_no'] = $this->order_model->get_bill_no();

        template('take_order', $data);

    }

    function get_item()
    {
        $item = $this->input->post('item');

        $res = $this->order_model->get_item($item);

        $html = '';
        $html .= '<ul>';

        if ($res):
            foreach ($res as $s):
                $html .= '<li>';
                $html .= '<a data-id="' . $s->id . '"><strong>' . $s->name . '</strong>' . ' - ' . settings('currency') . '. ' . $s->price . '<br> Category: <span class="category-name">' . $s->category_name . '</span></a>';
                $html .= '</li>';
            endforeach;
        else:
            $html .= '<li>No Item Available</li>';
        endif;
        $html .= '</ul>';

        echo $html;
    }

    function customer_list()
    {
        $name = $this->input->post('name');

        $res = $this->order_model->get_customer($name);

        $html = '';
        $html .= '<ul>';
        if ($res):
            foreach ($res as $s):
                $html .= '<li>';
                $html .= '<a data-id="' . $s->id . '">';
                $html .= '<div class="image-wrap"><span class="image">' . show_image($s->user_image, '', '$return') . '</span></div>';
                $html .= '<div class="detail-wrap">' . get_fullname($s->first_name, $s->middle_name, $s->last_name) . '<br> Contact No: ' . $s->mobile . '</div>';
                $html .= '</a>';
                $html .= '</li>';
            endforeach;
        endif;
        $html .= '</ul>';

        echo $html;
    }

    function get_customer($id)
    {
        $s = $this->order_model->get_customer_detail($id);

        $html = '';
        $html .= '<input type="hidden" name="customer_id" value="' . $s->id . '" class="customer_id">';
        $html .= '<div class="image-wrap"><span class="image">' . show_image($s->user_image, '', '$return') . '</span></div>';
        $html .= '<div class="detail-wrap">' . get_fullname($s->first_name, $s->middle_name, $s->last_name) . '<br> Contact No: ' . $s->mobile . '</div>';


        echo $html;
    }

    public function generate_bill($id)
    {
        $d = $this->order_model->get_order($id);
        $d->order_list = $this->order_model->order_list($id);

        $data['order'] = $d;

        $content = $this->load->view('bill_pdf', $data, TRUE);
        $this->_generate_pdf($content, '', 'Invoice', $d->bill_no);

    }

    public function _generate_pdf($html, $title, $paper = 'A4', $filename)
    {
        $this->load->library('mpdf60/mpdf');
        //mode,format,default_font_size,default_font,margin_left,margin_right,margin_top,margin_bottom,margin_header,margin_footer,orientation
        $mpdf = new mPDF('c', $paper, '', '', 50, 50, 10, 25, 5, 5, 'L');
        $boot_style = file_get_contents(base_url('assets/css/bootstrap/bootstrap.min.css')); //css ko link dine
        $cust_style = file_get_contents(base_url('assets/css/pdf_style.css')); //css ko link dine

        $mpdf->WriteHTML($boot_style, 1);
        $mpdf->WriteHTML($cust_style, 1);

        $mpdf->SetTitle("Bill" . $title);
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->WriteHTML($html);
        $mpdf->Output('Bill No #' . $filename . '.pdf', 'I');
    }
}