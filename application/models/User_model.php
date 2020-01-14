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
    $this->db->select('courses.id, course_name, course_code');
    $this->db->from('courses');
    $this->db->join('classrooms', 'courses.id = classrooms.course_id');
    $this->db->where('taught_by', $teacher_id);
    $result = $this->db->get();
    return $result->result_array();
  }

  public function get_courses_for_students($student_id)
  {
    $this->db->select('course_name, username, classrooms.id');
    $this->db->from('courses');
    $this->db->join('classrooms', 'courses.id = classrooms.course_id');
    $this->db->join('users', 'classrooms.taught_by = users.id');
    $this->db->where('student_id', $student_id);
    $this->db->where('users.role', 'teacher');
    $result = $this->db->get();
    return $result->result_array();
  }
}
