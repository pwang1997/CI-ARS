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
    $where = array(
      'course_id' => $course_id,
      'classrooms.id' => $classroom_id
    );
    $query = $this->db->select('*')->from('classrooms')->where($where)->join('courses', 'courses.id = classrooms.course_id')->get();
    $result = null;
    if ($query !== FALSE && $query->num_rows() > 0) {
      $result = $query->result_array()[0];
    }
    return $result;
  }

  public function get_enrolled_students_for_teacher($classroom_id)
  {
    $query = $this->db->select('*')->from('enrolled_students')
      ->join('users', 'users.id = enrolled_students.student_id')
      ->where(array('classroom_id' => $classroom_id))
      ->get();
    $result = $query->result_array();
    return $result;
  }

  public function get_quizs_for_teacher($classroom_id)
  {
    $query_arr = $this->db->get_where('quizs', array('classroom_id' => $classroom_id))->result_array();
    $new = array();
    foreach ($query_arr as $k => $v) {
      $new[$k]['id'] = $v['id'];
      $new[$k]['created_at'] = $v['created_at'];
    }
    return $new;
  }

  public function get_quizs_for_student($classroom_id)
  {
    $this->db->select('quizs.id as quiz_index, users.username as username')
      ->from('users')
      ->join('enrolled_students', 'users.id = enrolled_students.student_id')
      ->join('classrooms', 'enrolled_students.classroom_id = classrooms.id')
      ->join('quizs', 'quizs.classroom_id = classrooms.id')
      ->group_by(['quiz_index', 'username'])
      ->where(array('enrolled_students.classroom_id' => $classroom_id, 'users.id' => $this->session->id));
    $query = $this->db->get();

    $result = null;
    if ($query !== FALSE && $query->num_rows() > 0) {
      $result = $query->result_array();
    }
    return $result;
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
    $is_enrolled = $this->db->get_where('enrolled_students', array('classroom_id' => $classroom_id, 'student_id' => $student_id))->num_rows();

    if ($student_id !== FALSE && $is_enrolled == FALSE) {
      $data = array(
        'classroom_id' => $classroom_id,
        'student_id' => $student_id
      );
      $this->db->insert('enrolled_students', $data);
      return  $this->db->affected_rows() > 0;
    } else {
      return false;
    }
  }

  public function add_quiz_from_classroom()
  {
    $classroom_id = $this->input->post('classroom_id');
    return array('success' => $this->db->insert('quizs', array("classroom_id" => $classroom_id)), 'quiz_index' => $this->db->insert_id());
  }

  public function remove_quiz_from_classroom()
  {
    $quiz_id = $this->input->post('quiz_id');
    return $this->db->delete('quizs', array('id' => $quiz_id));
  }
  public function get_number_of_questions_for_teacher($arr_quiz)
  {
    $query_arr = array();
    $new = array();
    foreach ($arr_quiz as $quiz) {
      $quiz_id = $quiz['id'];
      $query_arr[$quiz_id] = $this->db->select('count(*) as num_questions')->from('questions')->join('quizs', 'quizs.id=questions.quiz_id')
        ->where(array('questions.quiz_id' => $quiz_id))->get()->result_array()[0];
    }

    foreach ($query_arr as $k => $v) {
      $new[$k] = $v['num_questions'];
    }
    return $new;
  }

  public function get_number_of_questions_for_student($arr_quiz)
  {
    $query_arr = array();
    $new = array();
    foreach ($arr_quiz as $quiz) {
      $quiz_id = $quiz['quiz_index'];
      $query_arr[$quiz_id] = $this->db->select('count(*) as num_questions')->from('questions')->join('quizs', 'quizs.id=questions.quiz_id')
        ->where(array('questions.quiz_id' => $quiz_id))->get()->result_array()[0];
    }

    foreach ($query_arr as $k => $v) {
      $new[$k] = $v['num_questions'];
    }
    return $new;
  }

  /**
   * remove student from classroom(in the view of teacher)
   */
  public function remove_student_from_classroom()
  {
    // $query = $this->db->get
    $username = $this->input->post('username');
    $student_id = $this->check_username_exists($username);
    $classroom_id = $this->input->post('classroom_id');
    return $this->db->delete('enrolled_students', array('classroom_id' => $classroom_id, 'student_id' => $student_id));
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
  public function get_enrolledStudent($classroom_id, $student_username)
  {
    $student_id = $this->check_username_exists($student_username);
    $query = $this->db->get_where('enrolled_students', array('classroom_id' => $classroom_id, 'student_id' => $student_id));

    if (empty($query->row_array())) {
      return false;
    } else {
      return $this->db->get_where('enrolled_students', array('classroom_id' => $classroom_id, 'student_id' => $student_id))->result_array()[0];
    }
  }


  /**
   * query rows of labs by selecting classroom_id and current user's id(teacher/ student)
   */
  public function get_quizs_for_user($classroom_id)
  {
    $user_id = $this->session->id;
    return $this->db->select('*')->from('enrolled_students')->where(array(
      'enrolled_students.student_id' => $user_id,
      'enrolled_students.classroom_id' => $classroom_id
    ))->join('quizs', 'quizs.classroom_id = enrolled_students.classroom_id')
      ->get()->result_array();
  }

  public function export_student_stat($quiz_id)
  {
    $result_questions = $this->db->select('*')->from('questions')
      ->where(array('quiz_id' => $quiz_id))->join('question_instances', 'questions.id = question_instances.question_meta_id')
      ->get()->result_array();
    $result_student_response = [];
    foreach ($result_questions as $instance) {
      $result_student_response[$instance['id']] = $this->db->select('*')->from('student_responses')
        ->where(array('question_instance_id' => $instance['id']))->join('users', 'users.id = student_responses.student_id')->get()->result_array();
    }
    $result['question'] = $result_questions;
    $result['student'] = $result_student_response;
    return $result;
  }
  public function get_questions_for_student($quizs)
  {
    $result = array();
    foreach ($quizs as $quiz) {
      $result[$quiz['quiz_index']] = $this->db->select('question_instances.id as id, quiz_id, answer, content, time_created')->from('questions')->where(array('quiz_id' => $quiz['quiz_index']))
        ->join('question_instances', 'question_instances.question_meta_id = questions.id')->get()->result_array();
    }
    return $result;
  }

  public function get_student_response($questions)
  {
    $result = [];
    foreach ($questions as $question) {
      for ($i = 0; $i < count($question); $i++) {
        $result[$question[$i]['id']] = $this->db->select('answer')->from('student_responses')->where(array('question_instance_id' => $question[$i]['id']))
          ->get()->result_array();
      }
    }
    return $result;
  }

  public function export_classroom_history($classroom_id)
  {
    //get quizs
    $quizs = $this->db->get_where('quizs', ['classroom_id' => $classroom_id])->result_array();
    if (empty($quizs)) return false;
    //get quiz instances
    $quiz_instances = [];
    //get questions' info by quiz id
    $questions = [];
    foreach ($quizs as $quiz) {
      $quiz_instances[] = $this->db->get_where('quiz_instances', ['quiz_meta_id' => $quiz['id'], 'status' => 'complete'])->result_array();
      $questions[] = $this->db->get_where('questions', ['quiz_id' => $quiz['id']])->result_array();
    }
    if (empty($quiz_instances) || empty($questions)) return false;
    //get question instances by quiz_instances id
    $question_instances = [];
    foreach ($quiz_instances as $quiz_instance) {
      foreach ($quiz_instance as $q) {
        $question_instances[] = $this->db->get_where('question_instances', array('quiz_instance_id' => $q['id']))->result_array();
      }
    }
    if (empty($question_instances)) return false;
    //get student response on question instances
    $student_responses = [];
    foreach ($question_instances as $question_instance) {
      foreach ($question_instance as $q) {
        $student_responses[$q['id']] = $this->db->select('*')->from('student_responses')->where(['question_instance_id' => $q['id']])
          ->join('users', 'users.id = student_responses.student_id')->get()->result_array();
      }
    }
    return [
      'quizs' => $quizs, 'quiz_instances' => $quiz_instances, 'questions' => $questions, 'question_instances' => $question_instances,
      'student_responses' => $student_responses
    ];
  }

  public function get_quiz_list($classroom_id)
  {
    return $this->db->get_where('quizs', ['classroom_id' => $classroom_id])->result_array();
  }

  public function get_teacher_id($classroom_id)
  {
    return $this->db->select('taught_by')->from('classrooms')->where(['id' => $classroom_id])->get()->result_array()[0]['taught_by'];
  }



  public function get_quiz_instance_list($quiz_list, $teacher_id)
  {
    $quiz_instance_list = [];
    foreach ($quiz_list as $quiz) {
      $quiz_instance_list[] = $this->db->select('*')->from('quiz_instances')->where([
        'teacher_id' => $teacher_id, 'quiz_meta_id' => $quiz['id'],
        'status' => 'complete'
      ])
        ->get()->result_array();
    }
    return $quiz_instance_list;
  }

  public function get_question_instance_list($question_list)
  {
    $row = array();
    foreach ($question_list as $question) {
      foreach ($question as $q) {
        $this->db->select('time_created,quiz_instance_id, users.id as user_id, username, questions.answer as answer,student_responses.answer as student_answer, student_responses.time_answered as time_answered')
          ->from('questions')->join('question_instances', 'questions.id = question_instances.question_meta_id')
          ->join('quiz_instances', 'question_instances.quiz_instance_id = quiz_instances.id')
          ->join('student_responses', 'question_instances.id = student_responses.question_instance_id')
          ->join('users', 'users.id = student_responses.student_id')
          ->where(['quiz_instance_id' => $q['id'], 'quiz_instances.status' => 'complete']);
        $result = $this->db->get()->result_array();
        if (!empty($result)) {
          $row[$q['id']][] = $result;
        }
      }
    }
    return $row;
  }

  public function getQuiz($quiz_index)
  {
    return $this->db->get_where('quizs', array('id' => $quiz_index))->result_array();
  }

  public function getQuestions($quiz_index)
  {
    $quiz_id = $this->getQuiz($quiz_index)[0]['id'];
    $query = $this->db->select('*')->from('questions')->where(array('quiz_id' => $quiz_id))->get()->result_array();
    $row = array();
    foreach ($query as $result) {
      $row[$result['id']] = $result;
    }
    return $row;
  }

  public function get_student_response_list_review_history($question_instance_list)
  {
    $row = array();
    foreach ($question_instance_list as $question_instances) {
      foreach ($question_instances as $question_instance) {
        //get last record of each instance for each user
        $this->db->select('MAX(id) as id');
        $this->db->from('student_responses')
          ->where(['question_instance_id' => $question_instance['id']]);
        $this->db->group_by(['student_id']);
        $subquery = $this->db->get_compiled_select();


        $query = $this->db->select('users.username, student_responses.answer,student_responses.time_answered')
          ->from('(' . $subquery . ') a')
          ->join('student_responses', 'student_responses.id= a.id')
          ->join('users', 'student_responses.student_id= users.id')
          ->group_by(['student_responses.student_id','student_responses.answer','student_responses.time_answered'])->get();

        $result = null;
        if ($query !== FALSE && $query->num_rows() > 0) {
          $result = $query->result_array();
        }

        if (!empty($result)) {
          $arr_student_response = array();
          foreach ($result as $student_response) {
            $arr_student_response[$student_response['student_id']] = $student_response;
          }

          $row[$question_instance['id']] = $arr_student_response;
        }
      }
    }
    return $row;
  }

  public function get_question_instance_list_review_history($question_list)
  {
    $row = array();
    foreach ($question_list as $question) {
      $query = $this->db->select('question_instances.id, question_instances.question_meta_id')
        ->from('questions')->join('question_instances', 'questions.id = question_instances.question_meta_id')
        ->join('quiz_instances', 'question_instances.quiz_instance_id = quiz_instances.id')
        ->where(['quiz_instance_id' => $question['id'], 'quiz_instances.status' => 'complete'])->get();

      $result = null;
      if ($query !== FALSE && $query->num_rows() > 0) {
        $result = $query->result_array();
      }
      if (!empty($result)) {
        $row[$question['id']] = $result;
      }
    }
    return $row;
  }

  public function get_quiz_instance_list_review_history($quiz_index, $teacher_id)
  {
    $result = $this->db->get_where('quiz_instances', 
    array('quiz_meta_id' => $quiz_index, 'teacher_id' => $teacher_id, 'status'=>'complete'))
      ->result_array();
    return $result;
  }

  public function get_student_list($quiz_id)
  {
    return $this->db->select('enrolled_students.student_id, users.username')->from('quizs')
      ->join('enrolled_students', 'quizs.classroom_id = enrolled_students.classroom_id')
      ->join('users', 'enrolled_students.student_id = users.id')->where(['quizs.id' => $quiz_id])->get()->result_array();
  }
}
