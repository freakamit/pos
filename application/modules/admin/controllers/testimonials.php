<?php

class Testimonials extends CI_Controller {

    var $_table = 'testimonial';

    public function __construct() {
        parent::__construct();

        check_user_session();

        $this->load->model('testimonial_model');
    }

    public function index() {
        $data['title'] = 'Testimonial list';
        $data['label'] = '<strong>Testimonial</strong> list';
        $data['sub_label'] = 'List of Testimonial';
        $data['list'] = $this->testimonial_model->get_all();

        $i = 0;
        foreach ($data['list'] as $k => $v):
            $data['list'][$i]->posted_on = user_format_date($v->posted_on);
            $data['list'][$i]->status = display_status($v->status);
            $data['list'][$i]->image = show_image($v->image, 'product_img', 'return');
            $i++;
        endforeach;

        $data['buttons'] = array(
            array(
                'title' => 'Add New Testimonial',
                'url' => base_url('admin/testimonials/create'),
                'class' => 'primary',
                'icon' => 'plus'
            )
        );
        $data['fields'] = array(
            'SN',
            'Name',
            'Image',
            'Posted On',
            'Status',
            'Action'
        );
        $data['action'] = array(
            array(
                'name' => 'Edit',
                'url' => base_url('admin/testimonials/edit'),
                'icon' => 'pencil'
            ),
            array(
                'name' => 'Delete',
                'url' => base_url('admin/testimonials/delete'),
                'icon' => 'trash-o'
            )
        );
        template('list', $data);
    }

    function form_array($data = array()) {
        $form_array['information'] = array(
            array(
                'name' => 'name',
                'type' => 'text',
                'value' => isset($data['name']) ? $data['name'] : false,
                'placeholder' => 'Name',
                'class' => 'form-control',
                'label' => 'Name',
            ),
            array(
                'name' => 'message',
                'type' => 'textarea',
                'value' => isset($data['message']) ? $data['message'] : false,
                'placeholder' => 'Message',
                'class' => 'form-control',
                'label' => 'Message',
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
                'label' => 'Image',
            ),
        );

        if (isset($data['image'])):
            $form_array['image'][] = array(
                'name' => '',
                'label' => '',
                'type' => 'image_block',
                'value' => $data['image'],
                'status' => $data['status'],
                'class' => 'product_img'
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
        $data['title'] = 'Create Testimonial';
        $data['form_action'] = 'admin/testimonials/create';

        $form['form_size'] = '6';
        $form['label'] = '<strong>Testimonial</strong>  Detail';
        $form['sub_label'] = '';
        $form['form_components'] = $this->form_array($this->input->post());
        $form['form_components'] = $form['form_components']['information'];
        $data['form'][] = $this->form_builder->build($form);

        $form['form_size'] = '6';
        $form['label'] = '<strong>Testimonial</strong>  Image';
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
                $datas['image'] = upload('uploads/testimonial');
            endif;

            $datas['posted_on'] = strtotime("now");
            $insert_id = save($this->_table, $datas);

            set_flashdata(array(
                'status' => 'success',
                'message' => '<strong>Success!</strong> Testimonial added successfully.'
            ));
            if ($this->input->post('submit') == 'save'):
                redirect('admin/testimonials');
            else:
                redirect('admin/testimonials/edit/' . $insert_id);
            endif;


        else:
            template('create', $data);
        endif;
    }

    public function edit($id) {
        $data['title'] = 'Update Testimonial';
        $d = (array) $this->testimonial_model->get($id);

        $data['form_action'] = 'admin/testimonials/edit/' . $id;

        $form['form_size'] = '6';
        $form['label'] = '<strong>Testimonial</strong>  Detail';
        $form['sub_label'] = '';
        $form['form_components'] = $this->form_array($d);
        $form['form_components'] = $form['form_components']['information'];
        $data['form'][] = $this->form_builder->build($form);

        $form['form_size'] = '6';
        $form['label'] = '<strong>Testimonial</strong>  Image';
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
            $datas = filter_array($this->input->post(), array('submit'));

            if (!isset($datas['status'])):
                $datas['status'] = 0;
            endif;
            
            if (isset($datas['remove_image'])):
                remove_image($datas['remove_image'][0]);
                $datas['image'] = 0;
                $datas = filter_array($datas, array('remove_image'));
            endif;

            if (!empty($_FILES['userfile']['name'])):
                remove_image($d['image']);
                $datas['image'] = upload('uploads/testimonial');
            endif;

            $con = array(
                'key' => 'id',
                'value' => $id
            );

            $datas = filter_array($datas, array('main_image'));

            update($this->_table, $con, $datas);

            set_flashdata(array(
                'status' => 'success',
                'message' => '<strong>Success!</strong> Testimonial updated successfully.'
            ));

            if ($this->input->post('submit') == 'save'):
                redirect('admin/testimonials');
            else:
                redirect('admin/testimonials/edit/' . $id);
            endif;
        else:
            template('create', $data);
        endif;
    }

    public function delete($id) {
        $d = (array) $this->testimonial_model->get($id);

        if ($d['image'] != 0):
            remove_image($d['image']);
        endif;

        $con = array(
            'key' => 'id',
            'value' => $id
        );

        delete($this->_table, $con);

        set_flashdata(array(
            'status' => 'success',
            'message' => '<strong>Success!</strong> Testimonial deleted successfully.'
        ));

        redirect('admin/testimonials');
    }

}
