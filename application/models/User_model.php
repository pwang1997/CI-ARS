<?php
class User_model extends CI_Model
{
  public function __construct()
  {
    $this->load->database();
  }
  // Register user 
  public function register($enc_password, $role)
  {
    // User data array
    $data = array(
      'username' => $this->input->post('username'),
      'password' => $enc_password,
      'role' => $role
    );
    // Insert user
    return $this->db->insert('users', $data);
  }

  // Check username exists
  public function check_username_exists($username)
  {
    $query = $this->db->get_where('users', array('username' => $username));
    if (empty($query->row_array())) {
      return true;
    } else {
      return false;
    }
  }
  // Log user in
  public function login($username, $password)
  {
    $this->db->select('id, role');
    $this->db->where('username', $username);
    $this->db->where('password', $password);
    $result = $this->db->get('users');
    if ($result->num_rows() == 1) {
      return array('id' => $result->row(0)->id, 'role' => $result->row(0)->role);
    } else {
      return false;
    }
  }

  public function get_courses_for_teachers($teacher_id)
  {
    $this->db->select('courses.id as course_id,  classrooms.id as classroom_id, course_name, course_code, taught_by');
    $this->db->join('classrooms', 'courses.id = classrooms.course_id');
    $where = array('taught_by' => $teacher_id);
    return $this->db->get_where('courses', $where)->result_array();
  }

  public function get_courses_for_students($student_id)
  {
    $this->db->select('courses.id as course_id, classrooms.id as classroom_id, course_name, section_id, course_code, taught_by');
    $this->db->join('enrolled_students', 'enrolled_students.student_id=users.id');
    $this->db->join('classrooms', 'enrolled_students.classroom_id = classrooms.id');
    $this->db->join('courses', 'classrooms.course_id = courses.id');
    $data = array(
      'enrolled_students.student_id' => $student_id,
      'users.role' => 'student'
    );
    return $this->db->get_where('users', $data)->result_array();
  }

  public function get_username($course_list)
  {
    $result['username'] = [];
    foreach ($course_list as $course) {
      $this->db->select('username');
      $result[$course['taught_by']] = $this->db->get_where('users', array('id' => $course['taught_by']))->result_array();
    }
    return $result;
  }

  public function get_section_list($course_list)
  {
    $result = [];
    foreach ($course_list as $course) {
      $result[] = $this->db->select('section_id')->from('classrooms')->where(array('course_id' => $course['course_id']))->get()->result_array()[0];
    }
    return $result;
  }
}
