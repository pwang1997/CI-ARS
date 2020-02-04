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

        $quiz_index = $this->uri->segment(3);
        $data['hasQuestion'] = $this->question_model->has_question_in_quiz($quiz_index);
        $data['question_list'] =$this->question_model->getQuestions($quiz_index);
        $data['quiz_index'] = $quiz_index;

        $this->load->view('templates/header');
        $this->load->view('questions/teacher', $data);
        $this->load->view('templates/footer');
    }

    public function student()
    {
        $data['title'] = 'Student\'s Quiz Page';

        $quiz_index = $this->uri->segment(3);
        $data['quiz_index'] = $quiz_index;
        $data['question_list'] =$this->question_model->getQuestions($quiz_index);
        
        $this->load->view('templates/header');
        $this->load->view('questions/student', $data);
        $this->load->view('templates/footer');
    }

    public function question_base() {
        $data['title'] = 'Question Base';
        
        $data['result'] = $this->question_model->get_question_base();

        $this->load->view('templates/header');
        $this->load->view('questions/question_base', $data);
        $this->load->view('templates/footer');
    }

    public function create_question() {
        $lab_index = $this->input->post('quiz_index');
        $msg['success'] = $this->question_model->create($lab_index);
        echo json_encode($msg);
    }

    public function student_response() {
        $msg['success'] = $this->question_model->student_response();
        echo json_encode($msg);
    }

    public function update_question() {
        $quiz_index = $this->input->post('quiz_index');
        $msg['success'] = $this->question_model->update_question($quiz_index);
        echo json_encode($msg);
    }
}
