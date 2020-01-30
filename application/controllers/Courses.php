<?php

class Courses extends CI_Controller
{
  public function create()
  {
    $data['title'] = 'Add Course';

    $this->form_validation->set_rules('courseName', 'CourseName', 'required');
    $this->form_validation->set_rules('courseCode', 'CourseCode', 'required');
    $this->form_validation->set_rules('sectionId', 'SectionId', 'required');

    if ($this->form_validation->run() === FALSE) {
      $this->load->view('templates/header');
      $this->load->view('courses/create', $data);
      $this->load->view('templates/footer');
    } else {
      $course_name = $this->input->post('courseName');
      $course_code = $this->input->post('courseCode');
      $course_description = $this->input->post('description');
      $section_id = $this->input->post('sectionId');
      //insert course row
      $course_id = $this->course_model->create_course($course_name, $course_code, $course_description, $section_id);
      //insert classroom row
      $this->course_model->create_classroom($this->session->id, $course_id, $section_id);

      redirect('users/teacher');
    }
  }

  //teacher's view of the course
  public function teacher() {
    $data['title'] = 'Teacher\'s Course Page';

    $course_id = $this->uri->segment(3);
    $classroom_id = $this->uri->segment(4);

    $data['course_info'] = $this->course_model->get_teacher_course($course_id, $classroom_id)[0];
    $data['enrolledStudents'] = $this->course_model->get_enrolledStudents_for_teacher($classroom_id);
    $data['quizs'] = $this->course_model->get_quizs_for_teacher($classroom_id);
    $data['num_of_questions'] = $this->course_model->get_number_of_questions($data['quizs']);

    $this->load->view('templates/header');
    $this->load->view('courses/teacher', $data);
    $this->load->view('templates/footer');

  }

  public function add_student_from_classroom() {
    $msg['success']= $this->course_model->add_student_from_classroom();
    $msg['username'] = $this->input->post('username');
    echo json_encode($msg);
  }

  public function add_quiz_from_classroom() {
    $result = $this->course_model->add_quiz_from_classroom();
    $msg['success'] = $result['success'];
    $msg['quiz_index'] = $result['quiz_index'];
    echo json_encode($msg);
  }

  public function remove_student_from_classroom() {
    $msg['success']= $this->course_model->remove_student_from_classroom();
    $msg['username'] = $this->input->post('username');
    
    echo json_encode($msg);
  }

  public function student() {
    $data['title'] = 'Student\'s Course Page';

    $course_id = $this->uri->segment(3);
    $classroom_id = $this->uri->segment(4);

    $data['course_info'] = $this->course_model->get_teacher_course($course_id, $classroom_id)[0];
    $data['labs'] = $this->course_model->get_labs_for_user($course_id, $classroom_id);

    $this->load->view('templates/header');
    $this->load->view('courses/student', $data);
    $this->load->view('templates/footer');

  }
}
