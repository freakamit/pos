<?php

class Admin extends CI_Controller {

    public function __construct() {
        parent::__construct();

        if (isset($this->session->userdata['userdata'])):
            redirect('admin/dashboard');
        endif;

        $this->load->model('admin_model');
    }

    public function index() {
        $this->create();
    }

    public function create() {
        $data['title'] = 'Login';

        $data['action'] = '';
        $data['attributes'] = array('id' => 'form-signin', 'class' => 'form-signin');
        $data['username'] = array('class' => 'form-control', 'placeholder' => "Username", 'name' => 'username');
        $data['password'] = array('class' => 'form-control', 'placeholder' => "Password", 'name' => 'password');
        $data['submit'] = array('class' => 'btn btn-lg btn-theme-inverse btn-block', 'id' => 'sign-in', 'value' => 'Login');

        $this->load->view('login/login', $data);
    }

    function check_login() {
        $username = $this->input->post('username');
        $password = $this->input->post('password');

        $result = $this->admin_model->login($username, $password);

        $status = FALSE;

        if ($result):
            $con = array(
                'key' => 'id',
                'value' => $result[0]->id
            );

            update('users', $con, array('last_login' => strtotime("now")));

            $status = TRUE;
            $sess_array = array();
            foreach ($result as $row) {
                if($row->user_image == 0 || $row->user_image == ''):
                    $img = base_url('assets/img/noimage.jpg');
                else:
                    $img = $row->user_image;
                endif;
                $sessarr = array(
                    'user_id' => $row->id,
                    'username' => $row->username,
                    'email' => $row->email,
                    'name' => $row->first_name,
                    'address' => $row->address_line,
                    'user_image' => $img,
                    'group_id' => $row->group_id,
                    'logged_in' => TRUE
                );
            }
            $this->session->set_userdata('userdata', $sessarr);

        else:
            $status = FALSE;
        endif;

        echo $status;
    }

}
