<?php

class Questions extends CI_Controller
{
    public function create()
    {
        $data['title'] = 'Add Quiz';

        if ($this->form_validation->run() === FALSE) {
            $this->load->view('templates/header');
            $this->load->view('questions/create', $data);
            $this->load->view('templates/footer');
        } else {
            
        }
    }

    //teacher's view of the course
    public function teacher()
    {
        $data['title'] = 'Teacher\'s Quiz Page';

        $lab_index = $this->uri->segment(3);

        $this->load->view('templates/header');
        $this->load->view('questions/teacher', $data);
        $this->load->view('templates/footer');
    }

    public function student()
    {
        $data['title'] = 'Student\'s Quiz Page';

        $lab_index = $this->uri->segment(3);

        $this->load->view('templates/header');
        $this->load->view('questions/student', $data);
        $this->load->view('templates/footer');
    }
}
