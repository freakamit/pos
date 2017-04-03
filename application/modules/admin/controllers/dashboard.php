<?php

class Dashboard extends CI_Controller {

    public function __construct() {
        parent::__construct();

        check_user_session();
    }

    public function index() {
        $data['title'] = 'Dashboard';

        template('dashboard', $data);
    }

}
