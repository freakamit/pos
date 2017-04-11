<?php

class Frontend extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('general_model');
    }

    public function index()
    {
        $data['title'] = 'Home';
        $data['slider'] = $this->general_model->get_slider();
        $data['about_us'] = $this->general_model->get_about_us();
        $data['item'] = $this->general_model->get_item();

        $data['testimonials'] = $this->general_model->get_testimonials();

        front_template('home', $data);
    }
}