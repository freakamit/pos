<?php

class Category extends CI_Controller {

    public function __construct() {
        parent::__construct();
        check_user_session();

        $this->load->model('category_model');
    }

    public function form_array($data = array()) {
        $form_array['parent_categories'] = array(
            array(
                'name' => 'parent_id',
                'type' => 'dropdown',
                'option' => category_list(),
                'selected' => isset($data['parent_id']) ? $data['parent_id'] : FALSE,
                'label' => 'Parent Category',
                'extra' => 'class ="form-control selectpicker"',
            ),
        );
        $form_array['general'] = array(
            array(
                'name' => 'category_name',
                'type' => 'text',
                'value' => isset($data['category_name']) ? $data['category_name'] : false,
                'placeholder' => 'Category Name',
                'class' => 'form-control slug_title',
                'label' => 'Category Name',
                'parsley-required' => 'true',
                'parsley-rangelength' => '[3,50]',
                'parsley-trigger' => "keyup"
            ),
            array(
                'name' => 'category_slug',
                'type' => 'text',
                'value' => isset($data['category_slug']) ? $data['category_slug'] : false,
                'placeholder' => 'Category Slug',
                'class' => 'form-control slug',
                'label' => 'Category Slug',
                'parsley-required' => 'true',
                'parsley-rangelength' => '[3,50]',
                'parsley-trigger' => "keyup"
            ),
            array(
                'name' => 'description',
                'type' => 'textarea',
                'value' => isset($data['description']) ? $data['description'] : false,
                'placeholder' => 'Description',
                'class' => 'form-control',
                'label' => 'Description',
                'id' => 'ckeditor'
            ),
            array(
                'name' => 'ordering',
                'type' => 'text',
                'value' => isset($data['ordering']) ? $data['ordering'] : false,
                'class' => 'form-control',
                'label' => 'Ordering',
                'placeholder' => 'Ordering',
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

        $form_array['category_image'] = array(
            array(
                'name' => 'userfile',
                'type' => 'upload',
                'class' => 'form-control',
                'label' => 'Category Image',
            ),
        );
        if (isset($data['category_banner_image']) && $data['category_banner_image'] != 0):
            $form_array['category_image'][] = array(
                'name' => '',
                'label' => '',
                'type' => 'image_block',
                'value' => $data['category_banner_image'],
                'status' => '',
                'class' => 'product_img',
                'option' => array(
                    array(
                        'name' => 'Remove Image',
                        'url' => base_url('admin/category/remove_banner') . '/' . $data['id'],
                        'btn_type' => '',
                        'class' => 'fa fa-trash-o'
                    ),
                )
            );
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

    public function listing($parent_id = 0) {
        $data['title'] = 'Category List';
        $data['label'] = '<strong>Category</strong> list';
        $data['sub_label'] = 'List of Category ';
        $data['list'] = $this->category_model->get_all($parent_id);
        $data['bulk_action'] = base_url('admin/category/listing') . '/' . $parent_id;

        $i = 0;
        foreach ($data['list'] as $k => $v):
            $data['list'][$i]->active = display_status($v->active);
            $i++;
        endforeach;

        $data['buttons'] = array(
            array(
                'title' => 'Add New Category',
                'url' => base_url('admin/category/create/' . $parent_id),
                'class' => 'primary',
                'icon' => 'plus'
            )
        );
        $data['fields'] = array(
            'Name',
            'Slug',
            'Status',
            'Action'
        );
        $data['action'] = array(
            array(
                'name' => 'View Products',
                'url' => base_url('admin/product/listing'),
                'icon' => 'eye'
            ),
            array(
                'name' => 'View Sub Categories',
                'url' => base_url('admin/category/listing'),
                'icon' => 'list'
            ),
            array(
                'name' => 'Edit',
                'url' => base_url('admin/category/edit'),
                'icon' => 'pencil'
            ),
            array(
                'name' => 'Delete',
                'url' => base_url('admin/category/delete/' . $parent_id),
                'icon' => 'trash-o'
            )
        );

        if ($this->input->post()):
            $bulk_action = $this->input->post();

            foreach ($bulk_action['bulk'] as $b):
                $con = array('key' => 'id', 'value' => $b);
                update('category', $con, array('active' => $bulk_action['bulk_action']));
            endforeach;

            set_flashdata(array(
                'status' => 'success',
                'message' => '<strong>Success!</strong> Status changed successfully'
            ));

            redirect('admin/category/listing/' . $parent_id);
        else:
            template('list', $data);
        endif;
    }

    public function create($parent_id = 0) {
        $data['title'] = 'Create Category';
        $data['form_action'] = 'admin/category/save/' . $parent_id;

        if ($parent_id == 0):
            $parent_category = 'Main Category';
        else:
            $parent_category = 'Parent Category: ' . $this->category_model->get($parent_id)->category_name;
        endif;
        $form['form_size'] = '12';
        $form['label'] = '<strong>General </strong>  Information';
        $form['sub_label'] = $parent_category;
        $form['form_components'] = $this->form_array();
        $form['form_components'] = $form['form_components']['general'];
        $data['form'][] = $this->form_builder->build($form);

        $form['form_size'] = '12';
        $form['label'] = '<strong>Category</strong> Image';
        $form['sub_label'] = '';
        $form['form_components'] = $this->form_array();
        $form['form_components'] = $form['form_components']['category_image'];
        $data['form'][] = $this->form_builder->build($form);

        $form['form_size'] = '12';
        $form['label'] = '';
        $form['sub_label'] = '';
        $form['form_components'] = $this->form_array();
        $form['form_components'] = $form['form_components']['buttons'];
        $data['form'][] = $this->form_builder->build($form);

        template('create', $data);
    }

    function save($parent_id) {
        $insert_data = $this->input->post();

        if (!check_duplication('category', array('category_slug' => $insert_data['category_slug']))):
            set_flashdata(array(
                'status' => 'danger',
                'message' => '<strong>Sorry!</strong> Category already exist'
            ));

            redirect('admin/category/create');
        endif;

        if (!empty($_FILES['userfile']['name'])):
            $insert_data['category_banner_image'] = upload('uploads/category');
        endif;

        $insert_data['parent_id'] = $parent_id;
        $insert_data = filter_array($insert_data, array('submit'));

        $insert_id = save('category', $insert_data);

        set_flashdata(array(
            'status' => 'success',
            'message' => '<strong>Success!</strong> Category <b>"' . $insert_data['category_name'] . '"</b> has been added successfully.'
        ));


        if ($this->input->post('submit') == 'save'):
            redirect('admin/category/listing/' . $parent_id);
        else:
            redirect('admin/category/edit/' . $insert_id);
        endif;
    }

    function edit($id) {
        $data['title'] = 'Edit Category';
        $d = (array) $this->category_model->get($id);

        $data['form_action'] = 'admin/category/edit/' . $id;

        $form['form_size'] = '12';
        $form['label'] = '<strong>General </strong>  Information';
        $form['sub_label'] = '';
        $form['form_components'] = $this->form_array($d);
        $form['form_merge'] = $form['form_components']['parent_categories'];
        foreach ($form['form_components']['general'] as $f):
            $form['form_merge'][] = $f;
        endforeach;
        $form['form_components'] = $form['form_merge'];
        $data['form'][] = $this->form_builder->build($form);

        $form['form_size'] = '12';
        $form['label'] = '<strong>Category</strong> Image';
        $form['sub_label'] = '';
        $form['form_components'] = $this->form_array($d);
        $form['form_components'] = $form['form_components']['category_image'];
        $data['form'][] = $this->form_builder->build($form);

        $form['form_size'] = '12';
        $form['label'] = '';
        $form['sub_label'] = '';
        $form['form_components'] = $this->form_array($d);
        $form['form_components'] = $form['form_components']['buttons'];
        $data['form'][] = $this->form_builder->build($form);

        if ($this->input->post()):
            $insert_data = $this->input->post();

            $con = array(
                'key' => 'category_id',
                'value' => $id
            );

            if (!isset($insert_data['active'])):
                $insert_data['active'] = '0';
            endif;

            if (!empty($_FILES['userfile']['name'])):
                remove_image($d['category_banner_image']);
                $insert_data['category_banner_image'] = upload('uploads/category');
            endif;

            $insert_data = filter_array($insert_data, array('submit', 'display_home'));

            $con = array('key' => 'id', 'value' => $id);
            update('category', $con, $insert_data);

            set_flashdata(array(
                'status' => 'success',
                'message' => '<strong>Success!</strong> Category <b>"' . $insert_data['category_name'] . '"</b> has been updated successfully.'
            ));


            if ($this->input->post('submit') == 'save'):
                redirect('admin/category/listing/' . $insert_data['parent_id']);
            else:
                redirect('admin/category/edit/' . $id);
            endif;
        else:
            template('create', $data);
        endif;
    }

    function delete($parent_id, $id) {
        $con = array('key' => 'id', 'value' => $id);
        delete('category', $con);

        set_flashdata(array(
            'status' => 'success',
            'message' => '<strong>Success!</strong> Category Deleted Successfully'
        ));

        if ($parent_id == 0):
            redirect('admin/category');
        else:
            redirect('admin/category/listing/' . $parent_id);
        endif;
    }

    function remove_banner($id) {
        $d = $this->category_model->get($id);

        remove_image($d->category_banner_image);

        $con = array('key' => 'id', 'value' => $id);
        update('category', $con, array('category_banner_image' => 0));

        set_flashdata(array(
            'status' => 'success',
            'message' => '<strong>Success!</strong> Category Image removed successfully'
        ));

        redirect('admin/category/edit/' . $id);
    }


}
