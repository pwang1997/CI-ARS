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
        $quiz_index = $this->uri->segment(3);

        $data['num_questions'] = $this->question->get_num_question($quiz_index);
        $data['categories'] = $this->question->get_categories($quiz_index);
        $data['title'] = 'Quiz ';
        $data['hasQuestion'] = $this->question->has_question_in_quiz($quiz_index);
        $data['question_list'] = $this->question->getQuestions($quiz_index);
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

        $this->load->view('templates/header');
        $this->load->view('questions/student', $data);
        $this->load->view('templates/footer');
    }

    public function question_base()
    {
        $data['title'] = 'Question Base';

        $data['result'] = $this->question->get_question_base();

        $this->load->view('templates/header');
        $this->load->view('questions/question_base', $data);
        $this->load->view('templates/footer');
    }

    public function view()
    {
        $question_index = $this->uri->segment(3);
        $data['question'] = $this->question->get_question($question_index);
        $data['courses'] = $this->question->get_all_courses();
        $data['quizs'] = $this->question->get_all_quizs();

        $this->load->view('templates/header');
        $this->load->view('questions/view', $data);
        $this->load->view('templates/footer');
    }

    public function quiz()
    {
        $quiz_index = $this->uri->segment(3);
        $data['quiz_index'] = $quiz_index;
        $teacher_id = $this->session->id;
        $data['question_list'] = $this->question->getQuestions($quiz_index);
        $data['quiz_instance_list'] = $this->question->get_quiz_instance_list($quiz_index, $teacher_id);
        $data['question_instance_list'] = $this->question->get_question_instance_list($data['quiz_instance_list']);
        $data['student_response_list'] = $this->question->get_student_response_list($data['question_instance_list']);

        $this->load->view('templates/header', $data);
        $this->load->view('questions/quiz', $data);
        $this->load->view('templates/footer');
    }

    public function summary()
    {
        $question_id = $this->uri->segment(3);
        $question_instance_id = $this->uri->segment(4);
        $data['title'] = "summary of question instance " . $question_instance_id;
        $data['question_instance_id'] = $question_instance_id;
        $data['question'] = $this->question->get_question($question_id);
        $data['question_id'] = $question_id;
        $this->load->view('templates/header');
        $this->load->view('questions/summary', $data);
        $this->load->view('templates/footer');
    }
    public function create_question()
    {
        $lab_index = $this->input->post('quiz_index');
        $msg['success'] = $this->question->create($lab_index);
        echo json_encode($msg);
    }

    public function student_response()
    {
        $msg['success'] = $this->question->student_response();
        echo json_encode($msg);
    }

    public function update_question()
    {
        $quiz_index = $this->input->post('quiz_index');
        $msg['success'] = $this->question->update_question($quiz_index);
        echo json_encode($msg);
    }

    public function add_question_instance()
    {
        $quiz_meta_id = $this->input->post('quiz_id');
        $teacher_id = $this->input->post('from_id');
        $question_index = $this->input->post('question_meta_id');

        $last_quiz_instance_id = $this->question->get_last_quiz_instance($quiz_meta_id, $teacher_id);
        
        $result = $this->question->add_question_instance($question_index, $last_quiz_instance_id);
        $msg['success'] = $result['success'];
        $msg['question_instance_id'] = $result['question_instance_id'];
        echo json_encode($msg);
    }

    public function pause_question_instance()
    {
        $quiz_meta_id = $this->input->post('quiz_id');
        $teacher_id = $this->input->post('from_id');
        $question_index = $this->input->post('question_meta_id');
        $last_quiz_instance_id = $this->question->get_last_quiz_instance($quiz_meta_id, $teacher_id);
        $this->question->pause_question_instance($question_index, $last_quiz_instance_id);
    }
    public function resume_question_instance()
    {
        $quiz_meta_id = $this->input->post('quiz_id');
        $teacher_id = $this->input->post('from_id');
        $question_index = $this->input->post('question_meta_id');
        $last_quiz_instance_id = $this->question->get_last_quiz_instance($quiz_meta_id, $teacher_id);
        $this->question->resume_question_instance($question_index, $last_quiz_instance_id);
    }

    public function complete_question_instance()
    {
        $quiz_meta_id = $this->input->post('quiz_id');
        $teacher_id = $this->input->post('from_id');
        $question_index = $this->input->post('question_meta_id');
        $last_quiz_instance_id = $this->question->get_last_quiz_instance($quiz_meta_id, $teacher_id);
        $this->question->complete_question_instance($question_index, $last_quiz_instance_id);
    }
    

    public function update_quiz_instance()
    {
        $quiz_meta_id = $this->input->post('quiz_id');
        $teacher_id = $this->input->post('from_id');

        $msg['result'] = $this->question->update_quiz_instance($quiz_meta_id, $teacher_id);
        echo json_encode($msg);
    }

    public function update_question_instance_status_tab_list() {
        $quiz_id = $this->input->post('quiz_id');
        $teacher_id = $this->input->post('from_id');

        $msg = $this->question->update_question_instance_status_tab_list($quiz_id, $teacher_id);
        echo json_encode($msg);
    }

    public function get_question_for_student()
    {
        $question_index = $this->input->post('question_index');
        $msg['result'] = $this->question->get_question($question_index);
        echo json_encode($msg);
    }

    public function submit_student_response()
    {
        $msg['quiz_id'] = $this->question->submit_student_response();
        $msg['cmd'] = "submit";
        $msg['msg'] = $this->input->post('answer');
        $msg['question_instance_id'] = $this->input->post('question_instance_id');
        echo json_encode($msg);
    }

    public function get_num_students_answered()
    {
        $msg['num_students_answered'] = $this->question->get_num_students_answered();
        echo json_encode($msg);
    }

    public function get_answered_question_instance()
    {
        $question_instance_id = $this->uri->segment(3);
        $msg['data'] = $this->question->get_answered_question_instance($question_instance_id);
        $msg['dataset'] = array();
        foreach ($msg['data'] as $data) {
            $data = str_replace('"', "'", $data['answer']);
            array_push($msg['dataset'], $data);
        }
        unset($msg['data']);
        // print_r(str_replace('"', "'", $msg['dataset'][0]['answer']));
        // print_r($msg['dataset']);
        echo json_encode($msg);
    }

    public function get_all_students() {
        $list_of_students = $this->question->get_all_students($this->input->post('quiz_id'));
        print_r(json_encode($list_of_students));
    }
}
