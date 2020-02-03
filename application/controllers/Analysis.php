<?php

class Analysis extends CI_Controller
{
    public function category_cloud()
    {
        $data = [];
        $this->load->view('templates/header');
        $this->load->view('analysis/category_cloud', $data);
        $this->load->view('templates/footer');
    }
}
