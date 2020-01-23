<?php

class Questions extends CI_Controller
{
    public function create()
    {
        $data['title'] = 'Create Quiz';
        $lab_index = $this->uri->segment(3);
        $data['lab_index'] = $lab_index;

        
        $this->load->view('templates/header');
        $this->load->view('questions/create', $data);
        $this->load->view('templates/footer');
    }

    //teacher's view of the course
    public function teacher()
    {
        $data['title'] = 'Teacher\'s Quiz Page';

        $lab_index = $this->uri->segment(3);
        $data['hasQuestion'] = $this->question_model->hasQuestion($lab_index);
        $data['lab_index'] = $lab_index;

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

    public function create_question() {
        $lab_index = $this->input->post('lab_index');
        $msg['success'] = $this->question_model->create($lab_index);
        echo json_encode($msg);
    }
}
