<?php

class Navigation extends CI_Controller
{

    private $_table = 'navigation_links';

    public function __construct()
    {
        parent::__construct();

        check_user_session();

        $this->load->model('navigation_model');
    }

    function form_array($values = array())
    {
        $form_array = array(
            array(
                'name' => 'title',
                'type' => 'text',
                'value' => isset($values['title']) ? $values['title'] : false,
                'placeholder' => 'Title',
                'class' => 'form-control',
                'label' => 'Title',
                'parsley-required' => 'true'
            ),
            array(
                'name' => 'navigation_group_id',
                'type' => 'dropdown',
                'option' => $this->navigation_model->get_group(),
                'selected' => isset($values['navigation_group_id']) ? $values['navigation_group_id'] : false,
                'label' => 'Navigation Group',
                'extra' => 'class="form-control selectpicker nav_group" data-parsley-required="true"'
            ),
            array(
                'name' => 'parent',
                'type' => 'dropdown',
                'option' => isset($values['navigation_group_id']) ? $this->navigation_model->get_child_list($values['navigation_group_id']) : array('' => 'Please Select Navigation Group'),
                'selected' => isset($values['parent']) ? $values['parent'] : false,
                'extra' => 'class="form-control selectpicker nav_parent"',
                'label' => 'Navigation Parent',
            ),
            array(
                'name' => 'link_type',
                'type' => 'dropdown',
                'option' => $this->link_type(),
                'selected' => isset($values['link_type']) ? $values['link_type'] : false,
                'label' => 'Link Type',
                'extra' => 'class="form-control selectpicker link_type_option" data-parsley-required="true"'
            )
        );
        if (isset($values['link_type'])):
            if ($values['link_type'] == 'uri'):
                $form_array[] = array(
                    'name' => 'uri',
                    'type' => 'text',
                    'value' => $values['uri'],
                    'label' => 'URI (Site Link)',
                    'class' => 'form-control',
                );
            elseif ($values['link_type'] == 'url'):
                $form_array[] = array(
                    'name' => 'url',
                    'type' => 'text',
                    'value' => $values['url'],
                    'label' => 'URL',
                    'class' => 'form-control',
                );
            elseif ($values['link_type'] == 'module'):
                $form_array[] = array(
                    'name' => 'module',
                    'type' => 'dropdown',
                    'option' => $this->get_module_list(),
                    'selected' => $values['module'],
                    'extra' => 'class="form-control selectpicker"',
                    'label' => 'Module Name'
                );
            elseif ($values['link_type'] == 'page'):
            endif;
        else:
            $form_array[] = array(
                'name' => '',
                'type' => 'caption',
                'label' => 'Please Select Link Type For More Option',
                'extra' => 'class="linktype" id="link_type"'
            );
        endif;

        $form_array[] = array(
            'name' => 'class',
            'type' => 'text',
            'value' => isset($values['class']) ? $values['class'] : false,
            'class' => 'form-control',
            'label' => 'Class'
        );
        $form_array[] = array(
            'name' => 'position',
            'type' => 'text',
            'value' => isset($values['position']) ? $values['position'] : false,
            'class' => 'form-control',
            'label' => 'Position'
        );
        $form_array[] = array(
            'name' => 'target',
            'type' => 'dropdown',
            'option' => $this->navigation_model->target(),
            'selected' => isset($values['target']) ? $values['target'] : false,
            'label' => 'Target',
            'extra' => 'class="form-control selectpicker"'
        );
        $form_array[] = array(
            'name' => 'submit',
            'type' => 'submit',
            'value' => 'save',
            'class' => 'btn btn-theme',
            'content' => 'Save & Exit'
        );
        $form_array[] = array(
            'name' => 'submit',
            'type' => 'submit',
            'value' => 'save_edit',
            'class' => 'btn btn-theme',
            'content' => 'Save & Continue Edit'
        );
        $form_array[] = array(
            'name' => 'reset',
            'type' => 'reset',
            'value' => 'Reset',
            'class' => 'btn',
            'onclick' => "$( 'form' ).parsley( 'destroy' )"
        );

        return $form_array;
    }

    public function index()
    {
        $this->listing();
    }

