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
      $course_id = $this->course->create_course($course_name, $course_code, $course_description, $section_id);
      //insert classroom row
      $this->course->create_classroom($this->session->id, $course_id, $section_id);

      redirect('users/teacher');
    }
  }

  //teacher's view of the course
  public function teacher()
  {
    $data['title'] = 'Teacher\'s Course Page';

    $course_id = $this->uri->segment(3);
    $classroom_id = $this->uri->segment(4);
    $data['classroom_id'] = $classroom_id;
    $data['course_info'] = $this->course->get_teacher_course($course_id, $classroom_id);
    $data['enrolled_students'] = $this->course->get_enrolled_students_for_teacher($classroom_id);
    $data['quizs'] = $this->course->get_quizs_for_teacher($classroom_id);
    $data['num_questions'] = $this->course->get_number_of_questions_for_teacher($data['quizs']);

    $this->load->view('templates/header');
    $this->load->view('courses/teacher', $data);
    $this->load->view('templates/footer');
  }

  public function add_student_from_classroom()
  {
    $msg['success'] = $this->course->add_student_from_classroom();
    $msg['username'] = $this->input->post('username');
    echo json_encode($msg);
  }

  public function add_quiz_from_classroom()
  {
    $result = $this->course->add_quiz_from_classroom();
    $msg['success'] = $result['success'];
    $msg['quiz_index'] = $result['quiz_index'];
    echo json_encode($msg);
  }

  public function remove_quiz_from_classroom()
  {
    $msg['success'] = $this->course->remove_quiz_from_classroom();
    echo json_encode($msg);
  }
  public function remove_student_from_classroom()
  {
    $msg['success'] = $this->course->remove_student_from_classroom();
    $msg['username'] = $this->input->post('username');

    echo json_encode($msg);
  }

  public function student()
  {
    $data['title'] = 'Student\'s Course Page';

    $course_id = $this->uri->segment(3);
    $classroom_id = $this->uri->segment(4);

    $data['course_info'] = $this->course->get_teacher_course($course_id, $classroom_id);
    $data['quizs'] = $this->course->get_quizs_for_student($course_id, $classroom_id);

    // $data['questions'] = $this->course->get_questions_for_student($data['quizs']);
    // $data['student_responses'] = $this->course->get_student_response($data['questions']);

    // $data['quiz_list'] = $this->course->get_quiz_list($classroom_id);
    // $data['question_list'] = $data['questions'];

    // $teacher_id = $this->course->get_teacher_id($classroom_id);

    // $data['quiz_instance_list'] = $this->course->get_quiz_instance_list($data['quiz_list'], $teacher_id);

    // $data['question_instance_list'] = $this->course->get_question_instance_list($data['quiz_instance_list']);

    $this->load->view('templates/header');
    $this->load->view('courses/student', $data);
    $this->load->view('templates/footer');
  }

  public function export_student_stat()
  {
    $result = $this->course->export_student_stat($this->input->post('quiz_id'));
    $msg['result'] = $result;
    echo json_encode($msg);
  }

  public function export_classroom_history() {
    $result = $this->course->export_classroom_history($this->input->post('classroom_id'));
    $msg['result'] = $result;
    echo json_encode($msg);
  }
}
