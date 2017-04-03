<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Groups extends CI_Controller {

    var $_table = 'groups';

    function __construct() {
        parent::__construct();

        check_user_session();

        $this->load->model('groups_model');
    }

    public function form_array($data = array()) {
        $form_array = array(
            array(
                'name' => 'name',
                'type' => 'text',
                'value' => isset($data['name']) ? $data['name'] : false,
                'placeholder' => 'Group Name',
                'class' => 'form-control',
                'label' => 'Group Name',
                'parsley-required' => 'true'
            ),
            array(
                'name' => 'type',
                'type' => 'dropdown',
                'option' => groups_type(),
                'selected' => isset($data['type']) ? $data['type'] : false,
                'label' => 'Group Type',
                'extra' => 'class ="form-control" parsley-required="true"',
            ),
            array(
                'name' => 'description',
                'type' => 'textarea',
                'value' => isset($data['description']) ? $data['description'] : false,
                'placeholder' => 'Description',
                'class' => 'form-control',
                'label' => 'Description'
            ),
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
        $data['title'] = 'Group list';
        $data['label'] = '<strong>Group</strong> list';
        $data['sub_label'] = 'List of Admin group';
        $data['list'] = $this->groups_model->get_all();
        $data['buttons'] = array(
            array(
                'title' => 'Add New Group',
                'url' => base_url('admin/groups/create'),
                'class' => 'primary',
                'icon' => 'plus'
            )
        );
        $data['fields'] = array(
            'SN',
            'Group Name',
            'Description',
            'Type',
            'Action'
        );
        $data['action'] = array(
//            array(
//                'name' => 'Assign Role',
//                'url' => base_url('admin/permission/assign'),
//                'icon' => 'gear'
//            ),
            array(
                'name' => 'Edit',
                'url' => base_url('admin/groups/edit'),
                'icon' => 'pencil'
            ),
            array(
                'name' => 'Delete',
                'url' => base_url('admin/groups/delete'),
                'icon' => 'trash-o'
            )
        );
        template('list', $data);
    }

    public function create() {
        $data['title'] = 'Create Group';
        $form['form_size'] = '12';
        $form['label'] = 'Create Group';
        $form['sub_label'] = 'Groups';
        $form['form_components'] = $this->form_array();
        $data['form'][] = $this->form_builder->build($form);
        $data['form_action'] = 'admin/groups/create';

        if ($this->input->post()):
            $remove_array = array(
                'submit'
            );
            $insert_data = filter_array($this->input->post(), $remove_array);
            $insert_id = save($this->_table, $insert_data);
            set_flashdata(array(
                'status' => 'success',
                'message' => '<strong>Success!</strong> Group added successfully.'
            ));
            if ($this->input->post('submit') == 'save'):
                redirect('admin/groups');
            else:
                redirect('admin/groups/edit/' . $insert_id);
            endif;
        else:
            template('create', $data);
        endif;
    }

    public function edit($id) {
        $data['title'] = 'Update Group';
        $d = (array) $this->groups_model->get($id);

        $form['label'] = 'Update Group';
        $form['sub_label'] = 'Groups';
        $form['form_size'] = '12';
        $form['form_components'] = $this->form_array($d);


        $data['form'][] = $this->form_builder->build($form);
        $data['form_action'] = 'admin/groups/edit/' . $id;

        if ($this->input->post()):
            $remove_array = array(
                'submit'
            );
            $update_data = filter_array($this->input->post(), $remove_array);
            $con = array(
                'key' => 'id',
                'value' => $id
            );
            update($this->_table, $con, $update_data);

            set_flashdata(array(
                'status' => 'success',
                'message' => '<strong>Success!</strong> Group updated successfully.'
            ));

            if ($this->input->post('submit') == 'save'):
                redirect('admin/groups');
            else:
                redirect('admin/groups/edit/' . $id);
            endif;
        else:
            template('create', $data);
        endif;
    }

    public function delete($id) {
        $con = array(
            'key' => 'id',
            'value' => $id
        );
        delete($this->_table, $con);

        set_flashdata(array(
            'status' => 'success',
            'message' => '<strong>Success!</strong> Group deleted successfully.'
        ));

        redirect('admin/groups');
    }

}
