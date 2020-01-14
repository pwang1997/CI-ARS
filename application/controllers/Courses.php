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

  //edit classroom 
  public function edit()
  {
  }
}
