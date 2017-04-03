<?php

class Cart extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        check_user_session();

        $this->load->model('order_model');
    }

    function add()
    {
        $res = $this->order_model->get($this->input->post('id'));

        $cart_id = $this->order_model->get_unique_id(get_slug($res->name), $res->sku);


        $cart_array = array(
            'id' => $cart_id,
            'product_id' => $res->id,
            'sku' => $res->sku,
            'qty' => 1,
            'price' => $res->price,
            'name' => $res->name,
        );
        $cart = $this->cart->insert($cart_array);

        $html = $this->cart_table('status');

        echo json_encode($html, JSON_PRETTY_PRINT);

    }

    function remove()
    {
        $rowid = $this->input->post('rowid');
        $this->cart->update(array(
            'rowid' => $rowid,
            'qty' => 0
        ));

        echo 'success';
    }

    function update()
    {
        $qty = $this->input->post('qty');
        $key = $this->input->post('key');

        foreach ($this->cart->contents() as $rows):
            if ($rows['rowid'] == $key):
                $array = array(
                    'rowid' => $rows['rowid'],
                    'qty' => $qty
                );

                $this->cart->update($array);
            endif;
        endforeach;

        $html = $this->cart_table('updated');

        echo json_encode($html, JSON_PRETTY_PRINT);

    }

    function clear()
    {
        $this->cart->destroy();

        redirect('admin/order');
    }

    function cart_final_price()
    {
        $insert_data = $this->input->post();

        $total = $this->cart->total();

        $tax = ($total * ($insert_data['tax'] / 100));
        $service = ($total * ($insert_data['service'] / 100));
        $total = $total + $tax + $service;

        $total = $total - $insert_data['discount'];
        $total = $total + $insert_data['delivery'];

        echo format_price($total);
    }

    function cart_table($status)
    {
        $html = '';
        foreach ($this->cart->contents() as $rows):
            $msg = '<strong>' . $rows['name'] . '</strong> has been ' . $status . ' to the list';
            $html .= '<tr>';
            $html .= '<td><a data-id="' . $rows['rowid'] . '" class="remove_item"><i class="fa fa-close"></i></a></td>';
            $html .= '<td><input type="hidden" name="product_id[]" value="' . $rows['product_id'] . '">' . $rows['name'] . '</td>';
            $html .= '<td><input type="hidden" name="price[]" value="' . $rows['price'] . '">' . settings('currency') . '. ' . format_price($rows['price']) . '</td>';
            $html .= '<td><input type="text" name="qty[]" value="' . $rows['qty'] . '" class="form-control qty-input" data-key="' . $rows['rowid'] . '"></td>';
            $html .= '<td><input type="hidden" name="total[]" value="' . $rows['subtotal'] . '">' . settings('currency') . '. ' . format_price($rows['subtotal']) . '</td>';
            $html .= '</tr>';
        endforeach;

        $array = array(
            'status' => $msg,
            'html' => $html
        );

        return $array;
    }
}