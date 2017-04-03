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
        $this->order();

//        $data['title'] = 'Order History';
//
//        $data['label'] = '<strong>Order</strong> History';
//        $data['sub_label'] = 'Order History';
//        $data['list'] = $this->order_model->get_all();
    }

    public function order()
    {
        $data['title'] = 'Order';

        $data['action'] = base_url('admin/checkout/success');
        $data['attributes'] = array('id' => 'order_form');
        $data['payment_type'] = form_dropdown('payment_type', payment_type(), '', 'class="form-control selectpicker payment_type"');

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
        $html .= '<input type="hidden" name="customer_id" value="' . $s->id . '">';
        $html .= '<div class="image-wrap"><span class="image">' . show_image($s->user_image, '', '$return') . '</span></div>';
        $html .= '<div class="detail-wrap">' . get_fullname($s->first_name, $s->middle_name, $s->last_name) . '<br> Contact No: ' . $s->mobile . '</div>';


        echo $html;
    }

}