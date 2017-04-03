<?php

class Modules_management extends CI_Controller {

    private $table = 'modules';

    public function __construct() {
        parent::__construct();

        check_user_session();

        $this->load->model('modules_model', 'addons');
    }

//    public function fxn_script() {
//        $sql = $this->db->select('fucntions')->from('modules')->get()->result();
//
//        foreach ($sql as $s):
//            if ($s->fucntions):
//                $array = json_decode($s->fucntions);
//                foreach ($array as $k => $v):
//                    $db = $this->db->get_where('modules_function_list', array('function' => $k))->row();
//                    if (!$db):
//                        save('modules_function_list', array('function' => $k));
//                    endif;
//                endforeach;
//            endif;
//        endforeach;
//
//        echo 'success';
//    }

    public function index() {
        $data['title'] = 'Module Management';

        $data['modules'] = $this->addons->get_modules();

        $data['buttons'] = array(
            array(
                'icon' => 'plus',
                'content' => 'Add Modules',
                'class' => 'primary',
                'url' => base_url('admin/modules_management/create')
            ),
        );
        template('module_list', $data);
    }

    public function change_status($id) {
        $data['module'] = $this->addons->check_status($id);
        if ($data['module']->enabled == 1):
            $status = '0';
        else:
            $status = '1';
        endif;
        $con = array('key' => 'id', 'value' => $id);
        $data = array('enabled' => $status);
        update($this->table, $con, $data);

        $child = $this->addons->check_child($id);
        foreach ($child as $c):
            $con = array('key' => 'id', 'value' => $c->id);
            update($this->table, $con, $data);
        endforeach;

        echo 'success';
    }

    function form_array($data = array()) {
        $form_array['modules'] = array(
            array(
                'name' => 'parent_id',
                'type' => 'dropdown',
                'option' => $this->addons->get_parent(),
                'selected' => isset($data['parent_id']) ? $data['parent_id'] : '',
                'extra' => 'class="form-control selectpicker"',
                'label' => 'Select Parent Module'
            ),
            array(
                'name' => 'name',
                'type' => 'text',
                'value' => isset($data['name']) ? $data['name'] : false,
                'class' => 'form-control',
                'label' => 'Module Name',
                'placeholder' => 'Module Name'
            ),
            array(
                'name' => 'slug',
                'type' => 'text',
                'value' => isset($data['slug']) ? $data['slug'] : false,
                'class' => 'form-control',
                'label' => 'Slug',
                'placeholder' => 'Slug'
            ),
            array(
                'name' => 'groups',
                'type' => 'text',
                'value' => isset($data['groups']) ? $data['groups'] : false,
                'class' => 'form-control',
                'label' => 'Groups',
                'placeholder' => 'Groups'
            ),
            array(
                'name' => 'description',
                'type' => 'textarea',
                'value' => isset($data['description']) ? $data['description'] : false,
                'class' => 'form-control',
                'label' => 'Description',
                'placeholder' => 'Description'
            ),
            array(
                'name' => 'version',
                'type' => 'text',
                'value' => isset($data['version']) ? $data['version'] : false,
                'class' => 'form-control',
                'label' => 'Version',
                'placeholder' => 'Version'
            ),
            array(
                'name' => 'function',
                'type' => 'textarea',
                'value' => isset($data['function']) ? $data['function'] : false,
                'class' => 'form-control',
                'label' => 'Functions',
                'placeholder' => 'Functions',
                'description' => 'Must be in json format'
            ),
            array(
                'name' => 'enabled',
                'type' => 'checkbox',
                'value' => 1,
                'checked' => isset($data['enabled']) ? $data['enabled'] : 1,
                'label' => 'Status',
            ),
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
        $data['form_action'] = 'admin/modules_management/create';

        $form['form_size'] = '12';
        $form['label'] = '<strong>Module</strong>  Information';
        $form['sub_label'] = '';
        $form['form_components'] = $this->form_array($this->input->post());
        $form['form_components'] = $form['form_components']['modules'];
        $data['form'][] = $this->form_builder->build($form);

        $form['label'] = '';
        $form['sub_label'] = '';
        $form['form_components'] = $this->form_array();
        $form['form_components'] = $form['form_components']['buttons'];
        $data['form'][] = $this->form_builder->build($form);

        if ($this->input->post()):
            $insert_data = filter_array($this->input->post(), array('submit'));


        else:
            template('create', $data);
        endif;
    }

}
