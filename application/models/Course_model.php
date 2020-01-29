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

  public function get_labs($classroom_id) {
    $this->db->select('users.username as username, labs.id as lab_index');
    $this->db->join('enrolledStudents', 'enrolledStudents.id = labs.enrolledStudent_id');
    $this->db->join('users', 'users.id=labs.assistant_id');
    return $this->db->get_where('labs', array('enrolledStudents.classroom_id' => $classroom_id))->result_array();
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

  /**
   * each TA dtermines a lab. each student can only be in one lab for each classroom
   * @return boolean
   */
  public function add_lab_from_classroom() {
    $ta_username = $this->input->post('ta_username');
    $student_username = $this->input->post('student_username');
    $classroom_id = $this->input->post('classroom_id');
    $ta_id = $this->check_username_exists($ta_username);//get ta's user id
    $enrolled_id = $this->get_enrolledStudent($classroom_id, $student_username)['id'];//get student's enroll id
    $already_exists = $this->is_student_in_lab($enrolled_id);//given enrolled student is registered in the lab
    $lab_index = $this->get_lab_id($ta_id, $enrolled_id);

    // print_r($already_exists);
    if($ta_id !== False && $enrolled_id !== FALSE && $already_exists === FALSE) {
      $data = array(
        'id' => $lab_index,
        'assistant_id' => $ta_id,
        'enrolledStudent_id' => $enrolled_id
      );
      $this->db->insert('labs', $data);
      return  array('success' => $this->db->affected_rows() > 0, 'lab_id' => $lab_index);
    } else {
      return false;
    }
  }

  public function is_student_in_lab($enrolled_id) {
    return !empty($this->db->get_where('labs', array('enrolledStudent_id' => $enrolled_id))->result_array());
  }

  public function get_lab_id($ta_id, $enrolled_id) {
    $query = $this->db->get_where('labs', array('assistant_id' => $ta_id, 'enrolledStudent_id'=>$enrolled_id))->result_array();
    if(!empty($query)) {
      return $query[0]['id'];
    } else {
      return $this->db->select('id')->order_by('id', 'desc')->limit(1)->get('labs')->result_array()[0]['id'];
    }
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
  public function get_labs_for_user($course_id, $classroom_id) {
    $user_id = $this->session->id;

    return $this->db->select('*')
    ->where(array('student_id'=>$user_id, 'classroom_id' => $classroom_id))
    ->join('labs', 'labs.enrolledStudent_id = enrolledStudents.id')->get('enrolledStudents')->result_array();
  }
}
