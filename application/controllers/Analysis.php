<?php

class Analysis extends CI_Controller
{
    public function category_cloud()
    {
        $data['category_list'] = $this->analysis_model->get_unique_category();
        $data['size'] = $this->analysis_model->get_category_count($data['category_list']);

        $this->load->view('templates/header');
        $this->load->view('analysis/category_cloud', $data);
        $this->load->view('templates/footer');
    }
}
