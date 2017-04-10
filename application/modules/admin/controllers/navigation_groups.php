<?php

class Navigation_groups extends CI_Controller
{

    private $_table = 'navigation_groups';

    public function __construct()
    {
        parent::__construct();

        check_user_session();

        $this->load->model('navigation_model', 'navigation');
    }

    function form_array($values = array())
    {
        $form_array = array(
            array(
                'name' => 'title',
                'type' => 'text',
                'value' => isset($values['title']) ? $values['title'] : false,
                'class' => 'form-control slug_title',
                'label' => 'Name'
            ),
            array(
                'name' => 'abbrev',
                'type' => 'text',
                'value' => isset($values['abbrev']) ? $values['abbrev'] : false,
                'class' => 'form-control slug',
                'label' => 'Abbrev'
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

    public function index()
    {
        $data['title'] = 'Navigation Group List';
        $data['label'] = '<strong>Navigation Group</strong> list';
        $data['sub_label'] = 'List of Navigtion Group';

        $data['list'] = $this->navigation->get_all($this->_table);

        $data['fields'] = array(
            'SN',
            'Title',
            'Abbrev',
            'Action'
        );


        $data['buttons'] = array(
            array(
                'title' => 'Add New Group',
                'url' => base_url('admin/navigation_groups/create'),
                'class' => 'primary',
                'icon' => 'plus'
            )
        );

        $data['action'][] = array(
            'name' => 'Edit',
            'url' => base_url('admin/navigation_groups/edit'),
            'icon' => 'pencil'
        );

        $data['action'][] = array(
            'name' => 'Delete',
            'url' => base_url('admin/navigation_groups/delete'),
            'icon' => 'trash-o'
        );
        template('list', $data);
    }

    public function create()
    {
        $data['title'] = 'Navigation Group';
        $form['form_size'] = '12';
        $form['label'] = 'Create Navigation Group';
        $form['sub_label'] = '';
        $form['form_components'] = $this->form_array();
        $data['form'][] = $this->form_builder->build($form);
        $data['form_action'] = 'admin/navigation_groups/create';

        if ($this->input->post()):
            if (!check_duplication('navigation_groups', array('abbrev' => $this->input->post('abbrev')))):
                set_flashdata(array(
                    'status' => 'danger',
                    'message' => 'Navigation Group already exist'
                ));

                redirect('admin/navigation_groups/create');
            endif;

            $remove_array = array(
                'submit'
            );
            $insert_data = filter_array($this->input->post(), $remove_array);
            $insert_id = save($this->_table, $insert_data);
            set_flashdata(array(
                'status' => 'success',
                'message' => '<strong>Success!</strong> Navigation Group added successfully.'
            ));
            if ($this->input->post('submit') == 'save'):
                redirect('admin/navigation_groups');
            else:
                redirect('admin/navigation_groups/edit/' . $insert_id);
            endif;

        else:
            template('create', $data);
        endif;
    }

    public function edit($id)
    {
        $data['title'] = 'Update Navigation Group';
        $d = (array)$this->navigation->get_where('navigation_groups', $id);

        $form['label'] = 'Update Navigation Group';
        $form['sub_label'] = 'Navigation Group';
        $form['form_size'] = '12';
        $form['form_components'] = $this->form_array($d);

        $data['form'][] = $this->form_builder->build($form);
        $data['form_action'] = 'admin/navigation_groups/edit/' . $id;

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
                'message' => '<strong>Success!</strong> Navigation Group updated successfully.'
            ));

            if ($this->input->post('submit') == 'save'):
                redirect('admin/navigation_groups');
            else:
                redirect('admin/navigation_groups/edit/' . $id);
            endif;
        else:
            template('create', $data);
        endif;
    }

    public function delete($id)
    {
        $con = array('key' => 'id', 'value' => $id);
        delete($this->_table, $con);

        set_flashdata(array(
            'status' => 'success',
            'message' => '<strong>Navigation Group</strong> deleted successfully'
        ));

        redirect('admin/navigation_groups');
    }

}
