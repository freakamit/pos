<?php

class Permission extends CI_Controller {

    public function __construct() {
        parent::__construct();

        check_user_session();

        $this->load->model('permission_model');
    }

    function form_array($data = array(), $group_id) {
        $form_array['modules'] = array();

        $i = 0;
        foreach ($data as $d):
            $form_array['modules'][] = array(
                'type' => 'div',
                'class' => 'modules_wrap',
                'label' => '<h2>' . $d->name . '</h2>',
            );
            if ($d->child):
                foreach ($d->child as $c):
                    if (strpos($c->slug, '?') > 0):
                        $c->slug = str_replace('?', '/q/', $c->slug);
                        $c->slug = str_replace('=', '/m/', $c->slug);
                    endif;
                    $form_array['modules'][$i]['child'][] = array(
                        'name' => $c->slug . '[]',
                        'type' => 'multiselect',
                        'option' => (array) json_decode($c->fucntions),
                        'selected' => $this->permission_model->get_selected($c->slug, $group_id),
                        'label' => '<h4>' . $c->name . '</h4> <small><em>' . $c->description . '</em></small>',
                        'extra' => 'class ="multiselect" ',
                    );
                endforeach;
            else:
                if (strpos($d->slug, '?') > 0):
                    $d->slug = str_replace('?', '/q/', $d->slug);
                    $d->slug = str_replace('=', '/m/', $d->slug);
                endif;
                $form_array['modules'][$i]['child'][] = array(
                    'name' => $d->slug . '[]',
                    'type' => 'multiselect',
                    'option' => (array) json_decode($d->fucntions),
                    'selected' => $this->permission_model->get_selected($d->slug, $group_id),
                    'label' => $d->name . ' <small><em>' . $d->description . '</em></small>',
                    'extra' => 'class ="multiselect" ',
                );
            endif;
            $i++;
        endforeach;

        $form_array['modules'][] = array(
            'name' => 'submit',
            'type' => 'submit',
            'value' => 'save',
            'class' => 'btn btn-theme',
            'content' => 'Assign'
        );
        
        return $form_array;
    }

    public function assign($group_id) {
        $data['title'] = 'Permission Management';

        $group = $this->permission_model->get_group($group_id);
        $modules = $this->permission_model->get_modules();

        $data['form_action'] = 'admin/permission/assign/' . $group_id;

        $form['form_size'] = '12';
        $form['label'] = '<strong>Permission </strong>  Management for ' . $group->name;
        $form['sub_label'] = 'Assign moduler permission to user group';
        $form['form_components'] = $this->form_array($modules, $group_id);
        $form['form_components'] = $form['form_components']['modules'];

        $data['form'][] = $this->form_builder->build($form);

        if ($this->input->post()):
            $insert_data = filter_array($this->input->post(), array('submit', '0'));
            
            delete('permissions', array('key' => 'group_id', 'value' => $group_id));

            foreach ($insert_data as $k => $v):
                $key = $k;
                if(strpos($key, '/q/')):
                    $key = str_replace('/q/', '?', $key);
                    $key = str_replace('/m/', '=', $key);
                endif;
                foreach ($v as $value):
                    $insert_array = array('module' => $key,
                        'group_id' => $group_id,
                        'roles' => $value
                    );
                    save('permissions', $insert_array);
                endforeach;
            endforeach;
            
            set_flashdata(array(
                'status' => 'success',
                'message' => '<strong>Success!</strong> Permission Set successfully'
            ));

            redirect('admin/permission/assign/' . $group_id);
        else:
            template('create', $data);
        endif;
    }

}
