<?php

class Customers extends CI_Controller {

    var $_table = 'users';
    var $_type = 'Customers';

    public function __construct() {
        parent::__construct();

        check_user_session();

        $this->load->model('user_model');
    }

    public function form_array($data = array()) {
        $form_array['personal_information'] = array(
            array(
                'name' => 'first_name',
                'type' => 'text',
                'value' => isset($data['first_name']) ? $data['first_name'] : false,
                'placeholder' => 'First Name',
                'class' => 'form-control',
                'label' => 'First Name',
                'parsley-required' => 'true'
            ),
            array(
                'name' => 'middle_name',
                'type' => 'text',
                'value' => isset($data['middle_name']) ? $data['middle_name'] : false,
                'placeholder' => 'Middle Name',
                'class' => 'form-control',
                'label' => 'Middle Name',
            ),
            array(
                'name' => 'last_name',
                'type' => 'text',
                'value' => isset($data['last_name']) ? $data['last_name'] : false,
                'placeholder' => 'Last Name',
                'class' => 'form-control',
                'label' => 'Last Name',
            ),
            array(
                'name' => 'gender',
                'type' => 'dropdown',
                'option' => gender(),
                'selected' => isset($data['gender']) ? $data['gender'] : FALSE,
                'label' => 'Gender',
                'extra' => 'class ="form-control selectpicker" parsley-required="true"',
            ),
            array(
                'name' => 'dob',
                'type' => 'datetime',
                'label' => 'Date of Birth',
                'placeholder' => 'Date Month Year',
                'class' => 'form-control',
                'value' => isset($data['dob']) ? user_format_date(strtotime($data['dob'])) : FALSE
            ),
        );
        $form_array['contact_information'] = array(
            array(
                'name' => 'phone',
                'type' => 'text',
                'value' => isset($data['phone']) ? $data['phone'] : false,
                'placeholder' => '(XXX) XXXX XXX',
                'class' => 'form-control',
                'label' => 'Phone',
                'parsley-type' => 'phone'
            ),
            array(
                'name' => 'mobile',
                'type' => 'text',
                'value' => isset($data['mobile']) ? $data['mobile'] : false,
                'placeholder' => '(XXX) XXXX XXX',
                'class' => 'form-control',
                'label' => 'Mobile',
                'parsley-type' => 'phone'
            ),
            array(
                'name' => 'country',
                'type' => 'dropdown',
                'option' => country(),
                'selected' => isset($data['country']) ? $data['country'] : FALSE,
                'label' => 'Country',
                'extra' => 'class ="form-control selectpicker country" parsley-required="true"',
            ),
            array(
                'name' => 'states',
                'type' => 'dropdown',
                'option' => isset($data['states']) ? state($data['country']) : array('' => 'Please Select Country'),
                'selected' => isset($data['states']) ? $data['states'] : FALSE,
                'label' => 'States',
                'extra' => 'class ="form-control selectpicker states"',
            ),
            array(
                'name' => 'city',
                'type' => 'dropdown',
                'option' => isset($data['city']) ? city($data['states']) : array('' => 'Please Select States'),
                'selected' => isset($data['city']) ? $data['city'] : FALSE,
                'label' => 'City',
                'extra' => 'class ="form-control selectpicker city"',
            ),
            array(
                'name' => 'address_line',
                'type' => 'text',
                'value' => isset($data['address_line']) ? $data['address_line'] : false,
                'placeholder' => 'Address Line',
                'class' => 'form-control',
                'label' => 'Address Line',
            ),
            array(
                'name' => 'postcode',
                'type' => 'text',
                'value' => isset($data['postcode']) ? $data['postcode'] : false,
                'placeholder' => 'Postcode',
                'class' => 'form-control',
                'label' => 'Postcode',
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
        $form_array['picture'] = array(
            array(
                'name' => 'userfile',
                'type' => 'upload',
                'class' => 'form-control',
                'label' => 'Profile Picture',
            ),
        );
        if (isset($data['user_image'])):
            $form_array['picture'][] = array(
                'name' => '',
                'label' => '',
                'type' => 'image_block',
                'value' => $data['user_image'],
                'status' => '',
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

    public function index() {
        $data['title'] = 'Customer list';
        $data['label'] = '<strong>Customer</strong> list';
        $data['sub_label'] = 'List of Customer Users';
        $data['list'] = $this->user_model->get_all($this->_type);
        $i = 0;
        foreach ($data['list'] as $k => $v):
            $data['list'][$i]->first_name = get_fullname($v->first_name, $v->middle_name, $v->last_name);
            unset($data['list'][$i]->middle_name);
            unset($data['list'][$i]->last_name);
            $data['list'][$i]->created_on = date('d F,Y', $v->created_on);
            $data['list'][$i]->active = display_status($v->active);
            if ($v->last_login == 0):
                $last_log = 'Not Logged';
            else:
                $last_log = user_format_date($v->last_login);
                ;
            endif;
            $data['list'][$i]->last_login = $last_log;
            $i++;
        endforeach;

        $data['buttons'] = array(
            array(
                'title' => 'Add New Customer',
                'url' => base_url('admin/customers/create'),
                'class' => 'primary',
                'icon' => 'plus'
            )
        );
        $data['fields'] = array(
            'SN',
            'Name',
            'Group',
            'Status',
            'Joined Date',
            'Last Visit',
            'Action'
        );
        $data['action'] = array(
            array(
                'name' => 'Order History',
                'url' => base_url('admin/customers/order_history'),
                'icon' => 'eye'
            ),
            array(
                'name' => 'Deactivate Account',
                'url' => base_url('admin/customers/deactivate'),
                'icon' => 'warning'
            ),
            array(
                'name' => 'Edit',
                'url' => base_url('admin/customers/edit'),
                'icon' => 'pencil'
            ),
            array(
                'name' => 'Delete',
                'url' => base_url('admin/customers/delete'),
                'icon' => 'trash-o'
            )
        );
        template('list', $data);
    }

    public function create() {
        $data['title'] = 'Create Customer';
        $data['form_action'] = 'admin/customers/create';

        $form['form_size'] = '6';
        $form['label'] = '<strong>Personal</strong>  Information';
        $form['sub_label'] = '';
        $form['form_components'] = $this->form_array();
        $form['form_components'] = $form['form_components']['personal_information'];
        $data['form'][] = $this->form_builder->build($form);

        $form['form_size'] = '6';
        $form['label'] = '<strong>Contact</strong>  Information';
        $form['sub_label'] = '';
        $form['form_components'] = $this->form_array();
        $form['form_components'] = $form['form_components']['contact_information'];
        $data['form'][] = $this->form_builder->build($form);

        $form['form_size'] = '12';
        $form['label'] = '<strong>Profile</strong>  Picture';
        $form['sub_label'] = '';
        $form['form_components'] = $this->form_array();
        $form['form_components'] = $form['form_components']['picture'];
        $data['form'][] = $this->form_builder->build($form);

        $form['label'] = '';
        $form['sub_label'] = '';
        $form['form_components'] = $this->form_array();
        $form['form_components'] = $form['form_components']['buttons'];
        $data['form'][] = $this->form_builder->build($form);

        if ($this->input->post()):
            $datas['post'] = $this->input->post();

            $datas['insert_users'] = array(
                'group_id' => 3,
                'ip_address' => $this->input->ip_address(),
                'active' => $this->input->post('active'),
                'created_on' => strtotime("now"),
            );

            $insert_id = save($this->_table, $datas['insert_users']);

            $datas['insert_user_profile'] = array(
                'created' => strtotime("now"),
                'created_by' => '',
                'user_id' => $insert_id,
                'first_name' => $this->input->post('first_name'),
                'middle_name' => $this->input->post('middle_name'),
                'last_name' => $this->input->post('last_name'),
                'bio' => $this->input->post('bio'),
                'dob' => db_format_date($this->input->post('dob')),
                'gender' => $this->input->post('gender'),
                'phone' => $this->input->post('phone'),
                'mobile' => $this->input->post('mobile'),
                'country' => $this->input->post('country'),
                'states' => $this->input->post('states'),
                'city' => $this->input->post('city'),
                'address_line' => $this->input->post('address_line'),
                'postcode' => $this->input->post('postcode'),
            );

            $insert_id = save('users_profile', $datas['insert_user_profile']);

            set_flashdata(array(
                'status' => 'success',
                'message' => '<strong>Success!</strong> Customer added successfully.'
            ));
            if ($this->input->post('submit') == 'save'):
                redirect('admin/customers');
            else:
                redirect('admin/customers/edit/' . $insert_id);
            endif;
        else:
            template('create', $data);
        endif;
    }

    public function edit($id) {
        $data['title'] = 'Update Customer';
        $d = (array) $this->user_model->get($id);

        $data['form_action'] = 'admin/customers/edit/' . $id;

        $form['form_size'] = '6';
        $form['label'] = '<strong>Personal</strong>  Information';
        $form['sub_label'] = '';
        $form['form_components'] = $this->form_array($d);
        $form['form_components'] = $form['form_components']['personal_information'];
        $data['form'][] = $this->form_builder->build($form);

        $form['form_size'] = '6';
        $form['label'] = '<strong>Contact</strong>  Information';
        $form['sub_label'] = '';
        $form['form_components'] = $this->form_array($d);
        $form['form_components'] = $form['form_components']['contact_information'];
        $data['form'][] = $this->form_builder->build($form);

        $form['form_size'] = '12';
        $form['label'] = '<strong>Profile</strong>  Picture';
        $form['sub_label'] = '';
        $form['form_components'] = $this->form_array($d);
        $form['form_components'] = $form['form_components']['picture'];
        $data['form'][] = $this->form_builder->build($form);

        $form['label'] = '';
        $form['sub_label'] = '';
        $form['form_components'] = $this->form_array();
        $form['form_components'] = $form['form_components']['buttons'];
        $data['form'][] = $this->form_builder->build($form);


        if ($this->input->post()):
            $datas['post'] = $this->input->post();

            $con = array(
                'key' => 'id',
                'value' => $id,
            );

            $datas['insert_users'] = array(
                'username' => $this->input->post('username'),
                'email' => $this->input->post('email'),
                'group_id' => $this->input->post('group'),
                'active' => $this->input->post('active'),
            );

            update($this->_table, $con, $datas['insert_users']);

            $datas['insert_user_profile'] = array(
                'updated' => strtotime("now"),
                'updated_by' => '',
                'first_name' => $this->input->post('first_name'),
                'middle_name' => $this->input->post('middle_name'),
                'last_name' => $this->input->post('last_name'),
                'bio' => $this->input->post('bio'),
                'dob' => db_format_date($this->input->post('dob')),
                'gender' => $this->input->post('gender'),
                'phone' => $this->input->post('phone'),
                'mobile' => $this->input->post('mobile'),
                'country' => $this->input->post('country'),
                'states' => $this->input->post('states'),
                'city' => $this->input->post('city'),
                'address_line' => $this->input->post('address_line'),
                'postcode' => $this->input->post('postcode'),
            );

            if (isset($datas['post']['remove_image'])):
                remove_image($datas['post']['remove_image'][0]);
                $datas['insert_user_profile']['user_image'] = 0;
            endif;

            $data['post'] = filter_array($data['post'], array('remove_image'));

            if (!empty($_FILES['userfile']['name'])):
                if ($d['user_image'] != 0):
                    remove_image($d['user_image']);
                endif;
                $datas['insert_user_profile']['user_image'] = upload('uploads/users');
            endif;

            $conn = array(
                'key' => 'user_id',
                'value' => $id
            );

            update('users_profile', $conn, $datas['insert_user_profile']);

            set_flashdata(array(
                'status' => 'success',
                'message' => '<strong>Success!</strong> Customer updated successfully.'
            ));

            if ($this->input->post('submit') == 'save'):
                redirect('admin/customers');
            else:
                redirect('admin/customers/edit/' . $id);
            endif;
        else:
            template('create', $data);
        endif;
    }

    public function delete($id) {
        $d = (array) $this->user_model->get($id);

        if ($d['user_image'] != 0):
            remove_image($d['user_image']);
        endif;

        $con = array(
            'key' => 'id',
            'value' => $id
        );
        delete($this->_table, $con);

        set_flashdata(array(
            'status' => 'success',
            'message' => '<strong>Success!</strong> Customer deleted successfully.'
        ));

        redirect('admin/customers');
    }

}
