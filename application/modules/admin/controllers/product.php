<?php

class Product extends CI_Controller {

    public function __construct() {
        parent::__construct();
        check_user_session();

        $this->load->model('product_model');
    }

    public function form_array($data = array()) {

        $form_array['general'] = array(
            array(
                'name' => 'name',
                'type' => 'text',
                'label' => 'Name*',
                'value' => isset($data['name']) ? $data['name'] : FALSE,
                'class' => 'form-control slug_title',
                'parsley-required' => 'true',
            ),
            array(
                'name' => 'sku',
                'type' => 'text',
                'label' => 'SKU*',
                'value' => isset($data['sku']) ? $data['sku'] : FALSE,
                'class' => 'form-control',
                'parsley-required' => 'true',
            ),
            array(
                'name' => 'url',
                'type' => 'text',
                'value' => isset($data['url']) ? $data['url'] : FALSE,
                'label' => 'URL Key*',
                'class' => 'form-control slug',
                'parsley-required' => 'true'
            ),
            array(
                'name' => 'price',
                'type' => 'text',
                'value' => isset($data['price']) ? $data['price'] : FALSE,
                'label' => 'Product Price*',
                'class' => 'form-control',
                'parsley-required' => 'true',
                'parsley-type' => 'number'
            ),
            array(
                'name' => 'active',
                'type' => 'checkbox',
                'value' => '1',
                'checked' => isset($data['active']) ? $data['active'] : TRUE,
                'class' => 'form-control',
                'label' => 'Active',
            ),
        );
        $form_array['description'] = array(
            array(
                'name' => 'description',
                'type' => 'textarea',
                'label' => 'Description',
                'value' => isset($data['description']) ? $data['description'] : FALSE,
                'class' => 'form-control ckeditor',
                'id' => 'product_description'
            ),
        );

        $form_array['category'] = array(
            array(
                'name' => 'category[]',
                'type' => 'multiselect',
                'option' => category_list(),
                'selected' => isset($data['categories']) ? $data['categories'] : 'false',
                'label' => 'Product Category',
                'extra' => 'class ="multiselect" parsley-required="true" ',
            ),
        );

        $form_array['image'] = array(
            array(
                'name' => 'userfile[]',
                'type' => 'upload',
                'class' => 'form-control',
                'label' => 'Product Image',
                'multiple' => 'multiple'
            ),
        );

        if (isset($data['image'])):
            foreach ($data['image'] as $i):
                if ($i->status == '1'):
                    $status = 'fa fa-check';
                else:
                    $status = 'fa fa-close';
                endif;
                $form_array['image'][] = array(
                    'name' => '',
                    'label' => '',
                    'type' => 'image_block',
                    'value' => $i->image_id,
                    'status' => $i->status,
                    'class' => 'product_img',
                    'option' => array(
                        array(
                            'name' => 'Set as Default Image',
                            'url' => base_url('admin/product/set_default_image') . '/' . $data['id'] . '/' . $i->image_id,
                            'btn_type' => 'btn btn-default',
                            'class' => $status
                        ),
                        array(
                            'name' => 'Remove Image',
                            'url' => base_url('admin/product/remove_image') . '/' . $data['id'] . '/' . $i->image_id,
                            'btn_type' => 'btn btn-default',
                            'class' => 'fa fa-trash-o'
                        ),
                    )
                );
            endforeach;
        endif;

        $form_array['buttons'] = array(
            array(
                'name' => 'submit',
                'type' => 'submit',
                'value' => 'save',
                'class' => 'btn btn-theme',
                'content' => 'Save & Exit'
            ),
            array(
                'name' => 'submit',
                'type' => 'submit',
                'value' => 'save_edit',
                'class' => 'btn btn-theme',
                'content' => 'Save & Continue Edit'
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

    public function index() {
        $this->listing();
    }

    public function listing($id = 0) {
        $data['title'] = 'Item List';
        $data['label'] = '<strong>Item </strong> list';
        $data['sub_label'] = 'List of Product';
        $data['list'] = $this->product_model->get_all($id);
        $data['bulk_action'] = base_url('admin/product/listing') . '/' . $id;

        $i = 0;
        foreach ($data['list'] as $k => $v):
            $data['list'][$i]->active = display_status($v->active);
            $data['list'][$i]->image_id = show_thumb_image($v->image_id, 'product_img', 'return');
            $i++;
        endforeach;
        //dumparray($data['list']);
        $data['buttons'] = array(
            array(
                'title' => 'Add New Item',
                'url' => base_url('admin/product/create'),
                'class' => 'primary',
                'icon' => 'plus'
            )
        );
        $data['fields'] = array(
            'Item Image',
            'Item Name',
            'Item Code',
            'Category Name',
            'Status',
            'Action'
        );
        $data['action'] = array(
//            array(
//                'name' => 'Review',
//                'url' => base_url('admin/product/review'),
//                'icon' => 'star'
//            ),
            array(
                'name' => 'Edit',
                'url' => base_url('admin/product/edit'),
                'icon' => 'pencil'
            ),
            array(
                'name' => 'Delete',
                'url' => base_url('admin/product/delete'),
                'icon' => 'trash-o'
            )
        );
        if ($this->input->post()):
            $bulk_action = $this->input->post();

            foreach ($bulk_action['bulk'] as $b):
                $con = array('key' => 'id', 'value' => $b);
                update('products', $con, array('active' => $bulk_action['bulk_action']));
            endforeach;

            set_flashdata(array(
                'status' => 'success',
                'message' => '<strong>Success!</strong> Status changed successfully'
            ));

            redirect('admin/product/listing/' . $id);
        else:
            template('list', $data);
        endif;
    }

    public function create() {

        $data['title'] = 'Add New Item';
        $data['form_action'] = 'admin/product/save';

        //create form
        $form['form_size'] = '6';
        $form['label'] = '<strong>New </strong> Item';
        $form['sub_label'] = 'General Product Infromation';
        $form['form_components'] = $this->form_array();
        $form['form_components'] = $form['form_components']['general'];
        $data['form'][] = $this->form_builder->build($form);

        $form['form_size'] = '6';
        $form['label'] = '<strong>Item </strong> Categories';
        $form['sub_label'] = '';
        $form['form_components'] = $this->form_array();
        $form['form_components'] = $form['form_components']['category'];
        $data['form'][] = $this->form_builder->build($form);

        $form['form_size'] = '12';
        $form['label'] = '<strong>Item </strong> Description';
        $form['sub_label'] = '';
        $form['form_components'] = $this->form_array();
        $form['form_components'] = $form['form_components']['description'];
        $data['form'][] = $this->form_builder->build($form);

        $form['form_size'] = '12';
        $form['label'] = '<strong>Item </strong> Images';
        $form['sub_label'] = '';
        $form['form_components'] = $this->form_array();
        $form['form_components'] = $form['form_components']['image'];
        $data['form'][] = $this->form_builder->build($form);

        $form['form_size'] = '12';
        $form['label'] = '';
        $form['sub_label'] = '';
        $form['form_components'] = $this->form_array();
        $form['form_components'] = $form['form_components']['buttons'];
        $data['form'][] = $this->form_builder->build($form);

        template('create', $data);
    }

    public function save() {
        $insert_data = $this->input->post();

        if (!check_duplication('products', array('sku' => $insert_data['sku']))):
            set_flashdata(array(
                'status' => 'danger',
                'message' => '<strong>Sorry!</strong> Item Code already exist'
            ));

            redirect('admin/product/create');
        endif;

        //insert product in database
        $insert_data['name'] = ucfirst($insert_data['name']);
        $insert_data['sku'] = strtoupper($insert_data['sku']);
        $insert_data['url'] = strtolower($insert_data['url']);
        $insert_data['description'] = ucfirst($insert_data['description']);

        $product_table_data = $insert_data;
        $product_table_data['url'] = get_slug($insert_data['name'] . ' ' . $insert_data['sku']);
        $product_table_data['created'] = active_user_id();
        $product_table_data['created_at'] = strtotime("now");
        $product_table_data = filter_array($product_table_data, array('submit', 'category', 'product_image'));

        $product_id = save('products', $product_table_data);

        //insert prodcut categories
        if (isset($insert_data['category'])):
            $this->product_model->add_product_categories($insert_data['category'], $product_id);
        endif;

        //insert product image
        if (!empty($_FILES['userfile']['name'][0])):
            $return = $this->product_model->add_product_image($product_id, '1');
            if($return['status']):
                set_flashdata(array(
                    'status' => 'danger',
                    'message' => $return['msg']
                ));
            redirect('admin/product');
                endif;
        endif;

        set_flashdata(array(
            'status' => 'success',
            'message' => '<strong>Success!</strong> Item <b>"' . $insert_data['name'] . '"</b> has been added successfully.'
        ));

        if ($this->input->post('submit') == 'save'):
            redirect('admin/product');
        else:
            redirect('admin/product/edit/' . $product_id);
        endif;
    }

    public function edit($id) {
        $data['title'] = 'Edit Item';
        $data['form_action'] = 'admin/product/update/' . $id;

        $d = (array) $this->product_model->get($id);

        //create form
        $form['form_size'] = '6';
        $form['label'] = '<strong>Edit </strong> Item';
        $form['sub_label'] = 'General Product Infromation';
        $form['form_components'] = $this->form_array($d);
        $form['form_components'] = $form['form_components']['general'];
        $data['form'][] = $this->form_builder->build($form);

        $form['form_size'] = '6';
        $form['label'] = '<strong>Item </strong> Categories';
        $form['sub_label'] = '';
        $form['form_components'] = $this->form_array($d);
        $form['form_components'] = $form['form_components']['category'];
        $data['form'][] = $this->form_builder->build($form);

        $form['form_size'] = '12';
        $form['label'] = '<strong>Item </strong> Description';
        $form['sub_label'] = '';
        $form['form_components'] = $this->form_array($d);
        $form['form_components'] = $form['form_components']['description'];
        $data['form'][] = $this->form_builder->build($form);

        $form['form_size'] = '12';
        $form['label'] = '<strong>Item </strong> Images';
        $form['sub_label'] = '';
        $form['form_components'] = $this->form_array($d);
        $form['form_components'] = $form['form_components']['image'];
        $data['form'][] = $this->form_builder->build($form);

        $form['form_size'] = '12';
        $form['label'] = '';
        $form['sub_label'] = '';
        $form['form_components'] = $this->form_array($d);
        $form['form_components'] = $form['form_components']['buttons'];
        $data['form'][] = $this->form_builder->build($form);

        template('create', $data);
    }

    public function update($id) {
        $insert_data = $this->input->post();

        //update product in database
        $insert_data['name'] = ucfirst($insert_data['name']);
        $insert_data['sku'] = strtoupper($insert_data['sku']);
        $insert_data['url'] = strtolower($insert_data['url']);
        $insert_data['description'] = ucfirst($insert_data['description']);
        $insert_data['active'] = $this->input->post('active');


        $product_table_data = $insert_data;
        $product_table_data['created'] = active_user_id();
        $product_table_data['created_at'] = strtotime("now");
        $product_table_data = filter_array($product_table_data, array('submit', 'category', 'product_image'));

        $con = array('key' => 'id', 'value' => $id);
        update('products', $con, $product_table_data);

        //insert product categories
        if (isset($insert_data['category'])):
            $this->product_model->remove_product_categories($id);
            $this->product_model->add_product_categories($insert_data['category'], $id);
        endif;

        //set primary image
        if (isset($insert_data['main_image'])):
            $this->product_model->set_primary_product_image($insert_data['main_image'], $id);
        endif;

        //insert product image
        if (!empty($_FILES['userfile']['name'][0])):
            $return = $this->product_model->add_product_image($id, 1);

            if($return['status']):
                set_flashdata(array(
                    'status' => 'danger',
                    'message' => $return['msg']
                ));
                redirect('admin/product');
            endif;
        endif;

        set_flashdata(array(
            'status' => 'success',
            'message' => '<strong>Success!</strong> Item <b>"' . $insert_data['name'] . '"</b> has been updated successfully.'
        ));

        if ($this->input->post('submit') == 'save'):
            redirect('admin/product');
        else:
            redirect('admin/product/edit/' . $id);
        endif;
    }

    public function product_attr_request($id) {
        $elem = '<div id="product_attr"><div id="attr_list"';
        $ele = $this->db->get_where('products_attr_set', array('id' => $id))->row();
        if ($ele):
            $attrs = json_decode($ele->attributes);
            foreach ($attrs as $attr):
                $p_attr = $this->db->get_where('products_attr', array('id' => $attr))->row();

                $placeholder = '';
                if ($p_attr->input_type == 'dropdown'):
                    $placeholder = 'Seperate the value with comma';
                endif;
                if ($p_attr->input_type == 'dropdown' && $p_attr->is_price == '1'):
                    $placeholder = 'Enter Proudct Type';
                    $placeholder_value = 'Enter Price';
                endif;

                $required = '';
                if ($p_attr->is_required == 1):
                    $required = 'parsley-required="true"';
                endif;
                if ($p_attr->input_type == 'dropdown' && $p_attr->is_price == '1'):
                    $elem.= '<label class="control-label">' . $p_attr->attribute_code . '</label>'
                            . '<div class="product_type_wrap">'
                            . '</div>'
                            . '<div><span class="btn btn-success add_product_type" data-id ="' . $p_attr->id . '">Add More</span></div>';
                else:
                    $elem.= '<label class="control-label">' . $p_attr->attribute_code . '</label>'
                            . '<div>'
                            . '<input type="text" placeholder="' . $placeholder . '" class="form-control attr_list" ' . $required . ' name="attr[' . $p_attr->id . ']" value="' . $p_attr->deafult_value . '">'
                            . '</div>';
                endif;

            endforeach;
        endif;
        $elem.='</div></div>';

        echo json_encode($elem);
    }

    public function delete($id) {
        $res = $this->product_model->get($id);

        //removing product images
        if (!empty($res->image)):
            $array = array();
            $i = 0;
            foreach ($res->image as $v):
                $array[$i++] = $v->image_id;
            endforeach;

            $this->product_model->remove_image($array);
        endif;

        //removing product category
        if (!empty($res->categories)):
            $this->product_model->remove_product_categories($id);
        endif;

        //removing product
        $con = array('key' => 'id', 'value' => $id);
        delete('products', $con);

        set_flashdata(array(
            'status' => 'success',
            'message' => '<strong>Success!</strong> Item <b>"' . $res->sku . '"</b> has been deleted successfully.'
        ));

        redirect('admin/product');
    }

    public function review($id) {
        $data['title'] = 'Product Review';
        $data['label'] = '<strong>Product </strong> Review';

        $d = $this->product_model->get_review_list($id);
        $data['sub_label'] = 'Product Code: ' . $d->sku;

        $data['list'] = $d->review;
        $i = 0;
        foreach ($data['list'] as $k => $v):
            $data['list'][$i]->status = display_status($v->status);
            $data['list'][$i]->rating = $v->rating . ' star';
            $data['list'][$i]->created_on = user_format_date($v->created_on);
            $i++;
        endforeach;

        $data['buttons'] = array(
            array(
                'title' => 'Add Review',
                'url' => base_url('admin/product/set_review') . '/' . $id,
                'class' => 'primary',
                'icon' => 'plus'
            )
        );
        $data['fields'] = array(
            'SN',
            'Reviewed By',
            'Rating',
            'Review',
            'Reviewed On',
            'Status',
            'Action'
        );
        $data['action'] = array(
            array(
                'name' => 'Edit',
                'url' => base_url('admin/product/review_edit/' . $id),
                'icon' => 'pencil'
            ),
            array(
                'name' => 'Delete',
                'url' => base_url('admin/product/review_delete/' . $id),
                'icon' => 'trash-o'
            )
        );


        template('list', $data);
    }

    public function set_review($id) {
        $data['title'] = 'Add Product Review';
        $data['form_action'] = 'admin/product/set_review/' . $id;

        //create form
        $form['form_size'] = '6';
        $form['label'] = '<strong>Customer </strong> Information';
        $form['sub_label'] = '';
        $form['form_components'] = $this->review_form_array();
        $form['form_components'] = $form['form_components']['general'];
        $data['form'][] = $this->form_builder->build($form);

        $form['form_size'] = '6';
        $form['label'] = '<strong>Customer </strong> Review';
        $form['sub_label'] = '';
        $form['form_components'] = $this->review_form_array();
        $form['form_components'] = $form['form_components']['review'];
        $data['form'][] = $this->form_builder->build($form);

        $form['form_size'] = '12';
        $form['label'] = '';
        $form['sub_label'] = '';
        $form['form_components'] = $this->form_array();
        $form['form_components'] = $form['form_components']['buttons'];
        $data['form'][] = $this->form_builder->build($form);


        if ($this->input->post()):
            $datas = filter_array($this->input->post(), array('submit'));

            if ($this->product_model->check_review($datas['email'], $id)):
                set_flashdata(array(
                    'status' => 'danger',
                    'message' => '<strong>Failed!</strong> Only one review can be added by one user.'
                ));

                redirect('admin/product/set_review/' . $id);
            endif;

            $datas['product_id'] = $id;
            $datas['created_on'] = strtotime("now");

            $insert_id = save('products_rating', $datas);

            set_flashdata(array(
                'status' => 'success',
                'message' => '<strong>Success!</strong> Product review added successfully.'
            ));

            if ($this->input->post('submit') == 'save'):
                redirect('admin/product/review/' . $id);
            else:
                redirect('admin/product/review_edit/' . $id . '/' . $insert_id);
            endif;

        else:
            template('create', $data);
        endif;
    }

    public function review_edit($product_id, $id) {
        $data['title'] = 'Add Product Review';
        $data['form_action'] = 'admin/product/review_edit/' . $product_id . '/' . $id;

        $d = (array) $this->product_model->get_review($id);

        //create form
        $form['form_size'] = '6';
        $form['label'] = '<strong>Customer </strong> Information';
        $form['sub_label'] = '';
        $form['form_components'] = $this->review_form_array($d);
        $form['form_components'] = $form['form_components']['general'];
        $data['form'][] = $this->form_builder->build($form);

        $form['form_size'] = '6';
        $form['label'] = '<strong>Customer </strong> Review';
        $form['sub_label'] = '';
        $form['form_components'] = $this->review_form_array($d);
        $form['form_components'] = $form['form_components']['review'];
        $data['form'][] = $this->form_builder->build($form);

        $form['form_size'] = '12';
        $form['label'] = '';
        $form['sub_label'] = '';
        $form['form_components'] = $this->form_array();
        $form['form_components'] = $form['form_components']['buttons'];
        $data['form'][] = $this->form_builder->build($form);

        if ($this->input->post()):
            $datas = filter_array($this->input->post(), array('submit'));

            $con = array('key' => 'id', 'value' => $id);
            update('products_rating', $con, $datas);

            set_flashdata(array(
                'status' => 'success',
                'message' => '<strong>Success!</strong> Product review updated successfully.'
            ));

            if ($this->input->post('submit') == 'save'):
                redirect('admin/product/review/' . $product_id);
            else:
                redirect('admin/product/review_edit/' . $product_id . '/' . $id);
            endif;

        else:
            template('create', $data);
        endif;
    }

    function review_form_array($data = array()) {
        $form_field['general'] = array(
            array(
                'name' => 'name',
                'type' => 'text',
                'label' => 'Name*',
                'value' => isset($data['name']) ? $data['name'] : FALSE,
                'class' => 'form-control',
                'parsley-required' => 'true',
            ),
            array(
                'name' => 'email',
                'type' => 'text',
                'label' => 'Email*',
                'value' => isset($data['email']) ? $data['email'] : FALSE,
                'class' => 'form-control',
                'parsley-required' => 'true',
                'parsley-type' => 'email'
            ),
            array(
                'name' => 'rating',
                'type' => 'dropdown',
                'selected' => isset($data['rating']) ? $data['rating'] : FALSE,
                'option' => $this->_rating(),
                'label' => 'Rating*',
                'extra' => 'class="form-control selectpicker"',
                'parsley-required' => 'true',
            ),
            array(
                'name' => 'status',
                'type' => 'checkbox',
                'value' => '1',
                'checked' => isset($data['status']) ? $data['status'] : TRUE,
                'class' => 'form-control',
                'label' => 'Status',
            ),
        );
        $form_field['review'] = array(
            array(
                'name' => 'review',
                'type' => 'textarea',
                'label' => 'Review',
                'value' => isset($data['review']) ? $data['review'] : FALSE,
                'class' => 'form-control',
                'parsley-required' => 'true',
            )
        );
        $form_array['buttons'] = array(
            array(
                'name' => 'submit',
                'type' => 'submit',
                'value' => 'save',
                'class' => 'btn btn-theme',
                'content' => 'Save & Exit'
            ),
            array(
                'name' => 'submit',
                'type' => 'submit',
                'value' => 'save_edit',
                'class' => 'btn btn-theme',
                'content' => 'Save & Continue Edit'
            ),
            array(
                'name' => 'reset',
                'type' => 'reset',
                'value' => 'Reset',
                'class' => 'btn',
                'onclick' => "$( 'form' ).parsley( 'destroy' )"
            )
        );

        return $form_field;
    }

    public function review_delete($product_id, $id) {
        $con = array(
            'key' => 'id',
            'value' => $id
        );

        delete('products_rating', $con);

        set_flashdata(array(
            'status' => 'success',
            'message' => '<strong>Success!</strong> Product review removed successfully.'
        ));

        redirect('admin/product/review/' . $product_id);
    }

    function set_default_image($redirect_id, $id) {
        $con = array('key' => 'product_id', 'value' => $redirect_id);
        update('products_images', $con, array('status' => '0'));

        $con = array('key' => 'image_id', 'value' => $id);
        update('products_images', $con, array('status' => '1'));

        set_flashdata(array(
            'status' => 'success',
            'message' => '<strong>Success!</strong> Default image set successfully'
        ));

        redirect('admin/product/edit/' . $redirect_id);
    }

    function remove_image($redirect_id, $id) {
        remove_image($id);
        $con = array('key' => 'image_id', 'value' => $id);
        delete('products_images', $con);

        set_flashdata(array(
            'status' => 'success',
            'message' => '<strong>Success!</strong> Product Image delete successfully'
        ));

        redirect('admin/product/edit/' . $redirect_id);
    }

    function _rating() {
        return array(
            '1' => '1 star',
            '2' => '2 star',
            '3' => '3 star',
            '4' => '4 star',
            '5' => '5 star'
        );
    }

    function show_product_price_attr($value) {
        $array = explode(';', $value);
        foreach ($array as $a):
            $newArray[] = explode('+', $a);
        endforeach;

        return $newArray;
    }

}
