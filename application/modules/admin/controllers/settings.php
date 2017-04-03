<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends CI_Controller {

    var $_table = 'settings';

    function __construct() {
        parent::__construct();

        check_user_session();

        $this->load->model('settings_model');
    }

    public function form_array($data = array()) {

        $form_array['settings'] = array();

        $i = 0;
        foreach ($data as $d):
            if ($d->type == 'dropdown'):
                $form_array['settings'][] = array(
                    'name' => $d->slug,
                    'type' => $d->type,
                    'option' => json_decode($d->options),
                    'selected' => ($d->value) ? $d->value : FALSE,
                    'label' => $d->title,
                    'extra' => 'class ="form-control selectpicker" parsley-required="true"',
                );
            else:
                $form_array['settings'][] = array(
                    'name' => $d->slug,
                    'type' => $d->type,
                    'value' => $d->value,
                    'checked' => ($d->value == 1) ? TRUE : FALSE,
                    'placeholder' => $d->title,
                    'class' => 'form-control',
                    'label' => $d->title,
                    'description' => $d->description,
                );
                if ($d->is_required):
                    $form_array['settings'][$i]['parsley-required'] = 'true';
                endif;
            endif;
            $i++;
        endforeach;

        $form_array['settings'][] = array(
            'name' => 'submit',
            'type' => 'submit',
            'value' => 'save',
            'class' => 'btn btn-theme',
            'content' => 'Update'
        );

        return $form_array;
    }

    public function index() {
        $module = $this->input->get('module');
        $settings = $this->settings_model->get($module);

        $data['title'] = ucfirst($module) . ' Setting';
        $data['form_action'] = 'admin/settings?module=' . $module;

        $form['form_size'] = '12';
        $form['label'] = '<strong>' . ucfirst($module) . '</strong>  Setting';
        $form['sub_label'] = '';
        $form['form_components'] = $this->form_array($settings);
        $form['form_components'] = $form['form_components']['settings'];

        $i = 0;
        foreach ($form['form_components'] as $k => $v):
            if ($v['type'] == 'upload'):
                ++$i;
                $image_id = $v['value'];
                $form['form_components'][$i] = array(
                    'name' => '',
                    'label' => '',
                    'type' => 'image_block',
                    'value' => $image_id,
                    'status' => '',
                    'class' => 'site_logo',
                    'input' => FALSE
                );
            else:
                $form['form_components'][$i] = $v;
            endif;
            $i++;
        endforeach;

        $data['form'][] = $this->form_builder->build($form);

        if ($this->input->post()):
            $data = $this->input->post();

            if (!empty($_FILES['userfile']['name'])):
                $data['userfile'] = upload('uploads/logo');
            endif;

            // dumparray($data);
            // for site status
            foreach ($data as $k => $v):
                $con = array(
                    'key' => 'slug',
                    'value' => $k,
                );

                update($this->_table, $con, array('value' => $v));
            endforeach;

            set_flashdata(array(
                'status' => 'success',
                'message' => '<strong>Success!</strong> ' . ucfirst($module) . ' setting has been  updated successfully.'
            ));

            redirect('admin/settings?module=' . $module);

        else:
            template('create', $data);
        endif;
    }

    public function error() {
        $data['title'] = 'Unauthorized Access';

        template('error', $data);
    }

}
