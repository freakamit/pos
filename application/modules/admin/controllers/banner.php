<?php

class Banner extends CI_Controller {

    private $_table = 'banner';

    public function __construct() {
        parent::__construct();

        check_user_session();

        $this->load->model('banner_model');
    }

    public function index() {
        $data['title'] = 'Banner list';
        $data['label'] = '<strong>Banner</strong> list';
        $data['sub_label'] = 'List of Banner';
        $data['list'] = $this->banner_model->get_all();

        $i = 0;
        foreach ($data['list'] as $k => $v):
            $data['list'][$i]->created_on = user_format_date($v->created_on);
            $data['list'][$i]->status = display_status($v->status);
            $data['list'][$i]->image = show_image($v->image, 'product_img', 'return');
            $i++;
        endforeach;

        $data['buttons'] = array(
            array(
                'title' => 'Add New Banner',
                'url' => base_url('admin/banner/create'),
                'class' => 'primary',
                'icon' => 'plus'
            )
        );
        $data['fields'] = array(
            'SN',
            'Image',
            'Primary Tag Line',
            'Secondary Tag Line',
            'Created On',
            'Status',
            'Action'
        );
        $data['action'] = array(
            array(
                'name' => 'Edit',
                'url' => base_url('admin/banner/edit'),
                'icon' => 'pencil'
            ),
            array(
                'name' => 'Delete',
                'url' => base_url('admin/banner/delete'),
                'icon' => 'trash-o'
            )
        );
        template('list', $data);
    }

    function form_array($data = array()) {
        $form_array['information'] = array(
            array(
                'name' => 'url',
                'type' => 'text',
                'value' => isset($data['url']) ? $data['url'] : false,
                'placeholder' => 'url',
                'class' => 'form-control',
                'label' => 'URL',
            ),
            array(
                'name' => 'primary_tag',
                'type' => 'text',
                'value' => isset($data['primary_tag']) ? $data['primary_tag'] : false,
                'placeholder' => 'Primary Tag Line',
                'class' => 'form-control',
                'label' => 'Primary Tag Line',
            ),
            array(
                'name' => 'secondary_tag',
                'type' => 'text',
                'value' => isset($data['secondary_tag']) ? $data['secondary_tag'] : false,
                'placeholder' => 'Secondary Tag Line',
                'class' => 'form-control',
                'label' => 'Secondary Tag Line',
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
        $form_array['image'] = array(
            array(
                'name' => 'userfile',
                'type' => 'upload',
                'class' => 'form-control',
                'label' => 'Banner Image',
                'description' => '[Dimension: {Width: 2500px | Height: 1938px}]'
            ),
        );

        if (isset($data['image'])):
            $form_array['image'][] = array(
                'name' => '',
                'label' => '',
                'type' => 'image_block',
                'value' => $data['image'],
                'status' => $data['status'],
                'class' => 'banner_img',
                'input' => FALSE
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

    public function create() {
        $data['title'] = 'Create Banner';
        $data['form_action'] = 'admin/banner/create';

        $form['form_size'] = '6';
        $form['label'] = '<strong>Banner</strong>  Information';
        $form['sub_label'] = '';
        $form['form_components'] = $this->form_array($this->input->post());
        $form['form_components'] = $form['form_components']['information'];
        $data['form'][] = $this->form_builder->build($form);

        $form['form_size'] = '6';
        $form['label'] = '<strong>Banner</strong>  Image';
        $form['sub_label'] = '';
        $form['form_components'] = $this->form_array($this->input->post());
        $form['form_components'] = $form['form_components']['image'];
        $data['form'][] = $this->form_builder->build($form);

        $form['label'] = '';
        $form['sub_label'] = '';
        $form['form_components'] = $this->form_array();
        $form['form_components'] = $form['form_components']['buttons'];
        $data['form'][] = $this->form_builder->build($form);

        if ($this->input->post()):
            $datas = filter_array($this->input->post(), array('submit'));

            if (!empty($_FILES['userfile']['name'])):
                $datas['image'] = upload('uploads/banner');
            else:
                set_flashdata(array(
                    'status' => 'danger',
                    'message' => '<strong>Error!</strong> Please Select Image.'
                ));

                redirect('admin/banner/create');
            endif;

            $datas['created_on'] = strtotime("now");

            $insert_id = save($this->_table, $datas);

            set_flashdata(array(
                'status' => 'success',
                'message' => '<strong>Success!</strong> Banner created successfully.'
            ));
            if ($this->input->post('submit') == 'save'):
                redirect('admin/banner');
            else:
                redirect('admin/banner/edit/' . $insert_id);
            endif;


        else:
            template('create', $data);
        endif;
    }

    public function edit($id) {
        $data['title'] = 'Update Banner';
        $d = (array) $this->banner_model->get($id);

        $data['form_action'] = 'admin/banner/edit/' . $id;

        $form['form_size'] = '6';
        $form['label'] = '<strong>Banner</strong>  Information';
        $form['sub_label'] = '';
        $form['form_components'] = $this->form_array($d);
        $form['form_components'] = $form['form_components']['information'];
        $data['form'][] = $this->form_builder->build($form);

        $form['form_size'] = '6';
        $form['label'] = '<strong>Banner</strong>  Image';
        $form['sub_label'] = '';
        $form['form_components'] = $this->form_array($d);
        $form['form_components'] = $form['form_components']['image'];
        $data['form'][] = $this->form_builder->build($form);

        $form['label'] = '';
        $form['sub_label'] = '';
        $form['form_components'] = $this->form_array();
        $form['form_components'] = $form['form_components']['buttons'];
        $data['form'][] = $this->form_builder->build($form);

        if ($this->input->post()):
            $datas = $this->input->post();

            if (!isset($datas['status'])):
                $datas['status'] = 0;
            endif;

            if (!empty($_FILES['userfile']['name'])):
                remove_image($d['image']);
                $datas['image'] = upload('uploads/banner');
            endif;

            $datas = filter_array($datas, array('main_image', 'submit'));

            $con = array(
                'key' => 'id',
                'value' => $id
            );

            update($this->_table, $con, $datas);

            set_flashdata(array(
                'status' => 'success',
                'message' => '<strong>Success!</strong> Group updated successfully.'
            ));

            if ($this->input->post('submit') == 'save'):
                redirect('admin/banner');
            else:
                redirect('admin/banner/edit/' . $id);
            endif;

        else:
            template('create', $data);
        endif;
    }

    public function delete($id) {
        $res = $this->banner_model->get($id);

        $con = array(
            'key' => 'id',
            'value' => $id
        );
        remove_image($res->image);
        remove_image($res->sub_image);

        delete($this->_table, $con);

        set_flashdata(array(
            'status' => 'success',
            'message' => '<strong>Success</strong> Banner deleted successfully'
        ));

        redirect('admin/banner');
    }

    function _type() {
        return array(
            '0' => 'Slider',
            '1' => 'Static'
        );
    }

}
