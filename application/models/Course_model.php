<?php

class Course_model extends CI_Model
{
  public function __construct()
  {
    $this->load->database();
  }

  public function create_course($course_name, $course_code, $course_description)
  {
    //insert in Table Courses
    $course_data = array(
      'course_name' => $course_name,
      'course_code' => $course_code,
      'description' => $course_description
    );

    $this->db->insert('courses', $course_data);
    return $this->db->insert_id();
  }

  public function create_classroom($teacher_id, $course_id, $section_id)
  {
    $classroom_data = array(
      'taught_by' => $teacher_id,
      'course_id' => $course_id,
      'section_id' => $section_id
    );

    return $this->db->insert('classrooms', $classroom_data);
  }

  public function edit()
  {
  }
}
