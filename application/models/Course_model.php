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

  //get individual course where courseId and classroomId are specified
  public function get_teacher_course($course_id, $classroom_id)
  {
    $this->db->select('*');
    $this->db->join('courses', 'courses.id = classrooms.course_id');
    $where = array(
      'course_id' => $course_id,
      'classrooms.id' => $classroom_id
    );
    return $this->db->get_where('classrooms', $where)->result_array();
  }

  public function get_enrolledStudents_for_teacher($classroom_id)
  {
    $this->db->join('users', 'users.id = labs.student_id');
    return $this->db->get_where('labs', array('classroom_id' => $classroom_id))->result_array();
  }


  /**
   * add student to the enrolledStudent table
   * return false if the student is unregistered
   */
  public function add_student_from_classroom()
  {
    $sname = $this->input->post('username');
    $classroom_id = $this->input->post('classroom_id');
    $student_id = $this->check_username_exists($sname);
    
    //if the student is already enrolled, return false
    $is_enrolled = $this->db->get_where('labs', array('classroom_id' => $classroom_id, 'student_id' => $student_id))->num_rows();

    if ($student_id !== FALSE && $is_enrolled == FALSE) {
      $data = array(
         			'classroom_id' => $classroom_id,
        			'student_id' => $student_id
      );
      $this->db->insert('labs', $data);
      return  $this->db->affected_rows() > 0;
    } else {
      return false;
    }
  }

  public function remove_student_from_classroom() {
    // $query = $this->db->get
    $username = $this->input->post('username');
    $student_id = $this->check_username_exists($username);
    $classroom_id = $this->input->post('classroom_id');
    return $this->db->delete('labs', array('classroom_id' => $classroom_id, 'student_id' => $student_id));

  }

  public function check_username_exists($username)
  {
    $query = $this->db->get_where('users', array('username' => $username));
    if (empty($query->row_array())) {
      return false;
    } else {
      return $this->db->get_where('users', array('username' => $username))->result_array()[0]['id'];
    }
  }
}