    public function listing($id = 0)
    {
        if ($id):
            $nav_title = $this->navigation_model->get_nav_title($id);
        endif;

        $data['title'] = 'Navigation Link';
        $data['label'] = '<strong>Navigation</strong> Link List';
        if (isset($nav_title)):
            $data['sub_label'] = 'Sub List of Navigtion of ' . $nav_title->title;
        else:
            $data['sub_label'] = 'Navigation Links';
        endif;

        $data['list'] = $this->navigation_model->get_all($this->_table, $id);

        $data['fields'] = array(
            'SN',
            'Title',
            'Group',
            'Link',
            'Action'
        );

        $data['buttons'] = array(
            array(
                'title' => 'Add New Link',
                'url' => base_url('admin/navigation/create') . '/' . $id,
                'class' => 'primary',
                'icon' => 'plus'
            )
        );
        $data['action'][] = array(
            'name' => 'View Child Menu',
            'url' => base_url('admin/navigation/listing'),
            'icon' => 'list'
        );
        $data['action'][] = array(
            'name' => 'Edit',
            'url' => base_url('admin/navigation/edit') . '/' . $id,
            'icon' => 'pencil'
        );

        $data['action'][] = array(
            'name' => 'Delete',
            'url' => base_url('admin/navigation/delete') . '/' . $id,
            'icon' => 'trash-o'
        );

        template('list', $data);
    }

    public function create($id)
    {
        $data['title'] = 'Navigation';
        $form['form_size'] = '12';
        $form['label'] = 'Create Navigation';
        $form['sub_label'] = '';
        $form['form_components'] = $this->form_array();
        $data['form'][] = $this->form_builder->build($form);
        $data['form_action'] = 'admin/navigation/create/' . $id;

        if ($this->input->post()):
            $datas = $this->input->post();

            if (!check_duplication('navigation_links', array('title' => $datas['title']))):
                set_flashdata(array(
                    'status' => 'danger',
                    'message' => 'Navigation already exist'
                ));

                redirect('admin/navigation/' . $id);
            endif;

            $insert_data = filter_array($this->input->post(), array('submit'));
            $insert_id = save($this->_table, $insert_data);

            set_flashdata(array(
                'status' => 'success',
                'message' => '<strong>Success!</strong> Navigation Link added successfully.'
            ));

            if ($this->input->post('submit') == 'save'):
                redirect('admin/navigation?module=links');
            else:
                redirect('admin/navigation/edit/' . $id . '/' . $insert_id);
            endif;

        else:
            template('create', $data);
        endif;
    }

    public function edit($redirect, $id)
    {
        $data['title'] = 'Navigation';

        $d = (array)$this->navigation_model->get_where($this->_table, $id);

        $form['form_size'] = '12';
        $form['label'] = 'Create Navigation';
        $form['sub_label'] = '';
        $form['form_components'] = $this->form_array($d);
        $data['form'][] = $this->form_builder->build($form);
        $data['form_action'] = 'admin/navigation/edit/' . $redirect . '/' . $id;

        if ($this->input->post()):
            $insert_data = filter_array($this->input->post(), array('submit'));

            $con = array('key' => 'id', 'value' => $id);
            update($this->_table, $con, $insert_data);

            set_flashdata(array(
                'status' => 'success',
                'message' => '<strong>Success!</strong> Navigation updated successfully.'
            ));

            if ($this->input->post('submit') == 'save'):
                redirect('admin/navigation/listing/' . $redirect);
            else:
                redirect('admin/navigation/edit/' . $redirect . '/' . $id);
            endif;
        else:
            template('create', $data);
        endif;
    }

    public function delete($redirect, $id)
    {
        $con = array('key' => 'parent', 'value' => $id);
        delete($this->_table, $con);

        $con = array('key' => 'id', 'value' => $id);
        delete($this->_table, $con);

        set_flashdata(array(
            'status' => 'success',
            'message' => '<strong>Navigation Group</strong> deleted successfully'
        ));

        redirect('admin/navigation');
    }

    function link_type()
    {
        return array(
            '' => 'Choose One',
            'url' => 'URL',
            'uri' => 'Site Link(uri)',
            'module' => 'Module',
            'page' => 'Page'
        );
    }

    function link_type_request($type)
    {

        if ($type == 'uri'):
            $elem = '<label class="control-label">(Site Link) URI</label><div><input type="text" class="form-control" id="link_type" name="uri"></div>';
        elseif ($type == 'url'):
            $elem = '<label class="control-label">URL</label><div><input type="text" class="form-control" id="link_type" name="url"></div>';
        elseif ($type == 'module'):
            $module_list = $this->get_module_list();
            $elem = form_dropdown('module', $module_list, '', 'class="form-control selectpicker"');

        elseif ($type == 'page'):

        endif;

        echo json_encode($elem);
    }

    function get_module_list()
    {
        $sql = $this->addons->get_modules_nav();
        $array[NULL] = 'Choose Module';
        foreach ($sql as $k => $v):
            $array[$v->id] = $v->name;
        endforeach;

        return $array;
    }

    function get_nav_group($id)
    {
        $res = $this->navigation_model->get_child_list($id);

        $form = form_dropdown('parent', $res, '', 'class="form-control selectpicker nav_parent"');

        $status = array('status' => TRUE, 'value' => $form);

        echo json_encode($status);
    }

}
