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
    $this->db->join('users', 'users.id = enrolledStudents.student_id');
    return $this->db->get_where('enrolledStudents', array('classroom_id' => $classroom_id))->result_array();
  }

  public function get_quizs_for_teacher($classroom_id) {
    $this->db->select('quizs.id as quiz_index, users.username as username');
    $this->db->from('users');
    $this->db->join('enrolledStudents', 'users.id = enrolledStudents.student_id');
    $this->db->join('classrooms', 'enrolledStudents.classroom_id = classrooms.id');
    $this->db->join('quizs', 'quizs.classroom_id = classrooms.id');
    $this->db->group_by('quizs.id');
    $this->db->where(array('enrolledStudents.classroom_id' => $classroom_id));
    return $this->db->get()->result_array();
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
    $is_enrolled = $this->db->get_where('enrolledStudents', array('classroom_id' => $classroom_id, 'student_id' => $student_id))->num_rows();

    if ($student_id !== FALSE && $is_enrolled == FALSE) {
      $data = array(
         			'classroom_id' => $classroom_id,
        			'student_id' => $student_id
      );
      $this->db->insert('enrolledStudents', $data);
      return  $this->db->affected_rows() > 0;
    } else {
      return false;
    }
  }

  public function add_quiz_from_classroom() {
    $classroom_id = $this->input->post('classroom_id');
    return array('success' => $this->db->insert('quizs', array("classroom_id" => $classroom_id)), 'quiz_index' => $this->db->insert_id());
  }

  public function get_number_of_questions($arr_quiz) {
    $result = array();
    foreach($arr_quiz as $quiz) {
      $quiz_id = $quiz['quiz_index'];
      $result[$quiz_id] = $this->db->select('count(*) as num_questions')->from('questions')->join('quizs', 'quizs.id=questions.quiz_id')
      ->where(array('questions.quiz_id' => $quiz_id))->get()->result_array()[0];
    }
    return $result;
  }

  /**
   * remove student from classroom(in the view of teacher)
   */
  public function remove_student_from_classroom() {
    // $query = $this->db->get
    $username = $this->input->post('username');
    $student_id = $this->check_username_exists($username);
    $classroom_id = $this->input->post('classroom_id');
    return $this->db->delete('enrolledStudents', array('classroom_id' => $classroom_id, 'student_id' => $student_id));

  }

  /**
   * check if the user exists by querying username
   * @return user's id if the user exists
   */
  public function check_username_exists($username)
  {
    $query = $this->db->get_where('users', array('username' => $username));
    if (empty($query->row_array())) {
      return false;
    } else {
      return $this->db->get_where('users', array('username' => $username))->result_array()[0]['id'];
    }
  }

  /**
   * get enrolled students
   * @return array of (enrolledStudentID, classroom_id, student_id)
   */
  public function get_enrolledStudent($classroom_id, $student_username) {
    $student_id = $this->check_username_exists($student_username);
    $query = $this->db->get_where('enrolledStudents', array('classroom_id' => $classroom_id, 'student_id' => $student_id));
    
    if (empty($query->row_array())) {
      return false;
    } else {
      return $this->db->get_where('enrolledStudents', array('classroom_id' => $classroom_id, 'student_id' => $student_id))->result_array()[0];
    }
  }


  /**
   * query rows of labs by selecting classroom_id and current user's id(teacher/ student)
   */
  public function get_quizs_for_user($classroom_id) {
    $user_id = $this->session->id;
    return $this->db->select('*')->from('enrolledStudents')->where(array('enrolledStudents.student_id' => $user_id, 
    'enrolledStudents.classroom_id'=>$classroom_id))->join('quizs', 'quizs.classroom_id = enrolledStudents.classroom_id')
    ->get()->result_array();
    // return $this->db->select('*')
    // ->where(array('student_id'=>$user_id, 'classroom_id' => $classroom_id))
    // ->join('labs', 'labs.enrolledStudent_id = enrolledStudents.id')->get('enrolledStudents')->result_array();
  }
}
